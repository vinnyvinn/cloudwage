@extends('layout')

@section('content')
    <div class="page-head">
        <div class="page-title">
            <h1>Bulk Assign - <small> Fill multiple employees details at once.</small></h1>
        </div>
    </div>
    <ul class="page-breadcrumb breadcrumb">
        <li>
            <a href="{{ url('/') }}">Home</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="#">Bulk Assign</a>
        </li>
    </ul>
    <div class="row">
        <div class="col-sm-12">
            <form action="{{ $formAction }}" method="post" role="form" id="generateForm">
                {{ csrf_field() }}
                <input type="hidden" name="assignment_id" value="{{ $assignToId }}">
                <div class="portlet light">
                    <div class="portlet-title">
                        <div class="caption font-red-sunglo">
                            <i class="fa fa-briefcase font-red-sunglo"></i>
                            <span class="caption-subject bold uppercase"> Assignment Details</span>
                        </div>
                    </div>
                    <div class="portlet-body form">
                        <h3>Bulk Assign</h3>
                        <hr>
                        <div class="form-body row">
                            @if($formAction == route('worked.store'))
                            <div class="col-sm-12">
                                <div class="form-group form-md-line-input form-md-floating-label">
                                    <label for="payroll_date">Payroll For*</label>
                                    <div class="input-group date date-picker margin-bottom-5" readonly>
                                        <input type="text" class="form-control form-filter input-sm" value="{{ old('for_month') }}" name="for_month" id="for_month" required>
                                        <span class="input-group-btn">
                                                        <button class="btn btn-sm default" type="button"><i class="fa fa-calendar"></i></button>
                                                    </span>
                                    </div>
                                    <span>This is month for which you want to generate the payroll</span>
                                </div>
                            </div>
                            @endif
                            <div class="col-sm-12">
                            @if ($allowCopy)
                                <div class="form-group">
                                    <label for="preset">Use Report Preset: </label>
                                    <select class="form-control" id="preset">
                                        <option selected disabled>Choose Template</option>
                                        @foreach($presets as $preset)
                                            <option value="{{ $preset->field_order }}">{{ $preset->template_name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="help-block">Select a previously saved template.</span>
                                </div>
                            @endif
                                <hr>
                                <div class="fields">
                                    @if($requiredFields[0]['type'] == 'checkbox')
                                        <div class="pull-right">
                                            <a class="btn btn-success btn-xs" onclick="selectAll()">Select All</a>
                                            <a class="btn btn-warning btn-xs" onclick="selectNone()">Select None</a>
                                        </div>
                                    @endif
                                    <br class="clearfix"/>
                                        <br>
                                    <table class="table table-hover" id="bulkTable">
                                        <thead>
                                        <tr>
                                        @foreach($columns as $column)
                                            <td>{{ title_case(str_replace('_', ' ', $column)) }}</td>
                                        @endforeach
                                        @foreach($requiredFields as $field)
                                            <td>{{ title_case(str_replace('_', ' ', $field['name'])) }}</td>
                                        @endforeach
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($rows as $row)
                                            <tr>
                                            @foreach($columns as $column)
                                                <td>{{ $row[$column] }}</td>
                                            @endforeach
                                            @foreach($requiredFields as $field)
                                                <td>{!! \Payroll\Factories\HTMLElementsFactory::get($field['type'], $row['id'], $field['name']) !!}</td>
                                            @endforeach
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <hr>
                                <div class="pull-right">
                                    <input type="submit" class="btn btn-primary" id="generate" value="Save">
                                    <a class="btn btn-danger" href="{{ URL::previous() }}">Back</a>
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
        $('#bulkTable').dataTable({
            paginate: false
        });
        var $save = $('#saveTemplate');
        var $templateOrder = $('#field_order');
        var $toUse = $('#toUseFields');
        var $droppable = $(".droppable");
        var $fieldOrder = $('#fieldOrder');
        var $preset = $('#preset');
        var $fieldList = $('#availableFields');
        var $fields = $fieldList.children('li');
        $fieldList.sortable({
            connectWith: "#toUseFields"
        });

        $toUse.sortable({
            connectWith: "#availableFields",
            update: function( event, ui ) {
                $templateOrder.val('');
                $fieldOrder.val('');
                $("#toUseFields li").each(function(index, element){
                    var elem = $(element).children('input').first().attr('value');
                    var oldVal = $fieldOrder.val();
                    $fieldOrder.val(oldVal + (oldVal == '' ? '' : ',') + elem);
                }.bind($fieldOrder));

                $templateOrder.val($fieldOrder.val());
                $fieldOrder.val() == '' ? $save.addClass('hidden') : $save.removeClass('hidden');
            }
        });

        $droppable.droppable({
            accept: ".droppableItem",
            drop: function( event, ui ) {
                var $element = ui.helper;
                var $inputs = $element.children('input');
                $.each($inputs, function (key, value) {
                    $(value).attr('name', 'report_fields[]')
                });

                $element.addClass('.droppedItem');
                $toUse.append(ui.helper);
            }.bind($toUse)
        });

        $('#templateForm').on('submit', function(e) {
            e.preventDefault();
            var $form = $(this);
            $.ajax({
                url: $form.attr('action'),
                data: $form.serialize(),
                method: 'post',
                success: function(response) {
                    toastr.options = {
                        "closeButton": true,
                        "debug": false,
                        "positionClass": "toast-top-right",
                        "onclick": null,
                        "showDuration": "1000",
                        "hideDuration": "1000",
                        "timeOut": "9000",
                        "extendedTimeOut": "1000",
                        "showEasing": "swing",
                        "hideEasing": "linear",
                        "showMethod": "fadeIn",
                        "hideMethod": "fadeOut"
                    };
                    toastr['info'](response.message);
                    var $tempName = $('#template_name');
                    var $newPreset = '<option value="' + $fieldOrder.val() + '" selected>' + $tempName.val() +'</option>';
                    $preset.append($newPreset);
                    $tempName.val('');
                    $('#template').modal('hide');
                }
            });
        });

        $preset.on('change', function() {
            $toUse.html('');
            $fieldList.html('');
            $fieldList.append($fields);
            var $order = $(this).val();
            var $items = $order.split(',');

            for(var i = 0; i < $items.length; i++)
            {
                var $field = $('#' + $items[i]);
                $.each($field.children('input'), function (key, value) {
                    $(value).attr('name', 'report_fields[]')
                });
                $field.addClass('.droppedItem');
                $field.remove();

                $toUse.append($field);
            }

            $fieldOrder.val($order);
        });
    @if($requiredFields[0]['type'] == 'checkbox')
        function selectAll() {
            $('.checkbox').prop("checked", true).parent().addClass('checked');
        }
        function selectNone() {
            $('.checkbox').removeProp("checked").parent().removeClass('checked');
        }
    @endif



        $(".date-picker").datepicker( {
            format: "mm-yyyy",
            viewMode: "months",
            minViewMode: "months",
            endDate: "0d"
        });

    </script>


@endsection