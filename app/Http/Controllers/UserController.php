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

class UserController extends Controller
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

        $this->middleware(function ($request, $next) {
            $this->user_id = Auth::user()->id;
            return $next($request);
        });
    }

    //--------------------------------------------------------------------------

    public function index()
    {
        parse_str( query_vars(), $search );

        $data['rows'] = $this->user
                             ->search($search)
                             ->orderBy(Input::get('sort', 'fullname'), Input::get('order', 'DESC'))
                             ->paginate(Input::get('rows', 15));

        $data['count'] = $this->user
                              ->search($search)
                              ->count();

        $data['all'] = $this->user->count();

        $data['trashed'] = $this->user->withTrashed()
                                      ->where('deleted_at', '<>', '0000-00-00')
                                      ->count();
        $data['post'] = $this->post;

        return view('app.users.index', $data);
    }

    //--------------------------------------------------------------------------

    public function profile()
    {
        $data['info'] = $info = $this->user->find( $this->user_id );
        foreach ($info->usermetas as $usermeta) {
            $data['info'][$usermeta->meta_key] = $usermeta->meta_value;
        }

        $user = $this->user->find( $this->user_id );
        
        $profile_picture = $data['info']->profile_picture;

        if( Input::get('op') == 2) {
            
            $rules = [
                'new_password'              => 'required|min:4|max:64|confirmed',
                'new_password_confirmation' => 'required|min:4',
            ];    

            $validator = Validator::make(Input::all(), $rules);

            if( ! $validator->passes() ) {
                
                if( $this->request->ajax() ) {
                    $response = array('error' => true, 'details' => $validator->errors());
                    return json_encode( $response );              
                }

                return Redirect::route('app.users.profile')
                               ->withErrors($validator)
                               ->withInput(); 
            }

            $user->password = Hash::make( Input::get('new_password') );
                                
            if( $user->save() ) {

                return Redirect::route('app.users.profile')
                               ->with('success','Password has been updated!');
            } 
        }

        if( Input::get('op') == 1) {
            
            $rules = [
                'email'          => 'required|email|max:64|unique:users,email,'.$this->user_id.',id',
                'firstname'      => 'required|max:25',
                'lastname'       => 'required|max:25',
            ];    

            $validator = Validator::make(Input::all(), $rules);

            if( ! $validator->passes() ) {

                if( $this->request->ajax() ) {
                    $response = array('error' => true, 'details' => $validator->errors());
                    return json_encode( $response );              
                }

                return Redirect::route('app.users.profile')
                               ->withErrors($validator)
                               ->withInput(); 
            }

            $user->fill( Input::all() );   

            $user->fullname = Input::get('firstname').' '.Input::get('lastname');   

            if( Input::hasFile('file') ) {
                $pic = upload_image(Input::file('file'), 'users/'.$this->user_id, $profile_picture, 'compress');
                $this->usermeta->update_meta($this->user_id, 'profile_picture', $pic);       
            }

            if( $user->save() ) {
                return Redirect::route('app.users.profile')
                               ->with('success','Your profile has been updated!');
            } 

        }

        return view('app.users.profile', $data);
    }

    //--------------------------------------------------------------------------

    public function add()
    {
        if( Input::get('_token') ) {
            $rules = [
                'email'      => 'required|email|max:64|unique:users,email',
                'firstname'  => 'required|max:25',
                'lastname'   => 'required|max:25',
                'password'   => 'required|min:4|max:25',
            ];    

            $validator = Validator::make(Input::all(), $rules);

            if( ! $validator->passes() ) {
                return Redirect::route('app.users.add')
                               ->withErrors($validator)
                               ->withInput(); 
            }

            $user = $this->user;

            $user->fill( Input::all() );   
        
            $user->password = Hash::make( Input::get('password') );  
            $user->fullname = Input::get('firstname').' '.Input::get('lastname');   
            $user->status   = 'actived';

            if( $user->save() ) {
                
                $id = $user->id;

                if( Input::hasFile('file') ) {
                    $pic = upload_image(Input::file('file'), 'users/'.$id, '');
                    $this->usermeta->update_meta($id, 'profile_picture', $pic);       
                }          

                return Redirect::route('app.users.edit', $id)
                               ->with('success','New user has been added!');
            } 
        }

        $data['post'] = $this->post;

        return view('app.users.add', $data);
    }

    //--------------------------------------------------------------------------

    public function edit($id='')
    {

        $data['info'] = $info = $this->user->find( $id);
        foreach ($info->usermetas as $usermeta) {
            $data['info'][$usermeta->meta_key] = $usermeta->meta_value;
        }

        if( Input::get('_token') )
        {
            $rules = [
                'email'      => 'required|email|max:64|unique:users,email,'.$id.',id',
                'firstname'  => 'required|max:25',
                'lastname'   => 'required|max:25',
            ];    

            if($id != 1) $rules['group'] = 'required';            	


            $validator = Validator::make(Input::all(), $rules);

            if( ! $validator->passes() ) {
                
                if( $this->request->ajax() ) {
                    $response = array('error' => true, 'details' => $validator->errors());
                    return json_encode( $response );              
                }

                return Redirect::route('app.users.edit', $id)
                               ->withErrors($validator)
                               ->withInput(); 
            }

            $user = $this->user->find( $id );

            $user->fill( Input::all() );   
            
            if( $password = Input::get('password') ) {
                $user->password = Hash::make( $password );
            }

            $user->fullname   = Input::get('firstname').' '.Input::get('lastname');   
            $user->updated_at = date('Y-m-d H:i:s');
            
            if($id != 1) $user->status = Input::get('status', 'inactived');

            if( Input::hasFile('file') ) {
                $pic = upload_image(Input::file('file'), 'users/'.$id, $info->profile_picture, 'compress');
                $this->usermeta->update_meta($id, 'profile_picture', $pic);       
            }            
                                
            if( $user->save() ) {
                return Redirect::route('app.users.edit', $id)
                               ->with('success','User has been updated!');
            } 
        }

        $data['post'] = $this->post;

        return view('app.users.edit', $data);
    }

    //--------------------------------------------------------------------------
  
    public function delete($id)
    {
        $this->user->findOrFail($id)->delete();
        return Redirect::route('app.users.index', query_vars())
                       ->with('success','Selected user has been move to trashed!');
    }

    //--------------------------------------------------------------------------

    public function restore($id)
    {   
        $user = $this->user->withTrashed()->findOrFail($id);
        $user->restore();
        return Redirect::route('app.users.index', ['type' => 'trash'])
                       ->with('success','Selected user has been restored!');
    }

    //--------------------------------------------------------------------------

  
    public function destroy($id)
    {   
        $this->usermeta->where('user_id', $id)->delete();
        $user = $this->user->withTrashed()->find($id);
        $user->forceDelete();

        return Redirect::route('app.users.index', ['type' => 'trash'])
                       ->with('success','Selected user has been deleted permanently!');
    }

    //--------------------------------------------------------------------------
    
    public function login($id)
    {
        Auth::loginUsingId($id);        

        Session::put('user_id', $id);

        return Redirect::route('app.general.dashboard');

    }

    //--------------------------------------------------------------------------

}
