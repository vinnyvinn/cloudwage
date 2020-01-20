@extends('layout')
@section('content')
    <div class="page-head">
        <div class="page-title">
            <h1>Deductions - <small> Set up the deductions that can be assigned to employees in the organization</small></h1>
        </div>
    </div>
    <ul class="page-breadcrumb breadcrumb">
        <li>
            <a href="{{ url('/') }}">Home</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="{{ route('deductions.index') }}">Deductions</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="#">Create</a>
        </li>
    </ul>

    <div class="row">
        <div class="col-sm-12">
            <form action="{{ route('deductions.store') }}" method="post" role="form">
                {{ csrf_field() }}
                <div class="portlet light">
                <div class="portlet-title">
                    <div class="caption font-red-sunglo">
                        <i class="fa fa-briefcase font-red-sunglo"></i>
                        <span class="caption-subject bold uppercase"> Deduction Details</span>
                    </div>
                </div>
                <div class="portlet-body form">
                    <div class="form-body">
                        <div class="form-group form-md-line-input form-md-floating-label">
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                            <label for="name">Deduction Name*</label>
                            <span class="help-block">This is the name of the deduction</span>
                        </div>
                        <div class="form-group form-md-line-input form-md-floating-label">
                            <input type="text" class="form-control" id="threshold" name="threshold" value="{{ old('threshold') }}" required>
                            <label for="name">Excemption*</label>
                            <span class="help-block">This is the minimum amount that an individual must earn in order to be deducted</span>
                        </div>
                        <div class="form-group form-md-line-input form-md-floating-label">
                            <select name="type" id="type" class="form-control" >
                                <option value="slab" {{ old('type') == 'slab' ? 'selected' : '' }}>Deduction Slab</option>
                                <option value="per_employee" {{ old('type') == 'per_employee' ? 'selected'  : '' }}>Per Employee</option>
                                <option value="rate" {{ old('type') == 'rate' ? 'selected'  : '' }}>Rate</option>
                            </select>
                            <label for="type">Type</label>
                            <span class="help-block">Choose whether the deduction is rate based or slab based</span>
                        </div>

                        <div id="rate_holder" hidden>
                            <div class="form-group form-md-line-input form-md-floating-label input-group">
                                <input type="text" class="form-control" id="rate" name="rate" value="{{ old('rate') }}">
                                <span class="input-group-addon">Rate</span>
                                <label for="rate">Rate Amount</label><br>
                                <span class="help-block" style="margin-left: -100%;">This is the rate to be applied to the given deduction. Add % if it is a percentage</span>
                            </div>
                        </div>
                        <div id="slab_holder" hidden>
                            <div class="form-group form-md-line-input form-md-floating-label row">
                                <div>
                                    <span>Enter the slab details below and leave the <strong>TO</strong> entry for the last element empty if it represents <strong>ANY AMOUNT ABOVE THE FROM</strong> entry</span>
                                    <span class="pull-right"> <a href="#" id="add_row" class="fa fa-plus btn btn-primary"> Add Row</a></span>
                                </div>
                                <table class="table table-responsive">
                                    <thead>
                                        <tr>
                                            <th class="col-sm-1">#</th>
                                            <th class="col-sm-4">From</th>
                                            <th class="col-sm-4">To</th>
                                            <th class="col-sm-3">Amount - Add % if it is a percentage</th>
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
                                            <td><input type="text" name="min_amount{{ $i }}" id="min_amount{{ $i }}" class="form-control" placeholder="0" value="{{ old('min_amount' . $i)  }}"></td>
                                            <td><input type="text" name="max_amount{{ $i }}" id="max_amount{{ $i }}" class="form-control" value="{{ old('max_amount' . $i)  }}"></td>
                                            <td><div class="input-group"><input type="text" name="rate{{ $i }}" id="rate{{ $i }}" class="form-control" placeholder="10" value="{{ old('rate' . $i) }}"></div></td>
                                            <td><a href="#" class="btn btn-danger row_remove" id="remove_row{{ $i }}">Remove</a></td>
                                        </tr>
                                    @endfor
                                @else
                                        <input type="text" name="total_rows" id="total_rows" hidden value="2">
                                        <tr id="row1">
                                            <input type="text" name="row_row1" id="row_row1" hidden value="1">
                                            <td class="row_number">1</td>
                                            <td><input type="text" name="min_amount1" id="min_amount1" class="form-control" placeholder="0"></td>
                                            <td><input type="text" name="max_amount1" id="max_amount1" class="form-control"></td>
                                            <td><div class="input-group"><input type="text" name="rate1" id="rate1" class="form-control" placeholder="10"></div></td>
                                            <td><a href="#" class="btn btn-danger row_remove" id="remove_row1">Remove</a></td>
                                        </tr>
                                        <tr id="row2">
                                            <input type="text" name="row_row2" id="row_row2" hidden value="2">
                                            <td class="row_number">2</td>
                                            <td><input type="text" name="min_amount2" id="min_amount2" class="form-control" placeholder="0"></td>
                                            <td><input type="text" name="max_amount2" id="max_amount2" class="form-control"></td>
                                            <td><div class="input-group"><input type="text" name="rate2" id="rate2" class="form-control" placeholder="10"></div></td>
                                            <td><a href="#" class="btn btn-danger row_remove" id="remove_row2">Remove</a></td>
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="form-group form-md-line-input form-md-floating-label">
                            <select class="form-control" id="has_relief" name="has_relief">
                                <option value="1" {{ old('has_relief') == 0 ?: 'selected' }}>Yes</option>
                                <option value="0" {{ old('has_relief') == 1 ?: 'selected' }}>NO</option>
                            </select>
                            <label for="added_to_basic">Has Relief*</label>
                            <span class="help-block">This determines whether the allowance has any relief attached to it</span>
                        </div>
                        <div id="relief_holder" hidden>
                            <div class="form-group form-md-line-input form-md-floating-label">
                                <input type="text" class="form-control" id="relief_name" name="relief_name" value="{{ old('relief_name') }}">
                                <label for="relief_name">Relief Name</label>
                                <span class="help-block">This is the name to be used to refer to the given relief</span>
                            </div>
                            <div class="form-group form-md-line-input form-md-floating-label">
                                <input type="text" pattern="[0-9]+$" title="Numbers only" class="form-control" id="relief_amount" name="relief_amount" value="{{ old('relief_amount') }}">
                                <label for="relief_amount">Relief Amount</label>
                                <span class="help-block">This is the relief given to the allowance</span>
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
            var relief = $("#has_relief");
            var type = $("#type");
            var relief_holder = $('#relief_holder');
            var rate_holder = $('#rate_holder');
            var slab_holder = $('#slab_holder');

            type.on('change', function() {
                showRate();
            });

            relief.on('change', function() {
                showAmount();
            });

            function showAmount() {
                relief.val() == 1 ? relief_holder.show() : relief_holder.hide();
            }

            function showRate()
            {
                if(type.val() == 'rate')
                {
                    rate_holder.show();
                    slab_holder.hide();
                }
                else if (type.val() == 'per_employee')
                {
                    rate_holder.hide();
                    slab_holder.hide();
                }
                else
                {
                    rate_holder.hide();
                    slab_holder.show();
                }

            }

            showAmount();
            showRate();


            var sortableElement = $( ".sortable" );

            sortableElement.on( "sortupdate", function( event, ui ) {
                var order = sortableElement.sortable('toArray');

                for(var i = 0; i < order.length; i++)
                {
                    var row = order[i];
                    var row_number = '#' + row + ' .row_number';
                    var row_input = '#row_' + row;

                    $(row_number).html(i+1);
                    $(row_input).val(i+1);
                }

            });

            function bindEvents()
            {
                $('.row_remove').off();
                $('.row_remove').on('click', function(e) {
                    e.preventDefault();

                    var totalHolder = $('#total_rows');
                    var total = parseInt(totalHolder.val());

                    var current_row = $(this).attr('id');
                    var current_row_number = parseInt(current_row.substr(10, current_row.length));

                    if(total == 2)
                    {
                        console.log(total);
                        alert('Cannot Delete: You need at least two slabs');
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

                    var newRow = '<tr id="row' + newTotal +'"><input type="text" name="row_row' + newTotal +'" id="row_row' + newTotal +'" hidden value="3"><td class="row_number">' + newTotal +'</td><td><input type="text" name="min_amount' + newTotal +'" id="min_amount' + newTotal +'" class="form-control" placeholder="0"></td><td><input type="text" name="max_amount' + newTotal +'" id="max_amount' + newTotal +'" class="form-control"></td><td><div class="input-group"><input type="text" name="rate' + newTotal +'" id="rate' + newTotal +'" class="form-control" placeholder="10"></div></td><td><a href="#" class="btn btn-danger row_remove" id="remove_row' + newTotal + '">Remove</a></td></tr>';
                    $('#row_holder').append(newRow);

                    totalHolder.val(newTotal);

                    bindEvents();
                });
            }

            bindEvents();
        });
    </script>
@endsection