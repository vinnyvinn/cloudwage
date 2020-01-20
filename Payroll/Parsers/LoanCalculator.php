<?php

namespace Payroll\Parsers;

use Payroll\Models\Loans;
use Payroll\Models\Policy;

/**
 * Class LoanCalculator
 *
 * Example
 *
 *  $details = $this->calculator
 *          ->calculate(5000)
 *          ->atInterest(0.1)
 *          ->using(LoanCalculator::COMPOUND)
 *          ->per(LoanCalculator::PER_YEAR)
 *          ->forYears(2)
 *          ->get());
 *
 * @category PHP
 * @package  Payroll\Parsers
 * @author   David Mjomba <smodavprivate@gmail.com>
 */

class LoanCalculator
{
    const LOAN_PRESCRIBED_RATE = 'PRESCRIBED LOAN RATE';
    const COMPOUND = 'compound';
    const PER_MONTH = 'per_month';
    const PER_YEAR = 'per_year';
    const SIMPLE = 'simple';

    private $amount;

    private $interest;

    private $type;

    private $years;

    private $iterations;

    private $interval;

    public static function getPrescribedRate()
    {
        return Policy::whereModuleId(Loans::MODULE_ID)
            ->wherePolicy(static::LOAN_PRESCRIBED_RATE)
            ->first()->value / 100;
    }

    public function calculate($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    public function using($type)
    {
        $this->type = $type;

        return $this;
    }

    public function atInterest($interest)
    {
        $this->interest = $interest;

        return $this;
    }

    public function per($duration)
    {
        $this->iterations = $duration;

        return $this;
    }

    public function forYears($years)
    {
        $this->years = $years;

        return $this;
    }

    private function getRate()
    {
        $rate = $this->interest;
        if ($this->iterations == static::PER_MONTH) {
            $rate = $rate * 12;
        }

        return $rate;
    }

    public function get()
    {
        $fringe = $this->getFringeBenefit();
        $low = $this->getLowBenefit();

        return collect([
            'amount' => $this->amount,
            'rate' => $this->getRate(),
            'fringe_benefit' => $fringe,
            'low_benefit' => $low,
            'amount_payable' => $this->getLoanDetails()
        ]);
    }

    private function getFringeBenefit()
    {
        $currentRate = static::getPrescribedRate();
        $fringe = 0;
        if ($currentRate > $this->getRate()) {
            $taxRate = $currentRate - $this->interest;
            $fringe = (($this->amount * $taxRate) * 0.3) / ($this->years * 12);
        }

        return $fringe;
    }

    private function getLowBenefit()
    {
        return 0;
        $currentRate = static::getPrescribedRate();
        $low = 0;
        if ($currentRate > $this->getRate()) {
            $taxRate = $currentRate - $this->interest;
            $low = ($this->amount * $taxRate) / ($this->years * 12);
        }

        return $low;
    }

    private function calculateCompound()
    {
        $monthlyRate = $this->getRate()/12;
        $durationInMonths = $this->getInterval() * $this->years * 12;

        $part1 = pow(1 + $monthlyRate, -$durationInMonths);

        return (($monthlyRate * $this->amount) / (1 - $part1)) * $durationInMonths;
    }


    private function getLoanDetails()
    {
        switch ($this->type) {
            case static::COMPOUND:
                return $this->calculateCompound();
                break;
            case static::SIMPLE:
            default:
                return $this->amount * (1 + ($this->getRate() * $this->years));
                break;
        }
    }

    private function getInterval()
    {
        if ($this->iterations == static::PER_MONTH) {
            return 12;
        }

        return 1;
    }
}
