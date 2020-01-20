@extends('layout')
@section('content')
    <div class="page-head">
        <div class="page-title">
            <h1>Work Plans - <small> Set up the default allocations of shifts to days that the organization is open</small></h1>
        </div>
    </div>
    <ul class="page-breadcrumb breadcrumb">
        <li>
            <a href="{{ url('/') }}">Home</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="{{ route('work-plans.index') }}">Work Plans</a>
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
                            <span class="caption-subject bold uppercase"> Work Plan Details</span>
                        </div>
                    </div>
                    <div class="portlet-body form">
                        <div class="form-body row">
                            <div class="col-sm-6">
                                <div class="form-group form-md-line-input form-md-floating-label">
                                    <label for="name">Work Plan Name</label>
                                    <div type="text" class="form-control">{{ $plan->name }}</div>
                                </div>
                                <div class="form-group form-md-line-input form-md-floating-label">
                                    <label for="name">Shift</label>
                                    <div type="text" class="form-control">
                                        <a href="{{ route('shifts.show', $plan->shift_id) }}">{{ $plan->shift->name }}</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group form-md-line-input form-md-floating-label">
                                    <label for="name">Days Allocated</label>
                                    <div type="text" class="form-control">{{ $plan->days_of_week }}</div>
                                </div>
                                <div class="form-group form-md-line-input form-md-floating-label">
                                    <a class="btn btn-success" href="{{ route('work-plans.edit', $plan->id) }}">Edit</a>
                                    <a href="{{ route('work-plans.destroy', $plan->id) }}" class="btn btn-danger" data-method="delete" rel="nofollow" data-confirm="Are you sure you want to delete this?" data-token="{{ csrf_token() }}">Delete</a>
                                    <a class="btn btn-warning" href="{{ route('work-plans.index') }}">Back</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>
@endsection