<div class="row">
    @foreach ($products as $product)
        <div class="col-lg-4 col-md-6 col-sm-6 col-12 product-card">
            <div class="product-card-inner">
                <div class="product-content-top text-center">
                    <div class="label">{{ $product->label->name ?? '' }}</div>
                    <h4><a href="{{ url($slug.'/product/'.$product->slug) }}">{{$product->name}}</a> </h4>
                    <div class="favorite-icon">
                        <a href="javascript:void(0)" class="wishbtn-globaly" product_id="{{$product->id}}" in_wishlist="{{ $product->in_whishlist ? 'remove' : 'add'}}">
                        <i class="{{ $product->in_whishlist ? 'fa fa-heart' : 'ti ti-heart' }}" style='color: white'></i>
                        </a>
                    </div>
                    <div class="product-btn-wrp">
                        @php
                            $module = Nwidart\Modules\Facades\Module::find('ProductQuickView');
                            $compare_module = Nwidart\Modules\Facades\Module::find('ProductCompare');
                        @endphp
                        @if(isset($module) && $module->isEnabled())
                            {{-- Include the module blade button --}}
                            @include('productquickview::pages.button', ['product_slug' => $product->slug ?? null, 'slug' => $slug ?? null, 'currentTheme' => $currentTheme])
                        @endif
                        @if(isset($compare_module) && $compare_module->isEnabled())
                            {{-- Include the module blade button --}}
                            @include('productcompare::pages.button', ['product_slug' => $product->slug ?? null, 'slug' => $slug ?? null, 'currentTheme' => $currentTheme])
                        @endif
                    </div>
                </div>
                <div class="product-img">
                    <div class="new-labl">
                    </div>
                    <a href="{{ url($slug.'/product/'.$product->slug) }}">
                        <img src="{{asset($product->cover_image_path)  }}" alt="product-img">
                    </a>
                </div>
                <div class="product-content-bottom text-center">
                    <div class="price-label2">
                        @if($product->variant_product == 0)
                            <div class="price">
                                {{ currency_format_with_sym(($product->sale_price ?? $product->price) , $store->id, $currentTheme) }}
                                {{--<ins>{{ $product->sale_price }} <span
                                        class="currency-type">{{ $currency_icon }}</span></ins>--}}
                            </div>
                        @else
                            <div class="price">
                                <ins>{{__('In Variant')}}</ins>
                            </div>
                        @endif
                        {!! \App\Models\Product::productSalesPage($currentTheme, $slug, $product->id) !!}

                    </div>
                    <a href="javascript:void(0)" class="btn theme-btn addcart-btn-globaly" product_id="{{ $product->id }}" variant_id="0" qty="1">
                        {{ __('Add to cart') }}
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="15" viewBox="0 0 16 15" fill="none">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M4.22012 7.5C4.02701 7.5 3.88012 7.6734 3.91187 7.86387L4.51047 11.4555C4.61093 12.0582 5.13242 12.5 5.74347 12.5H10.2578C10.8689 12.5 11.3904 12.0582 11.4908 11.4555L12.0894 7.86387C12.1212 7.6734 11.9743 7.5 11.7812 7.5H4.22012ZM3.11344 6.25C2.72722 6.25 2.43345 6.59679 2.49694 6.97775L3.27748 11.661C3.47839 12.8665 4.52137 13.75 5.74347 13.75H10.2578C11.4799 13.75 12.5229 12.8665 12.7238 11.661L13.5044 6.97775C13.5678 6.59679 13.2741 6.25 12.8879 6.25H3.11344Z" fill="#12131A"/>
                            <path d="M6.75 8.75C6.40482 8.75 6.125 9.02982 6.125 9.375V10.625C6.125 10.9702 6.40482 11.25 6.75 11.25C7.09518 11.25 7.375 10.9702 7.375 10.625V9.375C7.375 9.02982 7.09518 8.75 6.75 8.75Z" fill="#12131A"/>
                            <path d="M9.25 8.75C8.90482 8.75 8.625 9.02982 8.625 9.375V10.625C8.625 10.9702 8.90482 11.25 9.25 11.25C9.59518 11.25 9.875 10.9702 9.875 10.625V9.375C9.875 9.02982 9.59518 8.75 9.25 8.75Z" fill="#12131A"/>
                            <path d="M7.19194 2.31694C7.43602 2.07286 7.43602 1.67714 7.19194 1.43306C6.94786 1.18898 6.55214 1.18898 6.30806 1.43306L3.80806 3.93306C3.56398 4.17714 3.56398 4.57286 3.80806 4.81694C4.05214 5.06102 4.44786 5.06102 4.69194 4.81694L7.19194 2.31694Z" fill="#12131A"/>
                            <path d="M8.80806 2.31694C8.56398 2.07286 8.56398 1.67714 8.80806 1.43306C9.05214 1.18898 9.44786 1.18898 9.69194 1.43306L12.1919 3.93306C12.436 4.17714 12.436 4.57286 12.1919 4.81694C11.9479 5.06102 11.5521 5.06102 11.3081 4.81694L8.80806 2.31694Z" fill="#12131A"/>
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M12.375 5H3.625C3.27982 5 3 5.27982 3 5.625C3 5.97018 3.27982 6.25 3.625 6.25H12.375C12.7202 6.25 13 5.97018 13 5.625C13 5.27982 12.7202 5 12.375 5ZM3.625 3.75C2.58947 3.75 1.75 4.58947 1.75 5.625C1.75 6.66053 2.58947 7.5 3.625 7.5H12.375C13.4105 7.5 14.25 6.66053 14.25 5.625C14.25 4.58947 13.4105 3.75 12.375 3.75H3.625Z" fill="#12131A"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    @endforeach
</div>
@php
$page_no = !empty($page) ? $page : 1;
@endphp
<!-- a Tag for previous page -->
{{ $products->onEachSide(0)->links('pagination::bootstrap-4') }}
