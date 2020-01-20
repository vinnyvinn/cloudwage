@extends('layout')
@section('content')
    <div class="page-head">
        <div class="page-title">
            <h1>Deductions - <small> Set up the deductions that can be assigned to employees in the organization</small></h1>
        </div>
    </div>
    <ul class="page-breadcrumb breadcrumb">
        <li>
            <a href="{{ url('/') }}">Home</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="{{ route('deductions.index') }}">Deductions</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="#">View</a>
        </li>
    </ul>

    <div class="row">
        <div class="col-sm-12">
            <div class="portlet box blue">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-user"></i>{{ $deduction->name }} Details
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="tabbable-custom nav-justified">
                        <ul class="nav nav-tabs nav-justified">
                            <li class="active">
                                <a href="#details" data-toggle="tab">
                                    Deduction Details </a>
                            </li>
                            <li>
                                <a href="#payments" data-toggle="tab">
                                    Deduction Payments </a>
                            </li>
                            <li>
                                <a href="#employees" data-toggle="tab">
                                    Employees with {{ $deduction->name }} </a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="details">
                                <div class="form-body row">
                                    <div class="col-sm-6">
                                        <div class="form-group form-md-line-input">
                                            <label for="name">Deduction Name</label>
                                            <div class="form-control">{{ $deduction->name }}</div>
                                        </div>
                                        <div class="form-group form-md-line-input">
                                            <label for="name">Date Due</label>
                                            <div class="form-control">{{ $deduction->due_date }}<sup>{{ getSuffix($deduction->due_date) }}</sup> of every month</div>
                                        </div>
                                        <div class="form-group form-md-line-input">
                                            <label for="name">Exemption</label>
                                            <div class="form-control">Below {{ $currency . ' ' . number_format($deduction->threshold, 2) }}</div>
                                        </div>
                                        <div class="form-group form-md-line-input">
                                            <label for="name">Has Relief?</label>
                                            <div class="form-control">{{ $deduction->has_relief == 1 ? 'Yes' : 'No' }}</div>
                                        </div>

                                        @if($deduction->has_relief)
                                            <div class="form-group form-md-line-input">
                                                <label for="name">Relief Name</label>
                                                <div class="form-control">{{ $relief->name }}</div>
                                            </div>
                                            <div class="form-group form-md-line-input">
                                                <label for="name">Relief Amount</label>
                                                <div class="form-control">{{ $relief->amount }}</div>
                                            </div>
                                        @endif

                                        <div class="form-group form-md-line-input">
                                            <label for="name">Deduction Type</label>
                                            <div class="form-control">{{ title_case(str_replace('_', ' ', $deduction->type)) }}</div>
                                        </div>
                                        @if($deduction->type == 'rate')
                                            <div class="form-group form-md-line-input">
                                                <label for="name">Rate</label>
                                                <div class="form-control">{{ strstr($deduction->rate, '%') ? $deduction->rate : $currency . ' ' . number_format($deduction->rate, 2) }}</div>
                                            </div>
                                        @endif
                                        <div class="form-group form-md-line-input">
                                            <a class="btn btn-success" href="{{ route('deductions.edit', ['deductions' => $deduction->id]) }}">Edit</a>
                                            @if($deduction->id != 1 && $deduction->id != 2 && $deduction->id != 3)
                                                <a href="{{ route('deductions.destroy', $deduction->id) }}" class="btn btn-danger" data-method="delete" rel="nofollow" data-confirm="Are you sure you want to delete this?" data-token="{{ csrf_token() }}">Delete</a>
                                            @endif
                                            <a class="btn btn-warning" href="{{ URL::previous() }}">Back</a>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        @if($deduction->type == 'slab')
                                            <h4 class="caption-subject bold uppercase">Slabs</h4>
                                            <table class="table table-stripped table-hover table-responsive">
                                                <thead>
                                                <tr class="row">
                                                    <th class="col-sm-1 text-right">#</th>
                                                    <th class="col-sm-4 text-right">From</th>
                                                    <th class="col-sm-4 text-right">To</th>
                                                    <th class="col-sm-3 text-right">Rate</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($slabs as $slab)
                                                    <tr class="row">
                                                        <td class="col-sm-1 text-right">{{ $slab->slab_number }}</td>
                                                        @if($slab->max_amount == 0)
                                                            <td class="col-sm-8 text-right" colspan="2">{{ $slab->max_amount == 0 ? 'Above ' . $currency . ' ' . number_format($slab->min_amount, 2) : $currency . ' ' . number_format($slab->min_amount, 2) }}</td>
                                                        @else
                                                            <td class="col-sm-4 text-right">{{ $currency . ' ' . number_format($slab->min_amount, 2) }}</td>
                                                            <td class="col-sm-4 text-right">{{ $currency . ' ' . number_format($slab->max_amount, 2) }}</td>
                                                        @endif
                                                        <td class="col-sm-3 text-right">{{ strstr($slab->rate, '%') ? $slab->rate : $currency . ' ' . number_format($slab->rate, 2) }}</td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="payments">
                                <h3>Employee Deductions</h3>
                                <hr>
                                <table class="table table-striped table-hover table-responsive dataTable">
                                    <thead>
                                    <tr>
                                        <th>For Month</th>
                                        <th>Payroll Number</th>
                                        <th>Employee Name</th>
                                        <th>Deduction Number</th>
                                        <th>Amount Deducted</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($deduction->payments()->with('employee')->get() as $deductionPayment)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($deductionPayment->for_month)->format('F Y') }}</td>
                                            <td>
                                                <a href="{{ route('employees.show', $deductionPayment->employee) }}">
                                                    {{ $deductionPayment->employee->payroll_number }}
                                                </a>
                                            </td>
                                            <td>{{ $deductionPayment->employee->first_name . ' ' . $deductionPayment->employee->last_name }}</td>
                                            <td class="text-right">{{ $deductionPayment->deduction_number }}</td>
                                            <td class="text-right">{{ number_format($deductionPayment->amount, 2) }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane" id="employees">
                                <span class="pull-right">
                                    <a href="{{ route('employee-deductions.create', ['deduction' => $deduction->id]) }}" class="btn btn-warning">Assign Employees</a>
                                    <a href="{{ route('deductions.report', $deduction->id) }}" class="btn btn-primary">Generate Reports</a>
                                </span>
                                <h3>Employees with {{ $deduction->name }} ({{ $deduction->employees->count() }})</h3>
                                <hr>
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
                                            Gender
                                        </th>
                                        <th>
                                            Mobile Phone
                                        </th>
                                        <th>
                                            Amount
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
                                    @foreach($deduction->employees as $employee)
                                        <tr>
                                            <td>
                                                <a href="{{ route('employees.show', ['id' => $employee->id]) }}">{{ $employee->payroll_number }}</a>
                                            </td>
                                            <td>
                                                {{ $employee->identification_type . ' - ' . $employee->identification_number }}
                                            </td>
                                            <td>
                                                {{ $employee->first_name . ' ' . $employee->last_name }}
                                            </td>
                                            <td>
                                                {{ $employee->gender }}
                                            </td>
                                            <td>
                                                {{ $employee->mobile_phone }}
                                            </td>
                                            <td class="text-right">
                                                @if($deduction->type == 'per_employee')
                                                {{ number_format($deduction->employeeDeductions->keyBy('employee_id')->get($employee->id)->amount, 2) }}
                                                @else
                                                {{ title_case($deduction->type) }} Based
                                                @endif
                                            </td>
                                            <td>
                                                <a class="btn btn-success btn-xs" href="{{ route('employee-deductions.edit', $deduction->employeeDeductions->keyBy('employee_id')->get($employee->id)->id) }}">Edit</a>
                                            </td>
                                            <td>
                                                <a href="{{ route('employee-deductions.destroy', $deduction->employeeDeductions->keyBy('employee_id')->get($employee->id)->id) }}" class="btn btn-danger btn-xs" data-method="delete" rel="nofollow" data-confirm="Are you sure you want to delete this?" data-token="{{ csrf_token() }}">Delete</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection