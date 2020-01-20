    <div class="page-head">
        <div class="page-title">
            <h1>Departments in {{ $departments->first()->branch->branch_name }} - <small> Set up the departments in the organization</small></h1>
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
            <a href="#">Departments in {{ $departments->first()->branch->branch_name }}</a>
        </li>
    </ul>

    <div class="row">
        <div class="col-sm-12">
            <!-- BEGIN PORTLET-->
            <div class="portlet light ">
                <div class="portlet-title">
                    <div class="caption caption-md">
                        <a href="{{ route('departments.index') }}" class="btn btn-transparent grey-salsa btn-circle btn-sm active ajaxLink"><i class="fa fa-angle-left"></i> back</a>
                        <i class="icon-bar-chart theme-font-color hide"></i>
                        <span class="caption-subject theme-font-color bold uppercase">Current Departments in {{ $departments->first()->branch->branch_name }}</span>
                    </div>
                    <div class="actions">
                        <a href="{{ route('departments.create') }}" class="btn btn-transparent grey-salsa btn-circle btn-sm active ajaxLink"><i class="fa fa-plus"></i> Add Branch</a>
                        <a href="{{ route('departments.create') }}?type=department" class="btn btn-transparent grey-salsa btn-circle btn-sm active ajaxLink"><i class="fa fa-plus"></i> Add Department</a>
                    </div>
                </div>
                <div class="portlet-body">
                    <table class="table table-striped table-hover table-responsive dataTable" id="departments_table">
                        <thead>
                            <tr>
                                <th>
                                    Department Name
                                </th>
                                <th>
                                    Parent Department
                                </th>
                                <th>
                                    Date Created
                                </th>
                                <th>
                                    Edit
                                </th>
                                <th>
                                    Delete
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($departments as $department)
                            <tr>
                                <td>
                                    <a href="{{ route('departments.show', $department->id) . '?type=department' }}">{{ $department->name }}</a>
                                </td>
                                <td>
                                    {{ is_null($department->parent_id) ? 'None':\Payroll\Models\Department::find($department->parent_id)->first()->name }}
                                </td>
                                <td class="text-center">
                                    {{ $department->created_at->toFormattedDateString() }}
                                </td>
                                <td class="text-center">
                                    <a class="btn btn-success btn-xs ajaxLink" href="{{ route('departments.edit', ['department' => $department->id]) }}">
                                        Edit </a>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('departments.destroy', $department->id) }}" class="btn btn-danger btn-xs" data-method="delete" rel="nofollow" data-confirm="Are you sure you want to delete this?" data-token="{{ csrf_token() }}">Delete</a>
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
