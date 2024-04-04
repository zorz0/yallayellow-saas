<div class="order-confirm-details">
    <h5> {{ __('Product informations') }} :</h5>
    <ul>
        @if (!empty($response->data->cart_total_product))
            @foreach ($response->data->product_list as $item)
                <li>{{ $item->qty }} {{ $item->name }} ({{ currency_format_with_sym(($item->final_price ?? 0), $store->id, $currentTheme) ?? SetNumberFormat($item->final_price) }})</li>
            @endforeach
        @endif
    </ul>
</div>