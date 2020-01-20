@extends('layout')

@section('content')
    <div class="page-head">
        <div class="page-title">
            <h1>Payroll - <small> View the currently generated payrolls</small></h1>
        </div>
    </div>
    <ul class="page-breadcrumb breadcrumb">
        <li>
            <a href="{{ url('/') }}">Home</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="{{ route('payroll.index') }}">Payroll</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="#">View</a>
        </li>
    </ul>

    <div class="row">
        <div class="col-sm-12">
            <!-- BEGIN PORTLET-->
            <div class="portlet light ">
                <div class="portlet-title">
                    <div class="caption caption-md">
                        <i class="icon-bar-chart theme-font-color hide"></i>
                        <span class="caption-subject theme-font-color bold uppercase">{{ $payrolls->first()->payroll_date->startOfMonth()->toFormattedDateString() .' - '. $payrolls->first()->payroll_date->toFormattedDateString() }} Payrolls</span>
                    </div>
                    <div class="actions">
                        <a href="{{ route('payroll.show.all', $payrolls->first()->payroll_date->month . '-' .$payrolls->first()->payroll_date->year ) }}" class="btn btn-transparent grey-salsa btn-circle btn-sm active"><i class="fa fa-users"></i> View All</a>
                        <div class="btn-group pull-right">
                            <button data-toggle="dropdown" class="btn dropdown-toggle btn-transparent grey-salsa btn-circle btn-sm active">Tools <i class="fa fa-angle-down"></i>
                            </button>
                            <ul class="dropdown-menu pull-right">
                                <li>
                                    <a href="javascript:;">
                                        Print </a>
                                </li>
                                <li>
                                    <a href="javascript:;">
                                        Save as PDF </a>
                                </li>
                                <li>
                                    <a href="javascript:;">
                                        Export to Excel </a>
                                </li>
                            </ul>
                        </div>
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
                                    Employee Name
                                </th>
                                <th>
                                    Basic Pay
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
                        @foreach($payrolls as $payroll)
                            <tr>
                                <td>
                                    <a href="{{ route('payroll.show', ['id' => $payroll->id]) }}">{{ $payroll->employee->payroll_number }}</a>
                                </td>
                                <td>
                                    {{ $payroll->employee->first_name . " " . $payroll->employee->last_name }}
                                </td>
                                <td>
                                    {{ $currency->code ." " . number_format($payroll->basic_pay, 2) }}
                                </td>
                                <td>
                                    <a class="btn btn-success btn-xs" href="{{ route('payroll.edit', $payroll->id) }}">
                                        Edit </a>
                                </td>
                                <td>
                                    <a href="{{ route('payroll.destroy', $payroll->id) }}" class="btn btn-danger btn-xs" data-method="delete" rel="nofollow" data-confirm="Are you sure you want to delete this?" data-token="{{ csrf_token() }}">Delete</a>
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

