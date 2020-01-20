@extends('layout')
@section('content')
    <div class="page-head">
        <div class="page-title">
            <h1>Allowances - <small> Set up a new allowance to be used within the organization</small></h1>
        </div>
    </div>
    <ul class="page-breadcrumb breadcrumb">
        <li>
            <a href="{{ url('/') }}">Home</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="{{ route('allowances.index') }}">Allowances</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="#">Edit</a>
        </li>
    </ul>

    <div class="row">
        <div class="col-sm-12">
            <form action="{{ route('allowances.update', ['id' => $allowance->id]) }}" method="post" role="form">
                {{ csrf_field() }}
                {{ method_field('put') }}
                <div class="portlet light">
                    <div class="portlet-title">
                        <div class="caption font-red-sunglo">
                            <i class="fa fa-briefcase font-red-sunglo"></i>
                            <span class="caption-subject bold uppercase"> Allowance Details</span>
                        </div>
                    </div>
                    <div class="portlet-body form">
                        <div class="form-body">
                            <div class="form-group form-md-line-input form-md-floating-label">
                                <input type="text" class="form-control" id="name" name="name" value="{{ $allowance->name or old('name') }}" required>
                                <label for="name">Allowance Name*</label>
                                <span class="help-block">This is the name of the allowance</span>
                            </div>
                            <div class="form-group form-md-line-input form-md-floating-label">
                                <select class="form-control" id="non_cash" name="non_cash">
                                    <option value="0" {{ $allowance->non_cash == 0 ? 'selected' : '' }}>Cash Benefit</option>
                                    <option value="1" {{ $allowance->non_cash == 1 ? 'selected' : '' }}>Non-Cash Benefit</option>
                                </select>
                                <label for="non_cash">Allowance Type*</label>
                                <span class="help-block">This determines if the allowance is monetary or not. e.g. A House is provided</span>
                            </div>
                            <div class="form-group form-md-line-input form-md-floating-label">
                                <select name="currency_id" id="currency_id" class="form-control select2" >

                                    @foreach(\Payroll\Models\Currency::all() as $currency)
                                        <option value="{{ $currency->id }}" {{ $allowance->currency_id == $currency->id ? 'selected' : ''}}>{{ $currency->name }}</option>
                                    @endforeach
                                </select>
                                <label for="branch">Currency</label>
                                <span class="help-block">This is the currency relative to the allowance</span>
                            </div>

                            <div class="form-group form-md-line-input form-md-floating-label">
                                <select name="type" id="type" class="form-control" >
                                    <option value="per_employee" {{ $allowance->type == 'per_employee' ? 'selected' : '' }}>Per Employee</option>
                                    <option value="rate" {{ $allowance->type == 'rate' ? 'selected' : '' }}>Rate</option>
                                </select>
                                <label for="type">Type</label>
                                <span class="help-block">Choose whether the allowance is rate based or per employee</span>
                            </div>

                            <div id="rate_holder" hidden>
                                <div class="form-group form-md-line-input form-md-floating-label input-group">
                                    <input type="text" class="form-control" id="rate" name="rate" value="{{ $allowance->rate }}">
                                    <span class="input-group-addon">Rate</span>
                                    <label for="rate">Rate Amount</label><br>
                                    <span class="help-block" style="margin-left: -100%;">This is the rate to be applied to the given allowance. Add % if it is a percentage</span>
                                </div>
                            </div>

                            <div class="form-group form-md-line-input form-md-floating-label">
                                <select class="form-control" id="in_basic" name="in_basic">
                                    <option value="0" {{ $allowance->in_basic == 1 ?: 'selected' }}>Excluded in basic pay</option>
                                    <option value="1" {{ $allowance->in_basic == 0 ?: 'selected' }}>Included in basic pay</option>
                                </select>
                                <label for="in_basic">Payment Allocation*</label>
                                <span class="help-block">This determines whether the allowance is already part of the basic pay.</span>
                            </div>
                            
                            <div class="form-group form-md-line-input form-md-floating-label">
                                <select class="form-control" id="taxable" name="taxable">
                                    <option value="0" {{ $allowance->taxable == 1 ?: 'selected' }}>NO</option>
                                    <option value="1" {{ $allowance->taxable == 0 ?: 'selected' }}>Yes</option>
                                </select>
                                <label for="taxable">Taxable*</label>
                                <span class="help-block">This determines whether the allowance attracts any tax to it</span>
                            </div>
                            <div id="taxable_holder">
                                <div class="form-group form-md-line-input form-md-floating-label">
                                    <input type="text" class="form-control" id="tax_rate" name="tax_rate" value="{{ $allowance->tax_rate or old('tax_rate') }}">
                                    <label for="max_amount">Tax Rate in percentage (Omit the % sign)*</label>
                                    <span class="help-block">This is the rate in percentage at which the allowance is being taxed</span>
                                </div>
                            </div>
                            <div class="form-group form-md-line-input form-md-floating-label">
                                <select class="form-control" id="has_relief" name="has_relief">
                                    <option value="1" {{ $allowance->has_relief or old('has_relief') == 0 ?: 'selected' }}>Yes</option>
                                    <option value="0" {{ $allowance->has_relief or old('has_relief') == 1 ?: 'selected' }}>NO</option>
                                </select>
                                <label for="added_to_basic">Has Relief*</label>
                                <span class="help-block">This determines whether the allowance has any relief attached to it</span>
                            </div>
                            <div id="relief_holder" hidden>
                                <div class="form-group form-md-line-input form-md-floating-label">
                                    <input type="text" class="form-control" id="relief_name" name="relief_name" value="{{ $allowance->relief->name or old('relief_name') }}">
                                    <label for="relief_name">Relief Name</label>
                                    <span class="help-block">This is the name to be used to refer to the given relief</span>
                                </div>
                                <div class="form-group form-md-line-input form-md-floating-label">
                                    <input type="text" pattern="[0-9]+$" title="Numbers only" class="form-control" id="relief_amount" name="relief_amount" value="{{ $allowance->relief->amount or old('relief_amount') }}">
                                    <label for="relief_amount">Relief Amount</label>
                                    <span class="help-block">This is the relief given to the allowance</span>
                                </div>
                            </div>

                            <div class="form-group form-md-line-input form-md-floating-label">
                                <input type="submit" class="btn btn-primary" value="Save">
                                <a class="btn btn-danger" href="{{ route('allowances.index') }}">Back</a>
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
        $(document).ready(function() {
            var type = $("#type");
            var rate_holder = $('#rate_holder');
            var relief = $("#has_relief");
            var relief_holder = $('#relief_holder');
            var tax = $("#taxable");
            var tax_holder = $('#taxable_holder');
            relief.on('change', function() {
                showAmount();
            });

            tax.on('change', function() {
                showTax();
            });

            type.on('change', function() {
                showRate();
            });

            function showAmount() {
                relief.val() == 1 ? relief_holder.show() : relief_holder.hide();
            }

            function showTax() {
                tax.val() == 1 ? tax_holder.show() : tax_holder.hide();
            }

            function showRate()
            {
                if(type.val() == 'rate')
                {
                    rate_holder.show();
                    return;
                }
                rate_holder.hide();
            }

            showAmount();
            showTax();
            showRate();
        });
    </script>
    
@endsection