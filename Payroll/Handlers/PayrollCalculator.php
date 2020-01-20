<?php

namespace Payroll\Handlers;

use App\SpecialAllowance;
use Carbon\Carbon;
use Payroll\Models\Advance;
use Payroll\Models\Deduction;
use Payroll\Models\Employee;
use Payroll\Models\Payroll;
use Payroll\Parsers\LoanCalculator;
use Payroll\Repositories\DeductionRepository;
use Payroll\Repositories\HolidayRepository;
use Payroll\Repositories\PolicyRepository;

class PayrollCalculator
{
    public static function calculate(Employee $employee, $payrollDate, $filter)
    {
        $contract = self::getContract($employee, $payrollDate);
        if (! $contract) {
            return false;
        }
        $expectedWorkingDays = self::getWorkingDays($payrollDate);
        $basicSalary = $contract->current_basic_salary;
        list($type, $measure, $attendance, $basicSalary) =
            self::getAttendance($employee, $payrollDate, $basicSalary, $expectedWorkingDays);


        if ($attendance->count() == 0) {
            return false;
        }

        $for_rate = $attendance->first()->$measure;
        $basicSalary = $for_rate * $basicSalary;
        $for_rate = $for_rate . $type;
        $grossPay = $basicSalary;

        list($allowances, $taxableAmount) = self::getTaxAmountFromAllowances($employee, $basicSalary);

        $grossPay += $taxableAmount;
        $p9Details = [
            'employee_id' => $employee->id,
            'for_month' => $payrollDate->endOfMonth()
        ];
        $p9Details['basic_salary'] = $basicSalary;
        $p9Details['for_month'] = $payrollDate->endOfMonth();
        $p9Details['for_month'] = $p9Details['for_month']->format('Y-m-d');
        $p9Details['non_cash'] = [];
        $p9Details['quarters'] = 0;
        $p9Details['nssf'] = 0;
        $p9Details['tax_charged'] = 0;
        $p9Details['relief'] = 0;
        $p9Details['paye'] = 0;

        foreach ($allowances as $allowance) {
            if ($allowance['in_basic']) {
                $allowanceAmount = $allowance['amount'] + $allowance['tax_amount'];
                $basicSalary -= $allowanceAmount;
                $grossPay -= $allowanceAmount;
                $p9Details['basic_salary'] -= $allowanceAmount;
            }

            if ($allowance['non_cash'] == 1) {
                $p9Details['non_cash'] [] = $allowance;
                continue;
            }
            $p9Details['basic_salary'] += $allowance['tax_amount'];
        }
        //rockplant
        $ot = self::getOT($employee, $payrollDate);
        if($ot != null) {
            $grossPay += $ot->amount;
        }
        //end for rockplant overtime
        //start specail allowancess

        $specials = SpecialAllowance::where('employee_id', $employee->id)
                                    ->where('for_month', $payrollDate->format('Y-m-d'))->get();

            if($specials != null){
                foreach ($specials as $special){
                    $allowances [] = [
                        'in_basic' => 0,
                        'non_cash' => 1,
                        'taxable' => 1,
                        'tax_amount' => $special->total,
                        'name' => $special->name,
                        'amount' => 0,
                    ];

                    $p9Details['basic_salary'] += $special->total;
                    $grossPay += $special->total;
                }
            }





        $loans = self::getLoans($employee, $payrollDate);

        foreach ($loans as $key => $loan) {
            if ($loan['name'] == 'Low Interest Rate Benefit') {
                $p9Details['non_cash'] [] = $loan;
                $allowances[] = $loan;
                $grossPay += $loan['tax_amount'];
                unset($loans[$key]);
            }
        }
        $deductions = self::getDeductions($employee, $grossPay);
        $totalDeductions = 0;

        foreach ($deductions as $deduction) {
            if ($deduction['name'] == 'NSSF') {
                $p9Details['nssf'] = $deduction['amount'];
            }
            if ($deduction['name'] == 'PAYE') {
                if ($deduction['amount'] > 0) {
                    $p9Details['tax_charged'] = $deduction['amount']['amount'];
                    $p9Details['relief'] = $deduction['amount']['relief']['amount'];
                    $p9Details['paye'] = $deduction['amount']['amount'] - $deduction['amount']['relief']['amount'];
                }
            }

            if (is_array($deduction['amount'])) {
                $amount = $deduction['amount']['amount'] - $deduction['amount']['relief']['amount'];
                $totalDeductions += $amount;
                continue;
            }
            $totalDeductions += $deduction['amount'];
        }

        $p9Details['non_cash'] = json_encode($p9Details['non_cash']);
        $p9Details['prescribed_rate'] = LoanCalculator::getPrescribedRate();
        $advances = self::getAdvances($employee, $payrollDate, $grossPay - $totalDeductions);
        $today = Carbon::now();



        return [
            'employee_id' => $employee->id,
            'payroll_date' => $payrollDate,
            'filter' => $filter,
            'basic_pay' => $basicSalary,
            'for_rate' => $for_rate,
            'deductions' => json_encode($deductions),
            'allowances' => json_encode($allowances),
            'advances' => json_encode($advances),
            'loans' => json_encode($loans),
            'kra' => json_encode($p9Details),
//            rockplant
            'overtime' => json_encode($ot),
            //END ROCKPLANT
            'created_at' => $today,
            'updated_at' => $today
        ];
    }

