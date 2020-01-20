<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Payroll\Models\Payroll;
use Carbon\Carbon;
use \stdClass;

class EftReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $months = Payroll::orderBy('payroll_date', 'DESC')
            ->groupBy('payroll_date')
            ->get()
            ->map(function ($value) {
                return $value->payroll_date;
            });
        return view('modules.reports.eft.select')->with('months', $months);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }
    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }
    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function report(Request $request)
    {
        $month = Carbon::parse($request->get('month'))->endOfMonth()->format('Y-m-d');
        $fields = [];
        $dates = Carbon::parse($request->get('month'))->endOfMonth()->format('M Y');
        $paymentRef = 'Salary'.' '. $dates;
        Payroll::join('employees', 'employees.id', '=', 'payrolls.employee_id')
            ->join('employee_payment_methods', 'employees.id', '=', 'employee_payment_methods.employee_id')
            ->select(['account-name', 'first_name', 'last_name', 'account-number', 'bank-name', 'payrolls.id'])
            ->where('payroll_date', $month)
            ->where('employee_payment_methods.payment_method_id', 3)
            ->chunk(200, function ($payrolls) use ($paymentRef, &$fields) {
                $payrolls = $payrolls->map(function ($roll) use ($paymentRef) {
                    $net = $this->calculate_net_pay($roll->id);
                    $roll->payment_reference = $paymentRef;
                    $roll->net_pay = $net;
                    return $roll;
                })->toArray();
                $fields = array_merge($fields, $payrolls);
            });
        $csv = [];
        $csv[] = implode(',', [
            'Payment Reference', 'Beneficiary Name', 'Account Number', 'Bank Name', 'Amount'
        ]);
        foreach ($fields as $field) {
            $csv[] = implode(',', [
                $field['payment_reference'],
                $field['first_name'].' '.$field['last_name'],
                $field['account-number'],
                $field['bank-name'],
                $field['net_pay']
            ]);
        }
        $csv = implode("\n", $csv);
        $file = storage_path('app/' . rand(0, 999999) . '.csv');
        file_put_contents($file, $csv);
        return response()->download($file, 'EFT.csv', [
            'Content-Type' => 'text/csv'
        ])->deleteFileAfterSend(true);
    }
    public function calculate_net_pay($id)
    {
        $payroll = Payroll::find($id);
        
        $totalDeductions = 0;
        $totalBenefits = 0;
        $dud = 0;
        $totalAllowances = $payroll->basic_pay;
        $totalNonTax = 0;
        $allowances = json_decode($payroll->allowances);

        $nonCash = array_filter($allowances, function ($item) {
            return isset($item->non_cash) && $item->non_cash;
        });
//        $allowances = array_filter($allowances, function ($item) {
//            return !isset($item->non_cash) || !$item->non_cash;
//        });
        $deductions = json_decode($payroll->deductions);
        $untaxedAllowances = [];
        $reliefs = collect($allowances)->where('name', 'reliefs')->first();
        $retirement = NULL;
        $mortgage = NULL;
       

        foreach($allowances as $allowance)
        {
                if($allowance->name == 'reliefs'){
                    continue;
                }

                if($allowance->amount > 0 && $allowance->non_cash == 0){
                     $untaxedAllowances [] = $allowance; 
                }
                if ($allowance->taxable == 1){

                     $totalAllowances += $allowance->tax_amount;

                    if($allowance->name === 'Car Benefit')
                    {

                            $dud = $allowance->tax_amount;


                    }
                }
           }    
            $deds = json_decode($payroll->deductions);
             $nssf = collect($deds)->where('name', 'NSSF')->first();
             
             if($nssf)
             {
    
                if (!$retirement) {
                    $retirement = new stdClass;
                    $retirement->amount = 0;
                }
                if (!$mortgage) {
                    $mortgage = new stdClass;
                    $mortgage->amount = 0;
                }
                $deductible = $nssf->amount + $retirement->amount;
                $deductible = $deductible > 20000 ? 20000 : $deductible;
                $deductible -= $nssf->amount;
                $deductible += $mortgage->amount;
                $totalDeductions += $nssf->amount;
             }
             
             
        $paye = collect($deds)->where('name', 'PAYE')->first();
        $totalDeductions += $paye->amount->amount; 
              foreach(json_decode($payroll->deductions) as $deduction){
                if ($deduction->name == "NSSF" || $deduction->name == 'PAYE')
                {
                    continue;
                }

        if(is_object($deduction->amount)){
             $totalDeductions += ($deduction->amount->amount - $deduction->amount->relief->amount);
        }
        else{
            if($deduction->name == "NSSF"){
                continue;
            }
             $totalDeductions += $deduction->amount;
           
        }
    }
    
   
    foreach(json_decode($payroll->advances) as $advance){
         $totalDeductions += $advance->amount;
        
    }
    foreach(json_decode($payroll->loans) as $loan){
         $totalDeductions += $loan->amount;
    }
   
 
    
    foreach($untaxedAllowances as $allowance){
        $totalBenefits += $allowance->amount;
    }
    
        if ($reliefs) {
            $reliefs = $reliefs->items;
        } else {
            $reliefs = [];
        }
    
    foreach($reliefs as $relief){
        $totalDeductions -= $relief->amount;
        }
    
     $totalDeductions -= $paye->amount->relief->amount;
     
    $net = ceil($totalAllowances - $totalDeductions) + $totalBenefits - $dud;


    
    return $net;
                
    }
}