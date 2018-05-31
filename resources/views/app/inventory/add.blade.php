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
                            <div class="col-md-6">
                                <input type="text" class="form-control rtip" name="name" placeholder="Name" value="{{ Input::old('name') }}">
                                <!-- START error message -->
                                {!! $errors->first('name','<span class="help-block text-danger">:message</span>') !!}
                                <!-- END error message -->
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">Category</label>
                            <div class="col-md-6">
                                {{ Form::select('category', ['uncategorize' => 'Uncategorize'] + $post->select_posts(['post_type' => 'product-category']), Input::old('category'), ['class' => 'form-control select2']) }}
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label"></label>
                            <div class="col-md-3">
                                <h5>Normal Price <span class="required">*</span></h5>
                                <input type="number" class="form-control rtip text-right" name="normal_price" placeholder="0.00" value="{{ Input::old('normal_price') }}" min="0" step="any">
                                <!-- START error message -->
                                {!! $errors->first('normal_price','<span class="help-block text-danger">:message</span>') !!}
                                <!-- END error message -->
                            </div>
                            <div class="col-md-3">
                                <h5>Sales Price <span class="required">*</span></h5>
                                <input type="number" class="form-control rtip text-right" name="sales_price" placeholder="0.00" value="{{ Input::old('sales_price') }}"  min="0" step="any">
                                <!-- START error message -->
                                {!! $errors->first('sales_price','<span class="help-block text-danger">:message</span>') !!}
                                <!-- END error message -->
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="col-md-3 control-label"></label>
                            <div class="col-md-3">
                                <h5>Quantity <span class="required">*</span></h5>
                                <input type="number" class="form-control rtip text-right" name="quantity" placeholder="0" value="{{ Input::old('quantity') }}"  min="0" step="any">
                                <!-- START error message -->
                                {!! $errors->first('quantity','<span class="help-block text-danger">:message</span>') !!}
                                <!-- END error message -->
                            </div>
                            <div class="form-group">
                                <div class="col-md-3">
                                    <h5>Unit of Measure <span class="required">*</span></h5>
                                    {{ Form::select('unit_of_measure', ['' => 'Select Measurement'] + metrics(), Input::old('unit_of_measure', $setting->get_setting('uom')), ['class' => 'form-control select2']) }}
                                    <!-- START error message -->
                                    {!! $errors->first('unit_of_measure','<span class="help-block text-danger">:message</span>') !!}
                                    <!-- END error message -->
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">Description</label>
                            <div class="col-md-6">
                                <textarea class="form-control" name="description" placeholder="Description" rows="4">{{ Input::old('description') }}</textarea>
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
