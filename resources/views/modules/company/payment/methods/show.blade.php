@extends('layout')
@section('content')
    <div class="page-head">
        <div class="page-title">
            <h1>Payment Methods - <small> Set up the modes of payments that can be used within the organization</small></h1>
        </div>
    </div>
    <ul class="page-breadcrumb breadcrumb">
        <li>
            <a href="{{ url('/') }}">Home</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="{{ route('payment-methods.index') }}">Payment Methods</a>
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
                        <span class="caption-subject bold uppercase"> {{ $method->name }} Details</span>
                    </div>
                </div>
                <div class="portlet-body form">
                    <div class="form-body row">
                        <div class="col-sm-6">
                            <div class="form-group form-md-line-input">
                                <label for="name">Payment Method Name*</label>
                                <div type="text" class="form-control">{{ $method->name }}</div>
                            </div>
                            <div class="form-group form-md-line-input">
                                <label for="name">Description</label>
                                <div type="text" class="form-control">{{ $method->description }}</div>
                            </div>
                            <div class="form-group form-md-line-input">
                                <a href="{{ route('payment-methods.edit', $method->id) }}" class="btn btn-info">Edit</a>
                                <a href="{{ route('payment-methods.destroy', $method->id) }}" class="btn btn-danger" data-method="delete" rel="nofollow" data-confirm="Are you sure you want to delete this?" data-token="{{ csrf_token() }}">Delete</a>
                                <a class="btn btn-warning" href="{{ URL::previous() }}">Back</a>
                            </div>
                        </div>
                        <div class="col-sm-6">

                            <h4 class="caption-subject bold uppercase">Fields</h4>
                            <table class="table table-stripped table-hover table-responsive">
                                <thead>
                                <tr class="row">
                                    <th class="col-sm-1 text-right">#</th>
                                    <th class="col-sm-11 text-right">Field Name</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $x = 1; ?>
                                @foreach($fields as $field)
                                    <tr class="row">
                                        <td class="col-sm-1 text-right">{{ $x }}</td>
                                        <td class="col-sm-1 text-right">{{ studly_case($field->field_name) }}</td>
                                    </tr>
                                    <?php $x++; ?>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection