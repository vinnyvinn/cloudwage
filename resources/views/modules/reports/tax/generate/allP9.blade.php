@foreach($employees as $employee)
    <?php
//    $payrolls = $employee->p9()
//            ->where('for_month', '>=', $today->startOfYear()->format('Y-m-d'))
//            ->where('for_month', '<=', $today->endOfYear()->format('Y-m-d'))
//            ->orderBy('for_month', 'ASC')
//            ->get();
    $payrolls = $all_payrolls->whereLoose('employee_id', $employee->id);
    $contract = $emp_contracts->whereLoose('employee_id', $employee->id)->sortBy('start_date');

    if (count($payrolls) < 1) {
        continue;
    }
    ?>

    @include('modules.reports.tax.generate.p9')
@endforeach