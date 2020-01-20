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
            <a href="#">Edit</a>
        </li>
    </ul>

    <div class="row">
        <div class="col-sm-12">
            <form action="{{ route('shifts.update', $shift->id) }}" method="post" role="form">
                {{ csrf_field() }}
                {{ method_field('put') }}
                <div class="portlet light">
                    <div class="portlet-title">
                        <div class="caption font-red-sunglo">
                            <i class="fa fa-briefcase font-red-sunglo"></i>
                            <span class="caption-subject bold uppercase"> Shift Details</span>
                        </div>
                    </div>
                    <div class="portlet-body form">
                        <div class="form-body">
                            <div class="form-group form-md-line-input form-md-floating-label">
                                <input type="text" class="form-control" id="name" name="name" value="{{ $shift->name or old('name') }}" required>
                                <label for="name">Shift Name* (e.g. Morning Shift)</label>
                                <span class="help-block">This is the name of the shift</span>
                            </div>
                            <div class="form-group form-md-line-input form-md-floating-label">
                                <input type="number" class="form-control" id="time_allowance" name="time_allowance" min="0" value="{{ $shift->time_allowance or old('time_allowance') }}" required>
                                <label for="name">Time Allowance in Minutes*</label>
                                <span class="help-block">This is the time in minutes allowed before the employee is considered late</span>
                            </div>
                            <div class="form-group form-md-line-input form-md-floating-label">
                                <input type="number" class="form-control" id="breaks" name="breaks" min="0" value="{{ $shift->breaks or old('breaks') }}" required>
                                <label for="name">Breaks in Minutes*</label>
                                <span class="help-block">This is the time in minutes considered as a break during the shift</span>
                            </div>
                            <div class="row">
                                <div class="form-group form-md-line-input form-md-floating-label col-sm-3">
                                    <label for="is_overnight">Overnight</label><br/>
                                    <input type="checkbox" class="make-switch" {{ $shift->is_overnight ? 'checked' : '' }} data-on-text="YES" data-off-text="NO" data-on="success" data-off="warning" id="is_overnight" name="is_overnight">
                                    <br/><br/>
                                    <span>Is the shift supposed to run overnight to the next day</span>
                                </div>
                                <div class="form-group form-md-line-input col-sm-9">
                                    <label for="min_amount">Shift Period*</label>
                                    <div class="slider-value">
                                        Starts At: <strong id="start_at">10:00 AM</strong><br/>
                                    </div>
                                    <div id="slider-start-time" class="slider bg-blue"></div>
                                    <div class="slider-value">
                                        Ends At: <strong id="end_at">12:00 PM</strong><br/>
                                    </div>
                                    <div id="slider-end-time" class="slider bg-red"></div>
                                    <div class="slider-value">
                                        Shift duration including breaks: <strong id="timeDiff"></strong><br/>
                                        Shift duration excluding breaks: <strong id="timeDiffNoBreak"></strong>
                                    </div>
                                    <input type="hidden" name="shift_start" id="shift_start" value="10:00">
                                    <input type="hidden" name="shift_end" id="shift_end" value="12:00">
                                    <input type="hidden" name="shift_hours" id="shift_hours" value="2">
                                </div>
                            </div>
                            <div class="form-group form-md-line-input form-md-floating-label">
                                <input type="submit" class="btn btn-primary" value="Save">
                                <a class="btn btn-danger" href="{{ route('shifts.index') }}">Back</a>
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

        $('#breaks').on('change', function() {
            calculateDifference();
        });

        $('#breaks').on('keyup', function() {
            calculateDifference();
        });

        function calculateDifference()
        {
            var endTime = parseTime($('input[name="shift_end"]').val());
            var startTime = parseTime($('input[name="shift_start"]').val());
            var breaks = $('#breaks').val();

            if($('#is_overnight').prop('checked'))
            {
                endTime = endTime + (12 * 60);
                startTime = startTime - (12 * 60);
            }

            var difference = (endTime - startTime);
            var difNoBreak = difference - breaks;

            var diffHours = Math.floor(difference / 60);
            var diffMinutes = difference - (diffHours * 60);
            var diffHoursNoBreak = Math.floor(difNoBreak / 60);
            var diffMinutesNoBreak = difNoBreak - (diffHoursNoBreak * 60);

            $('#timeDiff').html(diffHours + ' Hours ' + diffMinutes + ' Minutes');
            $('#timeDiffNoBreak').html(diffHoursNoBreak + ' Hours ' + diffMinutesNoBreak + ' Minutes');
            $('#shift_hours').val(difference);
        }

        function parseTime(s) {
            var c = s.split(':');
            return parseInt(c[0]) * 60 + parseInt(c[1]);
        }


        $('#is_overnight').on('switchChange.bootstrapSwitch', function(event, state) {
            $("#slider-start-time").slider('option', 'min', 0);
            $("#slider-end-time").slider('option', 'min', 0);
            var startInput = $('#shift_start');
            var startLabel = $('#start_at');
            var endInput = $('#shift_end');
            var endLabel = $('#end_at');

            if(state) {
                $('#slider-range-max').removeClass('bg-red');
                $('#slider-range-max').addClass('bg-blue');
            } else {
                $('#slider-range-max').addClass('bg-red');
                $('#slider-range-max').removeClass('bg-blue');
            }
            var endVal = startInput.val();
            startInput.val(endInput.val());
            endInput.val(endVal);
            var startVal = startLabel.html();
            startLabel.html(endLabel.html());
            endLabel.html(startVal);

            calculateDifference();
        });

        <?php
            $start = ((explode(':', $shift->shift_start)[0])*60) + explode(':', $shift->shift_start)[1];
            $end = ((explode(':', $shift->shift_end)[0])*60) + explode(':', $shift->shift_end)[1];
            if($shift->is_overnight)
            {
                $newStart = $end;
                $end = $start;
                $start = $newStart;
            }
        ?>

        $("#slider-start-time").slider({
            min: 0,
            max: 1440,
            step: 15,
            value: {{ $start }},
            slide: function (e, ui) {
                checkSlider1(ui);
            }
        });

        $("#slider-end-time").slider({
            min: 600,
            max: 1440,
            step: 15,
            value: {{ $end }},
            slide: function (e, ui) {
                checkSlider2(ui);
            }
        });

        function checkSlider2(ui) {

            var hours2 = Math.floor(ui.value / 60);
            var minutes2 = ui.value - (hours2 * 60);

            if (hours2.length == 1) hours2 = '0' + hours2;
            if (minutes2.length == 1) minutes2 = '0' + minutes2;
            if (minutes2 == 0) minutes2 = '00';
            $('#shift_end').val(hours2 + ':' + minutes2);
            if (hours2 >= 12) {
                if (hours2 == 12) {
                    hours2 = hours2;
                    minutes2 = minutes2 + " PM";
                } else if (hours2 == 24) {
                    hours2 = 11;
                    minutes2 = "59 PM";
                } else {
                    hours2 = hours2 - 12;
                    minutes2 = minutes2 + " PM";
                }
            } else {
                hours2 = hours2;
                minutes2 = minutes2 + " AM";
            }

            $('#end_at').html(hours2 + ':' + minutes2);

            if($('#is_overnight').prop('checked'))
            {
                $("#slider-start-time").slider('option', 'min', ui.value);
            }

            calculateDifference();
        }

        function checkSlider1(ui)
        {
            var hours1 = Math.floor(ui.value / 60);
            var minutes1 = ui.value - (hours1 * 60);

            if (hours1.length == 1) hours1 = '0' + hours1;
            if (minutes1.length == 1) minutes1 = '0' + minutes1;
            if (minutes1 == 0) minutes1 = '00';
            $('#shift_start').val(hours1 + ':' + minutes1);
            if (hours1 >= 12) {
                if (hours1 == 12) {
                    hours1 = hours1;
                    minutes1 = minutes1 + " PM";
                } else {
                    hours1 = hours1 - 12;
                    minutes1 = minutes1 + " PM";
                }
            } else {
                hours1 = hours1;
                minutes1 = minutes1 + " AM";
            }
            if (hours1 == 0) {
                hours1 = 12;
                minutes1 = minutes1;
            }

            $('#start_at').html(hours1 + ':' + minutes1);

            if( ! $('#is_overnight').prop('checked'))
            {
                $("#slider-end-time").slider('option', 'min', ui.value);
            }

            calculateDifference();
        }

        var ui1 = {
            value: $("#slider-start-time").slider('value')
        }
        var ui2 = {
            value: $("#slider-end-time").slider('value')
        }

        checkSlider1(ui1);
        checkSlider2(ui2)

        @if($shift->is_overnight)
            $('#is_overnight').trigger('switchChange.bootstrapSwitch');
        @endif

    </script>
@endsection