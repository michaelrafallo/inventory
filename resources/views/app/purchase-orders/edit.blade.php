@extends('layouts.app')

@section('content')
<!-- BEGIN PAGE BAR -->
<div class="page-bar margin-bottom-20">
    <ul class="page-breadcrumb uppercase">
        <li>
           <h1 class="page-title">Edit {{ $single }}
            <a href="{{ URL::route($view.'.add') }}" class="btn-xs"><i class="fa fa-plus"></i> Create <b>Purchase Order</b></a>
           </h1>
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


<div class="load-details">
     @include($view.'.temp.details')   
</div>









@endsection



@section('top_style')
<style>
.table-po { margin: 0 }    
.table-po>tbody>tr>td, .table-po>tbody>tr>th, 
.table-po>tfoot>tr>td, .table-po>tfoot>tr>th, 
.table-po>thead>tr>td, .table-po>thead>tr>th {
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
    width: 150px;
}
td.col-sm, .col-sm input { width: 120px!important; } 
.border-left {
    border-left: 1px dotted #c1c1c1;    
}
.item-select .select2, .item-select {
    width: 100%;
}


.blockMsg {  z-index: 6 }
.modal { z-index: 5; }
.modal-backdrop { z-index: 4; }
</style>
@stop

@section('bottom_style')
@stop

@section('bottom_plugin_script') 
@stop

@section('bottom_script')
<script>

var product_url = '{{ URL::route('app.ajax.posts.view') }}';
var blockUImsg = 'Updating order details ...';

$(document).on('change','[name=supplier]', function(e) {
    e.preventDefault();
    var sid = $(this).val();

    $.get(product_url, { 'id' : sid }, function(response) {
        var data = JSON.parse(response);
        $('[name=address]').val(data.address);
    });
});


$(document).on('change','.item', function(e) {
    e.preventDefault();
    var $this    = $(this), 
        val      = $(this).val(),
        supplier = $('.supplier').val();

          $this.closest('table').find('.f-i').val('');

    $.get(product_url, { 'id' : val, 'sid' : supplier }, function(response) {
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
        sub_total = qty * price;

        t.closest('tr').find('.sub-total').html( number_format(sub_total.toFixed(2)) );
        t.closest('tr').find('.sub_total').val( sub_total );

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

    var target   = $(this).data('target');
    var formData = $(target);
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
            $('.modal-backdrop').remove();
            init_repeater();
            app_init();
            initTable();
        }         
        
        $.unblockUI();  

    });

});

</script>
@stop

