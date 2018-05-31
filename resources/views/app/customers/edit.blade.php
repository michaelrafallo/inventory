@extends('layouts.app')

@section('content')
<!-- BEGIN PAGE BAR -->
<div class="page-bar margin-bottom-20">
    <ul class="page-breadcrumb uppercase">
        <li>
           <h1 class="page-title">Edit {{ $single }}
            <a href="{{ URL::route($view.'.add') }}" class="btn-xs"><i class="fa fa-plus"></i> Add New</a>
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






<div class="row">

    <div class="col-md-12 col-centered">
        
        @include('notification')

        <div class="portlet light bordered">

            <div class="portlet-body form row">
                <!-- BEGIN FORM-->
                <form action="" class="form-horizontal form-submit" method="post">
                    {{ csrf_field() }}


                    <div class="col-md-3">

                        <ul class="ver-inline-menu tabbable margin-top-20 navigation-tab">
                            <li class="{{ actived(Input::get('tab'), 1) }}">
                                <a href="?tab=1">
                                    <i class="fa fa-user"></i> Customer Info</a>
                                <span class="after"> </span>
                            </li>
                            <li class="{{ actived(Input::get('tab'), 2) }}">
                                <a href="?tab=2">
                                    <i class="fa fa-opencart"></i> Order History</a>
                            </li>
                            <li class="{{ actived(Input::get('tab'), 3) }}">
                                <a href="?tab=3">
                                    <i class="fa fa-money"></i> Payment History</a>
                            </li>
                            <li class="{{ actived(Input::get('tab'), 4) }}">
                                <a href="?tab=4">
                                    <i class="fa fa-thumb-tack"></i> Satement of Account</a>
                            </li>
                            <li class="{{ actived(Input::get('tab'), 5) }}">
                                <a href="?tab=5">
                                    <i class="fa fa-list-alt"></i> Pricelist</a>
                            </li>
                        </ul>


                    </div>
                    <div class="col-md-9">
                        <div class="tab-content load-details">
                            @include($view.'.tabs.details')
                        </div>
                    </div>


                    
                    <button type="submit" class="hide"></button>
                </form>
                <!-- END FORM-->
            </div>
        </div>

        
    </div>


</div>


@if( Input::get('tab') == 5 )
<div class="modal fade form-modal" id="popupModal" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-md">
    <div class="modal-content">

    <form class="form-horizontal form-modal-submit" method="post" action="{{ URL::route('app.customers.product') }}">
        {{ csrf_field() }}

        <input type="hidden" name="pid" value="">
        <input type="hidden" name="sid" value="{{ $info->id }}">
        <input type="hidden" name="tab" value="{{ Input::get('tab') }}">

        <div class="modal-body">

            <div class="form-group">
                <h5 class="col-md-12 sbold">Customer Product</h5>
                <div class="col-md-12">
                    <h5>Item Name <span class="text-danger">*</span></h5>
                    {{ Form::select('item', ['' => 'Select Product'] + $post->select_posts(['post_type' => 'product']), '', ['class' => 'form-control select2']) }}
                    <span id="item" class="msg-error"></span>               
                </div>

                <div class="col-md-12">
                    <h5>Customer Price <span class="text-danger">*</span></h5>
                    <input type="number" class="form-control text-right rtip" name="customer_price" placeholder="0.00" min="1" step="any">   
                    <span id="customer_price" class="msg-error"></span>               
                </div>


            </div>

        </div>

        <div class="modal-footer">
            <button type="submit" class="btn btn-primary confirm uppercase" data-target=".form-modal-submit"><i class="fa fa-check"></i> Save Changes</button>
            <button class="btn btn-default uppercase" aria-hidden="true" data-dismiss="modal" class="close" type="button">Close</button> 
            <span class="msg-error"></span>           
        </div>

        </form>           
    </div>
  </div>
</div>
@endif

@if( Input::get('tab') == 1 )
<div class="form-actions-fixed">
    <button type="submit" class="btn btn-primary confirm" data-target=".form-submit"><i class="fa fa-check"></i> Save Changes</button>        
</div>
@endif
@endsection


@section('top_style')
<style>
.table-hover>tbody>tr:hover>td.editable:hover {
    cursor: pointer;
    background: #fff6a4!important;
}  

.page-header.navbar.navbar-fixed-top, .page-header.navbar.navbar-static-top {
    z-index: 3;
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

var blockUImsg = 'Updating supplier details ...';

var id     = $('[name="id"]'),
    key    = $('[name="key"]'),
    target = $('[name="target"]'),
    modal  = $('.form-modal');


$(document).on('click','.btn-add', function(e) {
    e.preventDefault();
    modal.find("input[type=number], input[name=id]").val('');
    $('[name="item"]').val('').trigger('change');

    modal.modal('show');
});


$(document).on('click','.btn-edit', function(e) {
    e.preventDefault();
    blockUI('Fetching product details ...');
    var url = $(this).attr('href');
    $.get(url, function(response) {
        var data = JSON.parse(response);
         $.each( data, function( key, value ) {
          $('[name="'+key+'"]').val( value );
          $('[name="'+key+'"]').val(value).trigger('change');
        });
        modal.modal('show');
        $.unblockUI();  
    });
});



$(document).on('click', '.confirm', function(e) {
    
    e.preventDefault();

    var $this    = $(this);
    var formData = $(this).data('target');
    var url      = $(formData).attr('action');

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
            modal.modal('hide');
            initTable();
        }         
        $.unblockUI();  
    });

});

</script>
@stop
