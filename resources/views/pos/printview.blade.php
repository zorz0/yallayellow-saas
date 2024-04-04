@php
    $settings = App\Models\Utility::seting();
    session()->forget('pos_'.getCurrentStore());
    $store_id = \App\Models\Store::where('id', getCurrentStore())->first();
    $Tax =  \App\Models\Tax::where('store_id', getCurrentStore())->where('theme_id', APP_THEME())->first();
@endphp
<div class="pt-0 pb-3 modal-body pos-module" id="printarea" >
    <table class="table pos-module-tbl">
        <tbody>
            <div class="text-center ">
                <h3>{{ $details['user']['name'] }}</h3>
            </div>
            <div class="text-left">
                {!! $details['user']['details'] !!}
            </div>
            <br>

            <div class="text-left">
                <b>#{{ $details['pos_id'] }}</b>
            </div>

            <div class="invoice-to mt-2 product-border" >
                {!! isset($details['customer']['name']) ? '' : $details['customer']['details'] !!}
            </div><br>
            <div>
                {!! isset($details['customer']['name']) ? 'Name:  ' . (isset($customer_detail->name) ? $customer_detail->name : '') : '' !!}
            </div>
            <div>
                {!! isset($details['customer']['address']) ? 'Address:  ' . $details['customer']['address'] : '' !!}
            </div>
            <div>
                {!! isset($details['customer']['email']) ? 'Email:  ' . (isset($customer_detail->email) ? $customer_detail->email : '') : '' !!}
            </div>
            <div>
                {!! isset($details['customer']['phone_number']) ? 'Phone:  ' . (isset($customer_detail->phone) ? $customer_detail->phone : '') : '' !!}
            </div>
            <div>
                {!! isset($details['date']) ? 'Date of POS:  ' . $details['date'] : '' !!}
            </div>
            <div class="product-border">
                {!! isset($details['store']['details']) ? 'Store Name:  ' . $details['store']['details'] : '' !!}
            </div>
        </tbody>
    </table>
    <div class=" text-black text-left fs-5 mt-0 mb-0">{{__('Items')}}</div>
        @foreach ($sales['data'] as $key => $value)
            <div class="mt-2">
                <div class="p-0"> <b>{{ $value['name'] }}</b></div>
                <div class="d-flex product-border">
                    <div>{{ __('Quantity:') }}</div>
                    <div class="text-end ms-auto">{{ $value['quantity'] }}</div>
                </div>
            </div>
            <div class="d-flex product-border">
                <div>{{__('Price:')}}</div>
                <div class="text-end ms-auto">{{ $value['orignal_price'] }}</div>
            </div>
            @php
                $taxes = $value['tax'];
            @endphp
            <div class="d-flex product-border">
                <div>{{__('Tax:')}}</div>
                @if(!empty($value['tax']))
                    <div class="text-end ms-auto">
                        <span class='badge badge-primary'> {{ $value['tax_amount'] }}%</span><br>
                    </div>
                @else
                <div class="text-end ms-auto">-</div>
                @endif

            </div>
            <div class="d-flex product-border mb-2">
                <div>{{__('Tax Amount:')}}</div>
                <div class="text-end ms-auto">{{ $value['tax_amount'] }}</div>
            </div>
            <div class="d-flex product-border mb-2">
                <div>{{__('Sub Total:')}}</div>
                <div class="text-end ms-auto"> {{ $value['total_orignal_price'] }}</div>
            </div>
        @endforeach
        <div class="d-flex product-border mb-2 mt-4">
            <div><b>{{__('Discount:')}}</b></div>
            <div class="text-end ms-auto"> {{ $sales['discount'] }}</div>
        </div>
        <div class="d-flex product-border mb-2">
            <div><b>{{__('Total:')}}</b></div>
            <div class="text-end ms-auto"> {{ $sales['total'] }}</div>
        </div>

        <h5 class="text-center mt-3 font-label">{{__('Thank You For Shopping With Us. Please visit again.')}}</h5>
</div>

    <div class="justify-content-center pt-2 modal-footer">
        <a href="#" id="print"
            class="btn btn-primary btn-sm text-right float-right mb-3 ">
            {{ __('Print') }}
        </a>
    </div>

<script>
    $("#print").click(function () {
        var print_div = document.getElementById("printarea");
        $('.row').addClass('d-none');
        $('.toast').addClass('d-none');
        $('#print').addClass('d-none');
        window.print();
        $('.row').removeClass('d-none');
        $('#print').removeClass('d-none');
        $('.toast').removeClass('d-none');
    });
</script>




