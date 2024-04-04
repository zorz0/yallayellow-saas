<ul class="list-group">
    @foreach ($cart_product as $product)
    <li class="list-group-item">{{ $product->product_data->name }}</li>
    @endforeach
</ul>