    /**
     * Get the current contract for the given employee for the particular date.
     *
     * @param Employee $employee
     * @param Carbon   $payrollDate
     *
     * @return bool
     */
    private static function getContract(Employee $employee, Carbon $payrollDate)
    {
        $contracts = $employee->contract->reject(function ($value) use ($payrollDate) {
            return $payrollDate->lte($value->start_date) || $payrollDate->gte($value->end_date);
        })->sortBy('end_date');

        if ($contracts->count() < 1) {
            return false;
        }

        return $contracts->first();
    }
    private static function getOT($employee, $payrollDate)
    {
        $payrollDate = $payrollDate->format('Y-m-d');
        $ot = $employee->overtimes->where('payroll_date', $payrollDate)->first();
//        $overtime [] = [
//            'ot_1' => 'OT 1.5',
//            'ot_1_amount' => $ot->ot_1_amount,
//            'ot_2' => 'OT 2.0',
//            'ot_2_amount' => $ot->ot_2_amount,
//            'amount' => $ot->amount
//        ];

        return $ot;
    }

    /**
     * Check if days worked setting is enabled.
     *
     * @return bool
     */
    private static function isWorkingDaysEnabled()
    {
        return PolicyRepository::get(
            Payroll::MODULE_ID, Payroll::ENABLE_DAYS_ATTENDANCE
        ) == 'true';
    }

    /**
     * Get the working days in the given month.
     *
     * @param Carbon $payrollDate
     *
     * @return bool|int
     */
    private static function getWorkingDays(Carbon $payrollDate)
    {
        $expectedWorkingDays = PolicyRepository::get(Payroll::MODULE_ID, Payroll::NUMBER_OF_DAYS);
        $holidays = self::getHolidayDays($payrollDate);

        return $expectedWorkingDays - $holidays;
    }

    /**
     * Get the number of holidays in the given payroll month.
     *
     * @param Carbon $payrollDate
     *
     * @return int
     */
    private static function getHolidayDays(Carbon $payrollDate)
    {
        $month = $payrollDate->month < 10 ? "0" . $payrollDate->month : $payrollDate->month;
        $holidays = HolidayRepository::getForMonth($month);

        if ($holidays->count() < 1) {
            return 0;
        }

        $totalHolidayDays = 0;

        foreach ($holidays as $holiday) {
            $day = Carbon::parse($holiday->holiday_day . '-' . $holiday->holiday_month . '-' . $payrollDate->year);

            if ($day->isWeekday() || $day->isSunday()) {
                $totalHolidayDays++;
            }
        }

        return $totalHolidayDays;
    }

    private static function getAttendance(Employee $employee, Carbon $payrollDate, $basicSalary, $workingDays)
    {
        $type = ' Days';
        $measure = 'days_worked';

        switch ($employee->paymentStructure->unit) {
            case "Unit":
                $attendance = $employee->unitsMade->whereLoose('for_month', $payrollDate->endOfMonth());
                $type = ' Units';
                $measure = 'units_produced';
                break;
            case "Hour":
                $attendance = $employee->hoursWorked->whereLoose('for_month', $payrollDate->endOfMonth());
                $type = ' Hours';
                $measure = 'hours_worked';
                break;
            case "Day":

                $basicSalary = $basicSalary / 30;

                $attendance = $employee->daysWorked->whereLoose('for_month', $payrollDate->endOfMonth());
                $type = ' Days';
                $measure = 'days_worked';
                break;
            case "Month":
            default:
                $basicSalary = $basicSalary / $workingDays;
                if (! self::isWorkingDaysEnabled()) {
                    $attendance = collect(json_decode('[{"days_worked" : '. $workingDays.'}]'));
                    break;
                }
                $attendance = $employee->daysWorked->whereLoose('for_month', $payrollDate->endOfMonth());
                break;
        }

        return [$type, $measure, $attendance, $basicSalary];
    }

