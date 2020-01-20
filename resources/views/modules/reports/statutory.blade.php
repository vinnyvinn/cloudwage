@extends('layout')
@section('content')
    <div class="page-head">
        <div class="page-title">
            <h1>Statutory Files - <small> Generate statutory files</small></h1>
        </div>
    </div>
    <ul class="page-breadcrumb breadcrumb">
        <li>
            <a href="{{ url('/') }}">Home</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="#">Generate</a>
        </li>
    </ul>

    <div class="row">
        <div class="col-sm-12">
            <div class="portlet light">
                <div class="portlet-title">
                    <i class="fa fa-briefcase font-red-sunglo"></i>
                    <span class="caption-subject bold uppercase">Details</span>
                </div>
                <div class="portlet-body">
                    <form action="{{ route('statutory-export') }}" method="post">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group form-md-line-input form-md-floating-label">
                                    <label for="payroll_date">Payroll For*</label>
                                    <select required name="payroll_date" id="payroll_date" class="form-control margin-bottom-5">
                                        @foreach($months as $month)
                                            <option value="{{ $month['id'] }}">{{ $month['value'] }}</option>
                                        @endforeach
                                    </select>
                                    <span>This is month for which you want to generate the report</span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group form-md-line-input form-md-floating-label">
                                    <label for="file">Document Type</label>
                                    <select class="form-control" name="file" id="file">
                                        <option value="intpaye"> PAYE</option>
                                        <option value="nssf">NSSF</option>
                                        <option value="nhif">NHIF</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group form-md-line-input form-md-floating-label">
                            <input type="submit" class="btn btn-primary" value="Generate">
                            <a class="btn btn-danger" href="{{ URL::previous() }}">Back</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endsection
