<section class="best-product-section padding-top {{ $option->class_name }}" style="position: relative;@if($option->is_hide == 1) opacity: 0.5; @else opacity: 1; @endif" data-index="{{ $option->order }}" data-id="{{ $option->order }}" data-value="{{ $option->id }}" data-hide="{{ $option->is_hide  }}">
    <div class="custome_tool_bar"></div>    
    <div class="container">
        <div class="row align-items-center res-column-reverse">
            <div class="col-md-6 col-12">
                <div class="best-product-right-inner">
                    <div class="product-banner-image-right">
                        <img src="{{ asset('themes/' . $currentTheme . '/assets/images/product-banner-right.png') }}" alt="product banner right">
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-12">
                <div class="section-title-left">
                    <div class="section-title">
                        <h2> <b>Fall in love with </b> <br> amazing aromas </h2>
                    </div>
                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum
                        has been the industry's standard dummy text </p>
                </div>
                <div class="banner-links">
                    <a href="product-list.html" class="btn" tabindex="0">
                        Search more products
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>