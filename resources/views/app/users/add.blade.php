@extends('layouts.app')

@section('content')
<!-- BEGIN PAGE BAR -->
<div class="page-bar margin-bottom-20">
    <ul class="page-breadcrumb uppercase">
        <li>
           <h1 class="page-title">Add User</h1>
        </li>
    </ul>
    <div class="page-toolbar">
        <div class="btn-group pull-right">
            <a href="{{ URL::route('app.users.index') }}" class="btn btn-default margin-top-20"> 
                <i class="fa fa-angle-left"></i> All Users
            </a>
        </div>
    </div>
</div>
<!-- END PAGE BAR -->



<div class="row">

    <div class="col-md-8 col-sm-8 col-centered">

    @include('notification')

        <div class="portlet light bordered">

            <div class="portlet-body form">
                <!-- BEGIN FORM-->
                <form action="" class="form-horizontal form-submit" method="post" enctype="multipart/form-data">

                    {{ csrf_field() }}  

                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">Profile Picture</label>
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
                        <div class="form-group">
                            <label class="col-md-3 control-label">First Name <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="text" class="form-control rtip" name="firstname" placeholder="First Name" value="{{ Input::old('firstname') }}">
                                <!-- START error message -->
                                {!! $errors->first('firstname','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
                                <!-- END error message -->
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Last Name <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="text" class="form-control rtip" name="lastname" placeholder="Last Name" value="{{ Input::old('lastname') }}"">
                                <!-- START error message -->
                                {!! $errors->first('lastname','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
                                <!-- END error message -->
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Group <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                {{ Form::select('group', ['' => 'Select Group'] + $post->select_posts(['post_type' => 'group']), Input::old('group'), ['class' => 'form-control rtip select2'] )  }}

                                <!-- START error message -->
                                {!! $errors->first('group','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
                                <!-- END error message -->
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Email <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <div class="input-icon">
                                    <i class="fa fa-envelope"></i>
                                    <input type="email" class="form-control rtip" name="email" placeholder="Email"  value="{{ Input::old('email') }}""> 
                                </div>
                                <!-- START error message -->
                                {!! $errors->first('email','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
                                <!-- END error message -->
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Password <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <div class="input-icon">
                                    <i class="fa fa-envelope"></i>
                                    <input type="password" class="form-control rtip" name="password" placeholder="Password"  value="{{ Input::old('password') }}""> 
                                </div>
                                <!-- START error message -->
                                {!! $errors->first('password','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
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
    <button type="submit" class="btn btn-primary btn-save" data-target=".form-submit"><i class="fa fa-check"></i> Save User</button>        
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
