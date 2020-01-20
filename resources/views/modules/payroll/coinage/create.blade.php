@extends('layout')
@section('content')
    <div class="page-head">
        <div class="page-title">
            <h1>Coinage - <small> Coinage breakdown for payment methods</small></h1>
        </div>
    </div>
    <ul class="page-breadcrumb breadcrumb">
        <li>
            <a href="{{ url('/') }}">Home</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="{{ route('coinage.index') }}">Coinage</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="#">Process Coinage</a>
        </li>
    </ul>

    <div class="row">
        <div class="col-sm-12">
            <form action="{{ route('coinage.store') }}" method="post" role="form" target="_blank">
                {{ csrf_field() }}
                <div class="portlet light">
                    <div class="portlet-title">
                        <div class="caption font-red-sunglo">
                            <i class="fa fa-briefcase font-red-sunglo"></i>
                            <span class="caption-subject bold uppercase">Process Coinage</span>
                        </div>
                    </div>
                    <div class="portlet-body form">
                        <div class="form-body">
                            <div class="form-group form-md-line-input form-md-floating-label">
                                <select class="form-control" id="month" name="month" required>
                                    @foreach($months as $month)
                                        <option value="{{ $month->format('d-m-Y') }}" {{ old('month') == $month ? 'selected' : '' }}>{{ $month->format('F Y') }}</option>
                                    @endforeach
                                </select>
                                <label for="month">Payroll Month*</label>
                                <span class="help-block">This is the month to calculate coinage for</span>
                            </div>
                            <div class="form-group">
                                <label for="documentType">Export Document Format: </label>
                                <select class="form-control" id="documentType" name="documentType">
                                    <option value="{{ \Payroll\Parsers\DocumentGenerator::CSV }}">Comma Separated Values (CSV)</option>
                                    <option value="{{ \Payroll\Parsers\DocumentGenerator::PDF }}">PDF</option>
                                    <option value="{{ \Payroll\Parsers\DocumentGenerator::XLS }}">Excel5 (.xls)</option>
                                    <option value="{{ \Payroll\Parsers\DocumentGenerator::XLSX }}">Excel2007 (.xlsx)</option>
                                </select>
                                <span class="help-block">Select the format that you want the generated document to be.</span>
                            </div>
                            <div class="form-group form-md-line-input form-md-floating-label">
                                <input type="submit" class="btn btn-primary" value="Calculate">
                                <a class="btn btn-danger" href="{{ URL::previous() }}">Back</a>
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
        $(".date-picker").datepicker( {
            format: "mm-yyyy",
            viewMode: "months",
            minViewMode: "months",
            startDate: "0d"
        });
    </script>
@endsection