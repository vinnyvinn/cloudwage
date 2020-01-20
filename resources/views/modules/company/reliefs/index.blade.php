@extends('layout')

@section('content')
    <div class="page-head">
        <div class="page-title">
            <h1>Reliefs - <small> Manage the reliefs assigned to employees in the organization</small></h1>
        </div>
    </div>
    <ul class="page-breadcrumb breadcrumb">
        <li>
            <a href="{{ url('/') }}">Home</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="{{ route('reliefs.index') }}">Reliefs</a>
        </li>
    </ul>

    <div class="row">
        <div class="col-sm-12">
            <!-- BEGIN PORTLET-->
            <div class="portlet light ">
                <div class="portlet-title">
                    <div class="caption caption-md">
                        <i class="icon-bar-chart theme-font-color hide"></i>
                        <span class="caption-subject theme-font-color bold uppercase">Current Reliefs</span>
                    </div>
                    <div class="actions">
                        <a href="{{ route('allowances.create') }}" class="btn btn-transparent grey-salsa btn-circle btn-sm active"><i class="fa fa-plus"></i> Add Allowance with Relief</a>
                        <a href="{{ route('deductions.create') }}" class="btn btn-transparent grey-salsa btn-circle btn-sm active"><i class="fa fa-plus"></i> Add Deduction with Relief</a>
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
                                    Attached To
                                </th>
                                <th>
                                    Amount
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($reliefs as $relief)
                            <tr>
                                <td>
                                    {{ $relief->name }}
                                </td>
                                <td>
                                    <a href="{{ route($relief->reliefable_type == 'Allowance' ? 'allowances.show' : 'deductions.show', ['id' => $relief->reliefable_id]) }}">{{ $relief->reliefable_type }}</a>
                                </td>
                                <td class="text-right">
                                    {{ number_format($relief->amount, 2) }}
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

