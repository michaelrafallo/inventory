<div class="form-group">
    <label class="col-md-3 control-label">Alert Stock Level</label>
    <div class="col-md-3">
        <input type="number" class="form-control" name="stock_level" value="{{ @$info->stock_level }}" min="0">
    </div>
</div>

<div class="form-group">
    <label class="col-md-3 control-label">Unit of Measurement</label>
    <div class="col-md-3">
        {{ Form::select('uom', metrics(), @$info->uom, ['class' => 'form-control select2']) }}
    </div>
</div>

<div class="form-group">
    <label class="col-md-3 control-label">Currency</label>
    <div class="col-md-6">
        {{ Form::select('currency', currencies(), @$info->currency, ['class' => 'form-control select2']) }}
    </div>
</div>

<div class="form-group">
    <label class="col-md-3 control-label">Deliver Address</label>
    <div class="col-md-6">
        <textarea class="form-control" name="delivery_address" rows="4" placeholder="Delivery Address">{{ @$info->delivery_address }}</textarea>
    </div>
</div>
