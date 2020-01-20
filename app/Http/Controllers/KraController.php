<?php

namespace App\Http\Controllers;

use App\Policies\Policy as UserPolicy;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Payroll\Models\Employee;
use Payroll\Models\EmployeeDeduction;
use Payroll\Models\Payroll;
use Payroll\Parsers\DocumentGenerator;

class KraController extends Controller
{
    private $payroll;
    private $employee;
    private $AllEmployeesKraDetails;
    private $date;
    private $formatted_date;
    public function __construct(Payroll $payroll, Employee $employee)
    {
        $this->payroll = $payroll;
        $this->employee = $employee;
        $this->AllEmployeesKraDetails = collect();
        $this->date = "";
        $this->formatted_date = "";
    }
    // kra report
    public function kraImports()
    {
        UserPolicy::canRead(new Payroll());
        $months = $this->payroll->all()->unique('payroll_date')->sort();
        $months = $months->each(function ($item, $key) use ($months) {
            $months[$key] = [
                'id' => $item->payroll_date,
                'value' => Carbon::parse($item->payroll_date)->format('d F Y'),
            ];
        });
        return view('modules.kra.kra-imports')
            ->withMonths($months);
    }
    public function generate(Request $request, DocumentGenerator $generator)
    {
        ini_set('max_execution_time', 240);
        UserPolicy::canRead(new Payroll());
        //    csv headers
        // $headers = collect([
        //     'PIN of Employee',
        //     'Name of Employee',
        //     'Residential Status',
        //     'Type of Employee',
        //     'Basic Salaray',
        //     'Housing Allowance',
        //     'Transport Allowance',
        //     'Leave Pay',
        //     'Over Time Allowance',
        //     "Director's Fee",
        //     "Lump Sum Payment if any",
        //     "Other Allowance",
        //     "Total Cash Pay",
        //     "Value of Car Benefits",
        //     "Other Non Cash Benefits",
        //     "Total Non Cash Pay",
        //     "Global Income",
        //     "Type of Housing",
        //     "Rent of House/Market Value",
        //     "Computed Rent of House",
        //     "Rent Recovered from Employee",
        //     "Net Value of Housing",
        //     "Total Gross Pay",
        //     "30% of Cash Pay",
        //     "Actual Contribution",
        //     "Permissible Limit",
        //     "Mortgage Interest",
        //     "Deposit on Home Ownership Saving Plan",
        //     "Amount of Benefit",
        //     "Taxable Pay",
        //     "Tax Payable",
        //     "Monthly Personal Relief",
        //     "Amount of Insurance",
        //     "Paye Tax",
        //     "Self Assessed PAYE Tax",
        // ]);
        //    passed to the generator
        // $this->AllEmployeesKraDetails->push($headers);
        //    fixed variables declaration
        $this->date = $request->payroll_date;
        $this->formatted_date = Carbon::parse($this->date)->format('Y-m-d');
        $monthly_personal_relief = 1408;
        $zero_value = 0;
        $residential_status = "Resident";
        $employee_type = "Primary Employee";
        $type_of_housing = "Benefit not given";
        $permissible_limit = 20000;
        $actual_contribution = 200;
        $amount_of_benefit = 200;
        //    get all employees payroll details
        $employeePayrollDetails = $this->payroll->where('payroll_date', $this->date)->get();
        foreach ($employeePayrollDetails as $kraDetails) {
            //    get individual employee details and contract details
            $emloyeeDetails = $this->employee::where('id', $kraDetails->employee_id)->with('contract')->first();
            //    collection to store individual employee details
            $employeeKraDetails = collect();
            //   get allowance details and assign to the correct values
            $house_allowances = 0;
            $transport_allowance = 0;
            $leave_pay = 0;
            $overtime_allowance = 0;
            $directors_fee = 0;
            $lump_sum_payment = 0;
            $other_allowances = 0;
            
            $allowances = json_decode($kraDetails->allowances);
            foreach ($allowances as $allowance) {
                if ($allowance->name == 'reliefs') {
                    continue;
                } elseif ($allowance->name == 'transport' || $allowance->name == 'fuel') {
                    $transport_allowance = $allowance->tax_amount;
                } elseif ($allowance->name == 'House Allowance' || $allowance->name == 'cook/house') {
                    $house_allowances = $allowance->tax_amount;
                } elseif($allowance->name == 'leave pay'){
                    $leave_pay = $allowance->tax_amount;
                }elseif($allowance->name == 'over time allowance'){
                    $overtime_allowance = $allowance->tax_amount;
                }elseif($allowance->name == 'directors fee'){
                    $directors_fee = $allowance->tax_amount;
                }elseif($allowance->name == 'lump sum payment'){
                    $lump_sum_payment = $allowance->tax_amount;
                }
                else {
                    $other_allowances += $allowance->tax_amount;
                }
            }
            $pin = EmployeeDeduction::where('deduction_id', 1)
            ->where('employee_id', $emloyeeDetails->id)->first();
            // calculate totals
            $total_allowances = $house_allowances + $transport_allowance + $leave_pay + $overtime_allowance + $directors_fee + $lump_sum_payment + $other_allowances;
            $basic_pay = $kraDetails->basic_pay;
            $total_cash_pay = $basic_pay + $total_allowances;
            $value_car_benefit = $zero_value; 
            $other_non_cash_benefits =  $zero_value;  
            $total_non_cash_pay = $value_car_benefit + $other_non_cash_benefits; 
            $global_income = $zero_value; 
            $computed_rent_house = $zero_value;  
            $rent_recovered = $zero_value;  
            $net_housing_value = $computed_rent_house - $rent_recovered;  
            $total_gross_pay = $total_cash_pay + $total_non_cash_pay + $global_income + $net_housing_value;
            $tax_payable = json_decode($kraDetails->kra)->tax_charged;
            $paye_tax = $tax_payable - $monthly_personal_relief;
            //    assign all other details values
            $employeeKraDetails['kra_pin'] = $pin != null ? $pin->deduction_number : '';
            $employeeKraDetails['employee_name'] = $emloyeeDetails->first_name. ' ' .$emloyeeDetails->last_name;
            $employeeKraDetails['residential_status'] = $residential_status;
            $employeeKraDetails['employee_type'] = $employee_type;
            $employeeKraDetails['basic_salary'] = $basic_pay;
            $employeeKraDetails['housing_allowance'] = $house_allowances;
            $employeeKraDetails['transport_allowance'] = $transport_allowance;
            $employeeKraDetails['leave_pay'] = $leave_pay;
            $employeeKraDetails['overtime_allowance'] = $overtime_allowance;
            $employeeKraDetails['directors_fee'] = $directors_fee;
            $employeeKraDetails['lumpsum_payment'] = $lump_sum_payment;
            $employeeKraDetails['other_allowances'] = $other_allowances;
            $employeeKraDetails['total_cash_pay'] = $total_cash_pay;
            $employeeKraDetails['value_car_benefit'] = $value_car_benefit;
            $employeeKraDetails['other_non_cash_benefits'] = $other_non_cash_benefits;
            $employeeKraDetails['total_non_cash_pay'] = $total_non_cash_pay;
            $employeeKraDetails['global_income'] = $global_income;
            $employeeKraDetails['type_of_housing'] =  $type_of_housing;
            $employeeKraDetails['rent_market_value'] = $zero_value;
            $employeeKraDetails['computed_rent_house'] = $computed_rent_house;
            $employeeKraDetails['rent_recovered'] = $rent_recovered;
            $employeeKraDetails['net_housing_value'] = $zero_value;
            $employeeKraDetails['total_gross_pay'] = $total_gross_pay;
            $employeeKraDetails['thirty_cash_pay'] = $total_cash_pay * 0.3;
            $employeeKraDetails['actual_contribution'] = $actual_contribution;
            $employeeKraDetails['permissible_limit'] = $permissible_limit;
            $employeeKraDetails['mortgage_interest'] = $zero_value;
            $employeeKraDetails['home_ownership_savings'] = $zero_value;
            $employeeKraDetails['amount_of_benefit'] = $amount_of_benefit;
            $employeeKraDetails['taxable_pay'] = $total_gross_pay - $amount_of_benefit;
            $employeeKraDetails['tax_payable'] = $tax_payable;
            $employeeKraDetails['monthly_personal_relief'] = $monthly_personal_relief;
            $employeeKraDetails['insurance_amount'] = $zero_value;
            $employeeKraDetails['paye_tax'] = $paye_tax;
            $employeeKraDetails['self_assessed_paye'] = $paye_tax;
            //    add an individual employee record to the main collection
            $this->AllEmployeesKraDetails->push($employeeKraDetails);
        }
        //    dd(json_encode($AllEmployeesKraDetails));
        // Generate and return the spreadsheet
        Excel::create($this->formatted_date . '-' . 'kra-imports', function ($excel) {
            $excel->setTitle('Month ending' . ' ' . $this->formatted_date . ' ' . 'kra-imports');
            $excel->setDescription('Kra Import records for the month ending ' . $this->formatted_date);
            // Build the spreadsheet, passing in the employee details collection
            $excel->sheet('Employee Details', function ($sheet) {
                $sheet->fromArray($this->AllEmployeesKraDetails, null, 'A1', true, false);
            });
        })->download('csv');
    }
}