<?php

namespace App\Http\Controllers;

use App\Policies\Policy;
use Illuminate\Http\Request;

use App\Http\Requests;
use Payroll\Models\Assignment;
use Payroll\Models\Department;

class EmployeeAssignmentsController extends Controller
{
    /**
     * @var Assignment
     */
    private $assignment;

    /**
     * EmployeeAssignmentsController constructor.
     *
     * @param Assignment $assignment
     */
    public function __construct(Assignment $assignment)
    {
        $this->assignment = $assignment;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Policy::canRead(new Assignment());

        $assignments = $this->assignment
            ->with(['employee', 'department'])
            ->get();

        return view('modules.employees.assignments.index')
            ->withAssignments($assignments);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        Policy::canUpdate(new Assignment());

        $assignment = $this->assignment
            ->with('employee')
            ->findOrFail($id);

        return view('modules.employees.assignments.edit')
            ->withAssignment($assignment)
            ->withDepartments(Department::all());
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
        Policy::canUpdate(new Assignment());

        $assignment = $this->assignment->findOrFail($id);
//          dd($request->get('payment_structure_id'));
        $assignment->fill(['department_id' => $request->get('payment_structure_id')]);
        $assignment->save();

        flash('Successfully updated record', 'success');

        return redirect()->route('assignments.index');
    }
}
