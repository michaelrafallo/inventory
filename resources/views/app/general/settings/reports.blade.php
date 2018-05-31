<div class="form-group">
    <label class="col-md-3 control-label">Default Company</label>
    <div class="col-md-8">
        {{ Form::select('company', $post->select_posts(['post_type' => 'company']), @$info->company, ['class' => 'form-control select2']) }}
    </div>
</div>

<h4 class="margin-top-30">Document Number</h4>

<a href="http://php.net/manual/en/function.date.php" target="_blank">PHP date - Manual</a>	

<h5 class="margin-top-20">Purchase Order</h5>

<div class="form-group">
    <div class="col-md-2">
    	<small>PREFIX</small>
        <input type="text" name="po_no" value="{{ @$info->po_no }}" class="form-control">
    </div>
    <div class="col-md-2">
    	<small>DATE</small>
        <input type="text" name="so_no_date" value="{{ @$info->so_no_date }}" class="form-control">
    </div>
    <div class="col-md-2">
    	<small>NEXT NUMBER</small>
        <input type="text" name="po_no_next" value="{{ @$info->po_no_next }}" class="form-control" maxlength="6">
    </div>
    <div class="col-md-6">
    	<small>PREVIEW</small>
        <h5>{{ @$info->po_no.date($info->so_no_date).@$info->po_no_next }}</h5>
    </div>
</div>

<h5>Sales Order</h5>
<div class="form-group">
    <div class="col-md-2">
        <input type="text" name="so_no" value="{{ @$info->so_no }}" class="form-control">
    </div>
    <div class="col-md-2">
        <input type="text" name="po_no_date" value="{{ @$info->po_no_date }}" class="form-control">
    </div>
    <div class="col-md-2">
        <input type="text" name="so_no_next" value="{{ @$info->so_no_next }}" class="form-control" maxlength="6">
    </div>
    <div class="col-md-6">
        <h5>{{ @$info->so_no.date(@$info->po_no_date).@$info->so_no_next }}</h5>
    </div>
</div>


