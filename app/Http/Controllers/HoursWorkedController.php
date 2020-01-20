<?php

namespace App\Http\Controllers;

use App\Policies\Policy;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use Payroll\Factories\HTMLElementsFactory;
use Payroll\Models\HoursWorked;
use Payroll\Parsers\BulkAssigner;
use Payroll\Parsers\TimeAttendanceHandler;

class HoursWorkedController extends Controller
{
    const FIELD_NAME = 'number_of_hours_worked';

    /**
     * @var HoursWorked
     */
    private $hoursWorked;

    /**
     * HoursWorkedController constructor.
     *
     * @param HoursWorked $hoursWorked
     */
    public function __construct(HoursWorked $hoursWorked)
    {
        $this->hoursWorked = $hoursWorked;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Policy::canRead(new HoursWorked());
        $unique = $this->hoursWorked->orderBy('for_month', 'DESC')->groupBy('for_month')->get();

        return view('modules.attendance.index')
            ->withTitle('Hours Worked')
            ->withRoute('hours-worked')
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
        Policy::canCreate(new HoursWorked());
        $todaysMonth = Carbon::now()->endOfMonth();
        $employees = $attendanceHandler->getEmployees($this->hoursWorked, 'Hour');
        if (!$employees) {
            return redirect()->back();
        }

        $requiredFields [] = [
            'name' => self::FIELD_NAME,
            'type' => HTMLElementsFactory::TEXT
        ];

        $employees->each(function ($item, $key) use ($employees) {
            $employees[$key] = collect($item)->only([
                'id', 'payroll_number', 'first_name', 'last_name', 'identification_number'
            ]);
        });

        if ($employees->count() == 0) {
            flash('You have already entered the hours worked for the month.', 'error');

            return redirect()->back();
        }

        return $assigner->withRows($employees)
            ->withRequiredFields($requiredFields)
            ->withAssignTo($todaysMonth)
            ->withFormAction(route('hours-worked.store'))
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
        Policy::canCreate(new HoursWorked());
        $data = collect($request->all());

        $insert = $attendanceHandler->processBulk($data, static::FIELD_NAME, 'hours_worked');
        $this->hoursWorked->insert($insert);
        flash('Successfully processed the hours worked for employees.', 'success');

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
        Policy::canRead(new HoursWorked());

        $hoursWorked = $this->hoursWorked->with(['employee']);
        if (count(explode('-', $id)) > 1) {
            $hoursWorked = $hoursWorked
                ->where('for_month', '>=', Carbon::parse('01-' . $id))
                ->where('for_month', '<=', Carbon::parse('01-' . $id)->endOfMonth())
                ->get();

            return view('modules.attendance.show')
                ->withRoute('hours-worked')
                ->withField('hours_worked')
                ->withTitle(Carbon::parse('01-' . $id)->format('F Y'))
                ->withAttendance($hoursWorked);
        }

        $hoursWorked = $hoursWorked
            ->where('for_month', '>=', Carbon::parse('01-01-' . $id))
            ->where('for_month', '<=', Carbon::parse('31-12-' . $id))
            ->get();

        return view('modules.attendance.show')
            ->withTitle(Carbon::parse('01-01-' . $id)->format('Y'))
            ->withField('hours_worked')
            ->withRoute('hours-worked')
            ->withAttendance($hoursWorked);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        Policy::canUpdate(new HoursWorked());
        $hours = $this->hoursWorked->with(['employee'])->findOrFail($id);

        return view('modules.attendance.edit')
            ->withTitle(Carbon::parse('01-01-' . $id)->format('Y'))
            ->withField('hours_worked')
            ->withRoute('hours-worked')
            ->withAttendance($hours);
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
        Policy::canUpdate(new HoursWorked());
        $attendance = $this->hoursWorked->findOrFail($id);
        $attendance->hours_worked = $request->hours_worked;
        $attendance->save();
        flash('Successfully edited the hours worked', 'success');

        return redirect()->route('hours-worked.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Policy::canDelete(new HoursWorked());
        
        $this->hoursWorked->findOrFail($id)->delete();
        flash('Successfully deleted the hours worked', 'success');

        return redirect()->route('hours-worked.index');
    }
}
