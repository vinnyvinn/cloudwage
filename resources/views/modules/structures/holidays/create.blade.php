@extends('layout')
@section('content')
    <div class="page-head">
        <div class="page-title">
            <h1>Holidays - <small> Set up the days that are considered holidays within the organization</small></h1>
        </div>
    </div>
    <ul class="page-breadcrumb breadcrumb">
        <li>
            <a href="{{ url('/') }}">Home</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="{{ route('holidays.index') }}">Holidays</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="#">Create</a>
        </li>
    </ul>

    <div class="row">
        <div class="col-sm-12">
                <div class="portlet light">
                    <div class="portlet-title">
                        <div class="caption font-red-sunglo">
                            <i class="fa fa-briefcase font-red-sunglo"></i>
                            <span class="caption-subject bold uppercase"> Holiday Details</span>
                        </div>
                    </div>
                    <div class="portlet-body form">
                        <div class="form-body">
                            <div class="calendar"></div>
                            <div class="form-group form-md-line-input form-md-floating-label">
                                <a class="btn btn-danger" href="{{ route('holidays.index') }}">Back</a>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>

    <div class="modal fade bs-modal-sm" id="addEvent" tabindex="-1" role="dialog" aria-hidden="true" style="position: fixed; top: 30% !important;">
        <div class="modal-dialog modal-sm">
            <form action="{{ route('holidays.store') }}" method="post" role="form">
                {{ csrf_field() }}
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                        <h4 class="modal-title">Holiday Details</h4>
                    </div>
                    <div class="modal-body">
                        <label for="event_name">Holiday Name</label>
                        <input type="text" id="event_name" class="form-control" name="name" required>
                        <input type="hidden" name="holiday_day" id="holiday_day">
                        <input type="hidden" name="holiday_month" id="holiday_month">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn blue" id="saveEvent">Save changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection

@section('footer')
    <script>
        $(document).ready(function() {
            $('.calendar').fullCalendar({
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,basicWeek'
                },
                defaultDate: '2016-01-12',
                editable: true,
                eventLimit: true,
                events: [
                @foreach($holidays as $holiday)
                    {
                        allDay: true,
                        id: {{ $holiday->id }},
                        title: '{{ $holiday->name }}',
                        start: '{{ Carbon\Carbon::now()->year . '-' . $holiday->holiday_month . '-' . $holiday->holiday_day }}'
                    },
                @endforeach
                ],
                dayClick: function(date, jsEvent, view) {
                    var eventDate = date.format();
                    var parts = eventDate.split('-');
                    var eMonth = parts[1];
                    var eDate = parts[2];

                    $('#holiday_day').val(eDate);
                    $('#holiday_month').val(eMonth);
                    $('#addEvent').modal('show');
                }
            });

            $('.fc-day').on('hover', function() {
                $('.fc-day').html('');
                $(this).html('<br><p style="padding:10px;">click to<br> add holiday</p>');
            });
        });
    </script>
@endsection
