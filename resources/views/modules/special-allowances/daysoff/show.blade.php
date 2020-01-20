@extends('layout')

@section('content')
    <div class="page-head">
        <div class="page-title">
            <h1>Days off payments - <small> Current days off given to employees in the Organization</small></h1>
        </div>
    </div>
    <ul class="page-breadcrumb breadcrumb">
        <li>
            <a href="{{ url('/') }}">Home</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="{{ route('days-off.index') }}">Days off payments</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="#">Days off for {{ $title }}</a>
        </li>
    </ul>

    <div class="row">
        <div class="col-sm-12">
            <!-- BEGIN PORTLET-->
            <div class="portlet light ">
                <div class="portlet-title">
                    <div class="caption caption-md">
                        <a href="{{ route('days-off.index') }}" class="btn btn-transparent grey-salsa btn-circle btn-sm active"><i class="fa fa-angle-left"></i> back</a>
                        <i class="icon-bar-chart theme-font-color hide"></i>
                        <span class="caption-subject theme-font-color bold uppercase">Current Days Off for {{ $title }}</span>
                    </div>
                    <div class="actions">
                        <a href="{{ route('days-off.create') }}" class="btn btn-transparent grey-salsa btn-circle btn-sm active"><i class="fa fa-plus"></i> Process New Days-off</a>

                    </div>
                </div>
                <div class="portlet-body">
                    <table class="table table-striped table-hover table-responsive dataTable" id="allowances_table">
                        <thead>
                        <tr>
                            <th>
                                Days OFf For
                            </th>
                            <th>
                                Payroll Number
                            </th>
                            <th>
                                Employee Name
                            </th>

                            <th>
                                Rate
                            </th>
                            <th>
                                Units
                            </th>
                            <th>
                                Amount
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

                                <td>
                                    {{ number_format($advance->rate, 2) }}
                                </td>
                                <td>
                                    {{ number_format($advance->units, 2) }}
                                </td>
                                <td>
                                    {{  number_format($advance->total, 2) }}
                                </td>

                                <td>
                                    <a href="{{ route('days-off.destroy', $advance->id) }}" class="btn btn-danger btn-xs" data-method="delete" rel="nofollow" data-confirm="Are you sure you want to delete this?" data-token="{{ csrf_token() }}">Delete</a>
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

