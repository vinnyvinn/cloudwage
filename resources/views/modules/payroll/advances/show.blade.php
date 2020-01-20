@extends('layout')

@section('content')
    <div class="page-head">
        <div class="page-title">
            <h1>Salary Advances - <small> Current advances given to employees in the Organization</small></h1>
        </div>
    </div>
    <ul class="page-breadcrumb breadcrumb">
        <li>
            <a href="{{ url('/') }}">Home</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="{{ route('advances.index') }}">Salary Advances</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="#">Salary Advances for {{ $title }}</a>
        </li>
    </ul>

    <div class="row">
        <div class="col-sm-12">
            <!-- BEGIN PORTLET-->
            <div class="portlet light ">
                <div class="portlet-title">
                    <div class="caption caption-md">
                        <a href="{{ route('advances.index') }}" class="btn btn-transparent grey-salsa btn-circle btn-sm active"><i class="fa fa-angle-left"></i> back</a>
                        <i class="icon-bar-chart theme-font-color hide"></i>
                        <span class="caption-subject theme-font-color bold uppercase">Current Salary Advances for {{ $title }}</span>
                    </div>
                    <div class="actions">
                        <a href="{{ route('advances.create') }}" class="btn btn-transparent grey-salsa btn-circle btn-sm active"><i class="fa fa-plus"></i> Process New Payment</a>
                        <a href="{{ route('advances.create') }}" class="btn btn-transparent grey-salsa btn-circle btn-sm active"><i class="fa fa-plus"></i> Process New Advance</a>
                    </div>
                </div>
                <div class="portlet-body">
                    <table class="table table-striped table-hover table-responsive dataTable" id="allowances_table">
                        <thead>
                        <tr>
                            <th>
                                Advance For
                            </th>
                            <th>
                                Payroll Number
                            </th>
                            <th>
                                Employee Name
                            </th>
                            <th>
                                Reason
                            </th>
                            <th>
                                Amount
                            </th>
                            <th>
                                Balance
                            </th>
                            <th>
                                Status
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
                        @foreach($advances as $advance)
                            <tr>
                                <td>
                                    {{ $advance->for_month->format('F Y') }}
                                </td>
                                <td>
                                    <a href="{{ route('employees.show', $advance->employee->id) }}">{{ $advance->employee->payroll_number }}</a>
                                </td>
                                <td>
                                    <a href="{{ route('employees.show', $advance->employee->id) }}">{{ $advance->employee->first_name . ' ' . $advance->employee->last_name }}</a>
                                </td>
                                <td> {{ $advance->desc != NULL ? $advance->desc :'None' }} </td>
                                <td>
                                    {{ number_format($advance->amount, 2) }}
                                </td>
                                <td>
                                    {{ number_format($advance->balance, 2) }}
                                </td>
                                <td>
                                    {{ $advance->status }}
                                </td>
                                <td>
                                    <a class="btn btn-success btn-xs" href="{{ route('advances.edit', $advance->id) }}">
                                        Edit </a>
                                </td>
                                <td>
                                    <a href="{{ route('advances.destroy', $advance->id) }}" class="btn btn-danger btn-xs" data-method="delete" rel="nofollow" data-confirm="Are you sure you want to delete this?" data-token="{{ csrf_token() }}">Delete</a>
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

