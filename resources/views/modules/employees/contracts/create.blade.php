@extends('layout')
@section('content')
    <div class="page-head">
        <div class="page-title">
            <h1>Employee Contracts - <small> Create a new employee contract</small></h1>
        </div>
    </div>
    <ul class="page-breadcrumb breadcrumb">
        <li>
            <a href="{{ url('/') }}">Home</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="{{ route('contracts.index') }}">Employee Contracts</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="#">Create</a>
        </li>
    </ul>
    <div class="row">
        <div class="col-sm-12">
            <form action="{{ route('contracts.store') }}" method="post" role="form">
                {{ csrf_field() }}
                <div class="row">
                    <div class="col-sm-12">
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
                                        <select class="form-control select2" id="employee_id" name="employee_id">
                                            @foreach($employees as $employee)
                                                <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>{{ $employee->payroll_number }} - {{ $employee->first_name }} {{ $employee->last_name }}</option>
                                            @endforeach
                                        </select>
                                        <label for="employee_id">Employee*</label>
                                        <p>Select the employee whom the contract is for</p>
                                    </div>
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
                            <div class="form-group form-md-line-input form-md-floating-label">
                                <input type="submit" class="btn btn-primary" value="Save">
                                <a class="btn btn-danger" href="{{ route('contracts.index') }}">Back</a>
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
                        insertElem = '<input type="text" name="' + inputElemId + '_amount" id="' + inputElemId + '_amount" class="form-control" style="width: 60%; margin: 10px; float: right;" placeholder="Allowance amount">';
                        $(this).closest('.form-group').append(insertElem);
                    } else {
                        insertElem = '<input type="text" name="' + inputElemId + '_number" id="' + inputElemId + '_amount" class="form-control" style="width: 60%; margin: 10px; float: right;" placeholder="Deduction Account Number">';
                        console.log($('#' + inputElemId + '_type').val());
                        if ($('#' + inputElemId + '_type').val() == 'per_employee') {
                            insertElem += '<br><input type="text" name="' + inputElemId + '_deduction_amount" id="' + inputElemId + '_deduction_amount" class="form-control" style="width: 60%; margin: 10px; float: right;" placeholder="Amount to be deducted">';
                        }
                        $(this).closest('.form-group').append(insertElem);
                    }
                } else {
                    $('#' + inputElemId + "_amount").remove();
                    $('#' + inputElemId + "_deduction_amount").remove();
                }

            });

            gradeChange($('#pay_grade_id'));
            getFields($('#payment_method_id'));

        });
    </script>
@endsection

