<?php

namespace App\Http\Controllers;

use App\Policies\Policy;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Str;
use Payroll\Factories\HTMLElementsFactory;
use Payroll\Models\Allowance;
use Payroll\Models\CompanyProfile;
use Payroll\Models\Employee;
use Payroll\Models\EmployeeAllowance;
use Payroll\Parsers\BulkAssigner;
use Payroll\Parsers\ModelFilter;

class EmployeeAllowancesController extends Controller
{
    const FIELD_NAME = 'allowance_amount';

    /**
     * @var EmployeeAllowance
     */
    private $employeeAllowance;

    /**
     * EmployeeAllowancesController constructor.
     *
     * @param EmployeeAllowance $employeeAllowance
     */
    public function __construct(EmployeeAllowance $employeeAllowance)
    {
        $this->employeeAllowance = $employeeAllowance;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request      $request
     *
     * @param ModelFilter  $filter
     *
     * @param BulkAssigner $assigner
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, ModelFilter $filter, BulkAssigner $assigner)
    {
        if (! $request->has('allowanceId')) {
            abort(404);
        }

        Policy::canCreate(new Employee());
        $allowance = Allowance::findOrFail($request->get('allowanceId'));

        $allowanceId = $request->allowanceId;
        $employees = $employees = $filter->filter(new Employee())
            ->usingKey('id')
            ->by($this->employeeAllowance)
            ->usingColumn('employee_id')
            ->whereAllowanceId($allowanceId)
            ->get();

        $requiredFields [] = [
            'name' => self::FIELD_NAME,
            'type' => HTMLElementsFactory::TEXT
        ];

        if ($allowance->type == 'rate') {
            $requiredFields [0] = [
                'name' => self::FIELD_NAME,
                'type' => HTMLElementsFactory::CHECKBOX
            ];
        }

        $employees->each(function ($item, $key) use ($employees) {
            $employees[$key] = collect($item)->only([
                'id', 'payroll_number', 'first_name', 'last_name', 'identification_number'
            ]);
        });

        return $assigner->withRows($employees)
            ->withRequiredFields($requiredFields)
            ->withAssignTo($allowanceId)
            ->withFormAction(route('employee-allowances.store'))
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
            return Str::startsWith($key, self::FIELD_NAME) && $value != '';
        });

        $currency = CompanyProfile::first()->currency_id;
        $now = Carbon::now();
        $insert = array();

        foreach ($fields as $key => $value) {
            $amount = $value;
            if ($value == 'on') {
                $amount = 0;
            }
            $insert [] = [
                'employee_id' => substr($key, strlen(self::FIELD_NAME)),
                'allowance_id' => $data['assignment_id'],
                'currency_id' => $currency,
                'amount' => $amount,
                'created_at' => $now,
                'updated_at' => $now
            ];
        }

        $this->employeeAllowance->insert($insert);

        flash('Successfully assigned allowance to employees.', 'success');
        return redirect()->route('allowances.show', $data['assignment_id']);
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

        $allowance = $this->employeeAllowance->with(['employee', 'allowance'])->findOrFail($id);

        return view('modules.employees.allowances.edit')
            ->withRoute('employee-allowances')
            ->withKeyItem('allowance')
            ->withFields(['amount'])
            ->withRecord($allowance);
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

        $allowance = $this->employeeAllowance->findOrFail($id);
        $allowance->fill($request->all());
        $allowance->save();
        flash('Successfully edited allowance details.', 'success');

        return redirect()->route('allowances.show', $allowance->allowance_id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int    $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Policy::canDelete(new Employee());

        $this->employeeAllowance
            ->whereId($id)
            ->delete();
        flash('Successfully removed employee from allowance records', 'info');

        return redirect()->back();
    }
}
