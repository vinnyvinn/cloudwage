<?php
/**
 * @category PHP
 * @author   David Mjomba <smodavprivate@gmail.com>
 */

namespace Payroll\Handlers;

use Payroll\Models\Payroll;

class Calculator
{
    protected $calculator;

    /**
     * Calculator constructor.
     *
     * @param $calculator
     */
    public function __construct(CoinageCalculator $calculator)
    {
        $this->calculator = $calculator;
    }


    public function getCoinage(Payroll $payroll)
    {
        $netPay = $this->getNetPay($payroll);
        $values = [1000, 500, 200, 100, 50, 40, 20, 10, 5, 1];

        return $this->calculator->withAmount($netPay)->withDivisions($values)->get();
    }

    public function getAmount($amount, $value, $step = 0, $coinage = [])
    {
        $result = explode('.', $amount / $value[$step]);
        $remainder = $amount - ($value[$step] * $result[0]);
        $coinage [$value[$step]] = $result[0];

        $step++;
        if ($step < count($value)) {
            return getAmount($remainder, $value, $step, $coinage);
        }

        return $coinage;
    }

    public function getNetPay(Payroll $payroll)
    {
        $grossPay = $this->getGross($payroll);
        $deductions = $this->getDeductionsAmount($payroll);
        $advances = $this->getAdvancesAmount($payroll);
        $loans = $this->getLoansAmount($payroll);
        
        return round($grossPay - ($deductions + $advances + $loans));
    }
    
    public function getGross(Payroll $payroll)
    {
        return $this->getBasicPay($payroll) + $this->getAllowancesAmount($payroll);
    }

    public function getBasicPay(Payroll $payroll)
    {
        return $payroll->basic_pay;
    }

    private function getAllowancesAmount(Payroll $payroll)
    {
        $allowances = json_decode($payroll->allowances);
        $total = 0;
        foreach ($allowances as $allowance) {
            $total += $allowance->amount;
        }

        return $total;
    }

    private function getDeductionsAmount(Payroll $payroll)
    {
        $deductions = json_decode($payroll->deductions);
        $total = 0;
        foreach ($deductions as $deduction) {
            if (is_object($deduction->amount)) {
                $total += $deduction->amount->amount - $deduction->amount->relief->amount;
                continue;
            }
            $total += $deduction->amount;
        }

        return $total;
    }

    private function getAdvancesAmount(Payroll $payroll)
    {
        $advances = json_decode($payroll->advances);
        $total = 0;
        foreach ($advances as $advance) {
            $total += $advance->amount;
        }

        return $total;
    }

    private function getLoansAmount(Payroll $payroll)
    {
        $loans = json_decode($payroll->loans);
        $total = 0;
        foreach ($loans as $loan) {
            $total += $loan->amount;
        }

        return $total;
    }
}
