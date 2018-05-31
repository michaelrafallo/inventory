<div class="row margin-top-20">
	<div class="col-md-6">
		<small class="text-muted">NAME :</small>
		<h4 class="no-margin">{{ $info->post_title }}</h4>		
	</div>
	<div class="col-md-2 text-right">
		<small class="text-muted">QTY :</small>
		<h5 class="no-margin"><b>{{ number_format($info->quantity) }}</b> {{ str_metric($info->unit_of_measure, $info->quantity) }}</h5>				
	</div>
	<div class="col-md-2 text-right">
		<small class="text-muted text-primary">NORMAL PRICE :</small>
		<h5 class="no-margin">{{ amount_formatted($info->normal_price) }}</h5>				
	</div>
	<div class="col-md-2 text-right">
		<small class="text-muted text-danger">SALES PRICE :</small>
		<h5 class="no-margin">{{ amount_formatted($info->sales_price) }}</h5>				
	</div>
</div>

<hr>

@if( $tab == 1 )
    <h1 class="page-title uppercase">Product Info</h1>    
    @include($view.'.tabs.info')
@endif

@if( $tab == 2 )
    <h1 class="page-title uppercase">Product Suppliers</h1>   
    @include($view.'.tabs.suppliers')
@endif

@if( $tab == 3 )
    <h1 class="page-title uppercase">Purchase Order History</h1>    
    @include($view.'.tabs.orders')
@endif

@if( $tab == 4 )
    <h1 class="page-title uppercase">Sales Order History</h1>    
    @include($view.'.tabs.sales')
@endif

