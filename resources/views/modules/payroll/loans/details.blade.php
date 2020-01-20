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
            <a href="#">Loan Details</a>
        </li>
    </ul>

    <div class="row">
        <div class="col-sm-12">
            <div class="portlet light">
                    <div class="portlet-title">
                        <div class="caption font-red-sunglo">
                            <a href="{{ route('loans.index') }}" class="btn btn-transparent grey-salsa btn-circle btn-sm active"><i class="fa fa-angle-left"></i> back</a>
                            <span class="caption-subject bold uppercase"> Loan Details</span>
                        </div>
                    </div>
                    <div class="portlet-body form">
                        <div class="form-body">
                            <div class="form-group form-md-line-input">
                                <div class="form-control" id="employee_id">{{ $loan->employee->payroll_number . ' - ' . $loan->employee->first_name . ' ' . $loan->employee->last_name }}</div>
                                <label for="employee_id">Employee*</label>
                                <span class="help-block">This is the employee that is being given the advance</span>
                            </div>
                            <div class="form-group form-md-line-input">
                                <div class="form-control" id="amount">{{ number_format($loan->amount, 2) }}</div>
                                <label for="amount">Amount*</label>
                                <span class="help-block">This is the amount of the loan</span>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group form-md-line-input">
                                        <div class="form-control" id="rate">{{ $loan->rate * 100 }}%</div>
                                        <label for="rate">Interest Rate</label>
                                        <span class="help-block">This is the rate to which the loan is given of the loan</span>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group form-md-line-input">
                                        <div class="form-control" id="type">{{ $loan->type == 'simple' ? 'Simple Interest' : 'Compound Interest' }}</div>
                                        <label for="type">Interest Type*</label>
                                        <span class="help-block">This is the mode of interest calculation</span>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group form-md-line-input">
                                        <div class="form-control" id="iterations">Per Year</div>
                                        <label for="iterations">Interest Type*</label>
                                        <span class="help-block">This is the mode of interest calculation</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group form-md-line-input">
                                <div class="form-control" id="duration">{{ $loan->duration }} Months / {{ round($loan->duration / 12, 2) }} Years</div>
                                <label for="duration">Period</label>
                                <span class="help-block">This is the number of months that the loan is for</span>
                            </div>
                            <div class="form-group form-md-line-input">
                                <label for="installments">Amount Per Installment*</label>
                                <div class="form-control" id="installments">{{ number_format($loan->installments, 2) }}</div>
                                <span>This is the amount to be paid per month from the salary</span>
                            </div>
                            <div class="form-group form-md-line-input">
                                <label for="amount_payable">Total Amount Payable*</label>
                                <div class="form-control" id="amount_payable">{{ number_format($loan->amount_payable, 2) }}</div>
                                <span>This is the amount to be paid back</span>
                            </div>
                            {{--<div class="form-group form-md-line-input">--}}
                                {{--<label for="low_benefit">Low Interest Rate Benefit Payable Per Month*</label>--}}
                                {{--<div class="form-control" id="low_benefit">{{ number_format($loan->low_benefit, 2) }}</div>--}}
                                {{--<span>This is the amount to be added to the basic salary as tax to be paid by employee depending on the current Loan Prescribed Interest Rate</span>--}}
                            {{--</div>--}}
                            <div class="form-group form-md-line-input">
                                <label for="fringe_benefit">Fringe Benefit Tax Payable Per Month*</label>
                                <div class="form-control" id="fringe_benefit">{{ number_format($loan->fringe_benefit, 2) }}</div>
                                <span>This is the of tax to be paid by employer depending on the current Loan Prescribed Interest Rate</span>
                            </div>

                            <div class="form-group form-md-line-input">
                                <a href="{{ route('loans.edit', $loan->id) }}" class="btn btn-info">Edit</a>
                                <a href="{{ route('loans.destroy', $loan->id) }}" class="btn btn-danger" data-method="delete" rel="nofollow" data-confirm="Are you sure you want to delete this?" data-token="{{ csrf_token() }}">Delete</a>
                                <a class="btn btn-warning" href="{{ URL::previous() }}">Back</a>
                            </div>
                        </div>
                    </div>
        </div>
        </div>
    </div>
@endsection
@section('footer')
    <script>
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

            $('#low_benefit').html($lowBenefit.toFixed(2));
            $('#fringe_benefit').html($fringe.toFixed(2));
        }

        function getLoanDetails()
        {
            switch ($type.val()) {
                case '{{ \Payroll\Parsers\LoanCalculator::COMPOUND }}':
                    var $rate = getRate();
                    var $interval = getInterval();
                    $payable = parseFloat(parseFloat($amount.val().replace(/,/g, '')) * Math.pow((1 + ($rate / $interval)), ($interval * ($duration.val() / 12))));
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