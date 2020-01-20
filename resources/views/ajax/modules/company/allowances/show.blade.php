@extends('layout')
@section('content')
    <div class="page-head">
        <div class="page-title">
            <h1>Allowances - <small> View an allowance and its details</small></h1>
        </div>
    </div>
    <ul class="page-breadcrumb breadcrumb">
        <li>
            <a href="{{ url('/') }}">Home</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="{{ route('allowances.index') }}">Allowances</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="#">View</a>
        </li>
    </ul>

    <div class="row">
        <div class="col-sm-12">
            <form action="{{ route('allowances.update', ['id' => $allowance->id]) }}" method="post" role="form">
                {{ csrf_field() }}
                {{ method_field('put') }}
                <div class="portlet light">
                    <div class="portlet-title">
                        <div class="caption font-red-sunglo">
                            <i class="fa fa-briefcase font-red-sunglo"></i>
                            <span class="caption-subject bold uppercase"> Allowance Details</span>
                        </div>
                    </div>
                    <div class="portlet-body form">
                        <div class="form-body row">
                            <div class="col-sm-6">
                                <div class="form-group form-md-line-input form-md-floating-label">
                                    <label for="name">Allowance Name</label>
                                    <div type="text" class="form-control">{{ $allowance->name }}</div>
                                </div>
                                <div class="form-group form-md-line-input form-md-floating-label">
                                    <label for="name">Currency</label>
                                    <div type="text" class="form-control">{{ $allowance->currency->name }}</div>
                                </div>
                                <div class="form-group form-md-line-input form-md-floating-label">
                                    <label for="name">Minimum Amount</label>
                                    <div type="text" class="form-control">{{ $allowance->currency->code . ' ' . number_format($allowance->min_amount, 2) }}</div>
                                </div>
                                <div class="form-group form-md-line-input form-md-floating-label">
                                    <label for="name">Maximum Amount</label>
                                    <div type="text" class="form-control">{{ $allowance->currency->code . ' ' . number_format($allowance->max_amount, 2) }}</div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group form-md-line-input form-md-floating-label">
                                    <label for="name">Added To Basic Salary</label>
                                    <div type="text" class="form-control">{{ $allowance->added_to_basic == 1 ? 'Yes' : 'No' }}</div>
                                </div>
                                <div class="form-group form-md-line-input form-md-floating-label">
                                    <label for="name">Has Relief?</label>
                                    <div type="text" class="form-control">{{ $allowance->has_relief == 1 ? 'Yes' : 'No' }}</div>
                                </div>

                                @if($allowance->has_relief)
                                    <div class="form-group form-md-line-input form-md-floating-label">
                                        <label for="name">Relief Name</label>
                                        <div type="text" class="form-control">{{ $relief->name }}</div>
                                    </div>
                                    <div class="form-group form-md-line-input form-md-floating-label">
                                        <label for="name">Relief Amount</label>
                                        <div type="text" class="form-control">{{ $relief->amount }}</div>
                                    </div>
                                @endif

                                <div class="form-group form-md-line-input form-md-floating-label">
                                    <a class="btn btn-success" href="{{ route('allowances.edit', ['allowances' => $allowance->id]) }}">Edit</a>
                                    <a href="{{ route('allowances.destroy', $allowance->id) }}" class="btn btn-danger" data-method="delete" rel="nofollow" data-confirm="Are you sure you want to delete this?" data-token="{{ csrf_token() }}">Delete</a>
                                    <a class="btn btn-warning" href="{{ URL::previous() }}">Back</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection