@extends('layout')
@section('content')
    <div class="page-head">
        <div class="page-title">
            <h1>Time Attendance - <small> Enter the attendance for the employees</small></h1>
        </div>
    </div>
    <ul class="page-breadcrumb breadcrumb">
        <li>
            <a href="{{ url('/') }}">Home</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="{{ route($route . '.index') }}">{{ $title }}</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="#">Edit</a>
        </li>
    </ul>

    <div class="row">
        <div class="col-sm-12">
            <form action="{{ route($route . '.update', $attendance->id) }}" method="post" role="form">
                {{ csrf_field() }}
                {{ method_field('put') }}
                <div class="portlet light">
                    <div class="portlet-title">
                        <div class="caption font-red-sunglo">
                            <i class="fa fa-briefcase font-red-sunglo"></i>
                            <span class="caption-subject bold uppercase"> Attendance Details</span>
                        </div>
                    </div>
                    <div class="portlet-body form">
                        <div class="form-body">
                            <div class="form-group form-md-line-input">
                                <div class="form-control" id="employee_id">{{ $attendance->employee->payroll_number . ' ' . $attendance->employee->first_name . ' ' . $attendance->employee->last_name }}</div>
                                <label for="name">Employee</label>
                                <span class="help-block">This is the name of the employee</span>
                            </div>
                            <div class="form-group form-md-line-input">
                                <div class="form-control" id="for_month">{{ Carbon\Carbon::parse($attendance->for_month)->format('d F Y') }}</div>
                                <label for="for_month">Month</label>
                                <span class="help-block">This is the month of attendance</span>
                            </div>
                            <div class="form-group form-md-line-input form-md-floating-label">
                                <input type="number" class="form-control" id="{{ $field }}" name="{{ $field }}" min="0" value="{{ $attendance->$field }}" required>
                                <label for="{{ $field }}">{{ title_case(str_replace('_', ' ', $field)) }}*</label>
                                <span class="help-block">This is the employee attendance for the month</span>
                            </div>
                            <div class="form-group form-md-line-input form-md-floating-label">
                                <input type="submit" class="btn btn-primary" value="Save">
                                <a class="btn btn-danger" href="{{ route($route . '.index') }}">Back</a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
