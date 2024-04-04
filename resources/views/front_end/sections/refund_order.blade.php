@php
    $refund_order = App\Models\OrderRefund::RefundReason();
    $show_status = false;

@endphp
@if ($order_refunds && $order_refunds->order_id == $order['id'])
    @php
        $show_status = true;
        $productRefundIdData = json_decode($order_refunds['product_refund_id']);
        if (is_array($productRefundIdData)) {
            foreach ($productRefundIdData as $item) {
                $productRefundId = $item->product_refund_id;
                $returnPrice = $item->return_price;
                $quantity = $item->quantity;
            }
        }
        $order['coupon_info']['discount_amount'] = 0;
        $order_sum = $order['tax_price'] + $order_refunds->product_refund_price + $order['delivered_charge'];
        $final_price = $order_sum - $order['coupon_info']['discount_amount'];
    @endphp
@endif

<div class="row">
    <div class="col-sm-12">
        {{ Form::open(['route' => ['order.refund.request', $store->slug, $order['id']], 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
        <div class="row order-details-modal" id="printableArea">
            <div class="col-xxl-7">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <p class="mb-0"><b>{{ __('Items from Order') }} {{ $order['order_id'] }}</b></p>
                    </div>
                    <div class="card-body" id="carthtml">
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
                                        <th>{{ __('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($order['product'] as $id => $item)
                                        @php
                                            $download_prod = \App\Models\ProductVariant::where('id', $item['variant_id'])->first();
                                        @endphp
                                        <tr id="product-id-{{ $id }}">
                                            <td class="total">
                                                <span class="p text-sm"> <a href="#">{{ $item['name'] }}</a>
                                                </span> <br>
                                                <span class="text-sm"> {{ $item['variant_name'] }} </span>
                                            </td>
                                            <td>
                                                @if ($order['paymnet_type'] == 'POS')
                                                    {{ $item['quantity'] }}
                                                @else
                                                    <input type="hidden" name="order_id" value="{{ $order['id'] }}">
                                                    <span class="quantity buttons_added">
                                                        @if (!$show_status)
                                                            <input type="button" value="-" class="minus">
                                                            <input type="number" step="1" min="1"
                                                                max="{{ $item['qty'] }}" name="quantity[]"
                                                                title="{{ __('Quantity') }}"
                                                                class="input-number qtyyyy"
                                                                data-id="{{ $item['product_id'] }}"
                                                                data-url="{{ url($store->slug, 'change-refund-cart') }}"
                                                                data-order-id="{{ $order['id'] }}"
                                                                data-product-id="{{ $item['product_id'] }}"
                                                                size="4" value="{{ $item['qty'] }}"
                                                                style="width:50px;">
                                                            <input type="button" value="+" class="plus">
                                                        @else
                                                            {{ $quantity }}
                                                        @endif

                                                    </span>
                                                @endif
                                            </td>
                                            <input type="hidden" class="product_refund_price" name="return_price[]"
                                                value="@if ($order['paymnet_type'] == 'POS') {{ currency_format_with_sym( ($item['orignal_price'] ?? 0), getCurrentStore(), APP_THEME()) ?? SetNumberFormat($item['orignal_price']) }} @else {{ currency_format_with_sym( ($item['final_price'] ?? 0), getCurrentStore(), APP_THEME()) ?? SetNumberFormat($item['final_price']) }} @endif">
                                            @if (!$show_status)
                                                <td class="product_price">
                                                    @if ($order['paymnet_type'] == 'POS')
                                                        {{ currency_format_with_sym( ($item['orignal_price'] ?? 0), getCurrentStore(), APP_THEME()) ?? SetNumberFormat($item['orignal_price']) }}
                                                    @else
                                                        {{ currency_format_with_sym( ($item['final_price'] ?? 0), getCurrentStore(), APP_THEME()) ?? SetNumberFormat($item['final_price']) }}
                                                    @endif
                                                </td>
                                            @else
                                                <td> {{ $returnPrice }} </td>
                                            @endif
                                            @if ($order['order_status'] == 1 && $order['is_guest'] == 0)
                                                <td> - </td>
                                            @endif
                                            @if ($order['order_status_text'] == 'Delivered' && !empty($download_prod->downloadable_product))
                                        <tr>
                                            <td>
                                                <div class="detail-bottom">
                                                    <button
                                                        data-value="{{ asset($download_prod->downloadable_product) }}"
                                                        data-id="{{ $order['id'] }}"
                                                        class="btn cart-btn downloadable_prodcut">{{ __('Download') }}
                                                        <i class="fas fa-shopping-basket"></i>
                                                    </button>

                                                </div>
                                            </td>
                                            <td>
                                                <p>{{ __('Get your product from here') }}</p>
                                            </td>
                                        </tr>
                                    @endif
                                    <td>
                                        <div class="col-lg-8 col-12">
                                            <div class="checkbox-custom">
                                                <input type="checkbox" name="product_refund_id[]"
                                                    {{ $show_status ? 'checked' : '' }}
                                                    id="{{ $item['product_id'] }}" value="{{ $item['product_id'] }}">
                                                <label for="{{ $item['product_id'] }}">

                                                </label>
                                            </div>
                                        </div>
                                    </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xxl-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <p class="mb-0"><b>{{ __('Order Refund Reason') }}</b></p>
                                {!! Form::select('refund_reason', $refund_order, $order_refunds ? $order_refunds->refund_reason : null, [
                                    'class' => 'form-control',
                                    'id' => 'RefundReasonss',
                                ]) !!}
                            </div>

                            <div class="card-header d-flex justify-content-between OtherReason">
                                <p class="mb-0"><b>{{ __('Add Your Reason') }}</b></p>
                                {!! Form::text('custom_refund_reason', $order_refunds ? $order_refunds->custom_refund_reason : null, [
                                    'class' => 'form-control',
                                ]) !!}
                            </div>

                        </div>
                    </div>
                    @if ($RefundStatus['attachment'] && $RefundStatus['attachment'] == '1')
                        <div class="col-xxl-12">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between">
                                    <p class="mb-0">
                                        <b>{{ __('Attachments') }}</b>:<small>({{ __('You can select multiple files') }})</small>
                                    </p>

                                    <div class="input-group file-select-set mb-3">
                                        <input type="text" class="form-control p-2 rounded" readonly=""
                                            placeholder="Choose file" id="attachments">
                                        <input type="file"
                                            class="form-control file-opc {{ $errors->has('attachments') ? ' is-invalid' : '' }}"
                                            name="attachments[]" id="file" aria-label="Upload" multiple=""
                                            data-filename="multiple_file_selection"
                                            onchange="document.getElementById('blah').src = window.URL.createObjectURL(this.files[0])">
                                        <label class="input-group-text file-opc bg-primary" for="attachments"><i
                                                class="ti ti-circle-plus"></i>{{ __('Browse') }}</label>
                                        <img src="" id="blah" width="20%" />

                                    </div>
                                    <p class="multiple_file_selection mx-4"></p>
                                </div>
                            </div>
                        </div>
                    @endif
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
                                        <input type="hidden" name="product_sub_total" class="product_price_1"
                                            value="{{ $order['sub_total'] }}">
                                        <td class="product_price_1 CURRENCY">
                                            @if (!$show_status)
                                                <span class="">{{ currency_format_with_sym( ($order['sub_total'] ?? 0), $store->id, $store->theme_id) ?? SetNumberFormat($order['sub_total']) }}</span>
                                            @else
                                                {{ currency_format_with_sym( ($order_refunds->product_refund_price ?? 0), $store->id, $store->theme_id) ?? SetNumberFormat($order_refunds->product_refund_price) }}
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>{{ __('Estimated Tax') }} :</td>
                                        <td>
                                            {{ currency_format_with_sym( ($order['tax_price'] ?? 0), $store->id, $store->theme_id) ?? SetNumberFormat($order['tax_price']) }}
                                        </td>
                                    </tr>
                                    @if ($order['paymnet_type'] == 'POS')
                                        <tr>
                                            <td>{{ __('Discount') }} :</td>
                                            <td>{{ currency_format_with_sym( ($order['coupon_price'] ?? 0), $store->id, $store->theme_id) ?? (!empty($order['coupon_price']) ? SetNumberFormat($order['coupon_price']) : SetNumberFormat(0)) }}
                                            </td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td>{{ __('Apply Coupon') }} :</td>
                                            <td>{{ currency_format_with_sym( ($order['coupon_info']['discount_amount'] ?? 0), $store->id, $store->theme_id) ?? (!empty($order['coupon_info']['discount_amount']) ? SetNumberFormat($order['coupon_info']['discount_amount']) : SetNumberFormat(0)) }}
                                            </td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td>{{ __('Delivered Charges') }} :</td>
                                        <td>{{ currency_format_with_sym( ($order['delivered_charge'] ?? 0), $store->id, $store->theme_id) ?? SetNumberFormat($order['delivered_charge']) }}</td>
                                    </tr>
                                    <tr>
                                        <input type="hidden" name="grand_total" class="grand_total"
                                            value="{{ $order['final_price'] }}">
                                        <td>{{ __('Grand Total') }} :</td>
                                        <td class="grand_total CURRENCY">
                                            @if (!$show_status)
                                                <b>{{ currency_format_with_sym( ($order['final_price'] ?? 0), $store->id, $store->theme_id) ?? SetNumberFormat($order['final_price']) }}</b>
                                            @else
                                                <b>{{ currency_format_with_sym( ($final_price ?? 0), $store->id, $store->theme_id) ?? SetNumberFormat($final_price) }}</b>
                                            @endif
                                        </td>
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

        <div class="form-container">
            <div class="d-flex acc-back-btn-wrp align-items-center justify-content-end">

                @if (!$show_status)
                    <div class="checkbox-custom">
                        <input type="checkbox" id="agg">
                        <label for="agg">
                            <span>{{ __('I have read and agree to the') }}
                                @foreach ($pages as $page)
                                    @if ($page->page_slug == 'refund-policy')
                                        <a href="{{ route('custom.page', [$store->slug, $page->page_slug]) }}"
                                            target="_blank">{{ __('Refund') }} &amp;
                                            {{ __('Policy') }}.
                                        </a>
                                    @else
                                    @endif
                                @endforeach
                            </span>
                        </label>
                    </div>
                    <button type="submit" class="btn btn-sm btn-primary me-2 ">
                        {{ __('Refund Request') }}
                    </button>
                @endif
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>


<script>
    $(document).ready(function() {
        $("#RefundReasonss").trigger('change');
    });

    $(document).on('change', '#RefundReasonss', function(e) {
        var conceptName = $('#RefundReasonss').find(":selected").val();
        if (conceptName == "Other") {
            $('.OtherReason').addClass('d-block');
            $('.OtherReason').removeClass('d-none');
        } else {
            $('.OtherReason').removeClass('d-block');
            $('.OtherReason').addClass('d-none');
        }
    });

    $(document).on('change keyup', '#carthtml input[name="quantity[]"]', function(e) {
        e.preventDefault();
        var ele = $(this);
        var url = ele.data('url');
        var quantity = ele.val();
        var order_id = ele.data('order-id');
        var product_id = ele.data('product-id');

        var data = {
            order_id: order_id,
            quantity: quantity,
            product_id: product_id,
        }

        var closestTr = ele.closest('tr');

        $.ajax({
            url: url,
            method: 'GET',
            data: data,
            context: this,
            success: function(data) {

                $('.CURRENCY').html(data.CURRENCY);

                var productPrice = parseFloat(data.product_price.replace(/[^0-9.-]+/g, ""));
                closestTr.find('.product_price').text(data.product_price);
                closestTr.find('.product_refund_price').val(data.product_price);

                var totalSum = 0;
                $('#carthtml input[name="quantity[]"]').each(function() {
                    var rowPrice = parseFloat($(this).closest('tr').find('.product_price')
                        .text().replace(/[^0-9.-]+/g, ""));
                    totalSum += rowPrice;
                });

                var taxPrice = parseFloat(data.tax_price.replace(/[^0-9.-]+/g, ""));
                var deliveredCharge = parseFloat(data.delivered_charge.replace(/[^0-9.-]+/g, ""));
                var discountPrice = parseFloat(data.discount_price.replace(/[^0-9.-]+/g, ""));
                var sum = taxPrice + deliveredCharge + totalSum - discountPrice;

                var totalSum = data.CURRENCY + totalSum.toFixed(2);

                $('.product_price_1').html(totalSum);
                $('.product_price_1').val(totalSum);
                $('.grand_total').html('<b>' + data.CURRENCY + sum.toFixed(2) + '</b>');
                $('.grand_total').val(data.CURRENCY + sum.toFixed(2));
            }
        });
    });
</script>
