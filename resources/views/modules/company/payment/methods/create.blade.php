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
            <form action="{{ route('payment-methods.store') }}" method="post" role="form">
                {{ csrf_field() }}
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
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                            <label for="name">Payment Method Name*</label>
                            <span class="help-block">This is the name of the payment method</span>
                        </div>
                        <div class="form-group form-md-line-input form-md-floating-label">
                            <textarea type="text" class="form-control" id="description" name="description" required rows="5">{{ old('description') }}</textarea>
                            <label for="name">Description*</label>
                            <span class="help-block">A short description of the payment method</span>
                        </div>
                        <div class="form-group form-md-line-input form-md-floating-label">
                            <select name="coinage" id="coinage" class="form-control">
                                <option value="0" selected>No</option>
                                <option value="1">Yes</option>
                            </select>
                            <label for="coinage">Uses Coinage?*</label>
                            <span class="help-block">Select whether this payment method should be included in the coinage report</span>
                        </div>
                        <div id="slab_holder">
                            <div class="form-group form-md-line-input form-md-floating-label row">
                                <div>
                                    <span>Enter the payment method required fields. <strong>Leave the default value blank for fields with no default values</strong></span>
                                    <span class="pull-right"> <a href="#" id="add_row" class="fa fa-plus btn btn-primary"> Add Row</a></span>
                                </div>
                                <table class="table table-responsive">
                                    <thead>
                                        <tr>
                                            <th class="col-sm-1">#</th>
                                            <th class="col-sm-4">Field Name</th>
                                            <th class="col-sm-4">Type</th>
                                            <th class="col-sm-3">Default Value</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody id="row_holder">
                                @if(old('total_rows'))
                                        <input type="text" name="total_rows" id="total_rows" hidden value="{{ old('total_rows') }}">
                                    @for($i = 1; $i <= old('total_rows'); $i++)
                                        <tr id="row{{ $i }}">
                                            <input type="text" name="row_row{{ $i }}" id="row_row{{ $i }}" hidden value="{{ $i }}">
                                            <td class="row_number">{{ $i }}</td>
                                            <td><input type="text" name="field_name{{ $i }}" id="field_name{{ $i }}" class="form-control" placeholder="Account Number" value="{{ old('field_name' . $i)  }}"></td>
                                            <td><select name="field_type{{ $i }}" id="field_type{{ $i }}" class="form-control">
                                                    <option value="boolean" {{ old('field_type' . $i ) ==  'boolean' ? 'selected' : '' }}>Boolean</option>
                                                    <option value="date" {{ old('field_type' . $i ) ==  'date' ? 'selected' : '' }}>Date</option>
                                                    <option value="double" {{ old('field_type' . $i ) ==  'double' ? 'selected' : '' }}>Decimal</option>
                                                    <option value="integer" {{ old('field_type' . $i ) ==  'integer' ? 'selected' : '' }}>Numeric</option>
                                                    <option value="string" {{ old('field_type' . $i ) ==  'string' ? 'selected' : '' }}>String</option>
                                                </select>
                                            <td><div class="input-group"><input type="text" name="field_default{{ $i }}" id="field_default{{ $i }}" class="form-control" value="{{ old('field_default' . $i) }}"></div></td>
                                            <td><a href="#" class="btn btn-danger row_remove" id="remove_row{{ $i }}">Remove</a></td>
                                        </tr>
                                    @endfor
                                @else
                                        <input type="text" name="total_rows" id="total_rows" hidden value="1">
                                        <tr id="row1">
                                            <input type="text" name="row_row1" id="row_row1" hidden value="1">
                                            <td class="row_number">1</td>
                                            <td><input type="text" name="field_name1" id="field_name1" class="form-control" placeholder="Account Number"></td>
                                            <td><select name="field_type1" id="field_type1" class="form-control">
                                                    <option value="boolean">Boolean</option>
                                                    <option value="date">Date</option>
                                                    <option value="double">Decimal</option>
                                                    <option value="integer">Numeric</option>
                                                    <option value="string">String</option>
                                                </select>
                                            <td><div class="input-group"><input type="text" name="field_default1" id="field_default1" class="form-control" value="{{ old('field_default1') }}"></div></td>
                                            <td><a href="#" class="btn btn-danger row_remove" id="remove_row1">Remove</a></td>
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="form-group form-md-line-input form-md-floating-label">
                            <input type="submit" class="btn btn-primary" value="Save">
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