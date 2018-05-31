@extends('layouts.app')

@section('content')
<!-- BEGIN PAGE BAR -->
<div class="page-bar margin-bottom-20">
    <ul class="page-breadcrumb uppercase">
        <li>
            <h1 class="page-title">Dashboard</h1>
        </li>
    </ul>
</div>
<!-- END PAGE BAR -->


<div class="row">
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <?php 
        $url = '#';
        if( App\Permission::has_access('sales-orders') ) {
            $url = URL::route('app.sales-orders.index', 'post_status=paid|!=');
        }
        ?>
        <a class="dashboard-stat dashboard-stat-v2 blue {{ $url != '#' ? 'link-stat' : '' }}" href="{{ $url }}">
            <div class="visual">
                <i class="fa fa-credit-card"></i>
            </div>
            <div class="details">
                <div class="number"><span>{{ amount_formatted($unpaid_customers) }}</span></div>
                <div class="desc">( {{ $unpaid_customer_count }} ) Unpaid Customer{{ is_plural($unpaid_customer_count) }} </div>
            </div>
        </a>
    </div>   

    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <?php 
        $url = '#';
        if( App\Permission::has_access('purchase-orders') ) {
            $url = URL::route('app.purchase-orders.index', 'post_status=paid|!=');
        }
        ?>
        <a class="dashboard-stat dashboard-stat-v2 blue {{ $url != '#' ? 'link-stat' : '' }}" href="{{ $url }}"">
            <div class="visual">
                <i class="fa fa-credit-card"></i>
            </div>
            <div class="details">
                <div class="number"><span>{{ amount_formatted($unpaid_suppliers) }}</span></div>
                <div class="desc">( {{ $unpaid_supplier_count }} ) Unpaid Supplier{{ is_plural($unpaid_supplier_count) }}</div>
            </div>
        </a>
    </div>   

    <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
        <?php 
        $url = '#';
        if( App\Permission::has_access('suppliers') ) {
            $url = URL::route('app.suppliers.index');
        }
        ?>
        <a class="dashboard-stat dashboard-stat-v2 green {{ $url != '#' ? 'link-stat' : '' }}" href="{{ $url }}">
            <div class="visual">
                <i class="fa fa-users"></i>
            </div>
            <div class="details">
                <div class="number"><span>{{ number_format($supplier_count) }}</span></div>
                <div class="desc">Suppliers </div>
            </div>
        </a>
    </div>  
    <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
        <?php 
        $url = '#';
        if( App\Permission::has_access('customers') ) {
            $url = URL::route('app.customers.index');
        }
        ?>
        <a class="dashboard-stat dashboard-stat-v2 green {{ $url != '#' ? 'link-stat' : '' }}" href="{{ $url }}"">
            <div class="visual">
                <i class="fa fa-users"></i>
            </div>
            <div class="details">
                <div class="number"><span>{{ number_format($customer_count) }}</span></div>
                <div class="desc">Customers </div>
            </div>
        </a>
    </div>  
    <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
        <?php 
        $url = '#';
        if( App\Permission::has_access('products') ) {
            $url = URL::route('app.inventory.index');
        }
        ?>
        <a class="dashboard-stat dashboard-stat-v2 purple {{ $url != '#' ? 'link-stat' : '' }}" href="{{ $url }}">
            <div class="visual">
                <i class="fa fa-shopping-cart"></i>
            </div>
            <div class="details">
                <div class="number"><span>{{ number_format($product_count) }}</span></div>
                <div class="desc">Products </div>
            </div>
        </a>
    </div>  

</div>

<div class="row">

    <div class="col-md-6">
        <!-- BEGIN PORTLET-->
        <div class="portlet light bordered">
            <div class="portlet-body">
    <div id="container"></div>
            </div>
        </div>
        <!-- END PORTLET-->
    </div>    

    <div class="col-md-6">
        <!-- BEGIN PORTLET-->
        <div class="portlet light bordered text-right">

            <small class="uppercase"><i class="cb badge-cal"></i>CUSTOMER's BILLS </small>
            <small class="uppercase"><i class="mb badge-cal"></i> MY BILLS</small>


            <div class="portlet-body margin-top-10">
                <div id="calendar"></div>
            </div>
        </div>
        <!-- END PORTLET-->
    </div>    

</div>
@endsection



@section('top_style')
<style>
.badge-cal { 
    display: inline-block;
    height: 10px;
    width: 10px;
    border-radius: 10px;
    margin: 0 10px 0;
}
.cb {
    background: #0023e6;    
}
.mb {
    background: #ff0000;    
}
.dashboard-stat {
    border-right: 3px solid #bbbbbb;
    border-bottom: 3px solid #bbbbbb;
    border-radius: 10px;    
    cursor: default!important;
}
.link-stat:hover {    
    opacity: 0.7;
    cursor: pointer!important;
}
</style>
@stop

@section('bottom_style')
@stop

@section('bottom_plugin_script')
<!-- FULLCALENDAR -->
<link href="{{ asset('assets/global/plugins/fullcalendar/fullcalendar.min.css') }}" rel="stylesheet" type="text/css" />
<script src="{{ asset('assets/global/plugins/fullcalendar/fullcalendar.min.js') }}" type="text/javascript"></script>

<!-- HIGHCHARTS -->
<script src="{{ asset('plugins/highcharts/highcharts.src.js') }}"></script>
<script src="{{ asset('plugins/highcharts/exporting.js') }}"></script>
<!-- optional -->
<script src="{{ asset('plugins/highcharts/offline-exporting.js') }}"></script>
@stop

@section('bottom_script') 
<script>
jQuery(document).ready(function() {    
    var date = new Date();
    var d = date.getDate();
    var m = date.getMonth();
    var y = date.getFullYear();

    var h = {};

    $('#calendar').fullCalendar({ 
        defaultView: 'month', 
        editable: false,
        droppable: false,
        events: <?php echo $events; ?>
    });
});    


Highcharts.chart('container', {
    chart: {
        type: 'column'
    },
    title: {
        text: 'Monthly Summary Report'
    },
    subtitle: {
        text: 'Statistics Report for 2017'
    },
    xAxis: {
        categories: <?php echo $categories; ?>,
        crosshair: true
    },
    yAxis: {
        min: 0,
        title: {
            text: 'Summary Report'
        }
    },
    tooltip: {
        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
            '<td style="padding:0">{{ $currency }} <b>{point.y:,.2f}</b></td></tr>',
        footerFormat: '</table>',
        shared: true,
        useHTML: true
    },
    plotOptions: {
        column: {
            pointPadding: 0.2,
            borderWidth: 0
        }
    },
    series: [{
        name: 'Purchased Order',
        data: <?php echo $po; ?>

    }, {
        name: 'Sales',
        data: <?php echo $so; ?>
    }, {
        name: 'Expense',
        data: <?php echo $ex; ?>

    }]
});
</script>
@stop
