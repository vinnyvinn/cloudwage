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
            <a href="#">Create</a>
        </li>
    </ul>

    <div class="row">
        <div class="col-sm-12">
            <form action="{{ route('employees.store') }}" method="post" role="form">
                {{ csrf_field() }}
                <div class="row">
                    <div class="col-sm-6">
                        <div class="portlet light">
                            <div class="portlet-title">
                                <div class="caption font-red-sunglo">
                                    <i class="fa fa-briefcase font-red-sunglo"></i>
                                    <span class="caption-subject bold uppercase"> Employee Details</span>
                                </div>
                            </div>
                            <div class="portlet-body form">
                                <div class="form-body">
                                    <div class="form-group form-md-line-input form-md-floating-label">
                                        <input type="text" class="form-control" id="payroll_number" name="payroll_number" value="{{ old('payroll_number') }}" required>
                                        <label for="payroll_number">Payroll Number* (e.g. WI02314/2013)</label>
                                        <span class="help-block">This is the payroll number that will be used to refer to the employee</span>
                                    </div>
                                    <div class="form-group form-md-line-input form-md-floating-label">
                                        <input type="text" class="form-control" id="first_name" name="first_name" value="{{ old('first_name') }}" required>
                                        <label for="first_name">First Name*</label>
                                        <span class="help-block">This is the first name of the employee</span>
                                    </div>
                                    <div class="form-group form-md-line-input form-md-floating-label">
                                        <input type="text" class="form-control" id="last_name" name="last_name" value="{{ old('last_name') }}" required>
                                        <label for="last_name">Last Name*</label>
                                        <span class="help-block">This is the last name of the employee</span>
                                    </div>
                                    <div class="form-group form-md-line-input">
                                        <select class="form-control" id="identification_type" name="identification_type">
                                            <option value="National ID" {{ old('identification_type') == "National ID" ? 'selected' : '' }}>National ID</option>
                                            <option value="Passport" {{ old('identification_type') == "Passport" ? 'selected' : '' }}>Passport</option>
                                        </select>
                                        <label for="identification_type">Identification Type*</label>
                                        <span class="help-block">This is the type of identification to be used by the employee</span>
                                    </div>
                                    <div class="form-group form-md-line-input form-md-floating-label">
                                        <input type="text" class="form-control" id="identification_number" name="identification_number" value="{{ old('identification_number') }}" required>
                                        <label for="identification_number">Identification Number*</label>
                                        <span class="help-block">This is either the passport number or the national ID of the employee</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="portlet light">
                            <div class="portlet-title">
                                <div class="caption font-red-sunglo">
                                    <i class="fa fa-briefcase font-red-sunglo"></i>
                                    <span class="caption-subject bold uppercase"> Pay Grades, Deductions & Allowances</span>
                                </div>
                            </div>
                            <div class="portlet-body form">
                                <div class="form-body">
                                    <div class="form-group form-md-line-input">
                                        <select class="form-control" id="pay_grade_id" name="pay_grade_id">
                                            @foreach($pay_grades as $payGrade)
                                                <option value="{{ $payGrade->id }}" {{ old('pay_grade_id') == $payGrade->id ? 'selected' : '' }}>{{ $payGrade->name }}</option>
                                            @endforeach
                                        </select>
                                        <label for="pay_grade_id">Pay Grade*</label>
                                        <p>These will be the default deductions that will be used with this grade</p>
                                    </div>
                                    <div class="form-group form-md-line-input">
                                        <label for="payment_structure_id">Payment Structure*</label>
                                        <p class="form-control" id="payment_structure_id"></p>
                                        <input type="hidden" name="payment_structure_id">
                                        <span class="help-block">This is the payment structure to be allocated to the employee</span>
                                    </div>
                                    <input type="hidden" name="currency_id" id="currency_id" value="{{ Payroll\Models\CompanyProfile::first()->currency->id }}">
                                    <div class="form-group form-md-line-input form-md-floating-label">
                                        <label for="current_basic_salary">Current Salary*</label>
                                        <div class="input-group date margin-bottom-5" readonly>
                                            <span class="input-group-btn">
                                                <span class="btn btn-sm default">{{ Payroll\Models\CompanyProfile::first()->currency->code }}</span>
											</span>
                                            <input type="text" id="current_basic_salary" name="current_basic_salary" class="form-control text-right" value="{{ old('current_basic_salary') }}" required>
                                            <span class="input-group-btn">
                                                <span class="btn btn-sm default">Per <span id="unit">Month</span></span>
											</span>
                                        </div>
                                        <span>This is the current basic salary that the employee gets.</span>
                                    </div>

                                    <div class="form-group form-md-line-input">
                                        <label for="name">Allowances*</label><br><br>
                                        <div class="row">
                                            @foreach(Payroll\Models\Allowance::all() as $allowance)
                                                <input type="hidden" name="allowance{{ $allowance->id }}_type" id="allowance{{ $allowance->id }}_type" value="{{ $allowance->type }}" >
                                                <div class="form-group">
                                                    <label for="inputEmail3" class="col-sm-8 control-label">{{ $allowance->name }}</label>
                                                    <div class="col-sm-4">
                                                        <input type="checkbox" name="allowances[]" id="allowance{{ $allowance->id }}" class="make-switch" value="{{ $allowance->id }}" data-on-text="<i class='fa fa-check'></i>" data-off-text="<i class='fa fa-times'></i>">
                                                    </div>

                                                </div>
                                            @endforeach
                                        </div>
                                        <br><br><br>
                                        <p>Assign allowances to the employee</p>
                                    </div>

                                    <div class="form-group form-md-line-input">
                                        <label for="name">Default Deductions*</label><br><br>
                                        <div class="row">
                                            @foreach(Payroll\Models\Deduction::all() as $deduction)
                                                <input type="hidden" name="deduction{{ $deduction->id }}_type" id="deduction{{ $deduction->id }}_type" value="{{ $deduction->type }}" >
                                                <div class="form-group">
                                                    <label for="inputEmail3" class="col-sm-8 control-label">{{ $deduction->name }}</label>
                                                    <div class="col-sm-4">
                                                        <input type="checkbox" name="deductions[]" id="deduction{{ $deduction->id }}" class="make-switch" value="{{ $deduction->id }}" data-on-text="<i class='fa fa-check'></i>" data-off-text="<i class='fa fa-times'></i>">
                                                    </div>
                                                </div>
                                            
                                                @if($deduction->id == Payroll\Models\Deduction::PAYE)
                                                    <div class="form-group">
                                                        <label for="has_custom_tax_rate" class="col-sm-8 control-label">Has Custom Tax Rate?</label>
                                                        <div class="col-sm-4">
                                                            <input type="checkbox" name="has_custom_tax_rate" id="has_custom_tax_rate" class="make-switch" data-on-text="<i class='fa fa-check'></i>" data-off-text="<i class='fa fa-times'></i>">
                                                        </div>
                                                    </div>

                                                    <div class="clearfix"></div>
                                                    <br>

                                                    <div class="form-group" id="customTaxHolder" style="display: none;">
                                                        <label for="custom_tax_rate" class="col-sm-8 control-label">Custom Tax Rate</label>
                                                        <div class="col-sm-4">
                                                            <input max="90" type="number" name="custom_tax_rate" id="custom_tax_rate" class="form-control">
                                                        </div>
                                                    </div>

                                                    <div class="clearfix"></div>
                                                    <br>
                                                @endif
                                                
                                            @endforeach
                                        </div>
                                        <br><br><br>
                                        <p>Assign deductions to the employee</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="portlet light">
                            <div class="portlet-title">
                                <div class="caption font-red-sunglo">
                                    <i class="fa fa-briefcase font-red-sunglo"></i>
                                    <span class="caption-subject bold uppercase"> Other Details</span>
                                </div>
                            </div>
                            <div class="portlet-body form">
                                <div class="form-body">
                                    <div class="form-group form-md-line-input form-md-floating-label">
                                        <input type="text" id="mobile_phone" name="mobile_phone" class="form-control" value="{{ old('mobile_phone') }}" required>
                                        <label for="mobile_phone">Mobile Phone*</label>
                                        <span class="help-block">This is the mobile phone number of the employee</span>
                                    </div>
                                    <div class="form-group form-md-line-input form-md-floating-label">
                                        <input type="email" value="{{ old('email') }}" id="email" name="email" class="form-control" required>
                                        <label for="email">Email*</label>
                                        <span class="help-block">This is the personal email of the employee</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="portlet light">
                            <div class="portlet-title">
                                <div class="caption font-red-sunglo">
                                    <i class="fa fa-briefcase font-red-sunglo"></i>
                                    <span class="caption-subject bold uppercase"> Contract Details</span>
                                </div>
                            </div>
                            <div class="portlet-body form">
                                <div class="form-body">
                                    <div class="form-group form-md-line-input">
                                        <select class="form-control" id="department_id" name="department_id">
                                            @foreach($departments as $department)
                                                <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                                            @endforeach
                                        </select>
                                        <label for="department_id">Assigned Department*</label>
                                        <span class="help-block">This is the department that employee belongs to</span>
                                    </div>
                                    <div class="form-group form-md-line-input">
                                        <select class="form-control" id="employee_type_id" name="employee_type_id">
                                            @foreach($employee_type as $empType)
                                                <option value="{{ $empType->id }}" {{ old('employee_type_id') == $empType->id ? 'selected' : '' }}>{{ $empType->name }}</option>
                                            @endforeach
                                        </select>
                                        <label for="employee_type_id">Employee Type*</label>
                                        <span class="help-block">This is the category of employees that the employee belongs to</span>
                                    </div>
                                    <div class="form-group form-md-line-input form-md-floating-label">
                                        <label for="start_date">Contract Start Date*</label>
                                        <div class="input-group date date-picker margin-bottom-5" readonly data-date-format="dd-mm-yyyy">
                                            <input type="text" class="form-control form-filter input-sm" name="start_date" value="{{ old('start_date') }}" required>
											<span class="input-group-btn">
											    <button class="btn btn-sm default" type="button"><i class="fa fa-calendar"></i></button>
											</span>
                                        </div>
                                        <span>This is the date that the current employee contract is starting</span>
                                    </div>
                                    <div class="form-group form-md-line-input form-md-floating-label">
                                        <label for="end_date">Contract End Date*</label>
                                        <div class="input-group date date-picker margin-bottom-5" readonly data-date-format="dd-mm-yyyy">
                                            <input type="text" class="form-control form-filter input-sm" name="end_date"  value="{{ old('end_date') }}" required>
											<span class="input-group-btn">
											    <button class="btn btn-sm default" type="button"><i class="fa fa-calendar"></i></button>
											</span>
                                        </div>
                                        <span>This is the date that the current employee contract is ending</span>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="portlet light">
                            <div class="portlet-title">
                                <div class="caption font-red-sunglo">
                                    <i class="fa fa-briefcase font-red-sunglo"></i>
                                    <span class="caption-subject bold uppercase"> Payment Details</span>
                                </div>
                            </div>
                            <div class="portlet-body form">
                                <div class="form-body">
                                    <div class="form-group form-md-line-input">
                                        <select class="form-control" id="payment_method_id" name="payment_method_id">
                                            @foreach($payment_methods as $payMethod)
                                                <option value="{{ $payMethod->id }}" {{ old('payment_method_id') == $payMethod->id ? 'selected' : '' }}>{{ $payMethod->name }}</option>
                                            @endforeach
                                        </select>
                                        <label for="payment_method_id">Payment Method*</label>
                                        <p>These will be the mode of payment that the employee will use</p>
                                    </div>
                                    <div id="payMethodsHolder"></div>

                                    <div class="form-group form-md-line-input form-md-floating-label">
                                        <input type="submit" class="btn btn-primary" value="Save">
                                        <a class="btn btn-danger" href="{{ route('employees.index') }}">Back</a>
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
        $(function (){
            var payGrades = {!! Payroll\Models\PayGrade::with('paymentStructure')->get()->toJson() !!};
            var udfs = {!! Payroll\Models\UDF::where('udfable_type', Payroll\Models\PaymentMethod::class)->get(['udfable_id', 'field_name', 'field_title'])->toJson() !!};

            function getFields(element) {
                var selectedMethod = $(element).val();
                $('.udf-field').remove();
                $.each(udfs, function(i, v) {
                    if (v.udfable_id == selectedMethod) {
                        var field = '<div class="form-group form-md-line-input form-md-floating-label udf-field"><input type="text" class="form-control" id="'+ v.field_name +'" name="'+ v.field_name +'" value="{{ old('last_name') }}" required><label for="'+ v.field_name +'">'+ v.field_title +'*</label><span class="help-block">This is the required payment method field</span></div>';
                        $('#payMethodsHolder').append(field);
                    }
                });
            }

            $('#payment_method_id').on('change', function() {
                getFields(this);
            });

            $('#pay_grade_id').on('change', function() {
                gradeChange(this);
            });

            function gradeChange(element) {
                var selectedVal = $(element).val();
                var selectedGrade = '';
                $.each(payGrades, function(i, v) {
                    if (v.id == selectedVal) {
                        selectedGrade = v;
                        return;
                    }
                }.bind(selectedGrade));

                $('.make-switch').bootstrapSwitch('state', false);
                $('#current_basic_salary').val(selectedGrade.basic_salary);
                var allowancesString = selectedGrade.default_allowances;
                var deductionsString = selectedGrade.default_deductions;


                $('#payment_structure_id').html(selectedGrade.payment_structure.name);
                $('input[name="payment_structure_id"]').val(selectedGrade.payment_structure.id);
                $('#unit').html(selectedGrade.payment_structure.unit);

                if(allowancesString != null)
                {
                    var allowances = allowancesString.split(',');
                    $.each(allowances, function(i, v) {
                        $('#allowance' + v).bootstrapSwitch('state', true);
                        return;
                    });
                }

                if(deductionsString != null)
                {
                    var deductions = deductionsString.split(',');
                    $.each(deductions, function(i, v) {
                        $('#deduction' + v).bootstrapSwitch('state', true);
                        return;
                    });
                }
            }

            $('.make-switch').on('switchChange.bootstrapSwitch', function(event, state) {
                var inputElemId = $(this).attr('id');
                var insertElem = "";
                if (state) {
                    if (inputElemId.substring(0, 5) == 'allow') {
                        if ($('#' + inputElemId + '_type').val() == 'per_employee') {
                            insertElem = '<input type="text" name="' + inputElemId + '_amount" id="' + inputElemId + '_amount" class="form-control" style="width: 60%; margin: 10px; float: right;" placeholder="Allowance amount">';
                            $(this).closest('.form-group').append(insertElem);
                        }
                    } else if (inputElemId.substring(0, 5) == 'deduc') {
                        insertElem = '<input type="text" name="' + inputElemId + '_number" id="' + inputElemId + '_amount" class="form-control" style="width: 60%; margin: 10px; float: right;" placeholder="Deduction Account Number">';
                        if ($('#' + inputElemId + '_type').val() == 'per_employee') {
                            insertElem += '<br><input type="number" name="' + inputElemId + '_deduction_amount" id="' + inputElemId + '_deduction_amount" class="form-control" style="width: 60%; margin: 10px; float: right;" placeholder="Amount to be deducted">';
                        }
                        $(this).closest('.form-group').append(insertElem);
                    } else if (inputElemId == 'has_custom_tax_rate'){
                        $('#customTaxHolder').removeAttr('style');
                    }
                } else {
                    $('#' + inputElemId + "_amount").remove();
                    $('#' + inputElemId + "_deduction_amount").remove();

                    if (inputElemId == 'has_custom_tax_rate'){
                        $('#customTaxHolder').val('').attr('style', 'display:none');
                    }
                }

            });

            gradeChange($('#pay_grade_id'));
            getFields($('#payment_method_id'));

        });
    </script>
@endsection