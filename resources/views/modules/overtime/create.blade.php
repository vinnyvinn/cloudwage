@extends('layout')
@section('content')
    <div class="page-head">
        <div class="page-title">
            <h1>Overtime Input - <small> Set up the pay grades that will be assigned within the organization</small></h1>
        </div>
    </div>
    <ul class="page-breadcrumb breadcrumb">
        <li>
            <a href="{{ url('/') }}">Home</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="{{ route('overtime.index') }}">Overtime</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="#">Create</a>
        </li>
    </ul>

    <div class="row">
        <div class="col-sm-12">
            <form action="{{ route('overtime.store') }}" method="post" role="form">
                {{ csrf_field() }}
                <div class="portlet light">
                    <div class="portlet-title">
                        <div class="caption font-red-sunglo">
                            <i class="fa fa-briefcase font-red-sunglo"></i>
                            <span class="caption-subject bold uppercase"> Overtime Details</span>
                        </div>
                    </div>
                    <div class="portlet-body form">
                        <div class="form-body">
                            
                                <div class="form-group form-md-line-input form-md-floating-label">
                                    <select class="form-control" id="employee_id" name="employee_id" required>
                                        @foreach($employees as $employee)
                                            <option value="{{ $employee->id }}" {{ $employee->id == old('employee_id') ? 'selected' : '' }}>{{ $employee->first_name .' '. $employee->last_name}}</option>
                                        @endforeach
                                    </select>
                                    <label for="payment_structure_id">Employee*</label>
                                    <span class="help-block">Select employee for whom you are keying in the overtime for</span>
                                </div>
                           
                            
                            <div class="form-group form-md-line-input form-md-floating-label">
                                <label for="payroll_date">Payroll For*</label>
                                <div class="input-group date date-picker margin-bottom-5" readonly>
                                    <input type="text" class="form-control form-filter input-sm" value="{{ old('payroll_date') }}" name="payroll_date" id="payroll_date" required>
                                    <span class="input-group-btn">
                                                        <button class="btn btn-sm default" type="button"><i class="fa fa-calendar"></i></button>
                                                    </span>
                                </div>
                                <span>This is month for which you want to generate the payroll</span>
                            </div>
                           
                            <div class="form-group form-md-line-input form-md-floating-label">
                                <label for="name">OT One Rate*</label>
                                <input type="number" name="ot_1_rate"  step="0.1" class="form-control" value="{{ $general->ot_1 }}" required>
                                <span class="help-block">This is the OT one rate to be used</span>
                            </div>
                            <div class="form-group form-md-line-input form-md-floating-label">
                                <input type="number" class="form-control" min="0" id="ot_1_hrs" name="ot_1_hrs" value="{{ old('ot_1_hrs') }}" required>
                                <label for="annual_increment">OT-1 Hours <span id="unit" class="uppercase"></span>*</label>
                                <span class="help-block">This is the total OT-1 hours for the month</span>
                            </div>
                            <div class="form-group form-md-line-input form-md-floating-label">
                                <label for="name">OT Two Rate*</label>
                                <input type="number" step="0.1" name="ot_2_rate" class="form-control" value="{{ $general->ot_2 }}" required>
                                <span class="help-block">This is the OT-2 rate to be used</span>
                            </div>
                            <div class="form-group form-md-line-input form-md-floating-label">
                                <input type="number" class="form-control" min="0" id="ot_2_hrs" name="ot_2_hrs" value="{{ old('ot_2_hrs') }}" required>
                                <label for="annual_increment">OT-2 Hours <span id="unit" class="uppercase"></span>*</label>
                                <span class="help-block">This is the total OT-2 hours for the month</span>
                            </div>

                            <div class="form-group form-md-line-input form-md-floating-label">
                                <label for="amount">Total Amount Earned*</label>
                                <input type="number" class="form-control" name="amount" value="0" step="0.01" min="0" required>
                                <span>This is the cumulative amount earned</span>
                            </div>


                            </div>
                            <div class="form-group form-md-line-input form-md-floating-label">
                                <input type="submit" class="btn btn-primary" value="Save">
                                <a class="btn btn-danger" href="{{ route('assignments.index') }}">Back</a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('footer')
@section('footer')
    <script>

        $(".date-picker").datepicker( {
            format: "mm-yyyy",
            viewMode: "months",
            minViewMode: "months",
            endDate: "0d"
        });
    </script>
@endsection
@stop
