@extends('layouts.app')

@section('page-title')
    {{ __('Sales Product Report') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item" aria-current="page">{{ __('Sales Product Report') }}</li>
@endsection

@section('action-button')
    <div class="text-end">
        <a href="javascript:;" class="btn btn-sm btn-primary btn-icon exportChartButton" title="{{ __('Export') }}"
            data-bs-toggle="tooltip" data-bs-placement="top">
            <i class="ti ti-file-export"></i>
        </a>
    </div>
@endsection
@section('content')
    <div class="row">
        <div class="col-xxl-12">
            <div class="card card-body">
                <ul class="nav nav-pills gap-2" id="pills-tab" role="tablist">
                    <li class="nav-item mb-2">
                        <button class="nav-link  chart-data active" name="chart_data"
                            value="year">{{ __('year') }}</button>
                    </li>
                    <li class="nav-item mb-2">
                        <button class="nav-link chart-data" name="chart_data"
                            value="last-month">{{ __('Last month') }}</button>
                    </li>
                    <li class="nav-item mb-2">
                        <button class="nav-link chart-data" name="chart_data"
                            value="this-month">{{ __('This month') }}</button>
                    </li>
                    <li class="nav-item mb-2">
                        <button class="nav-link chart-data" name="chart_data" value="seven-day">{{ 'Last 7 days' }}</button>
                    </li>
                    <div class="nav-item mb-2">
                        {{ Form::date('date', isset($_GET['date']) ? $_GET['date'] : date('Y-m-d'), ['class' => 'date month-btn form-control', 'id' => 'pc-daterangepicker-1', 'placeholder' => 'YYYY-MM-DD']) }}
                    </div>
                    <div class="col-md-2 " id="filter_type" style="padding-left :10px;">
                        <button class="btn btn-primary label-margin chart-data generate_button" id="generateButton"
                            name="chart_data" value="date-wise">{{ __('Generate') }}</button>
                    </div>
                    <li class="nav-item d-flex align-items-center report-filter custom-width">
                        {!! Form::select('product_id[]', $products, null, [
                            'class' => 'form-control multi-select ',
                            'multiple' => 'multiple',
                            'data-role' => 'tagsinput',
                            'id' => 'product_id',
                        ]) !!}
                        <a href="#" class="btn btn-sm btn-primary filter-apply-btn" data-bs-toggle="tooltip"
                            data-original-title="{{ __('apply') }}">
                            <span class="btn-inner--icon"><i class="ti ti-search"></i></span>
                        </a>
                        <a href="" class="btn btn-sm btn-warning " data-bs-toggle="tooltip"
                            title="{{ __('Reset') }}" data-original-title="{{ __('Reset') }}">
                            <span class="btn-inner--icon"><i class="ti ti-refresh text-white-off"></i></span>
                        </a>
                    </li>
                </ul>
                <div></div>
            </div>
        </div>
        <div class="chart_data pc-dt-export">

        </div>
    </div>
@endsection
@push('custom-script')
    <script>
        if ($(".multi-select").length > 0) {
            $($(".multi-select")).each(function(index, element) {
                var id = $(element).attr('id');
                var multipleCancelButton = new Choices(
                    '#' + id, {
                        removeItemButton: true,
                    }
                );
            });
        }
        $(document).ready(function() {

            function fetchData(value, date) {
                var csrfToken = $('meta[name="csrf-token"]').attr('content');
                var selectedProducts = $('#product_id').val();
                $.ajax({
                    type: "GET",
                    url: '{{ route('reports.product.order.chart') }}',
                    async: true,
                    data: {
                        _token: csrfToken,
                        chart_data: value,
                        Date: date,
                        selectedProducts: selectedProducts,
                    },
                    success: function(data) {
                        $('.chart_data').html(data.html);
                        (function() {
                            var options = {
                                series: [{
                                        name: 'Total Net sale',
                                        type: 'column',
                                        data: data.NetSaleTotal,
                                    },
                                    {
                                        name: 'Total purchased of items',
                                        type: 'line',
                                        data: data.PurchasedItemTotal,
                                    }
                                ],
                                chart: {
                                    height: 350,
                                    type: 'line',
                                    toolbar: {
                                        show: false
                                    },
                                },
                                stroke: {
                                    width: [0, 4]
                                },
                                colors: ['#6FD943BF','#F4B41ABF'],
                                xaxis: {
                                    categories: data.monthList,
                                }
                            };
                            var chart = new ApexCharts(document.querySelector(".traffic-chart"),
                                options);
                            chart.render();
                        })();
                    },
                    error: function(error) {}
                });
            }

            $(".chart-data").on("click", function() {
                $(".chart-data").removeClass("active");
                $(this).addClass("active");
                var value = $(this).val();
                var date = $('.date').val();

                fetchData(value, date);
            });

            var firstValue = $(".chart-data:first").val();
            $(".chart-data:first").addClass("active");
            fetchData(firstValue);

            $('.filter-apply-btn').on('click', function() {
                var selectedProducts = $('#product_id').val();

                var selectedValue = $(".chart-data.active").val();
                var date = $('.date').val();

                fetchData(selectedValue, date, selectedProducts);
            });

            $('#product_id').on('change', function() {
                var selectedProducts = $(this).val();
            });

        });
    </script>
    <script>
        $(document).ready(function() {
            $('.exportChartButton').click(function() {
                $.ajax({
                    url: '{{ route('product.order.export') }}',
                    method: 'GET',
                    success: function(data) {
                        if (data.hasOwnProperty("monthList") && data.hasOwnProperty(
                                "NetSaleTotal") &&
                            data.hasOwnProperty("PurchasedItemTotal")) {
                            var csvContent =
                                "data:text/csv;charset=utf-8,Duration,Total Net Sale ,Total Purchased items \n";
                            for (var i = 0; i < data.monthList.length; i++) {
                                csvContent +=
                                    `${data.monthList[i]},${data.NetSaleTotal[i]},${data.PurchasedItemTotal[i]}\n`;
                            }

                            var encodedUri = encodeURI(csvContent);
                            var link = document.createElement("a");
                            link.setAttribute("href", encodedUri);
                            link.setAttribute("download", "exported_data.csv");
                            document.body.appendChild(link);
                            link.click();
                        } else {
                            console.error("Data is not in the expected format:", data);
                        }
                    }
                });
            });


            $('#generateButton').prop('disabled', true);
            $('#pc-daterangepicker-1').on('change', function() {
                // Check if a date is selected
                if ($(this).val() !== '') {
                    // Enable the "Generate" button
                    $('#generateButton').prop('disabled', false);
                } else {
                    // Disable the "Generate" button if no date is selected
                    $('#generateButton').prop('disabled', true);
                }
            });
        });
    </script>
@endpush
