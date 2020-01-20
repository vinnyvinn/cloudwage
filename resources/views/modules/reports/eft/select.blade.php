@extends('layout')

@section('content')
    <div class="page-head">
        <div class="page-title">
            <h1>Electronic Funds Transfer - <small> Generate EFT report</small></h1>
        </div>
    </div>
    <ul class="page-breadcrumb breadcrumb">
        <li>
            <a href="{{ url('/') }}">Home</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="{{ route('eft.index') }}">Electronic Funds Transfer</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="#">Generate</a>
        </li>
    </ul>

    <div class="row">
        <div class="col-sm-12">
            <form action="{{ route('eft.report') }}" method="post" role="form" target="_blank">
                {{ csrf_field() }}
                <div class="row">
                    <div class="col-sm-12">
                        <div class="portlet light">
                            <div class="portlet-title">
                                <div class="caption font-red-sunglo">
                                    <i class="fa fa-briefcase font-red-sunglo"></i>
                                    <span class="caption-subject bold uppercase"> EFT Details</span>
                                </div>
                            </div>
                            <div class="portlet-body form">
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group form-md-line-input form-md-floating-label">
                                                <label for="month">Payroll For*</label>
                                                <select required name="month" id="month" class="form-control margin-bottom-5">
                                                @foreach($months as $month)
                                                    <option value="{{ $month }}">{{ $month->format('F Y') }}</option>
                                                @endforeach
                                                </select>
                                                <span>This is month for which you want to generate the report</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group form-md-line-input form-md-floating-label">
                                        <input type="submit" class="btn btn-primary" value="Generate">
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