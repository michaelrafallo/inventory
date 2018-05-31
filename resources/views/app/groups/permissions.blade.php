@extends('layouts.app')

@section('content')



<!-- BEGIN PAGE BAR -->
<div class="page-bar margin-bottom-20">
    <ul class="page-breadcrumb uppercase">
        <li>
           <h1 class="page-title">Set Permission</h1>
        </li>
    </ul>
    <div class="page-toolbar">
        <div class="btn-group pull-right">
            <a href="{{ URL::route('app.groups.index') }}" class="btn btn-default margin-top-20"> 
                <i class="fa fa-angle-left"></i> All Groups
            </a>
        </div>
    </div>
</div>
<!-- END PAGE BAR -->

    @include('notification')


<div class="portlet light bordered">

    <h3 class="margin-bottom-20">{{ $info->post_title }}</h3>

    <form class="form-s" method="post">
        {{ csrf_field() }}
            
        <?php 
        $mods = json_decode($info->post_content, true);
        ?>


        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                       <th width="1" colspan="2">
                        <label class="mt-checkbox mt-checkbox-outline">
                            <input type="checkbox" id="check_all">
                            <span></span>
                        </label>        
                        <label for="check_all" class="sbold">All Modules</label>
                        </th>
                        <th>Roles</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($modules as $module => $mod)
                    <tr>
                        <td width="1">
                            <?php $checked = @$mods[$module] ? 'checked' : ''; ?>
                            <!-- Front Parent -->
                            <label class="mt-checkbox mt-checkbox-outline">
                                <input id="{{ $module }}" type="checkbox" class="parent_checkbox checkboxes" value="{{ $module }}" {{ $checked }}/>
                                <span></span>
                            </label>        
                            <!-- Backend Parent -->  
                        </td>
                        <td width="200">
                            <label for="{{ $module }}">
                            {{ ucwords(str_replace('-', ' ', $module)) }}<br>
                            <small class="text-muted">{{ @$mod['note'] }}</small>
                            </label>
                        </td>
                        <td>
                            @foreach($mod as $roles => $role)
                            <?php
                                $checked = '';
                                if(@$mods[$module]) {
                                    $checked = in_array($roles, @$mods[$module]) ? 'checked' : ''; 
                                }
                            ?>
                            <label class="{{ $module.'-'.$roles }}  mt-checkbox mt-checkbox-outline">
                            <input name="{{ $module }}[]" class="checkboxes {{ $module }}" data-name="{{ $module }}" type="checkbox" value="{{  $roles }}" 
                            {{ $checked }}/> 
                                <span></span>
                      
                            {{ $role }}</label><br>
                            @endforeach
                        </td>
                        @endforeach
                    </tr>
                </tbody>
            </table>    
        </form>

    </div>

</div>

<div class="form-actions-fixed">
    <button type="submit" class="btn btn-primary btn-save" data-target=".form-s"><i class="fa fa-check"></i> Save Changes</button>        
</div>
@endsection


@section('top_style')
<style>
.mt-checkbox span {    
    border: 1px solid #888888!important;
}
</style>
@stop

@section('bottom_style')
@stop

@section('bottom_plugin_script') 
@stop

@section('bottom_script')
<script>
    //On Click Check All
    $(document).on('click change','input[id="check_all"]',function() {
        
        var checkboxes = $('.checkboxes');
    
        if ($(this).is(':checked')) {
            checkboxes.prop("checked" , true);
            checkboxes.closest('span').addClass('checked');
        } else {
            checkboxes.prop( "checked" , false );
            checkboxes.closest('span').removeClass('checked');
        }
    });
    
    //Document ready Check All
    $(document).ready(function(){
        
        //Hide all main checkboxes
        $('.main_modules').hide();
    
        var a = $(".checkboxes");
        if(a.length == a.filter(":checked").length){
            $('#check_all').prop("checked" , true);
            $('#check_all').closest('span').addClass('checked');
        }
    });
    
    //Parent checkboxes
    $('.parent_checkbox').click(function() {
        $class = $(this).attr('id');
        var checkboxes = $('.' + $class);
        if ($(this).is(':checked')) {
            checkboxes.prop("checked" , true);
            checkboxes.closest('span').addClass('checked'); 
        } else {
            checkboxes.prop( "checked" , false );
            checkboxes.closest('span').removeClass('checked');
        }
        if($('.parent_checkbox').filter(":checked").length == $('.parent_checkbox').length){
            $('#check_all').prop("checked" , true);
            $('#check_all').closest('span').addClass('checked');
        } else {
            $('#check_all').prop("checked" , false);
            $('#check_all').closest('span').removeClass('checked');
        }
    });
    
    
    //Children checkboxes
    $('.checkboxes').click(function() {
        var name = $(this).data('name');
        var $parent = $('input#' + name);
        var a = $('.' + name);        
        if(a.filter(":checked").length > 0){
            $parent.prop("checked" , true);
            $parent.closest('span').addClass('checked');
        } else {
            $parent.prop( "checked" , false );
            $parent.closest('span').removeClass('checked');
        }
    });
</script>
@stop
