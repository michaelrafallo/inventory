<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator, Redirect, Input, Auth, Hash, Session, URL, Mail, Config, PDF;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\User;
use App\UserMeta;
use App\Post;
use App\PostMeta;
use App\Setting;
use App\Inventory;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    protected $user;
    protected $usermeta;
    protected $post;
    protected $postmeta;
    protected $setting;


    public function __construct(User $user, UserMeta $usermeta, Post $post, PostMeta $postmeta, Setting $setting, Inventory $inventory, Request $request)
    {
        $this->user     = $user;
        $this->usermeta = $usermeta;
        $this->post     = $post;
        $this->postmeta = $postmeta;
        $this->setting  = $setting;
        $this->request  = $request;
        $this->inventory  = $inventory;

        $this->post_type = 'product';
        $this->view      = 'app.inventory';
        $this->single    = 'Product';
        $this->label     = 'Products';

        $this->middleware(function ($request, $next) {
            $this->user_id = Auth::user()->id;
            return $next($request);
        });
    }

    //--------------------------------------------------------------------------

    public function index()
    {
        $data['single'] = $this->single;                                      
        $data['label']  = $this->label; 
        $data['view']   = $this->view;
        $data['post']   = $this->post;
        $data['inventory'] = $this->inventory;
        $data['company'] = $this->setting->get_setting('company');

        parse_str( query_vars(), $search );

        $search['from'] = $from = Input::get('from', date('01-01-Y'));
        $search['to']   = $to   = Input::get('to', date('m-d-Y'));
        
        $data['date_from']  = date('F d, Y', strtotime(date_formatted_b($from)));
        $data['date_to']    = date('F d, Y', strtotime(date_formatted_b($to)));

        $queries = array();
        $selects = array();
        foreach(Input::all() as $input_k => $input_v) {            
            if($input_v) {
                if( in_array($input_k, $selects) ) {
                   $queries[] = $input_k;
                }                
            }
        }

        $rows = $this->post->search($search, $selects, $queries)
                           ->where('post_type', $this->post_type)
                           ->orderBy(Input::get('sort', 'id'), Input::get('order', 'DESC'));

        $data['count'] = $this->post
                              ->search($search, $selects, $queries)
                              ->where('post_type', $this->post_type)
                              ->count();

        $data['all'] = $this->post->where('post_type', $this->post_type)->count();

        $data['trashed'] = $this->post->withTrashed()
                                      ->where('post_type', $this->post_type)
                                      ->where('deleted_at', '<>', '0000-00-00')
                                      ->count();

 

        if( Input::get('preview') ) {

            $data['from'] = date_formatted_b($from);
            $data['to']   = date_formatted_b($to);

            $data['rows'] = $rows->get();
            
            if( $company_id = Input::get('company', $data['company']) ) {
                $data['info'] = $info = $this->post->find( $company_id );
                foreach ($info->postmetas as $postmeta) {
                    $data['info'][$postmeta->meta_key] = $postmeta->meta_value;
                }                
            }
        // return view($this->view.'.reports.inventory', $data);
    
            $pdf = PDF::loadView($this->view.'.reports.inventory', $data);
            return $pdf->stream($this->post_type.'.pdf');            
        }


        $data['rows'] = $rows->paginate(Input::get('rows', 15));

        return view($this->view.'.index', $data);
    }

    //--------------------------------------------------------------------------

    public function stocks()
    {
        $data['view'] = $this->view;
        $data['post'] = $this->post;
        $data['company'] = $this->setting->get_setting('company');

        parse_str( query_vars(), $search );

        $search['from'] = $from = Input::get('from', date('01-01-Y'));
        $search['to']   = $to   = Input::get('to', date('m-d-Y'));
        
        $data['date_from']  = date('F d, Y', strtotime(date_formatted_b($from)));
        $data['date_to']    = date('F d, Y', strtotime(date_formatted_b($to)));

        $rows = $this->inventory->search($search);

        $data['count'] = $this->inventory
                              ->search($search)
                              ->count();

        if( Input::get('preview') ) {

            $data['rows'] = $rows->get();
            
            if( $company_id = Input::get('company', $data['company']) ) {
                $data['info'] = $info = $this->post->find( $company_id );
                foreach ($info->postmetas as $postmeta) {
                    $data['info'][$postmeta->meta_key] = $postmeta->meta_value;
                }                
            }


            $pdf = PDF::loadView($this->view.'.reports.preview', $data);
            return $pdf->stream($this->post_type.'.pdf');            
        }

         $data['rows'] = $rows->orderBy('id', 'DESC')->paginate(15);

        return view($this->view.'.stocks', $data);
    }

    //--------------------------------------------------------------------------

    public function add()
    {
        $data['single'] = $this->single;                                      
        $data['label']  = $this->label; 
        $data['view']   = $this->view;
        $data['post']   = $this->post;
        $data['setting']   = $this->setting;

        if( Input::get('_token') ) {
            $rules = [
                'name'         => 'required',
                'normal_price' => 'required',
                'sales_price'  => 'required',
                'quantity'     => 'required',
                'unit_of_measure' => 'required',
            ];    

            $validator = Validator::make(Input::all(), $rules);

            if( ! $validator->passes() ) {
                return Redirect::route($this->view.'.add')
                               ->withErrors($validator)
                               ->withInput(); 
            }

            $inputs = Input::all();

            unset($inputs['_token']);

            $post = $this->post;

            $post->post_author  = $this->user_id;             
            $post->post_title   = Input::get('name');
            $post->post_type    = $this->post_type;
            $post->post_status  = 'actived';

            if( $post->save() ) {

                foreach ($inputs as $meta_key => $meta_val) {
                    $this->postmeta->update_meta($post->id, $meta_key, $meta_val);
                }

                return Redirect::route($this->view.'.edit', [$post->id, 'tab' => 1])
                               ->with('success', 'New '. strtolower($this->single).' has been added!');
            } 
        }

        return view($this->view.'.add', $data);
    }

    //--------------------------------------------------------------------------

    public function edit($id='')
    {

        $data['single'] = $this->single;                                      
        $data['label']  = $this->label; 
        $data['view']   = $this->view;
        $data['post']   = $this->post;
        
        $data['tab'] = Input::get('tab');

        $data['info'] = $info = $this->post->find( $id );
        foreach ($info->postmetas as $postmeta) {
            $data['info'][$postmeta->meta_key] = $postmeta->meta_value;
        }

        $data['post'] = $this->post;

        if( Input::get('_token') )
        {   


            if( Input::get('tab') == 1 ) {
                $rules = [
                    'name'         => 'required',
                    'normal_price' => 'required',
                    'sales_price'  => 'required',
                    'quantity'     => 'required',
                    'unit_of_measure' => 'required',
                ];      

                $validator = Validator::make(Input::all(), $rules);

                if( ! $validator->passes() ) {
                    
                    if( $this->request->ajax() ) {
                        $response = array('error' => true, 'details' => $validator->errors());
                        return json_encode( $response );              
                    }

                    return Redirect::route($this->view.'.edit', $id)
                                   ->withErrors($validator)
                                   ->withInput(); 
                }
            }

            $inputs = Input::except(['_token', 'status', 'tab', 'post_type', 'id']);

            $post = $this->post->find( $id );

            if( Input::get('tab') == 1) 
                $post->post_title   = Input::get('name');


            $post->post_status  = Input::get('status', 'inactived');                
            $post->post_author  = $this->user_id;

            if( Input::get('tab') != 1) {

                if( Input::get('suppliers') ) {
                    foreach ($inputs['suppliers'] as $row) {
                        if( $row['supplier'] )
                        $suppliers[$row['supplier']] = $row; 
                    }
                    $inputs['suppliers'] = $suppliers;                    
                }
                                
                $post->post_content = json_encode($suppliers);        
            }

            $post->updated_at   = date('Y-m-d H:i:s');

            if( $post->save() ) {
                
                            
                if( Input::hasFile('file') ) {
                    $pic = upload_image(Input::file('file'), 'products', $info->picture, 'compress');
                    $inputs['picture'] = $pic;       
                }

                foreach ($inputs as $meta_key => $meta_val) {
                    $this->postmeta->update_meta($id, $meta_key, array_to_json($meta_val));
                }


                if( $this->request->ajax() ) {

                    $data['info'] = $info = $this->post->find( $id );
                    foreach ($info->postmetas as $postmeta) {
                        $data['info'][$postmeta->meta_key] = $postmeta->meta_value;
                    }

                    return view($this->view.'.tabs.details', $data);
                }

                return Redirect::route($this->view.'.edit', $id)
                               ->with('success', $this->single.' has been updated!');
            } 
        }

        return view($this->view.'.edit', $data);
    }

    //--------------------------------------------------------------------------

    public function product_category()
    {
        $data['single'] = $this->single;                                      
        $data['label']  = $this->label; 
        $data['view']   = $this->view;

        $pid = Input::get('pid', 0);
            
        if( $pid ) {
            $data['info'] = $info = $this->post->find( $pid );
            foreach ($info->postmetas as $postmeta) {
                $data['info'][$postmeta->meta_key] = $postmeta->meta_value;
            }            
        }

        parse_str( query_vars(), $search );

        $data['rows'] = $this->post
                             ->where('post_type', 'product-category')
                             ->orderBy('id', 'ASC')
                             ->get();

        if( Input::get('_token') )
        {
            $rules = [
                'name' => 'required'
            ];    

            $validator = Validator::make(Input::all(), $rules);

            if( ! $validator->passes() ) {
                return Redirect::route($this->view.'.category')
                               ->withErrors($validator)
                               ->withInput(); 
            }

            $post = $this->post->find( $pid );

            if( ! $post ) {
                $post = $this->post;
            }

            $post->post_author  = $this->user_id;
            $post->post_title   = Input::get('name');
            $post->post_status  = Input::get('status', 'inactived');
            $post->post_type    = 'product-category';
            $post->updated_at   = date('Y-m-d H:i:s');

            if( $post->save() ) {

                $msg = 'New product category has been added!';

                if( $pid ) {
                    $msg = 'Product category has been updated!';                
                }

                return Redirect::route($this->view.'.category')
                               ->with('success', $msg);
            } 
        }

        return view($this->view.'.category', $data);
    }

    //--------------------------------------------------------------------------

    public function terms()
    {
        $data['single'] = $this->single;                                      
        $data['label']  = $this->label; 
        $data['view']   = $this->view;

        $pid = Input::get('pid', 0);
            
        if( $pid ) {
            $data['info'] = $info = $this->post->find( $pid );
            foreach ($info->postmetas as $postmeta) {
                $data['info'][$postmeta->meta_key] = $postmeta->meta_value;
            }            
        }

        parse_str( query_vars(), $search );

        $data['rows'] = $this->post
                             ->where('post_type', 'terms')
                             ->orderBy('id', 'ASC')
                             ->get();

        if( Input::get('_token') )
        {
            $rules = [
                'name' => 'required',
                'days' => 'required'
            ];    

            $validator = Validator::make(Input::all(), $rules);

            if( ! $validator->passes() ) {
                return Redirect::route($this->view.'.terms')
                               ->withErrors($validator)
                               ->withInput(); 
            }

            $post = $this->post->find( $pid );

            if( ! $post ) {
                $post = $this->post;
            }

            $post->post_author  = $this->user_id;
            $post->post_title   = Input::get('name');
            $post->post_name    = Input::get('days');
            $post->post_status  = Input::get('status', 'inactived');
            $post->post_type    = 'terms';
            $post->updated_at   = date('Y-m-d H:i:s');

            if( $post->save() ) {

                $msg = 'New product terms has been added!';

                if( $pid ) {
                    $msg = 'Product terms has been updated!';                
                }

                return Redirect::route($this->view.'.terms')
                               ->with('success', $msg);
            } 
        }

        return view($this->view.'.terms', $data);
    }

    //--------------------------------------------------------------------------

    public function delete($id)
    {
        $this->post->findOrFail($id)->delete();
        
        $msg = 'Selected '.strtolower($this->single).' has been move to trashed!';

        return Redirect::back()
                       ->with('success', $msg);
    }

    //--------------------------------------------------------------------------

    public function restore($id)
    {   
        $post = $this->post->withTrashed()->findOrFail($id);
        $post->restore();

        $msg = 'Selected '.strtolower($this->single).' has been restored!';

        return Redirect::back()
                       ->with('success', $msg);
    }

    //--------------------------------------------------------------------------
  
    public function destroy($id)
    {   
        $this->postmeta->where('post_id', $id)->delete(); 
        $post = $this->post->withTrashed()->find($id);
        $post->forceDelete();

        $msg = 'Selected '.strtolower($this->single).' has been deleted permanently!';

        return Redirect::back()
                       ->with('success', $msg);
    }

    //--------------------------------------------------------------------------
    
}
