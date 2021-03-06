@extends('layouts.app')

@section('content')
<!-- BEGIN PAGE BAR -->
<div class="page-bar margin-bottom-20">
    <ul class="page-breadcrumb uppercase">
        <li>
           <h1 class="page-title">Add {{ $single }}</h1>
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
                <form action="" class="form-horizontal form-submit" method="post" enctype="multipart/form-data">

                    {{ csrf_field() }}

                    <div class="form-body">

                        <div class="form-group">
                            <label class="col-md-3 control-label">Name <span class="required">*</span></label>
                            <div class="col-md-5">
                                <input type="text" class="form-control rtip" name="name" placeholder="Name" value="{{ Input::old('name') }}">
                                <!-- START error message -->
                                {!! $errors->first('name','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
                                <!-- END error message -->
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Address</label>
                            <div class="col-md-5">
                                <textarea name="address" class="form-control" rows="3" placeholder="Address">{{ Input::old('address') }}</textarea>
                                <!-- START error message -->
                                {!! $errors->first('address','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
                                <!-- END error message -->
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Email Address</label>
                            <div class="col-md-5">
                                <input type="text" class="form-control" name="email_address" placeholder="Email Address" value="{{ Input::old('email_address') }}">
                                <!-- START error message -->
                                {!! $errors->first('email_address','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
                                <!-- END error message -->
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Mobile Number</label>
                            <div class="col-md-5">
                                <input type="text" class="form-control" name="mobile_number" placeholder="Mobile Number" value="{{ Input::old('mobile_number') }}">
                                <!-- START error message -->
                                {!! $errors->first('mobile_number','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
                                <!-- END error message -->
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Telephone Number</label>
                            <div class="col-md-5">
                                <input type="text" class="form-control" name="telephone_number" placeholder="Telephone Number" value="{{ Input::old('telephone_number') }}">
                                <!-- START error message -->
                                {!! $errors->first('telephone_number','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
                                <!-- END error message -->
                            </div>
                        </div>                     
                        <div class="form-group">
                            <label class="col-md-3 control-label">TIN Number</label>
                            <div class="col-md-5">
                                <input type="text" class="form-control" name="tin_number" placeholder="TIN Number" value="{{ Input::old('tin_number') }}">
                                <!-- START error message -->
                                {!! $errors->first('tin_number','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
                                <!-- END error message -->
                            </div>
                        </div> 

                        <div class="form-group">
                            <label class="control-label col-md-3">Company Logo</label>
                            <div class="col-md-8">
                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                    <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="min-width: 150px; height: 150px;"> 
                                        <img src="{{ has_photo() }}">
                                    </div>
                                    <div>
                                        <span class="btn blue btn-outline btn-file btn-xs">
                                        <span class="fileinput-new"> Select image </span>
                                        <span class="fileinput-exists"> Change image </span>
                                        <input type="file" name="file" accept="image/*"> </span>
                                        <a href="javascript:;" class="btn red fileinput-exists btn-xs" data-dismiss="fileinput"> Remove </a>
                                    </div>
                                </div>
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
    <button type="submit" class="btn btn-primary btn-save" data-target=".form-submit"><i class="fa fa-check"></i> Save {{ $single }}</button>        
</div>
@endsection


@section('top_style')
@stop

@section('bottom_style')
@stop

@section('bottom_plugin_script') 
@stop

@section('bottom_script')
@stop
