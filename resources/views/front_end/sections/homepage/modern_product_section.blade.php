<section class="show-products padding-top" style="position: relative;@if(isset($option) && $option->is_hide == 1) opacity: 0.5; @else opacity: 1; @endif" data-index="{{ $option->order ?? '' }}" data-id="{{ $option->order ?? '' }}" data-value="{{ $option->id ?? '' }}" data-hide="{{ $option->is_hide ?? '' }}" data-section="{{ $option->section_name ?? '' }}"  data-store="{{ $option->store_id ?? '' }}" data-theme="{{ $option->theme_id ?? '' }}">
    <div class="custome_tool_bar"></div>       
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-12">
                <div class="show-products-left-inner">
                    @if(isset($section->modern_product->section->title))
                    <div class="section-title section-title-white">
                        <h2 id="{{ $section->modern_product->section->title->slug ?? '' }}_preview"> {!! $section->modern_product->section->title->text ?? '' !!} </h2>
                    </div>
                    @endif
                    <div class="row align-items-center min-padding-top">
                        <div class="col-lg-6 col-md-12 col-sm-6 col-12 bestseller-item product-card">
                            <div class="product-card-inner">
                                <div class="product-card-image">
                                    <a href="product.html">
                                        <img src="{{ asset('themes/' . $currentTheme . '/assets/images/best-pro1.png') }}" class="default-img">
                                        <img src="{{ asset('themes/' . $currentTheme . '/assets/images/best-pro1-hover.png') }}" class="hover-img">
                                    </a>
                                    <div class="new-labl">
                                        -20%
                                    </div>
                                    <div class="like-items-icon">
                                        <a href="javascript:;">
                                            <svg width="12" height="10" viewBox="0 0 12 10" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M6.11593 2.07337C5.92621 2.26388 5.62665 2.26388 5.43693 2.07337L5.09742 1.73247C4.70005 1.33346 4.16322 1.08982 3.56995 1.08982C2.35134 1.08982 1.36347 2.12348 1.36347 3.39855C1.36347 4.62096 1.99589 5.63035 2.90887 6.4597C3.82263 7.28976 4.91512 7.84026 5.56787 8.12122C5.70435 8.17997 5.84851 8.17997 5.98499 8.12122C6.63774 7.84026 7.73023 7.28976 8.64399 6.4597C9.55697 5.63035 10.1894 4.62096 10.1894 3.39855C10.1894 2.12348 9.20151 1.08982 7.98291 1.08982C7.38964 1.08982 6.85281 1.33346 6.45544 1.73247L6.11593 2.07337ZM5.77643 0.992124C5.20378 0.417112 4.42631 0.0637207 3.56995 0.0637207C1.80974 0.0637207 0.382812 1.55678 0.382812 3.39855C0.382812 6.66579 3.80057 8.47007 5.1948 9.07017C5.57028 9.23178 5.98258 9.23178 6.35806 9.07017C7.75229 8.47007 11.17 6.66579 11.17 3.39855C11.17 1.55678 9.74312 0.0637207 7.98291 0.0637207C7.12655 0.0637207 6.34908 0.417112 5.77643 0.992124Z"
                                                    fill="#12131A" />
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                                <div class="product-content text-center">
                                    <div class="product-content-top">
                                        <h3 class="product-title">
                                            <a href="product.html">
                                                Oat Hat
                                            </a>
                                        </h3>
                                        <div class="product-type">100% oat seeds</div>
                                    </div>
                                    <div
                                        class="product-content-bottom d-flex align-items-center justify-content-center">
                                        <div class="price">
                                            <ins>$29.99</ins>
                                        </div>
                                        <div
                                            class="d-flex flex-wrap text-checkbox checkbox-radio align-items-center justify-content-center w-100">
                                            <div class="checkbox">
                                                <input id="ml-1" name="radio" type="radio" value=".blue">
                                                <label for="ml-1" class="checkbox-label">XL</label>
                                            </div>
                                            <div class="checkbox">
                                                <input id="ml-2" name="radio" type="radio" value=".blue">
                                                <label for="ml-2" class="checkbox-label">XXL</label>
                                            </div>
                                        </div>
                                        <a href="cart.html" class="addtocart-btn btn btn-primary ">
                                            <span> Add to cart</span>
                                            <svg width="13" height="13" viewBox="0 0 13 13" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M9.24173 3.38727H2.75827C2.10699 3.38727 1.59075 3.93678 1.63138 4.58679L1.91365 9.10315C1.95084 9.69822 2.44431 10.1618 3.04054 10.1618H8.95946C9.55569 10.1618 10.0492 9.69822 10.0863 9.10315L10.3686 4.58679C10.4092 3.93678 9.89301 3.38727 9.24173 3.38727ZM2.75827 2.25818C1.4557 2.25818 0.423236 3.35719 0.504488 4.65722L0.78676 9.17358C0.861144 10.3637 1.84808 11.2909 3.04054 11.2909H8.95946C10.1519 11.2909 11.1389 10.3637 11.2132 9.17358L11.4955 4.65722C11.5768 3.35719 10.5443 2.25818 9.24173 2.25818H2.75827Z"
                                                    fill="#ffffff"></path>
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M3.17727 2.82273C3.17727 1.26378 4.44105 0 6 0C7.55895 0 8.82273 1.26378 8.82273 2.82273V3.95182C8.82273 4.26361 8.56997 4.51636 8.25818 4.51636C7.94639 4.51636 7.69363 4.26361 7.69363 3.95182V2.82273C7.69363 1.88736 6.93537 1.12909 6 1.12909C5.06463 1.12909 4.30636 1.88736 4.30636 2.82273V3.95182C4.30636 4.26361 4.05361 4.51636 3.74182 4.51636C3.43003 4.51636 3.17727 4.26361 3.17727 3.95182V2.82273Z"
                                                    fill="#ffffff"></path>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12 col-sm-6 col-12">
                            <div class="show-products-content">
                                <div class="show-products-main">
                                    <div class="section-title">
                                        <h3> <b>Fall in love with </b> <br> amazing clothes </h3>
                                    </div>
                                    <p> Lorem Ipsum is simply dummy text of the printing and typesetting
                                        industry. Lorem Ipsum has been the industry's standard dummy. </p>
                                    <a href="cart.html" class="addtocart-btn btn btn-grey" tabindex="0">
                                        <span> Add to cart</span>
                                        <svg width="13" height="13" viewBox="0 0 13 13" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M9.24173 3.38727H2.75827C2.10699 3.38727 1.59075 3.93678 1.63138 4.58679L1.91365 9.10315C1.95084 9.69822 2.44431 10.1618 3.04054 10.1618H8.95946C9.55569 10.1618 10.0492 9.69822 10.0863 9.10315L10.3686 4.58679C10.4092 3.93678 9.89301 3.38727 9.24173 3.38727ZM2.75827 2.25818C1.4557 2.25818 0.423236 3.35719 0.504488 4.65722L0.78676 9.17358C0.861144 10.3637 1.84808 11.2909 3.04054 11.2909H8.95946C10.1519 11.2909 11.1389 10.3637 11.2132 9.17358L11.4955 4.65722C11.5768 3.35719 10.5443 2.25818 9.24173 2.25818H2.75827Z"
                                                fill="#ffffff"></path>
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M3.17727 2.82273C3.17727 1.26378 4.44105 0 6 0C7.55895 0 8.82273 1.26378 8.82273 2.82273V3.95182C8.82273 4.26361 8.56997 4.51636 8.25818 4.51636C7.94639 4.51636 7.69363 4.26361 7.69363 3.95182V2.82273C7.69363 1.88736 6.93537 1.12909 6 1.12909C5.06463 1.12909 4.30636 1.88736 4.30636 2.82273V3.95182C4.30636 4.26361 4.05361 4.51636 3.74182 4.51636C3.43003 4.51636 3.17727 4.26361 3.17727 3.95182V2.82273Z"
                                                fill="#ffffff"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-12">
                <div class="show-products-right-inner">
                    <div class="row align-items-center res-row-reverse">
                        <div class="col-lg-6 col-md-12 col-sm-6 col-12 bestseller-item product-card">
                            <div class="product-card-inner">
                                <div class="product-card-image">
                                    <a href="product.html">
                                        <img src="{{ asset('themes/' . $currentTheme . '/assets/images/best-pro1.png') }}" class="default-img">
                                        <img src="{{ asset('themes/' . $currentTheme . '/assets/images/best-pro1-hover.png') }}" class="hover-img">
                                    </a>
                                    <div class="new-labl">
                                        -20%
                                    </div>
                                    <div class="like-items-icon">
                                        <a href="javascript:;">
                                            <svg width="12" height="10" viewBox="0 0 12 10" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M6.11593 2.07337C5.92621 2.26388 5.62665 2.26388 5.43693 2.07337L5.09742 1.73247C4.70005 1.33346 4.16322 1.08982 3.56995 1.08982C2.35134 1.08982 1.36347 2.12348 1.36347 3.39855C1.36347 4.62096 1.99589 5.63035 2.90887 6.4597C3.82263 7.28976 4.91512 7.84026 5.56787 8.12122C5.70435 8.17997 5.84851 8.17997 5.98499 8.12122C6.63774 7.84026 7.73023 7.28976 8.64399 6.4597C9.55697 5.63035 10.1894 4.62096 10.1894 3.39855C10.1894 2.12348 9.20151 1.08982 7.98291 1.08982C7.38964 1.08982 6.85281 1.33346 6.45544 1.73247L6.11593 2.07337ZM5.77643 0.992124C5.20378 0.417112 4.42631 0.0637207 3.56995 0.0637207C1.80974 0.0637207 0.382812 1.55678 0.382812 3.39855C0.382812 6.66579 3.80057 8.47007 5.1948 9.07017C5.57028 9.23178 5.98258 9.23178 6.35806 9.07017C7.75229 8.47007 11.17 6.66579 11.17 3.39855C11.17 1.55678 9.74312 0.0637207 7.98291 0.0637207C7.12655 0.0637207 6.34908 0.417112 5.77643 0.992124Z"
                                                    fill="#12131A" />
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                                <div class="product-content text-center">
                                    <div class="product-content-top">
                                        <h3 class="product-title">
                                            <a href="product.html">
                                                Oat Hat
                                            </a>
                                        </h3>
                                        <div class="product-type">100% oat seeds</div>
                                    </div>
                                    <div
                                        class="product-content-bottom d-flex align-items-center justify-content-center">
                                        <div class="price">
                                            <ins>$29.99</ins>
                                        </div>
                                        <div
                                            class="d-flex flex-wrap text-checkbox checkbox-radio align-items-center justify-content-center w-100">
                                            <div class="checkbox">
                                                <input id="ml-1" name="radio" type="radio" value=".blue">
                                                <label for="ml-1" class="checkbox-label">XL</label>
                                            </div>
                                            <div class="checkbox">
                                                <input id="ml-2" name="radio" type="radio" value=".blue">
                                                <label for="ml-2" class="checkbox-label">XXL</label>
                                            </div>
                                        </div>
                                        <a href="cart.html" class="addtocart-btn btn btn-primary ">
                                            <span> Add to cart</span>
                                            <svg width="13" height="13" viewBox="0 0 13 13" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M9.24173 3.38727H2.75827C2.10699 3.38727 1.59075 3.93678 1.63138 4.58679L1.91365 9.10315C1.95084 9.69822 2.44431 10.1618 3.04054 10.1618H8.95946C9.55569 10.1618 10.0492 9.69822 10.0863 9.10315L10.3686 4.58679C10.4092 3.93678 9.89301 3.38727 9.24173 3.38727ZM2.75827 2.25818C1.4557 2.25818 0.423236 3.35719 0.504488 4.65722L0.78676 9.17358C0.861144 10.3637 1.84808 11.2909 3.04054 11.2909H8.95946C10.1519 11.2909 11.1389 10.3637 11.2132 9.17358L11.4955 4.65722C11.5768 3.35719 10.5443 2.25818 9.24173 2.25818H2.75827Z"
                                                    fill="#ffffff"></path>
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M3.17727 2.82273C3.17727 1.26378 4.44105 0 6 0C7.55895 0 8.82273 1.26378 8.82273 2.82273V3.95182C8.82273 4.26361 8.56997 4.51636 8.25818 4.51636C7.94639 4.51636 7.69363 4.26361 7.69363 3.95182V2.82273C7.69363 1.88736 6.93537 1.12909 6 1.12909C5.06463 1.12909 4.30636 1.88736 4.30636 2.82273V3.95182C4.30636 4.26361 4.05361 4.51636 3.74182 4.51636C3.43003 4.51636 3.17727 4.26361 3.17727 3.95182V2.82273Z"
                                                    fill="#ffffff"></path>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12 col-sm-6 col-12">
                            <div class="show-products-content">
                                <div class="show-products-main">
                                    <div class="section-title">
                                        <h3> <b>Fall in love with </b> <br> amazing clothes </h3>
                                    </div>
                                    <p>
                                        Lorem Ipsum is simply dummy text of the printing and typesetting
                                        industry. Lorem Ipsum has been the industry's standard dummy.
                                    </p>
                                    <a href="cart.html" class="addtocart-btn btn btn-grey" tabindex="0">
                                        <span> Add to cart</span>
                                        <svg width="13" height="13" viewBox="0 0 13 13" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M9.24173 3.38727H2.75827C2.10699 3.38727 1.59075 3.93678 1.63138 4.58679L1.91365 9.10315C1.95084 9.69822 2.44431 10.1618 3.04054 10.1618H8.95946C9.55569 10.1618 10.0492 9.69822 10.0863 9.10315L10.3686 4.58679C10.4092 3.93678 9.89301 3.38727 9.24173 3.38727ZM2.75827 2.25818C1.4557 2.25818 0.423236 3.35719 0.504488 4.65722L0.78676 9.17358C0.861144 10.3637 1.84808 11.2909 3.04054 11.2909H8.95946C10.1519 11.2909 11.1389 10.3637 11.2132 9.17358L11.4955 4.65722C11.5768 3.35719 10.5443 2.25818 9.24173 2.25818H2.75827Z"
                                                fill="#ffffff"></path>
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M3.17727 2.82273C3.17727 1.26378 4.44105 0 6 0C7.55895 0 8.82273 1.26378 8.82273 2.82273V3.95182C8.82273 4.26361 8.56997 4.51636 8.25818 4.51636C7.94639 4.51636 7.69363 4.26361 7.69363 3.95182V2.82273C7.69363 1.88736 6.93537 1.12909 6 1.12909C5.06463 1.12909 4.30636 1.88736 4.30636 2.82273V3.95182C4.30636 4.26361 4.05361 4.51636 3.74182 4.51636C3.43003 4.51636 3.17727 4.26361 3.17727 3.95182V2.82273Z"
                                                fill="#ffffff"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 col-12">
                            <div class="section-title show-more-text-link">
                                @if(isset($section->modern_product->section->sub_title))
                                <h3 id="{{ $section->modern_product->section->sub_title->slug ?? '' }}_preview">{!!  $section->modern_product->section->sub_title->text ?? '' !!} </h3>
                                @endif
                                @if(isset($section->modern_product->section->button))
                                <div class="banner-links">
                                    <a href="product-list.html" class="btn btn-primary white-btn"
                                        tabindex="0" id="{{ $section->modern_product->section->button->slug ?? '' }}_preview">{{  $section->modern_product->section->button->text ?? '' }}</a>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>