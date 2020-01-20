<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <table>
                <thead>
                    <tr>
                        <th>Payment Reference</th>
                        <th>Beneficiary Name</th>
                        <th>Account Number</th>
                        <th>Bank Code</th>
                        <th>Branch Code</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($payrolls as $payroll)
                    <tr>
                        <td>{{ $payroll['payment_reference']}}</td>                        
                        <td>{{ $payroll['beneficiary_name']}}</td>
                        <td>{{ $payroll['account_number']}}</td>                        
                        <td>{{ $payroll['bank_code']}}</td>
                        <td>{{ $payroll['branch_code']}}</td>
                        <td>{{ $payroll['net_pay']}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
