<?php $rows = $post->where('post_type', 'sales-order')->where('parent', $info->id)->orderBy('id', 'DESC')->get(); ?>


  <div class="mt-repeater">
        <div data-repeater-list="suppliers">
            @if(@$info->suppliers)
            @foreach( json_decode($info->suppliers) as $supplier)
            <div data-repeater-item="" class="mt-repeater-item">
                <div class="mt-repeater-row">

                   <table class="table table-condensed table-hover">
                        <tr>
                            <td width="35%">
                                <h5 class="col">Supplier <span class="text-danger">*</span></h5>
                                {{ Form::select('supplier', ['' => 'Select Supplier'] + $post->select_posts(['post_type' => 'supplier']), $supplier->supplier, ['class' => 'form-control select2']) }}
                            </td>
                            <td width="25%">
                                <h5 class="col">Product Code</h5>
                                <input type="text" class="form-control" name="code" placeholder="Product Code" value="{{ $supplier->code }}">                                                             
                            </td>
                            <td width="15%">
                                <h5 class="col">Supplier Price <span class="text-danger">*</span></h5>
                                <input type="number" class="form-control text-right rtip" name="supplier_price" placeholder="0.00" min="0" step="any" value="{{ $supplier->supplier_price }}">                                                       
                            </td>
                            <td width="12%">
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
                            <td width="35%">
                                <h5 class="col">Supplier <span class="text-danger">*</span></h5>
                                {{ Form::select('supplier', ['' => 'Select Supplier'] + $post->select_posts(['post_type' => 'supplier']), '', ['class' => 'form-control select2']) }}
                            </td>
                            <td width="25%">
                                <h5 class="col">Product Code</h5>
                                <input type="text" class="form-control" name="code" placeholder="Product Code">                                                             
                            </td>
                            <td width="15%">
                                <h5 class="col">Supplier Price <span class="text-danger">*</span></h5>
                                <input type="number" class="form-control text-right rtip" name="supplier_price" placeholder="0.00" min="0" step="any">                                                       
                            </td>
                            <td width="12%">
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
        <a data-repeater-create="" class="btn mt-repeater-add btn-block margin-top-20">
        <i class="fa fa-plus"></i> Add Supplier</a>
    </div>



