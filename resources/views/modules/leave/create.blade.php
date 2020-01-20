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
            <a href="{{ route('leave.create') }}">Create</a>
        </li>
    </ul>
    <div class="row">
        <div class="col-sm-12">
            <div class="portlet light">
                <div class="portlet-title">

                </div>
                <div class="portlet-body">
                    <form action="{{ route('leave.store') }}" method="post">
                       {{ csrf_field() }}
                        <div class="form-group form-md-line-input form-md-floating-label">
                            <select class="form-control" name="employee_id" id="employee_id" required>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? ' selected' : '' }}>{{ $employee->first_name }} {{ $employee->last_name }}</option>
                                @endforeach
                            </select>
                            <label for="employee_id">Employee</label>
                            <span class="help-block">This is the name of the employee to be assigned leave</span>
                        </div>
                        <div class="form-group form-md-line-input form-md-floating-label leaveDate">
                            <label for="start_date">Start Date</label>
                            <div class="input-group margin-bottom-5" readonly data-date-format="dd-mm-yyyy">
                                <input type="text" class="form-control form-filter input-sm" name="start_date" id="start_date" value="{{ old('start_date') }}" required>
                                <span class="input-group-btn">
                                    <button class="btn btn-sm default" type="button"><i class="fa fa-calendar"></i></button>
                                </span>
                            {{--<span class="help-block">This is the start date of the leave given</span>--}}
                        </div>
                        <div class="form-group form-md-line-input form-md-floating-label ">
                            <label for="end_date">End Date</label>
                            <div class="input-group margin-bottom-5" readonly data-date-format="dd-mm-yyyy">
                                <input type="text" class="form-control form-filter input-sm" name="end_date" id="end_date" value="{{ old('end_date') }}" required>
                                <span class="input-group-btn">
                                    <button class="btn btn-sm default" type="button"><i class="fa fa-calendar"></i></button>
                                </span>
                            </div>
                            <span class="help-block">This is the end date of the leave given</span>
                        </div>
                            {{--<label for="status">Status</label>--}}
                            <span class="help-block">This is the end date of the leave given</span>
                        </div>
                        <div class="form-group">
                            <input type="hidden" name="days" id="name">
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
        $('#end_date').datepicker({
            autoclose: true,
            format: 'yyyy-mm-dd',
        });

        $('#start_date').datepicker({
            autoclose: true,
            format: 'yyyy-mm-dd',

        }).on('change', function(e) {
            let startDate = new Date(e.target.value);
            let endDate = $('#end_date');

            endDate.datepicker('setStartDate', startDate);
            if (new Date(endDate.val()) < startDate) {
                endDate.datepicker('setDate', startDate);
            }
        });

    </script>
    @endsection