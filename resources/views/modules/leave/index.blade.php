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
            </li>
        </ul>
    <div class="row">
        <div class="col-sm-12">
            <div class="portlet light">
                <div class="portlet-title">
                    <i class="icon-bar-chart theme-font-color hide"></i>
                    <span class="caption-subject theme-font-color bold uppercase">Leaves</span>
                    <a href="{{ url('leave-total') }}" class="btn btn-transparent grey-salsa btn-circle btn-sm active pull-right"><i class="fa fa-plus"></i> Total Leave</a>
                    <a href="{{ route('leave.create') }}" class="btn btn-transparent grey-salsa btn-circle btn-sm active pull-right"><i class="fa fa-plus"></i> Add Leave</a>
                </div>
                <div class="portlet-body">
                    <table class="table table-responsive table-striped table-hover dataTable" id="leave_table">
                        <thead>
                        <tr>
                            <th>Employee</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Days</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($leaves as $leave)
                            <tr>
                                <td>{{ $leave->employee->first_name }} {{ $leave->employee->last_name }}</td>
                                <td class="text-center">{{Carbon\Carbon::parse($leave->start_date)->toFormattedDateString() }}</td>
                                <td class="text-center">{{ Carbon\Carbon::parse($leave->end_date)->toFormattedDateString() }}</td>
                                <td class="text-right">{{ $leave->days }}</td>
                                <td class="text-center">{{ ucwords($leave->status)  }}</td>
                                <td>
                                    @if($leave->status == Payroll\Models\EmployeeLeave::PENDING )
                                        <a href="{{ route('leave.edit', $leave->id) }}" class="btn btn-primary btn-xs">Edit</a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endsection