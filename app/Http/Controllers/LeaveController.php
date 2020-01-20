<?php

namespace App\Http\Controllers;

use App\Policies\Policy;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Payroll\Models\Employee;
use Payroll\Models\EmployeeLeave;

class LeaveController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Policy::canRead(new EmployeeLeave());

        return view('modules.leave.index')->with('leaves', EmployeeLeave::with('employee')->get());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Policy::canCreate(new EmployeeLeave());

        return view('modules.leave.create')->with('employees', Employee::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Policy::canCreate(new EmployeeLeave());

        $data = $request->all();

        $currentLeave = EmployeeLeave::where('employee_id', $request->employee_id)
            ->where('start_date', '<=', $request->start_date)
            ->where('end_date', '>=', $request->start_date)
            ->exists();

        if ($currentLeave) {
            return redirect()->back()
                ->withInput(flash('The employee already has a leave within those days', 'error'));
        }

        $data['start_date'] = Carbon::parse($data['start_date']);
        $data['end_date'] = Carbon::parse($data['end_date']);
        $data['days'] = $data['end_date']->diffInDays($data['start_date']) + 1;
        $data['status'] = EmployeeLeave::PENDING;

        EmployeeLeave::create($data);


        flash('Successfully created leave', 'success');

        return redirect()->route('leave.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function total()
    {
        //
        $emps = [];
        $employees = Employee::with('leaves')->get();
       foreach($employees as $employee){
           $leaves = $employee->leaves;
           if(count($leaves) ){
               array_push($emps, $employee);
           }
       }
       return view('modules.leave.total', [
           'employees' => $emps
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        Policy::canUpdate(new EmployeeLeave());

        $leave = EmployeeLeave::findOrFail($id);

        return view('modules.leave.edit')->withInput($leave)
            ->with('employees', Employee::all());
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
        Policy::canUpdate(new EmployeeLeave());
        
        $data = $request->all();
        $data['start_date'] = Carbon::parse($data['start_date']);
        $data['end_date'] = Carbon::parse($data['end_date']);


        $leave = EmployeeLeave::findOrFail($id);

        $leave->update($data);

        flash('Successfully edited the leave', 'success');

        return redirect()->route('leave.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
