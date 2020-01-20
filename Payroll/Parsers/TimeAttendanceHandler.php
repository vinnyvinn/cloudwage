<?php

namespace Payroll\Parsers;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Payroll\Models\Employee;
use Payroll\Models\PaymentStructure;

/**
 * Class TimeAttendanceHandler
 *
 * @category PHP
 * @package  Payroll\Parsers
 * @author   David Mjomba <smodavprivate@gmail.com>
 */

class TimeAttendanceHandler
{
    /**
     * @var ModelFilter
     */
    private $filter;
    /**
     * @var Employee
     */
    private $employee;

    /**
     * TimeAttendanceHandler constructor.
     *
     * @param ModelFilter $filter
     * @param Employee    $employee
     */
    public function __construct(ModelFilter $filter, Employee $employee)
    {
        $this->filter = $filter;
        $this->employee = $employee;
    }

    public function getEmployees($fromModel, $unit)
    {
        $employee = $this->employee;
        $todaysMonth = Carbon::now()->endOfMonth();
        $paymentStructures = PaymentStructure::whereUnit($unit)->get();

        foreach ($paymentStructures as $structure) {

            if ($paymentStructures->first() == $structure) {
                $employee = $employee->where('payment_structure_id', $structure->id);
                continue;
            }
            $employee = $employee->orWhere('payment_structure_id', $structure->id);

        }
//        dd($employee);
        if ($employee->get()->count() == 0 || $paymentStructures->count() == 0) {
            flash('Sorry, you have no employees using the ' . $unit . ' payment structure', 'error');

            return false;
        }

//        $employees = $this->filter->filter($employee->get())
//            ->usingKey('id')
//            ->by($fromModel)
//            ->usingColumn('employee_id')
//            ->whereForMonth($todaysMonth)
//            ->get();
            $employees = $employee->get();
        return $employees;
    }

    public function processBulk($requestData, $fieldName, $insertColumn)
    {

        $fields = $requestData->filter(function ($value, $key) use ($fieldName) {
            return Str::startsWith($key, $fieldName) && $value != '';
        });

        $now = Carbon::now();
        $insert = array();

        foreach ($fields as $key => $value) {
            $insert [] = [
                'employee_id' => substr($key, strlen($fieldName)),
                'for_month' => Carbon::parse($now)->endOfMonth(),
                $insertColumn => $value,
                'created_at' => $now,
                'updated_at' => $now
            ];
        }

        return $insert;
    }
}