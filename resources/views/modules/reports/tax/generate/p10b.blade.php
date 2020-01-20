<style>
    body {
        font-size: 0.7em;
        font-family: "helvetica", "Sans-Serif";
    }

    .s-table {
        width: 100%;
    }
    .s-table-header
    {
        background-color: #E5E5E5;
    }

    .s-table-header th {
        padding: 5px;
    }

    .s-table-summary
    {
        background-color: #E5E5E5;
    }

    #header {
        height: 40px;
    }

    .intro {
        font-size: 1.1em;
        line-height: 18px;
    }

    .text-right
    {
        text-align: right;
    }

    .text-left
    {
        text-align: left;
    }

    .s-container table {
        width: 100%;
    }

    .s-container td {
        border: none !important;
    }

    .page-break
    {
        page-break-after: always;
    }

    .centerHead {
        position:absolute;
        top: 0;
        left: 40.5%;
        width: 200px;
    }
    .s-text-center {
        text-align: center;
    }

    .s-name {
        position: absolute;
    }

    .s-text-right {
        text-align: right;
    }

    table.data , .data th, .data td {
        border: 1px solid black;
        border-collapse: collapse;
        padding: 0 5px;
    }

    .body
    {
        margin-top: 60px;
    }

    .page-heading h3 {
        line-height: 5px;
    }

    .totals {
        font-weight: 600;
    }

    .left {
        float: left;
    }

    .right {
        float: right;
    }
    .rotate .page2 {
        position: relative;
        overflow: visible;
    }
    .rotate .content{
        width: 65%; height: 60%;
        overflow: visible;
        -webkit-transform: rotate(270deg);
        -moz-transform: rotate(270deg);
        -ms-transform: rotate(270deg);
        -o-transform: rotate(270deg);
        transform: rotate(270deg);
        transform-origin: center center;
        position: absolute; left: -250px; top: -55%;
    }

</style>
<img src="{{ public_path('images/kra.jpg') }}" alt="KRA" class="centerHead">
<div class="body">
    <h2 class="s-name">P10B</h2>
    <div class="s-text-center page-heading">
        <h3>DOMESTIC TAXES DEPARTMENT</h3>
        <h3>FRINGE BENEFIT TAX FOR YEAR {{ \Carbon\Carbon::now()->year }}</h3>
    </div>
    <table class="s-table" id="header">
        <tr>
            <td colspan="2">
            </td>
            <td colspan="2">
                <div class="intro s-text-right">
                    <strong>EMPLOYER'S PIN: </strong>{{ strtoupper($company->kra_pin) }}
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="4">
                <div class="intro">
                    <strong>EMPLOYER'S NAME: </strong>{{ strtoupper($company->name) }}
                </div>
            </td>
        </tr>
    </table>

    <table class="s-table data">
        <tr>
            <th class="s-text-center">EMPLOYEE'S NAME</th>
            <th class="s-text-center">OPENING LOAN BALANCE AT 1<sup>st</sup> JANUARY<br>KSHS.</th>
            <th class="s-text-center">ADDITIONAL LOAN<br>Kshs.</th>
            <th class="s-text-center">DATE LOAN ADVANCED<br>Kshs.</th>
            <th class="s-text-center">EMPLOYER'S INTEREST RATE<br>%</th>
            <th class="s-text-center">LOAN BALANCE AT 31<sup>st</sup> DEC.<br>Kshs.</th>
            <th class="s-text-center">TAXABLE FRINGE BENEFIT FOR YEAR<br>Kshs.</th>
            <th class="s-text-center">FRINGE BENEFIT TAX PAID<br>Kshs.</th>
        </tr>
    @foreach($employees as $employee)
    <?php
        $payrolls = $p9s->whereLoose('employee_id', $employee->id);
        if (count($payrolls) < 1) {
            continue;
        }

        $opening = 0;
        $additional = 0;
        $dateAdvanced = null;
        $employersRate = 0;
        $balance = 0;
        $fringePayable = 0;
        $fringePaid = 0;
        $continue = false;
        foreach ($payrolls as $payroll) {
            if (strlen($payroll->non_cash) < 3) {
                $continue = true;
                continue;
            }
            $kraNonTax = json_decode($payroll->non_cash);
            foreach($kraNonTax as $non) {
                if ($non->name != "Low Interest Rate Benefit") {
                    $continue = true;
                    continue;
                }
                $continue = false;
                $loanDetails = $non->details;
                $dateProcessed = \Carbon\Carbon::parse($loanDetails->date_processed);
                $balance += $loanDetails->balance;
                $fringePayable += $loanDetails->low_benefit;
                $fringePaid += $loanDetails->fringe_benefit;
                $employersRate = $loanDetails->rate;
                if ($dateProcessed < $today->startOfYear()) {
                    $period = $today->startOfYear()->diffInMonths($dateProcessed);
                    $opening = $period * $loanDetails->installments;
                    continue;
                }
                $additional += $loanDetails->amount;
                $dateAdvanced = $dateProcessed;
            }
        }

        if ($continue) {
            continue;
        }
        $employeesName = $employee->first_name . ' ' . $employee->middle_name . ' ' . $employee->last_name;
    ?>
        <tr>
            <td>{{ $employeesName }}</td>
            <td class="text-right">{{ number_format($opening, 2) }}</td>
            <td class="text-right">{{ number_format($additional, 2) }}</td>
            <td class="text-right">{{ is_null($dateAdvanced) ? '' : $dateAdvanced->format('d/m/Y') }}</td>
            <td class="text-right">{{ $employersRate }}</td>
            <td class="text-right">{{ number_format($balance, 2) }}</td>
            <td class="text-right">{{ number_format($fringePayable, 2) }}</td>
            <td class="text-right">{{ number_format($fringePaid, 2) }}</td>
        </tr>
    @endforeach
    </table>
</div>
<span class="page-break"></span>