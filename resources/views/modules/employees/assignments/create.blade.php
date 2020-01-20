@extends('layout')
@section('content')
    <div class="page-head">
        <div class="page-title">
            <h1>Pay Grades - <small> Set up the pay grades that will be assigned within the organization</small></h1>
        </div>
    </div>
    <ul class="page-breadcrumb breadcrumb">
        <li>
            <a href="{{ url('/') }}">Home</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="{{ route('grades.index') }}">Pay Grades</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="#">Create</a>
        </li>
    </ul>

    <div class="row">
        <div class="col-sm-12">
            <form action="{{ route('grades.store') }}" method="post" role="form">
                {{ csrf_field() }}
                <div class="portlet light">
                    <div class="portlet-title">
                        <div class="caption font-red-sunglo">
                            <i class="fa fa-briefcase font-red-sunglo"></i>
                            <span class="caption-subject bold uppercase"> Pay Grade Details</span>
                        </div>
                    </div>
                    <div class="portlet-body form">
                        <div class="form-body">
                            <div class="form-group form-md-line-input form-md-floating-label">
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                                <label for="name">Pay Grade Name* (e.g. Group A)</label>
                                <span class="help-block">This is the name of the pay grade</span>
                            </div>
                            <div class="form-group form-md-line-input form-md-floating-label">
                                <select class="form-control" id="payment_structure_id" name="payment_structure_id">
                                @foreach($payment_structures as $structure)
                                    <option value="{{ $structure->id }}" {{ $structure->id == old('payment_structure_id') ? 'selected' : '' }}>{{ $structure->name }}</option>
                                @endforeach
                                </select>
                                <label for="payment_structure_id">Payment Structure*</label>
                                <span class="help-block">This will be the applicable payment structure that will be used with this grade</span>
                            </div>
                            <div class="form-group form-md-line-input form-md-floating-label">
                                <select class="form-control" id="currency_id" name="currency_id">
                                    @foreach($currencies as $currency)
                                        <option value="{{ $currency->id }}" {{ $currency->id == $default_currency ? 'selected' : '' }}>{{ $currency->name }}</option>
                                    @endforeach
                                </select>
                                <label for="name">Currency*</label>
                                <span class="help-block">This is the currency to be used within the pay grade</span>
                            </div>
                            <div class="form-group form-md-line-input form-md-floating-label">
                                <input type="text" class="form-control" id="basic_salary" name="basic_salary" value="{{ old('basic_salary') }}" required>
                                <label for="annual_increment">Salary PER <span id="unit" class="uppercase"></span>*</label>
                                <span class="help-block">This is the basic salary for the job group</span>
                            </div>
                            <div class="form-group form-md-line-input form-md-floating-label">
                                <input type="text" class="form-control" id="annual_increment" name="annual_increment" value="{{ old('annual_increment') }}" required>
                                <label for="annual_increment">Annual Increment* (e.g. 10%)</label>
                                <span class="help-block">This is the expected annual increment of the basic salary</span>
                            </div>

                            <div class="form-group form-md-line-input">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <label for="name">Default Allowances*</label><br><br>
                                        @foreach($allowances as $allowance)
                                            <div class="form-group">
                                                <label for="inputEmail3" class="col-sm-9 control-label">{{ $allowance->name }}</label>
                                                <div class="col-sm-3">
                                                    <input type="checkbox" name="default_allowances[]" class="make-switch" value="{{ $allowance->id }}" data-on-text="<i class='fa fa-check'></i>" data-off-text="<i class='fa fa-times'></i>">
                                                </div>
                                            </div>
                                        @endforeach
                                        <br><br><br>
                                        <p>These will be the default allowances that will be used with this grade</p>
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="name">Default Deductions*</label><br><br>
                                        @foreach($deductions as $deduction)
                                            <div class="form-group">
                                                <label for="inputEmail3" class="col-sm-9 control-label">{{ $deduction->name }}</label>
                                                <div class="col-sm-3">
                                                    <input type="checkbox" name="default_deductions[]" class="make-switch" value="{{ $deduction->id }}" data-on-text="<i class='fa fa-check'></i>" data-off-text="<i class='fa fa-times'></i>">
                                                </div>
                                            </div>
                                        @endforeach
                                        <br><br><br>
                                        <p>These will be the default deductions that will be used with this grade</p>
                                    </div>
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
    <script>
        var structures = {!! Payroll\Models\PaymentStructure::get(['id', 'unit'])->toJson() !!}
        var unitElem = $('#unit');
        var structElem = $('#payment_structure_id');

        function setUnit(elem) {
            var selectedStructure = elem.val();

            $.each(structures, function(i, v) {
                if (v.id == selectedStructure) {
                    unitElem.html(v.unit);
                }
            });
        }

        structElem.on('change', function () {
            setUnit($(this));
        });

        setUnit(structElem);
    </script>
@stop
