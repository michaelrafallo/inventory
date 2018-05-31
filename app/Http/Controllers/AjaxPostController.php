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

class AjaxPostController extends Controller
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


        $this->middleware(function ($request, $next) {
            $this->user_id = Auth::user()->id;
            return $next($request);
        });
    }

    //--------------------------------------------------------------------------

    public function view()
    {
        $id = Input::get('id');
        $sid = Input::get('sid');

        $data['info'] = $info = $this->post->find( $id );
        foreach ($info->postmetas as $postmeta) {
            $data['info'][$postmeta->meta_key] = $postmeta->meta_value;
        }

        $data['info']['unit_of_measure'] = metrics($data['info']->unit_of_measure);

        if(@$info->quantity) {
            $data['info']['stocks']   = $info->quantity;
            $data['info']['quantity'] = number_format($info->quantity);
        }

        $data['info']['supplier_price'] = $info->normal_price;
        $data['info']['customer_price'] = $info->sales_price;

        $post_content = @($info->customers) ? $info->customers : $info->suppliers;

        if(  $rows = @json_decode($post_content, true)[$sid] ) {
            foreach($rows as $key => $val) {
          
                if($val) $data['info'][$key] = $val;
            }
        }    


        return json_encode($info);
    }

    //--------------------------------------------------------------------------

    
}
