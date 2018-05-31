@extends('layouts.app')

@section('content')


<!-- BEGIN PAGE BAR -->
<div class="page-bar margin-bottom-20">
    <ul class="page-breadcrumb uppercase">
        <li>
           <h1 class="page-title">Product Category</h1>
        </li>
    </ul>
    <div class="page-toolbar">
        <div class="btn-group pull-right">
            <a href="{{ URL::route($view.'.index') }}" class="btn btn-default margin-top-20"> 
                <i class="fa fa-plus"></i> All Products
            </a>
        </div>
    </div>
</div>
<!-- END PAGE BAR -->

@include('notification')

<div class="portlet light bordered">

    <div class="row">

        <div class="col-md-9">

            <div class="table-responsive">
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th>Category Name</th>
                        <th class="text-center">Status</th>
                        <th class="text-right" width="150"><a href="{{ URL::route($view.'.category') }}" class="btn btn-xs btn-primary"><i class="fa fa-plus"></i> Add New </a></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $order = 0; ?>
                    @foreach($rows as $row)
                    <?php $postmeta = get_meta( $row->postMetas()->get() ); ?>
                    <tr>
                        <td>
                        {{ $row->post_title }}<br>
                        <small class="text-muted">ID : {{ $row->id }}</small>
                        </td>
                        <td class="text-center">{{ status_ico($row->post_status) }}</td>
                        <td>
                            <div class="text-right">                    

                                <a href="{{ URL::route($view.'.category', ['pid' => $row->id]) }}" class="btn green btn-xs uppercase">Edit</a>

                                <a href="#" class="popup btn btn-xs btn-default uppercase"
                                    data-href="{{ URL::route($view.'.destroy', [$row->id, query_vars()]) }}" 
                                    data-toggle="modal"
                                    data-target=".popup-modal" 
                                    data-title="Confirm Delete"
                                    data-body="Are you sure you want to delete ID: <b>#{{ $row->id }}</b>?">Delete</a> 

                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>        

            @if( ! count($rows) )
            <p class="well">No record found!</p>
            @endif

            </div>
        </div>

        <div class="col-md-3">
                    
            <form action="" class="form-horizontal" method="post">

            {{ csrf_field() }}
            <input type="hidden" name="gid" value="{{ Input::get('gid') }}">

            <div class="form-body">

                <div class="form-group">
                    <div class="col-md-12">
                        <h5>Category Name<span class="required">*</span></h5>
                        <input type="text" class="form-control rtip" name="name" placeholder="Category Name" value="{{ Input::old('name', @$info->post_title ) }}">
                        <!-- START error message -->
                        {!! $errors->first('name','<span class="help-block"><span class="text-danger">:message</span></span>') !!}
                        <!-- END error message -->
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-12">
                        <label class="mt-checkbox">
                            <input type="checkbox" name="status" value="actived" {{ checked(@$info->post_status ? $info->post_status : 'actived', 'actived') }}> Active
                            <span></span>
                        </label>
                    </div>
                </div>

            </div>
            <div class="form-actions">
                <div class="row">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Save</button>
                    </div>
                </div>
            </div>
        </form>

        </div>

    </div>

</div>

@endsection


@section('top_style')
@stop

@section('bottom_style')
@stop

@section('bottom_plugin_script') 
@stop

@section('bottom_script')
<script>
$(document).on('click','.popup', function() {
    var $this  = $(this);
    var $title = $this.data('title');
    var $body  = $this.data('body');
    var $href  = $this.data('href');
    var $target = $(this).attr('target');

    $target = ($target == '_blank') ? '_blank' : '';

    $('.popup-modal a.confirm').attr('data-target', $target);
    $('.popup-modal a.confirm').attr('data-href', $href);
    $('.popup-modal .modal-title').html($title);
    $('.popup-modal .modal-body').html($body);
});

$(document).on('click','.popup-modal .modal-footer a', function(e) {
    e.preventDefault();
    $(this).html('Processing ...').attr('disabled', 'disabled');

    var $target = $(this).attr('data-target');
    var $href = $(this).attr('data-href');

    location.href = $href;   

});
    
</script>
@stop
