<?php

namespace App\Http\Controllers;

use App\Http\Requests\WorkPlanRequest;
use App\Policies\Policy;
use Illuminate\Http\Request;

use App\Http\Requests;
use Payroll\Models\Shift;
use Payroll\Models\WorkPlan;

class WorkPlanController extends Controller
{
    /**
     * @var WorkPlan
     */
    private $workPlan;
    /**
     * @var Shift
     */
    private $shift;

    /**
     * WorkPlanController constructor.
     *
     * @param WorkPlan $workPlan
     * @param Shift    $shift
     */
    public function __construct(WorkPlan $workPlan, Shift $shift)
    {
        $this->workPlan = $workPlan;
        $this->shift = $shift;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Policy::canRead(new WorkPlan());

        return view('modules.structures.workplans.index')
            ->withPlans($this->workPlan->all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Policy::canCreate(new WorkPlan());

        return view('modules.structures.workplans.create')
            ->withShifts($this->shift->all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  WorkPlanRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(WorkPlanRequest $request)
    {
        Policy::canCreate(new WorkPlan());

        $data = $request->all();
        $data['days_of_week'] = implode(',', $data['days_of_week']);
        $this->workPlan->create($data);
        flash('Successfully added new Work Plan', 'success');
        
        return redirect()->route('work-plans.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        Policy::canRead(new WorkPlan());

        return view('modules.structures.workplans.show')
            ->withPlan($this->workPlan->findOrFail($id));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        Policy::canUpdate(new WorkPlan());

        return view('modules.structures.workplans.edit')
            ->withPlan($this->workPlan->findOrFail($id))
            ->withShifts($this->shift->all());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  WorkPlanRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(WorkPlanRequest $request, $id)
    {
        Policy::canUpdate(new WorkPlan());

        $data = $request->all();
        $data['days_of_week'] = implode(',', $data['days_of_week']);
        $plan = $this->workPlan->findOrFail($id);
        $plan->fill($data)->save();
        flash('Successfully edited Work Plan', 'success');

        return redirect()->route('work-plans.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Policy::canDelete(new WorkPlan());

        $plan = $this->workPlan->findOrFail($id);

        if ($plan->assignment->count() > 0) {
            return redirect()->back()
                ->withErrors(
                    [
                        'message' => '<strong>Whoops!</strong><br> There are <strong>Employees</strong> currently assigned to this item. First unassign them then delete the item'
                    ]
                );
        }

        $plan->delete();
        flash('Successfully deleted Work Plan', 'success');

        return redirect()->route('work-plans.index');
    }
}
