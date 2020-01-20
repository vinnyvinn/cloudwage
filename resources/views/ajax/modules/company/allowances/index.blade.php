@extends('layout')

@section('content')
    <div class="page-head">
        <div class="page-title">
            <h1>Allowances - <small> Set up the allowances that can be given to employees in the organization</small></h1>
        </div>
    </div>
    <ul class="page-breadcrumb breadcrumb">
        <li>
            <a href="{{ url('/') }}">Home</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="{{ route('allowances.index') }}">Allowances</a>
        </li>
    </ul>

    <div class="row">
        <div class="col-sm-12">
            <!-- BEGIN PORTLET-->
            <div class="portlet light ">
                <div class="portlet-title">
                    <div class="caption caption-md">
                        <i class="icon-bar-chart theme-font-color hide"></i>
                        <span class="caption-subject theme-font-color bold uppercase">Current Allowances</span>
                    </div>
                    <div class="actions">
                        <a href="{{ route('allowances.create') }}" class="btn btn-transparent grey-salsa btn-circle btn-sm active"><i class="fa fa-plus"></i> Add Allowance</a>
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
                                    Minimum Amount
                                </th>
                                <th>
                                    Maximum Amount
                                </th>
                                <th>
                                    Has Relief?
                                </th>
                                <th>
                                    Add to Basic?
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
                        @foreach($allowances as $allowance)
                            <tr>
                                <td>
                                    <a href="{{ route('allowances.show', ['id' => $allowance->id]) }}">{{ $allowance->name }}</a>
                                </td>
                                <td>
                                    {{ $allowance->currency->code . ' ' . number_format($allowance->min_amount, 2) }}
                                </td>
                                <td>
                                    {{ $allowance->currency->code . ' ' .  number_format($allowance->max_amount, 2) }}
                                </td>
                                <td>
                                    {{ $allowance->has_relief == 1 ? 'Yes' : 'No' }}
                                </td>
                                <td>
                                    {{ $allowance->added_to_basic == 1 ? 'Yes' : 'No' }}
                                </td>
                                <td>
                                    <a class="btn btn-success btn-xs" href="{{ route('allowances.edit', ['allowances' => $allowance->id]) }}">
                                        Edit </a>
                                </td>
                                <td>
                                    <a href="{{ route('allowances.destroy', $allowance->id) }}" class="btn btn-danger btn-xs" data-method="delete" rel="nofollow" data-confirm="Are you sure you want to delete this?" data-token="{{ csrf_token() }}">Delete</a>
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

