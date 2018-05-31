@extends('layouts.app')

@section('content')
<!-- BEGIN PAGE BAR -->
<div class="page-bar margin-bottom-20">

    <div class="row">
        <div class="col-md-3">
            <ul class="page-breadcrumb uppercase">
                <li>
                   <h1 class="page-title">{{ $label }}</h1>
                </li>
            </ul>
        </div>
        <div class="col-md-6">
            <div class="margin-top-30">
                <div class="col-md-4">
                    Total : <br>
                    <b class="h4">{{ amount_formatted($total) }}</b>
                </div>
                <div class="col-md-4">
                    Paid : <br>
                    <b class="h4">{{ amount_formatted($paid) }}</b>
                </div>
                <div class="col-md-4">
                    Balance : <br>
                    <b class="h4">{{ amount_formatted($balance) }}</b>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="page-toolbar">
                <div class="btn-group pull-right">
                    
                    @if( App\Permission::has_access('purchase-orders', ['add_edit']) )
                    <a href="{{ URL::route($view.'.add') }}" class="btn blue margin-top-20"> 
                        <i class="fa fa-plus"></i> Create {{ $single }}
                    </a>
                    @endif

                </div>
            </div>
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
                <th width="320">Supplier</th>
                <th>Reference No.</th>
                <th class="text-right">Total</th>
                <th class="text-right">Paid</th>
                <th class="text-right">Balance</th>
                <th class="text-center" width="100">Date</th>
                <th class="text-center">Status</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($rows as $row)
            <?php $postmeta = get_meta( $row->postMetas()->get() ); ?>
            <tr>
                <td height="70">
                    <a href="{{ URL::route('app.suppliers.edit', [$postmeta->supplier, 'tab' => 1]) }}" class="sbold">{{ $post->find($postmeta->supplier)->post_title }}</a>
                    
                    <div class="btn-actions">                    
                        <div class="actions">                    
                        @if( Input::get('type') == 'trash')

                            @if( App\Permission::has_access('purchase-orders', ['trash_restore']) )
                            <a href="#" class="popup btn btn-xs uppercase margin-top-10"
                                data-href="{{ URL::route($view.'.restore', [$row->id, query_vars()]) }}" 
                                data-toggle="modal"
                                data-target=".popup-modal" 
                                data-title="Confirm Restore"
                                data-body="Are you sure you want to restore ID: <b>#{{ $row->id }}</b>?">Restore</a> 

                            <a href="#" class="popup btn btn-xs uppercase margin-top-10"
                                data-href="{{ URL::route($view.'.destroy', [$row->id, query_vars()]) }}" 
                                data-toggle="modal"
                                data-target=".popup-modal" 
                                data-title="Confirm Delete Permanently"
                                data-body="Are you sure you want to delete permanently ID: <b>#{{ $row->id }}</b>?">Delete Permanently</a>
                            @endif

                        @else
                            @if( App\Permission::has_access('purchase-orders', ['add_edit']) )
                            <a href="{{ URL::route($view.'.edit', $row->id) }}" class="btn btn-xs uppercase margin-top-10">Edit</a>
                            @endif

                            @if( App\Permission::has_access('purchase-orders', ['report']) )
                            <a href="{{ URL::route($view.'.edit', [$row->id, 'preview' => 1]) }}" class="btn-preview btn btn-xs uppercase margin-top-10">Generate PO</a>
                            @endif

                            @if( App\Permission::has_access('purchase-orders', ['trash_restore']) )
                                @if($row->post_status == 'unpaid' && $postmeta->fulfilled == 'unfulfilled')
                                <a href="#" class="popup btn btn-xs uppercase margin-top-10"
                                    data-href="{{ URL::route($view.'.delete', [$row->id, query_vars()]) }}" 
                                    data-toggle="modal"
                                    data-target=".popup-modal" 
                                    data-title="Confirm Delete"
                                    data-body="Are you sure you want to move to trash ID: <b>#{{ $row->id }}</b>?">Move to trash</a> 
                                @else
                                <button class="btn btn-xs btn-default uppercase margin-top-10" disabled>Move to trash</button>
                                @endif    
                            @endif

                        @endif  
                        </div>
                    </div>

                </td>
                <td> 
                    {{ $postmeta->reference_no }}
                </td>
                <td class="text-right">{{ amount_formatted($postmeta->total) }}</td>
                <td class="text-right">{{ amount_formatted($postmeta->total_paid) }}</td>
                <td class="text-right">{{ amount_formatted($postmeta->balance) }}</td>
                <td class="text-center">
                    {{ date_formatted($postmeta->date) }}<br>
                    <small class="text-muted">{{ time_ago($postmeta->date) }}</small>
                </td>
                <td class="text-center">{{ status_ico($postmeta->fulfilled) }}<br> {{ status_ico($row->post_status) }}</td>
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
