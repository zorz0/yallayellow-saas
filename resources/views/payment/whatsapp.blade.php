<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
@php
    $store    = \App\Models\Store::where('slug', $data['slug'])->first();
    $slug = $data['slug'];
    $theme_name = !empty(env('DATA_INSERT_APP_THEME')) ? env('DATA_INSERT_APP_THEME') : APP_THEME();
    $paymentwall_public_key = \App\Models\Utility::GetValueByName('paymentwall_public_key',$theme_name);
    $CURRENCY_NAME = \App\Models\Utility::GetValueByName('CURRENCY_NAME',$theme_name);
    if (!\Auth::guard('customers')->user())
    {
        $encode_product = json_encode($data['product']);
    }
    else{

        $encode_product = json_encode($data['cartlist']['product_list']);
    }
    $wts_number = $data['wts_number'];

@endphp
<input type="hidden" id="return_url">
<script src="{{ asset('public/js/jquery-3.6.0.min.js') }}"></script>
<script src="{{ asset('public/js/jquery.form.js') }}"></script>
<script src="{{ asset('assets/js/plugins/notifier.js') }}"></script>
<script src="{{ asset('js/custom.js') }}" defer="defer"></script>
<script>
    $( document ).ready(function () {
            var product_array = '{{$encode_product}}';
            var product = JSON.parse(product_array.replace(/&quot;/g, '"'));
            var order_id = '{{$order_id = time()}}';
            var wts_number = '{{$wts_number}}';
            var total_price = $('#Subtotal .total_price').attr('data-value');
            var coupon_id = $('.hidden_coupon').attr('data_id');
            var dicount_price = $('.dicount_price').html();

            var data = {
                type: 'whatsapp',
                product: product,
                order_id: order_id,
                coupon_id: coupon_id,
                dicount_price: dicount_price,
                total_price: total_price,
                wts_number: wts_number
            }

            getWhatsappUrl(dicount_price, total_price, coupon_id, data);

            $.ajax({
                url: '{{ route('user.whatsapp',$store->slug) }}',
                method: 'POST',
                data: data,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    if (data.status == 'success') {

                        setTimeout(function () {

                        var get_url_msg_url = $('#return_url').val();

                        var append_href = get_url_msg_url;
                        window.open(append_href, '_blank');
                        }, 100);

                        setTimeout(function () {

                            var queryParams = {
                                data: data.data
                            };
                            var queryString = $.param(queryParams);

                            var url = '{{ route('order.summary', [$store->slug]) }}?' + queryString;
                            url = url.replace(':id', data.order_id);

                            window.location.href = url;
                        }, 1000);

                    } else {
                        $('#owner-whatsapp').prop('disabled',false);
                        show_toastr("Error", data.success, data["status"]);
                    }

                }
            });


        });


        //for create/get Whatsapp Url
        function getWhatsappUrl(coupon = '', finalprice = '', coupon_id = '', data = '') {
            $.ajax({
                url: '{{ route('get.whatsappurl',$store->slug) }}',
                method: 'post',
                data: {dicount_price: coupon, finalprice: finalprice, coupon_id: coupon_id, data: data},
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    console.log(data);
                    $('#return_url').attr('value', data);
                    $('#return_url').val(data);
                }
            });
        }

</script>
