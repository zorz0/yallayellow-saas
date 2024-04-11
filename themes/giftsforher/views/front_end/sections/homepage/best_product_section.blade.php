<section class="best-product-section" style="@if (isset($option) && $option->is_hide == 1) opacity: 0.5; @else opacity: 1; @endif"
    data-index="{{ $option->order ?? '' }}" data-id="{{ $option->order ?? '' }}" data-value="{{ $option->id ?? '' }}"
    data-hide="{{ $option->is_hide ?? '' }}" data-section="{{ $option->section_name ?? '' }}"
    data-store="{{ $option->store_id ?? '' }}" data-theme="{{ $option->theme_id ?? '' }}">
    <div class="custome_tool_bar"></div>
    <div class="left-side-image" style="z-index: unset; top: 46%;">
        <img src="{{asset('themes/'.$currentTheme.'/assets/images/left-banner.png')}}" alt="Gifts.">
    </div>
    <div class="offset-container offset-left">
        <div class="best-product-slider">
            @foreach ($all_products as $all_product)
                <div class="best-pro-item">
                    <div class="product-card-inner">
                        <div class="product-card-image">
                            <a href="{{ route('page.product', [$slug, $all_product->slug]) }}" class="product-img">
                                <img src="{{ asset($all_product->cover_image_path) }}" alt="bestproduct">
                                <div class="new-labl">
                                    {{ $all_product->ProductData->name }}
                                </div>
                            </a>
                            <div class="product-content">
                                <div class="product-content-top">
                                    <h3 class="product-title">
                                        <a href="{{ route('page.product', [$slug, $all_product->slug]) }}">
                                            <b class="title">{{ $all_product->name }}</b>
                                        </a>
                                    </h3>
                                </div>
                                <div class="show-more-btn">
                                    <a href="{{ route('page.product-list', $slug) }}" class="link-btn" tabindex="0">
                                        {{ __('Show more') }}
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
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
