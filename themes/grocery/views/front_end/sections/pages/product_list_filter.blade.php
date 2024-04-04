<div class="row">
    @foreach ($products as $product)
        <div class="col-lg-4 col-xl-4 col-md-4 col-sm-6 col-12 product-card">
            <div class="product-card-inner">
                <div class="product-card-image">
                    <a href="{{ url($slug.'/product/'.$product->slug) }}">
                        <img src="{{ asset($product->cover_image_path) }}" class="default-img">
                        @if ($product->Sub_image($product->id)['status'] == true)
                            <img src="{{ asset($product->Sub_image($product->id)['data'][0]->image_path) }}"
                                class="hover-img">
                        @else
                            <img src="{{ asset($product->Sub_image($product->id)) }}" class="hover-img">
                        @endif
                    </a>
                    <button class="wishlist-btn" tabindex="0" style="top: 1px;">
                        <a href="javascript:void(0)" class="wishlist-btn wishbtn-globaly" tabindex="0"
                            product_id="{{ $product->id }}"
                            in_wishlist="{{ $product->in_whishlist ? 'remove' : 'add' }}">
                            <i class="{{ $product->in_whishlist ? 'fa fa-heart' : 'ti ti-heart' }}"></i>
                        </a>
                    </button>
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
                <div class="custom-output">
                {!! \App\Models\Product::productSalesPage($currentTheme, $slug, $product->id) !!}
                </div>
                <div class="product-content">
                    <div class="product-content-top">
                        <div class="product-type">{{ ucfirst($product->label->name ?? '') }}</div>
                        <h3 class="product-title">
                            <a href="{{ url($slug.'/product/'.$product->slug) }}">
                                {{ $product->name }}
                            </a>
                        </h3>
                        <div class="reviews-stars-wrap">
                            <div class="reviews-stars-outer">
                                @for ($i = 0; $i < 5; $i++)
                                    <i
                                        class="ti ti-star review-stars {{ $i < $product->average_rating ? 'text-warning' : '' }} "></i>
                                @endfor

                            </div>
                            <div class="point-wrap">
                                <span class="review-point">{{ $product->average_rating }}.0 / <span>5.0</span></span>
                            </div>
                        </div>
                    </div>

                    <div class="product-content-bottom">
                        @if ($product->variant_product == 0)
                            <div class="price">
                                {{ currency_format_with_sym(($product->sale_price ?? $product->price) , $store->id, $currentTheme) }}
                                {{--<ins>{{ $product->sale_price ?? $product->price }} <span
                                        class="currency-type">{{ $currency }}</span></ins>--}}
                            </div>
                        @else
                            <div class="price">
                                <ins>{{ __('In Variant') }}</ins>
                            </div>
                        @endif
                        <button class="addtocart-btn btn   addcart-btn-globaly" tabindex="0"
                            product_id="{{ $product->id }}" variant_id="0" qty="1">
                            <span> {{ __('Add to cart') }} </span>
                            <svg viewBox="0 0 10 5">
                                <path
                                    d="M2.37755e-08 2.57132C-3.38931e-06 2.7911 0.178166 2.96928 0.397953 2.96928L8.17233 2.9694L7.23718 3.87785C7.07954 4.031 7.07589 4.28295 7.22903 4.44059C7.38218 4.59824 7.63413 4.60189 7.79177 4.44874L9.43039 2.85691C9.50753 2.78197 9.55105 2.679 9.55105 2.57146C9.55105 2.46392 9.50753 2.36095 9.43039 2.28602L7.79177 0.69418C7.63413 0.541034 7.38218 0.544682 7.22903 0.702329C7.07589 0.859976 7.07954 1.11192 7.23718 1.26507L8.1723 2.17349L0.397965 2.17336C0.178179 2.17336 3.46059e-06 2.35153 2.37755e-08 2.57132Z">
                                </path>
                            </svg>
                        </button>

                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

@php
$page_no = !empty($page) ? $page : 1;
@endphp
<div class="d-flex justify-content-end col-12">
    <nav class="dataTable-pagination">
        <ul class="dataTable-pagination-list">
            <li class="pagination">
                {{ $products->onEachSide(0)->links('pagination::bootstrap-4') }}
            </li>
        </ul>
    </nav>
</div>
