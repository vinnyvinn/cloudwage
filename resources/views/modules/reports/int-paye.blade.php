<div class="container">
    <div class="row text-center">
        <h3>{{ $company->name }}</h3>
        <h4> PAYE Deductions :  {{ $payroll_date }}</h4>
        <h4> Employer PIN: {{ $company->kra_pin }}</h4>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <table>
                <thead>

                <tr>
                    <th>Payroll Number</th>
                    <th>Employee Name</th>
                    <th>P.I.N</th>
                    <th>Tax Amount</th>
                    <th>Remarks</th>
                </tr>
                </thead>
                <tbody>
                <?php $total = 0; ?>
                @foreach($payrolls as $payroll)
                    <?php $total += floatval($payroll->kra->paye ); ?>
                    <tr>
                        <td>{{ $payroll->employee->payroll_number }}</td>
                        <td>{{ $payroll->employee->first_name }} {{ $payroll->employee->last_name }}</td>
                        <td>{{ $payroll->employee->deductions->first()->deduction_number }}</td>
                        <td>{{ $payroll->kra->paye }}</td>
                        <td>_ _ _ _ _ _ _ _</td>


                    </tr>
                @endforeach
                <tr></tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td>TOTALS</td>
                    <td>{{ $total }}</td>

                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>