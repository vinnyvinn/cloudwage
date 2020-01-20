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
        </li>
    </ul>

    <div class="row">
        <div class="col-sm-12">
            <!-- BEGIN PORTLET-->
            <div class="portlet light ">
                <div class="portlet-title">
                    <div class="caption caption-md">
                        <i class="icon-bar-chart theme-font-color hide"></i>
                        <span class="caption-subject theme-font-color bold uppercase">Current Employee Assignments</span>
                    </div>
                </div>
                <div class="portlet-body">
                    <table class="table table-striped table-hover table-responsive dataTable" id="allowances_table">
                        <thead>
                            <tr>
                                <th>
                                    Employee Name
                                </th>
                                <th>
                                    Designated Department
                                </th>
                                <th>
                                    Edit
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($assignments as $assignment)
                            <tr>
                                <td>
                                    <a href="{{ route('employees.show', $assignment->employee->id) }}">{{ $assignment->employee->first_name . ' ' . $assignment->employee->last_name }}</a>
                                </td>
                                <td>
                                    <a href="{{ route('departments.show', $assignment->department->id) }}">{{ $assignment->department->name }}</a>
                                </td>
                                <td class="text-center">
                                    <a class="btn btn-success btn-xs" href="{{ route('assignments.edit', $assignment->id) }}">
                                        Edit </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- END PORTLET-->
        </div>
    </div>

@endsection

