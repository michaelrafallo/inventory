<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Input, Request, Auth, URL, Mail;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;

    public $timestamps = true;

    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstname',
        'lastname',
        'username',
        'email',
        'activation_key',
        'status',
        'group',
        'remember_token',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public static $unsets = ['_token', 'op', 'file', 'firstname', 'lastname', 'email', 'status'];


    public function userMetas()
    {
        return $this->hasMany('App\UserMeta', 'user_id');
    }
    
    public function scopeSearch($query, $data = array(), $selects = array(), $queries = array()) {

        $q = array();

        /* Select */
        $s=1;
        foreach($selects as $select) {
            $s_data = array('select' => $select, 's' => $s);
            $query->join("usermeta AS m{$s}", function ($join) use ($s_data) {            
                $select = $s_data['select'];
                $s = $s_data['s'];
                $join->on("users.id", '=', "m{$s}.user_id")
                     ->where("m{$s}.meta_key", '=', $select);
            });
            $select_data[] = "m{$s}.meta_value as ".$select;
            $s++;
        }

        /* Search */
        foreach($queries as $q) {
            if( isset($data[$q]) ) {
                if($data[$q] != '') {

                    $s_data = array('select' => $q, 's' => $s, 'data' => $data);
                    $query->join("usermeta AS m{$s}", function ($join) use ($s_data) {
                        $select = $s_data['select'];
                        $where = @$s_data['data'][$select];
                        $s = $s_data['s'];
                        $join->on("users.id", '=', "m{$s}.user_id")
                             ->where("m{$s}.meta_key", '=', $select)
                             ->where("m{$s}.meta_value", '=', $where);
                    });    
                
                }
            }
            $s++;
        }

        $select_data[] = 'users.*';

        $query->select($select_data)
        ->from('users');

        if( isset($data['s']) ) {
            if($data['s'] != '')
            $query->where('users.fullname', 'LIKE', '%'.$data['s'].'%');
        }

        if( isset($data['status']) ) {
            if($data['status'] != '')
            $query->where('users.status', $data['status']);
        }

        if( isset($data['group']) ) {
            if($data['group'] != '')
            $query->where('users.group', $data['group']);
        }

        if( isset($data['email']) ) {
            if($data['email'] != '')
            $query->where('users.email', 'LIKE', '%'.$data['email'].'%');
        }

        if( isset($data['type']) ) {
            if($data['type'] == 'trash')
            $query->withTrashed()->where('users.deleted_at', '<>', '0000-00-00');
        }


        return $query;
    }

    public static $forgotPassword = [
        'email' => 'required|email|max:64|exists:users,email',
    ];

    public static $newPassword = [
        'new_password'              => 'required|min:4|max:64|confirmed',
        'new_password_confirmation' => 'required|min:4',
    ];

    public function get_meta($key, $value)
    {
        return UserMeta::get_meta($key, $value);
    }

  
    public function send_email_verification($user_id) {
        $info = User::find($user_id); 
        $info->verify_token = $token = str_random(64);
        $info->save();

        $mail['url']  = URL::route('auth.verify', $token);
        $mail['site_name'] = $site_name = ucwords(Setting::get_setting('site_title'));

        $mail['email_support'] = Setting::get_setting('admin_email');
        $mail['email_title']   = 'Please verify your '.$site_name.' account';
        $mail['email_subject'] = 'Please verify your '.$site_name.' account';


        $mail['email'] = $info->email;
        
        Mail::send('emails.verify', $mail, function($message) use ($mail)
        {
            $message->from($mail['email_support'], $mail['email_title']);
            $message->to($mail['email'])->subject($mail['email_subject']);
        });        
    }

    public function select_employees() {
        $users = User::where('group', 'employee')->get(); 

        foreach ($users as $user) {
            $usermeta = get_meta( $user->userMetas()->get() );
            $data[$user->id] = '( '.$usermeta->employee_id.' ) '.$user->fullname;
        }

        return $data;
    }

    public function getGroupNameAttribute() {
        $post = Post::where('id', $this->group);
        if( $post->exists() ) 
            return $post->first()->post_title;
    }

}

