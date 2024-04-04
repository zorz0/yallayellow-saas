<div class="col-sm-12">
    <div class="row">
        <div class="col-xxl-4 col-12 mb-4">
            <div class="card">
                <div class="card-header">
                <div class="float-end">
                    <a href="#" data-bs-toggle="tooltip" data-bs-placement="top" title="Refferals"><i
                            class=""></i></a>
                </div>
                <h5>{{$totaluser}} {{ __('orders')}}</h5>
                </div>
                <div class="card-body"  style="min-height: 280px;">
                        <div class="row align-items-center">
                        <div class="col-12">
                            <div class="chart"></div>
                        </div>

                        </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-8">
            <div class="card">
                <div class="card-header">
                    <h5>{{ __('Customer order Vs Guest order') }}</h5>
                </div>
                <div class="card-body">
                    <div class="myChart" data-color="primary" data-height="230"></div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="col-xxl-12">
    {{-- orders chart --}}
    <div class="card min-h-390 overflow-auto">
        <div class="card-header">
            <h5>{{ __('Customer Vs Guest')}}</h5>
        </div>
        <div class="card-body">
            <div class="traffic-chart"></div>
        </div>
    </div>

</div>

<script src="{{ asset('public/assets/js/plugins/apexcharts.min.js') }}"></script>







