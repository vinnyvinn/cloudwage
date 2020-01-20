@extends('layout')

@section('content')
    <div class="page-head">
        <div class="page-title">
            <h1>Generate Report - <small> Select the fields and order to display in the report</small></h1>
        </div>
    </div>
    <ul class="page-breadcrumb breadcrumb">
        <li>
            <a href="{{ url('/') }}">Home</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="#">Generate Printout</a>
        </li>
    </ul>
    <div class="row">
        <div class="col-sm-12">
            <form action="{{ $formAction }}" method="post" role="form" id="generateForm" target="_blank">
                {{ csrf_field() }}
                <input type="hidden" name="order" value="" id="fieldOrder">
                <input type="hidden" name="item_id" value="{{ $itemId }}">
                <div class="portlet light">
                    <div class="portlet-title">
                        <div class="caption font-red-sunglo">
                            <i class="fa fa-briefcase font-red-sunglo"></i>
                            <span class="caption-subject bold uppercase"> Report Details</span>
                        </div>
                    </div>
                    <div class="portlet-body form">
                        <h3>Available Fields</h3>
                        <hr>
                        <div class="form-body row">
                            <div class="col-sm-6">
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
                                <hr>
                                <div class="fields">
                                    <h4>Available Fields</h4>
                                    <ul class="list-group" id="availableFields">
                                        <?php $x = 0; ?>
                                        @foreach($columns as $column)
                                            <?php $x++; ?>
                                            <li class="list-group-item droppableItem" id="{{ $column }}">
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
                                <a class="btn btn-success hidden" data-target="#template" data-toggle="modal" id="saveTemplate">
                                    Save as template
                                </a>
                            </div>

                        </div>
                        <hr>
                        <div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group form-md-line-input form-md-floating-label">
                                        <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" required>
                                        <label for="title">Document Title* (e.g. Employees)</label>
                                        <span class="help-block">This will be the name of the document and also its page title.</span>
                                    </div>
                                    <div class="form-group">
                                        <label for="includeDate">Include Date: </label>
                                        <input type="checkbox" class="form-control" id="includeDate" name="includeDate">
                                        <span class="help-block">Select whether to include the current date in the document title.</span>
                                    </div>
                                </div>
                                <div class="col-sm-6">
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
                                    <input type="submit" class="btn btn-primary" id="generate" value="Generate">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div id="template" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false" data-attention-animation="false" style="position: fixed; top: 40% !important">
        <form action="{{ route('template.store') }}" method="post" id="templateForm">
            {{ csrf_field() }}
            <div class="modal-body">
                <div class="form-group form-md-line-input form-md-floating-label">
                    <input class="form-control" id="template_name" type="text" name="template_name" required>
                    <label for="template_name">Template Name</label>
                    <span class="help-block">This is the name of the template</span>
                </div>
                <input type="hidden" name="module_id" id="module_id" value="{{ $moduleId }}">
                <input type="hidden" name="field_order" id="field_order">
            </div>

            <div class="modal-footer">
                <input type="submit" class="btn blue" value="Save">
                <button type="button" data-dismiss="modal" class="btn btn-default">Cancel</button>
            </div>
        </form>
    </div>
@endsection

@section('footer')
    <script>
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

    </script>
@endsection