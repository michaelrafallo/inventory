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

class GeneralController extends Controller
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
    protected $permission;
    protected $request;

    public function __construct(User $user, UserMeta $usermeta, Post $post, PostMeta $postmeta, Setting $setting, Permission $permission, Request $request)
    {
        $this->user     = $user;
        $this->usermeta = $usermeta;
        $this->post     = $post;
        $this->postmeta = $postmeta;
        $this->setting  = $setting;
        $this->permission = $permission;
        $this->request = $request;
    }

    //--------------------------------------------------------------------------

    public function dashboard()
    {

        $data['currency'] = currency_symbol($this->setting->get_setting('currency'));


        $data['unpaid_customers'] =  $this->post
                                      ->select('posts.*', 
                                           'm1.meta_value as balance'
                                        )->from('posts')
                                         ->join('postmeta AS m1', function ($join) {
                                            $join->on('posts.id', '=', 'm1.post_id')
                                                 ->where('m1.meta_key', '=', 'balance');
                                        })->where('posts.post_type', 'sales-order')
                                          ->where('posts.post_status', '<>', 'paid')
                                          ->get()
                                          ->sum('balance');

        $data['unpaid_customer_count'] = $this->post
                                      ->select('posts.*', 
                                           'm1.meta_value as customer'
                                        )->from('posts')
                                         ->join('postmeta AS m1', function ($join) {
                                            $join->on('posts.id', '=', 'm1.post_id')
                                                 ->where('m1.meta_key', '=', 'customer');
                                        })->where('posts.post_type', 'sales-order')
                                          ->where('posts.post_status', '<>', 'paid')
                                          ->GroupBy('customer')
                                          ->get()
                                          ->count();


        $data['unpaid_suppliers'] = $this->post
                                      ->select('posts.*', 
                                           'm1.meta_value as balance'
                                        )->from('posts')
                                         ->join('postmeta AS m1', function ($join) {
                                            $join->on('posts.id', '=', 'm1.post_id')
                                                 ->where('m1.meta_key', '=', 'balance');
                                        })->where('posts.post_type', 'purchase-order')
                                          ->where('posts.post_status', '<>', 'paid')
                                          ->get()
                                          ->sum('balance');

        $data['unpaid_supplier_count'] = $this->post
                                      ->select('posts.*', 
                                           'm1.meta_value as supplier'
                                        )->from('posts')
                                         ->join('postmeta AS m1', function ($join) {
                                            $join->on('posts.id', '=', 'm1.post_id')
                                                 ->where('m1.meta_key', '=', 'supplier');
                                        })->where('posts.post_type', 'purchase-order')
                                          ->where('posts.post_status', '<>', 'paid')
                                          ->GroupBy('supplier')
                                          ->get()
                                          ->count();

        $data['supplier_count']   = $this->post->where('post_type', 'supplier')->count();
        $data['customer_count']   = $this->post->where('post_type', 'customer')->count();
        $data['product_count']    = $this->post->where('post_type', 'product')->count();

        $data['categories'] = json_encode( array_values(get_past_months(12)) );

        foreach(range('1', date('m')) as $mon) {
            $date = date('Y-m', strtotime(date('Y-'.$mon)));

            $so_count =  $this->post
                              ->select('posts.*', 
                                   'm1.meta_value as total_paid',
                                   'm2.meta_value as date'
                                )->from('posts')
                                 ->join('postmeta AS m1', function ($join) {
                                    $join->on('posts.id', '=', 'm1.post_id')
                                         ->where('m1.meta_key', '=', 'total_paid');
                                })->join('postmeta AS m2', function ($join) use ($date) {
                                    $join->on('posts.id', '=', 'm2.post_id')
                                         ->where('m2.meta_key', 'date')
                                         ->where('m2.meta_value', 'LIKE', '%'.$date.'%');
                                })->where('posts.post_type', 'sales-order')
                                  ->where('posts.post_status', '!=', 'unpaid')
                                  ->get()
                                  ->sum('total_paid');

            $so[$date] = $so_count;

            $po_count =  $this->post
                              ->select('posts.*', 
                                   'm1.meta_value as total_paid',
                                   'm2.meta_value as date'
                                )->from('posts')
                                 ->join('postmeta AS m1', function ($join) {
                                    $join->on('posts.id', '=', 'm1.post_id')
                                         ->where('m1.meta_key', '=', 'total_paid');
                                })->join('postmeta AS m2', function ($join) use ($date) {
                                    $join->on('posts.id', '=', 'm2.post_id')
                                         ->where('m2.meta_key', 'date')
                                         ->where('m2.meta_value', 'LIKE', '%'.$date.'%');
                                })->where('posts.post_type', 'purchase-order')
                                  ->where('posts.post_status', '!=', 'unpaid')
                                  ->get()
                                  ->sum('total_paid');

            $po[$date] = $po_count;

            $ex_count =  $this->post
                              ->select('posts.*', 
                                   'm1.meta_value as amount',
                                   'm2.meta_value as date'
                                )->from('posts')
                                 ->join('postmeta AS m1', function ($join) {
                                    $join->on('posts.id', '=', 'm1.post_id')
                                         ->where('m1.meta_key', '=', 'amount');
                                })->join('postmeta AS m2', function ($join) use ($date) {
                                    $join->on('posts.id', '=', 'm2.post_id')
                                         ->where('m2.meta_key', 'date')
                                         ->where('m2.meta_value', 'LIKE', '%'.$date.'%');
                                })->where('posts.post_type', 'expense')
                                  ->where('posts.post_status', '!=', 'unpaid')
                                  ->get()
                                  ->sum('amount');

            $ex[$date] = $ex_count;
        }

        $data['so'] = json_encode( array_values($so) );
        $data['po'] = json_encode( array_values($po) );
        $data['ex'] = json_encode( array_values($ex) );

        $events = $this->post->whereIn('posts.post_type', ['purchase-order', 'sales-order'])
                             ->where('posts.post_status', '!=', 'paid')
                             ->get();
        
        $calendar = array();

        foreach ($events as $event) {

            $postmeta = get_meta( $event->postMetas()->get() ); 
            
            $date = @$postmeta->due_date;

            if($date) {
                $ev[$date][] = $event;

                if($event->post_type == 'sales-order') {
                    $title = 'SO DUE';
                    $color = '#0023e6';
                } else {
                    $title = 'PO DUE';
                    $color = '#ff0000';
                }
                
                $count = count($ev[$date]);

                $e = array(
                    'title'           => $count.' Bill'.is_plural($count),
                    'start'           => $date,
                    'end'             => $date,
                    'backgroundColor' => $color,
                    'borderColor'     => $color,
                );

                if( $this->permission->has_access( $event->post_type.'s' ) ) {
                  $e['url'] = URL::route('app.'.$event->post_type.'s.index', ['due_date' => $date]);
                }

                $calendar[$date] = $e;                
            }

        }

        $data['events'] = json_encode( array_values($calendar));


        return view('app.general.dashboard', $data);
    }

    //--------------------------------------------------------------------------

    public function settings()
    {

        $data = array();
        
        $data['post'] = $this->post;                            

        $data['info'] = (object)$this->setting->get()->pluck('value', 'key')->toArray();

        if ( Input::get('_token') ) 
        {   
            $inputs = Input::except(['_token']);

            if( Input::hasFile('file') ) {
                $pic = upload_image(Input::file('file'), 'images', @$data['info']->logo, 'original');
                $inputs['logo'] = $pic;
            }         


            foreach($inputs as $key => $val) {

                $setting = Setting::where('key', $key)->first();

                if( ! $setting ) {
                    $setting = new Setting();
                }
  
                $setting->key   = $key;
                $setting->value = $val;

                $setting->save();                    
     
            }   
            
            if( $this->request->ajax() ) {
              $data['info'] = (object)$this->setting->get()->pluck('value', 'key')->toArray();
              return view('app.general.settings.details', $data);
            }

            return Redirect::route('app.general.settings', query_vars())
                           ->with('success','Changes saved.');
        }

        return view('app.general.settings', $data);

    }

    //--------------------------------------------------------------------------

}
