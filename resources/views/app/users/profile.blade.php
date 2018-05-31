@extends('layouts.app')

@section('content')

@include('notification')



<div class="row">

    <div class="col-md-6">

        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption uppercase">
                    My Profile
                </div>
            </div>
            <div class="portlet-body form">
                <!-- BEGIN FORM-->
                <form action="" class="form-horizontal form-save" method="post" enctype="multipart/form-data">

                    {{ csrf_field() }}
                    <input type="hidden" name="op" value="1">   

                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">Profile Picture</label>
                            <div class="col-md-8">
                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                    <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="min-width: 150px; height: 150px;"> 
                                        <img src="{{ has_photo($info->profile_picture) }}">
                                    </div>
                                    <div>
                                        <span class="btn blue btn-outline btn-file btn-xs">
                                        <span class="fileinput-new"> Select image </span>
                                        <span class="fileinput-exists"> Change image </span>
                                        <input type="file" name="file" accept="image/*"> </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">First Name <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="text" class="form-control rtip" name="firstname" placeholder="First Name" value="{{ $info->firstname }}">
                                <span id="firstname" class="msg-error"></span>
                                <!-- START error message -->
                                {!! $errors->first('firstname','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
                                <!-- END error message -->
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Last Name <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="text" class="form-control rtip" name="lastname" placeholder="Last Name" value="{{ $info->lastname }}">
                                <span id="lastname" class="msg-error"></span>
                                <!-- START error message -->
                                {!! $errors->first('lastname','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
                                <!-- END error message -->
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Email <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <div class="input-icon">
                                    <i class="fa fa-envelope"></i>
                                    <input type="email" class="form-control rtip" name="email" placeholder="Email"  value="{{ $info->email }}"> 
                                </div>
                                <span id="email" class="msg-error"></span>
                                <!-- START error message -->
                                {!! $errors->first('email','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
                                <!-- END error message -->
                            </div>
                        </div>

                     
                    </div>
                    <div class="form-actions">
                        <div class="row">
                            <div class="col-md-offset-3 col-md-9">
                                <button type="submit" class="btn btn-primary btn-save"><i class="fa fa-check"></i> Update Profile</button>
                            </div>
                        </div>
                    </div>
                </form>
                <!-- END FORM-->
            </div>
        </div>

        
    </div>

    <div class="col-md-6 col-sm-4">
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption uppercase">
                    <span class="caption-subject">Change Password</span>
                </div>
            </div>
            <div class="portlet-body form">
                <form class="form-horizontal form-save" role="form" method="post">
                    <div class="form-body">
                        {{ csrf_field() }}
                        <input type="hidden" name="op" value="2">   
        
                        <div class="form-group">
                            <label class="col-md-4 control-label">New Password <span class="text-danger">*</span></label>
                            <div class="col-md-7">
                                <input type="password" class="form-control rtip" name="new_password" placeholder="New Password" value="{{ Input::old('new_password') }}">
                                <span id="new_password" class="msg-error"></span>
                                <!-- START error message -->
                                {!! $errors->first('new_password','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
                                <!-- END error message -->
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">Confirm New Password <span class="text-danger">*</span></label>
                            <div class="col-md-7">
                                <input type="password" class="form-control rtip" name="new_password_confirmation" placeholder="Confirm New Password" value="{{ Input::old('new_password_confirmation') }}">
                                <span id="new_password_confirmation" class="msg-error"></span>
                                <!-- START error message -->
                                {!! $errors->first('new_password_confirmation','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
                                <!-- END error message -->
                            </div>
                        </div>


                    </div>

                    <div class="form-actions">
                        <div class="row">
                            <div class="col-md-offset-4 col-md-8">
                                <button type="submit" class="btn btn-primary btn-save"><i class="fa fa-check"></i> Update Password</button>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>

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
<script>
blockUImsg = 'Updating profile details ...';
</script>
@stop
