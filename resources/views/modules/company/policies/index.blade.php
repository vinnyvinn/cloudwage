@extends('layout')

@section('content')
    <div class="page-head">
        <div class="page-title">
            <h1>Policies - <small> Set up the system policies.</small></h1>
        </div>
    </div>
    <ul class="page-breadcrumb breadcrumb">
        <li>
            <a href="{{ url('/') }}">Home</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="{{ route('policies.index') }}">Policies</a>
        </li>
    </ul>
    <style>
        .text-left {
            text-align: left !important;
        }
    </style>
    <div class="row">
        <div class="col-sm-12">
            <!-- BEGIN PORTLET-->
            <div class="portlet light ">
                <div class="portlet-title">
                    <div class="caption caption-md">
                        <i class="icon-bar-chart theme-font-color hide"></i>
                        <span class="caption-subject theme-font-color bold uppercase">Current Policies</span>
                    </div>
                </div>
                <div class="portlet-body">
                    <table class="table table-striped table-hover table-responsive dataTableMod" id="deductions_table">
                        <thead>
                            <tr>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                    @foreach($headers as $header)
                            <tr>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>
                            <tr>
                                <th class="text-left">{{ strtoupper($header->module->name) }} MODULE</th>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>
                            <tr>
                                <th class="text-left">
                                    Policy
                                </th>
                                <th class="text-left">
                                    Description
                                </th>
                                <th>
                                    Value
                                </th>
                                <th>
                                    Change
                                </th>
                            </tr>
                        @foreach($policies->where('module_id', "{$header->module->id}") as $policy)
                            <tr>
                                <td>
                                    <a href="{{ route('policies.show', ['id' => $policy->id]) }}">{{ $policy->policy }}</a>
                                </td>
                                <td>
                                    {{ $policy->description }}
                                </td>
                                <td class="text-center">
                                @if($policy->value == 'true')
                                    <span class="btn btn-success btn-xs">Yes</span>
                                @elseif($policy->value == 'false')
                                    <span class="btn btn-danger btn-xs">No</span>
                                @else
                                    {{ $policy->value . $policy->postfix}}
                                @endif
                                </td>
                                <td class="text-center">
                                    <a class="btn btn-primary btn-xs" href="{{ route('policies.edit', ['deduction' => $policy->id]) }}">
                                        Change </a>
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- END PORTLET-->
        </div>
    </div>

@endsection
@section('footer')
    <script>
        $(document).ready( function () {
            $('.dataTableMod').dataTable( {
                "bPaginate": false,
                "ordering": false
            } );
        } );
    </script>
@endsection

