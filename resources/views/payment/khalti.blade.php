<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
@php
    $store    = \App\Models\Store::where('slug', $data['slug'])->first();
    $slug = $data['slug'];
    $theme_name = !empty(env('DATA_INSERT_APP_THEME')) ? env('DATA_INSERT_APP_THEME') : APP_THEME();
    $khalti_public_key = \App\Models\Utility::GetValueByName('khalti_public_key',$theme_name);
    $CURRENCY_NAME = \App\Models\Utility::GetValueByName('CURRENCY_NAME',$theme_name);
@endphp
<script src="{{ asset('public/js/jquery-3.6.0.min.js') }}"></script>
<script src="{{ asset('public/js/custom.js') }}"></script>
<script src="https://khalti.s3.ap-south-1.amazonaws.com/KPG/dist/2020.12.17.0.0.0/khalti-checkout.iffe.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        var amount = {{$data['cartlist_final_price']}} * 100;
        var config = {
            "publicKey": "{{ isset($khalti_public_key) ? $khalti_public_key : '' }}",
            "productIdentity": "1234567890",
            "productName": "demo",
            "productUrl": "{{env('APP_URL')}}",
            "paymentPreference": [
                "KHALTI",
                "EBANKING",
                "MOBILE_BANKING",
                "CONNECT_IPS",
                "SCT",
            ],
            "eventHandler": {
                onSuccess (payload) {
                    if(payload.status==200) {
                        $.ajaxSetup({
                                headers: {
                                    'X-CSRF-Token': '{{csrf_token()}}'
                                }
                            });
                        $.ajax({
                            url: '{{ route('order.khalti',$slug) }}',
                            method: 'POST',
                            data : {
                                'payload' : payload,
                                'amount' : amount,
                            },
                            beforeSend: function () {

                            },
                            success: function(data) {
                                if(data.status_code === 200){
                                    window.location.href = data.store_complete;
                                    show_toastr('Success','Payment Done Successfully', 'success');
                                }
                                else{
                                    show_toastr('Error','Payment Failed', 'error');
                                }
                            },
                            error: function(err) {
                                console.log(err);
                                show_toastr('Error', err.response, 'error')
                            },
                        });
                    }
                },
                onError (error) {
                    show_toastr('Error', error, 'error')
                },
                onClose () {
                }
            }

        };

        var checkout = new KhaltiCheckout(config);

        checkout.show({ amount: amount });
    });
</script>
