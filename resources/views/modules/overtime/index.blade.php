@extends('layout')

@section('content')
    <div class="page-head">
        <div class="page-title">
            <h1>Overtime- <small> Set up the employees overtime</small></h1>
        </div>
    </div>
    <ul class="page-breadcrumb breadcrumb">
        <li>
            <a href="{{ url('/') }}">Home</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="{{ route('overtime.index') }}">Overtime Calculation</a>
        </li>
    </ul>

    <div class="row">
        <div class="col-sm-12">
            <!-- BEGIN PORTLET-->
            <div class="portlet light ">
                <div class="portlet-title">
                    <div class="caption caption-md">
                        <i class="icon-bar-chart theme-font-color hide"></i>
                        <span class="caption-subject theme-font-color bold uppercase">Overtime</span>
                    </div>
                    <div class="actions">
                        <a href="{{ route('overtime.create') }}" class="btn btn-transparent grey-salsa btn-circle btn-sm active"><i class="fa fa-plus"></i> Add Overtime</a>
                    </div>
                </div>
                <div class="portlet-body">
                    <table class="table table-striped table-hover table-responsive dataTable" id="allowances_table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>
                                Employee Name
                            </th>
                            <th>
                                Month
                            </th>
                            <th>
                                OT-1 Rate
                            </th>
                            <th>
                                OT-1 Hours
                            </th>
                            <th>
                                OT-2 Rate
                            </th>
                            <th>
                                OT-2 Hours
                            </th>
                            <th>
                                Total Amount
                            </th>
                            <th>
                                Actions
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($overtimes as $key=>$overtime)
                            <tr class="text-center">
                                <td>{{ $key+1 }}</td>
                                <td>{{ $overtime->employee->first_name .' '. $overtime->employee->last_name }}</td>
                                <td>{{ \Carbon\Carbon::parse($overtime->payroll_date)->format('M-Y') }}</td>

                                <td>
                                    {{ $overtime->ot_1_rate }}
                                </td>
                                <td>
                                    {{ $overtime->ot_1_hrs }}
                                </td>
                                <td>
                                    {{ $overtime->ot_2_rate }}
                                </td>
                                <td>
                                    {{ $overtime->ot_2_hrs }}
                                </td>
                                <td>
                                    {{ number_format($overtime->amount, 2) }}
                                </td>
                                <td>
                                    <a href="{{ route('overtime.destroy', $overtime->id) }}" class="btn btn-sm btn-danger" data-method="delete" rel="nofollow" data-confirm="Are you sure you want to delete this?" data-token="{{ csrf_token() }}"> <span class="fa fa-trash"></span> </a>
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

