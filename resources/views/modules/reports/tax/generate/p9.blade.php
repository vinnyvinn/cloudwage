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
<img src="http://ssdhillon.cloudwage.com/images/kra.jpg" alt="KRA" class="centerHead">
<div class="body">
    <h2 class="s-name">P9A</h2>
    <div class="s-text-center page-heading">
        <h3>DOMESTIC TAXES DEPARTMENT</h3>
        <h3>TAX DEDUCTION CARD YEAR {{ $payrolls->first()->for_month->year }}</h3>
    </div>
    <table class="s-table" id="header" style="margin-top: -20px">
        <tr>
            <td colspan="2">
                <div class="intro">
                    <strong>Employers Name: </strong>{{ $company->name }}
                </div>
            </td>
            <td colspan="2">
                <div class="intro s-text-right">
                    <strong>Employer's Pin: </strong>{{ $company->kra_pin }}
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="4">
                <div class="intro">
                    <strong>Employee's Main Name: </strong>{{ $employee->first_name }}
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <div class="intro">
                    <strong>Employee's Other Names: </strong>{{ $employee->middle_name . ' ' . $employee->last_name }}
                </div>
            </td>
            <td colspan="2">
                <div class="intro s-text-right">
                    <?php  $key = \Payroll\Models\Deduction::PAYE; ?>
                    <strong>Employee's Pin: </strong>{{ $employee->deductions->whereLoose('deduction_id', "$key")->first()->deduction_number }}
                </div>
            </td>
        </tr>
    </table>
    <table class="s-table data">
        <tr>
            <th class="s-text-center">Months</th>
            <th class="s-text-center">Basic Salary<br>Kshs.</th>
            <th class="s-text-center">Benefits – NonCash<br>Kshs.</th>
            <th class="s-text-center">Value of Quarters<br>Kshs.</th>
            <th class="s-text-center">Total Gross Pay<br>Kshs.</th>
            <th class="s-text-center" colspan="3">Defined Contribution Retirement Scheme<br>Kshs.</th>
            <th class="s-text-center">Owner-Occupied Interest<br>Kshs.</th>
            <th class="s-text-center">Retirement Contribution & Owner-Occupied Interest<br>Kshs.</th>
            <th class="s-text-center">Chargable Pay<br>Kshs.</th>
            <th class="s-text-center">Tax Charged<br>Kshs.</th>
            <th class="s-text-center">Personal Relief + Insurance Relief<br>Kshs.</th>
            <th class="s-text-center">PAYE Tax (J-K)<br>Kshs.</th>
        </tr>
        <tr>
            <td class="s-text-center" rowspan="2"></td>
            <td class="s-text-center" rowspan="2">A</td>
            <td class="s-text-center" rowspan="2">B</td>
            <td class="s-text-center" rowspan="2">C</td>
            <td class="s-text-center" rowspan="2">D</td>
            <td class="s-text-center" colspan="3">E</td>
            <td class="s-text-center" rowspan="2">F <br>Amount of Interest</td>
            <td class="s-text-center" rowspan="2">G <br>The Lowest of E added to F</td>
            <td class="s-text-center">H</td>
            <td class="s-text-center">J</td>
            <td class="s-text-center">K</td>
            <td class="s-text-center">L</td>
        </tr>
        <tr>
            <td class="s-text-center">E1 <br>30% of A</td>
            <td class="s-text-center">E2 <br>Actual</td>
            <td class="s-text-center">E3 <br>Fixed</td>
            <td class="s-text-center"></td>
            <td class="s-text-center"></td>
            <td class="s-text-center">Total  <br>Kshs.</td>
            <td class="s-text-center"></td>
        </tr>


    <?php
        $totalBasic = 0; $totalBenefits = 0; $totalQuarters = 0; $totalGross = 0; $totalE1 = 0;
        $totalE2 = 0; $totalE3 = 0; $totalG = 0;  $totalH = 0; $totalJ = 0; $totalK = 0; $totalL = 0; $currentMonth = 1;
    ?>


        @foreach($payrolls as $payroll)
        <?php
        //var_dump($payroll);
        //die();
        //{{ $calendar_months[$currentMonth] }}
        ?>
            @while($currentMonth != $payroll->for_month->month || $currentMonth > 12)
                <tr>
                    <td>{{ $currentMonth }} </td>
                    <td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                    <td></td><td></td><td></td><td></td><td></td><td></td>
                </tr>
                <?php $currentMonth++; ?>
            @endwhile
            <?php
            if($payroll->non_cash != null){
                $kraNonTax = json_decode($payroll->non_cash);
            }else{
                $kraNonTax = 0;
            }
            
                $housing = 0;
                $totalLoan = 0;
                $loan = null;
                $nonCash = 0;
                $prescribed_rate = $payroll->prescribed_rate;
                foreach($kraNonTax as $non) {
                    if (strpos($non->name, 'ous') !== false) {
                        $housing = $non->amount;
                    }
                    if ($non->name == "Low Interest Rate Benefit") {
                        $loan = $non->details;
                    }
                    $nonCash += $non->amount;
                }
            ?>
            <tr>
                <td>{{ $payroll->for_month->format('F') }}</td>
                <td class="s-text-right">{{ number_format($a = $payroll->basic_salary, 2) }}</td>
                <td class="s-text-right">{{ number_format($b = $nonCash, 2) }}</td>
                <td class="s-text-right">{{ number_format($c = $payroll->quarters, 2) }}</td>
                <td class="s-text-right">{{ number_format($d = ($a + $b + $c), 2) }}</td>
                <td class="s-text-right">{{ number_format($e1 = ($a * 0.3), 2) }}</td>
                <td class="s-text-right">{{ number_format($e2 = $payroll->nssf, 2) }}</td>
                <td class="s-text-right">{{ number_format($e3 = 20000, 2) }}</td>
                <td class="s-text-right">{{ number_format(0, 2) }}</td>
                <td class="s-text-right">{{ number_format($g = ($e1 < $e2 ? ($e1 < $e3 ? $e1 : $e3) : ($e2 < $e3 ? $e2 : $e3)), 2) }}</td>
                <td class="s-text-right">{{ number_format($h = $d - $g, 2) }}</td>
                <td class="s-text-right">{{ number_format($j = $payroll->tax_charged, 2) }}</td>
                <td class="s-text-right">{{ number_format($k = $payroll->relief, 2) }}</td>
                <td class="s-text-right">{{ number_format($l = $payroll->paye, 2) }}</td>
            </tr>
            <?php
            $totalBasic += $a; $totalBenefits += $b; $totalQuarters += $c; $totalGross += $d;
            $totalE1 += $e1; $totalE2 += $e2; $totalE3 += $e3; $totalG += $g; $totalH += $h;
            $totalJ += $j; $totalK += $k; $totalL += $l;
            ?>
        <?php $currentMonth++; ?>
        @endforeach
        @while($currentMonth < 13)
            <tr>
                <td>{{ $calendar_months[$currentMonth] }}</td>
                <td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                <td></td><td></td><td></td><td></td><td></td><td></td>
            </tr>
            <?php $currentMonth++; ?>
        @endwhile

        <tr class="totals">
            <td>Totals</td>
            <td class="s-text-right">{{ number_format($totalBasic, 2) }}</td>
            <td class="s-text-right">{{ number_format($totalBenefits, 2) }}</td>
            <td class="s-text-right">{{ number_format($totalQuarters, 2) }}</td>
            <td class="s-text-right">{{ number_format($totalGross, 2) }}</td>
            <td class="s-text-right">{{ number_format($totalE1, 2) }}</td>
            <td class="s-text-right">{{ number_format($totalE2, 2) }}</td>
            <td class="s-text-right">{{ number_format($totalE3, 2) }}</td>
            <td class="s-text-right">{{ number_format(0, 2) }}</td>
            <td class="s-text-right">{{ number_format($totalG, 2) }}</td>
            <td class="s-text-right">{{ number_format($totalH, 2) }}</td>
            <td class="s-text-right">{{ number_format($totalJ, 2) }}</td>
            <td class="s-text-right">{{ number_format($totalK, 2) }}</td>
            <td class="s-text-right">{{ number_format($totalL, 2) }}</td>
        </tr>

    </table>
