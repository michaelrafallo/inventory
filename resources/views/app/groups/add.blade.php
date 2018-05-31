@extends('layouts.app')

@section('content')
<!-- BEGIN PAGE BAR -->
<div class="page-bar margin-bottom-20">
    <ul class="page-breadcrumb uppercase">
        <li>
           <h1 class="page-title">Add Group</h1>
        </li>
    </ul>
    <div class="page-toolbar">
        <div class="btn-group pull-right">
            <a href="{{ URL::route('app.groups.index') }}" class="btn btn-default margin-top-20"> 
                <i class="fa fa-angle-left"></i> All Groups
            </a>
        </div>
    </div>
</div>
<!-- END PAGE BAR -->


@include('notification')



<div class="row">

    <div class="col-md-8 col-sm-8 col-centered">

        <div class="portlet light bordered">

            <div class="portlet-body form">
                <!-- BEGIN FORM-->
                <form action="" class="form-horizontal form-submit" method="post">

                    {{ csrf_field() }}
                    <input type="hidden" name="op" value="1">   

                    <div class="form-body">

                        <div class="form-group">
                            <label class="col-md-3 control-label">Group Name <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="text" class="form-control rtip" name="name" placeholder="Group Name" value="">
                                <!-- START error message -->
                                {!! $errors->first('name','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
                                <!-- END error message -->
                            </div>
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
    <button type="submit" class="btn btn-primary btn-save" data-target=".form-submit"><i class="fa fa-check"></i> Save Group</button>        
</div>
@endsection


@section('top_style')
@stop

@section('bottom_style')
<link href="{{ asset('assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css') }}" rel="stylesheet" type="text/css"/>
@stop

@section('bottom_plugin_script') 
<script src="{{ asset('assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js') }}" type="text/javascript"></script>
@stop

@section('bottom_script')
@stop
