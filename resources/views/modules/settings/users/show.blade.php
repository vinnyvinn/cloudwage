@extends('layout')
@section('content')
    <div class="page-head">
        <div class="page-title">
            <h1>Policies - <small> Set up the system policies.</small></h1>
        </div>
    </div>
    <ul class="page-breadcrumb breadcrumb">
        <li>
            <a href="{{ url('/') }}">Home</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="{{ route('policies.index') }}">Policies</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="#">View</a>
        </li>
    </ul>

    <div class="row">
        <div class="col-sm-12">
            <form action="{{ route('policies.update', $policy->id) }}" method="post" role="form">
                {{ csrf_field() }}
                {{ method_field('put') }}
                <div class="portlet light">
                    <div class="portlet-title">
                        <div class="caption font-red-sunglo">
                            <i class="fa fa-briefcase font-red-sunglo"></i>
                            <span class="caption-subject bold uppercase"> Policy Details</span>
                        </div>
                    </div>
                    <div class="portlet-body form">
                        <div class="form-body">
                            <div class="form-group form-md-line-input form-md-floating-label">
                                <input type="text" class="form-control" value="{{ $policy->policy }}" readonly id="name">
                                <label for="name">Policy Name*</label>
                                <span class="help-block">This is name of the policy</span>
                            </div>
                            <div class="form-group form-md-line-input form-md-floating-label">
                                <input type="text" class="form-control" value="{{ $policy->description }}" readonly id="description">
                                <label for="description">Policy Description*</label>
                                <span class="help-block">This is the decription of the policy</span>
                            </div>
                            <div class="form-group form-md-line-input form-md-floating-label">
                                @if($policy->value == 'true')
                                    <input type="text" class="form-control" value="{Yes" readonly id="description">
                                @elseif($policy->value == 'false')
                                    <input type="text" class="form-control" value="No" readonly id="description">
                                @else
                                    <input type="text" class="form-control" value="{{ $policy->value . $policy->postfix }}" readonly id="description">
                                @endif

                                <label for="description">Policy Value*</label>
                                <span class="help-block">This is the decription of the policy</span>
                            </div>
                            <div class="form-group form-md-line-input form-md-floating-label">
                                <a class="btn btn-primary" href="{{ route('policies.edit', ['deduction' => $policy->id]) }}">Change</a>
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