</div>
<table style="width: 100%; margin-top: -10px;">
    <tr>
        <td width="50%"><p>To be completed by Employer at end of year.</p></td>
        <td width="50%"></td>
    </tr>
    <tr>
        <td><strong>TOTAL CHARGEABLE PAY (COL. H) KShs. {{ number_format($totalH, 2) }}</strong></td>
        <td><strong>TOTAL TAX (COL. L) KShs. {{ number_format($totalL, 2) }}</strong></td>
    </tr>
    <tr>
        <td>
            <strong><u>IMPORTANT</u></strong>
            <ol type="1">
                <li>
                    Use P9A
                    <ol type="a">
                        <li>For all liable employees and where director/employee received benefits in addition to cash emoluments.</li>
                        <li>Where an employee is eligible to deduction on owner occupied interest.</li>
                    </ol>
                </li>
                <li>
                    (a) Deductible interest in respect of any month must not exceed KShs. 12,500/=
                </li>
                <li style="list-style-type: none">
                    <strong>(See back of this card for further information required by the Department).<br><em>P9A</em></strong>
                </li>
            </ol>
        </td>
        <td>
            (b) Attach <br>
            <ol type="i">
                <li>Photostat copy of interest certificate and statement of account from the financial institution.</li>
                <li>The DECLARATION duly signed by the employee.</li>
                <li style="list-style-type: none"><strong>NAMES OF FINANCIAL INSTITUTION ADVANCING MORTGAGE LOAN</strong></li>
                <li style="list-style-type: none"><strong>_______________________________</strong></li>
                <li style="list-style-type: none"><strong>L.R. NO. OF OWNER OCCUPIED PROPERTY:______________________</strong></li>
                <li style="list-style-type: none"><strong>DATE OF OCCUPATION OF HOUSE: _____________________________</strong></li>
                <li style="list-style-type: none"> &nbsp;</li>
            </ol>
        </td>
    </tr>
