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

class PurchaseOrderController extends Controller
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
    protected $inventory;

    public function __construct(User $user, UserMeta $usermeta, Post $post, PostMeta $postmeta, Setting $setting, Request $request, Inventory $inventory)
    {
        $this->user     = $user;
        $this->usermeta = $usermeta;
        $this->post     = $post;
        $this->postmeta = $postmeta;
        $this->setting  = $setting;
        $this->request  = $request;
        $this->inventory = $inventory;

        $this->post_type = 'purchase-order';
        $this->view      = 'app.purchase-orders';
        $this->single    = 'Purchase Order';
        $this->label     = 'Purchase Orders';

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

        $queries = array('date', 'supplier', 'fulfilled');
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

        
        $data['total'] =  $this->post->search($search, ['total'], $queries)
                                     ->where('posts.post_type', $this->post_type)
                                     ->get()
                                     ->sum('total');


        $data['paid'] =  $this->post->search($search, ['total_paid'], $queries)
                                     ->where('posts.post_type', $this->post_type)
                                     ->get()
                                     ->sum('total_paid');

        $data['balance'] =  $this->post->search($search, ['balance'], $queries)
                                     ->where('posts.post_type', $this->post_type)
                                     ->get()
                                     ->sum('balance');

        $data['post'] = $this->post;                                            

        if( Input::get('preview') ) {

            $data['rows'] = $rows->get();
            
            if( $company_id = Input::get('company', $data['company']) ) {
                $data['info'] = $info = $this->post->find( $company_id );
                foreach ($info->postmetas as $postmeta) {
                    $data['info'][$postmeta->meta_key] = $postmeta->meta_value;
                }                
            }


            $pdf = PDF::loadView($this->view.'.reports.preview', $data);
            return $pdf->stream($this->post_type.'-register.pdf');            
        }

        $data['rows'] = $rows->paginate(Input::get('rows', 15));

        return view($this->view.'.index', $data);
    }

    //--------------------------------------------------------------------------

    public function payables()
    {

        $data['view'] = $this->view;
        $data['post'] = $this->post;      
        $data['company'] = $this->setting->get_setting('company');

        $search['from'] = $from = Input::get('from', date('01-01-Y'));
        $search['to']   = $to   = Input::get('to', date('m-d-Y'));
        
        $data['date_from']  = date('F d, Y', strtotime(date_formatted_b($from)));
        $data['date_to']    = date('F d, Y', strtotime(date_formatted_b($to)));

        $rows = $this->post->where('post_status', '<>', 'paid')
                     ->where('post_type', $this->post_type)
                     ->orderBy(Input::get('sort', 'id'), Input::get('order', 'DESC'));


        $data['rows'] = $rows->get();
        
        if( $company_id = Input::get('company', $data['company']) ) {
            $data['info'] = $info = $this->post->find( $company_id );
            foreach ($info->postmetas as $postmeta) {
                $data['info'][$postmeta->meta_key] = $postmeta->meta_value;
            }                
        }

        $pdf = PDF::loadView($this->view.'.reports.payables', $data);
        return $pdf->stream($this->post_type.'-payables.pdf'); 

        return view($this->view.'.index', $data);
    }

    //--------------------------------------------------------------------------

    public function add()
    {
        $data['single'] = $this->single;                                      
        $data['label']  = $this->label; 
        $data['view']   = $this->view;

        $data['supplier'] = $supplier = Input::get('supplier');
        $data['reference_no'] = $reference_no = $this->post->reference_no('po');

        if( Input::get('_token') )
        {
            $rules = [
                'supplier'   => 'required',
                'address'    => 'required',
                'date'       => 'required',
            ];    

            $validator = Validator::make(Input::all(), $rules);

            if( ! $validator->passes() ) {
                return Redirect::route($this->view.'.add', query_vars())
                               ->withErrors($validator)
                               ->withInput(); 
            }

            $inputs = Input::except(['_token']);

            $inputs['date'] = date_formatted_b($inputs['date']);

            $post = $this->post;

            $post->post_author  = $this->user_id;
            $post->parent       = Input::get('supplier');            
            $post->post_content = json_encode($inputs);                
            $post->post_type    = $this->post_type;
            $post->post_status  = 'unpaid';

            if( $post->save() ) {

                $inputs['reference_no'] = $reference_no;
                $inputs['total_paid']   = 0;
                $inputs['balance']      = Input::get('total');     
                $inputs['payments']     = '';
                $inputs['fulfilled']    = 'unfulfilled';

                foreach ($inputs as $meta_key => $meta_val) {
                    $this->postmeta->update_meta($post->id, $meta_key, array_to_json($meta_val));
                }

                /* START Update Document Settings  */
                $next = $this->setting->get_setting('po_no_next');
                $this->setting->update_meta('po_no_next', sprintf('%0'.strlen($next).'s', $next + 1) );
                /* END Update Document Settings */

                return Redirect::route($this->view.'.edit', $post->id)
                               ->with('success', 'New '. strtolower($this->single).' has been added!');
            } 
        }


        $data['restocks'] =  $this->post->select('posts.id as item',
                                                'posts.post_content as product',
                                               'm2.meta_value as description'
                                            )->from('posts')
                                             ->join('postmeta AS m1', function ($join) {
                                                $join->on('posts.id', '=', 'm1.post_id')
                                                     ->where('m1.meta_key', '=', 'quantity')
                                                     ->where('m1.meta_value', '<=', 5);
                                            })
                                            ->join('postmeta AS m2', function ($join) {
                                                $join->on('posts.id', '=', 'm2.post_id')
                                                     ->where('m2.meta_key', '=', 'description');
                                            })
                                             ->where('posts.post_type', 'product')
                                              ->where('posts.post_content', 'LIKE', '%"supplier":"'.$data['supplier'].'"%')
                                              ->get()
                                              ->toArray();


        $data['post']    = $this->post;
        $data['address'] = $this->postmeta->get_meta($supplier, 'address');
        $data['delivery_address'] = $this->setting->get_setting('delivery_address');


        return view($this->view.'.add', $data);
    }

    //--------------------------------------------------------------------------

    public function edit($id='')
    {
        $data['single']  = $this->single;                                      
        $data['label']   = $this->label; 
        $data['view']    = $this->view;

        $data['company'] = $this->setting->get_setting('company');

        $data['info'] = $info = $this->post->find( $id );
        foreach ($info->postmetas as $postmeta) {
            $data['info'][$postmeta->meta_key] = $postmeta->meta_value;
        }

        $data['post'] = $this->post;

        if( Input::get('_token') )
        {
            $rules = [
                'supplier'   => 'required',
                'address'    => 'required',
                'date'       => 'required',
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

            $inputs = Input::except(['_token', 'pay_status']);

            $inputs['payments'] = $inputs['orders'] = '';

            $inputs['date'] = date_formatted_b($inputs['date']);
            $inputs['reference_no'] = $info->reference_no;
            
            $pay_status = $info->post_status;

            $orders = $payments = array();

            /* Payments */
            if( Input::get('payments') ) {
                foreach (Input::get('payments') as $row) {
                    if( $row['amount'] )
                    $payments[] = $row; 
                }                    
            }

            $inputs['payments'] = $payments;   

            if( Input::get('total') != Input::get('balance') && Input::get('balance') != 0 ) {
                $pay_status = 'partial';      
            }

            if( Input::get('balance') == 0 ) {
                $pay_status = 'paid';      
            }

            if( Input::get('total_paid') == 0 ) {
                $pay_status = 'unpaid';      
            }

            if( Input::get('pay_status') == 'pay_remaining' ) {
                $inputs['payments'][] = array(
                    "date"         => date('m-d-Y'),
                    "method"       => "cash",
                    "reference_no" => "",
                    "remarks"      => "Paid Remaining Balance",
                    "amount"       => $info->balance
                );        

                $inputs['total_paid'] = $info->total;       
                $inputs['balance']    = 0;      
                $pay_status           = 'paid';      
            }
            
            if( Input::get('pay_status') == 'unpay' ) {
                $inputs['payments']   = '';
                $inputs['total_paid'] = 0;      
                $inputs['balance']    = $info->total;       
                $pay_status           = 'unpaid';      
            }

            if( Input::get('pay_status') == 'pay' ) {
                $inputs['payments'][0] = array(
                    "date"         => date('m-d-Y'),
                    "method"       => "cash",
                    "reference_no" => "",
                    "remarks"      => "Fully Paid",
                    "amount"       => $info->total
                );    
                $inputs['total_paid'] = $info->total;       
                $inputs['balance']    = 0;      
                $pay_status           = 'paid';      
            }

            /* Orders */
            if( Input::get('orders') ) {
                foreach (Input::get('orders') as $row) {
                    if( $row['item'] )
                    $orders[$row['item']] = $row; 
                }                    
            }              

            $inputs['orders'] = $orders;

            $post = $this->post->find( $id );

            $post->post_author  = $this->user_id;
            $post->parent       = Input::get('supplier');            
            $post->post_content = json_encode($inputs);                
            $post->post_status  = $pay_status;
            $post->updated_at   = date('Y-m-d H:i:s');

            if( $post->save() ) {

                if( @$inputs['terms'] ) {
                    $terms = $this->post->find($inputs['terms'])->post_name;
                    $inputs['due_date'] = date("Y-m-d", strtotime("+".$terms." days",time()));
                }

                $inputs['fulfilled']    = Input::get('fulfilled', 'unfulfilled');

                /* Return stocks from purchase order */
                if( $info->fulfilled == 'fulfilled' && $inputs['fulfilled'] == 'unfulfilled' ) {
                   $this->inventory->stocks($id, $orders, $inputs, 'delete', 'in'); 
                }

                /* Deduct stocks from product quantity */
                if( $info->fulfilled == 'unfulfilled' && $inputs['fulfilled'] == 'fulfilled' ) {
                    $this->inventory->stocks($id, $orders, $inputs, 'store', 'in'); 
                }


                foreach ($inputs as $meta_key => $meta_val) {
                    $this->postmeta->update_meta($post->id, $meta_key, array_to_json($meta_val));
                }

                if( $this->request->ajax() ) {

                    $data['info'] = $info = $this->post->find( $id );
                    foreach ($info->postmetas as $postmeta) {
                        $data['info'][$postmeta->meta_key] = $postmeta->meta_value;
                    }

                    return view($this->view.'.temp.details', $data);
                }

                return Redirect::route($this->view.'.edit', $id)
                               ->with('success', $this->single.' has been updated!');
            } 
        }

        if( Input::get('preview') ) {

            if( $company_id = Input::get('company', $data['company']) ) {
                $data['company'] = $company = $this->post->find( $company_id );
                foreach ($company->postmetas as $postmeta) {
                    $data['company'][$postmeta->meta_key] = $postmeta->meta_value;
                }                
            }
            
            $pdf = PDF::loadView($this->view.'.reports.po', $data);
            return $pdf->stream($this->post_type.'.pdf');            
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
