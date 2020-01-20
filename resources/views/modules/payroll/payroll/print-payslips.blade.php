@extends('layout')

@section('content')
    <div class="page-head">
        <div class="page-title">
            <h1>Payroll - <small> print all payslips for aprticular payroll</small></h1>
        </div>
    </div>
    <ul class="page-breadcrumb breadcrumb">
        <li>
            <a href="{{ url('/') }}">Home</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="{{ route('payroll.index') }}">Payroll</a>
        </li>
    </ul>

    <div class="row">
        <div class="col-sm-12">
            <!-- BEGIN PORTLET-->
            <div class="portlet light ">
                <div class="portlet-title">
                    <div class="caption caption-md">
                        <i class="icon-bar-chart theme-font-color hide"></i>
                        <span class="caption-subject theme-font-color bold uppercase">Generated Payrolls</span>
                    </div>
                    
                </div>
                <div class="portlet-body">
                    <div class="row">
                    <form method="post" action="{{ route('payroll.pdfs')}}">
                    	<div class="col-sm-6 col-sm-offset-3"><h3>Select a month to for which to print payslips for</h3></div>
                        
                        <div class="col-sm-6 col-sm-offset-3">
                        	{{ csrf_field() }}
                            <select name="month" id="month" class="form-control change">
                                <option disabled selected>Select Payroll Month</option>
                            @foreach($sub_filters as $filter)
                                <option value="{{ $filter->payroll_date->month . '-' . $filter->payroll_date->year }}">{{ $filter->payroll_date->startOfMonth()->toFormattedDateString() .' - '. $filter->payroll_date->toFormattedDateString() }}</option>
                            @endforeach
                            </select>
                        </div>
                        
                        <div class="col-sm-6 col-sm-offset-3">
                        	<hr>
                        	<button class="btn btn-primary" type="submit">Print payslip Batch</button>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection