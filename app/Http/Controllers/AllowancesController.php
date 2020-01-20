<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Requests\AllowanceRequest;
use App\Http\Requests\ReportRequest;
use App\Policies\Policy;
use Illuminate\Http\Request;
use Payroll\Models\Allowance;
use Payroll\Models\CompanyProfile;
use Payroll\Models\Employee;
use Payroll\Models\EmployeeAllowance;
use Payroll\Models\Relief;
use Payroll\Parsers\DocumentGenerator;
use Schema;

class AllowancesController extends Controller
{
    protected $allowances;

    protected $relief;

    /**
     * AllowancesController constructor.
     *
     * @param Allowance $allowances
     * @param Relief    $relief
     */
    public function __construct(Allowance $allowances, Relief $relief)
    {
        $this->allowances = $allowances;
        $this->relief = $relief;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Policy::canRead(new Allowance());

        return view('modules.company.allowances.index')
            ->withAllowances($this->allowances->all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Policy::canCreate(new Allowance());
        $defaultCurr = CompanyProfile::first()->currency_id;

        return view('modules.company.allowances.create')
            ->withDefaultCurrency($defaultCurr);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param AllowanceRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function store(AllowanceRequest $request)
    {
        Policy::canCreate(new Allowance());
        if (! $this->validateRelief($request)) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['message' => 'Please enter the relief details']);
        }

        Allowance::create($request->all());

        flash('Successfully added new allowance.', 'success');

        return redirect()->route('allowances.index');
    }

    public function show($id)
    {
        Policy::canRead(new Allowance());
        $allowance = Allowance::with(['employees', 'employeeAllowances', 'relief'])
            ->findOrFail($id);

        return view('modules.company.allowances.show')
            ->withAllowance($allowance)
            ->withEmployees($allowance->employees);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        Policy::canUpdate(new Allowance());
        $allowance = Allowance::with(['relief'])->findOrFail($id);

        return view('modules.company.allowances.edit')
            ->withAllowance($allowance);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param AllowanceRequest $request
     * @param                  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     * @internal param int $id
     */
    public function update(AllowanceRequest $request, $id)
    {
        Policy::canUpdate(new Allowance());
        if (! $this->validateRelief($request)) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['message' => 'Please enter the relief details']);
        }

        Allowance::findOrFail($id)->update($request->all());

        flash('Successfully updated allowance', 'success');

        return redirect()->route('allowances.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Policy::canDelete(new Allowance());
        $allowance = Allowance::findOrFail($id);
        if ($allowance->has_relief == 1) {
            Relief::where('reliefable_id', $id)
                ->where('reliefable_type', 'Allowance')->delete();
        }

        $allowance->employeeAllowances()->delete();
        $allowance->delete();

        flash('Successfully deleted allowance', 'success');

        return redirect()->route('allowances.index');
    }

    public function generate($id, DocumentGenerator $generator)
    {
        Policy::canRead(new Allowance());

        return $generator->withModuleId(Allowance::MODULE_ID)
            ->setColumns($this->getColumns())
            ->withFormAction(route('allowances.document'))
            ->withItemId($id)
            ->view();
    }

    private function getColumns()
    {
        $columns = collect();
        foreach (Schema::getColumnListing((new Allowance())->getTable()) as $column) {
            if ($column == 'created_at' ||
                $column == 'updated_at' ||
                $column == 'deleted_at' ||
                $column == 'currency_id' ||
                $column == 'id'
            ) {
                continue;
            }

            $columns->push($column);
        }

        foreach (Schema::getColumnListing((new EmployeeAllowance())->getTable()) as $column) {
            if ($column == 'created_at' ||
                $column == 'updated_at' ||
                $column == 'deleted_at' ||
                $column == 'id' ||
                $column == 'employee_id' ||
                $column == 'currency_id' ||
                $column == 'allowance_id'
            ) {
                continue;
            }

            $columns->push($column);
        }

        foreach (Schema::getColumnListing((new Employee())->getTable()) as $column) {
            if ($column == 'created_at' ||
                $column == 'updated_at' ||
                $column == 'deleted_at' ||
                $column == 'id' ||
                $column == 'employee_id' ||
                $column == 'currency_id' ||
                $column == 'allowance_id'
            ) {
                continue;
            }

            $columns->push($column);
        }

        return $columns->sort();
    }

    public function getDocument(ReportRequest $request, DocumentGenerator $generator)
    {
        Policy::canRead(new Allowance());
        $allowance = $this->allowances->with(['employees', 'employeeAllowances'])->findOrFail($request->get('item_id'));
        $employeeAllowances = $allowance->employeeAllowances->keyBy('employee_id');

        $rows = collect();
        foreach ($allowance->employees as $employee) {
            foreach ($employeeAllowances->get($employee->id)->toArray() as $key => $value) {
                $employee->$key = $value;
            }
            foreach ($allowance->toArray() as $key => $value) {
                $employee->$key = $value;
            }

            $rows->push($employee);
        }

        $generator = $generator->prepare($request);
        $document = $generator->withRows($rows)
            ->render();

        return $document;
    }

    private function validateRelief(Request $request)
    {
        if ($request->get('has_relief')) {
            if (! $request->has('relief_name') || ! $request->has('relief_amount')) {
                return false;
            }
        }

        return true;
    }
}
