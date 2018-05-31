@extends('layouts.app')

@section('content')
<!-- BEGIN PAGE BAR -->
<div class="page-bar margin-bottom-20">
    <ul class="page-breadcrumb uppercase">
        <li>
           <h1 class="page-title">Inventory</h1>
        </li>
    </ul>
</div>
<!-- END PAGE BAR -->

@include('notification')

<div class="portlet light bordered">

    @include($view.'.search.stocks')

    <div class="table-responsive">
    <table class="table table-hover table-striped table-bordered">
        <thead>
            <tr>
                <th>Reference No.</th>
                <th>Item Name</th>
                <th width="100" class="text-right stocks">QTY</th>
                <th width="1">UoM</th>
                <th width="100" class="text-right price">Price</th>
                <th width="120" class="text-center">Date</th> 
                <th>Received / Delivered</th>                               
                <th width="100" class="text-center">Transaction</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rows as $row)
            <tr>
                <td>{{ $row->reference_no }}</td>
                <td>
                    <h5 class="no-margin">{{ $post->find($row->item)->post_title }}</h5>
                </td>
                <td class="text-right stocks">{!! number_format($row->qty) !!}</td>
                <td>{{ $row->uom }}</td>
                <td class="text-right price" width="10%">
                    {{ amount_formatted($row->price) }}     
                </td>
                <td class="text-center">
                    {{ date_formatted($row->date) }}
                </td>
                <td>{{ $post->find($row->person_id)->post_title }}</td>
                <td class="text-center">{{ status_ico($row->type) }}</td>
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
<style>

</style>
@stop

@section('bottom_style')
@stop

@section('bottom_plugin_script') 
@stop

@section('bottom_script')
@stop
