@extends('layout')
@section('content')
    <div class="page-head">
        <div class="page-title">
            <h1>Holiday pay Input - <small> Set</small></h1>
        </div>
    </div>
    <ul class="page-breadcrumb breadcrumb">
        <li>
            <a href="{{ url('/') }}">Home</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="{{ route('holiday-pay.index') }}">Holiday pay</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="#">Create</a>
        </li>
    </ul>

    <div class="row">
        <div class="col-sm-12">
            <form action="{{ route('holiday-pay.store') }}" method="post" role="form">
                {{ csrf_field() }}
                <div class="portlet light">
                    <div class="portlet-title">
                        <div class="caption font-red-sunglo">
                            <i class="fa fa-briefcase font-red-sunglo"></i>
                            <span class="caption-subject bold uppercase"> Holiday Pay Details</span>
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
                                <span class="help-block">Select employee for whom you are keying in the days off for</span>
                            </div>


                            <div class="form-group form-md-line-input form-md-floating-label">
                                <label for="payroll_date">Payroll For*</label>
                                <div class="input-group date date-picker margin-bottom-5" readonly>
                                    <input type="text" class="form-control form-filter input-sm" value="{{ old('for_month') }}" name="for_month" id="for_month" required>
                                    <span class="input-group-btn">
                                                        <button class="btn btn-sm default" type="button"><i class="fa fa-calendar"></i></button>
                                                    </span>
                                </div>
                                <span>This is month for which you want to capture the payment</span>
                            </div>
                            {{--<div class="form-group form-md-line-input form-md-floating-label">--}}
                            {{--<label for="name">OT *</label>--}}
                            {{--<select name="type" id="type">--}}
                            {{--<option value="0">Daily Rate</option>--}}
                            {{--<option value="1">Fixed Rate</option>--}}
                            {{--</select>--}}
                            {{--<span class="help-block">This is the OT-2 rate to be used</span>--}}
                            {{--</div>--}}

                            {{--<div class="form-group form-md-line-input form-md-floating-label">--}}
                            {{--<label for="name">Days Off Rate*</label>--}}
                            {{--<input type="number" name="rate" id="rate" step="0.1" class="form-control" required>--}}
                            {{--<span class="help-block">This is the days off rate to be used</span>--}}
                            {{--</div>--}}

                            <?php $emps = json_encode($employees); ?>
                            <div class="form-group form-md-line-input form-md-floating-label">
                                <input type="number" class="form-control" min="1" id="units" name="units" value="{{ old('units') }}" required>
                                <label for="annual_increment"> Units <span id="unit" class="uppercase"></span>*</label>
                                <span class="help-block">This is the total days off for the month</span>
                            </div>

                            {{--<div class="form-group form-md-line-input form-md-floating-label">--}}
                            {{--<label for="amount">Total Amount Earned*</label>--}}
                            {{--<div class="form-control" id="amount"></div>--}}
                            {{--<span>This is the cumulative amount earned</span>--}}
                            {{--</div>--}}


                        </div>
                        <div class="form-group form-md-line-input form-md-floating-label">
                            <input type="submit" class="btn btn-primary" value="Save">
                            <a class="btn btn-danger" href="{{ route('holiday-pay.index') }}">Back</a>
                            <a class="btn btn-danger" href="{{ route('holiday-pay.index') }}">Back</a>
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
