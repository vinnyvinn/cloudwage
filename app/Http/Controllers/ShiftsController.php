<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShiftsRequest;
use App\Policies\Policy;
use Illuminate\Http\Request;

use App\Http\Requests;
use Payroll\Models\Shift;

class ShiftsController extends Controller
{

    protected $shift;
    /**
     * @var Request
     */
    private $request;

    /**
     * ShiftsController constructor.
     *
     * @param Shift   $shift
     * @param Request $request
     */
    public function __construct(Shift $shift, Request $request)
    {
        $this->shift = $shift;
        $this->request = $request;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Policy::canRead(new Shift());

        return view('modules.structures.shifts.index')
            ->withShifts($this->shift->all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Policy::canCreate(new Shift());

        return view('modules.structures.shifts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  ShiftsRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ShiftsRequest $request)
    {
        Policy::canCreate(new Shift());

        $data = $request->all();
        $data['is_overnight'] = $request->has('is_overnight');
        $this->shift->create($data);

        flash('Successfully created a new shift', 'success');
        return redirect()->route('shifts.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        Policy::canRead(new Shift());

        return view('modules.structures.shifts.show')
            ->withShift($this->shift->findOrFail($id));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        Policy::canUpdate(new Shift());

        return view('modules.structures.shifts.edit')
            ->withShift($this->shift->findOrFail($id));
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
        Policy::canUpdate(new Shift());
        $shift = $this->shift->findOrFail($id);

        $data = $request->all();
        $data['is_overnight'] = $request->has('is_overnight');
        $shift->fill($data);
        $shift->save();

        flash('Successfully edited shift', 'success');
        return redirect()->route('shifts.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Policy::canDelete(new Shift());
        $shift = $this->shift->findOrFail($id);

        if (count($shift->workplans) > 0) {
            return redirect()->back()
                ->withErrors(
                    [
                        'message' =>
                            '<strong>Whoops!</strong><br> There are <strong>Work Plans</strong> currently assigned to this item. First unassign them then delete the item'
                    ]
                );
        }

        $shift->delete();
        flash('Successfully deleted Shift', 'success');

        return redirect()->route('shifts.index');
    }
}
