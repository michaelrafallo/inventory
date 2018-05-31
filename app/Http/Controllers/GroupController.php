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
use App\Permission;

class GroupController extends Controller
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
    protected $permission;


    public function __construct(User $user, UserMeta $usermeta, Post $post, PostMeta $postmeta, Setting $setting, Request $request, Permission $permission)
    {
        $this->user     = $user;
        $this->usermeta = $usermeta;
        $this->post     = $post;
        $this->postmeta = $postmeta;
        $this->setting  = $setting;
        $this->request  = $request;
        $this->permission = $permission;

        $this->middleware(function ($request, $next) {
            $this->user_id = Auth::user()->id;
            return $next($request);
        });
    }

    //--------------------------------------------------------------------------

    public function index()
    {

        parse_str( query_vars(), $search );

        $data['rows'] = $this->post
                             ->search($search)
                             ->where('post_type', 'group')
                             ->orderBy('id', 'DESC')
                             ->paginate(15);

        $data['count'] = $this->post
                              ->search($search)
                              ->where('post_type', 'group')
                              ->count();

        $data['all'] = $this->post->where('post_type', 'group')->count();

        $data['trashed'] = $this->post->withTrashed()
                                      ->where('post_type', 'group')
                                      ->where('deleted_at', '<>', '0000-00-00')
                                      ->count();

        return view('app.groups.index', $data);
    }

    //--------------------------------------------------------------------------

    public function add()
    {
        if( Input::get('_token') )
        {
            $rules = [
                'name' => 'required',
            ];    

            $validator = Validator::make(Input::all(), $rules);

            if( ! $validator->passes() ) {
                return Redirect::route('app.groups.add')
                               ->withErrors($validator)
                               ->withInput(); 
            }

            $post = $this->post;

            $post->post_author  = $this->user_id;
            $post->post_title   = Input::get('name');
            $post->post_name    = text_to_slug(Input::get('name'));
            $post->post_type    = 'group';
            $post->post_status  = 'actived';

            if( $post->save() ) {
                return Redirect::route('app.groups.edit', $post->id)
                               ->with('success','New group has been added!');
            } 
        }

        return view('app.groups.add');
    }

    //--------------------------------------------------------------------------

    public function edit($id='')
    {
        $data['info'] = $post = $this->post->find($id);

        if( Input::get('_token') )
        {
            $rules = [
                'name' => 'required',
            ];    

            $validator = Validator::make(Input::all(), $rules);

            if( ! $validator->passes() ) {

                if( $this->request->ajax() ) {
                    $response = array('error' => true, 'details' => $validator->errors());
                    return json_encode( $response );              
                }

                return Redirect::route('app.groups.edit', $id)
                               ->withErrors($validator)
                               ->withInput(); 
            }

            $post->post_author  = $this->user_id;
            $post->post_title   = Input::get('name');
            $post->post_status  = Input::get('status', 'inactived');
            $post->updated_at   = date('Y-m-d H:i:s');

            if( $post->save() ) {
                return Redirect::route('app.groups.edit', $id)
                               ->with('success','Group has been updated!');
            } 
        }

        return view('app.groups.edit', $data);
    }

    //--------------------------------------------------------------------------

    public function delete($id)
    {
        $this->post->findOrFail($id)->delete();
        return Redirect::route('app.groups.index', query_vars())
                       ->with('success','Selected group has been move to trashed!');
    }

    //--------------------------------------------------------------------------

    public function restore($id)
    {   
        $post = $this->post->withTrashed()->findOrFail($id);
        $post->restore();
        return Redirect::route('app.groups.index', ['type' => 'trash'])
                       ->with('success','Selected group has been restored!');
    }

    //--------------------------------------------------------------------------

  
    public function destroy($id)
    {   
        $this->postmeta->where('post_id', $id)->delete(); 
        $post = $this->post->withTrashed()->find($id);
        $post->forceDelete();

        return Redirect::route('app.groups.index', ['type' => 'trash'])
                       ->with('success','Selected group has been deleted permanently!');
    }

    //--------------------------------------------------------------------------
    
    public function permissions($group_id)
    {

        $data['info'] = $info = $this->post->find($group_id);  
        $data['modules'] = $this->permission->roles($group_id); 

        if ( Input::get('_token') )
        {
            $info->post_content = json_encode(Input::except('_token'));
            $info->save();

            return Redirect::back()
                           ->with('success','Permissions saved.');

        }

        $data['permission'] = $this->permission;

        return view('app.groups.permissions', $data);
    }

    //--------------------------------------------------------------------------

}
