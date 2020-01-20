@extends('layout')
@section('content')
    <div class="page-head">
        <div class="page-title">
            <h1>Shifts - <small> Set up the shifts that the organization has</small></h1>
        </div>
    </div>
    <ul class="page-breadcrumb breadcrumb">
        <li>
            <a href="{{ url('/') }}">Home</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="{{ route('shifts.index') }}">Shifts</a>
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
                            <span class="caption-subject bold uppercase"> Shift Details</span>
                        </div>
                    </div>
                    <div class="portlet-body form">
                        <div class="form-body row">
                            <div class="col-sm-6">
                                <div class="form-group form-md-line-input form-md-floating-label">
                                    <label for="name">Shift Name</label>
                                    <div type="text" class="form-control">{{ $shift->name }}</div>
                                </div>
                                <div class="form-group form-md-line-input form-md-floating-label">
                                    <label for="name">Overnight</label>
                                    <div type="text" class="form-control">{{ $shift->is_overnight ? 'Yes' : 'No' }}</div>
                                </div>
                                <div class="form-group form-md-line-input form-md-floating-label">
                                    <label for="name">Time Allowance</label>
                                    <div type="text" class="form-control">{{ $shift->time_allowance }} Minutes</div>
                                </div>
                                <div class="form-group form-md-line-input form-md-floating-label">
                                    <label for="name">Breaks</label>
                                    <div type="text" class="form-control">{{ $shift->breaks }} Minutes</div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group form-md-line-input form-md-floating-label">
                                    <label for="name">Shift Start</label>
                                    <div type="text" class="form-control">{{ substr($shift->shift_start, 0, -3) }}</div>
                                </div>
                                <div class="form-group form-md-line-input form-md-floating-label">
                                    <label for="name">Shift End</label>
                                    <div type="text" class="form-control">{{ substr($shift->shift_end, 0, -3) }}</div>
                                </div>
                                <div class="form-group form-md-line-input form-md-floating-label">
                                    <label for="name">Shift Duration</label>
                                    <div type="text" class="form-control">Including Breaks: {{ (($shift->shift_hours + $shift->breaks) - (($shift->shift_hours + $shift->breaks) % 60)) / 60 }} Hours {{ ($shift->shift_hours + $shift->breaks) % 60 }} Minutes</div>
                                    <div type="text" class="form-control">Excluding Breaks: {{ ($shift->shift_hours - ($shift->shift_hours % 60)) / 60 }} Hours {{ $shift->shift_hours % 60 }} Minutes</div>
                                </div>
                                <div class="form-group form-md-line-input form-md-floating-label">
                                    <a class="btn btn-success" href="{{ route('shifts.edit', $shift->id) }}">Edit</a>
                                    <a href="{{ route('shifts.destroy', $shift->id) }}" class="btn btn-danger" data-method="delete" rel="nofollow" data-confirm="Are you sure you want to delete this?" data-token="{{ csrf_token() }}">Delete</a>
                                    <a class="btn btn-warning" href="{{ route('shifts.index') }}">Back</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>
@endsection