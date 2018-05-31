<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
    <!--<![endif]-->
    <!-- BEGIN HEAD -->

    <head>
        <meta charset="utf-8" />
        <title>Payroll System</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="Preview page of Metronic Admin Theme #1 for " name="description" />
        <meta content="" name="author" />
        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/global/plugins/font-awesome/css/font-awesome.min.css') }}"  rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/global/plugins/simple-line-icons/simple-line-icons.min.css') }}"  rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/global/plugins/bootstrap/css/bootstrap.min.css') }}"  rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css') }}"  rel="stylesheet" type="text/css" />
        <!-- END GLOBAL MANDATORY STYLES -->
        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <link href="{{ asset('assets/global/plugins/select2/css/select2.min.css') }}"  rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/global/plugins/select2/css/select2-bootstrap.min.css') }}"  rel="stylesheet" type="text/css" />
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN THEME GLOBAL STYLES -->
        <link href="{{ asset('assets/global/css/components-md.min.css') }}"  rel="stylesheet" id="style_components" type="text/css" />
        <link href="{{ asset('assets/global/css/plugins-md.min.css') }}"  rel="stylesheet" type="text/css" />
        <!-- END THEME GLOBAL STYLES -->
        <!-- BEGIN PAGE LEVEL STYLES -->
        <link href="{{ asset('assets/pages/css/lock-2.min.css') }}"  rel="stylesheet" type="text/css" />
        <!-- END PAGE LEVEL STYLES -->
        <!-- BEGIN THEME LAYOUT STYLES -->

        <link href="{{ asset('assets/customs/style.css') }}"  rel="stylesheet" type="text/css" />

        <!-- END THEME LAYOUT STYLES -->
        <link rel="icon" href="{{ asset('favicon-32x-32.png') }}" sizes="32x32" />
        <link rel="icon" href="{{ asset('favicon-192x192.png') }}" sizes="192x192" />

        </head>
        <!-- END HEAD -->

        <style>
            .page-lock { top: 40%; }
        </style>
        <body>


        <!-- BEGIN LOGIN -->
        <div class="page-lock">
            <div class="page-logo">
                <a class="brand" href="">
                <img src="{{ asset(App\Setting::get_setting('logo')) }}" alt="" />
                </a>
            </div>

            @include('notification')

            <div class="page-body">
                <img class="page-lock-img" src="{{ has_photo($info->profile_picture) }}" alt="">
                <div class="page-lock-info">
                    <h1 class="uppercase">{{ $info->fullname }}</h1>
                    <span class="email"> {{ $info->email }} </span>
                    <span class="sbold text-warning small"> <i class="fa fa-lock"></i> LOCKED </span>
                    <form class="form-inline" action="" method="post">

                        {!! csrf_field() !!}

                        <div class="input-group input-medium">
                            <input type="text" name="password" class="form-control" placeholder="Password">
                            <span class="input-group-btn">
                                <button type="submit" class="btn green icn-only">
                                    <i class="m-icon-swapright m-icon-white"></i>
                                </button>
                            </span>
                        </div>

                        <!-- START error message -->
                        {!! $errors->first('password','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
                        <!-- END error message -->

                        <!-- /input-group -->
                        <div class="relogin">
                            <a href="{{ URL::route('auth.login') }}"> Not {{ $info->fullname }} ? </a>
                        </div>
                    </form>
                </div>
            </div>
            <div class="page-footer-custom"> 
                {{ date('Y') }} &copy; {{ App\Setting::get_setting('copy_right') }}
             </div>
        </div>

        <!-- END LOGIN -->

        <!-- BEGIN COPYRIGHT -->

        <!-- END COPYRIGHT -->
        <!--[if lt IE 9]>
        <script src="{{ asset('assets/global/plugins/respond.min.js') }}" ></script>
        <script src="{{ asset('assets/global/plugins/excanvas.min.js') }}" ></script> 
        <script src="{{ asset('assets/global/plugins/ie8.fix.min.js') }}" ></script> 
        
        <![endif]-->
        <!-- BEGIN CORE PLUGINS -->
        <script src="{{ asset('assets/global/plugins/jquery.min.js') }}"  type="text/javascript"></script>
        <script src="{{ asset('assets/global/plugins/bootstrap/js/bootstrap.min.js') }}"  type="text/javascript"></script>
        <script src="{{ asset('assets/global/plugins/js.cookie.min.js') }}"  type="text/javascript"></script>
        <!-- END CORE PLUGINS -->

        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <script src="{{ asset('assets/global/plugins/backstretch/jquery.backstretch.min.js') }}"  type="text/javascript"></script>
        <!-- END PAGE LEVEL PLUGINS -->


        <script>
        var Lock = function () {
            return {
                init: function () {
                     $.backstretch([
                        "{{ asset('assets/pages/media/bg/1.jpg') }}",
                        "{{ asset('assets/pages/media/bg/2.jpg') }}",
                        "{{ asset('assets/pages/media/bg/3.jpg') }}",
                        "{{ asset('assets/pages/media/bg/4.jpg') }}"
                        ], {
                          fade: 1000,
                          duration: 8000
                      });
                }
            };
        }();
        jQuery(document).ready(function() {
            Lock.init();
        });            
        </script>


    </body>

    </body>

</html>