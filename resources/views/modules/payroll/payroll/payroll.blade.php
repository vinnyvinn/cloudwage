<style>
    body {
        font-size: 0.8em;
        font-family: "helvetica", "Sans-Serif";
    }

    .s-table {
        line-height: 15px;
        width: 100%;

    }
    .s-table td{
        text-align: left;
        padding-left: 20px;

    }
    .sep{
        text-align: right;
        padding-right: 20px !important;
    }
    .s-table-header
    {
        background-color: #E5E5E5;
    }

    .s-table-header th {
        padding: 5px;
        text-align: center;
    }

    td {
        padding: 5px;
        padding-left: 10px;
        border-bottom: solid #E5E5E5 1px;
        max-width: 100%;
    }
    .s-table-summary
    {
        background-color: #E5E5E5;
    }

    #header {
        height: 40px;
    }

    #intro {
        font-size: 1.2em;
        line-height: 18px;
    }

    .text-right
    {
        text-align: right !important;
        padding-right: 15px !important;
    }

    .text-left
    {
        text-align: left;
    }

    .s-container {
        position:absolute;
        top: 0;
        left: 0;
        opacity:0.5;
        z-index:-10;
        width: 100%;
    }

    .s-container table {
        width: 100%;
    }

    .s-container td {
        border: none !important;
    }


    .bg-water {
        margin-top:50%;
        -webkit-transform: rotate(320deg);
        -moz-transform: rotate(320deg);
        -ms-transform: rotate(320deg);
        -o-transform: rotate(320deg);
        transform: rotate(320deg);
        font-size: 2em;
        text-align: center;
        opacity: 0.1;
        width: 120%;
    }

    .page-break
    {
        page-break-after: always;
    }
</style>
<div class="s-container">
    <table>
        <tr>
            <td>
                <div class="bg-water">{{ $company->name }}</div>
            </td>
            <td>
                <div class="bg-water">{{ $company->name }}</div>
            </td>
        </tr>
    </table>

</div>
<?php $deds = $payroll->employee->deductions;
foreach($deds as $ded){
    if($ded->deduction_id == 1){
        $pin = $ded->deduction_number;
    }
}
?>
<table class="s-table" id="header">
    <tr>
        <td colspan="2">
            <div id="intro">
                <img alt="" style="border-radius: 50%; height: 80px;" src="{{ asset($company->logo) }}"/><br>
                <strong>Payslip</strong><br>
                <strong>{{ $company->name }} | {{ $company->city }}</strong>
            </div>
        </td>
        <td colspan="2">
            <div id="intro">
                <img alt="" style="border-radius: 50%; height: 100px;" src="{{ asset($company->logo) }}"/><br>
                <strong>Payslip</strong><br>
                <strong>{{ $company->name }} | {{ $company->city }}</strong>
            </div>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <div class="text-left">
                 <strong>Full Name:</strong> {{ $payroll->employee->first_name }} {{ $payroll->employee->middle_name }} {{ $payroll->employee->last_name }}<br>
                 <strong>Payroll No:</strong> {{ $payroll->employee->payroll_number }}<br>
                <strong>{{ $payroll->employee->identification_type }}:</strong> {{ $payroll->employee->identification_number }}<br>
                <strong> PIN NO :</strong> {{ $pin }}<br>
                
               
                <strong>Date:</strong> {{ $payroll->payroll_date->startOfMonth()->toFormattedDateString() .' - '. $payroll->payroll_date->toFormattedDateString() }}<br>
            </div>
        </td>
        <td colspan="2">
            <div class="text-left">
                  <strong>Full Name:</strong> {{ $payroll->employee->first_name }} {{ $payroll->employee->middle_name }} {{ $payroll->employee->last_name }}<br>
                  <strong>Payroll No:</strong> {{ $payroll->employee->payroll_number }}<br>
                <strong>{{ $payroll->employee->identification_type }}:</strong> {{ $payroll->employee->identification_number }}<br>
                <strong> PIN NO :</strong> {{ $pin }}<br>
                
              
                <strong>Date:</strong> {{ $payroll->payroll_date->startOfMonth()->toFormattedDateString() .' - '. $payroll->payroll_date->toFormattedDateString() }}<br>
            </div>
        </td>
    </tr>
</table>
<?php
    $totalDeductions = 0;
    $totalBenefits = 0;
    $totalAllowances = $payroll->basic_pay;
    $allowances = json_decode($payroll->allowances);
    $deductions = json_decode($payroll->deductions);
    $untaxedAllowances = array();
    $overtime = json_decode($payroll->overtime);
    $dud = 0;
?>

