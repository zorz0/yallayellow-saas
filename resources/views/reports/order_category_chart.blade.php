<div class="col-xxl-12">
    <div class="row">
        <div class="col-12">
            <div class="row gy-3 order-report-row order-report-category " >
                @foreach ($NetSalesofcategory as $category => $saleAmount)
                    <div class="col-xl-3 col-lg-4 col-sm-12 col-12">
                        <div class="card">
                            <div class="card-header d-flex flex-column justify-content-center align-items-center">
                                <span class="mb-0 text-center mb-1">{{ __('Sales in  ') }}{{ $category }}</span>
                                <h2 class="mb-0 text-success">{{ $currency }}{{ number_format($saleAmount, 2) }}
                                    {{-- <small class="report-text-label" style="font-size: 10px;">{{__(' Net sale')}}</small> --}}
                                </h2>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="col-xxl-12">
        <div class="card min-h-390 overflow-auto">
            <div class="card-header">
                <h5>{{ __(' Category Sales Summary') }}</h5>
            </div>
            <div class="card-body">
                <div class="traffic-chart"></div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('public/assets/js/plugins/apexcharts.min.js') }}"></script>
