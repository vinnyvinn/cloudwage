<?php

namespace App\Http\Controllers;

use App\Http\Requests\PayGradesRequest;
use App\Policies\Policy;
use Illuminate\Http\Request;

use App\Http\Requests;
use Payroll\Models\Allowance;
use Payroll\Models\CompanyProfile;
use Payroll\Models\Currency;
use Payroll\Models\Deduction;
use Payroll\Models\PayGrade;
use Payroll\Models\PaymentStructure;

class PayGradesController extends Controller
{
    /**
     * @var PayGrade
     */
    private $payGrade;
    /**
     * @var Allowance
     */
    private $allowance;
    /**
     * @var Deduction
     */
    private $deduction;
    /**
     * @var Currency
     */
    private $currency;

    /**
     * PayGradesController constructor.
     *
     * @param PayGrade  $payGrade
     * @param Allowance $allowance
     * @param Deduction $deduction
     * @param Currency  $currency
     */
    public function __construct(PayGrade $payGrade, Allowance $allowance, Deduction $deduction, Currency $currency)
    {
        $this->payGrade = $payGrade;
        $this->allowance = $allowance;
        $this->deduction = $deduction;
        $this->currency = $currency;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Policy::canRead(new PayGrade());

        return view('modules.structures.grades.index')
            ->withGrades($this->payGrade->all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Policy::canCreate(new PayGrade());

        $payment_structures = PaymentStructure::all();
        $allowances = $this->allowance->all();
        $deductions = $this->deduction->all();

        $message = "";
        if (count($allowances) < 1) {
            $message .= 'Cannot create a new pay grade. Please create<br/><strong>At least ONE ALLOWANCE</strong>';
        }
        if (count($deductions) < 1) {
            if ($message == "") {
                $message .=  'Cannot create a new pay grade. Please create<br/>';
            }
            $message .=  '<strong>At least ONE DEDUCTION</strong>';
        }
        if (count($payment_structures) < 1) {
            if ($message == "") {
                $message .=  'Cannot create a new pay grade. Please create<br/>';
            }
            $message .=  '<strong>At least ONE PAYMENT STRUCTURE</strong>';
        }

        if ($message != "") {
            return redirect()->back()->withErrors(['message' => $message]);
        }

        return view('modules.structures.grades.create')
            ->withDefaultCurrency(CompanyProfile::first()->currency_id)
            ->withCurrencies($this->currency->all())
            ->withAllowances($allowances)
            ->withDeductions($deductions)
            ->withPaymentStructures($payment_structures);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param PayGradesRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PayGradesRequest $request)
    {
        Policy::canCreate(new PayGrade());

        $data = $request->all();

        if ($request->has('default_allowances')) {
            $data['default_allowances'] = implode(",", $data['default_allowances']);
        }

        if ($request->has('default_deductions')) {
            $data['default_deductions'] = implode(",", $data['default_deductions']);
        }
        $this->payGrade->create($data);
        flash('Successfully added a new pay grade', 'success');

        return redirect()->route('grades.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        Policy::canRead(new PayGrade());

        return view('modules.structures.grades.show')
            ->withGrade($this->payGrade->findOrFail($id));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        Policy::canUpdate(new PayGrade());

        $payment_structures = PaymentStructure::all();
        $allowances = $this->allowance->all();
        $deductions = $this->deduction->all();
        $grade = $this->payGrade->findOrFail($id);

        return view('modules.structures.grades.edit')
            ->withGrade($grade)
            ->withCurrencies($this->currency->all())
            ->withAllowances($allowances)
            ->withDeductions($deductions)
            ->withPaymentStructures($payment_structures);
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
        Policy::canUpdate(new PayGrade());

        $data = $request->all();
        $data['default_allowances'] = null;
        $data['default_deductions'] = null;

        if ($request->has('default_allowances')) {
            $data['default_allowances'] = implode(",", $request->get('default_allowances'));
        }

        if ($request->has('default_deductions')) {
            $data['default_deductions'] = implode(",", $request->get('default_deductions'));
        }

        $grade = $this->payGrade->findOrFail($id);
        $grade->fill($data);
        $grade->save();
        flash('Successfully edited pay grade', 'success');

        return redirect()->route('grades.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Policy::canDelete(new PayGrade());

        $grade = $this->payGrade->findOrFail($id);
        if ($grade->contracts->count() > 0) {
            return redirect()->back()
                ->withErrors(
                    [
                        'message' => '<strong>Whoops!</strong><br> There are <strong>Employee Types</strong> currently assigned to this item. First unassign them then delete the item'
                    ]
                );
        }

        $grade->delete();
        flash('Successfully deleted Pay Grade.', 'success');

        return redirect()->route('grades.index');
    }
}
