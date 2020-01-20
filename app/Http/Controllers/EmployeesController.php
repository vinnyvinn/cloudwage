<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Policies\Policy;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Payroll\Models\Allowance;
use Payroll\Models\Assignment;
use Payroll\Models\Deduction;
use Payroll\Models\Department;
use Payroll\Models\Employee;
use Payroll\Models\EmployeeAllowance;
use Payroll\Models\EmployeeContract;
use Payroll\Models\EmployeeDeduction;
use Payroll\Models\EmployeePaymentMethods;
use Payroll\Models\EmployeeType;
use Payroll\Models\EmployeeWorkPlanAssignment;
use Payroll\Models\PayGrade;
use Payroll\Models\PaymentMethod;
use Payroll\Models\PaymentStructure;
use Payroll\Models\WorkPlan;

class EmployeesController extends Controller
{
    /**
     * @var Employee
     */
    private $employee;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Policy::canRead(new Employee);

        return view('modules.employees.enrol.index')
            ->withEmployees(Employee::all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Policy::canCreate(new Employee());
        return view('modules.employees.enrol.create')
            ->withEmployeeType(EmployeeType::all())
            ->withPayGrades(PayGrade::all())
            ->withDepartments(Department::all())
            ->withEmployees(Employee::all())
            ->withPaymentMethods(PaymentMethod::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Policy::canCreate(new Employee());
        if (Employee::wherePayrollNumber($request->payroll_number)->exists()) {
            flash('Sorry, the given payroll number has already been assigned.', 'error');

            return redirect()->back()->withInput();
        }
        $data = $request->all();
        $data['start_date'] = Carbon::parse($data['start_date']);
        $data['end_date'] = Carbon::parse($data['end_date']);
        $data['has_custom_tax_rate'] = $request->has('has_custom_tax_rate');
        $employee = Employee::create($data);
        $data['employee_id'] = $employee->id;
        $now = Carbon::now();

        EmployeeContract::create($data);

        if ($request->has('allowances')) {
            $allowances = array();

            foreach ($data['allowances'] as $allowance) {
                $allowances [] = [
                    'employee_id' => $employee->id,
                    'allowance_id' => $allowance,
                    'currency_id' => $data['currency_id'],
                    'amount' => isset($data['allowance' . $allowance . '_amount']) ? $data['allowance' . $allowance . '_amount'] : 0,
                    'created_at' => $now,
                    'updated_at' => $now
                ];
            }

            EmployeeAllowance::insert($allowances);
        }

        if ($request->has('deductions')) {
            $deductions = array();

            foreach ($data['deductions'] as $deduction) {
                $deduct = [
                    'employee_id' => $employee->id,
                    'deduction_id' => $deduction,
                    'deduction_number' => $data['deduction' . $deduction . '_number'],
                    'amount' => null,
                    'created_at' => $now,
                    'updated_at' => $now
                ];

                if (in_array('deduction' . $deduction . '_deduction_amount', array_keys($data))) {
                    $deduct['amount'] = $data['deduction' . $deduction . '_deduction_amount'];
                }

                $deductions [] = $deduct;
            }
            EmployeeDeduction::insert($deductions);
        }

        $payment = EmployeePaymentMethods::create($data);
        $method = PaymentMethod::with('udfs')->findOrFail($data['payment_method_id']);
        $payment->updateWithUdfs($method->udfs, $data);
        $payment->save();

        Assignment::create([
            'employee_id' => $employee->id,
            'department_id' => $data['department_id']
        ]);
        flash('Successfully added new employee', 'success');

        return redirect()->route('employees.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        Policy::canRead(new Employee());
        $employee = Employee::with([
            'contract', 'paymentMethod', 'deductions', 'allowances', 'advances', 'advancePayments', 'deductionPayments'
        ])->findOrFail($id);
        $deductions = Deduction::all()->keyBy('id');
        $allowances = Allowance::all()->keyBy('id');
        $paymentMethod = PaymentMethod::with(['udfs'])->find($employee->paymentMethod->payment_method_id);

        return view('modules.employees.enrol.show')
            ->withPaymentMethod($paymentMethod)
            ->withEmployee($employee)
            ->withDeductions($deductions)
            ->withAllowances($allowances);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        Policy::canUpdate(new Employee());
        $employee = Employee::findOrFail($id);

        return view('modules.employees.enrol.edit')
            ->withEmployeeType(EmployeeType::all())
            ->withPayGrades(PayGrade::all())
            ->withDepartments(Department::all())
            ->withEmployees(Employee::all())
            ->withStructures(PaymentStructure::all())
            ->withPaymentMethods(PaymentMethod::all())
            ->withInput($employee);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        Policy::canUpdate(new Employee());
        $employee = Employee::findOrFail($id);
        $data = $request->all();
        $data['has_custom_tax_rate'] = $request->has('has_custom_tax_rate');

        $employee->fill($data);
        $employee->save();
        flash('Successfully updated employee record.', 'success');

        return redirect()->route('employees.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Policy::canDelete(new Employee());

        $employee = Employee::findOrFail($id);
        $employee->contract()->forceDelete();
        $employee->advances()->delete();
        $employee->deductionPayments()->delete();
        $employee->allowances()->delete();
        $employee->advancePayments()->delete();
        $employee->deductions()->delete();
        $employee->assignment()->delete();
        $employee->p9()->delete();
        $employee->loans()->delete();
        $employee->hoursWorked()->delete();
        $employee->unitsMade()->delete();
        $employee->daysWorked()->delete();
        $employee->paymentMethod()->delete();
        $employee->payroll()->delete();
        $employee->overtimes()->delete();
        $employee->delete();
        flash('Successfully deleted employee record.', 'success');

        return redirect()->route('employees.index');
    }
}
