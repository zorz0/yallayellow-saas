<section class="main-home-first-section"
    style="position: relative;@if (isset($option) && $option->is_hide == 1) opacity: 0.5; @else opacity: 1; @endif"
    data-index="{{ $option->order ?? '' }}" data-id="{{ $option->order ?? '' }}" data-value="{{ $option->id ?? '' }}"
    data-hide="{{ $option->is_hide ?? '' }}" data-section="{{ $option->section_name ?? '' }}"
    data-store="{{ $option->store_id ?? '' }}" data-theme="{{ $option->theme_id ?? '' }}">
    <div class="custome_tool_bar"></div>
    <img class="home-bg-img" src="{{asset('themes/'.$currentTheme.'/assets/images/banner-main.png')}}" alt="furniture"> 
    <div class="home-slider-wrapper offset-left">
        <div class="row align-items-center no-gutters">
            <div class="col-lg-4 col-12 padding-top">
                <div class="home-slider-left-column-inner">
                    <div class="home-slider-left-col">
                        @foreach ($home_products->take(3) as $product)
                            <div class="home-slider-left">
                                <div class="home-left-slider-inner">
                                    <div class="review-star">
                                        <span>{{ $product->ProductData->name }}</span>
                                        <div class="d-flex align-items-center">
                                            @for ($i = 0; $i < 5; $i++)
                                               
                                            @endfor
                                        </div>
                                    </div>
                                    <div class="section-title">
                                        <h2 class="title">{{ $product->name }}</h2>
                                    </div>
                                    <p class="descriptions">{{ strip_tags($product->description) }}
                                    </p>
                                    <div class="d-flex align-items-center margin-top-btn">
                                        <a href="JavaScript:void(0)"
                                            class="btn-secondary m-0 w-100 addtocart-btn addcart-btn-globaly"
                                            product_id="{{ $product->id }}" variant_id="0" qty="1">
                                            {{ __('Add to cart') }}
                                            <svg xmlns="http://www.w3.org/2000/svg" width="8" height="10"
                                                viewBox="0 0 4 6" fill="none">
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M0.65976 0.662719C0.446746 0.879677 0.446746 1.23143 0.65976 1.44839L2.18316 3L0.65976 4.55161C0.446747 4.76856 0.446747 5.12032 0.65976 5.33728C0.872773 5.55424 1.21814 5.55424 1.43115 5.33728L3.34024 3.39284C3.55325 3.17588 3.55325 2.82412 3.34024 2.60716L1.43115 0.662719C1.21814 0.445761 0.872773 0.445761 0.65976 0.662719Z"
                                                    fill=""></path>
                                            </svg>
                                        </a>
                                        @if ($product->variant_product == 0)
                                            <div class="price">
                                                <ins>{{ $product->sale_price }} <span
                                                        class="currency-type">{{ $currency }}</span></ins>
                                            </div>
                                        @else
                                            <div class="price">
                                                <ins>{{ __('In Variant') }}</ins>
                                            </div>
                                        @endif
                                        <div class="custom-output">
                                            {!! \App\Models\Product::productSalesPage($currentTheme, $slug, $product->id) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="slides-numbers" style="display: block;">
                        <span class="active">01</span> / <span class="total"></span>
                    </div>
                </div>
            </div>

            <div class="col-lg-5 col-12 padding-top">
                <div class="home-banner-image">
                    <img src="{{ asset($section->slider->section->background_image->image ?? '') }}"
                        id="{{ ($section->slider->section->background_image->slug ?? '') . '_preview' }}"
                        class="margin-left-banner-image" alt="lifestyle">
                </div>
            </div>
            <div class="col-lg-3 col-12">
                <div class="banner-right-col">
                    <div class="home-banner-image">
                        <img src="{{ asset($section->slider->section->background_image_second->image ?? '') }}"
                            id="{{ ($section->slider->section->background_image_second->slug ?? '') . '_preview' }}"
                            alt="home-decor">
                    </div>
                </div>
            </div>
        </div>
        <div class="home-slider-right-col desk-only"></div>
    </div>
</section>
