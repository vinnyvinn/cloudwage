        <div class="page-head">
            <div class="page-title">
                <h1>Company Profile <small>Set up the details about your organization</small></h1>
            </div>
        </div>
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <a href="{{ url('/') }}">Home</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <a href="{{ route('profile.index') }}">Company Profile</a>
            </li>
        </ul>
        <form action="{{ route('profile.store') }}" method="post" role="form" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="row">
                <div class="col-md-6 ">
                    <div class="portlet light">
                        <div class="portlet-title">
                            <div class="caption font-red-sunglo">
                                <i class="fa fa-briefcase font-red-sunglo"></i>
                                <span class="caption-subject bold uppercase"> Basic Details</span>
                            </div>
                        </div>
                        <div class="portlet-body form">
                            <div class="form-body">
                                <div class="form-group form-md-line-input form-md-floating-label">
                                    <h5>Company Logo</h5>
                                    <img class="img-responsive img-round company-logo" src="{{ $profile->logo }}" alt="{{ $profile->name }}">
                                    <div class="text-center">{{ $profile->name }}</div>
                                    <br>
                                    <input type="file" name="logo">
                                </div>
                                <div class="form-group form-md-line-input form-md-floating-label">
                                    <input type="text" class="form-control" id="name" name="name" value="{{ $profile->name or old('name') }}" required>
                                    <label for="name">Company Name*</label>
                                    <span class="help-block">This is the registered name used to refer to the company</span>
                                </div>
                                <div class="form-group form-md-line-input form-md-floating-label">
                                    <input type="text" class="form-control" id="branch" name="branch" value="{{ $profile->branch or old('branch') }}">
                                    <label for="branch">Branch</label>
                                    <span class="help-block">This is the branch or location of the company</span>
                                </div>

                                <div class="form-group form-md-line-input form-md-floating-label">
                                    <input type="text" class="form-control" id="country" name="country" value="{{ $profile->country or old('country') }}">
                                    <label for="country">Country</label>
                                    <span class="help-block">This is the locality of the company</span>
                                </div>

                                <div class="form-group form-md-line-input form-md-floating-label">
                                    <input type="text" class="form-control" id="city" name="city" value="{{ $profile->city or old('city') }}">
                                    <label for="city">City</label>
                                    <span class="help-block">This is the locality of the company</span>
                                </div>

                                <div class="form-body">
                                    <div class="form-group form-md-line-input form-md-floating-label">
                                        <select name="currency_id" id="currency_id" class="form-control">
                                            @foreach(Payroll\Models\Currency::all() as $currency)
                                                <option value="{{ $currency->id }}" {{ $profile->currency_id == $currency->id ? 'selected' : '' }}>{{ $currency->name }}</option>
                                            @endforeach
                                        </select>
                                        <label for="name">Default Currency*</label>
                                        <span class="help-block">This will be the currency to be used across the system</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="portlet light">
                        <div class="portlet-title">
                            <div class="caption font-red-sunglo">
                                <i class="fa fa-briefcase font-red-sunglo"></i>
                                <span class="caption-subject bold uppercase"> Contact Details</span>
                            </div>
                        </div>
                        <div class="portlet-body form">
                            <div class="form-body">
                                <div class="form-group form-md-line-input form-md-floating-label">
                                    <input type="text" class="form-control" id="phone" name="phone" value="{{ $profile->phone or old('phone') }}">
                                    <label for="phone">Telephone Number</label>
                                    <span class="help-block">This is the Company's Tel. number or land line</span>
                                </div>
                                <div class="form-group form-md-line-input form-md-floating-label">
                                    <input type="text" class="form-control" id="mobile" name="mobile" value="{{ $profile->mobile or old('mobile') }}">
                                    <label for="mobile">Mobile</label>
                                    <span class="help-block">This is the Company's main mobile number</span>
                                </div>

                                <div class="form-group form-md-line-input form-md-floating-label">
                                    <input type="email" class="form-control" id="email" name="email" value="{{ $profile->email or old('email') }}">
                                    <label for="email">Email</label>
                                    <span class="help-block">This is the Company's reachable email address</span>
                                </div>

                                <div class="form-group form-md-line-input form-md-floating-label">
                                    <input type="url" class="form-control" id="website" name="website" value="{{ $profile->website or old('website') }}">
                                    <label for="website">Website</label>
                                    <span class="help-block">This is the Company's website</span>
                                </div>

                                <div class="form-group form-md-line-input form-md-floating-label">
                                    <input type="text" class="form-control" id="postal_address" name="postal_address" value="{{ $profile->postal_address or old('postal_address') }}">
                                    <label for="postal_address">Postal Address</label>
                                    <span class="help-block">This is the Mailing address</span>
                                </div>
                            </div>
                            <div class="form-group form-md-line-input form-md-floating-label">
                                <input type="submit" class="btn btn-primary" value="Update">
                                <a class="btn btn-danger ajaxLink" href="{{ URL::previous() }}">Back</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 ">
                    <div class="portlet light">
                        <div class="portlet-title">
                            <div class="caption font-red-sunglo">
                                <i class="fa fa-briefcase font-red-sunglo"></i>
                                <span class="caption-subject bold uppercase"> Registration Details</span>
                            </div>
                        </div>
                        <div class="portlet-body form">
                            <div class="form-body">
                                <div class="form-group form-md-line-input form-md-floating-label">
                                    <input type="text" class="form-control" id="registration_number" name="registration_number" value="{{ $profile->registration_number or old('registration_number') }}" required>
                                    <label for="registration_number">Registration Number*</label>
                                    <span class="help-block">This is the Company's registration certificate number</span>
                                </div>
                                <div class="form-group form-md-line-input form-md-floating-label">
                                    <input type="text" class="form-control" id="kra_pin" name="kra_pin" value="{{ $profile->kra_pin or old('kra_pin') }}" required>
                                    <label for="kra_pin">KRA Pin*</label>
                                    <span class="help-block">The registered taxpayer Personal Identification Number</span>
                                </div>

                                <div class="form-group form-md-line-input form-md-floating-label">
                                    <input type="text" class="form-control" id="nssf" name="nssf" value="{{ $profile->nssf or old('nssf') }}">
                                    <label for="nssf">NSSF</label>
                                    <span class="help-block">The Registered NSSF Employer Number</span>
                                </div>

                                <div class="form-group form-md-line-input form-md-floating-label">
                                    <input type="text" class="form-control" id="nhif" name="nhif" value="{{ $profile->nhif or old('nhif') }}">
                                    <label for="nhif">NHIF</label>
                                    <span class="help-block">This is the locality of the company</span>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>