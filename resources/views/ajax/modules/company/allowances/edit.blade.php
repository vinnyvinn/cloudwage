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
                                <select name="currency_id" id="currency_id" class="form-control select2" >

                                    @foreach(\Payroll\Models\Currency::all() as $currency)
                                        <option value="{{ $currency->id }}" {{ $allowance->currency_id == $currency->id ? 'selected' : ''}}>{{ $currency->name }}</option>
                                    @endforeach
                                </select>
                                <label for="branch">Currency</label>
                                <span class="help-block">This is the currency relative to the allowance</span>
                            </div>

                            <div class="form-group form-md-line-input form-md-floating-label">
                                <input type="text" pattern="[0-9]+$" title="Numbers only" class="form-control" id="min_amount" name="min_amount" value="{{ $allowance->min_amount or old('min_amount') }}" required>
                                <label for="min_amount">Minimum Amount*</label>
                                <span class="help-block">This is the minimum amount that can be given as an allowance</span>
                            </div>
                            <div class="form-group form-md-line-input form-md-floating-label">
                                <input type="text" pattern="[0-9]+$" title="Numbers only" class="form-control" id="max_amount" name="max_amount" value="{{ $allowance->max_amount or old('max_amount') }}" required>
                                <label for="max_amount">Maximum Amount*</label>
                                <span class="help-block">This is the maximum amount that can be given as an allowance</span>
                            </div>
                            <div class="form-group form-md-line-input form-md-floating-label">
                                <select class="form-control" id="added_to_basic" name="added_to_basic">
                                    <option value="1" {{ $allowance->added_to_basic or old('added_to_basic') == 0 ?: 'selected' }}>Yes</option>
                                    <option value="0" {{ $allowance->added_to_basic or old('added_to_basic') == 1 ?: 'selected' }}>NO</option>
                                </select>
                                <label for="added_to_basic">Add to Basic Pay*</label>
                                <span class="help-block">This determines whether the allowance should be computed as part of basic pay</span>
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
                                    <input type="text" class="form-control" id="relief_name" name="relief_name" value="{{ $relief->name or old('relief_name') }}">
                                    <label for="relief_name">Relief Name</label>
                                    <span class="help-block">This is the name to be used to refer to the given relief</span>
                                </div>
                                <div class="form-group form-md-line-input form-md-floating-label">
                                    <input type="text" pattern="[0-9]+$" title="Numbers only" class="form-control" id="relief_amount" name="relief_amount" value="{{ $relief->amount or old('relief_amount') }}">
                                    <label for="relief_amount">Relief Amount</label>
                                    <span class="help-block">This is the relief given to the allowance</span>
                                </div>
                            </div>

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
        $(document).ready(function() {
            var relief = $("#has_relief");
            var relief_holder = $('#relief_holder');
            relief.on('change', function() {
                showAmount();
            });

            function showAmount() {
                relief.val() == 1 ? relief_holder.show() : relief_holder.hide();
            }

            showAmount();

        });
    </script>
@endsection