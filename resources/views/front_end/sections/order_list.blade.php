<table class="order-history-tbl">
    <thead>
        <tr>
            <th scope="col">{{ __('Order ID') }}</th>
            <th scope="col">{{ __('Order Date') }}</th>
            <th scope="col">{{ __('Product') }}</th>
            <th scope="col">{{ __('Price') }}</th>
            <th scope="col"> {{ __('Payment Type') }}</th>
            <th scope="col"> {{ __('Delivered Status') }}</th>
            <th class="">{{ __('Action') }}</th>
        </tr>
    </thead>
    <tbody>
        @if (count($orders))
            @foreach ($orders as $Order)
                @php
                    $order_data = $Order->order_detail($Order->id);
                    $store = \App\Models\Store::find($Order->store_id);
                @endphp
                <tr>
                    <td> {{ $order_data['order_id'] }} </td>
                    <td> {{ $Order->order_date }} </td>
                    <td> {{ implode(', ', array_column($order_data['product'], 'name')) }} </td>
                    <td> {{ $order_data['final_price'] }} </td>
                    <td> {{ $order_data['paymnet_type'] }} </td>
                    <td> {{ $order_data['order_status_text'] }} </td>
                    <td class="text-end row">
                        @if (
                            $order_data['payment_status'] == 'Unpaid' &&
                                $order_data['order_status_text'] != 'Cancel' &&
                                $Order->delivered_status == 0)
                            <a class="delstatus btn btn-sm btn-primary me-2 " data-id="{{ $order_data['id'] }}"
                                data-title="{{ __('View Order') }}">
                                <i class="ti ti-trash text-white py-1"></i>
                            </a>
                        @endif
                        <button class="btn btn-sm btn-primary me-2 "
                            data-url="{{ route('customer.order', [$store->slug, Crypt::encrypt($Order->id)]) }}"
                            data-size="lg" data-ajax-popup="true" data-title="{{ __('View Order') }}">
                            <i class="ti ti-eye text-white py-1"></i>
                        </button>
                        @php
                            $showRefundButton = false;
                        @endphp
                        @foreach ($order_refunds as $refund)
                            @if ($refund->order_id == $Order['id'])
                                @php
                                    $showRefundButton = true;
                                    break;
                                @endphp
                            @endif
                        @endforeach
                        @if (!$showRefundButton)
                            @if ($Order['delivered_status'] == '1')
                                <button class="btn btn-sm btn-primary me-2"
                                    data-url="{{ route('order.refund', [$store->slug, $Order->id, 'refund' => true]) }}"
                                    data-size="lg" data-ajax-popup="true" data-title="{{ __('Order Refund') }}">
                                    <i class="ti ti-truck-return text-white py-1"></i>
                                </button>
                            @endif
                        @endif
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td>
                    <h2>{{ __('No records found') }}</h2>
                </td>
            </tr>
        @endif
    </tbody>
</table>
<div class="right-result-tbl text-right">
    <b>Showing {{ $orders->firstItem() }}</b> to {{ $orders->lastItem() }} of {{ $orders->currentPage() }}
    ({{ $orders->lastPage() }} Pages)
</div>

<div class="form-container">
    <div class="d-flex acc-back-btn-wrp align-items-center justify-content-end">
        @php
            $previousPageUrl = '';
            $nextPageUrl = '';
            if ($orders->currentPage() < 1) {
                $previousPageUrl = $orders->previousPageUrl();
            }
            if ($orders->lastPage() > 1 && $orders->currentPage() != $orders->lastPage()) {
                $nextPageUrl = $orders->nextPageUrl();
            }
        @endphp
        <button class="btn-secondary back-btn-acc" onclick="get_order('{{ $previousPageUrl }}')">
            <svg xmlns="http://www.w3.org/2000/svg" width="35" height="14" viewBox="0 0 35 14" fill="none">
                <path d="M25.0749 14L35 7L25.0805 0L29.12 6.06667H0V7.93333H29.12L25.0749 14Z"></path>
            </svg>
            {{ __('Back') }}
        </button>
        <button class="btn continue-btn" onclick="get_order('{{ $nextPageUrl }}')">
            {{ __('Next') }}
            <svg xmlns="http://www.w3.org/2000/svg" width="35" height="14" viewBox="0 0 35 14" fill="none">
                <path d="M25.0749 14L35 7L25.0805 0L29.12 6.06667H0V7.93333H29.12L25.0749 14Z"></path>
            </svg>
        </button>
    </div>
</div>
<script>
    $(document).on('click', '.delstatus', function() {
        var order_id = $(this).attr('data-id');
        var data = {
            order_id: order_id,
            order_status: 'cancel',
        }
        $.ajax({
            url: '{{ route('status.cancel', $store->slug) }}',
            data: data,
            type: 'post',
            success: function(data) {
                if (data.status == 'error') {
                    show_toastr('{{ __('Error') }}', data.message, 'error')
                } else {
                    show_toastr('{{ __('Success') }}', data.message, 'success')
                    setTimeout(function() {
                        location.reload();
                    }, 2000);
                }
            }
        });
    });
</script>
