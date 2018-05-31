@extends('layouts.app')

@section('content')
<!-- BEGIN PAGE BAR -->
<div class="page-bar margin-bottom-20">
    <ul class="page-breadcrumb uppercase">
        <li>
           <h1 class="page-title">{{ $label }}</h1>
        </li>
    </ul>
    <div class="page-toolbar">
        <div class="btn-group pull-right">
            @if( App\Permission::has_access('suppliers', ['add_edit']) )
            <a href="{{ URL::route($view.'.add') }}" class="btn blue margin-top-20"> 
                <i class="fa fa-plus"></i> Add {{ $single }}
            </a>
            @endif
        </div>
    </div>
</div>
<!-- END PAGE BAR -->

@include('notification')

<div class="portlet light bordered">

   @include($view.'.search')

    <div class="table-responsive">
    <table class="table table-hover table-striped">
        <thead>
            <tr>
                <th width="50%">Name</th>
                <th class="text-center">Updated At</th>
                <th class="text-center">Status</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($rows as $row)
            <tr>
                <td>
                    {{ $row->post_title }}
                </td>
                <td class="text-center">
                    {{ date_formatted($row->created_at) }}<br>
                    <small class="text-muted">{{ time_ago($row->created_at) }}</small>
                </td>
                <td class="text-center">{{ status_ico($row->post_status) }}</td>
                <td>
                    <div class="text-right">                    

                    @if( Input::get('type') == 'trash')

                        @if( App\Permission::has_access('suppliers', ['trash_restore']) )
                        <a href="#" class="popup btn btn-xs btn-primary uppercase"
                            data-href="{{ URL::route($view.'.restore', [$row->id, query_vars()]) }}" 
                            data-toggle="modal"
                            data-target=".popup-modal" 
                            data-title="Confirm Restore"
                            data-body="Are you sure you want to restore ID: <b>#{{ $row->id }}</b>?">Restore</a> 

                        <a href="#" class="popup btn btn-xs btn-default uppercase"
                            data-href="{{ URL::route($view.'.destroy', [$row->id, query_vars()]) }}" 
                            data-toggle="modal"
                            data-target=".popup-modal" 
                            data-title="Confirm Delete Permanently"
                            data-body="Are you sure you want to delete permanently ID: <b>#{{ $row->id }}</b>?">Delete Permanently</a>
                        @endif

                    @else
                        
                        @if( App\Permission::has_access('suppliers', ['add_edit']) )
                        <a href="{{ URL::route($view.'.edit', [$row->id, 'tab' => 1]) }}" class="btn green btn-xs uppercase">Edit</a>
                        @endif

                        @if( App\Permission::has_access('suppliers', ['trash_restore']) )
                            @if($row->post_status != 'actived')
                            <a href="#" class="popup btn btn-xs btn-default uppercase"
                                data-href="{{ URL::route($view.'.delete', [$row->id, query_vars()]) }}" 
                                data-toggle="modal"
                                data-target=".popup-modal" 
                                data-title="Confirm Delete"
                                data-body="Are you sure you want to move to trash ID: <b>#{{ $row->id }}</b>?">Move to trash</a> 
                            @else
                            <button class="btn btn-xs btn-default uppercase" disabled>Move to trash</button>
                            @endif    
                        @endif

                    @endif  


                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>        

    @if( ! $count )
    <p class="well">No record found!</p>
    @endif

    </div>

    {{ $rows->links() }}

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
