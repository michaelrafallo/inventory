<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mail, Auth, Request, Input;


class Inventory extends Model
{

	protected $primaryKey = 'id';
	
	public $timestamps = false;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'inventory';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */


    public function scopeSearch($query, $data = array(), $selects = array(), $queries = array()) 
    {

        if( isset($data['item']) ) {
            if($data['item'] != '')
            $query->where('item', $data['item']);
        }

        if( isset($data['reference_no']) ) {
            if($data['reference_no'] != '')
            $query->where('reference_no', 'LIKE', '%'.$data['reference_no'].'%');
        }

        if( isset($data['type']) ) {
            if($data['type'] != '')
            $query->where('type', $data['type']);
        }

        if( isset($data['person_id']) ) {
            if($data['person_id'] != '')
            $query->where('person_id', $data['person_id']);
        }

        if( isset($data['from']) && isset($data['to']) ) {
            if($data['from'] != '' && $data['to'] != '')
            $query->whereBetween('date', [ date_formatted_b($data['from']), date_formatted_b($data['to'])]);
        }


        return $query;
    }

    public function stocks($post_id, $orders = array(), $inputs = array(), $method = 'store', $type = 'out') 
    {

        if($method == 'delete') {
            foreach (Inventory::where('post_id', $post_id)->get() as $inventory) {
                $product = Postmeta::where('post_id', $inventory->item)->where('meta_key', 'quantity')->first();
                
                if($type == 'out') {
                    $product->meta_value = $product->meta_value + $inventory->qty;
                }
                if($type == 'in') {
                    $product->meta_value = $product->meta_value - $inventory->qty;
                }

                $product->save();
            };

            Inventory::where('post_id', $post_id)->delete();
        }


        if( $method == 'store' ) {

            if( $orders ) {
                foreach ($orders as $row) {

                    $inventory = Inventory::where('post_id', $post_id)->where('item', $row['item'])->first();

                    if( ! $inventory ) {
                        $inventory = new Inventory();
                    }

                    $inventory->user_id      = Auth::User()->id;
                    $inventory->item         = $row['item'];
                    $inventory->reference_no = $inputs['reference_no'];
                    $inventory->uom          = $row['unit_of_measure'];
                    $inventory->person_id    = $type == 'out' ? $inputs['customer'] : $inputs['supplier']; 
                    $inventory->date         = $inputs['date'];
                    $inventory->qty          = $row['quantity'];
                    $inventory->price        = $row['price'];
                    $inventory->content      = json_encode($row);                            
                    $inventory->post_id      = $post_id;
                    $inventory->type         = $type;
                    $inventory->created_at   = date('Y-m-d H:i:s');
                    $inventory->save();

                    $product = Postmeta::where('post_id', $row['item'])->where('meta_key', 'quantity')->first();

                    if($type == 'out') {
                        $product->meta_value = $product->meta_value - $row['quantity'];
                    }
                    if($type == 'in') {
                        $product->meta_value = $product->meta_value + $row['quantity'];
                    }

                    $product->save();

                }                    
            }              
        }

    }

}
