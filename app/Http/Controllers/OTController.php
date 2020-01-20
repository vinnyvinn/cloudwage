<?php

namespace App\Http\Controllers;

use App\General;
use Illuminate\Http\Request;

use App\Http\Requests;
use Payroll\Models\Department;
use Payroll\Models\Employee;
use Carbon\Carbon;
use App\OT;

class OTController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('modules.overtime.index', [
            'overtimes' => OT::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('modules.overtime.create', [
            'employees' => Employee::all(),
            "general" => General::first()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //

        $employee = Employee::findorFail($request->employee_id);
        $payroll_date = Carbon::parse('01-' . $request->get('payroll_date'))->endOfMonth()->setTime(0, 0);
        $exists = OT::where('employee_id', $request->employee_id)
                        ->where('payroll_date', $payroll_date)->first();

        if($exists != null){

            flash(' '. $payroll_date->format('M-Y') .' '.'overtime  hours for the employee already exists kindly delete first to add again', 'warning');
            return redirect()->route('overtime.index');
        }
        $salary = $employee->contract->first()->current_basic_salary;
        $hrs = General::first()->hours;
        $days = General::first()->days;

        $daily_rate = $salary/$days;

        $hrly_rate = $daily_rate/$hrs;


        $ot_1_amount = $hrly_rate * $request->ot_1_rate * $request->ot_1_hrs;


        $ot_2_amount = $hrly_rate * $request->ot_2_rate * $request->ot_2_hrs;
        $amount = $ot_1_amount + $ot_2_amount;

        $data = $request->all();

        $data['payroll_date'] = $payroll_date->format('Y-m-d');
        if($data['amount'] != 0){
            $data['ot_1_amount'] = $data['amount'];
        }else{
            $data['ot_1_amount'] = $ot_1_amount;
        }

        $data['ot_2_amount'] = $ot_2_amount;

        if($data['amount'] == 0)
        {
            $data['amount'] = $amount;
        }


        OT::create($data);

        flash('You successfully created an overtime record', 'success');

        return redirect()->route('overtime.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
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
        $ot = OT::findorFail($id);
        if($ot->finalized == 1){
            flash('Sorry you cannot delete an overtime that has already been captured in the payroll', 'warning');
            return redirect()->route('overtime.index');
        }

        $ot->delete();

        flash('Succesfully deleted the overtime', 'success');
        return redirect()->route('overtime.index');


    }
}
