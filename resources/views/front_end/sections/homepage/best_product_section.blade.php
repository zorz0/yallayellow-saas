<section class="padding-bottom padding-top loved-category"
style="position: relative;@if(isset($option) && $option->is_hide == 1) opacity: 0.5; @else opacity: 1; @endif" data-index="{{ $option->order ?? '' }}" data-id="{{ $option->order ?? '' }}" data-value="{{ $option->id ?? '' }}" data-hide="{{ $option->is_hide ?? '' }}" data-section="{{ $option->section_name ?? '' }}"  data-store="{{ $option->store_id ?? '' }}" data-theme="{{ $option->theme_id ?? '' }}">
    <div class="custome_tool_bar"></div>
    <div class="container">
        <div class="section-title d-flex align-items-center justify-content-between">
            <h2 class="title" id="{{ $section->best_product->section->title->slug ?? '' }}_preview"> {!!
                $section->best_product->section->title->text ?? '' !!}</h2>
            <a href="#" class="btn" id="{{ $section->best_product->section->button->slug ?? '' }}_preview">
                {!! $section->best_product->section->button->text ?? '' !!}
                <svg xmlns="http://www.w3.org/2000/svg" width="4" height="6" viewBox="0 0 4 6" fill="none">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M0.65976 0.662719C0.446746 0.879677 0.446746 1.23143 0.65976 1.44839L2.18316 3L0.65976 4.55161C0.446747 4.76856 0.446747 5.12032 0.65976 5.33728C0.872773 5.55424 1.21814 5.55424 1.43115 5.33728L3.34024 3.39284C3.55325 3.17588 3.55325 2.82412 3.34024 2.60716L1.43115 0.662719C1.21814 0.445761 0.872773 0.445761 0.65976 0.662719Z"
                        fill="white"></path>
                </svg>
            </a>
        </div>
        <div class="row row-gap">
                    <div class="col-xl-6 col-lg-5 col-md-4 col-12">
                        <div class="category-card">
                            <div class="category-img">
                                <img src="{{ asset($section->best_product->section->image->image ?? '') }}"
                                    alt="vegetables-2" class="{{ ($section->best_product->section->image->slug ?? '').'_preview'}}">
                            </div>
                            <div class="category-card-body">

                                <div class="title-wrapper">
                                    <h4 class="title" id="{{ $section->best_product->section->sub_title->slug ?? '' }}_preview">
                {!! $section->best_product->section->sub_title->text ?? '' !!}</h4>
                                </div>
                                <p id="{{ $section->best_product->section->description->slug ?? '' }}_preview">
                {!! $section->best_product->section->description->text ?? '' !!}
                                </p>
                                <div class="btn-wrapper">
                                    <a href="{{ route('page.product-list', $slug) }}" class="btn" id="{{ $section->best_product->section->button_first->slug ?? '' }}_preview">
                {!! $section->best_product->section->button_first->text ?? '' !!}<svg xmlns="http://www.w3.org/2000/svg"
                                            width="4" height="6" viewBox="0 0 4 6" fill="none">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M0.65976 0.662719C0.446746 0.879677 0.446746 1.23143 0.65976 1.44839L2.18316 3L0.65976 4.55161C0.446747 4.76856 0.446747 5.12032 0.65976 5.33728C0.872773 5.55424 1.21814 5.55424 1.43115 5.33728L3.34024 3.39284C3.55325 3.17588 3.55325 2.82412 3.34024 2.60716L1.43115 0.662719C1.21814 0.445761 0.872773 0.445761 0.65976 0.662719Z"
                                                fill="white"></path>
                                        </svg></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-7 col-md-8 col-12">
                        <div class="product-row loved-category-slider">
                            @foreach ($modern_products as $product)
                                
                                <div class="product-card">
                                    <div class="product-card-inner">
                                        <div class="product-card-image">
                                            <a href="{{ url($slug.'/product/'.$product->slug) }}">
                                                <img src="{{ asset($product->cover_image_path) }}"
                                                    class="default-img">
                                                @if ($product->Sub_image($product->id)['status'] == true)
                                                    <img src="{{ asset($product->Sub_image($product->id)['data'][0]->image_path ?? '') }}"
                                                        class="hover-img">
                                                @else
                                                    <img src="{{ asset($product->Sub_image($product->id)) }}"
                                                        class="hover-img">
                                                @endif
                                                <button class="wishlist-btn" tabindex="0" style="top: 1px;">
                                                    <a href="javascript:void(0)" class="wishlist-btn wishbtn-globaly"
                                                        tabindex="0" product_id="{{ $product->id }}"
                                                        in_wishlist="{{ $product->in_whishlist ? 'remove' : 'add' }}">
                                                        <i class="{{ $product->in_whishlist ? 'fa fa-heart' : 'ti ti-heart' }}"></i>
                                                    </a>
                                                </button>
                                            </a>
                                        </div>
                                        <div class="custom-output">
                                        {!! \App\Models\Product::productSalesPage($currentTheme, $slug, $product->id) !!}
                                        </div>
                                        <div class="product-content">
                                            <div class="product-content-top">
                                                <div class="product-type">{{ ucfirst($product->tag_api) }}</div>
                                                <h3 class="product-title">
                                                    <a href="{{ url($slug.'/product/'.$product->slug) }}">
                                                        {{ $product->name }}
                                                    </a>
                                                </h3>
                                                <div class="reviews-stars-wrap d-flex align-items-center">
                                                    <div class="reviews-stars-outer">
                                                        @for ($i = 0; $i < 5; $i++)
                                                            <i
                                                                class="ti ti-star review-stars {{ $i < $product->average_rating ? 'text-warning' : '' }} "></i>
                                                        @endfor
                                                    </div>
                                                    <div class="point-wrap">
                                                        <span class="review-point">{{ $product->average_rating }}.0/
                                                            <span>5.0</span></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="product-content-bottom">
                                                @if ($product->variant_product == 0)
                                                    <div class="price">
                                                        <ins> 
                                                        {{ currency_format_with_sym(($product->sale_price ?? $product->price), $store->id, $currentTheme)}}
                                                        {{-- {{ $product->sale_price ?? $product->price }} 
                                                        <span
                                                                class="currency-type">{{ $currency }}</span> --}}</ins>
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
                    </div>
                </div>

    </div>
</section>