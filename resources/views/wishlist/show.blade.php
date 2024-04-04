<ul class="list-group">
    @foreach ($wishlist_product as $product)
    <li class="list-group-item">{{ $product->ProductData->name }}</li>
    @endforeach
</ul>