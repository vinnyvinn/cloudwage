@extends('layout')
@section('content')
    <div class="page-head">
        <div class="page-title">
            <h1>Employee Payment Method - <small> Manage the employee payment method</small></h1>
        </div>
    </div>
    <ul class="page-breadcrumb breadcrumb">
        <li>
            <a href="{{ url('/') }}">Home</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="{{ route('employees.index') }}">Employee Payment Method</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="#">Manage</a>
        </li>
    </ul>

    <div class="row">
        <div class="col-sm-12">
            <form action="{{ route('employee-payment-methods.update', $payment->id) }}" method="post" role="form">
                {{ csrf_field() }}
                {{ method_field('PUT') }}
                <div class="row">
                    <div class="col-sm-6">
                        <div class="portlet light">
                            <div class="portlet-title">
                                <div class="caption font-red-sunglo">
                                    <i class="fa fa-briefcase font-red-sunglo"></i>
                                    <span class="caption-subject bold uppercase"> Payment Details</span>
                                </div>
                            </div>
                            <div class="portlet-body form">
                                <div class="form-body">
                                    <div class="form-group form-md-line-input">
                                        <h4>Payroll Number</h4>
                                        <h4><strong>{{ $payment->employee->payroll_number }}</strong></h4>
                                    </div>
                                    <div class="form-group form-md-line-input">
                                        <h4>First Name</h4>
                                        <h4><strong>{{ $payment->employee->first_name }}</strong></h4>
                                    </div>
                                    <div class="form-group form-md-line-input">
                                        <h4>Last Name</h4>
                                        <h4><strong>{{ $payment->employee->last_name }}</strong></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="portlet light">
                            <div class="portlet-title">
                                <div class="caption font-red-sunglo">
                                    <i class="fa fa-briefcase font-red-sunglo"></i>
                                    <span class="caption-subject bold uppercase"> Payment Details</span>
                                </div>
                            </div>
                            <div class="portlet-body form">
                                <div class="form-body">
                                    <div class="form-group form-md-line-input">
                                        <select class="form-control" id="payment_method_id" name="payment_method_id">
                                            @foreach($payment_methods as $payMethod)
                                                <option value="{{ $payMethod->id }}" {{ $payment->payment_method_id == $payMethod->id ? 'selected' : '' }}>{{ $payMethod->name }}</option>
                                            @endforeach
                                        </select>
                                        <label for="payment_method_id">Payment Method*</label>
                                        <p>These will be the mode of payment that the employee will use</p>
                                    </div>
                                    <div id="payMethodsHolder"></div>

                                    <div class="form-group form-md-line-input form-md-floating-label">
                                        <input type="submit" class="btn btn-primary" value="Save">
                                        <a class="btn btn-danger" href="{{ URL::previous() }}">Back</a>
                                    </div>
                                </div>
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
        $(function (){
            var udfs = {!! Payroll\Models\UDF::where('udfable_type', Payroll\Models\PaymentMethod::class)->get(['udfable_id', 'field_name', 'field_title'])->toJson() !!};
            var empPayment = {!! $payment !!}
            function getFields(element) {
                var selectedMethod = $(element).val();
                $('.udf-field').remove();
                $.each(udfs, function(i, v) {
                    if (v.udfable_id == selectedMethod) {
                        var field = '<div class="form-group form-md-line-input form-md-floating-label udf-field"><input type="text" class="form-control" id="'+ v.field_name +'" name="'+ v.field_name +'" value="' + getValue(v.field_name ) + '" required><label for="'+ v.field_name +'">'+ v.field_title +'*</label><span class="help-block">This is the required payment method field</span></div>';
                        $('#payMethodsHolder').append(field);
                    }
                });
            };

            function getValue(field_name) {
                return empPayment[field_name] == null ? '' : empPayment[field_name];
            }

            $('#payment_method_id').on('change', function() {
                getFields(this);
            });

            getFields($('#payment_method_id'));
        });
    </script>
@endsection