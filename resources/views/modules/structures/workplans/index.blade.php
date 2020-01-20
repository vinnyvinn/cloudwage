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
        </li>
    </ul>

    <div class="row">
        <div class="col-sm-12">
            <!-- BEGIN PORTLET-->
            <div class="portlet light ">
                <div class="portlet-title">
                    <div class="caption caption-md">
                        <i class="icon-bar-chart theme-font-color hide"></i>
                        <span class="caption-subject theme-font-color bold uppercase">Current Work Plans</span>
                    </div>
                    <div class="actions">
                        <a href="{{ route('work-plans.create') }}" class="btn btn-transparent grey-salsa btn-circle btn-sm active"><i class="fa fa-plus"></i> Add Work Plan</a>
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
                                    Shift
                                </th>
                                <th>
                                    Days Attached
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
                        @foreach($plans as $plan)
                            <tr>
                                <td>
                                    <a href="{{ route('work-plans.show', $plan->id) }}">{{ $plan->name }}</a>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('shifts.show', $plan->shift_id) }}">{{ $plan->shift->name }}</a>
                                </td>
                                <td class="text-center">
                                    {{ count(explode(',', $plan->days_of_week)) }} Days
                                </td>
                                <td class="text-center">
                                    <a class="btn btn-success btn-xs" href="{{ route('work-plans.edit', $plan->id) }}">
                                        Edit </a>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('work-plans.destroy', $plan->id) }}" class="btn btn-danger btn-xs" data-method="delete" rel="nofollow" data-confirm="Are you sure you want to delete this?" data-token="{{ csrf_token() }}">Delete</a>
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

