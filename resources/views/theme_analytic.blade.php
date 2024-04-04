@extends('layouts.app')

@section('page-title')
    {{ __('Store Analytics') }}
@endsection

@push('css-page')
@endpush
@section('content')

    <div class="row">
        <div class="col-xxl-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h5>{{ __('Visitor') }}</h5>
                </div>
                <div class="card-body">
                    <div id="apex-storedashborad" data-color="primary" data-height="200"></div>
                </div>
            </div>
        </div>
        {{-- <div class="col-sm-12"> --}}
            {{-- <div class="row"> --}}
                <div class="col-xxl-6 dash-data">
                    <div class="card min-h-490 overflow-auto">
                        <div class="card-header d-flex justify-content-between">
                            <h5>{{ __('Top URL') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <tbody>
                                    @foreach ($visitor_url as $url)
                                        <tr>
                                            <td>
                                                <h6 class="m-0"><a
                                                        href="#">{{ $theme }}</a> </h6>
                                            </td>
                                            <td class="text-end">
                                                <h6 class="m-0">{{ $url->total }}</h6>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="card ">
                        <div class="card-header d-flex justify-content-between">
                            <h5>{{ __('Device') }}</h5>
                        </div>
                        <div class="card-body">
                            <div id="pie-storedashborad"></div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-6 dash-data">
                    <div class="card data-chart">
                        <div class="card-header d-flex justify-content-between">
                            <h5>{{ 'Platform' }}</h5>
                        </div>
                        <div class="card-body">
                            <div id="user_platform-chart"></div>
                        </div>
                    </div>
                    <div class="card data-chart">
                        <div class="card-header d-flex justify-content-between">
                            <h5>{{ __('Browser') }}</h5>
                        </div>
                        <div class="card-body">
                            <div id="pie-storebrowser"></div>
                        </div>
                    </div>
                </div>
            {{-- </div> --}}
        {{-- </div> --}}
    </div>



@endsection
@push('custom-script')
    <script>
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
                    name: "{{ __('Totle Page View') }}",
                    data: {!! json_encode($chartData['data']) !!}
                }, {
                    name: "{{ __('Unique Page View') }}",
                    data: {!! json_encode($chartData['unique_data']) !!}
                }],

                xaxis: {
                    categories: {!! json_encode($chartData['label']) !!},
                    title: {
                        text: 'Days'
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
                }
            };
            var chart = new ApexCharts(document.querySelector("#apex-storedashborad"), options);
            chart.render();
        })();

        var options = {
            series: {!! json_encode($devicearray['data']) !!},
            chart: {
                width: 350,
                type: 'donut',
            },
            colors: ["#6FD943", "#F4B41A", "#F4614D", "#F1F1F1"],
            labels: {!! json_encode($devicearray['label']) !!},
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        width: 200
                    },
                    legend: {
                        position: 'bottom',
                    }
                }
            }]
        };
        var chart = new ApexCharts(document.querySelector("#pie-storedashborad"), options);
        chart.render();

        var options = {
            series: {!! json_encode($browserarray['data']) !!},
            chart: {
                width: 350,
                type: 'donut',
            },
            colors: ["#6FD943", "#F4B41A", "#F4614D", "#F1F1F1"],
            labels: {!! json_encode($browserarray['label']) !!},
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        width: 200
                    },
                    legend: {
                        position: 'bottom',
                    }
                }
            }]
        };
        var chart = new ApexCharts(document.querySelector("#pie-storebrowser"), options);
        chart.render();
    </script>
    <script>
        var WorkedHoursChart = (function() {
            var $chart = $('#user_platform-chart');

            function init($this) {
                var options = {
                    chart: {
                        width: '100%',
                        type: 'bar',
                        zoom: {
                            enabled: false
                        },
                        toolbar: {
                            show: false
                        },
                        shadow: {
                            enabled: false,
                        },

                    },
                    plotOptions: {
                        bar: {
                            horizontal: false,
                            distributed: true,
                            columnWidth: '25%',
                            borderRadius: 12,
                            endingShape: 'rounded'
                        },
                    },
                    colors: ["#6FD943", "#F4B41A", "#F4614D", "#F1F1F1  "],
                    stroke: {
                        show: true,
                        width: 2,
                        colors: ['transparent']
                    },
                    series: [{
                        name: 'Platform',
                        data: {!! json_encode($platformarray['data']) !!},
                    }],
                    xaxis: {
                        labels: {
                            // format: 'MMM',
                            style: {
                                colors: PurposeStyle.colors.gray[600],
                                fontSize: '14px',
                                fontFamily: PurposeStyle.fonts.base,
                                cssClass: 'apexcharts-xaxis-label',
                            },
                        },
                        axisBorder: {
                            show: false
                        },
                        axisTicks: {
                            show: true,
                            borderType: 'solid',
                            color: PurposeStyle.colors.gray[300],
                            height: 6,
                            offsetX: 0,
                            offsetY: 0
                        },
                        title: {
                            text: '{{ __('Platform') }}'
                        },
                        categories: {!! json_encode($platformarray['label']) !!},
                    },
                    yaxis: {
                        labels: {
                            style: {
                                color: PurposeStyle.colors.gray[600],
                                fontSize: '12px',
                                fontFamily: PurposeStyle.fonts.base,
                            },
                        },
                        axisBorder: {
                            show: false
                        },
                        axisTicks: {
                            show: true,
                            borderType: 'solid',
                            color: PurposeStyle.colors.gray[300],
                            height: 6,
                            offsetX: 0,
                            offsetY: 0
                        }
                    },
                    fill: {
                        type: 'solid',
                        opacity: 1

                    },
                    grid: {
                        borderColor: PurposeStyle.colors.gray[300],
                        strokeDashArray: 5,
                    },
                    dataLabels: {
                        enabled: false
                    }
                }
                // Get data from data attributes
                var dataset = $this.data().dataset,
                    labels = $this.data().labels,
                    color = $this.data().color,
                    height = $this.data().height,
                    type = $this.data().type;
                options.chart.height = height ? height : 350;
                // Init chart
                var chart = new ApexCharts($this[0], options);
                // Draw chart
                setTimeout(function() {
                    chart.render();
                }, 300);
            }

            // Events
            if ($chart.length) {
                $chart.each(function() {
                    init($(this));
                });
            }
        })();
    </script>
@endpush
