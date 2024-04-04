<table class="order-history-tbl">
    <thead>
        <tr>
            <th scope="col"> {{ __('Order ID') }}</th>
            <th scope="col"> {{ __('Order Date') }}</th>
            <th scope="col"> {{ __('Product') }}</th>
            <th scope="col"> {{ __('Refund Status') }}</th>
            <th scope="col"> {{ __('Refund Reason') }}</th>
            <th scope="col"> {{ __('Action') }}</th>
        </tr>
    </thead>
    <tbody>
        @if (count($order_refunds))
            @foreach ($order_refunds as $key => $order_refund)
                @php
                    $order_data = \App\Models\Order::find($order_refund->order_id);
                    $product_refund_ids = json_decode($order_refund->product_refund_id, true);
                @endphp
                <tr>
                    <td>{{ $order_data->product_order_id }} </td>
                    <td>{{ $order_data->delivery_date }} </td>
                    <td>
                        @foreach ($product_refund_ids as $product_refund_id)
                            @php
                                $product = json_decode($order_data->product_json, true);
                                $matching_product = null;
                                foreach ($product as $item) {
                                    if ($item['product_id'] == $product_refund_id['product_refund_id']) {
                                        $matching_product = $item;
                                        break;
                                    }
                                }
                            @endphp
                            @if ($matching_product)
                                {{ $matching_product['name'] }}<br>
                            @endif
                        @endforeach
                    </td>
                    <td> {{ $order_refund->refund_status }} </td>
                    @if($order_refund->refund_reason != 'Other')
                        <td> {{ $order_refund->refund_reason }} </td>
                    @else
                        <td>{{ $order_refund->custom_refund_reason }}</td>
                    @endif
                    <td>
                        <button class="btn btn-sm btn-primary me-2"
                                data-url="{{ route('order.refund', [$store->slug, $order_refund->id]) }}"
                                data-size="lg"
                                data-ajax-popup="true"
                                data-title="{{ __('Order Refund') }}">
                            <i class="ti ti-eye text-white py-1"></i>
                        </button>
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td><h2>{{ __('No records found') }}</h2></td>
            </tr>
        @endif

    </tbody>
</table>
<div class="right-result-tbl text-right">
    <b>Showing {{ $order_refunds->firstItem() }}</b> to {{ $order_refunds->lastItem() }} of {{ $order_refunds->currentPage() }} ({{ $order_refunds->lastPage() }} Pages)
</div>

<div class="form-container">
    <div class="d-flex acc-back-btn-wrp align-items-center justify-content-end">
        @php
            $previousPageUrl = '';
            $nextPageUrl = '';
            if ($order_refunds->currentPage() < 1) {
                $previousPageUrl = $order_refunds->previousPageUrl();
            }
            if ($order_refunds->lastPage() > 1 && $order_refunds->currentPage() != $order_refunds->lastPage()) {
                $nextPageUrl = $order_refunds->nextPageUrl();
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
