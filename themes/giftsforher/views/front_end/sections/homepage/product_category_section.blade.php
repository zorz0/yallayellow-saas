<section class="interiors-design-section" style="@if (isset($option) && $option->is_hide == 1) opacity: 0.5; @else opacity: 1; @endif"
    data-index="{{ $option->order ?? '' }}" data-id="{{ $option->order ?? '' }}" data-value="{{ $option->id ?? '' }}"
    data-hide="{{ $option->is_hide ?? '' }}" data-section="{{ $option->section_name ?? '' }}"
    data-store="{{ $option->store_id ?? '' }}" data-theme="{{ $option->theme_id ?? '' }}">
    <div class="custome_tool_bar"></div>
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-3 col-12">
                <div class="section-title">
                    <h2 id="{{ $section->product_category->section->title->slug ?? '' }}_preview">
                        {!! $section->product_category->section->title->text ?? '' !!}
                    </h2>
                </div>
            </div>
            <div class="col-md-5 col-12">
                <div class=interiors-title-center>
                    <p id="{{ $section->product_category->section->description->slug ?? '' }}_preview">
                        {!! $section->product_category->section->description->text ?? '' !!}
                    </p>
                </div>
            </div>
            <div class="col-md-4 col-12">
                <a href="{{ route('page.product-list', $slug) }}" class="btn-secondary btn-secondary-theme-color"
                    id="{{ $section->product_category->section->button->slug ?? '' }}_preview">
                    {!! $section->product_category->section->button->text ?? '' !!}
                    <svg xmlns="http://www.w3.org/2000/svg" width="8" height="10" viewBox="0 0 4 6"
                        fill="none">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M0.65976 0.662719C0.446746 0.879677 0.446746 1.23143 0.65976 1.44839L2.18316 3L0.65976 4.55161C0.446747 4.76856 0.446747 5.12032 0.65976 5.33728C0.872773 5.55424 1.21814 5.55424 1.43115 5.33728L3.34024 3.39284C3.55325 3.17588 3.55325 2.82412 3.34024 2.60716L1.43115 0.662719C1.21814 0.445761 0.872773 0.445761 0.65976 0.662719Z"
                            fill=""></path>
                    </svg>
                </a>
            </div>
        </div>
        <div class="row padding-top">
            @foreach ($MainCategoryList->take(2) as $MainCategory)
                <div class="col-md-6 col-sm-6 col-12">
                    <div class="interiors-design-wrapper"
                        style="background-image: url({{ asset($MainCategory->image_path) }});">
                        <div class="row align-items-flex-end">
                            <div class="col-lg-5 col-12">
                                <div class="columnl-left-media-inner">
                                    <div class="column-left-media-content">
                                        <div class="section-title">
                                            <h3>{{ $MainCategory->name }}</h3>
                                        </div>
                                        <a href="{{ route('page.product-list', [$slug, 'main_category' => $MainCategory->slug]) }}"
                                            class="link-btn" tabindex="0">
                                            {{ __('Show More') }}
                                            <svg xmlns="http://www.w3.org/2000/svg" width="8" height="10"
                                                viewBox="0 0 4 6" fill="none">
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M0.65976 0.662719C0.446746 0.879677 0.446746 1.23143 0.65976 1.44839L2.18316 3L0.65976 4.55161C0.446747 4.76856 0.446747 5.12032 0.65976 5.33728C0.872773 5.55424 1.21814 5.55424 1.43115 5.33728L3.34024 3.39284C3.55325 3.17588 3.55325 2.82412 3.34024 2.60716L1.43115 0.662719C1.21814 0.445761 0.872773 0.445761 0.65976 0.662719Z"
                                                    fill=""></path>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @php
                                $prod = App\Models\Product::where('maincategory_id', $MainCategory->id)
                                    ->where('theme_id', $currentTheme)
                                    ->limit(1)
                                    ->get();
                            @endphp
                            @foreach ($prod as $pro)
                                @if ($pro->maincategory_id == $MainCategory->id)
                                    <div class="col-lg-7 col-12">
                                        <div class="columnl-right-caption-inner">
                                            <div class="product-card-inner">
                                                <div class="product-card-image">
                                                    <a href="{{ route('page.product', [$slug, $pro->slug) }}"
                                                        class="product-img">
                                                        <img src="{{ asset($pro->cover_image_path) }}"
                                                            class="default-img" alt="fan">
                                                    </a>
                                                    <div class="new-labl">
                                                        <a href="javascript:void(0)" class="wishbtn wishbtn-globaly"
                                                            product_id="{{ $pro->id }}"
                                                            in_wishlist="{{ $pro->in_whishlist ? 'remove' : 'add' }}">
                                                            <span class="wish-ic">
                                                                <i class="{{ $pro->in_whishlist ? 'fa fa-heart' : 'ti ti-heart' }}"
                                                                    style="color: black;"></i>
                                                            </span>
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="product-content">
                                                    <div class="product-content-top">
                                                        <div class="review-star">
                                                            <span>{{ $pro->ProductData->name }}</span>
                                                            <div class="d-flex align-items-center">
                                                                @for ($i = 0; $i < 5; $i++)
                                                                    <i
                                                                        class="fa fa-star review-stars {{ $i < $pro->average_rating ? 'text-warning' : '' }} "></i>
                                                                @endfor
                                                            </div>
                                                        </div>
                                                        <h3 class="product-title">
                                                            <a href="{{ route('page.product', [$slug, $pro->slug) }}">
                                                                {{ $pro->name }}
                                                            </a>
                                                        </h3>
                                                    </div>
                                                    <div class="product-content-bottom pro-bottom-price">
                                                        @if ($pro->variant_product == 0)
                                                            <div class="price">
                                                                <ins>{{ $pro->sale_price }}
                                                                    <span
                                                                        class="currency-type">{{ $currency }}</span>
                                                                </ins>
                                                            </div>
                                                        @else
                                                            <div class="price">
                                                                <ins>{{ __('In Variant') }}</ins>
                                                            </div>
                                                        @endif
                                                        <br>
                                                        <div class="custom-output">
                                                        {!! \App\Models\Product::productSalesPage($currentTheme, $slug, $pro->id) !!}
                                                        </div>
                                                    </div>
                                                    <div class="show-more-btn">
                                                        <a href="javascript:void(0)"
                                                            class="link-btn dark-link-btn addcart-btn-globaly"
                                                            product_id="{{ $pro->id }}" variant_id="0"
                                                            qty="1">
                                                            {{ __('Add to cart') }}
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="8"
                                                                height="10" viewBox="0 0 4 6" fill="none">
                                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                                    d="M0.65976 0.662719C0.446746 0.879677 0.446746 1.23143 0.65976 1.44839L2.18316 3L0.65976 4.55161C0.446747 4.76856 0.446747 5.12032 0.65976 5.33728C0.872773 5.55424 1.21814 5.55424 1.43115 5.33728L3.34024 3.39284C3.55325 3.17588 3.55325 2.82412 3.34024 2.60716L1.43115 0.662719C1.21814 0.445761 0.872773 0.445761 0.65976 0.662719Z"
                                                                    fill=""></path>
                                                            </svg>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
