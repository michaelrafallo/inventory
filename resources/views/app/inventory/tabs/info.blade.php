
<div class="form-group">
    <label class="col-md-3 control-label">Name <span class="required">*</span></label>
    <div class="col-md-8">
        <input type="text" class="form-control rtip" name="name" placeholder="Name" value="{{ Input::old('name', $info->post_title) }}">
        <span id="name"></span>
    </div>
</div>


<div class="form-group">
    <label class="col-md-3 control-label">Category</label>
    <div class="col-md-8">
        {{ Form::select('category', ['uncategorize' => 'Uncategorize'] + $post->select_posts(['post_type' => 'product-category']), Input::old('category', $info->category), ['class' => 'form-control select2']) }}
    </div>
</div>



<div class="form-group">
    <label class="col-md-3 control-label"></label>
    <div class="col-md-4">
        <h5>Normal Price <span class="required">*</span></h5>
        <input type="number" class="form-control rtip text-right" name="normal_price" placeholder="0.00" value="{{ Input::old('normal_price', $info->normal_price) }}" min="0" step="any">
        <!-- START error message -->
        {!! $errors->first('normal_price','<span class="help-block text-danger">:message</span>') !!}
        <!-- END error message -->
    </div>
    <div class="col-md-4">
        <h5>Sales Price <span class="required">*</span></h5>
        <input type="number" class="form-control rtip text-right" name="sales_price" placeholder="0.00" value="{{ Input::old('sales_price', $info->sales_price) }}"  min="0" step="any">
        <!-- START error message -->
        {!! $errors->first('sales_price','<span class="help-block text-danger">:message</span>') !!}
        <!-- END error message -->
    </div>
</div>

<div class="form-group">
    <label class="col-md-3 control-label"></label>
    <div class="col-md-4">
        <h5>Quantity <span class="required">*</span></h5>
        <input type="number" class="form-control rtip text-right" name="quantity" placeholder="0" value="{{ Input::old('quantity', $info->quantity) }}"  min="0" step="any">
        <!-- START error message -->
        {!! $errors->first('quantity','<span class="help-block text-danger">:message</span>') !!}
        <!-- END error message -->
    </div>
    <div class="form-group">
        <div class="col-md-4">
            <h5>Unit of Measure <span class="required">*</span></h5>
            {{ Form::select('unit_of_measure', ['' => 'Select Measurement'] + metrics(), Input::old('unit_of_measure', $info->unit_of_measure), ['class' => 'form-control select2']) }}
            <!-- START error message -->
            {!! $errors->first('unit_of_measure','<span class="help-block text-danger">:message</span>') !!}
            <!-- END error message -->
        </div>
    </div>
</div>


<div class="form-group">
    <label class="col-md-3 control-label">Description</label>
    <div class="col-md-8">
        <textarea class="form-control" name="description" placeholder="Description" rows="4">{{ Input::old('description', $info->description) }}</textarea>
    </div>
</div>
