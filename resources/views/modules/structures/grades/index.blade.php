@extends('layout')

@section('content')
    <div class="page-head">
        <div class="page-title">
            <h1>Pay Grades - <small> Set up the pay grades that will be assigned within the organization</small></h1>
        </div>
    </div>
    <ul class="page-breadcrumb breadcrumb">
        <li>
            <a href="{{ url('/') }}">Home</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="{{ route('grades.index') }}">Pay Grades</a>
        </li>
    </ul>

    <div class="row">
        <div class="col-sm-12">
            <!-- BEGIN PORTLET-->
            <div class="portlet light ">
                <div class="portlet-title">
                    <div class="caption caption-md">
                        <i class="icon-bar-chart theme-font-color hide"></i>
                        <span class="caption-subject theme-font-color bold uppercase">Current Pay Grades</span>
                    </div>
                    <div class="actions">
                        <a href="{{ route('grades.create') }}" class="btn btn-transparent grey-salsa btn-circle btn-sm active"><i class="fa fa-plus"></i> Add Pay Grade</a>
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
                                    Currency
                                </th>
                                <th>
                                    Basic Salary
                                </th>
                                <th>
                                    Annual Increment
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
                        @foreach($grades as $grade)
                            <tr>
                                <td>
                                    <a href="{{ route('grades.show', $grade->id) }}">{{ $grade->name }}</a>
                                </td>
                                <td class="text-center">
                                    {{ $grade->currency->name }}
                                </td>
                                <td class="text-right">
                                    {{ number_format($grade->basic_salary, 2) }}
                                </td>
                                <td class="text-right">
                                    {{ $grade->annual_increment }}
                                </td>
                                <td class="text-center">
                                    <a class="btn btn-success btn-xs" href="{{ route('grades.edit', $grade->id) }}">
                                        Edit </a>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('grades.destroy', $grade->id) }}" class="btn btn-danger btn-xs" data-method="delete" rel="nofollow" data-confirm="Are you sure you want to delete this?" data-token="{{ csrf_token() }}">Delete</a>
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

