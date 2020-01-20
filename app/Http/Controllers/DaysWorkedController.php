<?php

namespace App\Http\Controllers;

use App\Policies\Policy;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Str;
use Payroll\Factories\HTMLElementsFactory;
use Payroll\Models\DaysWorked;
use Payroll\Models\Employee;
use Payroll\Models\PaymentStructure;
use Payroll\Parsers\BulkAssigner;
use Payroll\Parsers\ModelFilter;
use Payroll\Parsers\TimeAttendanceHandler;

class DaysWorkedController extends Controller
{
    const FIELD_NAME = 'number_of_days';

    /**
     * @var DaysWorked
     */
    private $daysWorked;

    /**
     * DaysWorkedController constructor.
     *
     * @param DaysWorked $daysWorked
     */
    public function __construct(DaysWorked $daysWorked)
    {
        $this->daysWorked = $daysWorked;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Policy::canRead(new DaysWorked());

        $unique = $this->daysWorked->orderBy('for_month', 'DESC')->groupBy('for_month')->get();

        return view('modules.attendance.index')
            ->withTitle('Days Worked')
            ->withRoute('worked')
            ->withDaysWorked($unique);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param TimeAttendanceHandler $attendanceHandler
     * @param BulkAssigner          $assigner
     *
     * @return \Illuminate\Http\Response
     */
    public function create(TimeAttendanceHandler $attendanceHandler, BulkAssigner $assigner)
    {
        Policy::canCreate(new DaysWorked());

        $todaysMonth = Carbon::now()->endOfMonth();
        $employees = $attendanceHandler->getEmployees($this->daysWorked, 'Day');
        if (!$employees) {
            return redirect()->back();
        }

        $requiredFields [] = [
            'name' => self::FIELD_NAME,
            'type' => HTMLElementsFactory::NUMERIC
        ];

        $employees->each(function ($item, $key) use ($employees) {
            $employees[$key] = collect($item)->only([
                'id', 'payroll_number', 'first_name', 'last_name', 'identification_number'
            ]);
        });

//        if ($employees->count() == 0) {
//            flash('You have already entered the days worked for the month.', 'error');
//
//            return redirect()->back();
//        }

        return $assigner->withRows($employees)
            ->withRequiredFields($requiredFields)
            ->withAssignTo($todaysMonth)
            ->withFormAction(route('worked.store'))
            ->getForm();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param TimeAttendanceHandler     $attendanceHandler
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, TimeAttendanceHandler $attendanceHandler)
    {
        Policy::canCreate(new DaysWorked());

        $data = collect($request->all());


//        dd(Carbon::parse('01-'.$data['for_month'])->endOfMonth());
        $insert = $attendanceHandler->processBulk($data, static::FIELD_NAME, 'days_worked');
        $this->daysWorked->insert($insert);
        flash('Successfully processed the days worked for employees.', 'success');

        return redirect()->route('worked.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        Policy::canRead(new DaysWorked());

        $daysWorked = $this->daysWorked->with(['employee']);
        if (count(explode('-', $id)) > 1) {
            $daysWorked = $daysWorked
                ->where('for_month', '>=', Carbon::parse('01-' . $id))
                ->where('for_month', '<=', Carbon::parse('01-' . $id)->endOfMonth())
                ->get();

            return view('modules.attendance.show')
                ->withRoute('worked')
                ->withField('days_worked')
                ->withTitle(Carbon::parse('01-' . $id)->format('F Y'))
                ->withAttendance($daysWorked);
        }

        $daysWorked = $daysWorked
            ->where('for_month', '>=', Carbon::parse('01-01-' . $id))
            ->where('for_month', '<=', Carbon::parse('31-12-' . $id))
            ->get();

        return view('modules.attendance.show')
            ->withTitle(Carbon::parse('01-01-' . $id)->format('Y'))
            ->withField('days_worked')
            ->withRoute('worked')
            ->withAttendance($daysWorked);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        Policy::canUpdate(new DaysWorked());

        $daysWorked = $this->daysWorked->with(['employee'])->findOrFail($id);

        return view('modules.attendance.edit')
            ->withTitle(Carbon::parse('01-01-' . $id)->format('Y'))
            ->withField('days_worked')
            ->withRoute('worked')
            ->withAttendance($daysWorked);
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
        Policy::canUpdate(new DaysWorked());

        $attendance = $this->daysWorked->findOrFail($id);
        $attendance->update([
            'days_worked' => $request->get('days_worked')
        ]);
        flash('Successfully edited the days worked', 'success');

        return redirect()->route('worked.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Policy::canDelete(new DaysWorked());

        $this->daysWorked->findOrFail($id)->delete();
        flash('Successfully deleted the days worked', 'success');

        return redirect()->route('worked.index');
    }
}
