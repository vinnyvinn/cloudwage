<?php

namespace App\Http\Controllers;

use App\Events\GeneratePDF;
use App\Policies\Policy;
use Carbon\Carbon;
use DOMPDF;
use iio\libmergepdf\Merger;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\File;
use Payroll\Models\CompanyProfile;
use Payroll\Models\Deduction;
use Payroll\Models\Employee;
use Payroll\Models\EmployeeContract;
use Payroll\Models\EmployeeDeduction;
use Payroll\Models\KRAP10A;
use Payroll\Models\KRAP10B;
use Payroll\Models\KRAP9;
use Payroll\Models\Payroll;
use Payroll\Parsers\LoanCalculator;

class TaxReportsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (! $request->has('type')) {
            abort(404);
        }

        $months = KRAP9::all()->unique('for_month')->sort();

        $months = $months->each(
            function ($item, $key) use ($months) {
                $carbonDate = Carbon::parse($item->for_month)->year;
                $months[$key] = [
                    'id'    => $carbonDate,
                    'value' => $carbonDate
                ];
            }
        )->unique('id');

        return view('modules.reports.tax.generate.select')
            ->withType($request->get('type'))
            ->withMonths($months);
    }

    private function getP9($year, $employeeId)
    {
        $today = Carbon::parse('01-01-' . $year);
        $employee = Employee::with('contract')->findOrFail($employeeId);
        $employee->contract = $employee->contract->sortBy('start_date');
        $payrolls = KRAP9::where('employee_id', $employeeId)->get()->reject(function ($item) use ($today) {
                return $item->for_month->gt($today->endOfYear()) ||
                $item->for_month->lt($today->startOfYear());
        })->sortBy('for_month');

        if ($payrolls->count() < 1) {
            flash(
                'Sorry. Please ensure you have generated and finalized at least one month\'s payroll.',
                'error'
            );

            return redirect()->route('payroll.index');
        }

        return $this->getSingleP9($payrolls, $today, $employee);
    }

    private function getSingleP9($payrolls, $today, $employee)
    {
        $calendarMonths = [
            1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May', 6 => 'June',
            7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
        ];
        $pdf = new DOMPDF();
        $pdf->set_option('enable_remote', true);
        $pdf->set_option('enable_php', true);

        $view = view('modules.reports.tax.generate.p9')
            ->withCalendarMonths($calendarMonths)
            ->withPayrolls($payrolls)
            ->withContract($employee->contract)
            ->withCompany(CompanyProfile::first())
            ->withToday($today)
            ->withEmployee($employee);

        $pdf->load_html($view->render());
        $pdf->set_paper('A4', 'landscape');
        $pdf->render();
        unset($view);

        return $pdf->stream('KRA P9', ['Attachment' => 0]);
    }

    private function getP10s($formView, $year)
    {
        $paye = Deduction::whereName('PAYE')->with(['employees'])->first();
        $today = Carbon::parse('01-01-' . $year);
        $employees = $paye->employees;
        $pdf = new DOMPDF();
        $pdf->set_option('enable_remote', true);
        $pdf->set_option('enable_php', true);
        $pdf->set_paper('A4', 'portrait');
        $empDeductions = EmployeeDeduction::whereDeductionId(Deduction::PAYE)
            ->get()
            ->keyBy('employee_id');
        $p9s = KRAP9::where(
            'for_month',
            '>=',
            $today->startOfYear()->format('Y-m-d'))
            ->where('for_month', '<=', $today->endOfYear()->format('Y-m-d'))
            ->orderBy('for_month', 'ASC')
            ->get();

        $view = view($formView)
            ->withCompany(CompanyProfile::first())
            ->withToday($today)
            ->withEmployees($employees->keyBy('id'))
            ->withP9s($p9s)
            ->withEmployeeDeductions($empDeductions);

        $pdf->load_html($view->render());
        $pdf->render();

        header('Content-Type: application/pdf');

        return $pdf->stream('payroll.pdf', ['Attachment' => 0]);
    }

    public function getReport(Request $request, $type)
    {
        $formView = 'modules.reports.tax.generate.p10a';

        if (! $request->has('year')) {
            abort(404);
        }
        $year = $request->get('year');

        switch ($type) {
            case "p9":
                Policy::canRead(new KRAP9());
                if (! $request->has('employee_id')) {
                    flash('Please select an employee to generate P9', 'error');

                    return redirect()->back();
                }

                return $this->getP9($year, $request->get('employee_id'));
                break;
            case "p10a":
                Policy::canRead(new KRAP10A());
                $formView = 'modules.reports.tax.generate.p10a';
                break;
            case "p10b":
                Policy::canRead(new KRAP10B());
                $formView = 'modules.reports.tax.generate.p10b';
                break;
            default:
                abort(404);
                break;
        }

        return $this->getP10s($formView, $year);
    }
}
