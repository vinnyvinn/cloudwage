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
            <a href="#">Process Loan</a>
        </li>
    </ul>

    <div class="row">
        <div class="col-sm-12">
            <form action="{{ route('loans.store') }}" method="post" role="form">
                {{ csrf_field() }}
                <div class="portlet light">
                    <div class="portlet-title">
                        <div class="caption font-red-sunglo">
                            <a href="{{ route('loans.index') }}" class="btn btn-transparent grey-salsa btn-circle btn-sm active"><i class="fa fa-angle-left"></i> back</a>
                            <span class="caption-subject bold uppercase"> Loan Details</span>
                        </div>
                    </div>
                    <div class="portlet-body form">
                        <div class="form-body">
                            <div class="form-group form-md-line-input form-md-floating-label">
                                <select class="form-control select2" id="employee_id" name="employee_id">
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>{{ $employee->payroll_number . ' - ' . $employee->first_name . ' ' . $employee->last_name }}</option>
                                    @endforeach
                                </select>
                                <label for="employee_id">Employee*</label>
                                <span class="help-block">This is the employee that is being given the advance</span>
                            </div>
                            <div class="form-group form-md-line-input form-md-floating-label">
                                <input type="text" class="form-control" id="amount" name="amount" min="0" value="{{ old('amount') }}" required>
                                <label for="amount">Amount*</label>
                                <span class="help-block">This is the amount of the loan</span>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group form-md-line-input form-md-floating-label">
                                        <label for="min_amount">Month of issue*</label>
                                        <div class="input-group date date-picker margin-bottom-5" readonly>
                                            <input type="text" class="form-control form-filter input-sm" value="{{ old('for_month') }}" name="for_month" id="for_month" required>
                                            <span class="input-group-btn">
                                        <button class="btn btn-sm default" type="button"><i class="fa fa-calendar"></i></button>
                                    </span>
                                        </div>
                                        <span class="help-block">This is the month in which the loan was issued</span>
                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group form-md-line-input form-md-floating-label">
                                        <input type="number" class="form-control" id="rate" name="rate" min="0" value="{{ old('rate') }}" required>
                                        <label for="rate">Interest Rate (remove the % sign)*</label>
                                        <span class="help-block">This is the rate to which the loan is given of the loan</span>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group form-md-line-input form-md-floating-label">
                                        <select class="form-control" id="type" name="type">
                                            <option value="{{ \Payroll\Parsers\LoanCalculator::COMPOUND }}" {{ old('type') == \Payroll\Parsers\LoanCalculator::COMPOUND ? 'selected' : '' }}>Compound Interest</option>
                                            <option value="{{ \Payroll\Parsers\LoanCalculator::SIMPLE }}" {{ old('type') == \Payroll\Parsers\LoanCalculator::SIMPLE ? 'selected' : '' }}>Simple Interest</option>
                                        </select>
                                        <label for="type">Interest Type*</label>
                                        <span class="help-block">This is the mode of interest calculation</span>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group form-md-line-input form-md-floating-label">
                                        <select class="form-control" id="iterations" name="iterations">
                                            <option value="{{ \Payroll\Parsers\LoanCalculator::PER_MONTH }}" {{ old('type') == \Payroll\Parsers\LoanCalculator::PER_MONTH ? 'selected' : '' }}>Per Month</option>
                                            <option value="{{ \Payroll\Parsers\LoanCalculator::PER_YEAR }}" {{ old('type') == \Payroll\Parsers\LoanCalculator::PER_YEAR ? 'selected' : '' }}>Per Year</option>
                                        </select>
                                        <label for="iterations">Interest Type*</label>
                                        <span class="help-block">This is the mode of interest calculation</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group form-md-line-input form-md-floating-label">
                                <input type="number" class="form-control" id="duration" name="duration" min="0" step="0.000001" value="{{ old('duration') }}" required>
                                <label for="duration">Period in Months*</label>
                                <span class="help-block">This is the number of months that the loan is for</span>
                            </div>
                            <div class="form-group form-md-line-input form-md-floating-label">
                                <label for="installments">Amount Per Installment*</label>
                                <div class="form-control" id="installments"></div>
                                <span>This is the amount to be paid per month from the salary</span>
                            </div>
                            <div class="form-group form-md-line-input form-md-floating-label">
                                <label for="amount_payable">Total Amount Payable*</label>
                                <div class="form-control" id="amount_payable"></div>
                                <span>This is the amount to be paid back</span>
                            </div>
                            {{--<div class="form-group form-md-line-input form-md-floating-label">--}}
                                {{--<label for="low_benefit">Low Interest Rate Benefit Payable Per Month*</label>--}}
                                {{--<div class="form-control" id="low_benefit"></div>--}}
                                {{--<span>This is the amount to be added to the basic salary as tax to be paid by employee depending on the current Loan Prescribed Interest Rate</span>--}}
                            {{--</div>--}}
                            <div class="form-group form-md-line-input form-md-floating-label">
                                <label for="fringe_benefit">Fringe Benefit Tax Payable Per Month*</label>
                                <div class="form-control" id="fringe_benefit"></div>
                                <span>This is the of tax to be paid by employer depending on the current Loan Prescribed Interest Rate</span>
                            </div>

                            <div class="form-group form-md-line-input form-md-floating-label">
                                <input type="submit" class="btn btn-primary" value="Save">
                                <a class="btn btn-danger" href="{{ URL::previous() }}">Back</a>
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
        $(".date-picker").datepicker( {
            format: "mm-yyyy",
            viewMode: "months",
            minViewMode: "months"

        });
        var $interest = $('#rate');
        var $iterations = $('#iterations');
        var $amount = $('#amount');
        var $type = $('#type');
        var $duration = $('#duration');
        var $amountPayable = $('#amount_payable');
        var $installments = $('#installments');
        var $payable = 0;


        $amount.on('keyup', function() {
            var $val = $amount.val();
            if ($val.length > 0) {
                var $amt = Number(parseFloat($amount.val().replace(/,/g, ''))).toLocaleString('en');
                $amount.val($amt);
                getLoanDetails();
            }
        });
        $duration.on('keyup', function() {
            getLoanDetails();
        });
        $iterations.on('change', function() {
            getLoanDetails();
        });
        $duration.on('change', function() {
            getLoanDetails();
        });
        $type.on('change', function() {
            getLoanDetails();
        });

        function getRate()
        {
            var $rate = $interest.val();
            if ($iterations.val() == '{{ \Payroll\Parsers\LoanCalculator::PER_MONTH }}') {
                $rate = $rate * 12;
            }

            return $rate / 100;
        }

        function getFringeBenefit()
        {
            var $currentRate = '{{ \Payroll\Parsers\LoanCalculator::getPrescribedRate() }}';
            var $fringe = 0;
            if ($currentRate > getRate()) {
                var $taxRate = $currentRate - getRate();
                var $interest = parseFloat($amount.val().replace(/,/g, '')) * $taxRate;
                var $lowBenefit = $interest / $duration.val();
                $fringe = ($interest * 0.3) / $duration.val();
            }

            $lowBenefit = 0;

            $('#low_benefit').html($lowBenefit.toFixed(2));
            $('#fringe_benefit').html($fringe.toFixed(2));
        }

        function calculateCompound() {
            var $monthlyRate = getRate()/12;
            var $durationInMonths = parseInt($duration.val());
            var principal = parseFloat($amount.val().replace(/,/g, ''));

            var part1 = Math.pow(1 + $monthlyRate, -$durationInMonths);
            var amount = (($monthlyRate * principal) / (1 - part1)) * $durationInMonths;

            return isNaN(amount) ? 0 : amount;
        }

        function getLoanDetails()
        {
            switch ($type.val()) {
                case '{{ \Payroll\Parsers\LoanCalculator::COMPOUND }}':
                    $payable = calculateCompound();
                    break;
                case '{{ \Payroll\Parsers\LoanCalculator::SIMPLE }}':
                default:
                    $payable = parseFloat(parseFloat($amount.val().replace(/,/g, '')) * ((getRate()  * ($duration.val() / 12)) + 1));
                    break;
            }
            var $dur = $duration.val() == '' ? 1 : $duration.val();
            var $inst = $payable / parseFloat($dur);
            $installments.html(parseFloat($inst.toFixed(2)).toLocaleString('en'));
            $amountPayable.html(parseFloat($payable.toFixed(2)).toLocaleString('en'));

            getFringeBenefit();
        }

        function getInterval()
        {
            if ($iterations.val() == '{{ \Payroll\Parsers\LoanCalculator::PER_MONTH }}') {
                return 12;
            }

            return 1;
        }
    </script>
@endsection