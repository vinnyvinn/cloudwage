<?php

namespace App\Http\Controllers;

use App\Http\Requests\DepartmentRequest;
use App\Http\Requests\ReportRequest;
use App\Policies\Policy;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Payroll\Models\Assignment;
use Payroll\Models\Branches;
use Payroll\Models\CompanyProfile;
use Payroll\Models\Department;
use Payroll\Models\Employee;
use Payroll\Parsers\DocumentGenerator;

class DepartmentsController extends Controller
{
    protected $department;

    protected $request;
    /**
     * @var Branches
     */
    private $branches;

    /**
     * DepartmentsController constructor.
     *
     * @param Department $department
     * @param Request    $request
     * @param Branches   $branches
     */
    public function __construct(Department $department, Request $request, Branches $branches)
    {
        $this->department = $department;
        $this->request = $request;
        $this->branches = $branches;
    }

    private function generateView($view)
    {
        return view($this->request->ajax() ? 'ajax.' . $view : $view);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Policy::canRead(new Department());

        return $this->generateView('modules.company.departments.index')
            ->withBranches($this->branches->with(['departments'])->get());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        Policy::canCreate(new Department());

        if ($request->has('type')) {
            return $this->generateView('modules.company.departments.createDepartment')
                ->withBranches($this->branches->all());
        }

        return $this->generateView('modules.company.departments.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Policy::canCreate(new Department());

        if ($request->get('type') == 'branch') {
            $this->branches->create($request->all());
            flash('Successfully added new branch', 'success');

            return redirect()->route('departments.index');
        }

        $details = $request->all();
        if ($details['parent_id'] == "null") {
            $details['parent_id'] = null;
        }

        $this->department->create($details);

        flash('Successfully added new department', 'success');

        return redirect()->route('departments.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int    $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        Policy::canRead(new Department());

        if (! $this->request->has('type')) {
            return redirect()->route('departments.index');
        }

        switch ($this->request->get('type')) {
            case 'branch':
                $branch = $this->branches
                    ->with(['departments'])
                    ->findOrFail($id);

                return $this->generateView('modules.company.departments.departments')
                    ->withDepartments($branch->departments);
                break;
            case 'department':
                $department = $this->department->with(['branch'])->findOrFail($id);
                $assignments = Assignment::with('employee')
                    ->whereDepartmentId($department->id)
                    ->get();

                return $this->generateView('modules.company.departments.show')
                    ->withDepartment($department)
                    ->withAssignments($assignments);
                break;
            default:
                return redirect()->route('departments.index');
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int    $id
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Request $request)
    {
        Policy::canUpdate(new Department());

        if ($request->get('type') == 'branch') {
            $branch = $this->branches->findOrFail($id);

            return $this->generateView('modules.company.departments.edit')
                ->withBranch($branch);
        }

        $department = $this->department->findOrFail($id);

        return $this->generateView('modules.company.departments.editDepartment')
            ->withBranches($this->branches->all())
            ->withDepartment($department);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function update(Request $request, $id)
    {
        Policy::canUpdate(new Department());

        if ($request->get('type') == 'branch') {
            $branch = $this->branches->findOrFail($id);
            $branch->fill($request->all());
            $branch->save();
            flash('Successfully updated branch details.', 'success');

            return redirect()->route('departments.index');
        }

        $details = $request->all();
        if ($details['parent_id'] == "null") {
            $details['parent_id'] = null;
        }

        $department = $this->department->findOrFail($id);
        $department->fill($details);
        $department->save();
        flash('Successfully updated department details.', 'success');

        return redirect()->route('departments.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param  int    $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        Policy::canDelete(new Department());

        if ($request->get('type') == 'branch') {
            $branch = $this->branches->findOrFail($id);
            $branch->departments()->delete();
            $branch->delete();
            flash('Successfully deleted branch', 'info');

            return redirect()->route('departments.index');
        }
        $department = $this->department->findOrFail($id);
        $department->delete();

        flash('Successfully deleted department', 'info');
        return redirect()->route('departments.index');
    }

    public function generate($department, DocumentGenerator $generator)
    {
        return $generator->withFormAction(route('departments.pdfs'))
            ->withModuleId(Department::MODULE_ID)
            ->withItemId($department)
            ->withModel(Employee::class)
            ->view();
    }

    public function getPDF(ReportRequest $request, DocumentGenerator $generator)
    {
        Policy::canView(new Department());

        $department = $this->department->findOrFail($request->get('item_id'));
        $assignments = Assignment::with('employee')
            ->whereDepartmentId($department->id)
            ->get();
        $rows = collect();
        foreach ($assignments as $assignment) {
            $rows->push($assignment->employee);
        }

        $generator = $generator->prepare($request);
        $document = $generator->withRows($rows)
            ->render();

        return $document;
    }
}
