<?php

namespace App\Http\Controllers;

use App\Policies\Policy;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use Payroll\Models\Employee;
use Payroll\Models\EmployeeContract;
use Payroll\Models\EmployeeType;
use Payroll\Models\PayGrade;

class EmployeeContractsController extends Controller
{
    /**
     * @var EmployeeContract
     */
    private $contract;

    /**
     * EmployeeContractsController constructor.
     *
     * @param EmployeeContract $contract
     */
    public function __construct(EmployeeContract $contract)
    {
        $this->contract = $contract;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Policy::canRead(new EmployeeContract());

        return view('modules.employees.contracts.index')
            ->withContracts($this->contract->with(['employeeType', 'employee', 'payGrade'])->get());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Policy::canCreate(new EmployeeContract());
        return view('modules.employees.contracts.create')
            ->withEmployees(Employee::all())
            ->withEmployeeType(EmployeeType::all())
            ->withPayGrades(PayGrade::all())
            ->withContracts($this->contract->with(['employeeType', 'employee', 'payGrade'])->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Policy::canCreate(new EmployeeContract());
        $data = $request->all();
        $data['start_date'] = Carbon::parse($data['start_date']);
        $data['end_date'] = Carbon::parse($data['end_date']);

        if ($data['end_date']->lt($data['start_date'])) {
            flash('Sorry, the contract end date is before the start date', 'error');

            return redirect()->back()->withInput();
        }

        $existing = $this->contract
            ->whereEmployeeId($data['employee_id'])
            ->where('end_date', '>', $data['start_date'])
            ->get();

        if ($existing->count() > 0) {
            flash('Sorry, the employee has a contract that ends on ' .
                $existing->first()->end_date->format('d F Y') .
                '. Please choose a start date later than that,', 'error');

            return redirect()->back()->withInput();
        }

        $this->contract->create($data);
        flash('Successfully created new contract.', 'success');
        
        return redirect()->route('contracts.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        Policy::canRead(new EmployeeContract());
        $contract = $this->contract->with(['employeeType', 'employee', 'payGrade', 'currency'])
            ->findOrFail($id);
        
        return view('modules.employees.contracts.show')

            ->withContract($contract);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        Policy::canUpdate(new EmployeeContract());
        $contract = $this->contract->with(['employee'])->findOrFail($id);

        return view('modules.employees.contracts.edit')
            ->withContract($contract)
            ->withEmployeeType(EmployeeType::all())
            ->withPayGrades(PayGrade::all());
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
        Policy::canUpdate(new EmployeeContract());

        $contract = $this->contract->findOrFail($id);
        $data = $request->all();

        $data['start_date'] = Carbon::parse($data['start_date']);
        $data['end_date'] = Carbon::parse($data['end_date']);
        if($contract->employee->payment_structure_id != $data['payment_structure_id'])
        {
            $employee = $contract->employee;
            $employee->payment_structure_id = $data['payment_structure_id'];
            $employee->save();
        }
        $contract->fill($data);
        $contract->save();
        flash('Successfully edited contract details.', 'success');

        return redirect()->route('contracts.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Policy::canDelete(new EmployeeContract());
        $contract = $this->contract->findOrFail($id);
        $contract->forceDelete();
        flash('Successfully deleted contract.', 'success');

        return redirect()->route('contracts.index');
    }
}
