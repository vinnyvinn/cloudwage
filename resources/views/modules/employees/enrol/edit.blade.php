@extends('layout')
@section('content')
    <div class="page-head">
        <div class="page-title">
            <h1>Employees - <small> View the currently employed persons</small></h1>
        </div>
    </div>
    <ul class="page-breadcrumb breadcrumb">
        <li>
            <a href="{{ url('/') }}">Home</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="{{ route('employees.index') }}">Employee</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="#">Create</a>
        </li>
    </ul>
    <div class="row">
        <div class="col-sm-12">
            <form action="{{ route('employees.update', $input->id) }}" method="post" role="form">
                {{ csrf_field() }}
                {{ method_field('put') }}
                <div class="row">
                    <div class="col-sm-6">
                        <div class="portlet light">
                            <div class="portlet-title">
                                <div class="caption font-red-sunglo">
                                    <i class="fa fa-briefcase font-red-sunglo"></i>
                                    <span class="caption-subject bsession uppercase"> Employee Details</span>
                                </div>
                            </div>
                            <div class="portlet-body form">
                                <div class="form-body">
                                    <div class="form-group form-md-line-input form-md-floating-label">
                                        <input type="text" class="form-control" id="payroll_number" name="payroll_number" value="{{ $input->payroll_number }}" required>
                                        <label for="payroll_number">Payroll Number* (e.g. WI02314/2013)</label>
                                        <span class="help-block">This is the payroll number that will be used to refer to the employee</span>
                                    </div>
                                    <div class="form-group form-md-line-input form-md-floating-label">
                                        <input type="text" class="form-control" id="first_name" name="first_name" value="{{ $input->first_name }}" required>
                                        <label for="first_name">First Name*</label>
                                        <span class="help-block">This is the first name of the employee</span>
                                    </div>
                                    <div class="form-group form-md-line-input form-md-floating-label">
                                        <input type="text" class="form-control" id="last_name" name="last_name" value="{{ $input->last_name }}" required>
                                        <label for="last_name">Last Name*</label>
                                        <span class="help-block">This is the last name of the employee</span>
                                    </div>
                                    <div class="form-group form-md-line-input">
                                        <select class="form-control" id="identification_type" name="identification_type">
                                            <option value="National ID" {{ $input->identification_type == "National ID" ? 'selected' : '' }}>National ID</option>
                                            <option value="Passport" {{ $input->identification_type == "Passport" ? 'selected' : '' }}>Passport</option>
                                        </select>
                                        <label for="identification_type">Identification Type*</label>
                                        <span class="help-block">This is the type of identification to be used by the employee</span>
                                    </div>
                                    <div class="form-group form-md-line-input form-md-floating-label">
                                        <input type="text" class="form-control" id="identification_number" name="identification_number" value="{{ $input->identification_number }}" required>
                                        <label for="identification_number">Identification Number*</label>
                                        <span class="help-block">This is either the passport number or the national ID of the employee</span>
                                    </div>
                                    <div class="form-group form-md-line-input form-md-floating-label">
                                        <input type="submit" class="btn btn-primary" value="Save">
                                        <a class="btn btn-danger" href="{{ route('employees.index') }}">Back</a>
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
                                    <span class="caption-subject bsession uppercase"> Other Details</span>
                                </div>
                            </div>
                            <div class="portlet-body form">
                                <div class="form-body">
                                    <div class="form-group form-md-line-input">
                                        <select class="form-control" id="payment_structure_id" name="payment_structure_id">
                                            @foreach($structures as $structure)
                                                <option value="{{ $structure->id }}" {{ $structure->id == $input->payment_structure_id ? 'selected' : '' }}>{{ $structure->name }}</option>
                                            @endforeach
                                        </select>
                                        <label for="identification_type">Payment Structure *</label>
                                        <span class="help-block">This is the type of payment structure to be used by the employee</span>
                                    </div>
                                    <div class="form-group form-md-line-input form-md-floating-label">
                                        <input type="text" id="mobile_phone" name="mobile_phone" class="form-control" value="{{ $input->mobile_phone }}" required>
                                        <label for="mobile_phone">Mobile Phone*</label>
                                        <span class="help-block">This is the mobile phone number of the employee</span>
                                    </div>
                                    <div class="form-group form-md-line-input form-md-floating-label">
                                        <input type="email" value="{{ $input->email }}" id="email" name="email" class="form-control" required>
                                        <label for="email">Email*</label>
                                        <span class="help-block">This is the personal email of the employee</span>
                                    </div>
                                    <div class="form-group">
                                        <label for="has_custom_tax_rate" class="col-sm-8 control-label">Has Custom Tax Rate?</label>
                                        <div class="col-sm-4">
                                            <input type="checkbox" name="has_custom_tax_rate"{{ $input->has_custom_tax_rate ? ' checked' : '' }} id="has_custom_tax_rate" class="make-switch" data-on-text="<i class='fa fa-check'></i>" data-off-text="<i class='fa fa-times'></i>">
                                        </div>
                                    </div>

                                    <div class="clearfix"></div>
                                    <br>

                                    <div class="form-group" id="customTaxHolder"{!! $input->has_custom_tax_rate ? '' : ' style="display: none;"' !!}>
                                        <label for="custom_tax_rate" class="col-sm-8 control-label">Custom Tax Rate</label>
                                        <div class="col-sm-4">
                                            <input max="90" type="number" name="custom_tax_rate" id="custom_tax_rate" value="{{ $input->custom_tax_rate }}" class="form-control">
                                        </div>
                                    </div>

                                    <div class="clearfix"></div>
                                    <br>
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
            $('.make-switch').on('switchChange.bootstrapSwitch', function(event, state) {
                var inputElemId = $(this).attr('id');
                if (state) {
                    if (inputElemId == 'has_custom_tax_rate'){
                        $('#customTaxHolder').removeAttr('style');
                    }
                } else {
                    if (inputElemId == 'has_custom_tax_rate'){
                        $('#customTaxHolder').val('').attr('style', 'display:none');
                    }
                }

            });
        });
    </script>
@endsection

