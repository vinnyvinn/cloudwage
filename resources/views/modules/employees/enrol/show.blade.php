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
                        <i class="fa fa-user"></i>{{ $employee->first_name . ' ' . $employee->last_name }} Details
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="tabbable-custom nav-justified">
                        <ul class="nav nav-tabs nav-justified">
                            <li class="active">
                                <a href="#details" data-toggle="tab">
                                    Employee Details </a>
                            </li>
                            <li>
                                <a href="#contract" data-toggle="tab">
                                    Contracts </a>
                            </li>
                            <li>
                                <a href="#deductions" data-toggle="tab">
                                    Advances, Allowances & Deductions</a>
                            </li>
                            <li>
                                <a href="#payments" data-toggle="tab">
                                    Payments </a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="details">
                                <div class="form-body row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="name"><strong>Payroll Number</strong></label>
                                            <div>{{ $employee->payroll_number }}</div><hr>
                                        </div>
                                        <div class="form-group">
                                            <label for="name"><strong>{{ $employee->identification_type }}</strong></label>
                                            <div>{{ $employee->identification_number }}</div>
                                            <hr>
                                        </div>
                                        <div class="form-group">
                                            <label for="name"><strong>First Name</strong></label>
                                            <div>{{ $employee->first_name }}</div>
                                            <hr>
                                        </div>
                                        <div class="form-group">
                                            <label for="name"><strong>Last Name</strong></label>
                                            <div>{{ $employee->last_name }}</div>
                                            <hr>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="name"><strong>Mobile Phone</strong></label>
                                            <div>{{ $employee->mobile_phone }}</div>
                                            <hr>
                                        </div>
                                        <div class="form-group">
                                            <label for="name"><strong>Has Custom Tax Rate?</strong></label>
                                            <div>{{ $employee->has_custom_tax_rate ? 'Yes' : 'No' }}</div>
                                            <hr>
                                        </div>
                                        @if($employee->has_custom_tax_rate)
                                        <div class="form-group">
                                            <label for="name"><strong>Custom Tax Rate</strong></label>
                                            <div>{{ $employee->custom_tax_rate }}%</div>
                                            <hr>
                                        </div>
                                        @endif
                                        <div class="form-group form-md-line-input form-md-floating-label">
                                            <a class="btn btn-success" href="{{ route('employees.edit', $employee->id) }}">Edit</a>
                                            <a href="{{ route('employees.destroy', $employee->id) }}" class="btn btn-danger" data-method="delete" rel="nofollow" data-confirm="Are you sure you want to delete this?" data-token="{{ csrf_token() }}">Delete</a>
                                            <a class="btn btn-warning" href="{{ route('employees.index') }}">Back</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="contract">
                                <h3>Current Contract</h3>
                                <hr>
                                <div class="form-group">
                                    <label for="name"><strong>Contract Start</strong></label>
                                    <div>{{ \Carbon\Carbon::parse($employee->contract->first()->start_date)->toFormattedDateString() }}</div>
                                    <hr>
                                </div>
                                <div class="form-group">
                                    <label for="name"><strong>Contract End</strong></label>
                                    <div>{{ \Carbon\Carbon::parse($employee->contract->first()->end_date)->toFormattedDateString() }}</div>
                                    <hr>
                                </div>
                                <div class="form-group">
                                    <label for="name"><strong>Current Basic Salary</strong></label>
                                    <div>{{ number_format($employee->contract->first()->current_basic_salary, 2) }}</div>
                                    <hr>
                                </div>

                                <h3>All Contracts</h3>
                                <hr>
                                <table class="table table-striped table-hover table-responsive dataTable">
                                    <thead>
                                    <tr>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Employee Type</th>
                                        <th>Basic Salary</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($employee->contract()->with(['employeeType'])->withTrashed()->orderBy('start_date', 'DESC')->get() as $contract)
                                        <tr>
                                            <td class="text-center">{{ \Carbon\Carbon::parse($contract->start_date)->toFormattedDateString() }}</td>
                                            <td class="text-center">{{ \Carbon\Carbon::parse($contract->end_date)->toFormattedDateString() }}</td>
                                            <td class="text-right"><a
                                                        href="{{ route('employee-types.show', $contract->employee_type_id) }}">{{ $contract->employeeType->name }}</a></td>
                                            <td class="text-right">{{ number_format($contract->current_basic_salary, 2) }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane" id="deductions">
                                <h3>Advances</h3>
                                <hr>
                                <table class="table table-striped table-hover table-responsive dataTable">
                                    <thead>
                                    <tr>
                                        <th>Month</th>
                                        <th>Amount</th>
                                        <th>Balance</th>
                                        <th>Status</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($employee->advances as $advance)
                                        <tr>
                                            <td>{{ $advance->for_month->format('F-Y') }}</td>
                                            <td class="text-right">{{ number_format($advance->amount, 2) }}</td>
                                            <td class="text-right">{{ number_format($advance->balance, 2) }}</td>
                                        @if($advance->status == 'Paid')
                                            <td class='text-center'><span class="btn btn-success btn-xs">{{ $advance->status }}</span></td>
                                        @elseif($advance->status == 'Unpaid')
                                            <td class='text-center'><span class="btn btn-info btn-xs">{{ $advance->status }}</span></td>
                                        @else
                                            <td class='text-center'><span class="btn btn-error btn-xs">{{ $advance->status }}</span></td>
                                        @endif
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                <br>
                                <h3>Allowances</h3>
                                <hr>
                                <table class="table table-striped table-hover table-responsive dataTable">
                                    <thead>
                                    <tr>
                                        <th>Allowance</th>
                                        <th>Type</th>
                                        <th>Amount</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($employee->allowances as $allowance)
                                        <tr>
                                            <td><a href="{{ route('allowances.show', $allowance->allowance_id) }}">{{ $allowances->get($allowance->allowance_id)->name }}</a></td>
                                            <td>{{ $allowances->get($allowance->allowance_id)->taxable == 1 ? 'Taxable Allowance' : 'Benefit' }}</td>
                                            <td class="text-right">{{ number_format($allowance->amount, 2) }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                <br>
                                <h3>Deductions</h3>
                                <hr>
                                <table class="table table-striped table-hover table-responsive dataTable">
                                    <thead>
                                        <tr>
                                            <th>Deduction</th>
                                            <th>Deduction Number</th>
                                            <th>Amount / Type</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($employee->deductions as $deduction)
                                        <tr>
                                            <td><a href="{{ route('deductions.show', $deduction->deduction_id) }}">{{ $deductions->get($deduction->deduction_id)->name }}</a></td>
                                            <td>{{ $deduction->deduction_number }}</td>
                                            <td>{{ $deduction->amount == 0 ? studly_case(str_replace('_', ' ', $deductions->get($deduction->deduction_id)->type)) : number_format($deduction->amount, 2) }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane" id="payments">
                                <h3>Payment Method</h3>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <h4>Name</h4>
                                        <h5><strong>{{ $payment_method->name }}</strong></h5>
                                        <h4>Description</h4>
                                        <h5><strong>{{ $payment_method->description }}</strong></h5>
                                        <a href="{{ route('employee-payment-methods.edit', $employee->paymentMethod->id) }}"
                                           class="btn btn-primary">Edit Payment Method</a>
                                    </div>
                                    <div class="col-sm-6">
                                    @foreach($payment_method->udfs as $udf)
                                    <?php $field = $udf->field_name; ?>
                                        <h4>{{ $udf->field_title }}</h4>
                                        <h5><strong>{{ $employee->paymentMethod->$field }}</strong></h5>
                                    @endforeach
                                    </div>
                                </div>
                                <hr>
                                <h3>Advance Payments</h3>
                                <hr>
                                <table class="table table-striped table-hover table-responsive dataTable">
                                    <thead>
                                    <tr>
                                        <th>Advance For</th>
                                        <th>Amount</th>
                                        <th>Date Processed</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($employee->advancePayments as $advancePayment)
                                        <tr>
                                            <td>{{ $advancePayment->comment }}</td>
                                            <td class="text-right">{{ number_format($advancePayment->amount, 2) }}</td>
                                            <td class="text-right">{{ $advancePayment->created_at->format('F d, Y') }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                <br>
                                <h3>Deduction Amounts</h3>
                                <hr>
                                <div class="row">
                                    <table class="table table-striped table-hover table-responsive dataTable">
                                        <thead>
                                        <tr>
                                            <th>Month</th>
                                            <th>Deduction</th>
                                            <th>Deduction Number</th>
                                            <th>Amount</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($employee->deductionPayments as $deduction)
                                            <tr>
                                                <td>{{ \Carbon\Carbon::parse($deduction->for_month)->endOfMonth()->format('1 F Y') }}</td>
                                                <td><a href="{{ route('deductions.show', $deduction->deduction_id) }}">{{ $deductions->get($deduction->deduction_id)->name }}</a></td>
                                                <td class="text-right">{{ $deduction->deduction_number }}</td>
                                                <td class="text-right">{{ number_format($deduction->amount, 2) }}</td>
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
    </div>
@endsection