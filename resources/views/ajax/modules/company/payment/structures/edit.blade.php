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
            <a href="#">Edit</a>
        </li>
    </ul>

    <div class="row">
        <div class="col-sm-12">
            <form action="{{ route('payment-structures.update', $structure->id) }}" method="post" role="form">
                {{ csrf_field() }}
                {{ method_field('put') }}
                <div class="portlet light">
                    <div class="portlet-title">
                        <div class="caption font-red-sunglo">
                            <i class="fa fa-briefcase font-red-sunglo"></i>
                            <span class="caption-subject bold uppercase"> Payment Type Details</span>
                        </div>
                    </div>
                    <div class="portlet-body form">
                        <div class="form-body">
                            <div class="form-group form-md-line-input form-md-floating-label">
                                <input type="text" class="form-control" id="name" name="name" value="{{ $structure->name or old('name') }}" required>
                                <label for="name">Payment Type Name e.g. Per Month*</label>
                                <span class="help-block">This is the name of the payment structure</span>
                            </div>
                            <div class="form-group form-md-line-input form-md-floating-label">
                                <textarea type="text" class="form-control" id="description" name="description" required rows="5">{{ $structure->description or old('name') }}</textarea>
                                <label for="name">Description*</label>
                                <span class="help-block">A short description of the payment structure</span>
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
