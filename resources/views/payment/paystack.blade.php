<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
@php
    $p_data = is_string($data['billing_info']) ? json_decode($data['billing_info']) : $data['billing_info'];
    $price = $data['cartlist_final_price'];
    $theme_name = !empty(env('DATA_INSERT_APP_THEME')) ? env('DATA_INSERT_APP_THEME') : APP_THEME();
    $paystack_public_key = \App\Models\Utility::GetValueByName('paystack_public_key',$theme_name);
    $CURRENCY_NAME = \App\Models\Utility::GetValueByName('CURRENCY_NAME',$theme_name);
@endphp
{{-- <button type="submit" onclick="payWithPaystack()" class="btn">{{__('Pay Now')}}</button> --}}
<script src="{{ asset('public/js/jquery-3.6.0.min.js') }}"></script>
<script src="https://js.paystack.co/v1/inline.js"></script>
<script src="https://checkout.paystack.com/service-worker.js"></script>
{{-- PAYSTACK JAVASCRIPT FUNCTION --}}

<script>
    $(document).ready(function() {
        var slug = '{{ $store->slug }}';
        var order_id = '{{ $order_id = time() }}';
        var urls = "{{ route('store.payment.stripe', $store->slug) }}";
        var handler = PaystackPop.setup({
            key: '{{ $paystack_public_key }}',
            email: '{{ $p_data['email'] ?? ($p_data->email ?? '') }}',
            amount: '{{ $price * 100 }} ',
            currency: '{{  $CURRENCY_NAME }}',
            ref: 'pay_ref_id' + Math.floor((Math.random() * 1000000000) +
                1
            ), // generates a pseudo-unique reference. Please replace with a reference you generated. Or remove the line entirely so our API will generate one for you
            metadata: {
                custom_fields: [{
                    display_name: "Mobile Number",
                    variable_name: "mobile_number",
                    value: "2345544657"
                }]
            },

            callback: function(response) {

                var paystack_callback = urls;

                window.location.href = paystack_callback;
            },
            onClose: function() {
                alert('window closed');
            }
        });
        handler.openIframe();
    })
</script>
