<div class="row">
    <div class="col-sm-12">
        <div class="row order-details-modal" id="printableArea">
            <div class="col-xxl-7">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <p class="mb-0"><b>{{ __('Items from Order') }} {{ $order['order_id'] }}</b></p>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>{{ __('Item') }}</th>
                                        <th>{{ __('Quantity') }}</th>
                                        <th>{{ __('Total') }}</th>
                                        @if ($order['order_status'] == 1 && $order['is_guest'] == 0)
                                            <th>{{ __('Return') }}</th>
                                        @endif
                                        @if ($order['order_status'] == 1)
                                            <th>{{ __('Downloadable Product') }}</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($order['product'] as $item)
                                        @php
                                            $download_prod = \App\Models\ProductVariant::where('id', $item['variant_id'])->first();
                                            $download_product = \App\Models\Product::where('id', $item['product_id'])->first();
                                        @endphp
                                        <tr>
                                            <td class="total">
                                                <span class="p text-sm"> <a href="#">{{ $item['name'] }}</a>
                                                </span> <br>
                                                <span class="text-sm"> {{ $item['variant_name'] }} </span>
                                            </td>
                                            <td>
                                                @if ($order['paymnet_type'] == 'POS')
                                                    {{ $item['quantity'] }}
                                                @else
                                                    {{ $item['qty'] }}
                                                @endif
                                            </td>
                                            <td>
                                                @if ($order['paymnet_type'] == 'POS')
                                                    {{ currency_format_with_sym( ($item['orignal_price'] ?? 0), getCurrentStore(), APP_THEME()) ?? SetNumberFormat($item['orignal_price']) }}
                                                @else
                                                    {{ currency_format_with_sym( ($item['final_price'] ?? 0), getCurrentStore(), APP_THEME()) ?? SetNumberFormat($item['final_price']) }}
                                                @endif
                                            </td>
                                            @if ($order['order_status'] == 1 && $order['is_guest'] == 0)
                                                <td> - </td>
                                            @endif
                                            @if ($order['order_status_text'] == 'Delivered')
                                                @if (!empty($download_prod->downloadable_product) || !empty($download_product->downloadable_product))
                                                    <td>
                                                        <div class="detail-bottom">
                                                            @if (!empty($download_product->downloadable_product))
                                                                <a class="download_prod_{{ $item['product_id'] }}"
                                                                    href="{{ get_file($download_product->downloadable_product) }} "
                                                                    download></a>
                                                            @endif
                                                            @if (!empty($download_prod->downloadable_product))
                                                                <a class="download_prod_{{ $item['product_id'] }}"
                                                                    href="{{ get_file($download_prod->downloadable_product) }}"
                                                                    download></a>
                                                            @endif
                                                            <button data-id="{{ $order['id'] }}"
                                                                class="btn cart-btn downloadable_prodcut_variant_{{ $item['product_id'] }}">{{ __('Download') }}
                                                                <i class="fas fa-shopping-basket"></i>
                                                            </button>

                                                        </div>
                                                    </td>
                                                @endif
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 col-sm-12 col-lg-6 ">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <b class="">{{ __('Shipping Information') }}</b>
                            </div>
                            <div class="card-body pt-0">
                                <address class="mb-0 text-sm">
                                    <dl class="row mt-4 align-items-center">
                                        <small class="col-sm-5 col-5 text-sm"><b>{{ __('Name') }}</b></small>
                                        <small class="col-sm-7 col-7 text-sm">
                                            {{ !empty($order['delivery_informations']['name']) ? $order['delivery_informations']['name'] : '' }}</small>
                                        <small class="col-sm-5 col-5 text-sm"><b>{{ __('Email') }}</b></small>
                                        <small class="col-sm-7 col-7 text-sm">
                                            {{ !empty($order['delivery_informations']['email']) ? $order['delivery_informations']['email'] : '' }}</small>
                                        <small class="col-sm-5 col-5 text-sm"><b>{{ __('City') }}</b></small>
                                        <small
                                            class="col-sm-7 col-7 text-sm">{{ !empty($order['delivery_informations']['city']) ? $order['delivery_informations']['city'] : '' }}</small>
                                        <small class="col-sm-5 col-5 text-sm"><b>{{ __('State') }}</b></small>
                                        <small
                                            class="col-sm-7 col-7 text-sm">{{ !empty($order['delivery_informations']['state']) ? $order['delivery_informations']['state'] : '' }}</small>
                                        <small class="col-sm-5 col-5 text-sm"><b>{{ __('Country') }}</b></small>
                                        <small
                                            class="col-sm-7 col-7 text-sm">{{ !empty($order['delivery_informations']['country']) ? $order['delivery_informations']['country'] : '' }}</small>
                                        <small class="col-sm-5 col-5 text-sm"><b>{{ __('Postal Code') }}</b></small>
                                        <small
                                            class="col-sm-7 col-7 text-sm">{{ !empty($order['delivery_informations']['post_code']) ? $order['delivery_informations']['post_code'] : '' }}</small>
                                        <small class="col-sm-5 col-5 text-sm"><b>{{ __('Phone') }} </b></small>
                                        <small class="col-sm-7 col-7 text-sm">
                                            <a href="https://api.whatsapp.com/send?phone={{ !empty($order['delivery_informations']['phone']) ? $order['delivery_informations']['phone'] : '' }}&amp;text=Hi"
                                                target="_blank">
                                                {{ !empty($order['delivery_informations']['phone']) ? $order['delivery_informations']['phone'] : '' }}
                                            </a>
                                        </small>
                                    </dl>
                                </address>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12 col-lg-6 ">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <b class="">{{ __('Billing Information') }}</b>
                            </div>
                            <div class="card-body pt-0">
                                <address class="mb-0 text-sm">
                                    <dl class="row mt-4 align-items-center">
                                        <small class="col-sm-5 col-5 text-sm"><b>{{ __('Name') }}</b></small>
                                        <small class="col-sm-7 col-7 text-sm pb-2">
                                            {{ !empty($order['billing_informations']['name']) ? $order['billing_informations']['name'] : '' }}</small>
                                        <small class="col-sm-5 col-5 text-sm"><b>{{ __('Email') }}</b></small>
                                        <small class="col-sm-7 col-7 text-sm">
                                            {{ !empty($order['billing_informations']['email']) ? $order['billing_informations']['email'] : '' }}</small>
                                        <small class="col-sm-5 col-5 text-sm"><b>{{ __('City') }}</b></small>
                                        <small
                                            class="col-sm-7 col-7 text-sm">{{ !empty($order['billing_informations']['city']) ? $order['billing_informations']['city'] : '' }}</small>
                                        <small class="col-sm-5 col-5 text-sm"><b>{{ __('State') }}</b></small>
                                        <small
                                            class="col-sm-7 col-7 text-sm">{{ !empty($order['billing_informations']['state']) ? $order['billing_informations']['state'] : '' }}</small>
                                        <small class="col-sm-5 col-5 text-sm"><b>{{ __('Country') }}</b></small>
                                        <small
                                            class="col-sm-7 col-7 text-sm">{{ !empty($order['billing_informations']['country']) ? $order['billing_informations']['country'] : '' }}</small>
                                        <small class="col-sm-5 col-5 text-sm"><b>{{ __('Postal Code') }}</b></small>
                                        <small
                                            class="col-sm-7 col-7 text-sm">{{ !empty($order['billing_informations']['post_code']) ? $order['billing_informations']['post_code'] : '' }}</small>
                                        <small class="col-sm-5 col-5 text-sm"><b>{{ __('Phone') }}</b></small>
                                        <small class="col-sm-7 col-7 text-sm">
                                            <a href="https://api.whatsapp.com/send?phone={{ !empty($order['billing_informations']['phone']) ? $order['billing_informations']['phone'] : '' }}&amp;text=Hi"
                                                target="_blank">
                                                {{ !empty($order['billing_informations']['phone']) ? $order['billing_informations']['phone'] : '' }}
                                            </a>
                                        </small>
                                    </dl>
                                </address>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-5">
                <div class="card  p-0">
                    <div class="card-header d-flex justify-content-between pb-0">
                        <b class="mb-4">{{ __('Extra Information') }}</b>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <td>{{ __('Sub Total') }} :</td>
                                        <td>{{ currency_format_with_sym( ($order['sub_total'] ?? 0), getCurrentStore(), APP_THEME()) ?? SetNumberFormat($order['sub_total']) }}</td>
                                    </tr>
                                    <tr>
                                        <td>{{ __('Estimated Tax') }} :</td>
                                        <td>
                                            @if ($order['paymnet_type'] == 'POS')
                                                {{ currency_format_with_sym( ($order['tax_price'] ?? 0), getCurrentStore(), APP_THEME()) ?? SetNumberFormat($order['tax_price']) }}
                                            @else
                                                {{ currency_format_with_sym( ($order['tax_price'] ?? 0), getCurrentStore(), APP_THEME()) ?? SetNumberFormat($order['tax_price']) }}
                                            @endif
                                        </td>
                                    </tr>
                                    @if ($order['paymnet_type'] == 'POS')
                                        <tr>
                                            <td>{{ __('Discount') }} :</td>
                                            <td>{{ currency_format_with_sym( ($order['coupon_price'] ?? 0), getCurrentStore(), APP_THEME()) ?? (!empty($order['coupon_price']) ? SetNumberFormat($order['coupon_price']) : SetNumberFormat(0)) }}
                                            </td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td>{{ __('Apply Coupon') }} :</td>
                                            <td>{{ currency_format_with_sym( ($order['discount_amount'] ?? 0), getCurrentStore(), APP_THEME()) ?? (!empty($order['coupon_info']['discount_amount']) ? SetNumberFormat($order['coupon_info']['discount_amount']) : SetNumberFormat(0)) }}
                                            </td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td>{{ __('Delivered Charges') }} :</td>
                                        <td>{{ currency_format_with_sym( ($order['delivered_charge'] ?? 0), getCurrentStore(), APP_THEME()) ?? SetNumberFormat($order['delivered_charge']) }}</td>
                                    </tr>
                                    <tr>
                                        <td>{{ __('Grand Total') }} :</td>
                                        <td><b>{{ currency_format_with_sym( ($order['final_price'] ?? 0), getCurrentStore(), APP_THEME()) ?? SetNumberFormat($order['final_price']) }}</b></td>
                                    </tr>
                                    <tr>
                                        <td>{{ __('Payment Type') }} :</td>
                                        <td> {{ $order['paymnet_type'] }} </td>
                                    </tr>
                                    <tr>
                                        <td>{{ __('Order Status') }} :</td>
                                        <td>{{ $order['order_status_text'] }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).on('click', '.downloadable_prodcut', function() {

        var download_product = $(this).attr('data-value');
        var order_id = $(this).attr('data-id');
        console.log(order_id, download_product);
        var data = {
            download_product: download_product,
            order_id: order_id,
        }

        $.ajax({
            url: '{{ route('user.downloadable_prodcut', $store->slug) }}',
            method: 'POST',
            data: data,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(data) {
                if (data.status == 'success') {
                    show_toastr("success", data.message + '<br> <b>' + data.msg + '<b>', data[
                        "status"]);
                    $('.downloadab_msg').html('<span class="text-success">' + data.msg + '</sapn>');
                } else {
                    show_toastr("Error", data.message + '<br> <b>' + data.msg + '<b>', data[
                        "status"]);
                }
            }
        });
    });

    const numbers = {!! json_encode($order['product']) !!};;
    numbers.forEach(myFunction)

    function myFunction(item, index, arr) {
        const fileLinks = document.querySelectorAll('.download_prod_' + item.product_id);
        const downloadButton = document.querySelector('.downloadable_prodcut_variant_' + item.product_id);
        downloadButton.addEventListener('click', function() {
            fileLinks.forEach(link => {
                link.click();
            });
        });
    }
</script>