<table class="s-table">
    <tr class="s-table-header">
        <th colspan="2" class="text-left">Earnings</th>
        <th colspan="2" class="text-left">Earnings</th>
    </tr>
    <tr class="s-table-header">
        <th>Name</th>
        <th>Amount</th>
        <th>Name</th>
        <th>Amount</th>
    </tr>
    <tr>

        <td>Basic Pay
            @if($payroll->employee->payment_structure_id != 1)
             ({{ $payroll->for_rate }})
                @else
                (Monthly)
            @endif
        </td>
        <td class="text-right">{{ number_format($payroll->basic_pay, 2) }}</td>
        <td>Basic Pay
            @if($payroll->employee->payment_structure_id != 1)
                ({{ $payroll->for_rate }})
            @else
            (Monthly)
            @endif
        </td>
        <td class="text-right">{{ number_format($payroll->basic_pay, 2) }}</td>
    </tr>

@foreach($allowances as $allowance)
    @if($allowance->amount > 0 && $allowance->non_cash == 0)
        <?php $untaxedAllowances [] = $allowance; ?>
    @endif
    @if ($allowance->taxable)
        <?php
//                $dud = 0;
            $totalAllowances += $allowance->tax_amount;
//            $allowance->name == 'Car Benefit' : $dud += $allowance->tax_anount : '';
//        if($allowance->name == 'car benefit'){
//            $dud = $allowance->tax_amount;
//        }else{
//            $dud = 0;
//        }
        ?>
        <tr>
            <td>{{ $allowance->name }} {{ $allowance->name == 'Car Benefit' ? ($dud = $allowance->tax_amount) : '' }} {{ $allowance->amount > 0 ? 'Tax' : '' }}</td>
            <td class="text-right">{{ number_format($allowance->tax_amount, 2) }}</td>
            <td>{{ $allowance->name }} {{ $allowance->amount > 0 ? 'Tax' : '' }}</td>
            <td class="text-right">{{ number_format($allowance->tax_amount, 2) }}</td>
        </tr>
    @endif

