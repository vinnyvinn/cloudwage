@extends('layout')
@section('content')
    <div class="page-head">
        <div class="page-title">
            <h1>Payroll - <small> View the currently generated payrolls</small></h1>
        </div>
    </div>
    <ul class="page-breadcrumb breadcrumb">
        <li>
            <a href="{{ url('/') }}">Home</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="{{ route('payroll.index') }}">Payroll</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="#">View</a>
        </li>
    </ul>

    <div class="row">
        <div class="col-sm-12">
                <div class="portlet light">
                    <div class="portlet-title">
                        <div class="caption font-red-sunglo">
                            <i class="fa fa-briefcase font-red-sunglo"></i>
                            <span class="caption-subject bold uppercase"> Payroll Details</span>
                        </div>
                    </div>
                    <div class="portlet-body form">
                        <div class="form-body row">
                            <div class="col-sm-4">
                                <div class="form-group form-md-line-input form-md-floating-label">
                                    <label for="name">Payroll Date</label>
                                    <div type="text" class="form-control">{{ $payroll->payroll_date->startOfMonth()->toFormattedDateString() .' - '. $payroll->payroll_date->toFormattedDateString() }}</div>
                                </div>
                                <div class="form-group form-md-line-input form-md-floating-label">
                                    <label for="name">Payroll Number</label>
                                    <div type="text" class="form-control">{{ $payroll->employee->payroll_number }}</div>
                                </div>
                                <div class="form-group form-md-line-input form-md-floating-label">
                                    <label for="name">First Name</label>
                                    <div type="text" class="form-control">{{ $payroll->employee->first_name }}</div>
                                </div>
                                <div class="form-group form-md-line-input form-md-floating-label">
                                    <label for="name">Last Name</label>
                                    <div type="text" class="form-control">{{ $payroll->employee->last_name }}</div>
                                </div>

                                <div class="form-group form-md-line-input form-md-floating-label">
                                    {{--<a class="btn btn-success" href="{{ route('employee-types.edit', $payroll->id) }}">Edit</a>--}}
                                    {{--<a href="{{ route('employee-types.destroy', $payroll->id) }}" class="btn btn-danger" data-method="delete" rel="nofollow" data-confirm="Are you sure you want to delete this?" data-token="{{ csrf_token() }}">Delete</a>--}}
                                    <a class="btn btn-warning" href="{{ URL::previous() }}">Back</a>
                                </div>
                            </div>
                            <div class="col-sm-8">
                                <iframe src="{{ route('payroll.pdf', $payroll->id) }}#zoom=60" class="iPdf" frameborder="0"></iframe>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>
@endsection