    private static function getTaxAmountFromAllowances(Employee $employee, $basicSalary)
    {
        $allowances = [];
        $totalDeducts = 0;

        foreach ($employee->allowances as $allocated) {
            $amount = round(self::getAllowanceAmount($allocated, $basicSalary), 2);
            $taxAmount = round($allocated->allowance->taxable ?
                (($amount * $allocated->allowance->tax_rate)/100) : 0, 2);

            $allowances [] = [
                'in_basic' => $allocated->allowance->in_basic,
                'non_cash' => $allocated->allowance->non_cash,
                'taxable' => $allocated->allowance->taxable,
                'tax_amount' => $taxAmount,
                'name' => $allocated->allowance->name,
                'amount' => $amount - $taxAmount
            ];

            $totalDeducts += $taxAmount;
        }

        return array(collect($allowances), $totalDeducts);
    }

    private static function getAllowanceAmount($assigned, $basicSalary)
    {
        if ($assigned->allowance->type == 'rate') {
            return self::getRateAmount($basicSalary, $assigned->allowance->rate, $assigned->allowance->in_basic);
        }

        return $assigned->amount;
    }

    private static function getRateAmount($amount, $rate, $inAmount = false)
    {
        if (! preg_match('/%/', $rate)) {
            return $rate;
        }

        $rate = str_replace('%', '', $rate) / 100;

        if ($inAmount) {
            $rate = $rate / ($rate + 1);
        }

        return $amount * $rate;
    }

    private static function getLoans(Employee $employee, Carbon $payrollDate)
    {
        $loans = $employee->loans->reject(function ($value) {
            return $value->balance < 1;
        });

        if ($loans->count() < 1) {
            return [];
        }

        $totalLoans = 0;
        $toDeduct = [];

        foreach ($loans as $loan) {
            if (! $payrollDate->between($loan->date_processed, $loan->date_processed->addMonths($loan->duration))) {
                continue;
            }

            $totalLoans += $loan->installments;
            if ($loan->low_benefit > 0) {
                $toDeduct [] = [
                    'non_cash' => 1,
                    'taxable' => 1,
                    'name' => 'Low Interest Rate Benefit',
                    'details' => $loan,
                    'amount' => 0,
                    'tax_amount' => $loan->low_benefit
                ];
            }
//payroll rockplant
            $toDeduct [] = [
                'name' => 'Loan: ' . number_format($loan->amount, 2) .
                    ' borrowed: ' . $loan->date_processed->format('d-F-Y'),
                'balance' => $loan->balance,
                'amount' => $loan->installments
            ];
        }
//end of rockplant
        return collect($toDeduct);
    }

    /**
     * @param Employee $employee
     * @param          $grossPay
     *
     * @return array
     */
    private static function getDeductions(Employee $employee, $grossPay)
    {
        $deductions = DeductionRepository::all()->keyBy('name');
        $toDeduct = array();
        $empDeductions = array_keys(collect($employee->deductions->toArray())->keyBy('deduction_id')->toArray());
        $deductionAmount = 0;

        foreach ($employee->deductions as $empDeduction) {
            switch ($empDeduction->deduction->name) {
                case "PAYE":
                    $deductedPay = $grossPay;
                    if (in_array(3, $empDeductions)) {
                        $nssf = self::calculateNSSF($grossPay, $deductions->get('NSSF'));
                        $deductedPay -= $nssf;

                        $toDeduct [] = [
                            'name' => 'NSSF',
                            'amount' => $nssf
                        ];
                    }
                    $deductionAmount = self::calculatePAYE($deductedPay, $deductions->get('PAYE'), $employee);
                    break;
                case "NSSF":
                    if (in_array(1, $empDeductions)) {
                        continue;
                    }
                    $deductionAmount = self::calculateNSSF($grossPay, $deductions->get('NSSF'));
                    break;
                default:


                    $deduction = $deductions->get($empDeduction->deduction->name);
//                    dd($deduction);
                    if ($grossPay < $deduction->threshold) {
                        break;
                    }

                    if ($deduction->type == 'per_employee') {
                        $deductionAmount = $empDeduction->amount;
                        break;
                    }
                    $deductionAmount =
                        self::standardDeduction($deduction, $grossPay, $empDeduction);
                    break;
            }

            if (! ($employee->has_custom_tax_rate && $employee->custom_tax_rate > 0 && $empDeduction->deduction->id == Deduction::PAYE)) {
                if ($empDeduction->deduction->has_relief && $deductionAmount > 0) {
                    if ($employee->has_custom_tax_rate && $employee->custom_tax_rate > 0 && $empDeduction->deduction->id == Deduction::PAYE) {
                        continue;
                    }
                    $relief = $empDeduction->deduction->relief;
                    $deductionAmount = [
                        'amount' => $deductionAmount,
                        'relief' => [
                            'name' => $relief->name,
                            'amount' => $relief->amount
                        ]
                    ];
                }
            }


            if (in_array(1, $empDeductions) && $empDeduction->deduction->name == "NSSF") {
                continue;
            }

            $toDeduct [] = [
                'name' => $empDeduction->deduction->name,
                'amount' => $deductionAmount
            ];
        }

        return $toDeduct;
    }

