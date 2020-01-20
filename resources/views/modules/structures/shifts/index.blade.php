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
        </li>
    </ul>

    <div class="row">
        <div class="col-sm-12">
            <!-- BEGIN PORTLET-->
            <div class="portlet light ">
                <div class="portlet-title">
                    <div class="caption caption-md">
                        <i class="icon-bar-chart theme-font-color hide"></i>
                        <span class="caption-subject theme-font-color bold uppercase">Current Shifts</span>
                    </div>
                    <div class="actions">
                        <a href="{{ route('shifts.create') }}" class="btn btn-transparent grey-salsa btn-circle btn-sm active"><i class="fa fa-plus"></i> Add Shift</a>
                    </div>
                </div>
                <div class="portlet-body">
                    <table class="table table-striped table-hover table-responsive dataTable" id="allowances_table">
                        <thead>
                            <tr>
                                <th>
                                    Name
                                </th>
                                <th>
                                    Overnight
                                </th>
                                <th>
                                    Shift Start
                                </th>
                                <th>
                                    Shift End
                                </th>
                                <th>
                                    Time Allowance
                                </th>
                                <th>
                                    Edit
                                </th>
                                <th>
                                    Delete
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($shifts as $shift)
                            <tr>
                                <td>
                                    <a href="{{ route('shifts.show', $shift->id) }}">{{ $shift->name }}</a>
                                </td>
                                <td class="text-center">
                                    {{ $shift->is_overnight ? 'Yes' : 'No' }}
                                </td>
                                <td class="text-center">
                                    {{ substr($shift->shift_start, 0, -3) }}
                                </td>
                                <td class="text-center">
                                    {{ substr($shift->shift_end, 0, -3) }}
                                </td>
                                <td class="text-center">
                                    {{ $shift->time_allowance }} Minutes
                                </td>
                                <td class="text-center">
                                    <a class="btn btn-success btn-xs" href="{{ route('shifts.edit', $shift->id) }}">
                                        Edit </a>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('shifts.destroy', $shift->id) }}" class="btn btn-danger btn-xs" data-method="delete" rel="nofollow" data-confirm="Are you sure you want to delete this?" data-token="{{ csrf_token() }}">Delete</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- END PORTLET-->
        </div>
    </div>

@endsection

