@extends('layouts.app')

@section('content')
<!-- BEGIN PAGE BAR -->
<div class="page-bar margin-bottom-20">
    <ul class="page-breadcrumb uppercase">
        <li>
           <h1 class="page-title">Edit User
           <a href="{{ URL::route('app.users.add') }}" class="btn-xs"><i class="fa fa-plus"></i> Add New</a>
           </h1>
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
                <form action="" class="form-horizontal form-save" method="post" enctype="multipart/form-data">

                    {{ csrf_field() }}

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

                        @if( $info->id != 1 )
                        <div class="form-group">
                            <label class="col-md-3 control-label">Group <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                {{ Form::select('group', ['' => 'Select Group'] + $post->select_posts(['post_type' => 'group']), $info->group, ['class' => 'form-control rtip select2'] )  }}
                                <span id="group" class="msg-error"></span>
                                <!-- START error message -->
                                {!! $errors->first('group','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
                                <!-- END error message -->
                            </div>
                        </div>
                        @endif

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
                        <div class="form-group">
                            <label class="col-md-3 control-label">Password</label>
                            <div class="col-md-8">
                                <div class="input-icon">
                                    <i class="fa fa-envelope"></i>
                                    <input type="password" class="form-control" name="password" placeholder="Password"  value=""> 
                                </div>
                                <span class="help-block small">Leave blank if you don't want to change it</span>
                                
                                <!-- START error message -->
                                {!! $errors->first('password','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
                                <!-- END error message -->
                            </div>
                        </div>

                        @if( $info->id != 1 )
                        <div class="form-group">
                            <div class="col-md-offset-3 col-md-8">
                            <label class="mt-checkbox">
                                <input type="checkbox" name="status" value="actived" {{ checked($info->status, 'actived') }}> Active
                                <span></span>
                            </label>
                            </div>
                        </div>
                        @endif
                     
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
blockUImsg = 'Updating user details ...';
</script>
@stop
