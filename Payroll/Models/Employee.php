<?php

namespace Payroll\Models;

use App\OT;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Payroll\Factories\EmployeeFactory;
use Payroll\Parsers\LoanCalculator;
use Payroll\Repositories\EmployeeRepository;

class Employee extends Model
{
//    use SoftDeletes;
    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        self::created(function () {
            EmployeeRepository::reCache();
        });
        self::updated(function () {
            EmployeeRepository::reCache();
        });
        self::deleted(function () {
            EmployeeRepository::reCache();
        });
    }

    const MODULE_ID = 12;

    protected $fillable = [
        'payroll_number', 'avatar', 'identification_number', 'identification_type',
        'first_name', 'last_name', 'mobile_phone', 'payment_structure_id', 'email',
        'has_custom_tax_rate', 'custom_tax_rate'
    ];


    const PERMISSIONS = [
        'Create'    => 'employee.create',
        'Read'      => 'employee.read',
        'Update'    => 'employee.update',
        'Delete'    => 'employee.delete'
    ];

    public function isArchived()
    {
        return $this->trashed();
    }

    public function archive()
    {
        $this->delete();
        $this->user()->delete();

        return $this;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function completeDelete()
    {
        $this->forceDelete();
        $this->user()->forceDelete();

        return $this;
    }

    public function emptyArchive()
    {
        $archived = EmployeeFactory::archived();
        foreach ($archived as $archive) {
            $archive->completeDelete();
        }
    }

    public function contract()
    {
        return $this->hasMany(EmployeeContract::class);
    }

    public function allowances()
    {
        return $this->hasMany(EmployeeAllowance::class);
    }

    public function allowance()
    {
        return $this->belongsToMany(Allowance::class, 'employee_allowances');
    }

    public function deduction()
    {
        return $this->belongsToMany(Deduction::class, 'employee_deductions');
    }

    public function deductions()
    {
        return $this->hasMany(EmployeeDeduction::class);
    }

    public function paymentMethod()
    {
        return $this->hasOne(EmployeePaymentMethods::class);
    }
    
    public function payroll()
    {
        return $this->hasMany(Payroll::class);
    }
    public function overtimes()
    {
        return $this->hasMany(OT::class);
    }

    public function assignment()
    {
        return $this->hasOne(Assignment::class);
    }
    public function leaves()
    {
        return $this->hasMany(EmployeeLeave::class);
    }
    public function calculatePayroll($payrollDate, $filter, $daysTA, $expectedDays)
    {
        $contracts = $this->contract->reject(function ($value) use ($payrollDate) {
            $payDay = $payrollDate;
            $payDay = $payDay->subDay(1);
            return $value->end_date->lt($payDay) || $value->start_date->gt($payDay);
        })->sortBy('end_date');

        if ($contracts->count() < 1) {
            return false;
        }

        $basicSalary = $contracts->first()->current_basic_salary;
        $for_rate = $expectedDays;
        $type = ' Days';
        $measure = 'days_worked';

        switch ($this->paymentStructure->unit) {
            case "Unit":
                $attendance = $this->unitsMade()->whereForMonth($payrollDate->endOfMonth())->get();
                $type = ' Units';
                $measure = 'units_produced';
                break;
            case "Hour":
                $attendance = $this->hoursWorked()->whereForMonth($payrollDate->endOfMonth())->get();
                $type = ' Hours';
                $measure = 'hours_worked';
                break;
            case "Month":
            default:
                $basicSalary = $basicSalary / $expectedDays;
                if ($daysTA != 'true') {
                    $attendance = collect(json_decode('[{"days_worked" : '. $expectedDays.'}]'));
                    break;
                }
                $attendance = $this->daysWorked()->whereForMonth($payrollDate->endOfMonth())->get();
                break;
        }


        if ($attendance->count() == 0) {
            return false;
        } else {
            $for_rate = $attendance->first()->$measure;
            $basicSalary = $for_rate * $basicSalary;
            $for_rate = $for_rate  . $type;
        }

        $grossPay = $basicSalary;
        list($allowances, $taxableAmount) = $this->getTaxAmountFromAllowances();
        $grossPay += $taxableAmount;
        $p9Details = [
            'employee_id' => $this->id,
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
            if ($allowance['non_cash'] == 1) {
                $p9Details['non_cash'] [] = $allowance;
                continue;
            }
            $p9Details['basic_salary'] += $allowance['amount'];
        }
        $loans = $this->getLoans($payrollDate);
        foreach ($loans as $key => $loan) {
            if ($loan['name'] == 'Low Interest Rate Benefit') {
                $p9Details['non_cash'] [] = $loan;
                $allowances[] = $loan;
                $grossPay += $loan['amount'];
                unset($loans[$key]);
            }
        }
        $deductions = $this->getDeductions($grossPay);
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
        $advances = $this->getAdvances($payrollDate, $grossPay - $totalDeductions);
        $today = Carbon::now();
        $payroll = [
            'employee_id' => $this->id,
            'payroll_date' => $payrollDate,
            'filter' => $filter,
            'basic_pay' => $basicSalary,
            'for_rate' => $for_rate,
            'deductions' => json_encode($deductions),
            'allowances' => json_encode($allowances),
            'advances' => json_encode($advances),
            'loans' => json_encode($loans),
            'kra' => json_encode($p9Details),
            'created_at' => $today,
            'updated_at' => $today
        ];

        return $payroll;
    }

    private function getLoans($payrollDate)
    {
        $loans = $this->loans()
            ->where('balance', '>', 0)
            ->get();

        if ($loans->count() < 1) {
            return [];
        }

        $totalLoans = 0;
        $toDeduct = array();
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
                    'amount' => $loan->low_benefit
                ];
            }

            $toDeduct [] = [
                'name' => 'Loan: ' . number_format($loan->amount, 2) .
                    ' borrowed: ' . $loan->date_processed->format('d-F-Y'),
                'amount' => $loan->installments
            ];
        }

        return collect($toDeduct);
    }

    private function getAdvances($payrollDate, $grossPay)
    {
        $advances = $this->advances()
            ->where('for_month', $payrollDate->format('Y-m-d'))
            ->whereStatus(Advance::STATUS_UNPAID)
            ->get();

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

    private function getTaxAmountFromAllowances()
    {
        $allocatedAllowances = $this->allowances()->with('allowance')->get();
        $deductions = array();

        $totalDeducts = 0;

        foreach ($allocatedAllowances as $allocated) {
            $deductions [] = [
                'non_cash' => $allocated->allowance->non_cash,
                'taxable' => $allocated->allowance->taxable,
                'name' => $allocated->allowance->name,
                'amount' => $allocated->allowance->taxable ?
                    (($allocated->amount * $allocated->allowance->tax_rate)/100) :
                    $allocated->amount
            ];
            if (! $allocated->allowance->taxable) {
                continue;
            }
            $totalDeducts += ($allocated->amount * $allocated->allowance->tax_rate)/100;
        }

        return array(collect($deductions), $totalDeducts);
    }

    public function getDeductions($grossPay)
    {
        $employeeDeductions = $this->deductions()->with(['deduction', 'slabs'])->get();
        $deductions = Deduction::all()->keyBy('name');
        $toDeduct = array();
        $empDeductions = array_keys(collect($this->deductions->toArray())->keyBy('deduction_id')->toArray());
        $deductionAmount = 0;

        foreach ($employeeDeductions as $empDeduction) {
            switch ($empDeduction->deduction->name) {
                case "PAYE":
                    $deductedPay = $grossPay;
                    if (in_array(3, $empDeductions)) {
                        $nssf = $this->calculateNSSF($grossPay, $deductions->get('NSSF'));
                        $deductedPay -= $nssf;

                        $toDeduct [] = [
                            'name' => 'NSSF',
                            'amount' => $nssf
                        ];
                    }
                    $deductionAmount = $this->calculatePAYE($deductedPay, $deductions->get('PAYE'));
                    break;
                case "NSSF":
                    if (in_array(1, $empDeductions)) {
                        continue;
                    }
                    $deductionAmount = $this->calculateNSSF($grossPay, $deductions->get('NSSF'));
                    break;
                default:
                    $deduction = $deductions->get($empDeduction->deduction->name);

                    if ($grossPay < $deduction->threshold) {
                        break;
                    }

                    if ($deduction->type == 'per_employee') {
                        $deductionAmount = $empDeduction->amount;
                        break;
                    }
                    $deductionAmount =
                        $this->standardDeduction($deduction, $grossPay, $empDeduction);
                    break;
            }

            if ($empDeduction->deduction->has_relief && $deductionAmount > 0) {
                $relief = $empDeduction->deduction->relief;
                $deductionAmount = [
                    'amount' => $deductionAmount,
                    'relief' => [
                        'name' => $relief->name,
                        'amount' => $relief->amount
                    ]
                ];
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

    private function calculateNSSF($amount, $deduction)
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

    private function calculatePAYE($amount, $deduction)
    {

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

    private function standardDeduction($deduction, $amount)
    {
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

            return $slab[$slabIndex]->rate;
        }

        if (substr($deduction->rate, strlen($deduction->rate) - 1, strlen($deduction->rate)) == "%") {
            $toDeduct = ($amount * substr($deduction->rate, 0, strlen($deduction->rate) - 1)) / 100;

            return $toDeduct;
        }

        $toDeduct = $deduction->rate;

        return $toDeduct;
    }

    public function advances()
    {
        return $this->hasMany(Advance::class);
    }

    public function advancePayments()
    {
        return $this->hasMany(AdvancePayments::class);
    }

    public function deductionPayments()
    {
        return $this->hasMany(DeductionPayments::class);
    }

    public function loans()
    {
        return $this->hasMany(Loans::class);
    }

    public function daysWorked()
    {
        return $this->hasMany(DaysWorked::class);
    }

    public function paymentStructure()
    {
        return $this->belongsTo(PaymentStructure::class);
    }

    public function unitsMade()
    {
        return $this->hasMany(UnitsProduced::class);
    }
    
    public function hoursWorked()
    {
        return $this->hasMany(HoursWorked::class);
    }

    public function p9()
    {
        return $this->hasMany(KRAP9::class);
    }
}