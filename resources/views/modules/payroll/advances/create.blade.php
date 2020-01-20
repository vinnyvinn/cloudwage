@extends('layout')
@section('content')
    <div class="page-head">
        <div class="page-title">
            <h1>Salary Advances - <small> Current advances given to employees in the Organization</small></h1>
        </div>
    </div>
    <ul class="page-breadcrumb breadcrumb">
        <li>
            <a href="{{ url('/') }}">Home</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="{{ route('advances.index') }}">Salary Advances</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="#">Process Advance</a>
        </li>
    </ul>

    <div class="row">
        <div class="col-sm-12">
            <form action="{{ route('advances.store') }}" method="post" role="form">
                {{ csrf_field() }}
                <div class="portlet light">
                    <div class="portlet-title">
                        <div class="caption font-red-sunglo">
                            <i class="fa fa-briefcase font-red-sunglo"></i>
                            <span class="caption-subject bold uppercase"> Advance Details</span>
                        </div>
                    </div>
                    <div class="portlet-body form">
                        <div class="form-body">
                            <div class="form-group form-md-line-input form-md-floating-label">
                                <select class="form-control select2" id="employee_id" name="employee_id">
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>{{ $employee->payroll_number . ' - ' . $employee->first_name . ' ' . $employee->last_name }}</option>
                                @endforeach
                                </select>
                                <label for="employee_id">Employee*</label>
                                <span class="help-block">This is the employee that is being given the advance</span>
                            </div>
                            <div class="form-group form-md-line-input form-md-floating-label">
                                <input type="number" class="form-control" id="amount" name="amount" step="0.01" value="{{ old('amount') }}" required>
                                <label for="name">Amount*</label>
                                <span class="help-block">This is the amount of the advance</span>
                            </div>
                            <div class="form-group form-md-line-input form-md-floating-label">
                                <input type="text" class="form-control" id="amount" name="desc" value="{{ old('desc') }}">
                                <label for="name">Reason</label>
                                <span class="help-block">This is the reason for the advance (if any)</span>
                                </div>
                                
                                
                        @if($advance->getPolicy(\Payroll\Models\Advance::ALLOW_OTHER_MONTHS)->value == 'true')
                            <div class="form-group form-md-line-input form-md-floating-label">
                                <label for="min_amount">Advance for Month*</label>
                                <div class="input-group date date-picker margin-bottom-5" readonly>
                                    <input type="text" class="form-control form-filter input-sm" value="{{ old('for_month') }}" name="for_month" id="for_month" required>
                                    <span class="input-group-btn">
                                        <button class="btn btn-sm default" type="button"><i class="fa fa-calendar"></i></button>
                                    </span>
                                </div>
                                <span class="help-block">This is the month for which the advance is for</span>
                            </div>
                        @endif
                            <div class="form-group form-md-line-input form-md-floating-label">
                                <input type="submit" class="btn btn-primary" value="Save">
                                <a class="btn btn-danger" href="{{ URL::previous() }}">Back</a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('footer')
    <script>
        $(".date-picker").datepicker( {
            format: "mm-yyyy",
            viewMode: "months",
            minViewMode: "months"
            
        });
    </script>
@endsection