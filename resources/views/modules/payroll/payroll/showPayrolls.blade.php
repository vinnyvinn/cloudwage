@extends('layout')
@section('content')
    <div class="page-head">
        <div class="page-title">
            <h1>Payroll - <small> View the currently generated payrolls</small></h1>
        </div>
    </div>
    <ul class="page-breadcrumb breadcrumb">
        <li>
            <a href="{{ url('/') }}">Home</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="{{ route('payroll.index') }}">Payroll</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="#">View</a>
        </li>
    </ul>

    <div class="row">
        <div class="col-sm-12">
                <div class="portlet light">
                    <div class="portlet-title">
                        <div class="caption font-red-sunglo">
                            <i class="fa fa-briefcase font-red-sunglo"></i>
                            <span class="caption-subject bold uppercase"> Payroll Details</span>
                        </div>
                    </div>
                    <div class="portlet-body form">
                        <div class="form-body row">
                            <div class="col-sm-4">
                                <div class="form-group form-md-line-input form-md-floating-label">
                                    <label for="name">Total Payrolls</label>
                                    <div type="text" class="form-control">{{ count($payrolls) }}</div>
                                </div>

                                <div class="form-group form-md-line-input form-md-floating-label">
                                    <a class="btn btn-warning" href="{{ URL::previous() }}">Back</a>
                                </div>
                            </div>
                            <div class="col-sm-8">
                                <iframe src="{{ route('payroll.pdfs') . $allurl }}#zoom=60" class="iPdf" frameborder="0"></iframe>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>
@endsection