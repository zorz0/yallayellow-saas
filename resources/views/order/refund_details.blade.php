@extends('layouts.app')

@section('page-title', __('Order Refund Request'))

@section('action-button')
@endsection

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('refund-request.index') }}">{{ __('Order Refund Request') }}</a></li>
<li class="breadcrumb-item">{{ $order['order_id'] }}</li>
@endsection

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="row" id="printableArea">
            <div class="col-xxl-7">
                <div class="card">
                    <div class="row">
                        <div class="col-md-6 col-sm-12 col-lg-6 ">
                            <div class="">
                                <div class="p-4 d-flex gap-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 36 36" fill="none">
                                        <circle cx="18" cy="18" r="18" fill="#E8F8FF"/>
                                        <path d="M16.98 28.055C17.0959 28.2289 17.291 28.3333 17.5 28.3333C17.709 28.3333 17.9041 28.2289 18.02 28.055C19.4992 25.8364 21.6778 23.0963 23.196 20.3096C24.4099 18.0815 25 16.1811 25 14.5C25 10.3645 21.6355 7 17.5 7C13.3645 7 10 10.3645 10 14.5C10 16.1811 10.5901 18.0815 11.804 20.3096C13.3211 23.0942 15.5039 25.841 16.98 28.055ZM17.5 8.25C20.9462 8.25 23.75 11.0538 23.75 14.5C23.75 15.9668 23.2097 17.6716 22.0983 19.7116C20.7897 22.1137 18.9222 24.5503 17.5 26.5987C16.078 24.5506 14.2104 22.1138 12.9017 19.7116C11.7903 17.6716 11.25 15.9668 11.25 14.5C11.25 11.0538 14.0538 8.25 17.5 8.25Z" fill="#002332"/>
                                        <path d="M17.5 18.25C19.5677 18.25 21.25 16.5677 21.25 14.5C21.25 12.4323 19.5677 10.75 17.5 10.75C15.4323 10.75 13.75 12.4323 13.75 14.5C13.75 16.5677 15.4323 18.25 17.5 18.25ZM17.5 12C18.8785 12 20 13.1215 20 14.5C20 15.8785 18.8785 17 17.5 17C16.1215 17 15 15.8785 15 14.5C15 13.1215 16.1215 12 17.5 12Z" fill="#002332"/>
                                      </svg>
                                    <h5 class="d-flex align-items-center mb-0">{{ __('Shipping Information') }}</h5>
                                </div>
                                <div class="card-body pt-0">
                                    <address class="mb-0 text-sm">
                                        <dl class="row mt-4 align-items-center">
                                            <dt class="col-sm-3 h6 text-sm">{{ __('Name') }}</dt>
                                            <dd class="col-sm-9 text-sm"> {{ !empty($order['delivery_informations']['name']) ? $order['delivery_informations']['name'] : '' }}</dd>
                                            <dt class="col-sm-3 h6 text-sm">{{ __('Email') }}</dt>
                                            <dd class="col-sm-9 text-sm"> {{ !empty($order['delivery_informations']['email']) ? $order['delivery_informations']['email'] : '' }}</dd>
                                            <dt class="col-sm-3 h6 text-sm">{{ __('City') }}</dt>
                                            <dd class="col-sm-9 text-sm">{{ !empty($order['delivery_informations']['city']) ? $order['delivery_informations']['city'] : '' }}</dd>
                                            <dt class="col-sm-3 h6 text-sm">{{ __('State') }}</dt>
                                            <dd class="col-sm-9 text-sm">{{ !empty($order['delivery_informations']['state']) ? $order['delivery_informations']['state'] : '' }}</dd>
                                            <dt class="col-sm-3 h6 text-sm">{{ __('Country') }}</dt>
                                            <dd class="col-sm-9 text-sm">{{ !empty($order['delivery_informations']['country']) ? $order['delivery_informations']['country'] : '' }}</dd>
                                            <dt class="col-sm-3 h6 text-sm">{{ __('Postal Code') }}</dt>
                                            <dd class="col-sm-9 text-sm">{{ !empty($order['delivery_informations']['post_code']) ? $order['delivery_informations']['post_code'] : '' }}</dd>
                                            <dt class="col-sm-3 h6 text-sm">{{ __('Phone') }}</dt>
                                            <dd class="col-sm-9 text-sm">
                                                <a href="https://api.whatsapp.com/send?phone={{ !empty($order['delivery_informations']['phone']) ? $order['delivery_informations']['phone'] : '' }}&amp;text=Hi"
                                                    target="_blank">
                                                    {{ !empty($order['delivery_informations']['phone']) ? $order['delivery_informations']['phone'] : '' }}
                                                </a>
                                            </dd>
                                        </dl>
                                    </address>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-sm-12 col-lg-6 ">
                            <div class="">
                                <div class="p-4 d-flex gap-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 36 36" fill="none">
                                        <circle cx="18" cy="18" r="18" fill="#FFAEBD" fill-opacity="0.31"/>
                                        <path d="M27.1475 22.2505V22.2605C27.1421 22.9341 26.5923 23.4806 25.9175 23.4806H10.0834C9.40525 23.4806 8.85336 22.9287 8.85336 22.2505V13.0835C8.85336 12.4053 9.40525 11.8534 10.0834 11.8534H25.9175C26.5956 11.8534 27.1475 12.4053 27.1475 13.0835V22.2505ZM25.9175 10.98H10.0834C8.924 10.98 7.98 11.924 7.98 13.0835V22.2506C7.98 23.41 8.924 24.354 10.0834 24.354H25.9175C27.0769 24.354 28.0209 23.41 28.0209 22.2506V13.0835C28.0209 11.924 27.0769 10.98 25.9175 10.98Z" fill="#D80027" stroke="#D80027" stroke-width="0.04"/>
                                        <path d="M27.1475 15.9801H8.85336V14.3534H27.1475V15.9801ZM27.5842 13.48H8.4167C8.17564 13.48 7.98 13.6756 7.98 13.9167V16.4168C7.98 16.6579 8.17564 16.8535 8.4167 16.8535H27.5843C27.8253 16.8535 28.021 16.6579 28.021 16.4168V13.9167C28.0209 13.6756 27.8253 13.48 27.5842 13.48Z" fill="#D80027" stroke="#D80027" stroke-width="0.04"/>
                                        <path d="M14.2503 19.314H10.9168C10.6757 19.314 10.4801 19.5096 10.4801 19.7507C10.4801 19.9917 10.6757 20.1873 10.9168 20.1873H14.2503C14.4913 20.1873 14.687 19.9917 14.687 19.7506C14.687 19.5096 14.4913 19.314 14.2503 19.314Z" fill="#D80027" stroke="#D80027" stroke-width="0.04"/>
                                        <path d="M16.7504 20.98H10.9168C10.6757 20.98 10.4801 21.1756 10.4801 21.4167C10.4801 21.6578 10.6757 21.8534 10.9168 21.8534H16.7504C16.9914 21.8534 17.1871 21.6578 17.1871 21.4167C17.1871 21.1756 16.9914 20.98 16.7504 20.98Z" fill="#D80027" stroke="#D80027" stroke-width="0.04"/>
                                      </svg>
                                    <h5 class="d-flex align-items-center mb-0">{{ __('Billing Information') }}</h5>
                                </div>
                                <div class="card-body pt-0">
                                    <dl class="row mt-4 align-items-center">
                                        <dt class="col-sm-3 h6 text-sm">{{ __('Name') }}</dt>
                                        <dd class="col-sm-9 text-sm"> {{ !empty($order['billing_informations']['name']) ? $order['billing_informations']['name'] : '' }}</dd>
                                        <dt class="col-sm-3 h6 text-sm">{{ __('Email') }}</dt>
                                        <dd class="col-sm-9 text-sm"> {{ !empty($order['billing_informations']['email']) ? $order['billing_informations']['email'] : '' }}</dd>
                                        <dt class="col-sm-3 h6 text-sm">{{ __('City') }}</dt>
                                        <dd class="col-sm-9 text-sm">{{ !empty($order['billing_informations']['city']) ? $order['billing_informations']['city'] : '' }}</dd>
                                        <dt class="col-sm-3 h6 text-sm">{{ __('State') }}</dt>
                                        <dd class="col-sm-9 text-sm">{{ !empty($order['billing_informations']['state']) ? $order['billing_informations']['state'] : '' }}</dd>
                                        <dt class="col-sm-3 h6 text-sm">{{ __('Country') }}</dt>
                                        <dd class="col-sm-9 text-sm">{{ !empty($order['billing_informations']['country']) ? $order['billing_informations']['country'] : '' }}</dd>
                                        <dt class="col-sm-3 h6 text-sm">{{ __('Postal Code') }}</dt>
                                        <dd class="col-sm-9 text-sm">{{ !empty($order['billing_informations']['post_code']) ? $order['billing_informations']['post_code'] : '' }}</dd>
                                        <dt class="col-sm-3 h6 text-sm">{{ __('Phone') }}</dt>
                                        <dd class="col-sm-9 text-sm">
                                            <a href="https://api.whatsapp.com/send?phone={{ !empty($order['billing_informations']['phone']) ? $order['billing_informations']['phone'] : '' }}&amp;text=Hi"
                                                target="_blank">
                                                {{ !empty($order['billing_informations']['phone']) ? $order['billing_informations']['phone'] : '' }}
                                            </a>
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h5 class="mb-0">{{ __('Items from Order') }} {{ $order['order_id'] }} </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>{{ __('Item') }}</th>
                                        <th>{{ __('Quantity') }}</th>
                                        <th>{{ __('Total') }}</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($productRefundData as $productRefundId => $data)
                                    <tr>
                                        <td class="total">
                                            <span class="h6 text-sm"> <a href="#">{{ $data['product_name'] }}</a> </span> <br>
                                        </td>
                                        <td>
                                            <input type="hidden" value="{{ $data["quantity"] }}" class="product_refund_qty">
                                            @if ($order['paymnet_type'] == 'POS')
                                                {{ $data["quantity"] }}
                                            @else
                                                {{ $data["quantity"] }}
                                            @endif
                                        </td>
                                        <td>
                                            <input type="hidden" value="{{ $data['return_price'] }}" class="product_refund_price">
                                            @if ($order['paymnet_type'] == 'POS')
                                                {{ $data["return_price"] }}
                                            @else
                                                {{ $data["return_price"] }}
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td>  </td>
                                        <td>
                                            @if ($RefundStatus['shiping_deduct'] ?? '' && $RefundStatus['shiping_deduct'] == '1')
                                                @if($plan->shipping_method == 'on')
                                                    <div class="col-md-4">
                                                        <label for=""><b>{{ __('Refund Shipping Amount')}} : </b></label>
                                                        <input type="hidden" name="trending" value="0">
                                                        {!! Form::checkbox("trending", 1, null, ["class" => "form-check-input input-primary check-shipp", "id"=>"customCheckdef1trending"]) !!}
                                                        <label class="form-check-label" for="customCheckdef1trending"></label>
                                                    </div>
                                                @endif
                                            @endif
                                        </td>
                                        <input type="hidden" value="{{ $refund_requests->product_refund_price }}" name="refunded_amount" class="refunded_amount">
                                        <td class="refunded_amount"><b>{{ $refund_requests->product_refund_price }}</b></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-5">
                <div class="card  p-4">
                    <div class="card-header p-0 d-flex justify-content-between pb-0 border-0">
                        <h5 class="mb-4">{{ __('Order Refund Information') }}</h5>
                    </div>
                    <div class="card-body p-3 rounded" style="background: #f5f5f5">
                        <div class="">
                            <div class="">
                                    @php
                                        $image = $refund_requests['attachments'];
                                        $cdecef = json_decode($image);
                                    @endphp
                                    <div class="d-flex align-items-center gap-4">
                                        <p>{{ __('Refund Reason') }} :</p>
                                        @if($refund_requests->refund_reason != 'Other')
                                            <p>{{ $refund_requests['refund_reason'] }}</p>
                                        @else
                                            <p>{{ $refund_requests['custom_refund_reason'] }}</p>
                                        @endif
                                    </div>
                                    @if(!empty($refund_requests->attachments))
                                    <div class="d-flex align-items-center gap-4">
                                        <p>{{ __('Refund Image') }} :</p>
                                            <p>
                                                @foreach ($cdecef as $imagePath)
                                                    <a href="{{ get_file($imagePath , APP_THEME()) }}" target="_blank">
                                                        <img src="{{ get_file($imagePath , APP_THEME()) }}" alt="Image" class="refund-image">
                                                    </a>
                                                @endforeach
                                            </p>
                                        </div>
                                    @endif
                                    <input type="hidden" class="refund_id" value="{{ $refund_requests['id'] }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="text-end">
        <button class="btn btn-primary mt-2 btn-submit accept-button" data-refund-id="{{ $refund_requests['id'] }}">{{ __('Accept Request') }}</button>
        <button class="btn btn-primary btn-block mt-2 btn-submit cancel-button" data-refund-id="{{ $refund_requests['id'] }}" type="submit">{{ __('Cancel Request') }}</button>
            @if ($RefundStatus['manage_stock'] ?? '' && $RefundStatus['manage_stock'] ?? '' == '1')
                <button class="btn btn-primary mt-2 btn-submit" id="manage-button">{{ __('Manage Stock')}}</button>
            @endif
        <button class="btn btn-primary mt-2 btn-submit " id="refund-amount"> {{ __('Refund Amount')}}</button>
    </div>
