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
            <a href="#">Create</a>
        </li>
    </ul>

    <div class="row">
        <div class="col-sm-12">
            <form action="{{ route('work-plans.store') }}" method="post" role="form">
                {{ csrf_field() }}
                <div class="portlet light">
                    <div class="portlet-title">
                        <div class="caption font-red-sunglo">
                            <i class="fa fa-briefcase font-red-sunglo"></i>
                            <span class="caption-subject bold uppercase"> Work Plan Details</span>
                        </div>
                    </div>
                    <div class="portlet-body form">
                        <div class="form-body">
                            <div class="form-group form-md-line-input form-md-floating-label">
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                                <label for="name">Work Plan Name* (e.g. Weekday Morning Shift)</label>
                                <span class="help-block">This is the name of the work plan</span>
                            </div>
                            <div class="form-group form-md-line-input form-md-floating-label">
                                <select class="form-control" id="shift_id" name="shift_id">
                                    @foreach($shifts as $shift)
                                        <option value="{{ $shift->id }}" {{ $shift->id == old('shift_id') ? 'selected' : '' }}>{{ $shift->name }}</option>
                                    @endforeach
                                </select>
                                <label for="name">Shift*</label>
                                <span class="help-block">This is the shift that will be used for the work plan</span>
                            </div>
                            <div class="form-group form-md-line-input" id="days">
                                <label>Days of the Week*</label>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="row">
                                            <div class="form-group">
                                                <input type="checkbox" class="make-switch col-sm-6" name="days_of_week[]" value="Monday" data-on-text="<i class='fa fa-check'></i>" data-off-text="<i class='fa fa-times'></i>">
                                                <label class="col-sm-4">Monday</label>
                                            </div>
                                            <div class="form-group">
                                                <input type="checkbox" class="make-switch col-sm-6" name="days_of_week[]" value="Tuesday" data-on-text="<i class='fa fa-check'></i>" data-off-text="<i class='fa fa-times'></i>">
                                                <label class="col-sm-4">Tuesday</label>
                                            </div>
                                            <div class="form-group">
                                                <input type="checkbox" class="make-switch col-sm-6" name="days_of_week[]" value="Wednesday" data-on-text="<i class='fa fa-check'></i>" data-off-text="<i class='fa fa-times'></i>">
                                                <label class="col-sm-4">Wednesday</label>
                                            </div>
                                            <div class="form-group">
                                                <input type="checkbox" class="make-switch col-sm-6" name="days_of_week[]" value="Thursday" data-on-text="<i class='fa fa-check'></i>" data-off-text="<i class='fa fa-times'></i>">
                                                <label class="col-sm-4">Thursday</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="row">
                                            <div class="form-group">
                                                <input type="checkbox" class="make-switch col-sm-6" name="days_of_week[]" value="Friday" data-on-text="<i class='fa fa-check'></i>" data-off-text="<i class='fa fa-times'></i>">
                                                <label class="col-sm-4">Friday</label>
                                            </div>
                                            <div class="form-group">
                                                <input type="checkbox" class="make-switch col-sm-6" name="days_of_week[]" value="Saturday" data-on-text="<i class='fa fa-check'></i>" data-off-text="<i class='fa fa-times'></i>">
                                                <label class="col-sm-4">Saturday</label>
                                            </div>
                                            <div class="form-group">
                                                <input type="checkbox" class="make-switch col-sm-6" name="days_of_week[]" value="Sunday" data-on-text="<i class='fa fa-check'></i>" data-off-text="<i class='fa fa-times'></i>">
                                                <label class="col-sm-4">Sunday</label>
                                            </div>
                                            <div class="form-group">
                                                <a href="#" id="select_all" class="btn btn-success">Select All</a>
                                                <a href="#" id="select_none" class="btn btn-danger">Select None</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group form-md-line-input form-md-floating-label">
                                <input type="submit" class="btn btn-primary" value="Save">
                                <a class="btn btn-danger" href="{{ route('work-plans.index') }}">Back</a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('footer')
    <script>
        $('#select_all').on('click', function(e) {
            e.preventDefault();
            $('#days input').prop('checked', true);
            $('#days .bootstrap-switch-off').removeClass('bootstrap-switch-off').addClass('bootstrap-switch-on');
            $('#days .bootstrap-switch-container').prop('style', 'width: 114px;margin-left: 0;');

        });
        $('#select_none').on('click', function(e) {
            e.preventDefault();
            $('#days input').prop('checked', false);
            $('#days .bootstrap-switch-on').removeClass('bootstrap-switch-on').addClass('bootstrap-switch-off');
            $('#days .bootstrap-switch-container').prop('style', 'width: 114px;margin-left: -38px;');
        });
    </script>
@endsection