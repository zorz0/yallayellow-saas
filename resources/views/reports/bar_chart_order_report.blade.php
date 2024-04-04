@extends('layouts.app')

@section('page-title')
    {{ __('Sales Report') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item" aria-current="page">{{ __('Sales Report') }}</li>
@endsection

@section('action-button')
    <div class="text-end">
        <a href="javascript:;" class="btn btn-sm btn-primary btn-icon exportChartButton" title="{{ __('Export') }}"
            data-bs-toggle="tooltip" data-bs-placement="top">
            <i class="ti ti-file-export"></i>
        </a>
        <a href="{{ route('reports.order_report') }}" class="btn btn-sm btn-primary btn-icon"
            title="{{ __('Line Chart') }}" data-bs-toggle="tooltip" data-bs-placement="top">
            <i class="ti ti-chart-line"></i>
        </a>
    </div>
@endsection
@section('content')
    <div class="row">
        <div class="col-xxl-12">
            <div class="card card-body">
                <ul class="nav nav-pills gap-2 gap-3" id="pills-tab" role="tablist">
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
                    <div id="deals-staff-report" height="400" style="" width="1100" data-color="primary"
                        data-height="280">
                    </div>
                </ul>
            </div>
        </div>
        <div class="chart_data pc-dt-export">

        </div>
    </div>
@endsection
@push('custom-script')
    <script>
        $(document).ready(function() {
            function fetchData(value, date) {
                var csrfToken = $('meta[name="csrf-token"]').attr('content');

                $.ajax({
                    type: "GET",
                    url: '{{ route('reports.order.chart.data') }}',
                    async: true,
                    data: {
                        _token: csrfToken,
                        chart_data: value,
                        Date: date,
                    },
                    success: function(data) {
                        $('.chart_data').html(data.html);
                        (function() {
                            var options = {
                                chart: {
                                    height: 250,
                                    type: 'area',
                                    toolbar: {
                                        show: false,
                                    },
                                },
                                dataLabels: {
                                    enabled: false
                                },
                                stroke: {
                                    width: 2,
                                    curve: 'smooth'
                                },
                                series: [{
                                    name: 'Total Gross Sale',
                                    data: data.GrossSaleTotal
                                }, {
                                    name: 'Total Average Gross Sale',
                                    data: data.AverageGrossSales
                                }, {
                                    name: 'Total Net Sale',
                                    data: data.NetSaleTotal
                                }, {
                                    name: 'Total Average Net Sale',
                                    data: data.AverageNetSales
                                }],
                                xaxis: {
                                    categories: data.monthList,
                                },
                                colors: ['#ffa21d', '#FF3A6E','#008FFB', '#00E396'],

                                grid: {
                                    strokeDashArray: 4,
                                },
                                legend: {
                                    show: false,
                                }
                            };
                            if (!areAllZeros()) {
                                options.yaxis = {
                                    title: {
                                        text: ''
                                    },
                                    labels: {
                                        formatter: function(value) {
                                            return value.toFixed(
                                                2); // Format to 2 decimal places
                                        }
                                    }
                                };
                            }

                            function areAllZeros() {
                                // Check if all data arrays contain only zeros
                                return (
                                    data.GrossSaleTotal.every(function(val) {
                                        return val === 0;
                                    }) &&
                                    data.AverageGrossSales.every(function(val) {
                                        return val === 0;
                                    }) &&
                                    data.NetSaleTotal.every(function(val) {
                                        return val === 0;
                                    }) &&
                                    data.AverageNetSales.every(function(val) {
                                        return val === 0;
                                    })
                                );
                            }

                            var chart = new ApexCharts(document.querySelector(".myChart"), options);
                            chart.render();
                        })();
                        (function() {
                            var options = {
                                series: [{
                                        name: 'Total charged of Shipping',
                                        data: data.ShippingTotal
                                    },
                                    {
                                        name: 'Total Worth of coupons used',
                                        data: data.CouponTotal
                                    }
                                ],
                                chart: {
                                    height: 350,
                                    type: 'line',
                                    toolbar: {
                                        show: false
                                    },
                                },
                                forecastDataPoints: {
                                    count: 7
                                },
                                stroke: {
                                    width: 5,
                                    curve: 'smooth'
                                },
                                xaxis: {
                                    categories: data.monthList,
                                    tickAmount: 10,
                                },
                                colors: ['#F4B41A', '#6FD943'],
                                title: {
                                    text: '',
                                    align: 'left',
                                    style: {
                                        fontSize: "16px",
                                        color: '#666'
                                    }
                                },
                                fill: {
                                    type: 'gradient',
                                    gradient: {
                                        shade: 'dark',
                                        gradientToColors: ['#FDD835'],
                                        shadeIntensity: 1,
                                        type: 'horizontal',
                                        opacityFrom: 1,
                                        opacityTo: 1,
                                        stops: [0, 100, 100, 100]
                                    },
                                }
                            };

                            var chart = new ApexCharts(document.querySelector(".traffic-chart"),
                                options);
                            chart.render();

                        })();

                        var options = {
                            series: [data.PurchasedItemTotal, data.TotalOrderCount],
                            chart: {
                                width: 380,
                                type: 'donut',
                            },
                            colors: ['#F4B41A', '#6FD943'],
                            labels: ['Total Items', 'Total Order'],
                            responsive: [{
                                breakpoint: 480,
                                options: {
                                    chart: {
                                        width: 230
                                    },
                                    legend: {
                                        position: 'bottom'

                                    }
                                }
                            }]
                        };
                        var chart = new ApexCharts(document.querySelector(".radialbar-charts"), options);
                        chart.render();
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
        });
    </script>
    <script>
        $(document).ready(function() {
            $('.exportChartButton').click(function() {
                $.ajax({
                    url: '{{ route('order.barchart.reports.export') }}',
                    method: 'GET',
                    success: function(data) {
                        if (data.hasOwnProperty("monthList") && data.hasOwnProperty("NetSaleTotal") &&
                            data
                            .hasOwnProperty("AverageNetSales") && data.hasOwnProperty(
                                "GrossSaleTotal") && data.hasOwnProperty("AverageGrossSales") && data.hasOwnProperty(
                                "ShippingTotal") && data.hasOwnProperty("CouponTotal")) {
                            var csvContent =
                                "data:text/csv;charset=utf-8,Duration,Total Net Sale ,Total Average Net Sale , Total Gross Sale ,Total Average Gross Sale ,Total Shipping Charge ,Total Worth of coupon \n";
                            for (var i = 0; i < data.monthList.length; i++) {
                                csvContent +=
                                    `${data.monthList[i]},${data.NetSaleTotal[i]},${data.AverageNetSales[i]},${data.GrossSaleTotal[i]},${data.AverageGrossSales[i]},${data.ShippingTotal[i]},${data.CouponTotal[i]}\n`;
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

                if ($(this).val() !== '') {
                    $('#generateButton').prop('disabled', false);
                } else {
                    $('#generateButton').prop('disabled', true);
                }
            });
        });
    </script>
@endpush
