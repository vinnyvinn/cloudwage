@foreach($payrolls->chunk(20) as $payrollChunk)
    @foreach($payrollChunk as $payroll)
        @include('modules.payroll.payroll.payroll')
    @endforeach
@endforeach