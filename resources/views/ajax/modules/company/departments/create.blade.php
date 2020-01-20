    <div class="page-head">
        <div class="page-title">
            <h1>Branches - <small> Set up the branches in the organization</small></h1>
        </div>
    </div>
    <ul class="page-breadcrumb breadcrumb">
        <li>
            <a href="{{ url('/') }}">Home</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="{{ route('departments.index') }}">Branches</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="#">Create</a>
        </li>
    </ul>

    <div class="row">
        <div class="col-sm-12">
            <form action="{{ route('departments.store') }}?type=branch" method="post" role="form">
                {{ csrf_field() }}
                <div class="portlet light">
                <div class="portlet-title">
                    <div class="caption font-red-sunglo">
                        <i class="fa fa-briefcase font-red-sunglo"></i>
                        <span class="caption-subject bold uppercase"> Branch Details</span>
                    </div>
                </div>
                <div class="portlet-body form">
                    <div class="form-body">
                        <div class="form-group form-md-line-input form-md-floating-label">
                            <input type="text" class="form-control" id="branch_name" name="branch_name" value="{{ old('branch_name') }}" required>
                            <label for="branch_name">Branch Name*</label>
                            <span class="help-block">This is the name of the branch</span>
                        </div>
                        <div class="form-group form-md-line-input form-md-floating-label">
                            <input type="text" class="form-control" id="location" name="location" value="{{ old('location') }}" required>
                            <label for="location">Branch Location*</label>
                            <span class="help-block">This is the location of the branch</span>
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
