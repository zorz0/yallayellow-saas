@foreach ($latestSales as $productId => $saleData)
<div class="custom-output sale-tag-product">
    <div class="sale_tag_icon rounded col-1 onsale">
        <div>{{ __('Sale!') }}</div>
    </div>
</div>
@endforeach