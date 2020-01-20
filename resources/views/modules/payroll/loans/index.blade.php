@extends('layout')

@section('content')
    <div class="page-head">
        <div class="page-title">
            <h1>Company Loans - <small> Current loans given to employees in the Organization</small></h1>
        </div>
    </div>
    <ul class="page-breadcrumb breadcrumb">
        <li>
            <a href="{{ url('/') }}">Home</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="{{ route('loans.index') }}">Loans</a>
        </li>
    </ul>

    <div class="row">
        <div class="col-sm-12">
            <!-- BEGIN PORTLET-->
            <div class="portlet light ">
                <div class="portlet-title">
                    <div class="caption caption-md">
                        <i class="icon-bar-chart theme-font-color hide"></i>
                        <span class="caption-subject theme-font-color bold uppercase">Current Loans</span>
                    </div>
                    <div class="actions">
                        <a href="{{ route('loans.create') }}" class="btn btn-transparent grey-salsa btn-circle btn-sm active"><i class="fa fa-plus"></i> Process New Loan</a>
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
                        @foreach($loans as $loan)
                        <?php $x++; ?>
                            <tr>
                                <td class="text-right">{{ $x }}</td>
                                <td>
                                    <a href="{{ route('loans.show', $loan->date_processed->format('m-Y')) }}">
                                        {{ $loan->date_processed->format('F') }}
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ route('loans.show', $loan->date_processed->format('Y')) }}">
                                        {{ $loan->date_processed->format('Y') }}
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

