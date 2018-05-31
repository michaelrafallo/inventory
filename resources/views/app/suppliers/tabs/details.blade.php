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
    <h1 class="page-title uppercase">Supplier Info</h1>    
    @include('app.suppliers.tabs.info')
@endif

@if( $tab == 2 )
    <h1 class="page-title uppercase">Supplier Products     
    <a href="" class="btn-xs btn-add"><i class="fa fa-plus"></i> Add New</a>
    </h1>   
    @include('app.suppliers.tabs.products')
@endif

@if( $tab == 3 )
    <h1 class="page-title uppercase">Order History 
    
    @if( App\Permission::has_access('purchase-orders', ['add_edit']) )
    <a href="{{ URL::route('app.purchase-orders.add', ['supplier' => $info->id]) }}" class="btn-xs"><i class="fa fa-plus"></i> Create <b>Purchase Order</b></a>
    @endif

    </h1>   
    @include('app.suppliers.tabs.orders')
@endif

@if( $tab == 4 )
    <h1 class="page-title uppercase">Payment History 
    
    @if( App\Permission::has_access('purchase-orders', ['add_edit']) )
    <a href="{{ URL::route('app.purchase-orders.add', ['supplier' => $info->id]) }}" class="btn-xs"><i class="fa fa-plus"></i> Create <b>Purchase Order</b></a>
    @endif

    </h1>   
    @include('app.suppliers.tabs.payments')
@endif
