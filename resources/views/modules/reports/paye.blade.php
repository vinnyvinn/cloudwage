<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <table>
                <tbody>
                @foreach($payrolls as $payroll)
                <tr>
                    <td>{{ $payroll->employee->deductions->first()->deduction_number }}</td>
                    <td>{{ $payroll->employee->first_name }} {{ $payroll->employee->last_name }}</td>
                    <td>{{ $payroll->employee->identification_type == 'National ID' ? 'Resident' : 'Non-Resident'}}</td>
                    <td>Primary Employee</td>
                    <td>{{ $payroll->kra->basic_salary }}</td>
                    <?php $houseAmount = 0; ?>
                    @foreach($payroll->allowances as $allowance)
                        @if($allowance->name == 'Cook / House')
                            <?php $houseAmount = $allowance->tax_amount; ?>
                        @endif
                    @endforeach
                    <td>{{ $houseAmount }}</td>
                    <td>{{ 0 }}</td>
                    <td>{{ 0 }}</td>
                    <td>{{ 0 }}</td>
                    <td>{{ 0 }}</td>
                    <td>{{ 0 }}</td>
                    <?php $otherAllowances = 0; ?>
                    @foreach($payroll->allowances as $allowance)
                       <?php
			if(!isset($allowance->tax_amount)){
				continue;
			} 
				$otherAllowances += $allowance->tax_amount
                        ; ?>
                        @endforeach
                    <td>{{ $otherAllowances - $houseAmount }}</td>
                    <td></td>
                    <td>{{ 0 }}</td>
                    <td>{{ 0 }}</td>
                    <td></td>
                    <td>{{ 0 }}</td>
                    <td>Benefit Not Provided</td>
                    <td>{{ 0 }}</td>
                    <td></td>
                    <td>{{ $payroll->kra->nssf }}</td>
                    <td>{{ 0 }}</td>
                    <td>{{ 0 }}</td>
                    <td>{{ $payroll->kra->relief }}</td>
                    <td>{{ 0 }}</td>
                    <td>{{ $payroll->kra->tax_charged }}</td>
                </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
