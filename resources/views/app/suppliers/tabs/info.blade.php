<div class="form-group">
    <label class="col-md-3 control-label">Name <span class="required">*</span></label>
    <div class="col-md-5">
        <input type="text" class="form-control rtip" name="name" placeholder="Name" value="{{ Input::old('name', $info->post_title) }}">
        <!-- START error message -->
        {!! $errors->first('name','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
        <!-- END error message -->
    </div>
</div>
<div class="form-group">
    <label class="col-md-3 control-label">Address</label>
    <div class="col-md-5">
        <textarea name="address" class="form-control" rows="3" placeholder="Address">{{ Input::old('address', $info->address) }}</textarea>
        <!-- START error message -->
        {!! $errors->first('address','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
        <!-- END error message -->
    </div>
</div>

<h3 class="uppercase">Contacts</h3>

<div class="form-group">
    <label class="col-md-3 control-label">Email Address</label>
    <div class="col-md-5">
        <input type="text" class="form-control" name="email_address" placeholder="Email Address" value="{{ Input::old('email_address', $info->email_address) }}">
        <!-- START error message -->
        {!! $errors->first('email_address','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
        <!-- END error message -->
    </div>
</div>
<div class="form-group">
    <label class="col-md-3 control-label">Mobile Number</label>
    <div class="col-md-5">
        <input type="text" class="form-control" name="mobile_number" placeholder="Mobile Number" value="{{ Input::old('mobile_number', $info->mobile_number) }}">
        <!-- START error message -->
        {!! $errors->first('mobile_number','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
        <!-- END error message -->
    </div>
</div>
<div class="form-group">
    <label class="col-md-3 control-label">Telephone Number</label>
    <div class="col-md-5">
        <input type="text" class="form-control" name="telephone_number" placeholder="Telephone Number" value="{{ Input::old('telephone_number', $info->telephone_number) }}">
        <!-- START error message -->
        {!! $errors->first('telephone_number','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
        <!-- END error message -->
    </div>
</div>        
<div class="form-group">
    <label class="col-md-3 control-label">Fax Number</label>
    <div class="col-md-5">
        <input type="text" class="form-control" name="fax_number" placeholder="Fax Number" value="{{ Input::old('fax_number', $info->fax_number) }}">
        <!-- START error message -->
        {!! $errors->first('fax_number','<span class="help-block text-danger">:message</span>') !!}
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