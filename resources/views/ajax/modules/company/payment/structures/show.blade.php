@extends('layout')
@section('content')
    <div class="page-head">
        <div class="page-title">
            <h1>Payment Structures - <small> Set up the payment structures that can be used within the organization</small></h1>
        </div>
    </div>
    <ul class="page-breadcrumb breadcrumb">
        <li>
            <a href="{{ url('/') }}">Home</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="{{ route('payment-structures.index') }}">Payment Structures</a>
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
                        <span class="caption-subject bold uppercase"> {{ $structure->name }} Details</span>
                    </div>
                </div>
                <div class="portlet-body form">
                    <div class="form-body row">
                        <div class="col-sm-6">
                            <div class="form-group form-md-line-input">
                                <label for="name">Payment Type Name*</label>
                                <div type="text" class="form-control">{{ $structure->name }}</div>
                            </div>
                            <div class="form-group form-md-line-input">
                                <label for="name">Description</label>
                                <p>{{ $structure->description }}</p>
                            </div>
                            <div class="form-group form-md-line-input">
                                <a class="btn btn-success" href="{{ route('payment-structures.edit', $structure->id) }}">Edit</a>
                                <a href="{{ route('payment-structures.destroy', $structure->id) }}" class="btn btn-danger" data-method="delete" rel="nofollow" data-confirm="Are you sure you want to delete this?" data-token="{{ csrf_token() }}">Delete</a>
                                <a class="btn btn-warning" href="{{ URL::previous() }}">Back</a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection