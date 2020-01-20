<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Maatwebsite\Excel\Facades\Excel;
use Payroll\Models\Allowance;
use Payroll\Models\CompanyProfile;
use Payroll\Models\Deduction;
use Payroll\Models\Payroll;
use Carbon\Carbon;

class ReportsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function statutoryFiles()
    {
        $months = Payroll::all()->unique('payroll_date')->sort();
        $months = $months->each(function ($item, $key) use ($months) {
            $months[$key] = [
                'id' => $item->payroll_date,
                'value' => Carbon::parse($item->payroll_date)->format('d F Y')
            ];
        });
        return view('modules.reports.statutory')->withMonths($months);
    }
    public function exportDocument(Request $request)
    {
        switch ($request->get('file')) {
            case 'paye':
            default:
                return $this->exportPAYE($request);
            case 'nhif':
                return $this->exportNHIF($request);
            case 'nssf':
                return $this->exportNSSF($request);
            case 'intpaye':
                return $this->exportInternalPaye($request);
        }
    }
    public function exportPAYE(Request $request)
    {
        $payroll = $this->getPayrollDetails(Deduction::PAYE, 'PAYE', $request->payroll_date);

        Excel::create('PAYE', function ($excel) use ($request, $payroll) {
            $excel->sheet('Excel sheet', function ($sheet) use ($request, $payroll) {
                $sheet->loadView('modules.reports.paye')
                    ->with('payrolls', $payroll);
            });
        })->export('csv');
    }
    public function exportNHIF(Request $request)
    {
        $payroll = $this->getPayrollDetails(Deduction::NHIF, 'NHIF', $request->payroll_date);

        Excel::create('NHIF', function ($excel) use ($payroll, $request) {
            $excel->sheet('Excel sheet', function ($sheet) use ($payroll, $request) {
                $sheet->loadView('modules.reports.nhif')
                    ->with('company', CompanyProfile::first())
                    ->with('payrolls', $payroll)
                    ->with('payroll_date', Carbon::parse($request->get('payroll_date'))->format('M-Y'));

//                $sheet->setOrientation('landscape');
            });
        })->export('xls');
    }


    public function exportInternalPaye(Request $request)
    {
        $payroll = $this->getPayrollDetails(Deduction::PAYE, 'PAYE', $request->payroll_date);

        Excel::create('PAYE', function ($excel) use ($request, $payroll) {
            $excel->sheet('Excel sheet', function ($sheet) use ($request, $payroll) {
                $sheet->loadView('modules.reports.int-paye')
                    ->with('company', CompanyProfile::first())
                    ->with('payrolls', $payroll)
                    ->with('payroll_date', Carbon::parse($request->get('payroll_date'))->format('M-Y'));
            });
        })->export('xls');
    }
    public function exportNSSF(Request $request)
    {
        $payroll = $this->getPayrollDetails(Deduction::NSSF, 'NSSF', $request->payroll_date);

        Excel::create('NSSF', function ($excel) use ($payroll, $request) {
            $excel->sheet('Excel sheet',
                function ($sheet) use ($payroll, $request) {
                    $sheet->loadView('modules.reports.nssf')
                        ->with('payrolls', $payroll)
                        ->with('company', CompanyProfile::first())
                        ->with('payroll_date', Carbon::parse($request->get('payroll_date'))->format('M-Y'));
                });
        })->export('xls');
    }
    /**
     * @param       $deductionId
     * @param       $deductionName
     * @param array $extraFields
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|static|static[]
     */
    private function getPayrollDetails($deductionId, $deductionName, $date, $extraFields = [])
    {
        $columns = ['id', 'deductions', 'employee_id'];

        if ($deductionName == 'PAYE') {
            $columns [] = 'kra';
            $columns [] = 'allowances';
        }

        $columns = array_merge($columns, $extraFields);
        $payroll = Payroll::where('payroll_date', $date)->whereHas('employee.deductions', function ($builder) use ($deductionId) {
            return $builder->where('deduction_id', $deductionId);
        })
            ->with([
                'employee' => function ($builder) {
                    return $builder->select([
                        'id', 'first_name', 'last_name', 'payroll_number', 'identification_number',
                        'identification_type'
                    ]);
                },
                'employee.deductions' => function ($builder) use ($deductionId) {
                    return $builder->where('deduction_id', $deductionId)
                        ->select(['id', 'deduction_number', 'employee_id']);
                }
            ])

//            ->orderByRaw('`employees`.`payroll_number`, desc')
            ->get($columns);

        $payroll = $payroll->sortBy('employee.payroll_number');

        $payroll = $payroll->map(function ($payroll) use ($deductionName) {
            $payroll->deductions = json_decode($payroll->deductions);

            if ($deductionName == 'PAYE') {
                $payroll->kra = json_decode($payroll->kra);
                $payroll->allowances = json_decode($payroll->allowances);
            }

            foreach ($payroll->deductions as $deduction) {
                if ($deduction->name == $deductionName) {
                    $payroll->deductions = $deduction;
                    break;
                }
            }

            return $payroll;
        });

        return $payroll;
    }
}
