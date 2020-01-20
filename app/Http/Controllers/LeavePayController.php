<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Payroll\Models\Employee;
use Carbon\Carbon;
use App\SpecialAllowance;
use Payroll\Models\Payroll;
use Payroll\Repositories\PolicyRepository;

use App\Http\Requests;

class LeavePayController extends Controller
{
    private $specialAllowance;




    public function __construct(SpecialAllowance $specialAllowance)
    {
        $this->specialAllowance = $specialAllowance;

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

//        $allowances = $this->specialAllowance->with('employee')->orderBy('for_month')->get()->unique('for_month');
//
//            foreach ($allowances as $allowance){
//                dd($allowance->for_month);
//            }

        return view('modules.special-allowances.leave-pay.index', [
            'allowances' =>  $this->specialAllowance->with('employee')->where('type', 2)
                ->orderBy('for_month')->get()->unique('for_month')
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

        return view('modules.special-allowances.leave-pay.create', [
            'employees' => Employee::with('contract')->get(),

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
//        dd($request->get('for_month'));

        $payrollDate = Carbon::parse('01-' . $request->get('for_month'))->endOfMonth()->setTime(0, 0);

        $daysoff = SpecialAllowance::where('employee_id', $request->employee_id)
            ->where('for_month', $payrollDate)
            ->where('type', 2)->first();


        if($daysoff != null)
        {
            flash('You already have an entry for this employee this month', 'error');
            return redirect()->back();
        }
        $employee = Employee::find($request->employee_id);
        $contracts = $employee->contract->reject(function ($value) use ($payrollDate) {
            return $payrollDate->lte($value->start_date) || $payrollDate->gte($value->end_date);
        })->sortBy('end_date');

        if ($contracts->count() < 1) {
            return false;
        }

        $contract = $contracts->first();
        $salary = $contract->current_basic_salary;
        $WorkingDays = PolicyRepository::get(Payroll::MODULE_ID, Payroll::NUMBER_OF_DAYS);




        $data = $request->all();
        $data['for_month'] = $payrollDate;
        $data['type'] = 2;
        $data['name'] = 'Leave ';
        $data['rate'] = $salary / $WorkingDays;

        $data['total'] = $data['units'] * $data['rate'];

        SpecialAllowance::create($data);


        flash('leave Pay created successfully', 'success');
        return redirect()->route('leave-pay.index');


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

        $advance = $this->specialAllowance;
        if (count(explode('-', $id)) > 1) {
            $advances = $advance
                ->where('for_month', '=', Carbon::parse('1-' . $id)->endOfMonth()->format('Y-m-d'))
                ->where('type', 2)
                ->get();

            return view('modules.special-allowances.leave-pay.show')
                ->withTitle(Carbon::parse('30-' . $id)->format('F Y'))
                ->withAdvances($advances);
        }

        $advances = $advance
            ->where('for_month', '>=', Carbon::parse('01-01-' . $id)->format('Y-m-d'))
            ->where('for_month', '<=', Carbon::parse('31-12-' . $id)->format('Y-m-d'))
            ->where('type', 2)
            ->get();

        return view('modules.special-allowances.leave-pay.show')
            ->withTitle(Carbon::parse('01-01-' . $id)->format('Y'))
            ->withAdvances($advances);
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
        $advance = $this->specialAllowance->findOrFail($id);
        $advance->delete();
        flash('Successfully deleted Leave Pay', 'success');

        return redirect()->route('leave-pay.index');
    }
}
