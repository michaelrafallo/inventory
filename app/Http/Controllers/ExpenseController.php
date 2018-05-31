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

class ExpenseController extends Controller
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
    protected $request;

    public function __construct(User $user, UserMeta $usermeta, Post $post, PostMeta $postmeta, Setting $setting, Request $request)
    {
        $this->user     = $user;
        $this->usermeta = $usermeta;
        $this->post     = $post;
        $this->postmeta = $postmeta;
        $this->setting  = $setting;
        $this->request  = $request;

        $this->post_type = 'expense';
        $this->view      = 'app.expenses';
        $this->single    = 'Expense';
        $this->label     = 'Expenses';

        $this->middleware(function ($request, $next) {
            $this->user_id = Auth::user()->id;
            return $next($request);
        });
    }

    //--------------------------------------------------------------------------

    public function index()
    {
        $data['single']  = $this->single;                                      
        $data['label']   = $this->label; 
        $data['view']    = $this->view;
        
        $data['company'] = $this->setting->get_setting('company');

        parse_str( query_vars(), $search );

        $queries = array('date');
        $selects = array();
        foreach(Input::all() as $input_k => $input_v) {            
            if($input_v) {
                if( in_array($input_k, $selects) ) {
                   $queries[] = $input_k;
                }                
            }
        }

        $search['from'] = $from = Input::get('from', date('01-01-Y'));
        $search['to']   = $to   = Input::get('to', date('m-d-Y'));
        
        $data['date_from']  = date('F d, Y', strtotime(date_formatted_b($from)));
        $data['date_to']    = date('F d, Y', strtotime(date_formatted_b($to)));

        $rows = $this->post
                     ->search($search, $selects, $queries)
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


         $data['total_exp'] = $this->post
                                   ->search($search, $selects, $queries)
                                   ->where('post_type', $this->post_type)
                                   ->get()
                                   ->sum('post_name');

        $data['post'] = $this->post;                                            

        if( Input::get('preview') ) {

             $data['rows'] = $rows->get();

            if( $company_id = Input::get('company', $data['company']) ) {
                $data['info'] = $info = $this->post->find( $company_id );
                foreach ($info->postmetas as $postmeta) {
                    $data['info'][$postmeta->meta_key] = $postmeta->meta_value;
                }                
            }


            $pdf = PDF::loadView($this->view.'.preview', $data);
            return $pdf->stream($this->post_type.'.pdf');              
        }

        $data['rows'] = $rows->paginate(Input::get('rows', 15));

        return view($this->view.'.index', $data);
    }

    //--------------------------------------------------------------------------

    public function add()
    {
        $data['single'] = $this->single;                                      
        $data['label']  = $this->label; 
        $data['view']   = $this->view;

        if( Input::get('_token') )
        {
            $rules = [
                'name'    => 'required',
                'amount'  => 'required',
                'date'    => 'required',
            ];    

            $validator = Validator::make(Input::all(), $rules);

            if( ! $validator->passes() ) {
                return Redirect::route($this->view.'.add')
                               ->withErrors($validator)
                               ->withInput(); 
            }

            $inputs = Input::except(['name', '_token']);
        
            $inputs['date'] = date_formatted_b($inputs['date']);

            $post = $this->post;

            $post->post_author  = $this->user_id;
            $post->post_content = json_encode($inputs);                
            $post->post_title   = Input::get('name');
            $post->post_name    = Input::get('amount');
            $post->post_type    = $this->post_type;
            $post->post_status  = 'actived';

            if( $post->save() ) {

                foreach ($inputs as $meta_key => $meta_val) {
                    $this->postmeta->update_meta($post->id, $meta_key, $meta_val);
                }

                return Redirect::route($this->view.'.edit', $post->id)
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

        $data['info'] = $info = $this->post->find( $id );
        foreach ($info->postmetas as $postmeta) {
            $data['info'][$postmeta->meta_key] = $postmeta->meta_value;
        }

        if( Input::get('_token') )
        {
            $rules = [
                'name'    => 'required',
                'amount'  => 'required',
                'date'    => 'required',
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

            $inputs = Input::except(['name', '_token', 'status']);

            $inputs['date'] = date_formatted_b($inputs['date']);

            $post = $this->post->find( $id );

            $post->post_author  = $this->user_id;
            $post->post_title   = Input::get('name');
            $post->post_name    = Input::get('amount');
            $post->post_content = json_encode($inputs);     
            $post->post_status  = Input::get('status', 'inactived');
            $post->updated_at   = date('Y-m-d H:i:s');

            if( $post->save() ) {

                foreach ($inputs as $meta_key => $meta_val) {
                    $this->postmeta->update_meta($id, $meta_key, $meta_val);
                }

                return Redirect::route($this->view.'.edit', $id)
                               ->with('success', $this->single.' has been updated!');
            } 
        }

        return view($this->view.'.edit', $data);
    }

    //--------------------------------------------------------------------------

    public function delete($id)
    {
        $this->post->findOrFail($id)->delete();
        
        $msg = 'Selected '.strtolower($this->single).' has been move to trashed!';

        return Redirect::route($this->view.'.index', query_vars())
                       ->with('success', $msg);
    }

    //--------------------------------------------------------------------------

    public function restore($id)
    {   
        $post = $this->post->withTrashed()->findOrFail($id);
        $post->restore();

        $msg = 'Selected '.strtolower($this->single).' has been restored!';

        return Redirect::route($this->view.'.index', ['type' => 'trash'])
                       ->with('success', $msg);
    }

    //--------------------------------------------------------------------------

  
    public function destroy($id)
    {   
        $this->postmeta->where('post_id', $id)->delete(); 
        $post = $this->post->withTrashed()->find($id);
        $post->forceDelete();

        $msg = 'Selected '.strtolower($this->single).' has been deleted permanently!';

        return Redirect::route($this->view.'.index', ['type' => 'trash'])
                       ->with('success', $msg);
    }

    //--------------------------------------------------------------------------
    
}
