<?php

namespace App\Http\Controllers;

use App\Policies\Policy;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Payroll\Handlers\Calculator;
use Payroll\Handlers\CoinageCalculator;
use Payroll\Models\Coinage;
use Payroll\Models\PaymentMethod;
use Payroll\Models\Payroll;
use Payroll\Parsers\DocumentGenerator;

class CoinageController extends Controller
{

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Policy::canRead(new Coinage());
        $months = Payroll::orderBy('payroll_date', 'DESC')
            ->groupBy('payroll_date')
            ->get()
            ->map(function ($value) {
                return $value->payroll_date;
            });

        return view('modules.payroll.coinage.create')->withMonths($months);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Calculator                $calc
     *
     * @param CoinageCalculator         $coinageCalc
     *
     * @param DocumentGenerator         $gen
     *
     * @return \Illuminate\Http\Response
     * @internal param DocumentGenerator $generator
     *
     */
    public function store(Request $request, Calculator $calc, CoinageCalculator $coinageCalc, DocumentGenerator $gen)
    {
        Policy::canRead(new Coinage());
        $month = Carbon::parse($request->get('month'));
        $employeeIds = collect(DB::select(
            DB::raw('SELECT * FROM `employee_payment_methods` INNER JOIN `payment_methods` on `employee_payment_methods`.`payment_method_id` = `payment_methods`.`id` WHERE `payment_methods`.`coinage` = 1')
        ))->map(function ($value) {
            return $value->employee_id;
        });
        $payrolls = Payroll::with(['employee'])
            ->wherePayrollDate($month->format('Y-m-d'))
            ->whereIn('employee_id', $employeeIds)
            ->get();

        $values = [1000, 500, 200, 100, 50, 40, 20, 10, 5, 1];
        $coinageCalc = $coinageCalc->setDivisions($values);
        $payrolls = $payrolls->map(function ($value) use ($calc, $coinageCalc) {
            $netPay = $calc->getNetPay($value);
            $value->net_pay = number_format($netPay, 2);
            collect($coinageCalc->withAmount($netPay)->get())->each(function ($qty, $coinValue) use ($value) {
                $value->$coinValue = $qty;
            });

            $employee = collect($value->employee->getAttributes());
            $employee->each(function ($attribute, $key) use ($value) {
                $value->$key = $attribute;
            });

            return $value;
        });

        $totalRow = new Payroll();
        $totalRow->payroll_number = 'TOTALS';
        collect($coinageCalc->getTotalCoinage())->each(function ($value, $key) use ($totalRow) {
            $totalRow->$key = $value;
        });
        $payrolls->push(new Payroll());
        $payrolls->push($totalRow);

        $fields = ['payroll_number', 'identification_number', 'first_name', 'last_name', 'net_pay'];
        $fields = implode(',', array_merge($fields, $values));

        $request->request->add(['title' => 'Coinage report as of ' . $month->format('d F Y')]);
        $request->request->add(['order' => $fields]);
        $gen->orientation = 'landscape';
        $gen->allowNumeric = false;
        $gen = $gen->prepare($request);
        $document = $gen->withRows($payrolls)
            ->render();

        return $document;
    }
}
