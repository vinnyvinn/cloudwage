@extends('layout')
@section('content')
    <div class="page-head">
        <div class="page-title">
            <h1>Payment Methods - <small> Set up the modes of payments that can be used within the organization</small></h1>
        </div>
    </div>
    <ul class="page-breadcrumb breadcrumb">
        <li>
            <a href="{{ url('/') }}">Home</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="{{ route('payment-methods.index') }}">Payment Methods</a>
        </li>
        <li>
            <a href="#">Create</a>
        </li>
    </ul>

    <div class="row">
        <div class="col-sm-12">
            <form action="{{ route('payment-methods.update', $method->id) }}" method="post" role="form">
                {{ csrf_field() }}
                {{ method_field('PUT') }}
                <div class="portlet light">
                    <div class="portlet-title">
                        <div class="caption font-red-sunglo">
                            <i class="fa fa-briefcase font-red-sunglo"></i>
                            <span class="caption-subject bold uppercase"> Payment Methods Details</span>
                        </div>
                    </div>
                    <div class="portlet-body form">
                        <div class="form-body">
                            <div class="form-group form-md-line-input form-md-floating-label">
                                <input type="text" class="form-control" id="name" name="name" value="{{ $method->name }}" required>
                                <label for="name">Payment Method Name*</label>
                                <span class="help-block">This is the name of the payment method</span>
                            </div>
                            <div class="form-group form-md-line-input form-md-floating-label">
                                <textarea class="form-control" id="description" name="description" required rows="5">{{ $method->description }}</textarea>
                                <label for="name">Description*</label>
                                <span class="help-block">A short description of the payment method</span>
                            </div>
                            <div class="form-group form-md-line-input form-md-floating-label">
                                <select name="coinage" id="coinage" class="form-control">
                                    <option value="0"{{ $method->coinage == 0 ? ' selected' : '' }}>No</option>
                                    <option value="1"{{ $method->coinage == 1 ? ' selected' : '' }}>Yes</option>
                                </select>
                                <label for="coinage">Uses Coinage?*</label>
                                <span class="help-block">Select whether this payment method should be included in the coinage report</span>
                            </div>
                            <div class="form-group form-md-line-input form-md-floating-label">
                                <input type="submit" class="btn btn-primary" value="Save Changes">
                                <a class="btn btn-danger" href="{{ URL::previous() }}">Back</a>
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
        $(document).ready(function() {

            function bindEvents()
            {
                $('.row_remove').off();
                $('.row_remove').on('click', function(e) {
                    e.preventDefault();

                    var totalHolder = $('#total_rows');
                    var total = parseInt(totalHolder.val());

                    var current_row = $(this).attr('id');
                    var current_row_number = parseInt(current_row.substr(10, current_row.length));

                    if(total == 1)
                    {
                        console.log(total);
                        alert('Cannot Delete: You need at least one field');
                        return false;
                    }

                    // remove the row
                    $('#row' + current_row_number).remove();

                    // move all the elements up one level if there are any below the entry
                    if(total > current_row_number)
                    {
                        var next_row_number = current_row_number + 1
                        for(var j = next_row_number; j <= total; j++)
                        {
                            var new_row_number = j - 1;
                            var change_row = 'row' + j;
                            var change_row_number = '#' + change_row + ' .row_number';
                            var change_row_input = '#row_' + change_row;

                            // change input value to -1
                            $(change_row_input).val(new_row_number);
                            // change the row number
                            $(change_row_number).html(new_row_number)
                            // change the inputs
                            $('#min_amount' + j).attr('name', 'min_amount' + new_row_number);
                            $('#min_amount' + j).attr('id', 'min_amount' + new_row_number);

                            $('#max_amount' + j).attr('name', 'max_amount' + new_row_number);
                            $('#max_amount' + j).attr('id', 'max_amount' + new_row_number);

                            $('#rate' + j).attr('name', 'rate' + new_row_number);
                            $('#rate' + j).attr('id', 'rate' + new_row_number);

                            // change the remove button
                            $('#remove_row' + j).attr('id', 'remove_row' + new_row_number);

                            // finally change the row id
                            $('#' + change_row).attr('id', 'row' + new_row_number);
                        }
                    }

                    // reduce total items
                    totalHolder.val(total - 1);

                    bindEvents();
                });

                $('#add_row').off();
                $('#add_row').on('click', function (e) {
                    e.preventDefault();

                    var totalHolder = $('#total_rows');
                    var total = parseInt(totalHolder.val());
                    var newTotal = total + 1;

                    var newRow = '<tr id="row' + newTotal +'"><input type="text" name="row_row' + newTotal +'" id="row_row' + newTotal +'" hidden value="'+ newTotal +'"><td class="row_number">' + newTotal +'</td><td><input type="text" name="field_name' + newTotal +'" id="field_name' + newTotal +'" class="form-control" placeholder="Branch Name"></td><td><select name="field_type' + newTotal +'" id="field_type' + newTotal +'" class="form-control"><option value="boolean">Boolean</option><option value="date">Date</option><option value="double">Decimal</option><option value="integer">Numeric</option><option value="string">String</option></select></td><td><div class="input-group"><input type="text" name="field_default' + newTotal +'" id="field_default' + newTotal +'" class="form-control"></div></td><td><a href="#" class="btn btn-danger row_remove" id="remove_row' + newTotal + '">Remove</a></td></tr>';

                    $('#row_holder').append(newRow);

                    totalHolder.val(newTotal);

                    bindEvents();
                });
            }

            bindEvents();
        });
    </script>
@endsection