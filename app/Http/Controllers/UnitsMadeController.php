<?php

namespace App\Http\Controllers;

use App\Policies\Policy;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use Payroll\Factories\HTMLElementsFactory;
use Payroll\Models\UnitsProduced;
use Payroll\Parsers\BulkAssigner;
use Payroll\Parsers\TimeAttendanceHandler;

class UnitsMadeController extends Controller
{
    const FIELD_NAME = 'units_made';

    /**
     * @var UnitsProduced
     */
    private $unitsProduced;

    /**
     * UnitsMadeController constructor.
     *
     * @param UnitsProduced $unitsProduced
     */
    public function __construct(UnitsProduced $unitsProduced)
    {
        $this->unitsProduced = $unitsProduced;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Policy::canRead(new UnitsProduced());
        $unique = $this->unitsProduced->orderBy('for_month', 'DESC')->groupBy('for_month')->get();

        return view('modules.attendance.index')
            ->withTitle('Units Made')
            ->withRoute('units-made')
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
        Policy::canCreate(new UnitsProduced());
        $todaysMonth = Carbon::now()->endOfMonth();
        $employees = $attendanceHandler->getEmployees($this->unitsProduced, 'Unit');
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

        if ($employees->count() == 0) {
            flash('You have already entered the units made for the month.', 'error');

            return redirect()->back();
        }

        return $assigner->withRows($employees)
            ->withRequiredFields($requiredFields)
            ->withAssignTo($todaysMonth)
            ->withFormAction(route('units-made.store'))
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
        Policy::canCreate(new UnitsProduced());

        $data = collect($request->all());
        $insert = $attendanceHandler->processBulk($data, static::FIELD_NAME, 'units_produced');
        $this->unitsProduced->insert($insert);
        flash('Successfully processed the units made for employees.', 'success');

        return redirect()->route('units-made.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        Policy::canRead(new UnitsProduced());
        $unitsProduced = $this->unitsProduced->with(['employee']);
        if (count(explode('-', $id)) > 1) {
            $unitsProduced = $unitsProduced
                ->where('for_month', '>=', Carbon::parse('01-' . $id))
                ->where('for_month', '<=', Carbon::parse('01-' . $id)->endOfMonth())
                ->get();

            return view('modules.attendance.show')
                ->withRoute('units-made')
                ->withField('units_produced')
                ->withTitle(Carbon::parse('01-' . $id)->format('F Y'))
                ->withAttendance($unitsProduced);
        }

        $unitsProduced = $unitsProduced
            ->where('for_month', '>=', Carbon::parse('01-01-' . $id))
            ->where('for_month', '<=', Carbon::parse('31-12-' . $id))
            ->get();

        return view('modules.attendance.show')
            ->withTitle(Carbon::parse('01-01-' . $id)->format('Y'))
            ->withField('units_produced')
            ->withRoute('units-made')
            ->withAttendance($unitsProduced);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        Policy::canUpdate(new UnitsProduced());
        $units = $this->unitsProduced->with(['employee'])->findOrFail($id);

        return view('modules.attendance.edit')
            ->withTitle(Carbon::parse('01-01-' . $id)->format('Y'))
            ->withField('units_produced')
            ->withRoute('units-made')
            ->withAttendance($units);
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
        Policy::canUpdate(new UnitsProduced());
        $attendance = $this->unitsProduced->findOrFail($id);
        $attendance->units_produced = $request->units_produced;
        $attendance->save();
        flash('Successfully edited the units produced', 'success');

        return redirect()->route('units-made.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Policy::canDelete(new UnitsProduced());
        $this->unitsProduced->findOrFail($id)->delete();
        flash('Successfully deleted the units produced', 'success');

        return redirect()->route('units-made.index');
    }
}
