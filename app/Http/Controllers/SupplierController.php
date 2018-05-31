<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator, Redirect, Input, Auth, Hash, Session, URL, Mail, Config;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\User;
use App\UserMeta;
use App\Post;
use App\PostMeta;
use App\Setting;

class SupplierController extends Controller
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


    public function __construct(User $user, UserMeta $usermeta, Post $post, PostMeta $postmeta, Setting $setting, Request $request)
    {
        $this->user     = $user;
        $this->usermeta = $usermeta;
        $this->post     = $post;
        $this->postmeta = $postmeta;
        $this->setting  = $setting;
        $this->request  = $request;


        $this->post_type = 'supplier';
        $this->view      = 'app.suppliers';
        $this->single    = 'Supplier';
        $this->label     = 'Suppliers';

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
        
        parse_str( query_vars(), $search );

        $data['rows'] = $this->post
                             ->search($search)
                             ->where('post_type', $this->post_type)
                             ->orderBy(Input::get('sort', 'id'), Input::get('order', 'DESC'))
                             ->paginate(Input::get('rows', 15));

        $data['count'] = $this->post
                              ->search($search)
                              ->where('post_type', $this->post_type)
                              ->count();

        $data['all'] = $this->post->where('post_type', $this->post_type)->count();

        $data['trashed'] = $this->post->withTrashed()
                                      ->where('post_type', $this->post_type)
                                      ->where('deleted_at', '<>', '0000-00-00')
                                      ->count();
         
        return view($this->view.'.index', $data);
    }

    //--------------------------------------------------------------------------

    public function add()
    {
        $data['single'] = $this->single;                                      
        $data['label']  = $this->label; 
        $data['view']   = $this->view;

        if( Input::get('_token') ) {
            $rules = [
                'name' => 'required',
            ];    

            $validator = Validator::make(Input::all(), $rules);

            if( ! $validator->passes() ) {
                return Redirect::route($this->view.'.add')
                               ->withErrors($validator)
                               ->withInput(); 
            }

            $inputs = Input::all();

            $inputs = Input::except(['_token', 'name', 'status']);

            $post = $this->post;

            $post->post_author  = $this->user_id;
            $post->post_content = json_encode($inputs);                
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

        $data['tab'] = Input::get('tab', 1);

        $data['info'] = $info = $this->post->find( $id );
        foreach ($info->postmetas as $postmeta) {
            $data['info'][$postmeta->meta_key] = $postmeta->meta_value;
        }

        $data['post'] = $this->post;

        $data['credit_balance'] = $this->post
                                        ->select('posts.*', 
                                           'm1.meta_value as balance'
                                        )->from('posts')
                                         ->join('postmeta AS m1', function ($join) {
                                            $join->on('posts.id', '=', 'm1.post_id')
                                                 ->where('m1.meta_key', '=', 'balance');
                                        })->where('posts.post_type', 'purchase-order')
                                          ->where('posts.parent', $id)
                                          ->get()
                                          ->sum('balance');

        if( Input::get('view') ) {
            return json_encode( $info );              
        }

        if( Input::get('_token') ) {

            $rules = [
                'name' => 'required',
            ];    

            $inputs = Input::except(['_token', 'status', 'tab', 'post_type', 'id']);

            $validator = Validator::make(Input::all(), $rules);

            if( ! $validator->passes() ) {
    
                if( $this->request->ajax() ) {
                    $response = array('error' => true, 'details' => $validator->errors());
                    return json_encode( $response );              
                }

                return Redirect::route($this->view.'.edit', [$id, query_vars()])
                               ->withErrors($validator)
                               ->withInput(); 
            }

            if( $this->request->ajax() ) {

                if( Input::get('name') ) {
                    $post = $this->post->find( $id );
                } else {
                    $pid = Input::get('id');
                    $post = $this->post->find( $pid );
                    if( ! $post ) {
                        $post = $this->post;
                    }                    
                }

            } else {
                $post = $this->post->find( $id );
            }


            $post->post_author  = $this->user_id;
            $post->post_title   = Input::get('name');
            $post->post_content = json_encode($inputs);        
            $post->post_type    = Input::get('post_type', $this->post_type);
            $post->parent       = $id;
            $post->post_status  = Input::get('status', 'inactived');
            $post->created_at   = date('Y-m-d H:i:s');            
            $post->updated_at   = date('Y-m-d H:i:s');

            if( $post->save() ) {
                foreach ($inputs as $meta_key => $meta_val) {
                    $this->postmeta->update_meta($post->id, $meta_key, $meta_val);
                }

                if( $this->request->ajax() ) {

                    $data['info'] = $info = $this->post->find( $id );
                    foreach ($info->postmetas as $postmeta) {
                        $data['info'][$postmeta->meta_key] = $postmeta->meta_value;
                    }
                    
                    return view($this->view.'.tabs.details', $data);
                }

                return Redirect::route($this->view.'.edit', [$id, query_vars()])
                               ->with('success', $this->single.' has been updated!');

            } 
        }

        return view($this->view.'.edit', $data);
    }

    //--------------------------------------------------------------------------

    public function product()
    {
        $data['single'] = $this->single;                                      
        $data['label']  = $this->label; 
        $data['view']   = $this->view;

        $data['tab'] = Input::get('tab', 1);
        
        $sid = (int)Input::get('sid');
        $id  = Input::get('pid') ? Input::get('pid') : Input::get('item');


        if( $this->request->method() == 'POST' ) {
            $rules = [
                'item'           => 'required',
                'supplier_price' => 'required',
            ];    

            $validator = Validator::make(Input::all(), $rules);

            if( ! $validator->passes() ) {
                $response = array('error' => true, 'details' => $validator->errors());
                return json_encode( $response ); 
            }                  
        }

        $data['info'] = $info = $this->post->find( $id );
        foreach ($info->postmetas as $postmeta) {
            $data['info'][$postmeta->meta_key] = $postmeta->meta_value;
        }

        $supplier = json_decode($info->suppliers, true);

        $data['info']['pid']            = $id;
        $data['info']['item']           = $id;
        $data['info']['code']           = $supplier[$sid]['code'];
        $data['info']['supplier_price'] = $supplier[$sid]['supplier_price'];


        if( $this->request->method() == 'GET' ) {
            return json_encode($data['info']);
        }

        $data['post'] = $this->post;

        $data['credit_balance'] = $this->post->select('posts.*', 
                                               'm1.meta_value as balance'
                                            )->from('posts')
                                             ->join('postmeta AS m1', function ($join) {
                                                $join->on('posts.id', '=', 'm1.post_id')
                                                     ->where('m1.meta_key', '=', 'balance');
                                            })->where('posts.post_type', 'purchase-order')
                                              ->where('posts.parent', $sid)
                                              ->get()
                                              ->sum('balance');



        $supplier[$sid] = array(
            'supplier'       => (string)$sid,
            'code'           => Input::get('code'),
            'supplier_price' => Input::get('supplier_price'),
        );


        $this->postmeta->update_meta($id, 'suppliers', json_encode($supplier));

        $data['info'] = $info = $this->post->find( $sid );
        foreach ($info->postmetas as $postmeta) {
            $data['info'][$postmeta->meta_key] = $postmeta->meta_value;
        }

        return view($this->view.'.tabs.details', $data);
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
