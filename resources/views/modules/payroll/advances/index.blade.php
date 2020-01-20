@extends('layout')

@section('content')
    <div class="page-head">
        <div class="page-title">
            <h1>Salary Advances - <small> Current advances given to employees in the Organization</small></h1>
        </div>
    </div>
    <ul class="page-breadcrumb breadcrumb">
        <li>
            <a href="{{ url('/') }}">Home</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="{{ route('advances.index') }}">Salary Advances</a>
        </li>
    </ul>

    <div class="row">
        <div class="col-sm-12">
            <!-- BEGIN PORTLET-->
            <div class="portlet light ">
                <div class="portlet-title">
                    <div class="caption caption-md">
                        <i class="icon-bar-chart theme-font-color hide"></i>
                        <span class="caption-subject theme-font-color bold uppercase">Current Salary Advances</span>
                    </div>
                    <div class="actions">
                        <a href="{{ route('advances.create') }}" class="btn btn-transparent grey-salsa btn-circle btn-sm active"><i class="fa fa-plus"></i> Process New Advance</a>
                        <a href="{{ route('advances.bulk') }}" class="btn btn-transparent grey-salsa btn-circle btn-sm active"><i class="fa fa-plus"></i> Process Bulk Advances</a>
                    </div>
                </div>
                <div class="portlet-body">
                    <table class="table table-striped table-hover table-responsive dataTable" id="allowances_table">
                        <thead>
                        <tr>
                            <th width="20">#</th>
                            <th>
                                Month
                            </th>
                            <th>
                                Year
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $x = 0; ?>
                        @foreach($advances as $advance)
                            <?php $x++; ?>
                            <tr>
                                <td class="text-right">{{ $x }}</td>
                                <td>
                                    <a href="{{ route('advances.show', $advance->for_month->format('m-Y')) }}">
                                        {{ $advance->for_month->format('F') }}
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ route('advances.show', $advance->for_month->format('Y')) }}">
                                        {{ $advance->for_month->format('Y') }}
                                    </a>
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

