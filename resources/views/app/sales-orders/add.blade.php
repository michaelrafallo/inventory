@extends('layouts.app')

@section('content')
<!-- BEGIN PAGE BAR -->
<div class="page-bar margin-bottom-20">
    <ul class="page-breadcrumb uppercase">
        <li>
           <h1 class="page-title">Create {{ $single }} <small class="sbold"> : {{ $reference_no }}</small></h1>
        </li>
    </ul>
    <div class="page-toolbar">
        <div class="btn-group pull-right">
            <a href="{{ URL::route($view.'.index') }}" class="btn btn-default margin-top-20"> 
                <i class="fa fa-angle-left"></i> All {{ $label }}
            </a>
        </div>
    </div>
</div>
<!-- END PAGE BAR -->


<div class="row">

    <div class="col-md-12 col-centered">
        
        @include('notification')

        <!-- BEGIN FORM-->
        <form action="" class="form-horizontal form-submit" method="post">

        <div class="portlet light bordered">

            <div class="portlet-body">


                    {{ csrf_field() }}

                    <div class="form-body">

                    <div class="row">
                        <div class="col-md-6">

                            <div class="form-group">
                                <label class="col-md-4 control-label">Customer <span class="required">*</span></label>
                                <div class="col-md-7">
                                   {{ Form::select('customer', ['' => 'Select Supplier'] + $post->select_posts(['post_type' => 'customer']), Input::old('customer', $customer), ['class' => 'form-control select2 customer']) }}
                                    <!-- START error message -->
                                    {!! $errors->first('customer','<span class="text-danger help-block">:message</span>') !!}
                                    <!-- END error message -->
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label">Address <span class="required">*</span></label>
                                <div class="col-md-7">
                                   <textarea name="address" class="form-control rtip" rows="3" placeholder="Address">{{ Input::old('address', $address) }}</textarea>
                                    <!-- START error message -->
                                    {!! $errors->first('address','<span class="text-danger help-block">:message</span>') !!}
                                    <!-- END error message -->
                                </div>
                            </div>    

                            <div class="form-group">
                                <label class="col-md-4 control-label">Terms</label>
                                <div class="col-md-7">
                                   {{ Form::select('terms', ['' => 'None'] + $post->select_posts(['post_type' => 'terms']), Input::old('terms'), ['class' => 'form-control select2']) }}
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label">Sales Invoice No.</label>
                                <div class="col-md-7">
                                    <input type="text" name="si_no" class="form-control" value="{{ Input::old('si_no') }}">
                                    <span class="help-inline">Overwrite Reference No.</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label">Delivery Receipt No.</label>
                                <div class="col-md-7">
                                    <input type="text" name="dr_no" class="form-control" value="{{ Input::old('dr_no') }}">
                                    <span class="help-inline">Overwrite Reference No.</span>
                                </div>
                            </div>


                        </div>
                        <div class="col-md-6 border-left">
                            
                            <div class="form-group">
                                <label class="col-md-4 control-label">Date <span class="required">*</span></label>
                                <div class="col-md-7">
                                    <input type="text" name="date" class="form-control datepicker rtip" value="{{ Input::old('date', date('m-d-Y')) }}">
                                    <!-- START error message -->
                                    {!! $errors->first('date','<span class="text-danger help-block">:message</span>') !!}
                                    <!-- END error message -->
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label">Delivery Address</label>
                                <div class="col-md-7">
                                   <textarea name="delivery_address" class="form-control" rows="3" placeholder="Delivery Address">{{ Input::old('delivery_address', $address) }}</textarea>
                                </div>
                            </div>  

                            <div class="form-group">
                                <label class="col-md-4 control-label">Remarks</label>
                                <div class="col-md-7">
                                   <textarea name="remarks" class="form-control" rows="3" placeholder="Remarks">{{ Input::old('remarks') }}</textarea>
                                </div>
                            </div>        


                        </div>                        
                    </div>

                </div>

            </div>
        </div>


        <div class="portlet light bordered">
            
            <h4 class="uppercase"><b class="text-primary">Sales</b> Orders</h4>

            <div class="portlet-body">
                    <div class="form-body">
    
                    <div class="mt-repeater">
                        <div data-repeater-list="orders">

                            @if($orders = Input::old('orders', $restocks))
                            @foreach( $orders as $order)
                            <?php 
                                $code  = @$order['code'];
                                $price = @$order['price'];

                               if(@$order['product']) {
                                    $product = json_decode($order['product'], true);
                                    $code  = $product[$supplier]['code'];
                                    $price = $product[$supplier]['supplier_price'];
                                }

                                $stocks = $postmeta->get_meta($order['item'], 'quantity');
                                $remaining = $stocks - @$order['quantity'];
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
                                            <div class="stocks remaining text-right qstock sbold {{ $remaining <= 0 ? 'text-danger' : '' }}">{{ number_format($remaining) }}</div>
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
                                            <input type="number" class="form-control text-right s-t price supplier_price f-i input-sm sbold text-primary" name="price" placeholder="0.00" value="{{ @$price }}" data-original-title="Unit Price" min="1" step="any">
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
                                            <input type="number" class="form-control text-right s-t price supplier_price f-i input-sm sbold text-primary" name="price" placeholder="0.00" value="" data-original-title="Unit Price" min="1" step="any">
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



                    </div>
                    
                    <input type="hidden" name="total" value="0">
                    <button type="submit" class="hide"></button>


            </div>
        </div>
                </form>
        
    </div>



