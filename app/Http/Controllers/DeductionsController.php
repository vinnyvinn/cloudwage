<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Requests\DeductionRequest;
use App\Http\Requests\ReportRequest;
use App\Policies\Policy;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Payroll\Models\CompanyProfile;
use Payroll\Models\Deduction;
use Payroll\Models\DeductionPayments;
use Payroll\Models\DeductionSlab;
use Payroll\Models\Employee;
use Payroll\Models\EmployeeDeduction;
use Payroll\Models\Relief;
use Payroll\Parsers\DocumentGenerator;
use Schema;

class DeductionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Policy::canRead(new Deduction());
        $currency = CompanyProfile::first()->currency;

        return view('modules.company.deductions.index')
            ->withCurrency($currency->code)
            ->withDeductions(Deduction::all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Policy::canCreate(new Deduction());

        return view('modules.company.deductions.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param DeductionRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function store(DeductionRequest $request)
    {
        Policy::canCreate(new Deduction());

        if (! $this->validateRelief($request)) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['message' => 'Please enter the relief details']);
        }

        Deduction::create($request->all());

        flash('Successfully added new deduction.', 'success');

        return redirect()->route('deductions.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        Policy::canRead(new Deduction());

        return $this->returnViewWithData($id, 'modules.company.deductions.show');
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
        Policy::canUpdate(new Deduction());

        return $this->returnViewWithData($id, 'modules.company.deductions.edit');
    }

    private function returnViewWithData($id, $view)
    {
        $deduction = Deduction::with(['employees', 'employeeDeductions'])
            ->findOrFail($id);
        $currency = CompanyProfile::first()->currency;
        $relief = new Relief;
        if ($deduction->has_relief) {
            $relief = $deduction->relief;
        }

        $slabs = new DeductionSlab;
        if ($deduction->type == 'slab') {
            $slabs = $deduction->slabs()->orderBy('slab_number', 'ASC')->get();
        }

        return view($view)
            ->withCurrency($currency->code)
            ->withDeduction($deduction)
            ->withSlabs($slabs)
            ->withRelief($relief);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param DeductionRequest $request
     * @param  int             $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function update(DeductionRequest $request, $id)
    {
        Policy::canUpdate(new Deduction());

        if (! $this->validateRelief($request)) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['message' => 'Please enter the relief details']);
        }

        Deduction::findOrFail($id)->update($request->all());

        flash('Successfully edited deduction.', 'success');

        return redirect()->route('deductions.index');
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
        Policy::canDelete(new Deduction());
        $deduction = Deduction::findOrFail($id);

        if ($deduction->has_relief) {
            $deduction->relief()->delete();
        }

        if ($deduction->type == 'slab') {
            $deduction->slabs()->delete();
        }
        $deduction->employeeDeductions()->delete();
        $deduction->delete();

        flash('Successfully deleted deduction', 'info');

        return redirect()->route('deductions.index');
    }

    public function report($deductionId)
    {
        $months = DeductionPayments::all()->unique('for_month')->sort();
        $months = $months->each(function ($item, $key) use ($months) {
            $months[$key] = [
                'id' => $item->for_month,
                'value' => Carbon::parse($item->for_month)->format('F Y')
            ];
        });

        return view('smodav.pdf.monthFilter')
            ->withTitle('Deductions')
            ->withRoute(route('deductions.generate', $deductionId))
            ->withMonths($months);
    }

    public function generate($id, DocumentGenerator $generator, Request $request)
    {
        $month = $request->get('month');

        return $generator
            ->withModuleId(Deduction::MODULE_ID)
            ->setColumns($this->getColumns())
            ->withFormAction(route('deductions.document'))
            ->withItemId($id . ',' . $month)
            ->view();
    }

    private function getColumns()
    {
        $columns = collect();
        foreach (Schema::getColumnListing((new Deduction())->getTable()) as $column) {
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

        foreach (Schema::getColumnListing(
            (new EmployeeDeduction())->getTable()) as $column) {
            if ($column == 'created_at' ||
                $column == 'updated_at' ||
                $column == 'deleted_at' ||
                $column == 'id' ||
                $column == 'employee_id' ||
                $column == 'currency_id' ||
                $column == 'deduction_id'
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
                $column == 'deduction_id'
            ) {
                continue;
            }

            $columns->push($column);
        }

        foreach (Schema::getColumnListing(
            (new DeductionPayments())->getTable()) as $column) {
            if ($column == 'created_at' ||
                $column == 'updated_at' ||
                $column == 'deleted_at' ||
                $column == 'id' ||
                $column == 'employee_id' ||
                $column == 'currency_id' ||
                $column == 'deduction_id'
            ) {
                continue;
            }

            $columns->push($column);
        }

        return $columns->sort();
    }

    public function getDocument(ReportRequest $request, DocumentGenerator $generator)
    {
        $item_id = explode(',', $request->get('item_id'));
        $month = Carbon::parse($item_id[1])
            ->endOfMonth()->format('Y-m-d');
        $deduction = Deduction::with(['employees', 'employeeDeductions', 'payments'])
            ->findOrFail($item_id[0]);
        $employeeDeductions = $deduction->employeeDeductions->keyBy('employee_id');
        $deductionPayments = $deduction->payments->whereLoose('for_month', $month);
        $rows = collect();

        if (in_array('for_month', explode(',', $request->get('order')))) {
            $employees = $deduction->employees->keyBy('id');
            foreach ($deduction->payments as $payment) {
                foreach ($deduction->toArray() as $key => $value) {
                    $payment->$key = $value;
                }
                foreach ($employees->get($payment->employee->id)
                    ->toArray() as $key => $value) {
                    $payment->$key = $value;
                }

                $rows->push($payment);
            }

            $generator = $generator->prepare($request);
            $document = $generator->withRows($rows)
                ->render();

            return $document;
        }

        foreach ($deduction->employees as $employee) {
            foreach ($employeeDeductions->get($employee->id)
                ->toArray() as $key => $value) {
                $employee->$key = $value;
            }
            foreach ($deduction->toArray() as $key => $value) {
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
