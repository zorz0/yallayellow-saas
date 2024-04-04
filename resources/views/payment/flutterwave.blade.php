@php
$store = $res_data["store"];
@endphp 
<script src="{{ asset('public/js/jquery-3.6.0.min.js') }}"></script>

        <script src="https://api.ravepay.co/flwv3-pug/getpaidx/api/flwpbf-inline.js"></script>
        <script src="{{ asset('public/js/jquery.form.js') }}"></script>
        <script>
            $( document ).ready(function () {
                var coupon_id = '';
                var API_publicKey = 'FLWPUBK_TEST-05113ace39b840b31bcd365f532858ca-X';
                var nowTim = "{{ date('d-m-Y-h-i-a') }}";
                var order_id = '{{$order_id = time()}}';
                var slug = '{{$store}}';
                var urls = "{{ route('store.payment.stripe', $store) }}";
                var flutter_callback = "{{ url('store-payment-flutterwave') }}";
                var currency = '{{$res_data['currency']}}';
                var x = getpaidSetup({
                PBFPubKey: API_publicKey,
                customer_email: '{{$res_data['email']}}',
                amount: '{{$res_data['total_price']}}',
                currency: currency,
                

                txref: nowTim + '__' + Math.floor((Math.random() * 1000000000)) +
                    'fluttpay_online-' +
                    {{ date('Y-m-d') }},
                meta: [{
                    metaname: "payment_id",
                    metavalue: "id"
                }],
                onclose: function() {},
                callback: function(response) {
                    var txref = response.tx.txRef;
                    if (
                    response.tx.chargeResponseCode == "00" ||
                    response.tx.chargeResponseCode == "0"
                    ) {
                        window.location.href =  urls;

                    } else {
                    // redirect to a failure page.
                    }
                    x
                    .close(); // use this to close the modal immediately after payment.
                }
                });
});
 </script>