</div>
@endsection

@push('custom-script')
<script>
    $(document).ready(function() {
        setTimeout(() => {
            var actionPerformed = localStorage.getItem('actionPerformed');
            var initialStatus = '{{ $refund_requests["refund_status"] }}';

            if (initialStatus === 'Processing') {
                $('#manage-button').hide();
                $('#refund-amount').hide();
                $('.accept-button').show();
                $('.cancel-button').show();
            }
            else if (initialStatus === 'Accept') {
                $('#manage-button').show();
                $('#refund-amount').show();
                $('.accept-button').hide();
                $('.cancel-button').hide();
            }
            else if (initialStatus === 'Cancel') {
                $('.accept-button').hide();
                $('.cancel-button').hide();
                $('#manage-button').hide();
                $('#refund-amount').hide();
            }
            else if (initialStatus === 'Refunded') {
                $('.accept-button').hide();
                $('.cancel-button').hide();
                $('#manage-button').hide();
                $('#refund-amount').hide();
            }
        });
    }, 500);

    $('.accept-button').click(function() {
        var refundId = $(this).data('refund-id');
        localStorage.setItem('actionPerformed', 'Accept');
        $.ajax({
            type: 'POST',
            url: '{{ route('update-refund-status') }}',
            data: { _token: '{{ csrf_token() }}', refund_id: refundId },
            success: function(data) {
                if (data.status === 'Accept') {
                    $('.accept-button').hide();
                    $('.cancel-button').hide();
                    $('#manage-button').show();
                    $('#refund-amount').show();
                    show_toastr('Success', data.message, 'success');

                    localStorage.setItem('actionPerformed', true);
                } else {
                    $('#manage-button').hide();
                    $('#refund-amount').hide();
                    $('.accept-button').show();
                    $('.cancel-button').show();
                }
            },
        });
    });

    $('.cancel-button').click(function() {
        var refundId = $(this).data('refund-id');
        var product_refund_qty = $('.product_refund_qty').val();
        var subtotalString = $('.product_refund_price').val();
        var subtotal = parseFloat(subtotalString.replace('USD', '').trim());

        $.ajax({
            type: 'POST',
            url: '{{ route('cancel.refund.status') }}',
            data: {
                _token: '{{ csrf_token() }}',
                refund_id: refundId,
                product_refund_qty: product_refund_qty,
                subtotal: subtotal,
            },

            success: function(data) {
                $('.accept-button').hide();
                $('.cancel-button').hide();
                $('#manage-button').hide();
                $('#refund-amount').hide();
                show_toastr('Success', data.message, 'success');

                localStorage.setItem('actionPerformed', true);
            },
        });

        localStorage.setItem('actionPerformed', 'Cancel');
    });


    $(document).ready(function() {
        var isButtonClicked = localStorage.getItem('manageButtonClicked');

        if (isButtonClicked !== 'true') {
            $('#manage-button').show();
        } else {
            $('#manage-button').hide();
        }

        $(document).on('click', '#manage-button', function(e) {
            var refundId = $('.refund_id').val();
            var product_refund_qty = $('.product_refund_qty').val();

            localStorage.setItem('actionPerformed', 'Accept');
            var data = {
                refundId: refundId,
                product_refund_qty: product_refund_qty,
            }
            $.ajax({
                url: '{{ route('refund.stock') }}',
                method: 'POST',
                data: data,
                context: this,
                success: function(response) {
                    $('#manage-button').hide();
                    localStorage.setItem('manageButtonClicked', 'true');
                    show_toastr('Success', response.message, 'success');
                }
            });
        });
    });



    $(document).ready(function () {
        $('.check-shipp').click(function () {
            var trendingChecked = $('#customCheckdef1trending').is(':checked');
            var subtotalString = $('.refunded_amount').val();

            var subtotal = parseFloat(subtotalString.replace('$', '').trim());
            var deliveryPrice = parseFloat('{{ $order['delivered_charge'] }}');
            var refundId = $('.refund_id').val();

            var finalPrice = trendingChecked ? subtotal + deliveryPrice : subtotal;
            var finalPriceFormatted = finalPrice.toFixed(2);

            $.ajax({
                url: '{{ route('updateFinalPrice', $order['id']) }}',
                method: 'POST',
                data: {
                    finalPrice: finalPrice,
                    refundId: refundId,
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    $('.refunded_amount').html('<b class="abc">' + response.refund_amount + '</b>');
                    show_toastr('Success', response.message, 'success');
                },
                error: function (error) {
                    show_toastr('Error', 'Error updating final price.', 'error')
                }
            });
        });
    });



    $(document).ready(function() {
        var isButtonClicked = localStorage.getItem('refundedClicked');
        if(isButtonClicked) {
            $('#refund-amount').hide();
        }
        $(document).on('click', '#refund-amount', function(e) {
            var refundId = $('.refund_id').val();
            var refund_amount = $("td.refunded_amount b").text();
            var isTrendingChecked = $('#customCheckdef1trending').is(':checked');

            var data = {
                refundId: refundId,
                refund_amount: refund_amount,
                isTrendingChecked: isTrendingChecked,
            }

            $.ajax({
                url: '{{ route('refund.amount', $order['id']) }}',
                method: 'POST',
                data: data,
                context: this,
                success: function(response) {
                    $('#refund-amount').hide();
                    localStorage.setItem('refundedClicked', 'true');
                    show_toastr('Success', response.message, 'success');
                }
            });
        });
    });

</script>

@endpush
