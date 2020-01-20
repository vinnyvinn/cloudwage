@extends('layout')
@section('content')
    <div class="page-head">
        <div class="page-title">
            <h1>Employee Assignments - <small> Set up the designations of each employee within the organization the organization</small></h1>
        </div>
    </div>
    <ul class="page-breadcrumb breadcrumb">
        <li>
            <a href="{{ url('/') }}">Home</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="{{ route('assignments.index') }}">Employee Assignments</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="#">Edit</a>
        </li>
    </ul>

    <div class="row">
        <div class="col-sm-12">
            <form action="{{ route('assignments.update', $assignment->id) }}" method="post" role="form">
                {{ csrf_field() }}
                {{ method_field('put') }}
                <div class="portlet light">
                    <div class="portlet-title">
                        <div class="caption font-red-sunglo">
                            <i class="fa fa-briefcase font-red-sunglo"></i>
                            <span class="caption-subject bold uppercase"> Employee Assignment Details</span>
                        </div>
                    </div>
                    <div class="portlet-body form">
                        <div class="form-body">
                            <div class="form-group form-md-line-input">
                                <label for="payrol">Payroll Number*</label>
                                <div class="form-control">
                                    {{ $assignment->employee->payroll_number }}
                                </div>
                                <span class="help-block">This is the payroll number of the employee</span>
                            </div>
                            <div class="form-group form-md-line-input">
                                <label for="name">Employee Name*</label>
                                <div class="form-control" id="name">
                                    {{ $assignment->employee->first_name . ' ' . $assignment->employee->last_name }}
                                </div>
                                <span class="help-block">This is the name of the employee</span>
                            </div>
                            <div class="form-group form-md-line-input form-md-floating-label">
                                <select class="form-control" id="payment_structure_id" name="payment_structure_id">
                                    @foreach($departments as $department)
                                        <option value="{{ $department->id }}" {{ $department->id == $assignment->department_id ? 'selected' : '' }}>{{ $department->name }}</option>
                                    @endforeach
                                </select>
                                <label for="payment_structure_id">Department*</label>
                                <span class="help-block">This will department to which the employee is attached</span>
                            </div>

                            <div class="form-group form-md-line-input form-md-floating-label">
                                <input type="submit" class="btn btn-primary" value="Save">
                                <a class="btn btn-danger" href="{{ route('assignments.index') }}">Back</a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection