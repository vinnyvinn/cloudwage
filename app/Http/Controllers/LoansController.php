<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoansRequest;
use App\Policies\Policy;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use Payroll\Models\CompanyProfile;
use Payroll\Models\Employee;
use Payroll\Models\Loans;
use Payroll\Parsers\LoanCalculator;

class LoansController extends Controller
{
    /**
     * @var Loans
     */
    private $loans;
    /**
     * @var LoanCalculator
     */
    private $calculator;
    private $currency;

    /**
     * LoansController constructor.
     *
     * @param Loans          $loans
     * @param LoanCalculator $calculator
     */
    public function __construct(Loans $loans, LoanCalculator $calculator)
    {
        $this->loans = $loans;
        $this->calculator = $calculator;
        $this->currency = CompanyProfile::first()->currency;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Policy::canRead(new Loans());
        $loans = $this->loans->with(['employee'])
            ->orderBy('date_processed')
            ->get();
        $toShow = collect();
        foreach ($loans as $loan) {
            $details = new \stdClass();
            $details->date_processed = $loan->date_processed->endOfMonth();
            $toShow->push($details);
        }

        return view('modules.payroll.loans.index')
            ->withCurrency($this->currency)
            ->withLoans($toShow->unique('date_processed'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Policy::canCreate(new Loans());

        return view('modules.payroll.loans.create')
            ->withEmployees(Employee::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param LoansRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(LoansRequest $request)
    {
        Policy::canCreate(new Loans());
//        dd(Carbon::parse('01-'. $request->get('for_month'))->format('Y-m-d'));

        $loanDetails = ($this->calculator->calculate(str_replace(',', '', $request->get('amount')))
            ->atInterest($request->get('rate') / 100)
            ->using($request->get('type'))
            ->per($request->get('iterations'))
            ->forYears($request->get('duration') / 12)->get());

        $loanDetails['employee_id'] = $request->get('employee_id');
        $loanDetails['type'] = $request->get('type');
        $loanDetails['date_processed'] = Carbon::parse('01-'. $request->get('for_month'))->format('Y-m-d');
        $loanDetails['balance'] = $loanDetails['amount_payable'];
        $loanDetails['duration'] = $request->get('duration');
        $loanDetails['installments'] = $loanDetails['balance'] / $loanDetails['duration'];
        $loanDetails['deadline'] = Carbon::now()->addMonths($request->get('duration'));
        $loanDetails['payment_months_made'] = 0;
        $this->loans->create($loanDetails->toArray());
        flash('Successfully processed loan', 'success');

        return redirect()->route('loans.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        Policy::canRead(new Loans());
        $loan = $this->loans->with(['employee']);
        if (count(explode('-', $id)) > 1) {
            $loans = $loan
                ->where('date_processed', '>=', Carbon::parse('01-' . $id)->format('Y-m-d'))
                ->where('date_processed', '<=', Carbon::parse('01-' . $id)->endOfMonth()->format('Y-m-d'))
                ->get();

            return view('modules.payroll.loans.show')
                ->withCurrency($this->currency)
                ->withTitle(Carbon::parse('01-' . $id)->format('F Y'))
                ->withLoans($loans);
        }

        $loans = $loan
            ->where('date_processed', '>=', Carbon::parse('01-01-' . $id)->format('Y-m-d'))
            ->where('date_processed', '<=', Carbon::parse('31-12-' . $id)->format('Y-m-d'))
            ->get();

        return view('modules.payroll.loans.show')
            ->withCurrency($this->currency)
            ->withTitle(Carbon::parse('01-01-' . $id)->format('Y'))
            ->withLoans($loans);
    }

    public function details($loanId)
    {
        Policy::canRead(new Loans());
        $loan = $this->loans->with(['employee'])->findOrFail($loanId);
        
        return view('modules.payroll.loans.details')->withLoan($loan);
    }

    public function edit($loanId)
    {
        Policy::canUpdate(new Loans());
        return view('modules.payroll.loans.edit')
            ->withEmployees(Employee::all())
            ->withLoan($this->loans->findOrFail($loanId));
    }

    public function update(Request $request, $loanId)
    {
        Policy::canUpdate(new Loans());

        $loan = $this->loans->findOrFail($loanId);
        $loanDetails = ($this->calculator->calculate(str_replace(',', '', $request->get('amount')))
            ->atInterest($request->get('rate') / 100)
            ->using($request->get('type'))
            ->per($request->get('iterations'))
            ->forYears($request->get('duration') / 12)->get());

        $loanDetails['employee_id'] = $request->get('employee_id');
        $loanDetails['type'] = $request->get('type');
        $loanDetails['date_processed'] = Carbon::now();
        $loanDetails['balance'] = $loanDetails['amount_payable'];
        $loanDetails['duration'] = $request->get('duration');
        $loanDetails['installments'] = $loanDetails['balance'] / $loanDetails['duration'];
        $loanDetails['deadline'] = Carbon::now()->addMonths($request->get('duration'));
        $loanDetails['payment_months_made'] = 0;
        $loan->fill($loanDetails->toArray());
        $loan->save();
        flash('Successfully edited loan', 'success');

        return redirect()->route('loans.index');
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Policy::canDelete(new Loans());
        $loan = $this->loans->findOrFail($id);
        $loan->payments()->delete();
        $loan->delete();
        flash('Successfully deleted loan', 'success');

        return redirect()->route('loans.index');
    }
}
