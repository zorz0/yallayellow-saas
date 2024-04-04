@extends('layouts.app')

@section('page-title')
    {{ __('Customer Reports') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item" aria-current="page">{{ __('Customer Reports') }}</li>
@endsection

@section('action-button')
    <div class="text-end">
        <a href="javascript:;" class="btn btn-sm btn-primary btn-icon exportChartButton" title="{{ __('Export') }}"
            data-bs-toggle="tooltip" data-bs-placement="top">
            <i class="ti ti-file-export"></i>
        </a>
    </div>
@endsection

@php
@endphp



@section('content')
    <div class="row">
        <div class="col-xxl-12">
            <div class="card card-body">
                <ul class="nav nav-pills gap-2 gap-3" id="pills-tab" role="tablist">
                    <li class="nav-item mb-2">
                        <button class="nav-link  chart-data active" name="chart_data" value="year">year</button>
                    </li>
                    <li class="nav-item mb-2">
                        <button class="nav-link chart-data" name="chart_data" value="last-month">Last month</button>
                    </li>
                    <li class="nav-item mb-2">
                        <button class="nav-link chart-data" name="chart_data" value="this-month">This month</button>
                    </li>
                    <li class="nav-item mb-2">
                        <button class="nav-link chart-data" name="chart_data" value="seven-day">Last 7 days</button>
                    </li>
                    <div class="nav-item mb-2">
                        {{ Form::date('date', isset($_GET['date']) ? $_GET['date'] : date('Y-m-d'), ['class' => 'date month-btn form-control', 'id' => 'pc-daterangepicker-1', 'placeholder' => 'YYYY-MM-DD']) }}


                    </div>
                    <div class="col-md-2 " id="filter_type" style="padding-left :10px;">
                        <button
                            class="btn btn-primary label-margin chart-data generate_button">{{ __('Generate') }}</button>
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
                    url: '{{ route('reports.chart') }}',
                    async: true,
                    data: {
                        _token: csrfToken,
                        chart_data: value,
                        Date: date,
                    },
                    success: function(data) {
                        $('.chart_data').html(data.html);
                        (function() {
                            var chartBarOptions = {
                                series: [{
                                    name: '{{ __('Customer') }}',
                                    data: data.userTotal,

                                }, {
                                    name: "{{ __('Guest') }}",
                                    data: data.guestTotal
                                }, ],

                                chart: {
                                    height: 300,
                                    type: 'bar',
                                    stacked: true,

                                    // type: 'line',
                                    dropShadow: {
                                        enabled: true,
                                        color: '#000',
                                        top: 18,
                                        left: 7,
                                        blur: 10,
                                        opacity: 0.2
                                    },
                                    toolbar: {
                                        show: false
                                    }
                                },
                                dataLabels: {
                                    enabled: false
                                },
                                // stroke: {
                                //     width: 2,
                                //     curve: 'smooth'
                                // },
                                title: {
                                    text: '',
                                    align: 'left'
                                },
                                xaxis: {
                                    categories: data.monthList,
                                    title: {
                                        text: '{{ __('Months') }}'
                                    }
                                },
                                colors: ['#6FD943BF', '#F4B41ABF'],


                                grid: {
                                    strokeDashArray: 4,
                                },
                                legend: {
                                    show: false,
                                },
                                yaxis: {


                                }

                            };
                            var arChart = new ApexCharts(document.querySelector(".myChart"),
                                chartBarOptions);
                            arChart.render();
                        })();
                        var options = {
                            series: [data.customer, data.guest],
                            chart: {
                                width: 380,
                                type: 'donut',
                            },
                            colors: ['#6FD943', '#F4B41A'],
                            labels: ['Customer', 'Guest'],
                            responsive: [{
                                breakpoint: 480,
                                options: {
                                    chart: {
                                        width: 260
                                    },
                                    legend: {
                                        position: 'bottom'

                                    }
                                }
                            }]
                        };
                        var chart = new ApexCharts(document.querySelector(".chart"), options);
                        chart.render();
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
                                    name: 'Customer',
                                    data: data.registerTotal
                                }, {
                                    name: ' Guest',
                                    data: data.newguestTotal
                                }],
                                xaxis: {
                                    categories: data.monthList,
                                    title: {
                                        text: '{{ __('Days') }}'
                                    }
                                },
                                colors: ['#75DA48', '#F4B41A'],

                                grid: {
                                    strokeDashArray: 4,
                                },
                                legend: {
                                    show: false,
                                },
                                yaxis: {
                                    tickAmount: 3,
                                    title: {},
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
        });
    </script>
    <script>
        $(document).ready(function() {
            $('.exportChartButton').click(function() {
                $.ajax({
                    url: '{{ route('reports.export') }}',
                    method: 'GET',
                    success: function(data) {
                        console.log(data)
                        if (data.hasOwnProperty("monthList") && data.hasOwnProperty(
                            "userTotal") && data.hasOwnProperty("registerTotal") && data
                            .hasOwnProperty("guest") && data.hasOwnProperty("newguestTotal")) {
                            var csvContent =
                                "data:text/csv;charset=utf-8,Duration,Customer orders,Guest orders ,Signups Customer , Guest \n";
                            for (var i = 0; i < data.monthList.length; i++) {
                                csvContent +=
                                    `${data.monthList[i]},${data.userTotal[i]},${data.guest[i]},${data.registerTotal[i]},${data.newguestTotal[i]}\n`;
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
        });
    </script>
@endpush
