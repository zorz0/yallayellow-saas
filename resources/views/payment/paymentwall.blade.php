<head>
  <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
@php
        $store    = \App\Models\Store::where('slug', $data['slug'])->first();
        $slug = $data['slug'];
        $theme_name = !empty(env('DATA_INSERT_APP_THEME')) ? env('DATA_INSERT_APP_THEME') : APP_THEME();
        $paymentwall_public_key = \App\Models\Utility::GetValueByName('paymentwall_public_key',$theme_name);
        $CURRENCY_NAME = \App\Models\Utility::GetValueByName('CURRENCY_NAME',$theme_name);

@endphp

{{-- {{ dd( $admin_payment_setting) }} --}}
<script src="https://api.paymentwall.com/brick/build/brick-default.1.5.0.min.js"> </script>
<div id="payment-form-container"> </div>
<script>
    var urls = "{{ route('store.payment.stripe', $store->slug) }}";
    var brick = new Brick({
    public_key: '{{ $paymentwall_public_key }}', // please update it to Brick live key before launch your project
    amount: '{{$data['cartlist_final_price']}}' ,
    currency: '{{$CURRENCY_NAME}}',
    container: 'payment-form-container',
    action: '{{url("order.pay.with.paymentwall",$slug)}}',
    form: {
        merchant: 'Paymentwall',
        product: '{{$store->name}}',
        pay_button: 'Pay',
        show_zip: true, // show zip code
        show_cardholder: true // show card holder name
    }
    });
    brick.showPaymentForm(function(data) {
        if(data.flag == 1){
        window.location.href =urls;
        }else{
        window.location.href =urls;
        }
    }, function(errors) {
        if(errors.flag == 1){
            window.location.href =urls;
        }else{
            window.location.href =urls;
        }
    });
</script>
