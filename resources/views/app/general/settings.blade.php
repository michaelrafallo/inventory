@extends('layouts.app')

@section('content')
<!-- BEGIN PAGE BAR -->
<div class="page-bar margin-bottom-20">
    <ul class="page-breadcrumb uppercase">
        <li>
            <h1 class="page-title">General Settings</h1>
        </li>
    </ul>
</div>
<!-- END PAGE BAR -->


<div class="row">

    <div class="col-md-12">

        @include('notification')

        <div class="portlet light bordered">
            <div class="portlet-body form row">
                <form class="form-horizontal form-save" action="" role="form" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}

                    <div class="col-md-3">

                        <ul class="ver-inline-menu tabbable margin-top-20 navigation-tab">
                            <li class="active">
                                <a data-toggle="tab" href="#system">
                                    <i class="fa fa-gears"></i> System</a>
                                <span class="after"> </span>
                            </li>
                            <li class="">
                                <a data-toggle="tab" href="#reports">
                                    <i class="fa fa-print"></i> Reports</a>
                            </li>
                            <li class="">
                                <a data-toggle="tab" href="#inventory">
                                    <i class="fa fa-edit"></i> Inventory</a>
                            </li>
                        </ul>

                    </div>
                    <div class="col-md-9">
                        <div class="tab-content load-details">

                            @include('app.general.settings.details')

                        </div>
                    </div>

                    <button type="submit" class="hide"></button>        

                </form>
            </div>
        </div>
    </div>

</div>

<div class="form-actions-fixed">
    <button type="submit" class="btn btn-primary btn-save" data-target=".form-save"><i class="fa fa-check"></i> Save Changes</button>        
</div>
@endsection



@section('top_style')
@stop

@section('bottom_style')
@stop

@section('bottom_plugin_script') 
<script>
var blockUImsg = 'Updating general settings ...';

</script>

@stop
