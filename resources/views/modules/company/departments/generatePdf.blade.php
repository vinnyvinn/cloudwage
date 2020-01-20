@extends('layout')

@section('content')
    @include('ajax.modules.company.departments.generatePdf')
@endsection

@section('footer')
    <script>
        var $toUse = $('#toUseFields');
        var $droppable = $(".droppable");
        var $fieldOrder = $('#fieldOrder');

        $('#availableFields').sortable({
            connectWith: "#toUseFields"
        });

        $toUse.sortable({
            connectWith: "#availableFields",
            update: function( event, ui ) {
                $fieldOrder.val('');
                $("#toUseFields li").each(function(index, element){
                    var elem = $(element).children('input').first().attr('value');
                    var oldVal = $fieldOrder.val();
                    $fieldOrder.val(oldVal + (oldVal == '' ? '' : ',') + elem);
                }.bind($fieldOrder));
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

    </script>
@endsection