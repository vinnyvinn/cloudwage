@extends('layout')
@section('content')
    <div class="page-head">
        <div class="page-title">
            <h1>Leaves</h1>
        </div>
    </div>
    <ul class="page-breadcrumb breadcrumb">
        <li>
            <a href="{{ url('/') }}">Home</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="{{ route('leave.index') }}">Leave</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="#">Edit</a>
        </li>
    </ul>
    <div class="row">
        <div class="col-sm-12">
            <div class="portlet light">
                <div class="portlet-title">

                </div>
                <div class="portlet-body">
                    <form action="{{ route('leave.update', $input->id) }}" method="post">
                        {{ csrf_field() }}
                        {{ method_field('PUT') }}
                        <div class="form-group form-md-line-input form-md-floating-label">
                            <input id="employee_id" class="form-control" disabled value="{{ $input->employee->first_name }} {{ $input->employee->last_name }}">
                            <label for="employee_id">Employee</label>
                            <span class="help-block">This is the name of the employee to be assigned leave</span>
                        </div>
                        <div class="form-group form-md-line-input form-md-floating-label leaveDate">
                            <label for="start_date">Start Date</label>
                            <div class="input-group date date-picker margin-bottom-5" readonly data-date-format="dd-mm-yyyy">
                                <input type="text" class="form-control form-filter input-sm" name="start_date" value="{{ Carbon\Carbon::parse($input->start_date)->format('d-m-Y') }}" required>
                                <span class="input-group-btn">
                                    <button class="btn btn-sm default" type="button"><i class="fa fa-calendar"></i></button>
                                </span>
                            </div>
                            <span class="help-block">This is the start date of the leave given</span>
                        </div>
                        <div class="form-group form-md-line-input form-md-floating-label ">
                            <label for="end_date">End Date</label>
                            <div class="input-group date date-picker margin-bottom-5" readonly data-date-format="dd-mm-yyyy">
                                <input type="text" class="form-control form-filter input-sm" name="end_date"  value="{{ Carbon\Carbon::parse($input->end_date)->format('d-m-Y') }}" required>
                                <span class="input-group-btn">
                                    <button class="btn btn-sm default" type="button"><i class="fa fa-calendar"></i></button>
                                </span>
                            </div>
                            <span class="help-block">This is the end date of the leave given</span>
                        </div>
                        <div class="form-group form-md-line-input form-md-floating-label">
                            <select class="form-control" name="status" id="status" required>
{{--                                <option value="{{ Payroll\Models\EmployeeLeave::PENDING }}"{{ $input->status ==  Payroll\Models\EmployeeLeave::PENDING ? ' selected' : ''}}>Pending approval</option>--}}
                                <option value="{{ Payroll\Models\EmployeeLeave::APPROVED }}"{{ $input->status == Payroll\Models\EmployeeLeave::APPROVED ? ' selected' : ''}}>Approved</option>
                                <option value="{{ Payroll\Models\EmployeeLeave::DECLINED }}"{{ $input->status == Payroll\Models\EmployeeLeave::DECLINED  ? ' selected' : ''}}>Declined</option>
                            </select>
                            <label for="status">Status</label>
                            <span class="help-block">This is the end date of the leave given</span>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-success">Save</button>
                            <a href="{{ route('leave.index') }}" class="btn btn-danger">Back</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('footer')
    <script>

    </script>
@endsection