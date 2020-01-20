
    <div class="page-head">
        <div class="page-title">
            <h1>{{ $department->name }}  - <small> Set up the departments in the organization</small></h1>
        </div>
    </div>
    <ul class="page-breadcrumb breadcrumb">
        <li>
            <a href="{{ url('/') }}">Home</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="{{ route('departments.index') }}" class="ajaxLink">Branches</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="{{ URL::previous() }}">Departments in {{ $department->branch->branch_name }}</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="#">View</a>
        </li>
    </ul>

    <div class="row">
        <div class="col-sm-12">

            <div class="portlet box blue">
                <div class="portlet-title">
                    <div class="caption">
                        <a href="{{ URL::previous() }}" class="btn btn-transparent grey-salsa btn-circle btn-sm active ajaxLink"><i class="fa fa-angle-left"></i> back</a>
                        {{ $department->name }} Details
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="tabbable-custom nav-justified">
                        <ul class="nav nav-tabs nav-justified">
                            <li class="active">
                                <a href="#details" data-toggle="tab">
                                    Department Details </a>
                            </li>
                            <li>
                                <a href="#employees" data-toggle="tab">
                                    Department Employees </a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="details">
                                <div class="form-group form-md-line-input form-md-floating-label">
                                    <div><strong>Department Name</strong></div>
                                    <p>{{ $department->name }}</p>
                                    <hr>
                                </div>
                                <div class="form-group form-md-line-input form-md-floating-label">
                                    <div><strong>Parent Department</strong></div>
                                    <p>{{ is_null($department->parent_id) ? 'None' : \Payroll\Models\Department::findOrFail($department->parent_id)->name }}</p>
                                    <hr>
                                </div>

                                <div class="form-group form-md-line-input form-md-floating-label">
                                    <a class="btn btn-success" href="{{ route('departments.edit', ['department' => $department->id]) }}">
                                        Edit </a>
                                    <a href="{{ route('departments.destroy', $department->id) }}" class="btn btn-danger" data-method="delete" rel="nofollow" data-confirm="Are you sure you want to delete this?" data-token="{{ csrf_token() }}">Delete</a>
                                    <a class="btn btn-primary" href="{{ URL::previous() }}">Back</a>
                                </div>
                            </div>
                            <div class="tab-pane" id="employees">
                                <span class="pull-right">
                                    <a href="{{ route('departments.generate', $department->id) }}" class="btn btn-primary">Generate Reports</a>
                                </span>
                                <h3>Employees in {{ $department->name }} ({{ $assignments->count() }})</h3>
                                <hr>
                                <table class="table table-striped table-hover table-responsive dataTable" id="allowances_table">
                                    <thead>
                                    <tr>
                                        <th>
                                            Payroll Number
                                        </th>
                                        <th>
                                            Identification
                                        </th>
                                        <th>
                                            Full Names
                                        </th>
                                        <th>
                                            Gender
                                        </th>
                                        <th>
                                            Mobile Phone
                                        </th>
                                        <th>
                                            Join Date
                                        </th>
                                        <th>
                                            Edit
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($assignments as $assignment)
                                        <tr>
                                            <td>
                                                <a href="{{ route('employees.show', ['id' => $assignment->employee->id]) }}">{{ $assignment->employee->payroll_number }}</a>
                                            </td>
                                            <td>
                                                {{ $assignment->employee->identification_number }}
                                            </td>
                                            <td>
                                                {{ $assignment->employee->first_name . ' ' . $assignment->employee->last_name }}
                                            </td>
                                            <td>
                                                {{ $assignment->employee->gender }}
                                            </td>
                                            <td>
                                                {{ $assignment->employee->mobile_phone }}
                                            </td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($assignment->employee->join_date)->format('d F Y') }}
                                            </td>
                                            <td>
                                                <a class="btn btn-success btn-xs" href="{{ route('assignments.edit', $assignment->id) }}">
                                                    Edit </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
