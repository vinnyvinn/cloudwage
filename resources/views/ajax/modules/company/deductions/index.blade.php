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
        </li>
    </ul>

    <div class="row">
        <div class="col-sm-12">
            <!-- BEGIN PORTLET-->
            <div class="portlet light ">
                <div class="portlet-title">
                    <div class="caption caption-md">
                        <i class="icon-bar-chart theme-font-color hide"></i>
                        <span class="caption-subject theme-font-color bold uppercase">Current Deductions</span>
                    </div>
                    <div class="actions">
                        <a href="{{ route('deductions.create') }}" class="btn btn-transparent grey-salsa btn-circle btn-sm active"><i class="fa fa-plus"></i> Add Deductions</a>
                        <div class="btn-group pull-right">
                            <button data-toggle="dropdown" class="btn dropdown-toggle btn-transparent grey-salsa btn-circle btn-sm active">Tools <i class="fa fa-angle-down"></i>
                            </button>
                            <ul class="dropdown-menu pull-right">
                                <li>
                                    <a href="javascript:;">
                                        Print </a>
                                </li>
                                <li>
                                    <a href="javascript:;">
                                        Save as PDF </a>
                                </li>
                                <li>
                                    <a href="javascript:;">
                                        Export to Excel </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="portlet-body">
                    <table class="table table-striped table-hover table-responsive dataTable" id="deductions_table">
                        <thead>
                            <tr>
                                <th>
                                    Name
                                </th>
                                <th>
                                    Type
                                </th>
                                <th>
                                    Rate
                                </th>
                                <th>
                                    Has Relief?
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
                        @foreach($deductions as $deduction)
                            <tr>
                                <td>
                                    <a href="{{ route('deductions.show', ['id' => $deduction->id]) }}">{{ $deduction->name }}</a>
                                </td>
                                <td>
                                    {{ strtoupper($deduction->type) }}
                                </td>
                                <td>
                                    {!! is_null($deduction->rate) ? '<a href="' . route('deductions.show', ['id' => $deduction->id]) . '">View Slab</a>' : (strstr($deduction->rate, '%') ? $deduction->rate : $currency . ' ' . number_format($deduction->rate, 2)) !!}
                                </td>
                                <td>
                                    {{ $deduction->has_relief == 1 ? 'Yes' : 'No' }}
                                </td>
                                <td>
                                    <a class="btn btn-success btn-xs" href="{{ route('deductions.edit', ['deduction' => $deduction->id]) }}">
                                        Edit </a>
                                </td>
                                <td>
                                    <a href="{{ route('deductions.destroy', $deduction->id) }}" class="btn btn-danger btn-xs" data-method="delete" rel="nofollow" data-confirm="Are you sure you want to delete this?" data-token="{{ csrf_token() }}">Delete</a>
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