</div>

<div class="form-actions-fixed">

    <div class="col-md-6 col-xs-6">
        <button type="submit" class="btn btn-primary btn-xs btn-save" data-target=".form-submit"><i class="fa fa-check"></i> Save Order</button>     
    </div>
    <div class="col-md-3">
        <h4 class="no-margin"><small>TOTAL : <b class="total">{{ number_format(Input::old('total'), 2) }}</b></small></h4>
    </div>

</div>
@endsection


@section('top_style')
<style>
.table-so { margin: 0 }    
.table-so>tbody>tr>td, .table-so>tbody>tr>th, 
.table-so>tfoot>tr>td, .table-so>tfoot>tr>th, 
.table-so>thead>tr>td, .table-so>thead>tr>th {
    border: none;   
    padding: 2px;
}
.mt-repeater .mt-repeater-item {
    border-bottom: 1px dotted #e7505a!important;
    padding-bottom: 10px!important;
    margin-bottom: 10px!important;
}
.col { display: none; }
.mt-repeater .mt-repeater-item:first-child .mt-repeater-delete {
    margin-top: 30px;
}
.mt-repeater .mt-repeater-item:first-child .col {
    display: block;
}
.mt-repeater-delete {
    display: block!important;
}
.sub-total {
    margin: 7px 5px 0;
    font-weight: bold;
    width: 120px;
}
td.col-sm, .col-sm input { width: 120px!important; } 
.border-left {
    border-left: 1px dotted #c1c1c1;    
}
.item-select .select2, .item-select {
    width: 100%;
}

.mt-repeater .mt-repeater-item:first-child .qstock { 
    margin: 16px 0 0;
    width: 90px; 
}
.mt-repeater .mt-repeater-item .qstock { 
    margin: 6px 0 0;
    width: 90px; 
}
</style>
@stop

@section('bottom_style')
@stop

@section('bottom_plugin_script') 
@stop

@section('bottom_script')
<script>

var product_url = '{{ URL::route('app.ajax.posts.view') }}';


$(document).on('change','[name=customer]', function(e) {
    e.preventDefault();
    var sid = $(this).val();

    $.get(product_url, { 'id' : sid }, function(response) {
        var data = JSON.parse(response);
        $('[name=delivery_address]').val(data.address);
        $('[name=address]').val(data.address);
    });
});


$(document).on('change','.item', function(e) {
    e.preventDefault();
    var $this    = $(this), 
        val      = $(this).val(),
        customer = $('.customer').val();

          $this.closest('table').find('.f-i').val('');

    $.get(product_url, { 'id' : val, 'sid' : customer }, function(response) {
        var data = JSON.parse(response);
         $.each( data, function( key, value ) {
          $this.closest('table').find('.'+key).val( value );
          $this.closest('table').find('.'+key).html( value );      
        });
        calculate($this);
    });
});


$(document).on('keyup', '.s-a, .s-t', function() {
    calculate($(this));
});

$(document).on('click', '.mt-repeater-delete', function() {
    $(this).closest('.mt-repeater-item').find('input').val('');
    $(this).closest('tr').find('input').val('');
    calculate($(this));
});  


function calculate(t) {

    total = total_paid = 0;

    var qty = t.closest('tr').find('.qty').val(),
        price = t.closest('tr').find('.price').val();
        stocks = t.closest('tr').find('input.stocks').val(), 
        sub_total = qty * price,
        remaining = stocks - qty;

    t.closest('tr').find('.sub-total').html( number_format(sub_total.toFixed(2)) );
    t.closest('tr').find('.sub_total').val( sub_total );
    t.closest('tr').find('.remaining').html( number_format(remaining) );

    t.closest('tr').find('.remaining').removeClass('text-danger');
    if(remaining <= 0) {
        t.closest('tr').find('.remaining').addClass('text-danger');
    }

    $('.sub_total').each(function() {
        total += Number( $(this).val() );
    });

    $('.total').html( number_format(total.toFixed(2)) );
    $('[name=total]').val( total );



    $('.s-a').each(function() {
        total_paid += Number( $(this).val() );
    });

    $('.total-paid').html( number_format(total_paid.toFixed(2)) );
    $('[name=total_paid]').val( total_paid );

    var balance = total - total_paid;

    $('.balance').html( number_format(balance.toFixed(2)) );
    $('[name=balance]').val( balance );

}




$(document).on('click', '.confirm', function(e) {
    
    e.preventDefault();

    var $this    = $(this);
    var formData = $(this).closest('.form-modal').find('form');
    var url      = formData.attr('action');

    $('.msg-error').html('');
    
    blockUI(blockUImsg);

    $.post(url, form_to_json(formData), function(response) {
              
        var IS_JSON = true;
        try {
            var data = JSON.parse(response);
        } catch(err){
            IS_JSON = false;
        } 

        if( IS_JSON ) {
            $.each(data.details, function(key, val) {
                $('#'+key).html('<span class="text-danger help-inline">'+val+'</div>');
            });
        } else {
            $('.load-details').html( response );
            initTable();
        }         
        $.unblockUI();  
    });

});

</script>
@stop
