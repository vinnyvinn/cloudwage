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
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="#">View</a>
        </li>
    </ul>

    <div class="row">
        <div class="col-sm-12">
                <div class="portlet light">
                    <div class="portlet-title">
                        <div class="caption font-red-sunglo">
                            <i class="fa fa-briefcase font-red-sunglo"></i>
                            <span class="caption-subject bold uppercase"> Pay Grade Details</span>
                        </div>
                    </div>
                    <div class="portlet-body form">
                        <div class="form-body row">
                            <div class="col-sm-6">
                                <div class="form-group form-md-line-input form-md-floating-label">
                                    <label for="name">Pay Grade Name</label>
                                    <div class="form-control">{{ $grade->name }}</div>
                                </div>
                                <div class="form-group form-md-line-input form-md-floating-label">
                                    <label for="name">Currency</label>
                                    <div class="form-control">{{ $grade->currency->name }}</div>
                                </div>
                                <div class="form-group form-md-line-input form-md-floating-label">
                                    <label for="name">Basic Salary</label>
                                    <div class="form-control">{{ $grade->currency->code .' ' . number_format($grade->basic_salary, 2) }}</div>
                                </div>
                                <div class="form-group form-md-line-input form-md-floating-label">
                                    <label for="name">Annual Increment</label>
                                    <div class="form-control">{{ $grade->annual_increment }}</div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                @if($grade->default_allowances)
                                <div class="form-group form-md-line-input form-md-floating-label">
                                    <label for="name">Default Allowances</label>
                                    <?php $allowances = explode(",", $grade->default_allowances); ?>
                                    @foreach($allowances as $allowance)
                                    <div class="form-control"><a href="{{ route('allowances.show', $allowance) }}">{{ Payroll\Models\Allowance::findOrFail($allowance)->name }}</a></div>
                                    @endforeach
                                </div>
                                @endif
                                @if($grade->default_deductions)
                                    <div class="form-group form-md-line-input form-md-floating-label">
                                        <label for="name">Default Deductions</label>
                                        <?php $deductions = explode(",", $grade->default_deductions); ?>
                                        @foreach($deductions as $deduction)
                                            <div class="form-control"><a href="{{ route('deductions.show', $deduction) }}">{{ Payroll\Models\Deduction::findOrFail($deduction)->name }}</a></div>
                                        @endforeach
                                    </div>
                                @endif
                                
                                <div class="form-group form-md-line-input form-md-floating-label">
                                    <a class="btn btn-success" href="{{ route('grades.edit', $grade->id) }}">Edit</a>
                                    <a href="{{ route('grades.destroy', $grade->id) }}" class="btn btn-danger" data-method="delete" rel="nofollow" data-confirm="Are you sure you want to delete this?" data-token="{{ csrf_token() }}">Delete</a>
                                    <a class="btn btn-warning" href="{{ URL::previous() }}">Back</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>
@endsection