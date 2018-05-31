
<div class="form-group">
    <label class="col-md-3 control-label">Site Title <span class="required">*</span></label>
    <div class="col-md-8">
        <input type="text" class="form-control rtip" name="site_title" placeholder="Site Title" value="{{ @$info->site_title }}">
    </div>
</div>

<div class="form-group">
    <label class="col-md-3 control-label">Admin Email <span class="required">*</span></label>
    <div class="col-md-8">
        <input type="text" class="form-control rtip" name="admin_email" placeholder="Admin Email" value="{{ @$info->admin_email }}">
    </div>
</div>
<div class="form-group">
    <label class="col-md-3 control-label">Copy Right <span class="required">*</span></label>
    <div class="col-md-8">
        <input type="text" class="form-control rtip" name="copy_right" placeholder="Copy Right" value="{{ @$info->copy_right }}"> 
    </div>
</div>



<div class="form-group margin-top-30">
    <label class="col-md-3 control-label">Logo <span class="required">*</span></label>
    <div class="col-md-4">

        <div class="fileinput fileinput-new" data-provides="fileinput">
            <div class="fileinput-preview thumbnail setting-logo" data-trigger="fileinput" style="min-width: 150px; min-height: 150px;"> 
                <img src="{{ asset(@$info->logo) }}">
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

<h3 class="uppercase">Image Settings</h3>

<div class="form-group">
    <label class="col-md-3 control-label">Image Compression</label>
    <div class="col-md-8">
        <label class="mt-radio">
            <input type="radio" name="img_compress" value="1" {{ checked(@$info->img_compress, '1') }}> Enabled
            <span></span>
        </label>
        <label class="mt-radio">
            <input type="radio" name="img_compress" value="0" {{ checked(@$info->img_compress, '0') }}> Disabled 
            <span></span>
        </label>
    </div>
</div>

<div class="form-group">
    <label class="col-md-3 control-label">Compression Rate <span class="required">*</span></label>
    <div class="col-md-4">
        <input type="number" class="form-control rtip" name="img_compress_rate" placeholder="" value="{{ @$info->img_compress_rate }}"> 
        <span class="help-inline">retain image quality from 1 - 100 (%)</span>
    </div>
</div>

<div class="form-group">
    <label class="col-md-3 control-label">Width <span class="required">*</span></label>
    <div class="col-md-4">
        <input type="number" class="form-control rtip" name="img_width" placeholder="" value="{{ @$info->img_width }}"> 
    </div>
</div>

