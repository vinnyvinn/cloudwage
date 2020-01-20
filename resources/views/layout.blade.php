<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<head>
    <meta charset="utf-8"/>
    <title>CloudWage | Administration</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport"/>
    <meta content="" name="description"/>
    <meta content="" name="author"/>
    <meta id="token" name="token" value="{{ csrf_token() }}"/>
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('plugins/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('plugins/simple-line-icons/simple-line-icons.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('plugins/bootstrap-modal/css/bootstrap-modal.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('plugins/bootstrap-modal/css/bootstrap-modal-bs3patch.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('plugins/jquery-ui/jquery-ui.min.css') }}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}"/>

    <link href="{{ asset('plugins/uniform/css/uniform.default.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('plugins/bootstrap-switch/css/bootstrap-switch.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('css/fullcalendar.min.css') }}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/bootstrap-toastr/toastr.min.css') }}"/>

    <link href="{{ asset('plugins/bootstrap-daterangepicker/daterangepicker-bs3.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('plugins/fullcalendar/fullcalendar.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('plugins/morris/morris.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('admin/pages/css/tasks.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('css/components-rounded.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('css/plugins.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('admin/layout4/css/layout.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('admin/layout4/css/themes/default.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('admin/layout4/css/custom.css') }}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="{{ asset('plugins/select2/select2.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css') }}">
    @yield('header')
    <link href="{{ asset('css/style.css') }}" rel="stylesheet" type="text/css"/>
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}"/>
</head>

<body class="page-header-fixed page-sidebar-closed-hide-logo page-sidebar-fixed">
<div class="overlay" id="loader" hidden><span class="fa fa-cog fa-4x fa-spin"></span><h5>Please Wait</h5></div>
<div class="errorDiv">

</div>
@include('partials.nav')
<!-- BEGIN CONTAINER -->
<div class="page-container">

    @include('partials.sidebar')

    <!-- BEGIN CONTENT -->
    <div class="page-content-wrapper">
        <div class="page-content" id="content">
            @yield('content')
        </div>
    </div>
    <!-- END CONTENT -->
</div>

@include('partials.footer')

<!--[if lt IE 9]>
<script src="{{ asset('plugins/respond.min.js') }}"></script>
<script src="{{ asset('plugins/excanvas.min.js') }}"></script>
<![endif]-->
<script src="{{ asset('plugins/jquery.min.js') }}"></script>
<script src="{{ asset('plugins/jquery-migrate.min.js') }}"></script>
<script src="{{ asset('plugins/jquery-ui/jquery-ui.min.js') }}"></script>

<script src="{{ asset('plugins/bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js') }}"></script>
<script src="{{ asset('plugins/bootstrap-toastr/toastr.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('plugins/jquery-slimscroll/jquery.slimscroll.min.js') }}"></script>
<script src="{{ asset('plugins/jquery.blockui.min.js') }}"></script>
<script src="{{ asset('plugins/jquery.cokie.min.js') }}"></script>
<script src="{{ asset('plugins/uniform/jquery.uniform.min.js') }}"></script>
<script src="{{ asset('plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>
<script src="{{ asset('plugins/jquery.sparkline.min.js') }}"></script>
<script src="{{ asset('scripts/metronic.js') }}"></script>
<script src="{{ asset('admin/layout4/scripts/layout.js') }}"></script>
<script src="{{ asset('admin/layout4/scripts/quick-sidebar.js') }}"></script>
<script src="{{ asset('admin/pages/scripts/tasks.js') }}"></script>
<script src="{{ asset('plugins/moment.min.js') }}"></script>
<script src="{{ asset('js/fullcalendar.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/select2/select2.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/datatables/media/js/jquery.dataTables.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js') }}"></script>
@yield('footer')
<script src="{{ asset('js/scripts.js') }}"></script>

<script>
    jQuery(document).ready(function() {
        Metronic.init(); // init metronic core componets
        Layout.init(); // init layout
        QuickSidebar.init(); // init quick sidebar
        Tasks.initDashboardWidget(); // init tash dashboard widget
    });



</script>

@if(count($errors) > 0)
<?php
$displayErrors = "";
foreach($errors->all() as $error)
{
    $displayErrors .= $error . "<br>";
}
?>

<script>
    $(function() {
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "positionClass": "toast-top-right",
            "onclick": null,
            "showDuration": "1000",
            "hideDuration": "1000",
            "timeOut": "9000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };
        toastr['error']("{!! $displayErrors !!}");
    });
</script>
@endif
@if(currentSubdomain() == 'demo')
    <script>
        function changePassword() {
            $(function() {
                toastr.options = {
                    "closeButton": true,
                    "debug": false,
                    "positionClass": "toast-top-right",
                    "onclick": null,
                    "showDuration": "1000",
                    "hideDuration": "1000",
                    "timeOut": "9000",
                    "extendedTimeOut": "1000",
                    "showEasing": "swing",
                    "hideEasing": "linear",
                    "showMethod": "fadeIn",
                    "hideMethod": "fadeOut"
                };
                toastr['error']("Sorry, you cannot change password for the demo.");
            });
        }
    </script>
@endif
@if(session()->has('flash_message'))
    <script>
        $(function() {
            toastr.options = {
                "closeButton": true,
                "debug": false,
                "positionClass": "toast-top-right",
                "onclick": null,
                "showDuration": "1000",
                "hideDuration": "1000",
                "timeOut": "9000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            };
            toastr['{{ session('flash_message_status') }}']("{!! session('flash_message') !!}");
        });
    </script>
@endif

</body>
</html>