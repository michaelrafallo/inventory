@extends('layouts.app')

@section('content')
<!-- BEGIN PAGE BAR -->
<div class="page-bar margin-bottom-20">
    <ul class="page-breadcrumb uppercase">
        <li>
           <h1 class="page-title">Edit {{ $single }}
            <a href="{{ URL::route($view.'.add') }}" class="btn-xs"><i class="fa fa-plus"></i> Add New</a>
           </h1>
        </li>
    </ul>
    <div class="page-toolbar">
        <div class="btn-group pull-right">
            <a href="{{ URL::route($view.'.index') }}" class="btn btn-default margin-top-20"> 
                <i class="fa fa-angle-left"></i> All {{ $label }}
            </a>
        </div>
    </div>
</div>
<!-- END PAGE BAR -->






<div class="row">

    <div class="col-md-12 col-centered">
        
        @include('notification')

        <div class="portlet light bordered">

            <div class="portlet-body form">
                <!-- BEGIN FORM-->
                <form action="" class="form-horizontal form-save" method="post">

                    {{ csrf_field() }}
                    <div class="form-body">

                        <div class="form-group">
                            <label class="col-md-3 control-label">Name <span class="required">*</span></label>
                            <div class="col-md-5">
                                <input type="text" class="form-control rtip" name="name" placeholder="Name" value="{{ Input::old('name', $info->post_title) }}">
                                <span id="name" class="msg-error"></span>
                                <!-- START error message -->
                                {!! $errors->first('name','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
                                <!-- END error message -->
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"></label>
                            <div class="col-md-4">
                                <h5>Amount <span class="required">*</span></h5>
                                <input type="number" class="form-control rtip" name="amount" placeholder="Amount" value="{{ Input::old('amount', $info->amount) }}" min="0" step="any">
                                <span id="amount" class="msg-error"></span>
                                <!-- START error message -->
                                {!! $errors->first('amount','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
                                <!-- END error message -->
                            </div>
                            <div class="col-md-4">
                                <h5>Date <span class="required">*</span></h5> 
                                <input type="text" class="form-control rtip datepicker" name="date" placeholder="MM-DD-YYYY" value="{{ date_formatted_b(Input::old('date', $info->date)) }}">
                                <span id="date" class="msg-error"></span>
                                <!-- START error message -->
                                {!! $errors->first('date','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
                                <!-- END error message -->
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Remarks</label>
                            <div class="col-md-8">
                                <textarea class="form-control" name="remarks" placeholder="Remarks" rows="4">{{ Input::old('remarks', $info->remarks) }}</textarea>
                                <!-- START error message -->
                                {!! $errors->first('remarks','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
                                <!-- END error message -->
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-offset-3 col-md-8">
                            <label class="mt-checkbox">
                                <input type="checkbox" name="status" value="actived" {{ checked($info->post_status, 'actived') }}> Active
                                <span></span>
                            </label>
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
    <button type="submit" class="btn btn-primary btn-save" data-target=".form-save"><i class="fa fa-check"></i> Save Changes</button>        
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
blockUImsg = 'Updating expense details ...';
</script>
@stop
