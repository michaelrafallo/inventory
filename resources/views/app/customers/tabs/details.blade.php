<div class="row margin-top-20">
    <div class="col-md-7">
        <small class="text-muted uppercase">Customer : </small><br>
        {{ $info->post_title }}
    </div>
    <div class="col-md-5">
        <small class="text-muted uppercase">Credit Balance : </small><br>
        <b>{{ amount_formatted($credit_balance) }}</b>
    </div>  
</div>

<hr>


@if( $tab == 1 )
    <h1 class="page-title uppercase">Customer Info</h1>    
    @include($view.'.tabs.info')
@endif

@if( $tab == 2 )
    <h1 class="page-title uppercase">Order History 

    @if( App\Permission::has_access('sales-orders', ['add_edit']) )
    <a href="{{ URL::route('app.sales-orders.add', ['customer' => $info->id]) }}" class="btn-xs btn"><i class="fa fa-plus"></i> Create <b>Delivery Order</b></a>
    @endif

    </h1>   
    @include($view.'.tabs.orders')
@endif

@if( $tab == 3 )
    <h1 class="page-title uppercase">Payment History</h1>    
    @include($view.'.tabs.payment')
@endif

@if( $tab == 4 )
    <h1 class="page-title uppercase">Statement of Account 
    
    @if( App\Permission::has_access('customers', ['report']) )
    <a href="{{ URL::route('app.customers.soa', $info->id) }}" class="btn-xs btn btn-preview"><i class="fa fa-print"></i> Generate <b>SOA</b></a>
    @endif

    </h1>    
    @include($view.'.tabs.soa')
@endif

@if( $tab == 5 )
    <h1 class="page-title uppercase">Pricelist 
    
    @if( App\Permission::has_access('customers', ['report']) )
    <a href="{{ URL::route($view.'.index', ['preview' => 'pricelist', 'post_id' => $info->id]) }}" class="btn-xs btn btn-preview"><i class="fa fa-print"></i> Generate <b>Pricelist</b></a> 
    @endif

    <a href="" class="btn-xs btn btn-add"><i class="fa fa-plus"></i> Add New</a>

    </h1>    
    @include($view.'.tabs.pricelist')
@endif
