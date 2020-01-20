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
            <a href="#">View</a>
        </li>
    </ul>

    <div class="row">
        <div class="col-sm-12">
                <div class="portlet light">
                    <div class="portlet-title">
                        <div class="caption font-red-sunglo">
                            <i class="fa fa-briefcase font-red-sunglo"></i>
                            <span class="caption-subject bold uppercase"> Attendance Details</span>
                        </div>
                    </div>
                    <div class="portlet-body form">
                        <table class="table table-striped table-hover table-responsive dataTable" id="allowances_table">
                            <thead>
                            <tr>
                                <th>
                                    Date
                                </th>
                                <th>
                                    Payroll #
                                </th>
                                <th>
                                    Employee
                                </th>
                                <th>
                                    Attendance
                                </th>
                                <th></th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($attendance as $worked)
                                <tr>
                                    <td>{{ Carbon\Carbon::parse($worked->for_month)->format('d F Y') }}</td>
                                    <td>{{ $worked->employee->payroll_number }}</td>
                                    <td>{{ $worked->employee->first_name . ' ' . $worked->employee->last_name }}</td>
                                    <td class="text-right">{{ $worked->$field }}</td>
                                    <td>
                                        <a href="{{ route($route . '.edit', $worked->id) }}" class="btn btn-xs btn-info">Edit</a></td>
                                    <td>
                                        <a href="{{ route($route . '.destroy', $worked->id) }}" class="btn btn-danger btn-xs" data-method="delete" rel="nofollow" data-confirm="Are you sure you want to delete this?" data-token="{{ csrf_token() }}">Delete</a>
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