<input type="hidden" name="orders" value="">
<input type="hidden" name="payments" value="">


<div class="row">
    <div class="col-md-12 col-centered">
        @include('notification')
        <!-- BEGIN FORM-->
        <form action="" class="form-horizontal form-submit" method="post">
            <div class="portlet light bordered">
                <div class="portlet-body">
                    {{ csrf_field() }}
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h3 class="no-margin uppercase">{{ $post->find($info->supplier)->post_title }}</h3>
                                <h5>{{ $info->address }}</h5>
                            </div>
                            <div class="col-md-6 text-right">
                                <h5 class="uppercase">Reference No. <b>{{ $info->reference_no }}</b></h5>   
                                {{ status_ico($info->fulfilled) }} {{ status_ico($info->post_status) }}

                                
                            </div>
                        </div>   

                        <hr class="margin-top-10">

                        <div class="row margin-top-20">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-md-4 control-label">Supplier <span class="required">*</span></label>
                                    <div class="col-md-7">
                                        {{ Form::select('supplier', $post->select_posts(['post_type' => 'supplier']), Input::old('supplier', $info->supplier), ['class' => 'form-control select2']) }}
                                        <!-- START error message -->
                                        <span id="supplier"></span>
                                        <!-- END error message -->
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label">Address <span class="required">*</span></label>
                                    <div class="col-md-7">
                                        <textarea name="address" class="form-control rtip" rows="3" placeholder="Address">{{ Input::old('address', $info->address) }}</textarea>
                                        <!-- START error message -->
                                        <span id="address"></span>
                                        <!-- END error message -->
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label">Terms</label>
                                    <div class="col-md-7">
                                        {{ Form::select('terms', ['' => 'None'] + $post->select_posts(['post_type' => 'terms']), Input::old('terms', $info->terms), ['class' => 'form-control select2']) }}
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-4 control-label">Purchase Order No.</label>
                                    <div class="col-md-7">
                                        <input type="text" name="po_no" class="form-control" value="{{ Input::old('po_no', $info->po_no) }}">
                                        <span class="help-inline">Overwrite Reference No.</span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-4 control-label"></label>
                                    <div class="col-md-7">
                                        <a href="#popupModal" data-toggle="modal" class="btn btn-block"><i class="fa fa-money"></i> Payment Details</a>                                    
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 border-left">
                                <div class="form-group">
                                    <label class="col-md-4 control-label">Date <span class="required">*</span></label>
                                    <div class="col-md-7">
                                        <input type="text" name="date" class="form-control datepicker rtip" value="{{ Input::old('date', date_formatted_b($info->date)) }}">
                                        <!-- START error message -->
                                        <span id="date"></span>
                                        <!-- END error message -->
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label">Delivery Address</label>
                                    <div class="col-md-7">
                                        <textarea name="delivery_address" class="form-control" rows="3" placeholder="Delivery Address">{{ Input::old('delivery_address', $info->delivery_address) }}</textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label">Remarks</label>
                                    <div class="col-md-7">
                                        <textarea name="remarks" class="form-control" rows="3" placeholder="Remarks">{{ Input::old('remarks', $info->remarks) }}</textarea>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-4 control-label">Status</label>
                                    <div class="col-md-7">
                                        <div class="mt-checkbox-inline">
                                            <label class="mt-checkbox">
                                            <input type="checkbox" name="fulfilled" value="fulfilled" {{ checked($info->fulfilled, 'fulfilled') }}> Fullfilled
                                            <span></span>
                                            </label>
                                            <label class="mt-checkbox">
                                            @if($info->balance == 0)
                                            <input type="checkbox" name="pay_status" value="unpay">
                                            Unpay
                                            @elseif($info->balance != $info->total)      
                                            <input type="checkbox" name="pay_status" value="pay_remaining">
                                            Pay Remaining                                      
                                            @else
                                            <input type="checkbox" name="pay_status" value="pay">
                                            Pay                                                
                                            @endif
                                            <span></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="portlet light bordered">
                <div class="portlet-body">
                    <div class="form-body">

                    <h4 class="uppercase"><b class="text-primary">Purchase</b> Orders</h4>
    
                    @if( $info->fulfilled == 'fulfilled' )
                    @include($view.'.temp.fulfilled-orders')
                    @endif

                    <div class="{{ $info->fulfilled == 'fulfilled' ? 'hide' : '' }}">
                    @include($view.'.temp.unfulfilled-orders')
                    </div>


                    </div>
                    <input type="hidden" name="total" value="{{ $info->total }}">
                    <input type="hidden" name="total_paid" value="{{ @$info->total_paid }}">
                    <input type="hidden" name="balance" value="{{ @$info->balance }}">
                    <button type="submit" class="hide"></button>
                </div>
            </div>



            <div class="modal fade form-modal" id="popupModal"  tabindex="1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" aria-hidden="false">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                            <h4 class="modal-title">Payment Details</h4>
                        </div>
                        <div class="modal-body">
                            <div class="mt-repeater">
                                <div data-repeater-list="payments">
                                    @if( json_decode($info->payments) )
                                    @foreach( json_decode($info->payments) as $payment)
                                    <div data-repeater-item="" class="mt-repeater-item">
                                        <div class="mt-repeater-row">
                                            <table class="table table-condensed table-hover">
                                                <tr>
                                                    <td width="14%">
                                                        <h5 class="col">Date</h5>
                                                        <input type="text" class="form-control input-sm datepicker" name="date" value="{{ @$payment->date }}" data-original-title="Date">
                                                    </td>
                                                    <td width="25%">
                                                        <h5 class="col">Method</h5>
                                                        <div class="input-group-sm">
                                                            {{ Form::select('method', ['' => 'Select Payment Method'] + payment_method(), @$payment->method, ['class' => 'form-control select2'] ) }}          
                                                            
                                                        </div>
                                                    </td>
                                                    <td width="15%">
                                                        <h5 class="col">Reference Number</h5>
                                                        <input type="text" class="form-control input-sm text" name="reference_no" placeholder="Reference No." value="{{ @$payment->reference_no }}" data-original-title="Quantity">
                                                    </td>
                                                    <td width="25%">
                                                        <h5 class="col">Remarks</h5>
                                                        <input type="text" class="form-control input-sm" name="remarks" placeholder="Remarks" value="{{ @$payment->remarks }}" data-original-title="Remarks">
                                                    </td>
                                                    <td width="10%">
                                                        <h5 class="col text-right">Amount</h5>
                                                        <input type="text" class="form-control input-sm text-right s-a" name="amount" placeholder="0.00" value="{{ @$payment->amount }}" data-original-title="Amount">
                                                    </td>
                                                    <td>
                                                        <button type="button" data-repeater-delete="" class="btn btn-xs btn-outline red mt-repeater-delete pull-right">
                                                        <i class="fa fa-close"></i> Remove
                                                        </button>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                    @endforeach
                                    @else
                                    <div data-repeater-item="" class="mt-repeater-item">
                                        <div class="mt-repeater-row">
                                            <table class="table table-condensed table-hover">
                                                <tr>
                                                    <td width="14%">
                                                        <h5 class="col">Date</h5>
                                                        <input type="text" class="form-control input-sm datepicker" name="date" value="" data-original-title="Date">
                                                    </td>
                                                    <td width="25%">
                                                        <h5 class="col">Method</h5>
                                                        <div class="input-group-sm">
                                                            {{ Form::select('method', ['' => 'Select Payment Method'] + payment_method(), '', ['class' => 'form-control select2'] ) }}   
                                                        </div>       
                                                    </td>
                                                    <td width="15%">
                                                        <h5 class="col">Reference Number</h5>
                                                        <input type="text" class="form-control input-sm" name="reference_no" placeholder="Reference No." value="" data-original-title="Quantity">
                                                    </td>
                                                    <td width="25%">
                                                        <h5 class="col">Remarks</h5>
                                                        <input type="text" class="form-control input-sm" name="remarks" placeholder="Remarks" value="" data-original-title="Remarks">
                                                    </td>
                                                    <td width="10%">
                                                        <h5 class="col text-right">Amount</h5>
                                                        <input type="text" class="form-control input-sm text-right s-a" name="amount" placeholder="0.00" value="" data-original-title="Amount">
                                                    </td>
                                                    <td>
                                                        <button type="button" data-repeater-delete="" class="btn btn-xs btn-outline red mt-repeater-delete pull-right">
                                                        <i class="fa fa-close"></i> Remove
                                                        </button>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                <a data-repeater-create="" class="btn mt-repeater-add btn-outline blue margin-top-20">
                                <i class="fa fa-plus"></i> Add Item</a>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary confirm" data-target=".form-submit"><i class="fa fa-check"></i> Save Payment</button>   
                            <button class="btn btn-default uppercase" aria-hidden="true" data-dismiss="modal" class="close" type="button">Close</button> 
                            <span class="msg-error"></span>           
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="form-actions-fixed">
    <div class="col-md-3 col-xs-6">
        <button type="submit" class="btn btn-primary confirm btn-xs" data-target=".form-submit"><i class="fa fa-check"></i> Save Order</button>     
        <a href="{{ URL::route($view.'.edit', [$info->id, 'preview' => 1]) }}" class="btn-preview btn btn-outline green btn-xs uppercase"><i class="fa fa-print"></i> Generate PO</a>

    </div>
    <div class="col-md-2">
        <h4 class="no-margin"><small>TOTAL : <b class="total">{{ number_format(@$info->total, 2) }}</b></small></h4>
    </div>
    <div class="col-md-2">
        <h4 class="no-margin"><small class="text-primary"><a href="#popupModal" data-toggle="modal">PAID : <b class="total-paid">{{ number_format(@$info->total_paid, 2) }}</b></small></a></h4>
    </div>
    <div class="col-md-2">
        <h4 class="no-margin"><small class="text-danger">BALANCE : <b class="balance">{{ number_format(@$info->balance, 2) }}</b></small></h4>
    </div>
</div>