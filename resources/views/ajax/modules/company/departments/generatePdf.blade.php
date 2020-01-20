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
            <a href="#">Generate Printout</a>
        </li>
    </ul>

    <div class="row">
        <div class="col-sm-12">
            <form action="{{ route('departments.pdfs') }}" method="post" role="form" id="generateForm" target="_blank">
                {{ csrf_field() }}
                <input type="hidden" name="order" value="" id="fieldOrder">
                <input type="hidden" name="department_id" value="{{ $department->id }}">
                <div class="portlet light">
                <div class="portlet-title">
                    <div class="caption font-red-sunglo">
                        <i class="fa fa-briefcase font-red-sunglo"></i>
                        <span class="caption-subject bold uppercase"> Report Details</span>
                    </div>
                </div>
                <div class="portlet-body form">
                    <div class="form-body row">
                        <h3>Employee Details</h3>
                        <hr>
                        <div class="col-sm-6">
                            <div class="fields">
                                <h4>Available Fields</h4>
                                <ul class="list-group" id="availableFields">
                                    <?php $x = 0; ?>
                                    @foreach($columns as $column)
                                        <?php $x++; ?>
                                        <li class="list-group-item droppableItem">
                                            <input type="hidden" class="field" value="{{ $column }}">
                                            {{ title_case(str_replace('_', ' ', $column)) }}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div id="container">
                                <h4>Drop here & rearrange columns for the report</h4>
                                <ul class="list-group droppable" id="toUseFields">
                                </ul>
                            </div>
                        </div>
                        <div>
                            <input type="submit" class="btn btn-primary" id="generate" value="Generate">
                            <a class="btn btn-danger" href="{{ URL::previous() }}">Back</a>
                        </div>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>

