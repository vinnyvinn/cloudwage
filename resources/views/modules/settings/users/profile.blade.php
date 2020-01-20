@extends('layout')
@section('content')
    <div class="page-head">
        <div class="page-title">
            <h1>System Users - <small> Set up the users of the system.</small></h1>
        </div>
    </div>
    <ul class="page-breadcrumb breadcrumb">
        <li>
            <a href="{{ url('/') }}">Home</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="{{ url('user-profile') }}">Profile</a>
        </li>
    </ul>

    <div class="row">
        <div class="col-sm-12">
            <form action="{{ url('user-profile') }}" method="post" role="form">
                {{ csrf_field() }}
                <div class="portlet light">
                <div class="portlet-title">
                    <div class="caption font-red-sunglo">
                        <i class="fa fa-briefcase font-red-sunglo"></i>
                        <span class="caption-subject bold uppercase"> User Details</span>
                    </div>
                </div>
                <div class="portlet-body form">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-body">
                                <div class="form-group form-md-line-input form-md-floating-label">
                                    <input type="text" class="form-control" value="{{ $user->username }}" disabled required>
                                    <label for="username">Username*</label>
                                    <span class="help-block">This is the username of the user</span>
                                </div>
                                <div class="form-group form-md-line-input form-md-floating-label">
                                    <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" required>
                                    <label for="email">Email*</label>
                                    <span class="help-block">This is the email of the user</span>
                                </div>
                                <div class="form-group form-md-line-input form-md-floating-label">
                                    <input type="submit" class="btn btn-primary" value="Save">
                                    <a class="btn btn-danger" href="{{ URL::previous() }}">Back</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-body">
                                <div class="form-group form-md-line-input form-md-floating-label">
                                    <input type="password" class="form-control" id="old_password" name="old_password" required>
                                    <label for="password">Old Password*</label>
                                    <span class="help-block">This is the password for the user account</span>
                                </div>
                                <div class="form-group form-md-line-input form-md-floating-label">
                                    <input type="password" class="form-control" id="password" name="password" required>
                                    <label for="password">New Password*</label>
                                    <span class="help-block">This is the password for the user account</span>
                                </div>
                                <div class="form-group form-md-line-input form-md-floating-label">
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                                    <label for="password_confirmation">Confirm Password*</label>
                                    <span class="help-block">This is the password for the user account</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>
@endsection