    private static function calculateNSSF($amount, $deduction)
    {
        if ($amount < $deduction->threshold) {
            return 0;
        }

        if ($deduction->type == 'rate') {
            return $deduction->rate;
        }
        $slabs = $deduction->slabs->keyBy('slab_number');

        $lowerEarningLimit = $slabs->get(1)->max_amount;
        $upperEarningLimit = $slabs->get(2)->max_amount;
        if ($amount <= $lowerEarningLimit) {
            return $amount * 0.06;
        }

        $tier1 = $lowerEarningLimit * 0.06;
        if ($amount <= $upperEarningLimit) {
            $tier2 = ($amount - $lowerEarningLimit) * 0.06;
            return $tier1 + $tier2;
        }
        $tier2 = ($upperEarningLimit - $lowerEarningLimit) * 0.06;

        return $tier1 + $tier2;
    }

    private static function calculatePAYE($amount, $deduction, $employee)
    {
        if ($employee->has_custom_tax_rate && $employee->custom_tax_rate > 0) {
            return ($employee->custom_tax_rate / 100) * $amount;
        }

        $slabs = $deduction->slabs->keyBy('slab_number');
        $threshold = $deduction->threshold;
        $deduction = 0;

        if ($amount < $threshold) {
            return $deduction;
        }

        foreach ($slabs as $slab) {
            $lowerLimit = $slab->min_amount == 0 ? 0 : $slab->min_amount - 1;
            $upperLimit = $slab->max_amount;
            $rate = $slab->rate / 100;

            if ($upperLimit == 0) {
                $deduction += ($amount - ($lowerLimit + 1)) * $rate;
                continue;
            }

            if ($amount >= $upperLimit) {
                $deduction += ($upperLimit - $lowerLimit) * $rate;
                continue;
            }

            $deduction += ($amount - $lowerLimit) * $rate;
            break;
        }

        return $deduction;
    }

    private static function standardDeduction($deduction, $amount)
    {
        $amount = ceil($amount);
        if ($deduction->type == 'slab') {
            $slab = $deduction->slabs;

            $slabIndex = $slab->search(function ($item) use ($amount) {
                $maxQuery = ($amount <= $item->max_amount);

                if ($item->max_amount == 0) {
                    $maxQuery = true;
                }

                if ($amount >= $item->min_amount && $maxQuery) {
                    return $item;
                }
            });
//            dd($slabIndex);
            return $slab[$slabIndex]->rate;
        }

        if (substr($deduction->rate, strlen($deduction->rate) - 1, strlen($deduction->rate)) == "%") {
            $toDeduct = ($amount * substr($deduction->rate, 0, strlen($deduction->rate) - 1)) / 100;

            return $toDeduct;
        }

        $toDeduct = $deduction->rate;

        return $toDeduct;
    }

    private static function getAdvances(Employee $employee, Carbon $payrollDate, $grossPay)
    {
        $advances = $employee->advances->reject(function ($value) use ($payrollDate) {
            return (! $payrollDate->isSameDay($value->for_month)) && ($value->status != Advance::STATUS_UNPAID);
        });

        if ($advances->count() < 1) {
            return [];
        }
        $usableGross = $grossPay;
        $totalAdvances = 0;
        $toDeduct = array();

        foreach ($advances as $advance) {
            if ($usableGross < $advance->balance) {
                $totalAdvances = $usableGross;
                $advance->balance -= $usableGross;
                $advance->save();

                $toDeduct [] = [
                    'name' => 'Advance - ' . $advance->for_month->format('F-Y'),
                    'amount' => $usableGross
                ];
                $advance->for_month->format('F-Y');
                continue;
            }
            $totalAdvances += $advance->balance;
            $toDeduct [] = [
                'name' => 'Advance - ' . $advance->for_month->format('F-Y'),
                'amount' => $advance->balance
            ];
            $usableGross -= $advance->balance;
        }

        return collect($toDeduct);
    }
}
