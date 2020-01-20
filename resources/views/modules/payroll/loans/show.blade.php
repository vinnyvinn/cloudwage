@extends('layout')

@section('content')
    <div class="page-head">
        <div class="page-title">
            <h1>Company Loans - <small> Current loans given to employees in the Organization</small></h1>
        </div>
    </div>
    <ul class="page-breadcrumb breadcrumb">
        <li>
            <a href="{{ url('/') }}">Home</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="{{ route('loans.index') }}">Loans</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="#">Loans for {{ $title }}</a>
        </li>
    </ul>

    <div class="row">
        <div class="col-sm-12">
            <!-- BEGIN PORTLET-->
            <div class="portlet light ">
                <div class="portlet-title">
                    <div class="caption caption-md">
                        <a href="{{ route('loans.index') }}" class="btn btn-transparent grey-salsa btn-circle btn-sm active"><i class="fa fa-angle-left"></i> back</a>
                        <i class="icon-bar-chart theme-font-color hide"></i>
                        <span class="caption-subject theme-font-color bold uppercase">Current Loans for {{ $title }}</span>
                    </div>
                    <div class="actions">
                        <a href="{{ route('loans.create') }}" class="btn btn-transparent grey-salsa btn-circle btn-sm active"><i class="fa fa-plus"></i> Process New Loan</a>
                    </div>
                </div>
                <div class="portlet-body">
                    <table class="table table-striped table-hover table-responsive dataTable" id="allowances_table">
                        <thead>
                        <tr>
                            <th>
                                Date
                            </th>
                            <th>
                                Payroll #
                            <th>
                                Amount
                            </th>
                            <th>
                                Balance
                            </th>
                            <th>
                                Installments
                            </th>
                            <th>
                                Duration
                            </th>
                            <th>
                                Status
                            </th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($loans as $loan)
                            <tr>
                                <td>
                                    {{ $loan->date_processed->format('d F Y') }}
                                </td>
                                <td>
                                    <a href="{{ route('employees.show', $loan->employee->id) }}">{{ $loan->employee->payroll_number }}</a>
                                </td>
                                <td class="text-right">
                                    {{ $currency->code . ' ' . number_format($loan->amount, 2) }}
                                </td>
                                <td class="text-right">
                                    {{  $currency->code . ' ' . number_format($loan->balance, 2) }}
                                </td>
                                <td class="text-right">
                                    {{ $currency->code . ' ' . number_format($loan->installments, 2) }}
                                </td>
                                <td class="text-right">
                                    {{ $loan->duration }} Months ({{ is_float($loan->duration / 12) ? number_format($loan->duration / 12, 2) : $loan->duration / 12 }} Year{{ $loan->duration / 12 > 1 ? 's' : '' }})
                                </td>
                                <td class="text-center">
                                    @if($loan->balance > 0)
                                        <span class="btn btn-warning btn-xs">Not Cleared</span>
                                    @else
                                        <span class="btn btn-success btn-xs">Fully Paid</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('loans.details', $loan->id) }}" class="btn btn-xs btn-success">View</a>
                                </td>
                                <td>
                                    <a href="{{ route('loans.edit', $loan->id) }}" class="btn btn-xs btn-info">Edit</a>
                                </td>
                                <td>
                                    <a href="{{ route('loans.destroy', $loan->id) }}" class="btn btn-danger btn-xs" data-method="delete" rel="nofollow" data-confirm="Are you sure you want to delete this?" data-token="{{ csrf_token() }}">Delete</a>
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

