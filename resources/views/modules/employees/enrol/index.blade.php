@extends('layout')

@section('content')
    <div class="page-head">
        <div class="page-title">
            <h1>Employees - <small> View the currently employed persons</small></h1>
        </div>
    </div>
    <ul class="page-breadcrumb breadcrumb">
        <li>
            <a href="{{ url('/') }}">Home</a>
            <i class="fa fa-circle"></i>
        </li>

        <li>
            <a href="{{ route('employees.index') }}">Employee</a>
        </li>
    </ul>

    <div class="row">
        <div class="col-sm-12">
            <!-- BEGIN PORTLET-->
            <div class="portlet light ">
                <div class="portlet-title">
                    <div class="caption caption-md">
                        <i class="icon-bar-chart theme-font-color hide"></i>
                        <span class="caption-subject theme-font-color bold uppercase">Current Employees</span>
                    </div>
                    <div class="actions">
                        <a href="#" class="btn btn-transparent grey-salsa btn-circle btn-sm active"><i class="fa fa-plus"></i>Import from HR</a>

                        <a href="{{ route('employees.create') }}" class="btn btn-transparent grey-salsa btn-circle btn-sm active"><i class="fa fa-plus"></i> Add Employee</a>
                    </div>
                </div>
                <div class="portlet-body">
                    <table class="table table-striped table-hover table-responsive dataTable" id="allowances_table">
                        <thead>
                            <tr>
                                <th>
                                    Payroll Number
                                </th>
                                <th>
                                    Identification
                                </th>
                                <th>
                                    Full Names
                                </th>
                                <th>
                                    Mobile Phone
                                </th>
                                <th>
                                    Edit
                                </th>
                                <th>
                                    Delete
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($employees as $employee)
                            <tr>
                                <td>
                                    <a href="{{ route('employees.show', ['id' => $employee->id]) }}">{{ $employee->payroll_number }}</a>
                                </td>
                                <td class="text-center">
                                    {{ $employee->identification_number }}
                                </td>
                                <td class="text-center">
                                    {{ $employee->first_name . ' ' . $employee->last_name }}
                                </td>
                                <td class="text-center">
                                    {{ $employee->mobile_phone }}
                                </td>
                                <td class="text-center">
                                    <a class="btn btn-success btn-xs" href="{{ route('employees.edit', $employee->id) }}">
                                        Edit </a>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('employees.destroy', $employee->id) }}" class="btn btn-danger btn-xs" data-method="delete" rel="nofollow" data-confirm="Are you sure you want to delete this?" data-token="{{ csrf_token() }}">Delete</a>
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

