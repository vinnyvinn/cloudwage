<?php

namespace App\Http\Controllers;

use App\Http\Requests\HolidayRequests;
use App\Policies\Policy;
use Illuminate\Http\Request;

use App\Http\Requests;
use Payroll\Models\Holiday;

class HolidaysController extends Controller
{
    /**
     * @var Holiday
     */
    private $holiday;

    /**
     * HolidaysController constructor.
     *
     * @param Holiday $holiday
     */
    public function __construct(Holiday $holiday)
    {
        $this->holiday = $holiday;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Policy::canRead(new Holiday());

        return view('modules.structures.holidays.index')
            ->withHolidays($this->holiday->all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Policy::canCreate(new Holiday());

        return view('modules.structures.holidays.create')
            ->withHolidays($this->holiday->all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  HolidayRequests  $request
     * @return \Illuminate\Http\Response
     */
    public function store(HolidayRequests $request)
    {
        Policy::canCreate(new Holiday());

        $this->holiday->create($request->all());
        flash('Successfully added a holiday', 'success');

        return redirect()->route('holidays.create');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        Policy::canUpdate(new Holiday());

        $holiday = $this->holiday->findOrFail($id);

        return view('modules.structures.holidays.edit')
            ->withHoliday($holiday);
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
        Policy::canUpdate(new Holiday());

        $holiday = $this->holiday->findOrFail($id);
        $holiday->name = $request->get('name');
        $holiday->save();
        flash('Successfully edited holiday.', 'success');

        return redirect()->route('holidays.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Policy::canDelete(new Holiday());

        $holiday = $this->holiday->findOrFail($id);
        $holiday->delete();
        flash('Successfully deleted holiday.', 'success');

        return redirect()->route('holidays.index');
    }
}
