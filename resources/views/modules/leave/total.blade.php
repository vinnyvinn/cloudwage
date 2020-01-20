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
                    <a href="{{ route('leave.create') }}" class="btn btn-transparent grey-salsa btn-circle btn-sm active pull-right"><i class="fa fa-plus"></i> Add Leave</a>
                </div>
                <div class="portlet-body">
                    <table class="table table-responsive table-striped table-hover dataTable" id="leave_table">
                        <thead>
                        <tr>
                            <th>Payroll Number</th>
                            <th>Employee</th>
                           <th>Year</th>
                            <th>Total Days</th>

                        </tr>
                        </thead>
                        <tbody>
                            @foreach($employees as $employee)
                                <?php
                                $leaves = $employee->leaves;
                                $tots = 0;
                                foreach ($leaves as $leave){
                                    $tots += $leave->days;
                                }
                                ?>
                                <tr>
                                    <td class="text-center">{{ $employee->payroll_number }}</td>
                                    <td class="text-center">{{ $employee->first_name }} {{ $employee->last_name }}</td>
                                    <td class="text-center"> 2019 </td>
                                    <td class="text-center">{{ $tots }}</td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection