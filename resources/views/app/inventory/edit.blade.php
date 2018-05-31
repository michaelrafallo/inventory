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
                <form action="" class="form-horizontal form-submit" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}


                    <div class="col-md-3">



                    <div class="fileinput fileinput-new" data-provides="fileinput">
                        <div class="fileinput-preview thumbnail" data-trigger="fileinput"> 
                            <img src="{{ has_photo($info->picture) }}">
                        </div>
                        <div>
                            <span class="btn blue btn-outline btn-file btn-xs">
                            <span class="fileinput-new"> Select image </span>
                            <span class="fileinput-exists"> Change image </span>
                            <input type="file" name="file" accept="image/*"> </span>
                        </div>
                    </div>

                        <div class="clearfix"></div>
                        <label class="mt-checkbox margin-top-10">
                            <input type="checkbox" name="status" value="actived" {{ checked($info->post_status, 'actived') }}> Active
                            <span></span>
                        </label>
                        


                        <ul class="ver-inline-menu tabbable margin-top-20 navigation-tab">
                            <li class="{{ actived(Input::get('tab'), 1) }}">
                                <a href="?tab=1">
                                    <i class="fa fa-shopping-cart"></i> Product Info</a>
                                <span class="after"> </span>
                            </li>
                            <li class="{{ actived(Input::get('tab'), 2) }}">
                                <a href="?tab=2">
                                    <i class="fa fa-users"></i> Product Suppliers</a>
                            </li>
                            <li class="{{ actived(Input::get('tab'), 3) }}">
                                <a href="?tab=3">
                                    <i class="fa fa-money"></i> Purcahse Order History</a>
                            </li>
                            <li class="{{ actived(Input::get('tab'), 4) }}">
                                <a href="?tab=4">
                                    <i class="fa fa-money"></i> Sales Order History</a>
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




<div class="form-actions-fixed">
    <button type="submit" class="btn btn-primary btn-save" data-target=".form-submit"><i class="fa fa-check"></i> Save Changes</button>        
</div>


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
.mt-repeater .mt-repeater-item {
    padding-bottom: 0px!important;
}
.mt-repeater-delete {
    margin: 5px 0!important;
}
.table { margin: 0 } 
.col { display: none; }
.mt-repeater .mt-repeater-item:first-child .col {
    display: block;
}
</style>
@stop

@section('bottom_style')
@stop

@section('bottom_plugin_script') 
@stop

@section('bottom_script')
<script>

var blockUImsg = 'Updating product details ...';

var id     = $('[name="id"]'),
    key    = $('[name="key"]'),
    target = $('[name="target"]'),
    modal  = $('.form-modal');


$(document).on('click','.btn-add', function(e) {
    e.preventDefault();
    modal.find("input[type=text], textarea, input[name=id]").val('');
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
        });
        modal.modal('show');
        $.unblockUI();  
    });
});


$(".form-submit").on('submit', function(e) {
    e.preventDefault();

    var formData = $(this),
        url      = formData.attr('action');

    blockUI(blockUImsg);

    $.ajax({
        url: url, // Url to which the request is send
        type: "POST",             // Type of request to be send, called as method 
        data: new FormData(this), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        contentType: false,       // The content type used when sending data to the server.
        cache: false,             // To unable request pages to be cached
        processData:false,        // To send DOMDocument or non processed data file it is set to false
        success: function(response)   // A function to be called if request succeeds
        {
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
                init_repeater();
                app_init();
                initTable();
            }
            $.unblockUI();  

        }
    });
});





</script>
@stop
