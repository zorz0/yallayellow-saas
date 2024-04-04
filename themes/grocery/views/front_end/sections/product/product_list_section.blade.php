<section class="product-listing-section {{ $option->class_name }}" style="position: relative;@if($option->is_hide == 1) opacity: 0.5; @else opacity: 1; @endif" data-index="{{ $option->order }}" data-id="{{ $option->order }}" data-value="{{ $option->id }}" data-hide="{{ $option->is_hide  }}">
    <div class="custome_tool_bar"></div>   
    <div class="product-heading-row">
        <div class="container">
            <div class=" row no-gutters">
                <div class="product-heading-column col-lg-3 col-md-4 col-1">
                    <div class="filter-title">
                        <h4>Filters</h4>
                        <div class="filter-ic">
                            <svg class="icon icon-filter" aria-hidden="true" focusable="false"
                                role="presentation" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                fill="none">
                                <path fill-rule="evenodd"
                                    d="M4.833 6.5a1.667 1.667 0 1 1 3.334 0 1.667 1.667 0 0 1-3.334 0ZM4.05 7H2.5a.5.5 0 0 1 0-1h1.55a2.5 2.5 0 0 1 4.9 0h8.55a.5.5 0 0 1 0 1H8.95a2.5 2.5 0 0 1-4.9 0Zm11.117 6.5a1.667 1.667 0 1 0-3.334 0 1.667 1.667 0 0 0 3.334 0ZM13.5 11a2.5 2.5 0 0 1 2.45 2h1.55a.5.5 0 0 1 0 1h-1.55a2.5 2.5 0 0 1-4.9 0H2.5a.5.5 0 0 1 0-1h8.55a2.5 2.5 0 0 1 2.45-2Z"
                                    fill="currentColor"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="product-heading-right-column col-lg-9 col-md-8 col-11">
                    <div class="product-sorting-row d-flex align-items-center justify-content-between">
                        <ul class="produdt-filter-cat d-flex align-items-center">
                            <li><a href="#">Categories</a></li>
                            <li><a href="#">Men's</a></li>
                            <li><a href="#">Pants</a></li>
                        </ul>
                        <div class="filter-select-box d-flex align-items-center justify-content-end">
                            <span class="sort-lbl">Sort by:</span>
                            <select>
                                <option value="manual">Featured</option>
                                <option value="best-selling" selected="selected">Best selling</option>
                                <option value="title-ascending">Alphabetically, A-Z</option>
                                <option value="title-descending">Alphabetically, Z-A</option>
                                <option value="price-ascending">Price, low to high</option>
                                <option value="price-descending">Price, high to low</option>
                                <option value="created-ascending">Date, old to new</option>
                                <option value="created-descending">Date, new to old</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="product-list-row row no-gutters">
            <div class="product-filter-column col-lg-3 col-md-4 col-12">
                <div class="product-filter-body">
                    <div class="mobile-only close-filter">
                        <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 50 50"
                            fill="none">
                            <path
                                d="M27.7618 25.0008L49.4275 3.33503C50.1903 2.57224 50.1903 1.33552 49.4275 0.572826C48.6647 -0.189868 47.428 -0.189965 46.6653 0.572826L24.9995 22.2386L3.33381 0.572826C2.57102 -0.189965 1.3343 -0.189965 0.571605 0.572826C-0.191089 1.33562 -0.191186 2.57233 0.571605 3.33503L22.2373 25.0007L0.571605 46.6665C-0.191186 47.4293 -0.191186 48.666 0.571605 49.4287C0.952952 49.81 1.45285 50.0007 1.95275 50.0007C2.45266 50.0007 2.95246 49.81 3.3339 49.4287L24.9995 27.763L46.6652 49.4287C47.0465 49.81 47.5464 50.0007 48.0463 50.0007C48.5462 50.0007 49.046 49.81 49.4275 49.4287C50.1903 48.6659 50.1903 47.4292 49.4275 46.6665L27.7618 25.0008Z"
                                fill="white"></path>
                        </svg>
                    </div>
                    <div class="product-widget product-cat-widget">
                        <div class="pro-itm has-children">
                            <a href="javascript:;" class="acnav-label">
                                products
                            </a>
                            <ul class="pro-itm-inner acnav-list">
                                <li class="filter-listing">
                                    <ul>
                                        <li class="has-children">
                                            <a href="javascript:;" class="acnav-label">new</a>
                                            <ul class="acnav-list">
                                                <li>
                                                    <a href="#">Fedoras</a>
                                                </li>
                                                <li>
                                                    <a href="#">Flat Caps</a>
                                                </li>
                                                <li>
                                                    <a href="#">Straws</a>
                                                </li>
                                                <li>
                                                    <a href="#">Cold Weather</a>
                                                </li>
                                                <li>
                                                    <a href="#">Baseball</a>
                                                </li>
                                                <li>
                                                    <a href="#">Hat Care</a>
                                                </li>
                                                <li>
                                                    <a href="#">Facemasks</a>
                                                </li>
                                                <li>
                                                    <a href="#">Accessories</a>
                                                </li>
                                                <li>
                                                    <a href="#">Gift Cards</a>
                                                </li>
                                            </ul>
                                        </li>
                                        <li class="has-children">
                                            <a href="javascript:;" class="acnav-label">Accessories </a>
                                            <ul class="acnav-list">
                                                <li>
                                                    <a href="#">Fedoras</a>
                                                </li>
                                                <li>
                                                    <a href="#">Flat Caps</a>
                                                </li>
                                                <li>
                                                    <a href="#">Straws</a>
                                                </li>
                                            </ul>
                                        </li>
                                        <li class="has-children">
                                            <a href="javascript:;" class="acnav-label"> Sets</a>
                                            <ul class="acnav-list">
                                                <li>
                                                    <a href="#">Fedoras</a>
                                                </li>
                                                <li>
                                                    <a href="#">Flat Caps</a>
                                                </li>
                                                <li>
                                                    <a href="#">Straws</a>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="product-widget product-tag-widget">
                        <div class="pro-itm has-children">
                            <a href="javascript:;" class="acnav-label">
                                tags
                            </a>
                            <div class="pro-itm-inner acnav-list">
                                <div class="d-flex flex-wrap text-checkbox">
                                    <div class="checkbox">
                                        <input id="checkbox-1" name="radio" type="checkbox" value=".blue">
                                        <label for="checkbox-1" class="checkbox-label">Accessories</label>
                                    </div>
                                    <div class="checkbox">
                                        <input id="checkbox-2" name="radio" type="checkbox" value=".blue">
                                        <label for="checkbox-2" class="checkbox-label">Glasses</label>
                                    </div>
                                    <div class="checkbox">
                                        <input id="checkbox-3" name="radio" type="checkbox" value=".blue">
                                        <label for="checkbox-3" class="checkbox-label">Glasses</label>
                                    </div>
                                    <div class="checkbox">
                                        <input id="checkbox-4" name="radio" type="checkbox" value=".blue">
                                        <label for="checkbox-4" class="checkbox-label">Glasses</label>
                                    </div>
                                </div>
                                <div class="delete-all">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="8" height="8"
                                        viewBox="0 0 8 8" fill="none">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M6.43285 1.01177C6.59919 0.845434 6.86887 0.845434 7.03521 1.01177C7.20154 1.1781 7.20154 1.44779 7.03521 1.61412L4.62579 4.02354L7.03521 6.43295C7.20154 6.59929 7.20154 6.86897 7.03521 7.03531C6.86887 7.20164 6.59919 7.20164 6.43285 7.03531L4.02344 4.62589L1.61402 7.03531C1.44769 7.20164 1.178 7.20164 1.01167 7.03531C0.845333 6.86897 0.845333 6.59929 1.01167 6.43295L3.42108 4.02354L1.01167 1.61412C0.845333 1.44779 0.845332 1.17811 1.01167 1.01177C1.178 0.845434 1.44769 0.845434 1.61402 1.01177L4.02344 3.42119L6.43285 1.01177Z"
                                            fill="#183A40" />
                                    </svg>
                                    Delete all
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="product-widget product-price-widget">
                        <div class="pro-itm has-children">
                            <a href="javascript:;" class="acnav-label">
                                price
                            </a>
                            <div class="pro-itm-inner acnav-list">
                                <div class="price-select d-flex">
                                    <div class="select-col">
                                        <p>min price:</p>
                                        <select>
                                            <option value="$ 100">$ 100</option>
                                            <option value="$ 200">$ 200</option>
                                            <option value="$ 300">$ 300</option>
                                            <option value="$ 400">$ 400</option>
                                        </select>
                                    </div>
                                    <div class="select-col">
                                        <p>max price:</p>
                                        <select>
                                            <option value="$ 500">$ 500</option>
                                            <option value="$ 600">$ 600</option>
                                            <option value="$ 700">$ 700</option>
                                            <option value="$ 800">$ 800</option>
                                        </select>
                                    </div>
                                </div>
                                <div id="range-slider">
                                    <div id="slider-range"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="product-widget product-colors-widget">
                        <div class="pro-itm has-children">
                            <a href="javascript:;" class="acnav-label">
                                Colors
                            </a>
                            <div class="pro-itm-inner acnav-list">
                                <div class="colors-checkbox">
                                    <label class="check-label" for="color1">
                                        <span class="custom-checkbox">
                                            <input id="color1" type="checkbox">
                                            <span class="color" style="background-color:#CFC4A6;"></span>
                                        </span>
                                        <div class="color-name">
                                            Taupe
                                            <span class="color-count">(1)</span>
                                        </div>
                                    </label>
                                </div>
                                <div class="colors-checkbox">
                                    <label class="check-label" for="color2">
                                        <span class="custom-checkbox">
                                            <input id="color2" type="checkbox">
                                            <span class="color" style="background-color:#f5f5dc;"></span>
                                        </span>
                                        <div class="color-name">
                                            Beige
                                            <span class="color-count">(1)</span>
                                        </div>
                                    </label>
                                </div>
                                <div class="colors-checkbox">
                                    <label class="check-label" for="color3">
                                        <span class="custom-checkbox">
                                            <input id="color3" type="checkbox">
                                            <span class="color" style="background-color:#faebd7;"></span>
                                        </span>
                                        <div class="color-name">
                                            Off White
                                            <span class="color-count">(4)</span>
                                        </div>
                                    </label>
                                </div>
                                <div class="colors-checkbox">
                                    <label class="check-label" for="color4">
                                        <span class="custom-checkbox">
                                            <input id="color4" type="checkbox">
                                            <span class="color" style="background-color:#E84C3D;"></span>
                                        </span>
                                        <div class="color-name">
                                            Red
                                            <span class="color-count">(15)</span>
                                        </div>
                                    </label>
                                </div>
                                <div class="colors-checkbox">
                                    <label class="check-label" for="color5">
                                        <span class="custom-checkbox">
                                            <input id="color5" type="checkbox">
                                            <span class="color" style="background-color:#F39C11;"></span>
                                        </span>
                                        <div class="color-name">
                                            Orange
                                            <span class="color-count">(10)</span>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="product-filter-right-column col-lg-9 col-md-8 col-12">
                <div class="row">
                    <div class="col-lg-4 col-md-6 col-sm-6 col-12  bestseller-item product-card">
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
                    <div class="col-lg-4 col-md-6 col-sm-6 col-12  bestseller-item product-card">
                        <div class="product-card-inner">
                            <div class="product-card-image">
                                <a href="product.html">
                                    <img src="{{ asset('themes/' . $currentTheme . '/assets/images/best-pro2.png') }}" class="default-img">
                                    <img src="{{ asset('themes/' . $currentTheme . '/assets/images/best-pro2-hover.png') }}" class="hover-img">
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
                                            Navy Dress
                                        </a>
                                    </h3>
                                    <div class="product-type">100% Polyester</div>
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
                    <div class="col-lg-4 col-md-6 col-sm-6 col-12  bestseller-item product-card">
                        <div class="product-card-inner">
                            <div class="product-card-image">
                                <a href="product.html">
                                    <img src="{{ asset('themes/' . $currentTheme . '/assets/images/best-pro3.png') }}" class="default-img">
                                    <img src="{{ asset('themes/' . $currentTheme . '/assets/images/best-pro3-hover.png') }}" class="hover-img">
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
                                            Skirt
                                        </a>
                                    </h3>
                                    <div class="product-type">100% Cotton</div>
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
                    <div class="col-lg-4 col-md-6 col-sm-6 col-12  bestseller-item product-card">
                        <div class="product-card-inner">
                            <div class="product-card-image">
                                <a href="product.html">
                                    <img src="{{ asset('themes/' . $currentTheme . '/assets/images/best-pro4.png') }}" class="default-img">
                                    <img src="{{ asset('themes/' . $currentTheme . '/assets/images/best-pro4-hover.png') }}" class="hover-img">
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
                                            Shoes
                                        </a>
                                    </h3>
                                    <div class="product-type">100% Leather</div>
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
                    <div class="col-lg-4 col-md-6 col-sm-6 col-12  bestseller-item product-card">
                        <div class="product-card-inner">
                            <div class="product-card-image">
                                <a href="product.html">
                                    <img src="{{ asset('themes/' . $currentTheme . '/assets/images/best-pro3.png') }}" class="default-img">
                                    <img src="{{ asset('themes/' . $currentTheme . '/assets/images/best-pro3-hover.png') }}" class="hover-img">
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
                                            Skirt
                                        </a>
                                    </h3>
                                    <div class="product-type">100% Cotton</div>
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
                </div>
            </div>
        </div>
    </div>
</section>