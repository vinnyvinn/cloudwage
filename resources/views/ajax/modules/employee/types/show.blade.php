@extends('layout')
@section('content')
    <div class="page-head">
        <div class="page-title">
            <h1>Employee Types - <small> Set up the types of employees that the organization has</small></h1>
        </div>
    </div>
    <ul class="page-breadcrumb breadcrumb">
        <li>
            <a href="{{ url('/') }}">Home</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="{{ route('employee-types.index') }}">Employee Types</a>
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
                            <span class="caption-subject bold uppercase"> Employee Type Details</span>
                        </div>
                    </div>
                    <div class="portlet-body form">
                        <div class="form-body row">
                            <div class="col-sm-12">
                                <div class="form-group form-md-line-input form-md-floating-label">
                                    <label for="name">Type Name</label>
                                    <div type="text" class="form-control">{{ $type->name }}</div>
                                </div>
                                <div class="form-group form-md-line-input form-md-floating-label">
                                    <label for="name">Payment Structure</label>
                                    <div type="text" class="form-control">
                                        <a href="{{ route('payment-structures.show', $type->paymentStructure->id) }}">
                                            {{ $type->paymentStructure->name }}
                                        </a>
                                    </div>
                                </div>
                                <div class="form-group form-md-line-input form-md-floating-label">
                                    <label for="name">Description</label>
                                    <div type="text" class="form-control">{{ $type->description }}</div>
                                </div>
                                <div class="form-group form-md-line-input form-md-floating-label">
                                    <a class="btn btn-success" href="{{ route('employee-types.edit', $type->id) }}">Edit</a>
                                    <a href="{{ route('employee-types.destroy', $type->id) }}" class="btn btn-danger" data-method="delete" rel="nofollow" data-confirm="Are you sure you want to delete this?" data-token="{{ csrf_token() }}">Delete</a>
                                    <a class="btn btn-warning" href="{{ URL::previous() }}">Back</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>
@endsection