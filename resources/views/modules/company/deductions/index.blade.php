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
                    </div>
                </div>
                <div class="portlet-body">
                    <table class="table table-striped table-hover table-responsive dataTable" id="deductions_table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Date Due</th>
                                <th>Type</th>
                                <th>Rate</th>
                                <th>Has Relief?</th>
                                <th>Edit</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($deductions as $deduction)
                            <tr>
                                <td>
                                    <a href="{{ route('deductions.show', ['id' => $deduction->id]) }}">{{ $deduction->name }}</a>
                                </td>
                                <td class="text-center">
                                    {{ $deduction->due_date }}<sup>{{ getSuffix($deduction->due_date) }}</sup>
                                </td>
                                <td class="text-center">
                                    {{ title_case(str_replace('_', ' ', $deduction->type)) }}
                                </td>
                                <td class="text-center">
                                    {!! $deduction->type == 'slab' ? '<a href="' . route('deductions.show', ['id' => $deduction->id]) . '">View Slab</a>' : ($deduction->type == 'rate' ? (strstr($deduction->rate, '%') ? $deduction->rate : $currency . ' ' . number_format($deduction->rate, 2)): '') !!}
                                </td>
                                <td class="text-center">
                                    {{ $deduction->has_relief == 1 ? 'Yes' : 'No' }}
                                </td>
                                <td class="text-center">
                                    <a class="btn btn-success btn-xs" href="{{ route('deductions.edit', ['deduction' => $deduction->id]) }}">
                                        Edit </a>
                                </td>
                                <td class="text-center">
                                @if($deduction->id != 1 && $deduction->id != 2 && $deduction->id != 3)
                                        <a href="{{ route('deductions.destroy', $deduction->id) }}" class="btn btn-danger btn-xs" data-method="delete" rel="nofollow" data-confirm="Are you sure you want to delete this?" data-token="{{ csrf_token() }}">Delete</a>
                                    @endif
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

