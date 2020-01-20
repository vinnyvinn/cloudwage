<?php

namespace App\Listeners;

use App\Events\GeneratePDF;
use Carbon\Carbon;
use DOMPDF;
use iio\libmergepdf\Merger;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\File;
use Payroll\Models\CompanyProfile;
use Payroll\Models\Deduction;
use Payroll\Models\EmployeeContract;
use Payroll\Models\KRAP9;

class GeneratePDFListener implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  GeneratePDF  $event
     * @return void
     */
    public function handle(GeneratePDF $event)
    {
        if ($event->type == 'KRAP9') {
            $this->generateP9($event->attributes);
        }
    }

    private function generateP9($attributes)
    {
        $profile = CompanyProfile::first();
        $contracts = EmployeeContract::all();
        $KRAP9 = KRAP9::all()->reject(function ($item) use ($attributes) {
            return $item->for_month->gt($attributes['today']->endOfYear()) ||
            $item->for_month->lt($attributes['today']->startOfYear());
        })->sortBy('for_month');
        $employees = Deduction::whereName('PAYE')->with(['employees'])->first()->employees;

        foreach ($employees as $employee) {
            $payrolls = $KRAP9->whereLoose('employee_id', $employee->id);
            $contract = $contracts->whereLoose('employee_id', $employee->id)->sortBy('start_date');
            if (count($payrolls) < 1) {
                continue;
            }

            var_dump('beforez');
            file_put_contents(
                public_path('payrolls/payrollP9-'.$employee->id.'.pdf'),
                $this->getSingleP9($profile, $payrolls, $contract, $attributes['today'], $employee)
            );


        }

        $files = File::glob('payrolls/payrollP9-*.pdf');
        $merger = new Merger();
        $merger->addIterator($files);
        file_put_contents('all-p9s.pdf', $merger->merge());
        File::delete($files);
    }

    private function getSingleP9($profile, $payrolls, $contract, $today, $employee)
    {
        $calendarMonths = [
            1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May', 6 => 'June',
            7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
        ];
        $pdf = new DOMPDF();
        $pdf->set_option('enable_remote', true);
        $pdf->set_option('enable_php', true);
        $pdf->set_paper('A4', 'landscape');

        $view = view('modules.reports.tax.generate.p9')
            ->withCalendarMonths($calendarMonths)
            ->withPayrolls($payrolls)
            ->withContract($contract)
            ->withCompany($profile)
            ->withToday($today)
            ->withEmployee($employee);

        $pdf->load_html($view->render());
        file_put_contents(public_path('hehe.html'), $view->render());
        
        $pdf->render();
        var_dump('viewa');


        return $pdf->output();
    }
}
