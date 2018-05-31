@yield('bottom_style')

<!--[if lt IE 9]>
<script src="{{ asset('assets/global/plugins/respond.min.js') }}""></script>
<script src="{{ asset('assets/global/plugins/excanvas.min.js') }}""></script> 
<script src="{{ asset('assets/global/plugins/ie8.fix.min.js') }}""></script> 
<![endif]-->


<!-- BEGIN CORE PLUGINS -->
<script src="{{ asset('assets/global/plugins/jquery.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/bootstrap/js/bootstrap.min.js') }}"" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/js.cookie.min.js') }}"" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js') }}"" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/jquery.blockui.min.js') }}"" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}"" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/jquery-ui/jquery-ui.min.js') }}" type="text/javascript"></script>

<!-- END CORE PLUGINS -->

<!-- BEGIN THEME GLOBAL SCRIPTS -->
<script src="{{ asset('assets/global/plugins/moment.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/scripts/app.min.js') }}"" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/jquery.blockui.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/jquery-repeater/jquery.repeater.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}" type="text/javascript"></script>

<script src="{{ asset('assets/global/scripts/datatable.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/datatables/datatables.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js') }}" type="text/javascript"></script>


<script src="{{ asset('assets/global/plugins/bootstrap-sweetalert/sweetalert.min.js') }}" type="text/javascript"></script>

<script src="{{ asset('assets/global/plugins/bootstrap-select/js/bootstrap-select.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/select2/js/select2.full.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/jquery-inputmask/jquery.inputmask.bundle.min.js') }}" type="text/javascript"></script>

<script src="{{ asset('assets/pages/scripts/ui-sweetalert.js') }}"></script>


<link rel="stylesheet" type="text/css" href="{{ asset('plugins/timepicker/mmnt.css') }}"/ >
<script src="{{ asset('plugins/timepicker/mmnt.js') }}"></script>

<!-- END THEME GLOBAL SCRIPTS -->

<!-- BEGIN THEME LAYOUT SCRIPTS -->
<script src="{{ asset('assets/layouts/layout/scripts/layout.min.js') }}" type="text/javascript"></script>

<!-- END THEME LAYOUT SCRIPTS -->

<script src="{{ asset('js/script.js') }}" type="text/javascript"></script>




    

@yield('bottom_plugin_script')

@yield('bottom_script')

@yield('filter_script')

<script>
function blockUI(msg) {
    $.blockUI({
        message: '<img src="{{ asset('img/loading-spinner-grey.gif') }}" /> '+msg,
        boxed: true,
        css: { padding: '20px'}
    });

    /* 
       window.setTimeout(function() {
            $.unblockUI();
        }, 1000);    
    */
}
</script>

@include('partials.popup-modal')