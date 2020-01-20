@extends('layout')
@section('content')
    <div class="page-head">
        <div class="page-title">
            <h1>General OT settings - <small> Set up general parameters</small></h1>
        </div>
    </div>
    <ul class="page-breadcrumb breadcrumb">
        <li>
            <a href="{{ url('/') }}">Home</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="{{ route('overtime.index') }}">Settings</a>
            <i class="fa fa-circle"></i>
        </li>

    </ul>

    <div class="row">
        <div class="col-sm-12">
            <form action="{{ route('general.store') }}" method="post" role="form">
                {{ csrf_field() }}
                <div class="portlet light">
                    <div class="portlet-title">
                        <div class="caption font-red-sunglo">
                            <i class="fa fa-briefcase font-red-sunglo"></i>
                            <span class="caption-subject bold uppercase"> Overtime Settings</span>
                        </div>
                    </div>

                <div class="portlet-body form">
                    <div class="form-body">
                        <div class="form-group form-md-line-input form-md-floating-label">
                            <input type="number" min="1" value="{{ $setting->days }}"class="form-control" name="days" required>
                            <label for="employee_id">Working Days*</label>
                            <span class="help-block">This is the company number of working days</span>
                        </div>
                    </div>
                    <div class="form-body">
                        <div class="form-group form-md-line-input form-md-floating-label">
                            <input type="number" min="1" value="{{ $setting->hours }}" step="0.1" class="form-control" name="hours" required>
                            <label for="employee_id">Working hours*</label>
                            <span class="help-block">This is the company number of working hours in a day</span>
                        </div>
                    </div>
                    <div class="form-body">
                        <div class="form-group form-md-line-input form-md-floating-label">
                            <input type="number" min="1" value={{ $setting->ot_1 }} step="0.1" class="form-control" name="ot_1" required>
                            <label for="employee_id">OT One Rate*</label>
                            <span class="help-block">This is the company's overtime one rate</span>
                        </div>
                    </div>
                    <div class="form-body">
                        <div class="form-group form-md-line-input form-md-floating-label">
                            <input type="number" min="1" step="0.1" value={{ $setting->ot_2 }} class="form-control" name="ot_2" required>
                            <label for="employee_id">OT Two Rate*</label>
                            <span class="help-block">This is the company's overtime two rate</span>
                        </div>
                    </div>
                    <div class="form-group form-md-line-input form-md-floating-label">
                        <input type="submit" class="btn btn-primary" value="Save">
                        <a class="btn btn-danger" href="{{ route('general.index') }}">Back</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    </div>
    @endsection