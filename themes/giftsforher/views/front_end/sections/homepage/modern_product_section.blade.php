
<section class="two-col-variant-section-two" style="@if (isset($option) && $option->is_hide == 1) opacity: 0.5; @else opacity: 1; @endif"
    data-index="{{ $option->order ?? '' }}" data-id="{{ $option->order ?? '' }}" data-value="{{ $option->id ?? '' }}"
    data-hide="{{ $option->is_hide ?? '' }}" data-section="{{ $option->section_name ?? '' }}"
    data-store="{{ $option->store_id ?? '' }}" data-theme="{{ $option->theme_id ?? '' }}">
    <div class="custome_tool_bar"></div>
        <div class="container">
            @for ($i=0; $i < ($section->modern_product->loop_number); $i++)

                <div class="row">
                    <div class="col-lg-6 col-md-4 col-12 d-flex">
                        <div class="columnl-left-media-inner">
                            <div class="decorative-text"
                                id="{{ ($section->modern_product->section->sub_title->slug ?? '').'_'.$i }}_preview">
                                {!! $section->modern_product->section->sub_title->text->{$i} ?? '' !!}
                            </div>
                            <img src="{{ asset($section->modern_product->section->image->image->{$i} ?? '') }}"
                                id="{{ ($section->modern_product->section->image->slug ?? '').'_'.$i }}_preview" alt="room">
                            <div class="column-left-media-content">
                                <div class="section-title">
                                    <h3 id="{{ ($section->modern_product->section->title->slug ?? '') .'_'.$i}}_preview">
                                        {!! $section->modern_product->section->title->text->{$i} ?? '' !!}</h3>
                                </div>
                                <a href="{{ route('page.product-list', $slug) }}" class="link-btn" tabindex="0"
                                    id="{{ ($section->modern_product->section->button->slug ?? '').'_'.$i }}_preview">
                                    {!! $section->modern_product->section->button->text->{$i} ?? '' !!}
                                    <svg xmlns="http://www.w3.org/2000/svg" width="8" height="10" viewBox="0 0 4 6"
                                        fill="none">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M0.65976 0.662719C0.446746 0.879677 0.446746 1.23143 0.65976 1.44839L2.18316 3L0.65976 4.55161C0.446747 4.76856 0.446747 5.12032 0.65976 5.33728C0.872773 5.55424 1.21814 5.55424 1.43115 5.33728L3.34024 3.39284C3.55325 3.17588 3.55325 2.82412 3.34024 2.60716L1.43115 0.662719C1.21814 0.445761 0.872773 0.445761 0.65976 0.662719Z"
                                            fill=""></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-8 col-12">
                        <div class="row h-100">
                            @foreach($home_products->take(2) as $h_product)
                          
                            <div class="col-lg-6 col-md-6 col-sm-6 col-12 d-flex">
                                <div class="columnl-right-caption-inner w-100">
                                    <div class="product-card-inner">
                                        <div class="product-card-image">
                                            <a href="{{route('page.product',[$slug,$h_product->slug])}}" class="product-img">
                                                <img src="{{asset($h_product->cover_image_path)}}" class="default-img">
                                            </a>
                                            <div class="new-labl" >
                                                    <a href="javascript:void(0)" class="wishbtn wishbtn-globaly" product_id="{{$h_product->id}}" in_wishlist="{{ $h_product->in_whishlist ? 'remove' : 'add'}}" >
                                                        <span class="wish-ic">
                                                            <i class="{{ $h_product->in_whishlist ? 'fa fa-heart' : 'ti ti-heart' }}" ></i>
                                                        </span>
                                                    </a>
                                            </div>
                                        </div>
                                        <div class="product-content">
                                            <div class="product-content-top">
                                                <div class="review-star">
                                                    <span>{{ $h_product->ProductData->name }}</span>
                                                    <div class="d-flex align-items-center">
                                                        @if(!empty($h_product->average_rating))
                                                            @for ($i = 0; $i < 5; $i++)
                                                                <i class="fa fa-star review-stars {{ $i < $h_product->average_rating ? 'text-warning' : '' }} "></i>
                                                            @endfor
                                                        @endif
                                                    </div>
                                                </div>
                                                <h3 class="product-title"><a class="title" href="{{route('page.product',[$slug,$h_product->slug])}}">
                                                    {{$h_product->name}}</a></h3>
                                            </div>
                                            <div class="product-content-bottom">
                                                @if ($h_product->variant_product == 0)
                                                    <div class="price">
                                                        <ins>{{ $h_product->sale_price ?? $h_product->price }} <span class="currency-type">{{$currency}}</span></ins>
                                                    </div>
                                                @else
                                                    <div class="price">
                                                        <ins>{{ __('In Variant') }}</ins>
                                                    </div>
                                                @endif
                                                <div class="custom-output">
                                                {!! \App\Models\Product::productSalesPage($currentTheme, $slug,
                                                        $h_product->id) !!}
                                                </div>
                                                <a href="javascript:void(0)" class="link-btn addtocart-btn addcart-btn-globaly" product_id="{{ $h_product->id }}" variant_id="0" qty="1">
                                                    {{ __('Add to cart') }}
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
                </div>
                <div class="row row-reverse">
                    <div class="col-lg-6 col-md-4 col-12 d-flex">
                        <div class="columnl-left-media-inner">
                            <div class="decorative-text"
                                id="{{ ($section->modern_product->section->sub_title->slug ?? '').'_'.$i }}_preview">
                                {!! $section->modern_product->section->sub_title->text->{$i} ?? '' !!}
                            </div>
                            <img src="{{ asset($section->modern_product->section->image->image->{$i} ?? '') }}"
                                id="{{ ($section->modern_product->section->image->slug ?? '').'_'.$i }}_preview" alt="room">
                            <div class="column-left-media-content">
                                <div class="section-title">
                                    <h3 id="{{ ($section->modern_product->section->title->slug ?? '') .'_'.$i}}_preview">
                                        {!! $section->modern_product->section->title->text->{$i} ?? '' !!}</h3>
                                </div>
                                <a href="{{ route('page.product-list', $slug) }}" class="link-btn" tabindex="0"
                                    id="{{ ($section->modern_product->section->button->slug ?? '').'_'.$i }}_preview">
                                    {!! $section->modern_product->section->button->text->{$i} ?? '' !!}
                                    <svg xmlns="http://www.w3.org/2000/svg" width="8" height="10" viewBox="0 0 4 6"
                                        fill="none">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M0.65976 0.662719C0.446746 0.879677 0.446746 1.23143 0.65976 1.44839L2.18316 3L0.65976 4.55161C0.446747 4.76856 0.446747 5.12032 0.65976 5.33728C0.872773 5.55424 1.21814 5.55424 1.43115 5.33728L3.34024 3.39284C3.55325 3.17588 3.55325 2.82412 3.34024 2.60716L1.43115 0.662719C1.21814 0.445761 0.872773 0.445761 0.65976 0.662719Z"
                                            fill=""></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-8 col-12">
                        <div class="row h-100">
                            @foreach($home_products->take(2) as $h_product)
                            <div class="col-lg-6 col-md-6 col-sm-6 col-12 d-flex">
                                <div class="columnl-right-caption-inner w-100">
                                    <div class="product-card-inner">
                                        <div class="product-card-image">
                                            <a href="{{route('page.product',[$slug,$h_product->slug])}}" class="product-img">
                                                <img src="{{asset($h_product->cover_image_path )}}" class="default-img">
                                            </a>
                                            <div class="new-labl" >
                                                    <a href="javascript:void(0)" class="wishbtn wishbtn-globaly" product_id="{{$h_product->id}}" in_wishlist="{{ $h_product->in_whishlist ? 'remove' : 'add'}}" >
                                                        <span class="wish-ic">
                                                            <i class="{{ $h_product->in_whishlist ? 'fa fa-heart' : 'ti ti-heart' }}" ></i>
                                                        </span>
                                                    </a>
                                            </div>
                                        </div>
                                        <div class="product-content">
                                            <div class="product-content-top">
                                                <div class="review-star">
                                                    <span>{{ $h_product->ProductData->name }}</span>
                                                    <div class="d-flex align-items-center">
                                                        @if(!empty($h_product->average_rating))
                                                            @for ($i = 0; $i < 5; $i++)
                                                                <i class="fa fa-star review-stars {{ $i < $h_product->average_rating ? 'text-warning' : '' }} "></i>
                                                            @endfor
                                                        @endif
                                                    </div>
                                                </div>
                                                <h3 class="product-title"><a class="title" href="{{route('page.product',[$slug,$h_product->slug])}}">
                                                    {{$h_product->name}}</a></h3>
                                            </div>
                                            <div class="product-content-bottom">
                                                @if ($h_product->variant_product == 0)
                                                    <div class="price">
                                                        <ins>{{ $h_product->sale_price }} <span class="currency-type">{{$currency}}</span></ins>
                                                    </div>
                                                @else
                                                    <div class="price">
                                                        <ins>{{ __('In Variant') }}</ins>
                                                    </div>
                                                @endif
                                                <div class="custom-output">
                                                {!! \App\Models\Product::productSalesPage($currentTheme, $slug,
                                                        $h_product->id) !!}
                                                </div>
                                                <a href="javascript:void(0)" class="link-btn addtocart-btn addcart-btn-globaly" product_id="{{ $h_product->id }}" variant_id="0" qty="1">
                                                    {{ __('Add to cart') }}
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
                </div>
            @endfor
        </div>
    </section>