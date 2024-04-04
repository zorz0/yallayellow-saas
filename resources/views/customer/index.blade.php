@extends('layouts.app')

@section('page-title')
    {{ __('Customer') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item" aria-current="page">{{ __('Customer') }}</li>
@endsection

@section('action-button')
    <div class="text-end">
        {{-- @permission('Manage Customer') --}}
            <a href="javascript:;" class="btn btn-sm btn-primary btn-icon csv" title="{{ __('Export') }}" data-bs-toggle="tooltip"
                data-bs-placement="top">
                <i class="ti ti-file-export"></i>
            </a>
        {{-- @endpermission --}}
    </div>
@endsection

@section('content')
    <div class="row">

        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class=" multi-collapse mt-2" id="multiCollapseExample1">
                        {{ Form::open(['route' => ['customer.filter.data'], 'method' => 'GET', 'id' => 'frm_submit']) }}
                        <div class="d-flex align-items-center justify-content-end card-input-wrapper">
                            <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12 col-12 mr-2">
                                <div class=" form-group">
                                    {{ Form::select('field_name', $customer_field, isset($_GET['field_name'])?$_GET['field_name']:null, ['class' => 'form-control', 'id' => 'customer_field', 'placeholder' => __('Select Customer Fields')]) }}
                                </div>
                            </div>
                            <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12 col-12 mr-2">
                                <div class=" form-group">
                                    <div id="select-container" class="value-select-box">
                                        {{ Form::hidden('select_value', null, ['id' => 'select-value']) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12 col-12 mr-2">
                                <div class=" form-group">
                                    <div id="text-field">
                                        {{ Form::hidden('text_value', null, ['id' => 'text-value']) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto float-end ms-2 mb-4">
                                <a href="#" class="btn btn-sm btn-primary" id="apply-button">
                                    <span class="btn-inner--icon">
                                        <i class="ti ti-search"></i>
                                    </span>
                                </a>
                                <a href="{{ route('customer.index') }}" class="btn btn-sm btn-warning"
                                    data-bs-toggle="tooltip" title="{{ __('Reset') }}">
                                    <span class="btn-inner--icon"><i class="ti ti-refresh text-white-off "></i></span>
                                </a>
                            </div>

                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <h5></h5>
                    <div class="table-responsive">
                        <table class="table mb-0 dataTable" id="pc-dt-export">
                            <thead>
                                <tr>
                                    <th> {{ __('customer info') }}</th>
                                    <th> {{ __('Email') }}</th>
                                    <th> {{ __('Last active') }}</th>
                                    <th> {{ __('Date registered') }}</th>
                                    <th> {{ __('Orders') }}</th>
                                    <th> {{ __('Total spend') }}</th>
                                    <th> {{ __('AOV') }}</th>
                                    {{-- <th> {{ __('Phone No') }}</th> --}}
                                    <th class="text-right ignore"> {{ __('Action') }}</th>
                                    <th class="text-right ignore"> {{ __('status') }}</th>
                                </tr>
                            </thead>
                            <tbody id="service-filter-data">
                                @foreach ($customers as $customer)
                                    <tr class="font-style">
                                        @php
                                            if ($customer->regiester_date !== null) {
                                                $carbonDate = \Carbon\Carbon::parse($customer->regiester_date);
                                                $formattedDate = $carbonDate->format('F d, Y');
                                            } else {
                                                $formattedDate = null;
                                            }
                                            if ($customer->last_active !== null) {
                                                $active = \Carbon\Carbon::parse($customer->last_active);
                                                $last_active = $active->format('F d, Y');
                                            } else {
                                                $last_active = null;
                                            }

                                            $AOV = 0;
                                            if ($customer->total_spend() != 0 && $customer->Ordercount() != 0) {
                                                $AOV = number_format($customer->total_spend() / $customer->Ordercount(), 2);
                                            }

                                            $activityLogEntry = $activitylog->where('customer_id', $customer->id)->first();

                                        @endphp
                                        <td>
                                            @if ($activityLogEntry)
                                                <a class="text-success" href="{{ route('customer.timeline', $customer->id) }}">
                                                    <span class="btn-inner--icon"></span>
                                                    <span class="btn-inner--text text- capitalize">{{ $customer->first_name }}
                                                        {{ $customer->last_name }}</span><br>

                                                </a>
                                                {{ $customer->mobile }}
                                            @else
                                              <p class="text- capitalize text-success"> {{ $customer->first_name }} {{ $customer->last_name }} </p> <br>
                                              {{ $customer->mobile }}
                                            @endif
                                        </td>
                                        <td>{{ $customer->email }}</td>
                                        <td>{{ $last_active }}</td>
                                        <td>{{ $formattedDate }}</td>
                                        <td>
                                            <a href="{{ route('customer.show', $customer->id) }}">
                                                {{ $customer->Ordercount() }}
                                            </a>

                                        </td>
                                        <td>{{ $customer->total_spend() }}</td>
                                        <td>{{ $AOV }}</td>

                                        <td class="Action ignore">
                                            <div class="d-flex">
                                                @if ($activityLogEntry)
                                                    <a href="{{ route('customer.timeline', $customer->id) }}"
                                                        class="btn btn-sm btn-icon btn-warning me-2"
                                                        data-bs-placement="top">
                                                        <i class="ti ti-eye f-20"></i>
                                                    </a>
                                                @endif
                                                {{-- @permission('Show Customer') --}}
                                                    <a href="{{ route('customer.show', $customer->id) }}"
                                                        class="btn btn-sm btn-icon btn-info me-2" data-bs-placement="top">
                                                        <i class="ti ti-shopping-cart f-20"></i>
                                                    </a>
                                                {{-- @endpermission --}}
                                            </div>
                                        </td>
                                        <td>
                                        {{-- @permission('Status Customer') --}}
                                                @if ($customer->regiester_date != null)
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input page-checkbox" id="{{ $customer->id }}"
                                                            type="checkbox" name="page_active" data-onstyle="success"
                                                            data-offstyle="danger" data-toggle="toggle" data-on="off"
                                                            data-off="on"
                                                            @if ($customer->status == 1) checked="checked" @endif />
                                                    </div>
                                                @endif
                                        {{-- @endpermission --}}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('custom-script')
    <script type="text/javascript">
        $(document).ready(function() {

            $(document).on('change', '.page-checkbox', function() {
                var status = $(this).prop('checked') == true ? 1 : 0;
                var customer_id = $(this).attr('id');

                $.ajax({
                    type: "GET",
                    dataType: "json",
                    url: "{{ route('update.customer.status') }}",
                    data: {
                        'status': status,
                        'customer_id': customer_id
                    },
                    success: function(data) {
                        if (data.success) {
                            show_toastr('Success', data.success, 'success');
                        } else {
                            show_toastr('Error', "{{ __('Something went wrong.') }}", 'error');
                        }
                    },
                });
            })
        })
    </script>
    <script src="https://rawgit.com/unconditional/jquery-table2excel/master/src/jquery.table2excel.js"></script>
    <script>
        const d = new Date();
        let seconds = d.getSeconds();
        $('.csv').on('click', function() {
            $('.ignore').remove();
            $("#pc-dt-export").table2excel({
                filename: "Customer_" + seconds
            });
            window.location.reload();
        })
    </script>

    <script>
        $(document).ready(function() {

            $('#customer_field').on('change', function() {
                var selectedValue = $(this).val();
                var data = {
                    customer_field: selectedValue,
                }
                $.ajax({
                    url: '{{ route('customer.filter') }}',
                    method: 'GET',
                    data: data,
                    context: this,
                    success: function(response) {
                        $('#select-container').empty();
                        $('#text-field').empty();

                        if (response.condition) {
                            var selectBox = $('<select name="selected_name" class="form-control">');
                            var TextField = $('<input>');

                            $.each(response.condition, function(index, option) {
                                var optionElement = $('<option>');
                                optionElement.val(option);
                                optionElement.text(option);
                                selectBox.append(optionElement);
                            });

                            $('#select-container').append(selectBox);

                            var inputType = response.field_type;

                            if (inputType === 'text' || inputType === 'number' || inputType === 'email' || inputType === 'date') {
                                var inputElement = $('<input name="text_field" class="form-control">');

                                inputElement.attr('type', inputType);
                                $('#text-field').append(inputElement);

                                // Set the values of the hidden input fields
                                $('#select-value').val(selectBox.val());
                                $('#text-value').val(inputElement.val());
                            }
                        }
                    }
                });
            });

            $('#frm_submit').on('submit', function(event) {
                event.preventDefault();
                applyFilter();
            });

            $('#apply-button').on('click', function(event) {
                event.preventDefault();
                applyFilter();
            });

            // Function to apply the filter
            function applyFilter() {
                var selectedValue = $('#customer_field').val();
                var select = $('#select-container select').val();
                var TextValue = $('#text-field input').val();

                var data = {
                    'text_field': TextValue,
                    'selected_name': select,
                    'customer_field': selectedValue,
                }

                $.ajax({
                    url: '{{route('customer.filter.data')}}',
                    type: 'GET',
                    data: data,
                    context: this,
                    success: function (data) {
                        $('#service-filter-data').html('');
                        $('#service-filter-data').html(data);
                    }
                });
            }
        });
    </script>
@endpush
