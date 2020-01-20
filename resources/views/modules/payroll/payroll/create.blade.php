@extends('layout')

@section('content')
    <div class="page-head">
        <div class="page-title">
            <h1>Payroll - <small> View the currently generated payrolls</small></h1>
        </div>
    </div>
    <ul class="page-breadcrumb breadcrumb">
        <li>
            <a href="{{ url('/') }}">Home</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="{{ route('payroll.index') }}">Payroll</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="#">Generate</a>
        </li>
    </ul>

    <div class="row">
        <div class="col-sm-12">
            <form action="{{ route('payroll.store') }}" method="post" role="form">
                {{ csrf_field() }}
                <div class="row">
                    <div class="col-sm-12">
                        <div class="portlet light">
                            <div class="portlet-title">
                                <div class="caption font-red-sunglo">
                                    <i class="fa fa-briefcase font-red-sunglo"></i>
                                    <span class="caption-subject bold uppercase"> Payroll Details</span>
                                </div>
                            </div>
                            <div class="portlet-body form">
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="form-group form-md-line-input form-md-floating-label">
                                                <select class="form-control" id="parent_filter" name="parent_filter">
                                                @foreach($filters as $key => $filter)
                                                    <option value="{{ json_encode($filter) }}">{{ $key }}</option>
                                                @endforeach
                                                </select>
                                                <label for="parent_filter">Generate Per*</label>
                                                <span class="help-block">This is the first filter to be used to group the payroll</span>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group form-md-line-input form-md-floating-label">
                                                <select class="form-control" id="filter" name="filter">
                                                </select>
                                                <label for="filter">Sub Filter*</label>
                                                <span class="help-block">This is the sub filter to be used on the main filter</span>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group form-md-line-input form-md-floating-label">
                                                <label for="payroll_date">Payroll For*</label>
                                                <div class="input-group date date-picker margin-bottom-5" readonly>
                                                    <input type="text" class="form-control form-filter input-sm" value="{{ old('date_of_birth') }}" name="payroll_date" id="payroll_date" required>
                                                    <span class="input-group-btn">
                                                        <button class="btn btn-sm default" type="button"><i class="fa fa-calendar"></i></button>
                                                    </span>
                                                </div>
                                                <span>This is month for which you want to generate the payroll</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group form-md-line-input form-md-floating-label">
                                        <input type="submit" class="btn btn-primary" value="Generate">
                                        <a class="btn btn-danger" href="{{ URL::previous() }}">Back</a>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('footer')
    <script>
        var $parentFilter = $('#parent_filter');
        var $filter = $('#filter');

        function getSubFilters() {
            var $selectedVal = JSON.parse($parentFilter.val());
            $filter.empty();
            $selectedVal.forEach(function (item, index) {
                var $insert = "";
                if (typeof item.name == "undefined") {
                    $insert = '<option value="' + item.id + '">' + item.branch_name + '</option>';
                }
                else
                {
                    $insert = '<option value="' + item.id + '">' + item.name + '</option>';
                }
                $filter.append($insert);
            });
        }

        $parentFilter.on('change', getSubFilters);

        getSubFilters();

        $(".date-picker").datepicker( {
            format: "mm-yyyy",
            viewMode: "months",
            minViewMode: "months",
            endDate: "0d"
        });
    </script>
@endsection
