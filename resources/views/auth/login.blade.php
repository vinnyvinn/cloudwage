<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<head>
    <meta charset="utf-8"/>
    <title>CloudWage | Login</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport"/>
    <meta content="" name="description"/>
    <meta content="" name="author"/>
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('plugins/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('plugins/simple-line-icons/simple-line-icons.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('plugins/uniform/css/uniform.default.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('plugins/bootstrap-switch/css/bootstrap-switch.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('plugins/select2/select2.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('admin/pages/css/login3.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('plugins/select2/select2.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('plugins/select2/select2.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('css/components-rounded.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('css/plugins.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('admin/layout4/css/layout.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('admin/layout4/css/themes/default.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('admin/layout4/css/custom.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('css/style.css') }}" rel="stylesheet" type="text/css"/>
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}"/>
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="login">
<div class="errorDiv">
    @if(count($errors))
        <div class="alert alert-warning">
            @foreach($errors->all() as $error)
                {!! $error !!}
            @endforeach
        </div>
    @endif
</div>
<!-- BEGIN LOGO -->
<div class="logo">
    Cloud<span>Wage</span>
</div>
<!-- BEGIN LOGIN -->
<div class="content">
    <!-- BEGIN LOGIN FORM -->
    <form class="form-horizontal" role="form" method="POST" action="{{ url('/login') }}">
        {!! csrf_field() !!}
        <h3 class="form-title">Login to your account</h3>
        <div class="alert alert-danger display-hide">
            <button class="close" data-close="alert"></button>
			<span>
			Enter your username and password. </span>
        </div>
        <div class="form-group">
            <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
            <label class="control-label visible-ie8 visible-ie9">Username</label>
            <div class="input-icon">
                <i class="fa fa-user"></i>
                <input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="Username" name="username" required/>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label visible-ie8 visible-ie9">Password</label>
            <div class="input-icon">
                <i class="fa fa-lock"></i>
                <input class="form-control placeholder-no-fix" type="password" autocomplete="off" placeholder="Password" name="password" required/>
            </div>
        </div>
        <div class="form-actions">
            <label class="checkbox">
                <input type="checkbox" name="remember" value="1"/> Remember me </label>
            <button type="submit" class="btn green-haze pull-right">
                Login <i class="m-icon-swapright m-icon-white"></i>
            </button>
        </div>
        <div class="forget-password">
            <h4>Forgot your password ?</h4>
            <p>
                no worries, click <a href="javascript:;" id="forget-password">
                    here </a>
                to reset your password.
            </p>
        </div>
    </form>

    <form class="forget-form" action="{{ url('/forgot') }}" method="post">
        <h3>Forget Password ?</h3>
        <p>
            Enter your e-mail address below to reset your password.
        </p>
        <div class="form-group">
            <div class="input-icon">
                <i class="fa fa-envelope"></i>
                <input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="Email" name="email"/>
            </div>
        </div>
        <div class="form-actions">
            <button type="button" id="back-btn" class="btn">
                <i class="m-icon-swapleft"></i> Back </button>
            <button type="submit" class="btn green-haze pull-right">
                Submit <i class="m-icon-swapright m-icon-white"></i>
            </button>
        </div>
    </form>
    <!-- END FORGOT PASSWORD FORM -->
</div>
<!-- END LOGIN -->
<div class="copyright">
    {{ \Carbon\Carbon::now()->year }} &copy; <a href="http://wizag.biz" target="_blank"> Wise & Agile Solutions</a>
</div>

<script src="{{ asset('plugins/respond.min.js') }}"></script>
<script src="{{ asset('plugins/excanvas.min.js') }}"></script>
<script src="{{ asset('plugins/jquery.min.js') }}"></script>
<script src="{{ asset('plugins/jquery-migrate.min.js') }}"></script>
<script src="{{ asset('plugins/bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('plugins/jquery.blockui.min.js') }}"></script>
<script src="{{ asset('plugins/uniform/jquery.uniform.min.js') }}"></script>
<script src="{{ asset('plugins/jquery.cokie.min.js') }}"></script>
<script src="{{ asset('plugins/jquery-validation/js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('plugins/select2/select2.min.js') }}"></script>
<script src="{{ asset('scripts/metronic.js') }}"></script>
<script src="{{ asset('admin/layout4/scripts/layout.js') }}"></script>
<script src="{{ asset('admin/layout4/scripts/demo.js') }}"></script>
<script src="{{ asset('admin/pages/scripts/login.js') }}"></script>


</body>
<!-- END BODY -->
</html>