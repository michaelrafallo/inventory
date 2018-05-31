@extends('layouts.app')

@section('content')


<!-- BEGIN PAGE BAR -->
<div class="page-bar margin-bottom-20">
    <ul class="page-breadcrumb uppercase">
        <li>
           <h1 class="page-title">Users</h1>
        </li>
    </ul>
    <div class="page-toolbar">
        <div class="btn-group pull-right">

            @if( App\Permission::has_access('users', ['add_edit']) )
            <a href="{{ URL::route('app.users.add') }}" class="btn blue margin-top-20"> 
                <i class="fa fa-plus"></i> Add User
            </a>
            @endif

        </div>
    </div>
</div>
<!-- END PAGE BAR -->

@include('notification')

<div class="portlet light bordered">
    @include('app.users.search')

    <div class="table-responsive">
    <table class="table table-hover table-striped">
        <thead>
            <tr>
                <th width="90">Details</th>
                <th></th>
                <th>Group</th>
                <th class="text-center">Status</th>
                <th class="text-center">Last Login</th>
                <th class="text-center">Last Registered</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rows as $row)
            <?php $usermeta = get_meta( $row->userMetas()->get() ); ?>
            <tr>
                <td height="90">
                    <a href="{{ has_photo(@$usermeta->profile_picture) }}" class="btn-img-preview" data-title="{{ $row->fullname }}">
                        <img src="{{ has_photo(@$usermeta->profile_picture) }}" class="img-responsive img-thumb"> 
                    </a>
                </td>
                <td>
                    <h5 class="no-margin sbold">{{ $row->firstname.' '.$row->lastname }}</h5>
                    
                    <small class="text-muted">ID: {{ $row->id }}</small>
                    <small>{{ $row->email }}</small>

                    <div class="btn-actions">
                                                
                    @if( Input::get('type') == 'trash')

                        @if( App\Permission::has_access('users', ['trash_restore']) )
                        <a href="#" class="popup btn btn-xs uppercase margin-top-10"
                            data-href="{{ URL::route('app.users.restore', [$row->id, query_vars()]) }}" 
                            data-toggle="modal"
                            data-target=".popup-modal" 
                            data-title="Confirm Restore"
                            data-body="Are you sure you want to restore ID: <b>#{{ $row->id }}</b>?">Restore</a> 
                            @endif

                        @if( App\Permission::has_access('users', ['destroy']) )
                        <a href="#" class="popup btn btn-xs uppercase margin-top-10"
                            data-href="{{ URL::route('app.users.destroy', [$row->id, query_vars()]) }}" 
                            data-toggle="modal"
                            data-target=".popup-modal" 
                            data-title="Confirm Delete Permanently"
                            data-body="Are you sure you want to delete permanently ID: <b>#{{ $row->id }}</b>?">Delete Permanently</a>
                        @endif

                    @else
                        
                        @if( App\Permission::has_access('users', ['login'])  && $row->id != 1)
                        <a href="{{ URL::route('app.users.login', $row->id) }}" class="btn btn-xs uppercase margin-top-10"><i class="fa fa-sign-in"></i></a>
                        @endif

                        @if( App\Permission::has_access('users', ['add_edit']) )
                        <a href="{{ URL::route('app.users.edit', $row->id) }}" class="btn btn-xs uppercase margin-top-10">Edit</a>
                        @endif

                        @if( App\Permission::has_access('users', ['trash_restore'])  && $row->id != 1)
                            @if($row->status != 'actived')
                            <a href="#" class="popup btn btn-xs uppercase margin-top-10"
                                data-href="{{ URL::route('app.users.delete', [$row->id, query_vars()]) }}" 
                                data-toggle="modal"
                                data-target=".popup-modal" 
                                data-title="Confirm Delete"
                                data-body="Are you sure you want to move to trash ID: <b>#{{ $row->id }}</b>?">Move to trash</a> 
                            @else
                            <button class="btn btn-xs btn-default uppercase margin-top-10" disabled>Move to trash</button>
                            @endif    
                        @endif
                    @endif  

                    </div>
                </td>
                <td>{{ $row->group_name }}</td>
                <td class="text-center">{{ status_ico($row->status) }}</td>
                <td class="text-center">
                    @if( @$usermeta->last_login )
                    {{ date_formatted($usermeta->last_login) }}<br>
                    <small>{{ time_ago($usermeta->last_login) }}</small>
                    @endif
                </td>
                <td class="text-center">
                    {{ date_formatted($row->created_at) }}<br>
                    <small>{{ time_ago($row->created_at) }}</small>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    @if( ! $count )
    <p class="well"><b>No record found!</b> try boardening your search criteria.</p>
    @endif

    </div>

    {{ $rows->links() }}

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
