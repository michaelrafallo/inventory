<a href="{{ URL::route($view.'.stocks', ['preview' => 1, query_vars()]) }}" class="btn-preview btn btn-xs"><i class="fa fa-print"></i> Generate Inventory Register</a>

<small class="pull-right uppercase text-muted"><b>{{ number_format($count) }}</b> Record{{ is_plural($count) }} Found</small>

<div class="form-body margin-top-10">
    <div class="row">
        <form method="get" class="form-horizontal">

            <div class="col-md-12">
                <div class="well">
                    <div class="row">
    
                        <div class="col-md-4">
                            <div class="input-group input-group-sm input-large daterange-picker input-daterange">
                                <input type="text" class="form-control period" name="from" value="{{ Input::get('from', date('01-01-Y')) }}">
                                <span class="input-group-addon"> to </span>
                                <input type="text" class="form-control period" name="to" value="{{ Input::get('to', date('m-d-Y')) }}"> 
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="input-group input-group-sm">
                                <input class="form-control" type="text" name="reference_no" placeholder="Search Reference Number" value="{{ Input::get('reference_no') }}">
                                <span class="input-group-btn">
                                    <button type="submit" class="btn blue btn-sm"><i class="fa fa-search"></i> Search</button>
                                </span>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <a href="{{ URL::route($view.'.stocks') }}" class="btn btn-default btn-sm">Reset</a>
                            <a class="btn btn-outline btn-default btn-sm" data-toggle="modal" href="#basic"> Advanced Search </a>
                        </div>                        
                    </div>
                </div>
            </div>

            <!-- START ADVANCED SEARCH -->
            <div class="modal fade" id="basic" role="basic" aria-hidden="true" style="display: none;">
                <div class="modal-dialog modal-md">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                            <h4 class="modal-title">Advanced Search</h4>
                        </div>
                        <div class="modal-body">

                            <div class="form-body">

                                <div class="form-group">  
                                    <div class="col-md-6">         
                                    <h5>Transaction</h5>                           
                                        {{ Form::select('post_status', ['' => 'All Transactions', 'in' => '( IN ) Purchased Orders', 'out' => '( OUT ) Sold Orders'],  Input::get('post_status'), ['class' => 'select2 form-control']) }}
                                    </div>

                                    <div class="col-md-6">
                                        <h5>Per Page</h5>
                                        {{ Form::select('rows', per_page(),  Input::get('rows'), ['class' => 'select2 form-control']) }}
                                    </div>
                                </div>    


                                <div class="form-group">  
                                    <div class="col-md-6">         
                                        <h5>Item</h5>                           
                                        {{ Form::select('item', ['' => 'All Item'] + $post->select_posts(['post_type' => 'product']),  Input::get('item'), ['class' => 'select2 form-control']) }}
                                    </div>

                                    <div class="col-md-6">
                                        <h5>Received / Delivered</h5>                           
                                        {{ Form::select('person_id', ['' => 'All Accounts'] + $post->select_posts(['post_type' => 'supplier']) + $post->select_posts(['post_type' => 'customer']),  Input::get('person_id'), ['class' => 'select2 form-control']) }}
                                    </div>
                                </div>    

                                <div class="form-group">  
                                    <div class="col-md-12">         
                                    <h5>Company</h5>                           
                                        {{ Form::select('company', ['' => 'Choose Header'] + $post->select_posts(['post_type' => 'company']),  Input::get('company', $company), ['class' => 'select2 form-control']) }}
                                        <span class="help-inline">Use for report header</span>
                                    </div>
                                </div>    

                            </div>


                            <div class="modal-footer">
                                <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn green">Search</button>
                            </div>


                        </div>
  
                    </div>
                </div>
            </div>
            <!-- END ADVANCED SEARCH -->

            @if( Input::get('post_status') == 'trash' )
            <input type="hidden" name="type" value="trash">
            @endif

        </form>
    </div>
</div>