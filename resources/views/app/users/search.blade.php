
@if(Input::get('type') == 'trash')
<a href="{{ URL::route('app.users.index', query_vars('type=0&s=0')) }}">All ({{ number_format($all) }})</a> | 
<b>Trashed ({{ number_format($trashed) }})</b>
@else
<b>All ({{ number_format($all) }})</b> | 
<a href="{{ URL::route('app.users.index', query_vars('type=trash&s=0')) }}">Trashed ({{ number_format($trashed) }})</a>
@endif

<small class="pull-right uppercase text-muted"><b>{{ number_format($count) }}</b> Record{{ is_plural($count) }} Found</small>

<div class="form-body margin-top-20">
    <div class="row">
        <form method="get" class="form-horizontal">

            <div class="col-md-12">
                <div class="well">
                    <div class="row">
                        <div class="col-md-6">
                        <div class="input-group input-group-sm">
                            <input class="form-control" type="text" name="s" placeholder="Search Name" value="{{ Input::get('s') }}">
                            <span class="input-group-btn">
                                <button type="submit" class="btn blue btn-sm"><i class="fa fa-search"></i> Search</button>
                            </span>
                        </div>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ URL::route('app.users.index') }}" class="btn btn-default btn-sm">Reset</a>
                            <a class="btn btn-outline btn-default btn-sm" data-toggle="modal" href="#basic"> Advanced Search </a>
                        </div>                        
                    </div>
                </div>
            </div>

            <!-- START ADVANCED SEARCH -->
            <div class="modal fade" id="basic" role="basic" style="display: none;">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                            <h4 class="modal-title">Advanced Search</h4>
                        </div>
                        <div class="modal-body">

                            <div class="form-body">
                            
                                <div class="form-group">                        
                                    <div class="col-md-6">         
                                    <h5>Status</h5>                           
                                        {{ Form::select('status', ['' => 'All Status'] + user_status(),  Input::get('status'), ['class' => 'select2 form-control']) }}
                                    </div>

                                    <div class="col-md-6">
                                        <h5>Group</h5>
                                        {{ Form::select('group', ['' => 'Select Group'] + $post->select_posts(['post_type' => 'group']), Input::get('group'), ['class' => 'form-control rtip select2'] )  }}                                    </div>
                                </div>                            

                                <div class="form-group">
                                    <div class="col-md-4">
                                        <h5>Sort By</h5>
                                        {{ Form::select('sort', user_sort_by(),  Input::get('sort'), ['class' => 'select2 form-control']) }}
                                    </div>
                                    <div class="col-md-4">
                                        <h5>Order By</h5>
                                        {{ Form::select('order', order_by(),  Input::get('order'), ['class' => 'select2 form-control']) }}
                                    </div>
                                    <h5>Per Page</h5>
                                    <div class="col-md-4">
                                        {{ Form::select('rows', per_page(),  Input::get('rows'), ['class' => 'select2 form-control']) }}
                                    </div>
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
            <!-- END ADVANCED SEARCH -->

            @if( Input::get('type') == 'trash' )
            <input type="hidden" name="type" value="trash">
            @endif

        </form>
    </div>
</div>