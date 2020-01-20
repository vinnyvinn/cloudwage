@extends('layout')
@section('content')
    <div class="page-head">
        <div class="page-title">
            <h1>Deductions - <small> Set up the deductions that can be assigned to employees in the organization</small></h1>
        </div>
    </div>
    <ul class="page-breadcrumb breadcrumb">
        <li>
            <a href="{{ url('/') }}">Home</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="{{ route('deductions.index') }}">Deductions</a>
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
                            <span class="caption-subject bold uppercase"> {{ $deduction->name }} Details</span>
                        </div>
                    </div>
                    <div class="portlet-body form">
                        <div class="form-body row">
                            <div class="col-sm-6">
                                <div class="form-group form-md-line-input">
                                    <label for="name">Deduction Name</label>
                                    <div type="text" class="form-control">{{ $deduction->name }}</div>
                                </div>
                                <div class="form-group form-md-line-input">
                                    <label for="name">Exemption</label>
                                    <div type="text" class="form-control">Below {{ $currency . ' ' . number_format($deduction->threshold, 2) }}</div>
                                </div>
                                <div class="form-group form-md-line-input">
                                    <label for="name">Has Relief?</label>
                                    <div type="text" class="form-control">{{ $deduction->has_relief == 1 ? 'Yes' : 'No' }}</div>
                                </div>

                                @if($deduction->has_relief)
                                    <div class="form-group form-md-line-input">
                                        <label for="name">Relief Name</label>
                                        <div type="text" class="form-control">{{ $relief->name }}</div>
                                    </div>
                                    <div class="form-group form-md-line-input">
                                        <label for="name">Relief Amount</label>
                                        <div type="text" class="form-control">{{ $relief->amount }}</div>
                                    </div>
                                @endif

                                <div class="form-group form-md-line-input">
                                    <label for="name">Deduction Type</label>
                                    <div type="text" class="form-control">{{ studly_case($deduction->type) }}</div>
                                </div>
                                @if($deduction->type == 'rate')
                                <div class="form-group form-md-line-input">
                                    <label for="name">Rate</label>
                                    <div type="text" class="form-control">{{ strstr($deduction->rate, '%') ? $deduction->rate : $currency . ' ' . number_format($deduction->rate, 2) }}</div>
                                </div>
                                @endif
                                <div class="form-group form-md-line-input">
                                    <a class="btn btn-success" href="{{ route('deductions.edit', ['deductions' => $deduction->id]) }}">Edit</a>
                                    <a href="{{ route('deductions.destroy', $deduction->id) }}" class="btn btn-danger" data-method="delete" rel="nofollow" data-confirm="Are you sure you want to delete this?" data-token="{{ csrf_token() }}">Delete</a>
                                    <a class="btn btn-warning" href="{{ URL::previous() }}">Back</a>
                                </div>
                            </div>
                            <div class="col-sm-6">
                            @if($deduction->type == 'slab')
                                <h4 class="caption-subject bold uppercase">Slabs</h4>
                                <table class="table table-stripped table-hover table-responsive">
                                    <thead>
                                        <tr class="row">
                                            <th class="col-sm-1 text-right">#</th>
                                            <th class="col-sm-4 text-right">From</th>
                                            <th class="col-sm-4 text-right">To</th>
                                            <th class="col-sm-3 text-right">Rate</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($slabs as $slab)
                                        <tr class="row">
                                            <td class="col-sm-1 text-right">{{ $slab->slab_number }}</td>
                                            @if($slab->max_amount == 0)
                                            <td class="col-sm-8 text-right" colspan="2">{{ $slab->max_amount == 0 ? 'Above ' . $currency . ' ' . number_format($slab->min_amount, 2) : $currency . ' ' . number_format($slab->min_amount, 2) }}</td>
                                            @else
                                                <td class="col-sm-4 text-right">{{ $currency . ' ' . number_format($slab->min_amount, 2) }}</td>
                                                <td class="col-sm-4 text-right">{{ $currency . ' ' . number_format($slab->max_amount, 2) }}</td>
                                            @endif
                                            <td class="col-sm-3 text-right">{{ strstr($slab->rate, '%') ? $slab->rate : $currency . ' ' . number_format($slab->rate, 2) }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            @endif
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </div>
@endsection