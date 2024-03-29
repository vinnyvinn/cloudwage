@extends('layout')

@section('content')
    <div class="page-head">
        <div class="page-title">
            <h1>Holiday - <small> Set up the employees holiday payments</small></h1>
        </div>
    </div>
    <ul class="page-breadcrumb breadcrumb">
        <li>
            <a href="{{ url('/') }}">Home</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="{{ route('holiday-pay.index') }}">Holiday Payments Calculation</a>
        </li>
    </ul>

    <div class="row">
        <div class="col-sm-12">
            <!-- BEGIN PORTLET-->
            <div class="portlet light ">
                <div class="portlet-title">
                    <div class="caption caption-md">
                        <i class="icon-bar-chart theme-font-color hide"></i>
                        <span class="caption-subject theme-font-color bold uppercase">Holiday Pay</span>
                    </div>
                    <div class="actions">
                        <a href="{{ route('holiday-pay.create') }}" class="btn btn-transparent grey-salsa btn-circle btn-sm active"><i class="fa fa-plus"></i> Add Holiday Pay</a>
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
                        @foreach($allowances as $allowance)
                            <?php $x++;
                            $d = \Carbon\Carbon::parse($allowance->for_month);
                            ?>
                            <tr>
                                <td class="text-right">{{ $x }}</td>
                                <td class="text-center">
                                    <a href="{{ route('holiday-pay.show', $d->format('m-Y'))}}">
                                        {{ $d->format('F') }}
                                    </a>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('holiday-pay.show', $d->format('Y')) }}">
                                        {{ $d->format('Y') }}
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

