@extends('layouts.app')

@section('page-title')
    {{ __('Sales Category Report') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item" aria-current="page">{{ __('Sales Category Report') }}</li>
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
                        {!! Form::select('category_id[]', $MainCategoryList, null, [
                            'class' => 'form-control multi-select ',
                            'multiple' => 'multiple',
                            'data-role' => 'tagsinput',
                            'id' => 'category_id',
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
                var selectedCategory = $('#category_id').val();
                $.ajax({
                    type: "GET",
                    url: '{{ route('reports.category.order.chart') }}',
                    async: true,
                    data: {
                        _token: csrfToken,
                        chart_data: value,
                        Date: date,
                        selectedCategory: selectedCategory,
                    },
                    success: function(data) {
                        $('.chart_data').html(data.html);
                        var seriesData = [];

                        data.NetSaleTotal.forEach(function(category) {
                            var series = {
                                name: category.name,
                                type: 'column',
                                data: category.data,
                            };
                            seriesData.push(series);
                        });

                        (function() {
                            var options = {
                                series: seriesData,
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
                                colors: ['#F4B41ABF', '#6FD943','#D80027','#002333'],

                                xaxis: {
                                    categories: data.monthList,
                                }
                            };

                            var chart = new ApexCharts(document.querySelector(".traffic-chart"),
                                options);
                            chart.render();
                        })();
                    },
                    error: function(error) {

                    }
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
                var selectedCategory = $('#category_id').val();

                var selectedValue = $(".chart-data.active").val();
                var date = $('.date').val();

                fetchData(selectedValue, date, selectedCategory);
            });

            $('#category_id').on('change', function() {
                var selectedCategory = $(this).val();
            });

        });
    </script>
    <script>
        $(document).ready(function() {
            $('.exportChartButton').click(function() {
                $.ajax({
                    url: '{{ route('category.order.export') }}',
                    method: 'GET',
                    success: function(data) {
                        console.log(data.NetSaleTotal);
                        if (data.hasOwnProperty("monthList") && data.hasOwnProperty(
                                "NetSaleTotal")) {
                            var csvContent =
                                "data:text/csv;charset=utf-8,Duration,Category Name,Total Net Sale\n";

                            // Assuming data.NetSaleTotal is an array of objects with "name" and "data" properties
                            for (var i = 0; i < data.monthList.length; i++) {
                                var categoryName = data.NetSaleTotal[0]
                                .name; // Assuming you want the "main-category" name
                                var netSale = data.NetSaleTotal[0].data[
                                i]; // Assuming you want the data for "main-category"

                                csvContent +=
                                    `${data.monthList[i]},${categoryName},${netSale}\n`;
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