</table>
<span class="page-break"></span>
<div class="rotate">
    <div class="page2">
        <div class="content">
            <h2>APPENDIX 1B</h2>
            <p>INFORMATION REQUIRED FROM EMPLOYER AT END OF YEAR</p>
            <table class="data" align="right">
                <tr>
                    <th>Year</th>
                    <th>Amount</th>
                    <th>Tax</th>
                </tr>
                <tr>
                    <td></td>
                    <td><strong>KShs.</strong></td>
                    <td><strong>KShs.</strong></td>
                </tr>
                <tr>
                    <td><strong>20</strong></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td><strong>20</strong></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td><strong>20</strong></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td><strong>20</strong></td>
                    <td></td>
                    <td></td>
                </tr>
            </table>
            <ol type="1">
                <li>Date Employee commenced if during year: <strong><u>{{ $contract->first()->start_date->year == $today->year ? $contract->first()->start_date->format('d-F-Y') : 'N/A'}}</u></strong></li>
                <li style="list-style-type: none">Name and address of old employer: <strong><u>N/A.</u></strong></li>
                <li>Date Left if during year: <strong><u>{{ $contract->last()->end_date->year == $today->year ? $contract->last()->end_date->format('d-F-Y') : 'N/A'}}</u></strong></li>
                <li style="list-style-type: none">Name and address of new employer: <strong><u>N/A</u></strong></li>
                <li>Where housing is provided, state monthly rent charged: ________________________________</li>
                <li>Where any of the pay relates to a period other than this year, e.g. gratuity,
                    <br>Give details of Amounts, Year and Tax</li>
            </ol>
            <p>FOR MONTHLY RATES OF BENEFITS PLEASE REFER TO EMPLOYER'S GUIDE TO P.A.Y.E. - P7</p>
            {{--json_decode($payroll->non_cash)--}}
            <table class="s-table">
                <tr>
                    <td colspan="8" style="text-align: center">CALCULATION OF TAX ON BENEFITS</td>
                </tr>
                <tr>
                    <td><u>BENEFIT</u></td>
                    <td class="text-center"><u>NO</u></td>
                    <td></td>
                    <td class="s-text-center"><u>RATE</u></td>
                    <td></td>
                    <td class="s-text-center"><u>NO. OF MONTHS</u></td>
                    <td></td>
                    <td class="text-right"><u>TOTAL AMOUNT</u></td>
                </tr>
                <tr>
                    <td>COOK/HOUSE</td>
                    <td></td>
                    <td>X</td>
                    <td class="s-text-center">{{ $housing }}</td>
                    <td>X</td>
                    <td class="s-text-center">12</td>
                    <td>=</td>
                    <td class="text-right">{{ $housing * 12 }}</td>
                </tr>
                <tr>
                    <td>SERVANT</td>
                    <td></td>
                    <td>X</td>
                    <td></td>
                    <td>X</td>
                    <td></td>
                    <td>=</td>
                    <td></td>
                </tr>
                <tr>
                    <td>GARDENER</td>
                    <td></td>
                    <td>X</td>
                    <td></td>
                    <td>X</td>
                    <td></td>
                    <td>=</td>
                    <td></td>
                </tr>
                <tr>
                    <td>AYAH</td>
                    <td></td>
                    <td>X</td>
                    <td></td>
                    <td>X</td>
                    <td></td>
                    <td>=</td>
                    <td></td>
                </tr>
                <tr>
                    <td>WATCHMAN (D)</td>
                    <td></td>
                    <td>X</td>
                    <td></td>
                    <td>X</td>
                    <td></td>
                    <td>=</td>
                    <td></td>
                </tr>
                <tr>
                    <td>WATCHMAN (N)</td>
                    <td></td>
                    <td>X</td>
                    <td></td>
                    <td>X</td>
                    <td></td>
                    <td>=</td>
                    <td></td>
                </tr>
                <tr>
                    <td>FURNITURE</td>
                    <td></td>
                    <td>X</td>
                    <td></td>
                    <td>X</td>
                    <td></td>
                    <td>=</td>
                    <td></td>
                </tr>
                <tr>
                    <td>WATER</td>
                    <td></td>
                    <td>X</td>
                    <td></td>
                    <td>X</td>
                    <td></td>
                    <td>=</td>
                    <td></td>
                </tr>
                <tr>
                    <td>TELEPHONE</td>
                    <td></td>
                    <td>X</td>
                    <td></td>
                    <td>X</td>
                    <td></td>
                    <td>=</td>
                    <td></td>
                </tr>
                <tr>
                    <td>ELECTRICITY</td>
                    <td></td>
                    <td>X</td>
                    <td></td>
                    <td>X</td>
                    <td></td>
                    <td>=</td>
                    <td></td>
                </tr>
                <tr>
                    <td>SECURITY SYSTEM</td>
                    <td></td>
                    <td>X</td>
                    <td></td>
                    <td>X</td>
                    <td></td>
                    <td>=</td>
                    <td></td>
                </tr>
            </table>
            <p>Where actual cost is higher than given monthly rates of benefits then the actual cost is brought to charge in full.</p>
            <h3>LOW INTEREST RATE BELOW PRESCRIBED RATE OF INTEREST.</h3>
            <p>EMPLOYERS LOAN<tab></tab><tab></tab> = KShs {{ is_null($loan) ? '...................' : number_format($amnt = $loan->amount, 2) }} @ {{ is_null($loan) ? '.......... ' : $lRate = $loan->rate . '%' }} RATE</p>
            <p>RATE DIFFERENCE</p>
            <p>(PRESCRIBED RATE - EMPLOYERS RATE) <tab></tab><tab></tab> = {{ is_null($loan) ? '...................' : $rDiff = ($prescribed_rate * 100) - $lRate }}%</p>
            <p>
                MONTHLY BENEFIT (RATE DIFFERENCE X LOAN) <tab></tab><tab></tab> = <span style="padding: 0 10px;">{{ is_null($loan) ? '........' : $rDiff }}% X KShs {{ is_null($loan) ? '...................' : number_format($amnt, 2) }}</span> = <span style="padding: 0 10px;">{{ is_null($loan) ? '...................' : number_format($totalLoan = ($amnt * $rDiff)/100, 2) }}</span> = <span style="padding: 0 10px;"><sup>{{ is_null($loan) ? '...................' : number_format($totalLoan = ($amnt * $rDiff)/100, 2) }}</sup>/<sub>{{ is_null($loan) ? ' .......... ' : $loan->duration }}</sub></span> = <span style="padding: 0 10px;">{{ is_null($loan) ? '...................' : number_format($totalLoan / $loan->duration, 2) }}</span><br><div align="right" style="display: inline-block">{{ is_null($loan) ? '...................' : number_format($totalLoan / $loan->duration, 2) . ' X ' . count($payrolls) . ' = ' . number_format($totalLoan = ($totalLoan / $loan->duration) * count($payrolls), 2) }}</div>
            </p>
            <p>MOTOR CARS</p>
            <table class="s-table">
                <tr>
                    <td>Up ro 1500 c.c.</td>
                    <td class="s-text-center"></td>
                    <td>X</td>
                    <td></td>
                    <td>=</td>
                    <td class="text-right"></td>
                </tr>
                <tr>
                    <td>1501 c.c. - 1750 c.c.</td>
                    <td class="s-text-center"></td>
                    <td>X</td>
                    <td></td>
                    <td>=</td>
                    <td class="text-right"></td>
                </tr>
                <tr>
                    <td>1751 c.c. - 2000 c.c.</td>
                    <td class="s-text-center"></td>
                    <td>X</td>
                    <td></td>
                    <td>=</td>
                    <td class="text-right"></td>
                </tr>
                <tr>
                    <td>2001 c.c. - 3000 c.c.</td>
                    <td class="s-text-center"></td>
                    <td>X</td>
                    <td></td>
                    <td>=</td>
                    <td class="text-right"></td>
                </tr>
                <tr>
                    <td>Over 3000 c.c.</td>
                    <td class="s-text-center"></td>
                    <td>X</td>
                    <td></td>
                    <td>=</td>
                    <td class="text-right"></td>
                </tr>
                <tr>
                    <td>Total Benefit in Year</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>=</td>
                    <td class="text-right">{{ number_format($housing + $totalLoan, 2) }}</td>
                </tr>
            </table>
            <p>If this amount does not agree with total of Col. B overleaf, attach explanation.</p>
            <p>FOR PICK-UPS, PANEL VANS AND LAND-ROVERS REFER TO APPENDIX 5 OF EMPLOYER'S GUIDE.</p>
            <p>CAR BENEFIT - The higher the amount of the fixed monthly rate or the prescribed rate of benefits is to be brought to charge :-</p>
            <table>
                <tr>
                    <td>PRESCRIBED RATE: - </td>
                    <td>1996 - 1% per month of the initial cost of the vehicle</td>
                </tr>
                <tr>
                    <td></td>
                    <td>1996 - 1.5% per month of the initial cost of the vehicle</td>
                </tr>
                <tr>
                    <td></td>
                    <td>1996 - 2% per month of the initial cost of the vehicle</td>
                </tr>
            </table>
            <p>EMPLOYERS CERTIFICATE OF PAY AND TAX</p>
            <p>NAME: <u>{{ strtoupper($company->name) }} </u></p>
            <p>ADDRESS: <u>{{ strtoupper($company->postal_address . ' ' . $company->city) }} </u></p>
            <p>SIGNATURE: _____________________________________________________</p>
            <p>DATE & STAMP: ___________________________________________________</p>
            <p>
                NOTE: Employer's certificate to be signed by the person who prepares and submits the PAYE End of Year Returns and
                copy of the P9A be issued to the employee in January.
            </p>
        </div>
    </div>
</div>
<span class="page-break"></span>