@endforeach
    @if($overtime != null)
        @if($overtime->ot_1_amount > 0)
            <?php $totalAllowances += $overtime->ot_1_amount; ?>
            <tr>
            <td>OT 1.5 ( {{ $overtime->ot_1_hrs}} hours)</td>
            <td class="text-right">{{ number_format($overtime->ot_1_amount, 2) }}</td>
            <td>OT 1.5 ( {{ $overtime->ot_1_hrs}} hours)</td>
            <td class="text-right">{{ number_format($overtime->ot_1_amount, 2) }}</td>
            </tr>
        @endif
            @if($overtime->ot_2_amount > 0)
                <?php $totalAllowances += $overtime->ot_2_amount; ?>
                <tr>
                    <td>OT 2.0 ( {{ $overtime->ot_2_hrs}} hours)</td>
                    <td class="text-right">{{ number_format($overtime->ot_2_amount, 2) }}</td>
                    <td>OT 2.0  ({{ $overtime->ot_2_hrs}} hours)</td>
                    <td class="text-right">{{ number_format($overtime->ot_2_amount, 2) }}</td>
                </tr>
            @endif

    @endif
    <tr class="s-table-summary">
        <td><strong>Gross Pay</strong></td>
        <td class="text-right"><strong>{{ number_format($totalAllowances, 2) }}</strong></td>
        <td><strong>Gross Pay</strong></td>
        <td class="text-right"><strong>{{ number_format($totalAllowances, 2) }}</strong></td>
    </tr>

    <tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
    <tr class="s-table-header">
        <th colspan="2" class="text-left">Deductions</th>
        <th colspan="2" class="text-left">Deductions</th>
    </tr>
    @foreach(json_decode($payroll->deductions) as $deduction)
        @if(is_object($deduction->amount))
            <?php $totalDeductions += ($deduction->amount->amount - $deduction->amount->relief->amount); ?>
            <tr>
                <td>{{ $deduction->name }}</td>
                <td class="text-right">{{ number_format($deduction->amount->amount, 2) }}</td>
                <td>{{ $deduction->name }}</td>
                <td class="text-right">{{ number_format($deduction->amount->amount, 2) }}</td>
            </tr>
            <tr>
                <td>{{ $deduction->amount->relief->name }}</td>
                <td class="text-right">({{ number_format($deduction->amount->relief->amount, 2) }})</td>
                <td>{{ $deduction->amount->relief->name }}</td>
                <td class="text-right">({{ number_format($deduction->amount->relief->amount, 2) }})</td>
            </tr>
            @if($deduction->name == 'PAYE' && $deduction->amount->relief->amount != null)
                <tr class="s-table-header">
                    <td> PAYE DUE</td>
                    <td class="text-right"> {{ number_format(($deduction->amount->amount - $deduction->amount->relief->amount), 2) }}</td>
                    <td> PAYE DUE</td>
                    <td class="text-right"> {{ number_format(($deduction->amount->amount - $deduction->amount->relief->amount), 2) }}</td>
                </tr>
                @endif
        @else
            <?php $totalDeductions += $deduction->amount; ?>
            <tr>
                <td>{{ $deduction->name }}</td>
                <td class="text-right">{{ number_format($deduction->amount, 2) }}</td>
                <td>{{ $deduction->name }}</td>
                <td class="text-right">{{ number_format($deduction->amount, 2) }}</td>
            </tr>
        @if ($deduction->name == "NSSF")
            <tr class="s-table-header">
                <td><strong>Taxable Pay</strong></td>
                <td class="text-right"><strong>{{ number_format($totalAllowances - $totalDeductions, 2) }}</strong></td>
                <td><strong>Taxable Pay</strong></td>
                <td class="text-right"><strong>{{ number_format($totalAllowances - $totalDeductions, 2) }}</strong></td>
            </tr>
        @endif
        @endif
    @endforeach
    <tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
    @foreach(json_decode($payroll->advances) as $advance)
        <?php $totalDeductions += $advance->amount; ?>
        <tr>
            <td>{{ $advance->name }}</td>
            <td class="text-right">{{ number_format($advance->amount, 2) }}</td>
            <td>{{ $advance->name }}</td>
            <td class="text-right">{{ number_format($advance->amount, 2) }}</td>
        </tr>
    @endforeach
    @foreach(json_decode($payroll->loans) as $loan)
        <?php $totalDeductions += $loan->amount; ?>
        <tr>
            <td> Loan balance -
                @if(($loan->balance - $loan->amount ) > 0)
                {{ $loan->balance - $loan->amount }}
                @endif
            </td>
            <td class="text-right">{{ number_format($loan->amount, 2) }}</td>
            <td>Loan balance -
                @if(($loan->balance - $loan->amount ) > 0)
                    {{ $loan->balance - $loan->amount }}
                @endif</td>
            <td class="text-right">{{ number_format($loan->amount, 2) }}</td>
        </tr>
    @endforeach
    <tr class="s-table-summary">
        <td><strong>Total Deductions</strong></td>
        <td class="text-right"><strong>{{ number_format($totalDeductions, 2) }}</strong></td>
        <td><strong>Total Deductions</strong></td>
        <td class="text-right"><strong>{{ number_format($totalDeductions, 2) }}</strong></td>
    </tr>

    <tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
    <tr class="s-table-header">
        <th colspan="2" class="text-left">Other Benefits</th>
        <th colspan="2" class="text-left">Other Benefits</th>
    </tr>
    @foreach($untaxedAllowances as $allowance)
        <?php $totalBenefits += $allowance->amount; ?>
        <tr>
            <td>{{ $allowance->name }}</td>
            <td class="text-right">{{ number_format($allowance->amount, 2) }}</td>
            <td>{{ $allowance->name }}</td>
            <td class="text-right">{{ number_format($allowance->amount, 2) }}</td>
        </tr>
    @endforeach
    <tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
    <tr class="s-table-header">
        <th colspan="2" class="text-left">Summary</th>
        <th colspan="2" class="text-left">Summary</th>
    </tr>
    <tr>
        <td><strong>Gross Pay</strong></td>
        <td class="text-right">{{ number_format($totalAllowances, 2) }}</td>
        <td><strong>Gross Pay</strong></td>
        <td class="text-right">{{ number_format($totalAllowances, 2) }}</td>
    </tr>
    <tr>
        <td><strong>Deductions</strong></td>
        <td class="text-right">({{ number_format($totalDeductions, 2) }})</td>
        <td><strong>Deductions</strong></td>
        <td class="text-right">({{ number_format($totalDeductions, 2) }})</td>
    </tr>
    <tr>
        <td><strong>Other Benefits</strong></td>
        <td class="text-right">{{ number_format($totalBenefits, 2) }}</td>
        <td><strong>Other Benefits</strong></td>
        <td class="text-right">{{ number_format($totalBenefits, 2) }}</td>
    </tr>
    <tr class="s-table-summary">
        <td><strong>Net Pay</strong></td>
        <td class="text-right"><strong>{{ number_format(((ceil($totalAllowances - $totalDeductions) + $totalBenefits) - $dud) , 2) }}</strong></td>
        <td><strong>Net Pay</strong></td>
        <td class="text-right"><strong>{{ number_format(((ceil($totalAllowances - $totalDeductions) + $totalBenefits) - $dud) , 2) }}</strong></td>
    </tr>
    <tr>
        <td colspan="4"></td>
    </tr>
    <tr>
        <td colspan="2"><strong>Receiver's Signature</strong></td>
        <td colspan="2"><strong>Receiver's Signature</strong></td>
    </tr>
</table>
<span class="page-break"></span>
