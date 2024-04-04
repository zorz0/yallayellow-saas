@foreach ($latestSales as $productId => $saleData)
    <div class="custom-output">
        <div class="option">
            @if ($saleData['discount_type'] == 'flat')
                -{{ $saleData['discount_amount'] }}{{ $currency_icon ?? '$' }}
            @elseif ($saleData['discount_type'] == 'percentage')
                -{{ $saleData['discount_amount'] }}%
            @endif
        </div>
    </div>
@endforeach