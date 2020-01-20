@extends('layout')
@section('content')
    <div class="page-head">
        <div class="page-title">
            <h1>System Users - <small> Set up the users of the system.</small></h1>
        </div>
    </div>
    <ul class="page-breadcrumb breadcrumb">
        <li>
            <a href="{{ url('/') }}">Home</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="{{ route('users.index') }}">System Users</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="#">Edit</a>
        </li>
    </ul>

    <div class="row">
        <div class="col-sm-12">
            <form action="{{ route('users.update', $user->id) }}" method="post" role="form">
                {{ csrf_field() }}
                {{ method_field('PUT') }}
                <div class="portlet light">
                    <div class="portlet-title">
                        <div class="caption font-red-sunglo">
                            <i class="fa fa-briefcase font-red-sunglo"></i>
                            <span class="caption-subject bold uppercase"> User Details</span>
                        </div>
                    </div>
                    <div class="portlet-body form">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-body">
                                    <div class="form-group form-md-line-input form-md-floating-label">
                                        <input type="text" class="form-control" id="username" name="username" value="{{ $user->username }}" required>
                                        <label for="username">Username*</label>
                                        <span class="help-block">This is the username of the user</span>
                                    </div>
                                    <div class="form-group form-md-line-input form-md-floating-label">
                                        <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" required>
                                        <label for="email">Email*</label>
                                        <span class="help-block">This is the email of the user</span>
                                    </div>
                                    <div class="form-group form-md-line-input form-md-floating-label">
                                        <input type="submit" class="btn btn-primary" value="Save">
                                        <a class="btn btn-danger" href="{{ URL::previous() }}">Back</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-8">
                                <div class="row">
                                    <h4>Permissions</h4>
                                    <div class="col-sm-12">
                                        <div class="col-sm-3">Module</div>
                                        <div class="col-sm-2 text-center">View</div>
                                        <div class="col-sm-2 text-center">Create</div>
                                        <div class="col-sm-2 text-center">Edit</div>
                                        <div class="col-sm-2 text-center">Delete</div>
                                        <div class="col-sm-1 text-center">All</div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="col-sm-3"></div>
                                        <div class="col-sm-2 text-center"><a href="#" class="btn btn-primary btn-xs btn-all" id="chk-view">All</a></div>
                                        <div class="col-sm-2 text-center"><a href="#" class="btn btn-primary btn-xs btn-all" id="chk-create">All</a></div>
                                        <div class="col-sm-2 text-center"><a href="#" class="btn btn-primary btn-xs btn-all" id="chk-edit">All</a></div>
                                        <div class="col-sm-2 text-center"><a href="#" class="btn btn-primary btn-xs btn-all" id="chk-delete">All</a></div>
                                        <div class="col-sm-1 text-center"></div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-12 sys-user">
                                        <div class="col-sm-3">System Users</div>
                                        <div class="col-sm-2 text-center"><input type="checkbox" class="form-control sys-user chk-view" name="permissions[]" value="{{ App\User::PERMISSIONS['Read'] }}"></div>
                                        <div class="col-sm-2 text-center"><input type="checkbox" class="form-control sys-user chk-create" name="permissions[]" value="{{ App\User::PERMISSIONS['Create'] }}"></div>
                                        <div class="col-sm-2 text-center"><input type="checkbox" class="form-control sys-user chk-edit" name="permissions[]" value="{{ App\User::PERMISSIONS['Update'] }}"></div>
                                        <div class="col-sm-2 text-center"><input type="checkbox" class="form-control sys-user chk-delete" name="permissions[]" value="{{ App\User::PERMISSIONS['Delete'] }}"></div>
                                        <div class="col-sm-1 text-center"><a href="#" class="btn btn-primary btn-xs btn-all" id="sys-user">All</a></div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-12 sys-user">
                                        <div class="col-sm-3">Company Profile</div>
                                        <div class="col-sm-2 text-center"><input type="checkbox" class="form-control cmp-profile chk-view" name="permissions[]" value="{{ Payroll\Models\CompanyProfile::PERMISSIONS['Read'] }}"></div>
                                        <div class="col-sm-2 text-center"></div>
                                        <div class="col-sm-2 text-center"><input type="checkbox" class="form-control cmp-profile chk-edit" name="permissions[]" value="{{ Payroll\Models\CompanyProfile::PERMISSIONS['Update'] }}"></div>
                                        <div class="col-sm-2 text-center"></div>
                                        <div class="col-sm-1 text-center"><a href="#" class="btn btn-primary btn-xs btn-all" id="cmp-profile">All</a></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 sys-user">
                                        <div class="col-sm-3">Branches</div>
                                        <div class="col-sm-2 text-center"><input type="checkbox" class="form-control brnch chk-view" name="permissions[]" value="{{ Payroll\Models\Branches::PERMISSIONS['Read'] }}"></div>
                                        <div class="col-sm-2 text-center"><input type="checkbox" class="form-control brnch chk-create" name="permissions[]" value="{{ Payroll\Models\Branches::PERMISSIONS['Create'] }}"></div>
                                        <div class="col-sm-2 text-center"><input type="checkbox" class="form-control brnch chk-edit" name="permissions[]" value="{{ Payroll\Models\Branches::PERMISSIONS['Update'] }}"></div>
                                        <div class="col-sm-2 text-center"><input type="checkbox" class="form-control brnch chk-delete" name="permissions[]" value="{{ Payroll\Models\Branches::PERMISSIONS['Delete'] }}"></div>
                                        <div class="col-sm-1 text-center"><a href="#" class="btn btn-primary btn-xs btn-all" id="brnch">All</a></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 sys-user">
                                        <div class="col-sm-3">Departments</div>
                                        <div class="col-sm-2 text-center"><input type="checkbox" class="form-control dpt chk-view" name="permissions[]" value="{{ Payroll\Models\Department::PERMISSIONS['Read'] }}"></div>
                                        <div class="col-sm-2 text-center"><input type="checkbox" class="form-control dpt chk-create" name="permissions[]" value="{{ Payroll\Models\Department::PERMISSIONS['Create'] }}"></div>
                                        <div class="col-sm-2 text-center"><input type="checkbox" class="form-control dpt chk-edit" name="permissions[]" value="{{ Payroll\Models\Department::PERMISSIONS['Update'] }}"></div>
                                        <div class="col-sm-2 text-center"><input type="checkbox" class="form-control dpt chk-delete" name="permissions[]" value="{{ Payroll\Models\Department::PERMISSIONS['Delete'] }}"></div>
                                        <div class="col-sm-1 text-center"><a href="#" class="btn btn-primary btn-xs btn-all" id="dpt">All</a></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 sys-user">
                                        <div class="col-sm-3">Company Allowances</div>
                                        <div class="col-sm-2 text-center"><input type="checkbox" class="form-control allowance chk-view" name="permissions[]" value="{{ Payroll\Models\Allowance::PERMISSIONS['Read'] }}"></div>
                                        <div class="col-sm-2 text-center"><input type="checkbox" class="form-control allowance chk-create" name="permissions[]" value="{{ Payroll\Models\Allowance::PERMISSIONS['Create'] }}"></div>
                                        <div class="col-sm-2 text-center"><input type="checkbox" class="form-control allowance chk-edit" name="permissions[]" value="{{ Payroll\Models\Allowance::PERMISSIONS['Update'] }}"></div>
                                        <div class="col-sm-2 text-center"><input type="checkbox" class="form-control allowance chk-delete" name="permissions[]" value="{{ Payroll\Models\Allowance::PERMISSIONS['Delete'] }}"></div>
                                        <div class="col-sm-1 text-center"><a href="#" class="btn btn-primary btn-xs btn-all" id="allowance">All</a></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 sys-user">
                                        <div class="col-sm-3">Company Deductions</div>
                                        <div class="col-sm-2 text-center"><input type="checkbox" class="form-control deduction chk-view" name="permissions[]" value="{{ Payroll\Models\Deduction::PERMISSIONS['Read'] }}"></div>
                                        <div class="col-sm-2 text-center"><input type="checkbox" class="form-control deduction chk-create" name="permissions[]" value="{{ Payroll\Models\Deduction::PERMISSIONS['Create'] }}"></div>
                                        <div class="col-sm-2 text-center"><input type="checkbox" class="form-control deduction chk-edit" name="permissions[]" value="{{ Payroll\Models\Deduction::PERMISSIONS['Update'] }}"></div>
                                        <div class="col-sm-2 text-center"><input type="checkbox" class="form-control deduction chk-delete" name="permissions[]" value="{{ Payroll\Models\Deduction::PERMISSIONS['Delete'] }}"></div>
                                        <div class="col-sm-1 text-center"><a href="#" class="btn btn-primary btn-xs btn-all" id="deduction">All</a></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 sys-user">
                                        <div class="col-sm-3">Employee Types</div>
                                        <div class="col-sm-2 text-center"><input type="checkbox" class="form-control emp-types chk-view" name="permissions[]" value="{{ Payroll\Models\EmployeeType::PERMISSIONS['Read'] }}"></div>
                                        <div class="col-sm-2 text-center"><input type="checkbox" class="form-control emp-types chk-create" name="permissions[]" value="{{ Payroll\Models\EmployeeType::PERMISSIONS['Create'] }}"></div>
                                        <div class="col-sm-2 text-center"><input type="checkbox" class="form-control emp-types chk-edit" name="permissions[]" value="{{ Payroll\Models\EmployeeType::PERMISSIONS['Update'] }}"></div>
                                        <div class="col-sm-2 text-center"><input type="checkbox" class="form-control emp-types chk-delete" name="permissions[]" value="{{ Payroll\Models\EmployeeType::PERMISSIONS['Delete'] }}"></div>
                                        <div class="col-sm-1 text-center"><a href="#" class="btn btn-primary btn-xs btn-all" id="emp-types">All</a></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 sys-user">
                                        <div class="col-sm-3">System Policies</div>
                                        <div class="col-sm-2 text-center"><input type="checkbox" class="form-control policies chk-view" name="permissions[]" value="{{ Payroll\Models\Policy::PERMISSIONS['Read'] }}"></div>
                                        <div class="col-sm-2 text-center"></div>
                                        <div class="col-sm-2 text-center"><input type="checkbox" class="form-control policies chk-edit" name="permissions[]" value="{{ Payroll\Models\Policy::PERMISSIONS['Update'] }}"></div>
                                        <div class="col-sm-2 text-center"></div>
                                        <div class="col-sm-1 text-center"><a href="#" class="btn btn-primary btn-xs btn-all" id="policies">All</a></div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-12 sys-user">
                                        <div class="col-sm-3">Shifts</div>
                                        <div class="col-sm-2 text-center"><input type="checkbox" class="form-control shifts chk-view" name="permissions[]" value="{{ Payroll\Models\Shift::PERMISSIONS['Read'] }}"></div>
                                        <div class="col-sm-2 text-center"><input type="checkbox" class="form-control shifts chk-create" name="permissions[]" value="{{ Payroll\Models\Shift::PERMISSIONS['Create'] }}"></div>
                                        <div class="col-sm-2 text-center"><input type="checkbox" class="form-control shifts chk-edit" name="permissions[]" value="{{ Payroll\Models\Shift::PERMISSIONS['Update'] }}"></div>
                                        <div class="col-sm-2 text-center"><input type="checkbox" class="form-control shifts chk-delete" name="permissions[]" value="{{ Payroll\Models\Shift::PERMISSIONS['Delete'] }}"></div>
                                        <div class="col-sm-1 text-center"><a href="#" class="btn btn-primary btn-xs btn-all" id="shifts">All</a></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 sys-user">
                                        <div class="col-sm-3">Work Plans</div>
                                        <div class="col-sm-2 text-center"><input type="checkbox" class="form-control plans chk-view" name="permissions[]" value="{{ Payroll\Models\WorkPlan::PERMISSIONS['Read'] }}"></div>
                                        <div class="col-sm-2 text-center"><input type="checkbox" class="form-control plans chk-create" name="permissions[]" value="{{ Payroll\Models\WorkPlan::PERMISSIONS['Create'] }}"></div>
                                        <div class="col-sm-2 text-center"><input type="checkbox" class="form-control plans chk-edit" name="permissions[]" value="{{ Payroll\Models\WorkPlan::PERMISSIONS['Update'] }}"></div>
                                        <div class="col-sm-2 text-center"><input type="checkbox" class="form-control plans chk-delete" name="permissions[]" value="{{ Payroll\Models\WorkPlan::PERMISSIONS['Delete'] }}"></div>
                                        <div class="col-sm-1 text-center"><a href="#" class="btn btn-primary btn-xs btn-all" id="plans">All</a></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 sys-user">
                                        <div class="col-sm-3">Holidays</div>
                                        <div class="col-sm-2 text-center"><input type="checkbox" class="form-control holidays chk-view" name="permissions[]" value="{{ Payroll\Models\Holiday::PERMISSIONS['Read'] }}"></div>
                                        <div class="col-sm-2 text-center"><input type="checkbox" class="form-control holidays chk-create" name="permissions[]" value="{{ Payroll\Models\Holiday::PERMISSIONS['Create'] }}"></div>
                                        <div class="col-sm-2 text-center"><input type="checkbox" class="form-control holidays chk-edit" name="permissions[]" value="{{ Payroll\Models\Holiday::PERMISSIONS['Update'] }}"></div>
                                        <div class="col-sm-2 text-center"><input type="checkbox" class="form-control holidays chk-delete" name="permissions[]" value="{{ Payroll\Models\Holiday::PERMISSIONS['Delete'] }}"></div>
                                        <div class="col-sm-1 text-center"><a href="#" class="btn btn-primary btn-xs btn-all" id="holidays">All</a></div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-12 sys-user">
                                        <div class="col-sm-3">Payment Methods</div>
                                        <div class="col-sm-2 text-center"><input type="checkbox" class="form-control pymnt-mthds chk-view" name="permissions[]" value="{{ Payroll\Models\PaymentMethod::PERMISSIONS['Read'] }}"></div>
                                        <div class="col-sm-2 text-center"><input type="checkbox" class="form-control pymnt-mthds chk-create" name="permissions[]" value="{{ Payroll\Models\PaymentMethod::PERMISSIONS['Create'] }}"></div>
                                        <div class="col-sm-2 text-center"><input type="checkbox" class="form-control pymnt-mthds chk-edit" name="permissions[]" value="{{ Payroll\Models\PaymentMethod::PERMISSIONS['Update'] }}"></div>
                                        <div class="col-sm-2 text-center"><input type="checkbox" class="form-control pymnt-mthds chk-delete" name="permissions[]" value="{{ Payroll\Models\PaymentMethod::PERMISSIONS['Delete'] }}"></div>
                                        <div class="col-sm-1 text-center"><a href="#" class="btn btn-primary btn-xs btn-all" id="pymnt-mthds">All</a></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 sys-user">
                                        <div class="col-sm-3">Payment Structures</div>
                                        <div class="col-sm-2 text-center"><input type="checkbox" class="form-control pymt-structure chk-view" name="permissions[]" value="{{ Payroll\Models\PaymentStructure::PERMISSIONS['Read'] }}"></div>
                                        <div class="col-sm-2 text-center"><input type="checkbox" class="form-control pymt-structure chk-create" name="permissions[]" value="{{ Payroll\Models\PaymentStructure::PERMISSIONS['Create'] }}"></div>
                                        <div class="col-sm-2 text-center"><input type="checkbox" class="form-control pymt-structure chk-edit" name="permissions[]" value="{{ Payroll\Models\PaymentStructure::PERMISSIONS['Update'] }}"></div>
                                        <div class="col-sm-2 text-center"><input type="checkbox" class="form-control pymt-structure chk-delete" name="permissions[]" value="{{ Payroll\Models\PaymentStructure::PERMISSIONS['Delete'] }}"></div>
                                        <div class="col-sm-1 text-center"><a href="#" class="btn btn-primary btn-xs btn-all" id="pymt-structure">All</a></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 sys-user">
                                        <div class="col-sm-3">Pay Grades</div>
                                        <div class="col-sm-2 text-center"><input type="checkbox" class="form-control pay-grades chk-view" name="permissions[]" value="{{ Payroll\Models\PayGrade::PERMISSIONS['Read'] }}"></div>
                                        <div class="col-sm-2 text-center"><input type="checkbox" class="form-control pay-grades chk-create" name="permissions[]" value="{{ Payroll\Models\PayGrade::PERMISSIONS['Create'] }}"></div>
                                        <div class="col-sm-2 text-center"><input type="checkbox" class="form-control pay-grades chk-edit" name="permissions[]" value="{{ Payroll\Models\PayGrade::PERMISSIONS['Update'] }}"></div>
                                        <div class="col-sm-2 text-center"><input type="checkbox" class="form-control pay-grades chk-delete" name="permissions[]" value="{{ Payroll\Models\PayGrade::PERMISSIONS['Delete'] }}"></div>
                                        <div class="col-sm-1 text-center"><a href="#" class="btn btn-primary btn-xs btn-all" id="pay-grades">All</a></div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-12 sys-user">
                                        <div class="col-sm-3">Employees</div>
                                        <div class="col-sm-2 text-center"><input type="checkbox" class="form-control employee chk-view" name="permissions[]" value="{{ Payroll\Models\Employee::PERMISSIONS['Read'] }}"></div>
                                        <div class="col-sm-2 text-center"><input type="checkbox" class="form-control employee chk-create" name="permissions[]" value="{{ Payroll\Models\Employee::PERMISSIONS['Create'] }}"></div>
                                        <div class="col-sm-2 text-center"><input type="checkbox" class="form-control employee chk-edit" name="permissions[]" value="{{ Payroll\Models\Employee::PERMISSIONS['Update'] }}"></div>
                                        <div class="col-sm-2 text-center"><input type="checkbox" class="form-control employee chk-delete" name="permissions[]" value="{{ Payroll\Models\Employee::PERMISSIONS['Delete'] }}"></div>
                                        <div class="col-sm-1 text-center"><a href="#" class="btn btn-primary btn-xs btn-all" id="employee">All</a></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 sys-user">
                                        <div class="col-sm-3">Employee Contracts</div>
                                        <div class="col-sm-2 text-center"><input type="checkbox" class="form-control emp-contracts chk-view" name="permissions[]" value="{{ Payroll\Models\EmployeeContract::PERMISSIONS['Read'] }}"></div>
                                        <div class="col-sm-2 text-center"><input type="checkbox" class="form-control emp-contracts chk-create" name="permissions[]" value="{{ Payroll\Models\EmployeeContract::PERMISSIONS['Create'] }}"></div>
                                        <div class="col-sm-2 text-center"><input type="checkbox" class="form-control emp-contracts chk-edit" name="permissions[]" value="{{ Payroll\Models\EmployeeContract::PERMISSIONS['Update'] }}"></div>
                                        <div class="col-sm-2 text-center"><input type="checkbox" class="form-control emp-contracts chk-delete" name="permissions[]" value="{{ Payroll\Models\EmployeeContract::PERMISSIONS['Delete'] }}"></div>
                                        <div class="col-sm-1 text-center"><a href="#" class="btn btn-primary btn-xs btn-all" id="emp-contracts">All</a></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 sys-user">
                                        <div class="col-sm-3">Employee Assignment</div>
                                        <div class="col-sm-2 text-center"><input type="checkbox" class="form-control emp-assignments chk-view" name="permissions[]" value="{{ Payroll\Models\Assignment::PERMISSIONS['Read'] }}"></div>
                                        <div class="col-sm-2 text-center"></div>
                                        <div class="col-sm-2 text-center"><input type="checkbox" class="form-control emp-assignments chk-edit" name="permissions[]" value="{{ Payroll\Models\Assignment::PERMISSIONS['Update'] }}"></div>
                                        <div class="col-sm-2 text-center"></div>
                                        <div class="col-sm-1 text-center"><a href="#" class="btn btn-primary btn-xs btn-all" id="emp-assignments">All</a></div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-12 sys-user">
                                        <div class="col-sm-3">Advances</div>
                                        <div class="col-sm-2 text-center"><input type="checkbox" class="form-control advances chk-view" name="permissions[]" value="{{ Payroll\Models\Advance::PERMISSIONS['Read'] }}"></div>
                                        <div class="col-sm-2 text-center"><input type="checkbox" class="form-control advances chk-create" name="permissions[]" value="{{ Payroll\Models\Advance::PERMISSIONS['Create'] }}"></div>
                                        <div class="col-sm-2 text-center"><input type="checkbox" class="form-control advances chk-edit" name="permissions[]" value="{{ Payroll\Models\Advance::PERMISSIONS['Update'] }}"></div>
                                        <div class="col-sm-2 text-center"><input type="checkbox" class="form-control advances chk-delete" name="permissions[]" value="{{ Payroll\Models\Advance::PERMISSIONS['Delete'] }}"></div>
                                        <div class="col-sm-1 text-center"><a href="#" class="btn btn-primary btn-xs btn-all" id="advances">All</a></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 sys-user">
                                        <div class="col-sm-3">Loans</div>
                                        <div class="col-sm-2 text-center"><input type="checkbox" class="form-control loans chk-view" name="permissions[]" value="{{ Payroll\Models\Loans::PERMISSIONS['Read'] }}"></div>
                                        <div class="col-sm-2 text-center"><input type="checkbox" class="form-control loans chk-create" name="permissions[]" value="{{ Payroll\Models\Loans::PERMISSIONS['Create'] }}"></div>
                                        <div class="col-sm-2 text-center"><input type="checkbox" class="form-control loans chk-edit" name="permissions[]" value="{{ Payroll\Models\Loans::PERMISSIONS['Update'] }}"></div>
                                        <div class="col-sm-2 text-center"><input type="checkbox" class="form-control loans chk-delete" name="permissions[]" value="{{ Payroll\Models\Loans::PERMISSIONS['Delete'] }}"></div>
                                        <div class="col-sm-1 text-center"><a href="#" class="btn btn-primary btn-xs btn-all" id="loans">All</a></div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-12 sys-user">
                                        <div class="col-sm-3">Days Worked</div>
                                        <div class="col-sm-2 text-center"><input type="checkbox" class="form-control days-worked chk-view" name="permissions[]" value="{{ Payroll\Models\DaysWorked::PERMISSIONS['Read'] }}"></div>
                                        <div class="col-sm-2 text-center"><input type="checkbox" class="form-control days-worked chk-create" name="permissions[]" value="{{ Payroll\Models\DaysWorked::PERMISSIONS['Create'] }}"></div>
                                        <div class="col-sm-2 text-center"><input type="checkbox" class="form-control days-worked chk-edit" name="permissions[]" value="{{ Payroll\Models\DaysWorked::PERMISSIONS['Update'] }}"></div>
                                        <div class="col-sm-2 text-center"><input type="checkbox" class="form-control days-worked chk-delete" name="permissions[]" value="{{ Payroll\Models\DaysWorked::PERMISSIONS['Delete'] }}"></div>
                                        <div class="col-sm-1 text-center"><a href="#" class="btn btn-primary btn-xs btn-all" id="days-worked">All</a></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 sys-user">
                                        <div class="col-sm-3">Hours Worked</div>
                                        <div class="col-sm-2 text-center"><input type="checkbox" class="form-control hours-worked chk-view" name="permissions[]" value="{{ Payroll\Models\HoursWorked::PERMISSIONS['Read'] }}"></div>
                                        <div class="col-sm-2 text-center"><input type="checkbox" class="form-control hours-worked chk-create" name="permissions[]" value="{{ Payroll\Models\HoursWorked::PERMISSIONS['Create'] }}"></div>
                                        <div class="col-sm-2 text-center"><input type="checkbox" class="form-control hours-worked chk-edit" name="permissions[]" value="{{ Payroll\Models\HoursWorked::PERMISSIONS['Update'] }}"></div>
                                        <div class="col-sm-2 text-center"><input type="checkbox" class="form-control hours-worked chk-delete" name="permissions[]" value="{{ Payroll\Models\HoursWorked::PERMISSIONS['Delete'] }}"></div>
                                        <div class="col-sm-1 text-center"><a href="#" class="btn btn-primary btn-xs btn-all" id="hours-worked">All</a></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 sys-user">
                                        <div class="col-sm-3">Units Made</div>
                                        <div class="col-sm-2 text-center"><input type="checkbox" class="form-control units-produced chk-view" name="permissions[]" value="{{ Payroll\Models\UnitsProduced::PERMISSIONS['Read'] }}"></div>
                                        <div class="col-sm-2 text-center"><input type="checkbox" class="form-control units-produced chk-create" name="permissions[]" value="{{ Payroll\Models\UnitsProduced::PERMISSIONS['Create'] }}"></div>
                                        <div class="col-sm-2 text-center"><input type="checkbox" class="form-control units-produced chk-edit" name="permissions[]" value="{{ Payroll\Models\UnitsProduced::PERMISSIONS['Update'] }}"></div>
                                        <div class="col-sm-2 text-center"><input type="checkbox" class="form-control units-produced chk-delete" name="permissions[]" value="{{ Payroll\Models\UnitsProduced::PERMISSIONS['Delete'] }}"></div>
                                        <div class="col-sm-1 text-center"><a href="#" class="btn btn-primary btn-xs btn-all" id="units-produced">All</a></div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-12 sys-user">
                                        <div class="col-sm-3">Payroll</div>
                                        <div class="col-sm-2 text-center"><input type="checkbox" class="form-control payroll chk-view" name="permissions[]" value="{{ Payroll\Models\Payroll::PERMISSIONS['Read'] }}"></div>
                                        <div class="col-sm-2 text-center"><input type="checkbox" class="form-control payroll chk-create" name="permissions[]" value="{{ Payroll\Models\Payroll::PERMISSIONS['Create'] }}"></div>
                                        <div class="col-sm-2 text-center"><input type="checkbox" class="form-control payroll chk-edit" name="permissions[]" value="{{ Payroll\Models\Payroll::PERMISSIONS['Update'] }}"></div>
                                        <div class="col-sm-2 text-center"><input type="checkbox" class="form-control payroll chk-delete" name="permissions[]" value="{{ Payroll\Models\Payroll::PERMISSIONS['Delete'] }}"></div>
                                        <div class="col-sm-1 text-center"><a href="#" class="btn btn-primary btn-xs btn-all" id="payroll">All</a></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 sys-user">
                                        <div class="col-sm-3">Coinage</div>
                                        <div class="col-sm-2 text-center"><input type="checkbox" class="form-control coinage chk-view" name="permissions[]" value="{{ Payroll\Models\Coinage::PERMISSIONS['Read'] }}"></div>
                                        <div class="col-sm-2 text-center"></div>
                                        <div class="col-sm-2 text-center"></div>
                                        <div class="col-sm-2 text-center"></div>
                                        <div class="col-sm-1 text-center"><a href="#" class="btn btn-primary btn-xs btn-all" id="coinage">All</a></div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-12 sys-user">
                                        <div class="col-sm-3">KRA P9</div>
                                        <div class="col-sm-2 text-center"><input type="checkbox" class="form-control krap9 chk-view" name="permissions[]" value="{{ Payroll\Models\KRAP9::PERMISSIONS['Read'] }}"></div>
                                        <div class="col-sm-2 text-center"></div>
                                        <div class="col-sm-2 text-center"></div>
                                        <div class="col-sm-2 text-center"></div>
                                        <div class="col-sm-1 text-center"><a href="#" class="btn btn-primary btn-xs btn-all" id="krap9">All</a></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 sys-user">
                                        <div class="col-sm-3">KRA P10A</div>
                                        <div class="col-sm-2 text-center"><input type="checkbox" class="form-control krap10a chk-view" name="permissions[]" value="{{ Payroll\Models\KRAP10A::PERMISSIONS['Read'] }}"></div>
                                        <div class="col-sm-2 text-center"></div>
                                        <div class="col-sm-2 text-center"></div>
                                        <div class="col-sm-2 text-center"></div>
                                        <div class="col-sm-1 text-center"><a href="#" class="btn btn-primary btn-xs btn-all" id="krap10a">All</a></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 sys-user">
                                        <div class="col-sm-3">KRA P10B</div>
                                        <div class="col-sm-2 text-center"><input type="checkbox" class="form-control krap10b chk-view" name="permissions[]" value="{{ Payroll\Models\KRAP10B::PERMISSIONS['Read'] }}"></div>
                                        <div class="col-sm-2 text-center"></div>
                                        <div class="col-sm-2 text-center"></div>
                                        <div class="col-sm-2 text-center"></div>
                                        <div class="col-sm-1 text-center"><a href="#" class="btn btn-primary btn-xs btn-all" id="krap10b">All</a></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('footer')
    <script>
        var permissions = {!! json_encode($user->permissions) !!};

        for (var key in permissions) {
            if (permissions.hasOwnProperty(key)) {
                var input = $('input[value="' + key + '"]');
                input.parent().addClass('checked');
                input.attr('checked', true);
            }
        }

        console.log(permissions);
        $('.btn-all').on('click', function (e) {
            e.preventDefault();
            var inputs = $('input.' + $(this).attr('id'));
            inputs.each(function (key, input) {
                input = $(input);
                input.is(':checked') ? input.parent().removeClass('checked') : input.parent().addClass('checked');
                input.attr('checked', !input.is(':checked'));
            });
        });
    </script>
@endsection