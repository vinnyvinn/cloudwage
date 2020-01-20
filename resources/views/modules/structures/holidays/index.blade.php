@extends('layout')

@section('content')
    <?php $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'] ?>
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
        </li>
    </ul>

    <div class="row">
        <div class="col-sm-12">
            <!-- BEGIN PORTLET-->
            <div class="portlet light ">
                <div class="portlet-title">
                    <div class="caption caption-md">
                        <i class="icon-bar-chart theme-font-color hide"></i>
                        <span class="caption-subject theme-font-color bold uppercase">Current Holidays</span>
                    </div>
                    <div class="actions">
                        <a href="{{ route('holidays.create') }}" class="btn btn-transparent grey-salsa btn-circle btn-sm active"><i class="fa fa-plus"></i> Add Holiday</a>
                    </div>
                </div>
                <div class="portlet-body">
                    <table class="table table-striped table-hover table-responsive dataTable">
                        <thead>
                            <tr>
                                <th>
                                    Name
                                </th>
                                <th>
                                    Day
                                </th>
                                <th>
                                    Month
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
                        @foreach($holidays as $holiday)
                            <tr>
                                <td>
                                    {{ $holiday->name }}
                                </td>
                                <td>
                                    {{ $holiday->holiday_day }}
                                </td>
                                <td>
                                    {{ $months[$holiday->holiday_month - 1] }}
                                </td>
                                <td>
                                    <a class="btn btn-success btn-xs" href="{{ route('holidays.edit', $holiday->id) }}">
                                        Edit </a>
                                </td>
                                <td>
                                    <a href="{{ route('holidays.destroy', $holiday->id) }}" class="btn btn-danger btn-xs" data-method="delete" rel="nofollow" data-confirm="Are you sure you want to delete this?" data-token="{{ csrf_token() }}">Delete</a>
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

