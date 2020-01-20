<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Requests\EmployeeTypeRequest;
use App\Policies\Policy;
use Payroll\Models\EmployeeType;
use Payroll\Models\PaymentStructure;

class EmployeeTypesController extends Controller
{

    protected $employee_type;

    protected $paymentStructure;

    /**
     * EmployeeTypesController constructor.
     *
     * @param EmployeeType     $employee_type
     * @param PaymentStructure $paymentStructure
     *
     */
    public function __construct(EmployeeType $employee_type, PaymentStructure $paymentStructure)
    {
        $this->employee_type = $employee_type;
        $this->paymentStructure = $paymentStructure;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Policy::canRead(new EmployeeType());
        return view('modules.employees.types.index')
            ->withTypes($this->employee_type->all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Policy::canCreate(new EmployeeType());
        $paymentStructure = $this->paymentStructure->all();

        if (count($paymentStructure) < 1) {
            return redirect()->route('employee-types.index')
                ->withErrors(['message' => '<strong>Whoops!</strong><br> You first need to add a <strong>Payment Structure</strong> before adding an employee type']);
        }

        return view('modules.employees.types.create')
            ->withStructures($paymentStructure);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  EmployeeTypeRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(EmployeeTypeRequest $request)
    {
        Policy::canCreate(new EmployeeType());
        $this->employee_type->create($request->only(['name', 'description', 'payment_structure_id']));
        flash('Successfully added new Employee Type', 'success');

        return redirect()->route('employee-types.index');
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
        Policy::canRead(new EmployeeType());
        $type = $this->employee_type->findOrFail($id);

        return view('modules.employees.types.show')
            ->withType($type);
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
        Policy::canUpdate(new EmployeeType());
        $type = $this->employee_type->findOrFail($id);
        $paymentStructure = $this->paymentStructure->all();

        return view('modules.employees.types.edit')
            ->withType($type)
            ->withStructures($paymentStructure);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  EmployeeTypeRequest $request
     * @param  int                 $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(EmployeeTypeRequest $request, $id)
    {
        Policy::canUpdate(new EmployeeType());
        $type = $this->employee_type->findOrFail($id);
        $type->fill($request->all())->save();
        flash('Successfully edited Employee Type', 'success');

        return redirect()->route('employee-types.index');
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
        Policy::canDelete(new EmployeeType());
        $type = $this->employee_type->findOrFail($id);

        if (count($type->employeeTypes) > 0) {
            return redirect()->back()
                ->withErrors(
                    [
                        'message' => '<strong>Whoops!</strong><br> There are <strong>Employees</strong> currently assigned to this item. First unassign them then delete the item'
                    ]
                );
        }

        $type->delete();
        flash('Successfully deleted Employee Type', 'success');

        return redirect()->route('employee-types.index');
    }
}
