    <div class="page-head">
        <div class="page-title">
            <h1>Departments - <small> Set up the departments in the organization</small></h1>
        </div>
    </div>
    <ul class="page-breadcrumb breadcrumb">
        <li>
            <a href="{{ url('/') }}">Home</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="{{ route('departments.index') }}">Departments</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="#">Create</a>
        </li>
    </ul>

    <div class="row">
        <div class="col-sm-12">
            <form action="{{ route('departments.store') }}?type=department" method="post" role="form">
                {{ csrf_field() }}
                <div class="portlet light">
                <div class="portlet-title">
                    <div class="caption font-red-sunglo">
                        <i class="fa fa-briefcase font-red-sunglo"></i>
                        <span class="caption-subject bold uppercase"> Department Details</span>
                    </div>
                </div>
                <div class="portlet-body form">
                    <div class="form-body">
                        <div class="form-group form-md-line-input form-md-floating-label">
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                            <label for="name">Department Name*</label>
                            <span class="help-block">This is the name of the department</span>
                        </div>
                        <div class="form-group form-md-line-input form-md-floating-label">
                            <select name="branch_id" id="branch_id" class="form-control select2" >
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : ''}}>{{ $branch->branch_name }}</option>
                                @endforeach
                            </select>
                            <label for="branch_id">Parent Branch</label>
                            <span class="help-block">This is the branch to which the department belongs to</span>
                        </div>
                        <div class="form-group form-md-line-input form-md-floating-label">
                            <select name="parent_id" id="parent_id" class="form-control select2" >
                                <option value="null">None</option>
                            @foreach(\Payroll\Models\Department::all() as $dept)
                                <option value="{{ $dept->id }}" {{ old('parent_id') == $dept->id ? 'selected' : ''}}>{{ $dept->name }}</option>
                            @endforeach
                            </select>
                            <label for="branch">Parent Department</label>
                            <span class="help-block">This is the department to which it belongs to</span>
                        </div>
                        <div class="form-group form-md-line-input form-md-floating-label">
                            <input type="submit" class="btn btn-primary" value="Save">
                            <a class="btn btn-danger ajaxLink" href="{{ route('departments.index') }}">Back</a>
                        </div>

                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>
