<?php

namespace App\Http\Controllers;

use App\Policies\Policy;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Str;
use Payroll\Factories\HTMLElementsFactory;
use Payroll\Models\Deduction;
use Payroll\Models\Employee;
use Payroll\Models\EmployeeDeduction;
use Payroll\Parsers\BulkAssigner;
use Payroll\Parsers\ModelFilter;

class EmployeeDeductionsController extends Controller
{

    const FIELD1_NAME = 'deduction_number';
    const FIELD2_NAME = 'deduction_amount';
    /**
     * @var EmployeeDeduction
     */
    private $deduction;

    /**
     * EmployeeDeductionsController constructor.
     *
     * @param EmployeeDeduction $deduction
     */
    public function __construct(EmployeeDeduction $deduction)
    {
        $this->deduction = $deduction;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request      $request
     * @param ModelFilter  $filter
     * @param BulkAssigner $assigner
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, ModelFilter $filter, BulkAssigner $assigner)
    {
        if (! $request->has('deduction')) {
            abort(404);
        }
        Policy::canCreate(new Employee());

        $deductionId = $request->deduction;
        $deduction = Deduction::findOrFail($deductionId);

        $employees = $employees = $filter->filter(new Employee())
            ->usingKey('id')
            ->by($this->deduction)
            ->usingColumn('employee_id')
            ->whereDeductionId($deductionId)
            ->get();

        $requiredFields = array();

        if ($deduction->type == 'per_employee') {
            $requiredFields [] = [
                'name' => self::FIELD2_NAME,
                'type' => HTMLElementsFactory::TEXT
            ];
        }

        $requiredFields [] = [
            'name' => self::FIELD1_NAME,
            'type' => HTMLElementsFactory::TEXT
        ];

        $employees->each(function ($item, $key) use ($employees) {
            $employees[$key] = collect($item)->only([
                'id', 'payroll_number', 'first_name', 'last_name', 'identification_number'
            ]);
        });

        return $assigner->withRows($employees)
            ->withRequiredFields($requiredFields)
            ->withAssignTo($deductionId)
            ->withFormAction(route('employee-deductions.store'))
            ->getForm();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Policy::canCreate(new Employee());

        $data = collect($request->all());
        $fields = $data->filter(function ($value, $key) {
            return (Str::startsWith($key, self::FIELD1_NAME) ||
                Str::startsWith($key, self::FIELD2_NAME)) && $value != '';
        });

        $deduction = Deduction::findOrFail($data['assignment_id']);

        $now = Carbon::now();
        $insert = array();

        foreach ($fields as $key => $value) {
            $employeeId = substr($key, strlen(self::FIELD1_NAME));
            if ($deduction->type != 'per_employee') {
                $insert [] = [
                    'employee_id' => $employeeId,
                    'deduction_id' => $data['assignment_id'],
                    'deduction_number' => $data[self::FIELD1_NAME . $employeeId],
                    'amount' => 0,
                    'created_at' => $now,
                    'updated_at' => $now
                ];

                continue;
            }


            if (Str::startsWith($key, self::FIELD2_NAME)) {
                continue;
            }

            $insert [] = [
                'employee_id' => $employeeId,
                'deduction_id' => $data['assignment_id'],
                'deduction_number' => $data[self::FIELD1_NAME . $employeeId],
                'amount' => $data[self::FIELD2_NAME . $employeeId],
                'created_at' => $now,
                'updated_at' => $now
            ];
        }

        $this->deduction->insert($insert);

        flash('Successfully assigned deduction to employees.', 'success');
        return redirect()->route('deductions.show', $data['assignment_id']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        Policy::canUpdate(new Employee());
        $deduction = $this->deduction->with(['employee', 'deduction'])->findOrFail($id);

        return view('modules.employees.allowances.edit')
            ->withRoute('employee-deductions')
            ->withKeyItem('deduction')
            ->withFields(['amount', 'deduction_number'])
            ->withRecord($deduction);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        Policy::canUpdate(new Employee());
        
        $deduction = $this->deduction->findOrFail($id);
        $deduction->fill($request->all());
        $deduction->save();
        flash('Successfully edited deduction details.', 'success');

        return redirect()->route('deductions.show', $deduction->deduction_id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Policy::canDelete(new Employee());
        
        $this->deduction
            ->whereId($id)
            ->delete();
        flash('Successfully removed employee from deduction records', 'info');

        return redirect()->back();
    }
}
