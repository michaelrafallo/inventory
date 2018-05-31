<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
    <!--<![endif]-->
    
    <!-- BEGIN HEAD -->
    @include('partials.header')
    <!-- END HEAD -->

    <body class="page-sidebar-closed-hide-logo page-content-white page-md page-header-fixed page-container-bg-solid">
        <div class="page-wrapper">

            <!-- BEGIN HEADER -->
           @include('partials.header-nav')        
            <!-- END HEADER -->

            <!-- BEGIN CONTAINER -->
            <div class="page-container">

                <!-- BEGIN SIDEBAR -->
                @include('partials.sidebar')
                <!-- END SIDEBAR -->


                <!-- BEGIN CONTENT -->
                <div class="page-content-wrapper">
                    <!-- BEGIN CONTENT BODY -->
                    <div class="page-content">

                    @yield('content')  

                    </div>
                    <!-- END CONTENT BODY -->
                </div>
                <!-- END CONTENT -->



            </div>
            <!-- END CONTAINER -->

            <!-- BEGIN FOOTER -->
            @include('partials.footer')
            <!-- END FOOTER -->
        </div>

        @include('partials.script')


    </body>
</html>