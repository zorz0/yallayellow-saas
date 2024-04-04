<section class="cart-page-section padding-bottom {{ $option->class_name }}" style="position: relative;@if($option->is_hide == 1) opacity: 0.5; @else opacity: 1; @endif" data-index="{{ $option->order }}" data-id="{{ $option->order }}" data-value="{{ $option->id }}" data-hide="{{ $option->is_hide  }}">
    <div class="custome_tool_bar"></div>    
    <div class="container">
        <div class="section-title">
            <a href="product-list.html" class="back-btn">
                <span class="svg-ic">
                    <svg xmlns="http://www.w3.org/2000/svg" width="11" height="5" viewBox="0 0 11 5"
                        fill="none">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M10.5791 2.28954C10.5791 2.53299 10.3818 2.73035 10.1383 2.73035L1.52698 2.73048L2.5628 3.73673C2.73742 3.90636 2.74146 4.18544 2.57183 4.36005C2.40219 4.53467 2.12312 4.53871 1.9485 4.36908L0.133482 2.60587C0.0480403 2.52287 -0.000171489 2.40882 -0.000171488 2.2897C-0.000171486 2.17058 0.0480403 2.05653 0.133482 1.97353L1.9485 0.210321C2.12312 0.0406877 2.40219 0.044729 2.57183 0.219347C2.74146 0.393966 2.73742 0.673036 2.5628 0.842669L1.52702 1.84888L10.1383 1.84875C10.3817 1.84874 10.5791 2.04609 10.5791 2.28954Z"
                            fill="white"></path>
                    </svg>
                </span>
                Back to category
            </a>
            <h2> <b>Shopping</b> Cart <span>(125)</span></h2>
        </div>
        <div class="row">
            <div class="col-lg-9 col-12">
                <div class="order-historycontent">
                    <table class="cart-tble">
                        <thead>
                            <tr>
                                <th scope="col">Product</th>
                                <th scope="col">Name</th>
                                <th scope="col">date</th>
                                <th scope="col">quantity</th>
                                <th scope="col">Price</th>
                                <th scope="col">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td data-label="Product">
                                    <a href="product.html" class="pro-img-cart">
                                        <img src="{{ asset('themes/' . $currentTheme . '/assets/images/cart.png') }}">
                                    </a>
                                </td>
                                <td data-label="Name">
                                    <a href="product.html">Oat Hat</a>
                                </td>
                                <td data-label="date">11/08/22</td>
                                <td data-label="quantity">
                                    <div class="qty-spinner">
                                        <button type="button" class="quantity-decrement ">
                                            <svg width="12" height="2" viewBox="0 0 12 2" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path d="M0 0.251343V1.74871H12V0.251343H0Z" fill="#61AFB3">
                                                </path>
                                            </svg>
                                        </button>
                                        <input type="text" class="quantity" data-cke-saved-name="quantity"
                                            name="quantity" value="01" min="01" max="100">
                                        <button type="button" class="quantity-increment ">
                                            <svg width="12" height="12" viewBox="0 0 12 12" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M6.74868 5.25132V0H5.25132V5.25132H0V6.74868H5.25132V12H6.74868V6.74868H12V5.25132H6.74868Z"
                                                    fill="#61AFB3"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                                <td data-label="Price">
                                    $2,100.00
                                </td>
                                <td data-label="Total">
                                    $2,107.00
                                    <a href="javascript:;" class="remove-btn">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"
                                            aria-hidden="true" focusable="false" role="presentation"
                                            class="icon icon-remove">
                                            <path
                                                d="M14 3h-3.53a3.07 3.07 0 00-.6-1.65C9.44.82 8.8.5 8 .5s-1.44.32-1.87.85A3.06 3.06 0 005.53 3H2a.5.5 0 000 1h1.25v10c0 .28.22.5.5.5h8.5a.5.5 0 00.5-.5V4H14a.5.5 0 000-1zM6.91 1.98c.23-.29.58-.48 1.09-.48s.85.19 1.09.48c.2.24.3.6.36 1.02h-2.9c.05-.42.17-.78.36-1.02zm4.84 11.52h-7.5V4h7.5v9.5z"
                                                fill="currentColor"></path>
                                            <path
                                                d="M6.55 5.25a.5.5 0 00-.5.5v6a.5.5 0 001 0v-6a.5.5 0 00-.5-.5zM9.45 5.25a.5.5 0 00-.5.5v6a.5.5 0 001 0v-6a.5.5 0 00-.5-.5z"
                                                fill="currentColor"></path>
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td data-label="Product">
                                    <a href="product.html" class="pro-img-cart">
                                        <img src="{{ asset('themes/' . $currentTheme . '/assets/images/cart.png') }}">
                                    </a>
                                </td>
                                <td data-label="Name">
                                    <a href="product.html">Oat Hat</a>
                                </td>
                                <td data-label="date">11/08/22</td>
                                <td data-label="quantity">
                                    <div class="qty-spinner">
                                        <button type="button" class="quantity-decrement ">
                                            <svg width="12" height="2" viewBox="0 0 12 2" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path d="M0 0.251343V1.74871H12V0.251343H0Z" fill="#61AFB3">
                                                </path>
                                            </svg>
                                        </button>
                                        <input type="text" class="quantity" data-cke-saved-name="quantity"
                                            name="quantity" value="01" min="01" max="100">
                                        <button type="button" class="quantity-increment ">
                                            <svg width="12" height="12" viewBox="0 0 12 12" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M6.74868 5.25132V0H5.25132V5.25132H0V6.74868H5.25132V12H6.74868V6.74868H12V5.25132H6.74868Z"
                                                    fill="#61AFB3"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                                <td data-label="Price">
                                    $2,100.00
                                </td>
                                <td data-label="Total">
                                    $2,107.00
                                    <a href="javascript:;" class="remove-btn">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"
                                            aria-hidden="true" focusable="false" role="presentation"
                                            class="icon icon-remove">
                                            <path
                                                d="M14 3h-3.53a3.07 3.07 0 00-.6-1.65C9.44.82 8.8.5 8 .5s-1.44.32-1.87.85A3.06 3.06 0 005.53 3H2a.5.5 0 000 1h1.25v10c0 .28.22.5.5.5h8.5a.5.5 0 00.5-.5V4H14a.5.5 0 000-1zM6.91 1.98c.23-.29.58-.48 1.09-.48s.85.19 1.09.48c.2.24.3.6.36 1.02h-2.9c.05-.42.17-.78.36-1.02zm4.84 11.52h-7.5V4h7.5v9.5z"
                                                fill="currentColor"></path>
                                            <path
                                                d="M6.55 5.25a.5.5 0 00-.5.5v6a.5.5 0 001 0v-6a.5.5 0 00-.5-.5zM9.45 5.25a.5.5 0 00-.5.5v6a.5.5 0 001 0v-6a.5.5 0 00-.5-.5z"
                                                fill="currentColor"></path>
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="coupon-table">
                        <div class="coupon-text">
                            <h4>Use Coupon Code</h4>
                            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem
                                Ipsum has been the industry's standard dummy.</p>
                        </div>
                        <div class="coupon-code">
                            <div class="coupon-code-text">
                                <h4>code:</h4>
                                <p>
                                    SJG8392SAQT
                                    <span></span>
                                </p>
                            </div>
                            <div class="coupon-btn">
                                <button type="button" class="btn btn-primary">
                                    Confirm
                                    <svg width="14" height="6" viewBox="0 0 14 6" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M3.48504e-08 3.00044C-4.9681e-06 3.32261 0.261158 3.58378 0.583324 3.58378L11.9791 3.58396L10.6083 4.91557C10.3773 5.14006 10.3719 5.50937 10.5964 5.74045C10.8209 5.97153 11.1902 5.97688 11.4213 5.75239L13.8232 3.41906C13.9362 3.30922 14 3.15829 14 3.00065C14 2.84301 13.9362 2.69208 13.8232 2.58224L11.4213 0.24891C11.1902 0.0244262 10.8209 0.0297744 10.5964 0.260855C10.3719 0.491935 10.3773 0.861243 10.6083 1.08573L11.979 2.41729L0.583343 2.41712C0.261176 2.41711 5.07257e-06 2.67827 3.48504e-08 3.00044Z"
                                            fill="#ffffff"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-12">
                <div class="cart-summery">
                    <ul>
                        <li>
                            <span class="cart-sum-left">1 item</span>
                            <span class="cart-sum-right">$48.00</span>
                        </li>
                        <li>
                            <span class="cart-sum-left">Shipping</span>
                            <span class="cart-sum-right">$7.00</span>
                        </li>
                        <li>
                            <span class="cart-sum-left">Total (tax excl.)</span>
                            <span class="cart-sum-right"> $55.00</span>
                        </li>
                        <li>
                            <span class="cart-sum-left">Total (tax incl.)</span>
                            <span class="cart-sum-right"> $55.00</span>
                        </li>
                        <li>
                            <span class="cart-sum-left">Taxes: </span>
                            <span class="cart-sum-right"> $0.00</span>
                        </li>
                    </ul>
                    <button class="btn checkout-btn">
                        Proceed to checkout
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>