    <div class="page-head">
        <div class="page-title">
            <h1>Branches - <small> Set up the branches and the departments in the branches</small></h1>
        </div>
    </div>
    <ul class="page-breadcrumb breadcrumb">
        <li>
            <a href="{{ url('/') }}">Home</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="{{ route('departments.index') }}" class="ajaxLink">Branches</a>
        </li>
    </ul>

    <div class="row">
        <div class="col-sm-12">
            <!-- BEGIN PORTLET-->
            <div class="portlet light ">
                <div class="portlet-title">
                    <div class="caption caption-md">
                        <i class="icon-bar-chart theme-font-color hide"></i>
                        <span class="caption-subject theme-font-color bold uppercase">Current Branches</span>
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
                                    Branch Name
                                </th>
                                <th>
                                    Location
                                </th>
                                <th>
                                    # of Departments
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
                        @foreach($branches as $branch)
                            <tr>
                                <td>
                                    <a class="ajaxLink" href="{{ route('departments.show', $branch->id) . '?type=branch'}}">{{ $branch->branch_name }}</a>
                                </td>
                                <td>
                                    {{ $branch->location }}
                                </td>
                                <td>
                                    {{ count($branch->departments) }} Departments in Branch
                                </td>
                                <td>
                                    {{ $branch->created_at->toFormattedDateString() }}
                                </td>
                                <td>
                                    <a class="btn btn-success btn-xs ajaxLink" href="{{ route('departments.edit', ['department' => $branch->id]) . '?type=branch' }}">
                                        Edit </a>
                                </td>
                                <td>
                                    <a href="{{ route('departments.destroy', $branch->id) . '?type=branch' }}" class="btn btn-danger btn-xs" data-method="delete" rel="nofollow" data-confirm="Are you sure you want to delete this?" data-token="{{ csrf_token() }}">Delete</a>
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
