@extends('layout')
@section('content')
    <div class="page-head">
        <div class="page-title">
            <h1>Employee Contracts - <small> The current contracts that the organization has.</small></h1>
        </div>
    </div>
    <ul class="page-breadcrumb breadcrumb">
        <li>
            <a href="{{ url('/') }}">Home</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="{{ route('contracts.index') }}">Employee Contracts</a>
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
                            <span class="caption-subject bold uppercase"> Employee Contract Details</span>
                        </div>
                    </div>
                    <div class="portlet-body form">
                        <div class="form-body row">
                            <div class="col-sm-6">
                                <div class="form-group form-md-line-input form-md-floating-label">
                                    <label for="name">Employee Name</label>
                                    <div class="form-control">
                                        <a href="{{ route('employees.show', $contract->employee->id) }}">{{ $contract->employee->first_name . ' ' . $contract->employee->last_name }}</a>
                                    </div>
                                </div>
                                <div class="form-group form-md-line-input form-md-floating-label">
                                    <label for="name">Employee Type</label>
                                    <div class="form-control">
                                        <a href="{{ route('employee-types.show', $contract->employeeType->id) }}">{{ $contract->employeeType->name }}</a>
                                    </div>
                                </div>
                                <div class="form-group form-md-line-input form-md-floating-label">
                                    <label for="name">Pay Grade</label>
                                    <div class="form-control">
                                        <a href="{{ route('grades.show', $contract->payGrade->id) }}">{{ $contract->payGrade->name }}</a>
                                    </div>
                                </div>
                                <div class="form-group form-md-line-input form-md-floating-label">
                                    <a class="btn btn-success" href="{{ route('contracts.edit', $contract->id) }}">Edit</a>
                                    <a href="{{ route('contracts.destroy', $contract->id) }}" class="btn btn-danger" data-method="delete" rel="nofollow" data-confirm="Are you sure you want to delete this?" data-token="{{ csrf_token() }}">Delete</a>
                                    <a class="btn btn-warning" href="{{ route('contracts.index') }}">Back</a>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group form-md-line-input form-md-floating-label">
                                    <label for="name">Current Basic Salary</label>
                                    <div class="form-control">
                                        {{ $contract->currency->code . ' ' . number_format($contract->current_basic_salary, 2) }}
                                    </div>
                                </div>
                                <div class="form-group form-md-line-input form-md-floating-label">
                                    <label for="name">Contract Start Date</label>
                                    <div class="form-control">
                                        {{ $contract->start_date->toFormattedDateString() }}
                                    </div>
                                </div>
                                <div class="form-group form-md-line-input form-md-floating-label">
                                    <label for="name">Contract End Date</label>
                                    <div class="form-control">
                                        {{ $contract->end_date->toFormattedDateString() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>
@endsection