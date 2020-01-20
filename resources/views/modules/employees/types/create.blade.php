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
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="#">Create</a>
        </li>
    </ul>

    <div class="row">
        <div class="col-sm-12">
            <form action="{{ route('employee-types.store') }}" method="post" role="form">
                {{ csrf_field() }}
                <div class="portlet light">
                    <div class="portlet-title">
                        <div class="caption font-red-sunglo">
                            <i class="fa fa-briefcase font-red-sunglo"></i>
                            <span class="caption-subject bold uppercase"> Employee Type Details</span>
                        </div>
                    </div>
                    <div class="portlet-body form">
                        <div class="form-body">
                            <div class="form-group form-md-line-input form-md-floating-label">
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                                <label for="name">Type Name* (e.g. Permanent & Pensionable)</label>
                                <span class="help-block">This is the name of the employee type</span>
                            </div>

                            <div class="form-group form-md-line-input form-md-floating-label">
                                <select class="form-control" id="payment_structure_id" name="payment_structure_id">
                                @foreach($structures as $structure)
                                        <option value="{{ $structure->id }}" {{ old('payment_structure_id') == $structure->id ? 'selected' : '' }}>{{ $structure->name }}</option>
                                @endforeach
                                </select>
                                <label for="name">Payment Structure*</label>
                                <span class="help-block">This is the type of payment structure to be used by the employee type</span>
                            </div>
                            <div class="form-group form-md-line-input form-md-floating-label">
                                <textarea class="form-control" id="description" name="description" required rows="5">{{ old('description') }}</textarea>
                                <label for="min_amount">Description*</label>
                                <span class="help-block">This is the short description of the employee type</span>
                            </div>
                            <div class="form-group form-md-line-input form-md-floating-label">
                                <input type="submit" class="btn btn-primary" value="Save">
                                <a class="btn btn-danger" href="{{ route('employee-types.index') }}">Back</a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection