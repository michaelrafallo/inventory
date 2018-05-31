<div class="mt-repeater">
    <div data-repeater-list="orders">

        @if($orders = json_decode($info->orders, true))
        @foreach( $orders as $order)
        <?php 
            $code  = @$order['code'];
            $price = @$order['price'];

            $stocks = $postmeta->get_meta($order['item'], 'quantity');
        ?>
        <div data-repeater-item="" class="mt-repeater-item">
            <div class="mt-repeater-row">            


            <table class="table table-condensed table-striped">
                <tr>
                    <td class="item-select">           
                        <h5 class="col">Item</h5> 
                        <div class="input-group-sm">                 
                        {{ Form::select('item', ['' => 'Select Item'] + $post->select_posts(['post_type' => 'product']), @$order['item'], ['class' => 'form-control item select2']) }}
                        </div>
                    </td>
                    <td class="col-sm">
                        <h5 class="col text-right">Stocks</h5>
                        <div class="quantity text-right qstock sbold">{{ number_format($stocks) }}</div>
                        <input type="hidden" class="stocks" value="{{ $stocks }}">
                    </td>
                    <td class="col-sm">
                        <h5 class="col text-right">Balance</h5>
                        <?php $sqty = $stocks - @$order['quantity']; ?>
                        <div class="stocks remaining text-right qstock sbold {{ $sqty < 1 ? 'text-danger' : '' }}">{{ number_format($sqty) }}</div>
                    </td>
                    <td>
                    <td class="col-sm">
                        <h5 class="col text-right">Quantity</h5>

                        <div class="input-group">
                            <input type="number" class="form-control text-right s-t qty input-sm sbold text-primary" name="quantity" placeholder="0" value="{{ @$order['quantity'] }}" data-original-title="Quantity" min="1">
                            <span class="input-group-addon unit_of_measure">{{ @$order['unit_of_measure'] }}</span>
                            <input type="hidden" name="unit_of_measure" value="{{ @$order['unit_of_measure'] }}" class="unit_of_measure">
                        </div>

                    </td>
                    <td class="col-sm">
                        <h5 class="col text-right">Price</h5>
                        <input type="number" class="form-control text-right s-t price customer_price f-i input-sm sbold text-primary" name="price" placeholder="0.00" value="{{ @$price }}" data-original-title="Unit Price" min="1" step="any">
                    </td>
                    <td>
                        <h5 class="col text-right">Sub-Total</h5>
                          <h5 class="text-right sub-total">{{ number_format(@$order['sub_total'], 2) }}</h5>
                          <input type="hidden" name="sub_total" class="sub_total f-i" value="{{ @$order['sub_total'] }}">
                    </td>
                    <td>
                        <button type="button" data-repeater-delete="" class="btn btn-xs btn-outline red mt-repeater-delete pull-right">
                            <i class="fa fa-close"></i>
                        </button>
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        <input type="text" class="form-control description input-sm f-i" name="description" placeholder="Description" value="{{ @$order['description'] }}" data-original-title="Item Description">
                    </td>
                    <td colspan="4">
                      <input type="text" class="form-control code input-sm f-i" name="code" placeholder="Product Code" value="{{ $code }}" data-original-title="Product Code">            
                    </td>                                        
                </tr>
            </table>


            </div>
        </div> 

        @endforeach
        @else

        <div data-repeater-item="" class="mt-repeater-item">
            <div class="mt-repeater-row">            

            <table class="table table-condensed table-striped">
                <tr>
                    <td class="item-select">           
                        <h5 class="col">Item</h5> 
                        <div class="input-group-sm">                 
                        {{ Form::select('item', ['' => 'Select Item'] + $post->select_posts(['post_type' => 'product']), '', ['class' => 'form-control item select2']) }}
                        </div>
                    </td>
                    <td class="col-sm">
                        <h5 class="col text-right">Stocks</h5>
                        <div class="quantity text-right qstock sbold">0</div>
                        <input type="hidden" class="stocks" value="0">
                    </td>
                    <td class="col-sm">
                        <h5 class="col text-right">Balance</h5>
                        <div class="stocks remaining text-right qstock sbold">0</div>
                    </td>
                    <td class="col-sm">
                        <h5 class="col text-right">Quantity</h5>

                        <div class="input-group">
                            <input type="number" class="form-control text-right s-t qty input-sm sbold text-primary" name="quantity" placeholder="0" value="" data-original-title="Quantity" min="1">
                            <span class="input-group-addon unit_of_measure">Piece(s)</span>
                            <input type="hidden" name="unit_of_measure" value="Piece(s)" class="unit_of_measure">
                        </div>

                    </td>
                    <td class="col-sm">
                        <h5 class="col text-right">Price</h5>
                        <input type="number" class="form-control text-right s-t price customer_price f-i input-sm sbold text-primary" name="price" placeholder="0.00" value="" data-original-title="Unit Price" min="1" step="any">
                    </td>
                    <td>
                        <h5 class="col text-right">Sub-Total</h5>
                          <h5 class="text-right sub-total">0.00</h5>
                          <input type="hidden" name="sub_total" class="sub_total f-i" value="">
                    </td>
                    <td>
                        <button type="button" data-repeater-delete="" class="btn btn-xs btn-outline red mt-repeater-delete pull-right">
                            <i class="fa fa-close"></i>
                        </button>
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        <input type="text" class="form-control description input-sm f-i" name="description" placeholder="Description" value="" data-original-title="Item Description">
                    </td>
                    <td colspan="4">
                      <input type="text" class="form-control code input-sm f-i" name="code" placeholder="Product Code" value="" data-original-title="Product Code">            
                    </td>                                        
                </tr>
            </table>


            </div>
        </div>  
        @endif

    </div>
    <a data-repeater-create="" class="btn mt-repeater-add btn-outline blue margin-top-20">
        <i class="fa fa-plus"></i> Add Item</a>
</div>

<input type="hidden" name="total" value="{{ $info->total }}">
<input type="hidden" name="total_paid" value="{{ @$info->total_paid }}">
<input type="hidden" name="balance" value="{{ @$info->balance }}">
<button type="submit" class="hide"></button>