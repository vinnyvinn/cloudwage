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
        </li>
    </ul>

    <div class="row">
        <div class="col-sm-12">
            <!-- BEGIN PORTLET-->
            <div class="portlet light ">
                <div class="portlet-title">
                    <div class="caption caption-md">
                        <i class="icon-bar-chart theme-font-color hide"></i>
                        <span class="caption-subject theme-font-color bold uppercase">Generated Payrolls</span>
                    </div>
                    <div class="actions">
                        <a href="{{ route('payroll.create') }}" class="btn btn-transparent grey-salsa btn-circle btn-sm active"><i class="fa fa-plus"></i> Generate Payroll</a>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <h3>Payroll Groups</h3>
                            <select name="group" id="group" class="form-control change">
                                <option disabled selected>Select Payroll Group</option>
                            @foreach($filters as $filter)
                                <option {{ request()->main == $filter->filter ? 'selected' : '' }} value="{{ route('payroll.show', 0) . '?main=' . $filter->filter . (is_null(request()->sub) ? '' : '&sub=' . request()->sub)}}">{{ $filter->filter }}</option>
                            @endforeach
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <h3>Sub-Groups</h3>
                            <select name="month" id="month" class="form-control change">
                                <option disabled selected>Select Payroll Month</option>
                            @foreach($sub_filters as $filter)
                                <option {{ request()->sub == urlencode($filter->payroll_date->month . '-' . $filter->payroll_date->year) ? 'selected' : '' }} value="{{ route('payroll.show', 0) }}?sub={{ urlencode($filter->payroll_date->month . '-' . $filter->payroll_date->year) . (is_null(request()->main) ? '' : '&main=' . request()->main) }}">{{ $filter->payroll_date->startOfMonth()->toFormattedDateString() .' - '. $filter->payroll_date->toFormattedDateString() }}</option>
                            @endforeach
                            </select>
                        </div>
                    </div>
                    <hr>
                    <a href="{{ route('payroll.show', is_null(request()->payroll) ? 0 : request()->payroll) . $allurl }}" class="btn btn-primary">View All Filtered</a>
                    @if(isset($pay_date))
                        <a href="{{ url('finalize-payroll') }}?date={{ $pay_date->format('Y-m-d') }}" class="btn btn-success">Finalize {{ $pay_date->format('F Y') }} Payroll</a>
                        <a href="{{ url('delete-payroll') }}?date={{ $pay_date->format('Y-m-d') }}" class="btn btn-danger">Delete {{ $pay_date->format('F Y') }} Payrolls</a>
                    @endif
                        <hr>
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
                                @if($payroll->finalized == 0)
                                    <a href="{{ route('payroll.destroy', $payroll->id) }}" class="btn btn-danger btn-xs" data-method="delete" rel="nofollow" data-confirm="Are you sure you want to delete this?" data-token="{{ csrf_token() }}">Delete</a>
                                @endif
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
@section('footer')
    <script>
        $('.change').on('change', function () {
            window.location = $(this).val();
        });
    </script>
@endsection

