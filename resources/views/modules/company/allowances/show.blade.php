@extends('layout')
@section('content')
    <div class="page-head">
        <div class="page-title">
            <h1>Allowances - <small> View an allowance and its details</small></h1>
        </div>
    </div>
    <ul class="page-breadcrumb breadcrumb">
        <li>
            <a href="{{ url('/') }}">Home</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="{{ route('allowances.index') }}">Allowances</a>
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
                        <i class="fa fa-user"></i>{{ $allowance->name }} Details
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="tabbable-custom nav-justified">
                        <ul class="nav nav-tabs nav-justified">
                            <li class="active">
                                <a href="#details" data-toggle="tab">
                                    Allowance Details </a>
                            </li>
                            <li>
                                <a href="#employees" data-toggle="tab">
                                    Employees with {{ $allowance->name }} </a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="details">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div>
                                            <div><strong>Allowance Name</strong></div>
                                            <p>{{ $allowance->name }}</p>
                                            <hr>
                                        </div>
                                        <div>
                                            <div><strong>Currency</strong></div>
                                            <p>{{ $allowance->currency->name }}</p>
                                            <hr>
                                        </div>
                                        <div>
                                            <div><strong>Allocation Type</strong></div>
                                            <p>{{ $allowance->type == 'rate' ? "Rate on basic pay" : "Per Employee" }}</p>
                                            <hr>
                                        </div>
                                        <div>
                                            <div><strong>Allowance Type</strong></div>
                                            <p>{{ $allowance->non_cash == 1 ? "Non-Cash Benefit" : "Cash Benefit" }}</p>
                                            <hr>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div>
                                            <div><strong>Payment Allocation</strong></div>
                                            <p>{{ $allowance->in_basic == 1 ? "Included in basic pay" : "Excluded in basic pay" }}</p>
                                            <hr>
                                        </div>
                                        <div>
                                            <div><strong>Taxable?</strong></div>
                                            <p>{{ $allowance->taxable == 1 ? "Yes" : "No" }}</p>
                                            <hr>
                                        </div>
                                        @if($allowance->taxable)
                                            <div>
                                                <div><strong>Tax Rate</strong></div>
                                                <p>{{ $allowance->tax_rate }}%</p>
                                                <hr>
                                            </div>
                                        @endif
                                        <div>
                                            <div><strong>Has Relief?</strong></div>
                                            <p>{{ $allowance->has_relief == 1 ? 'Yes' : 'No' }}</p>
                                            <hr>
                                        </div>

                                        @if($allowance->has_relief)
                                            <div>
                                                <div><strong>Relief Name</strong></div>
                                                <p>{{ $allowance->relief->name }}</p>
                                                <hr>
                                            </div>
                                            <div>
                                                <div><strong>Relief Amount</strong></div>
                                                <p>{{ $allowance->relief->amount }}</p>
                                                <hr>
                                            </div>
                                        @endif

                                        <div>
                                            <a class="btn btn-success" href="{{ route('allowances.edit', ['allowances' => $allowance->id]) }}">Edit</a>
                                            <a href="{{ route('allowances.destroy', $allowance->id) }}" class="btn btn-danger" data-method="delete" rel="nofollow" data-confirm="Are you sure you want to delete this?" data-token="{{ csrf_token() }}">Delete</a>
                                            <a class="btn btn-warning" href="{{ URL::previous() }}">Back</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="employees">
                                <span class="pull-right">
                                    <a href="{{ route('employee-allowances.create', ['allowanceId' => $allowance->id]) }}" class="btn btn-warning">Assign Employees</a>
                                    <a href="{{ route('allowances.generate', $allowance->id) }}" class="btn btn-primary">Generate Reports</a>
                                </span>
                                <h3>Employees with {{ $allowance->name }} ({{ $employees->count() }})</h3>
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
                                    @foreach($employees as $employee)
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
                                                {{ $employee->mobile_phone }}
                                            </td>
                                            <td class="text-right">
                                                {{ $allowance->type == 'rate' ? $allowance->rate : number_format($allowance->employeeAllowances->keyBy('employee_id')->get($employee->id)->amount, 2) }}
                                            </td>
                                            <td>
                                                <a class="btn btn-success btn-xs" href="{{ route('employee-allowances.edit', $allowance->employeeAllowances->keyBy('employee_id')->get($employee->id)->id) }}">Edit</a>
                                            </td>
                                            <td>
                                                <a href="{{ route('employee-allowances.destroy', $allowance->employeeAllowances->keyBy('employee_id')->get($employee->id)->id) }}" class="btn btn-danger btn-xs" data-method="delete" rel="nofollow" data-confirm="Are you sure you want to delete this?" data-token="{{ csrf_token() }}">Delete</a>
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