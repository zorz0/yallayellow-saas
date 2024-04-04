<section class="checkout-page padding-bottom padding-top  {{ $option->class_name }}" style="position: relative;@if($option->is_hide == 1) opacity: 0.5; @else opacity: 1; @endif" data-index="{{ $option->order }}" data-id="{{ $option->order }}" data-value="{{ $option->id }}" data-hide="{{ $option->is_hide  }}">
    <div class="custome_tool_bar"></div>    
    <div class="container">
        <div class="my-acc-head">
            <div class="d-flex justify-content-start back-toshop">
                <a href="#" class="back-btn">
                    <span class="svg-ic">
                        <svg xmlns="http://www.w3.org/2000/svg" width="11" height="5" viewBox="0 0 11 5"
                            fill="none">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M10.5791 2.28954C10.5791 2.53299 10.3818 2.73035 10.1383 2.73035L1.52698 2.73048L2.5628 3.73673C2.73742 3.90636 2.74146 4.18544 2.57183 4.36005C2.40219 4.53467 2.12312 4.53871 1.9485 4.36908L0.133482 2.60587C0.0480403 2.52287 -0.000171489 2.40882 -0.000171488 2.2897C-0.000171486 2.17058 0.0480403 2.05653 0.133482 1.97353L1.9485 0.210321C2.12312 0.0406877 2.40219 0.044729 2.57183 0.219347C2.74146 0.393966 2.73742 0.673036 2.5628 0.842669L1.52702 1.84888L10.1383 1.84875C10.3817 1.84874 10.5791 2.04609 10.5791 2.28954Z"
                                fill="white"></path>
                        </svg>
                    </span>
                    Back to Shop
                </a>
            </div>
            <div class="section-title">
                <h2>Checkout</h2>
            </div>
        </div>
        <div class="row align-items-start">
            <div class="col-lg-9 col-12">
                <div class="checkout-page-left">
                    <div class="set has-children">
                        <a href="javascript:;" class="acnav-label">
                            <span>Step 1: <b>Checkout options</b></span>
                        </a>
                        <div class="acnav-list">
                            <div class="row">
                                <div class="col-md-6 col-12 ">
                                    <h3 class="check-head">New Customer?</h3>
                                    <p>By creating an account you will be able to shop faster, be up to date on
                                        an order's status,
                                        and keep track of the orders you have previously made.</p>
                                    <div class="btn-flex d-flex align-items-center">
                                        <a href="register.html" class="btn  reg-btn">
                                            Register
                                            <svg xmlns="http://www.w3.org/2000/svg" width="35" height="14"
                                                viewBox="0 0 35 14" fill="none">
                                                <path
                                                    d="M25.0749 14L35 7L25.0805 0L29.12 6.06667H0V7.93333H29.12L25.0749 14Z">
                                                </path>
                                            </svg>
                                        </a>
                                        <a class="btn login-btn" href="login.html">
                                            Login
                                            <svg xmlns="http://www.w3.org/2000/svg" width="35" height="14"
                                                viewBox="0 0 35 14" fill="none">
                                                <path
                                                    d="M25.0749 14L35 7L25.0805 0L29.12 6.06667H0V7.93333H29.12L25.0749 14Z">
                                                </path>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <form class="login-form">
                                        <div class="form-container">
                                            <div class="form-heading">
                                                <h3>Log in</h3>
                                            </div>
                                        </div>
                                        <div class="form-container">
                                            <div class="row">
                                                <div class="col-12">
                                                    <p>I am a returning customer</p>
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group">
                                                        <label>E-mail<sup aria-hidden="true">*</sup>:</label>
                                                        <input type="email" class="form-control"
                                                            placeholder="shop@company.com" required="">
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group">
                                                        <label>Password<sup aria-hidden="true">*</sup>:</label>
                                                        <input type="text" class="form-control"
                                                            placeholder="**********" required="">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-container">
                                            <div class="row align-items-center form-footer  ">
                                                <div class="col-lg-12  col-12 d-flex align-items-center">
                                                    <button class="btn" type="submit">
                                                        Login
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="35"
                                                            height="14" viewBox="0 0 35 14" fill="none">
                                                            <path
                                                                d="M25.0749 14L35 7L25.0805 0L29.12 6.06667H0V7.93333H29.12L25.0749 14Z">
                                                            </path>
                                                        </svg>
                                                    </button>
                                                    <a href="#" class="forgot-pass">Forgot Password?</a>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="set has-children">
                        <a href="javascript:;" class="acnav-label">
                            <span>Step 2: <b>Billing details</b></span>
                        </a>
                        <div class="acnav-list">
                            <h3 class="check-head">Your Personal Details</h3>
                            <form class="personal-info-form">
                                <div class="form-container">
                                    <div class="row">
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label>First Name<sup aria-hidden="true">*</sup>:</label>
                                                <input type="text" class="form-control" placeholder="John"
                                                    required="">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label>Last Name<sup aria-hidden="true">*</sup>:</label>
                                                <input type="text" class="form-control" placeholder="Doe"
                                                    required="">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label>E-mail<sup aria-hidden="true">*</sup>:</label>
                                                <input type="email" class="form-control"
                                                    placeholder="shop@company.com" required="">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label>Telephone<sup aria-hidden="true">*</sup>:</label>
                                                <input type="number" class="form-control"
                                                    placeholder="1234567890" required="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <h3 class="check-head">Your Address</h3>
                            <form class="your-add-form">
                                <div class="form-container">
                                    <div class="row">
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label>First Name<sup aria-hidden="true">*</sup>:</label>
                                                <input type="text" class="form-control" placeholder="John"
                                                    required="">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label>Last Name<sup aria-hidden="true">*</sup>:</label>
                                                <input type="text" class="form-control" placeholder="Doe"
                                                    required="">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label>Company<sup aria-hidden="true">*</sup>:</label>
                                                <input type="text" class="form-control"
                                                    placeholder="shop@company.com" required="">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label>Address 1<sup aria-hidden="true">*</sup>:</label>
                                                <input type="text" class="form-control" placeholder="address"
                                                    required="">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label>Address 2<sup aria-hidden="true">*</sup>:</label>
                                                <input type="text" class="form-control" placeholder="address"
                                                    required="">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label>City<sup aria-hidden="true">*</sup>:</label>
                                                <input type="text" class="form-control" placeholder="city"
                                                    required="">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label>Post Code<sup aria-hidden="true">*</sup>:</label>
                                                <input type="text" class="form-control" placeholder="post code"
                                                    required="">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label>Country<sup aria-hidden="true">*</sup>:</label>
                                                <select class="form-control" style="display: none;">
                                                    <option>India</option>
                                                    <option>USA</option>
                                                    <option>Canada</option>
                                                    <option>Europe</option>
                                                </select>
                                                <div class="nice-select form-control" tabindex="0"><span
                                                        class="current">India</span>
                                                    <ul class="list">
                                                        <li data-value="India" class="option selected">India
                                                        </li>
                                                        <li data-value="USA" class="option">USA</li>
                                                        <li data-value="Canada" class="option">Canada</li>
                                                        <li data-value="Europe" class="option">Europe</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label>Region / State<sup aria-hidden="true">*</sup>:</label>
                                                <select class="form-control" style="display: none;">
                                                    <option>Up</option>
                                                    <option>Gujarat</option>
                                                    <option>Mp</option>
                                                    <option>Hp</option>
                                                </select>
                                                <div class="nice-select form-control" tabindex="0"><span
                                                        class="current">Up</span>
                                                    <ul class="list">
                                                        <li data-value="Up" class="option selected">Up</li>
                                                        <li data-value="Gujarat" class="option">Gujarat</li>
                                                        <li data-value="Mp" class="option">Mp</li>
                                                        <li data-value="Hp" class="option">Hp</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label> </label>
                                                <div class="checkbox-custom">
                                                    <input type="checkbox" id="da" checked>
                                                    <label for="da">
                                                        <span>My delivery and billing addresses are the
                                                            same.</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-container">
                                    <div class="d-flex acc-back-btn-wrp align-items-center justify-content-end">
                                        <button class="btn continue-btn" type="submit">
                                            Continue
                                            <svg xmlns="http://www.w3.org/2000/svg" width="11" height="12"
                                                viewBox="0 0 11 12" fill="none">
                                                <g clip-path="url(#down)">
                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                        d="M5.28956 0.546387C5.04611 0.546383 4.84876 0.743733 4.84875 0.987181L4.84862 9.59851L3.84237 8.56269C3.67274 8.38807 3.39367 8.38403 3.21905 8.55366C3.04443 8.72329 3.04039 9.00236 3.21002 9.17698L4.97323 10.992C5.05623 11.0774 5.17028 11.1257 5.2894 11.1257C5.40852 11.1257 5.52257 11.0774 5.60558 10.992L7.36878 9.17698C7.53841 9.00236 7.53437 8.72329 7.35975 8.55366C7.18514 8.38403 6.90606 8.38807 6.73643 8.56269L5.73022 9.59847L5.73035 0.987195C5.73036 0.743747 5.53301 0.54639 5.28956 0.546387Z"
                                                        fill="white" />
                                                </g>
                                                <defs>
                                                    <clipPath id="down">
                                                        <rect width="10.5792" height="10.5792" fill="white"
                                                            transform="translate(10.5791 0.546387) rotate(90)" />
                                                    </clipPath>
                                                </defs>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="set has-children">
                        <a href="javascript:;" class="acnav-label">
                            <span>Step 3: <b>Delivery Method </b></span>
                        </a>
                        <div class="acnav-list">
                            <h3 class="check-head">Select Your Delivery</h3>
                            <p>Please select the preferred shipping method to use on this order.</p>
                            <form class="payment-method-form">
                                <div class="radio-group">
                                    <input type="radio" id="dhsd" name="payment" checked="">
                                    <label for="dhsd">
                                        <span><b>DHL</b> Delivery</span>
                                        <div class="center-descrp">
                                            Please select the preferred payment method to use on this order.
                                        </div>
                                        <div class="radio-right">
                                            <p>Price:</p>
                                            <b>$5.00</b>
                                            <img src="{{ asset('themes/' . $currentTheme . '/assets/images/dhl_logo-1.png') }}" alt="dhl">
                                        </div>
                                    </label>
                                </div>
                                <div class="radio-group">
                                    <input type="radio" id="ship2" name="payment">
                                    <label for="ship2">
                                        <span><b>Flat</b> Shipping Rate</span>
                                        <div class="center-descrp">
                                            Please select the preferred shipping method to use on this order.
                                        </div>
                                        <div class="radio-right">
                                            <p>Price:</p>
                                            <b>$5.00</b>
                                            <img src="{{ asset('themes/' . $currentTheme . '/assets/images/truck.png') }}" alt="dhl">
                                        </div>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label>Add Comments About Your Order:</label>
                                    <textarea class="form-control" name="message" placeholder="Description"
                                        rows="8"></textarea>
                                </div>
                                <div class="form-container">
                                    <div class="d-flex acc-back-btn-wrp align-items-center justify-content-end">
                                        <button class="btn continue-btn" type="submit">
                                            Continue
                                            <svg xmlns="http://www.w3.org/2000/svg" width="11" height="12"
                                                viewBox="0 0 11 12" fill="none">
                                                <g clip-path="url(#down)">
                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                        d="M5.28956 0.546387C5.04611 0.546383 4.84876 0.743733 4.84875 0.987181L4.84862 9.59851L3.84237 8.56269C3.67274 8.38807 3.39367 8.38403 3.21905 8.55366C3.04443 8.72329 3.04039 9.00236 3.21002 9.17698L4.97323 10.992C5.05623 11.0774 5.17028 11.1257 5.2894 11.1257C5.40852 11.1257 5.52257 11.0774 5.60558 10.992L7.36878 9.17698C7.53841 9.00236 7.53437 8.72329 7.35975 8.55366C7.18514 8.38403 6.90606 8.38807 6.73643 8.56269L5.73022 9.59847L5.73035 0.987195C5.73036 0.743747 5.53301 0.54639 5.28956 0.546387Z"
                                                        fill="white"></path>
                                                </g>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="set has-children">
                        <a href="javascript:;" class="acnav-label">
                            <span>Step 4: <b>Payments Method</b></span>
                        </a>
                        <div class="acnav-list">
                            <h3 class="check-head">Select Your Delivery</h3>
                            <p>Please select the preferred shipping method to use on this order.</p>
                            <form class="payment-method-form">
                                <div class="radio-group">
                                    <input type="radio" id="ppl" name="paypal" checked="">
                                    <label for="ppl">
                                        <span><b>PayPal</b> </span>
                                        <div class="center-descrp">
                                            Please select the preferred payment method to use on this order.
                                        </div>
                                        <div class="radio-right">
                                            <p>Additional price:</p>
                                            <b>$0.00</b>
                                            <img src="{{ asset('themes/' . $currentTheme . '/assets/images/paypal-2.png') }}" alt="paypal">
                                        </div>
                                    </label>
                                </div>
                                <div class="radio-group">
                                    <input type="radio" id="ship" name="paypal">
                                    <label for="ship">
                                        <span>Cash on Delivery</span>
                                        <div class="center-descrp">
                                            Please select the preferred payment method to use on this order.
                                        </div>
                                        <div class="radio-right">
                                            <p>Additional price:</p>
                                            <b>$1.00</b>
                                            <img src="{{ asset('themes/' . $currentTheme . '/assets/images/dhl-2.png') }}" alt="dhl">
                                        </div>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label>Add Comments About Your Order:</label>
                                    <textarea class="form-control" name="message" placeholder="Description"
                                        rows="8"></textarea>
                                </div>
                                <div class="form-container">
                                    <div class="d-flex acc-back-btn-wrp align-items-center justify-content-end">
                                        <div class="checkbox-custom">
                                            <input type="checkbox" id="agg">
                                            <label for="agg">
                                                <span>I have read and agree to the <a
                                                        href="privacy-policy.html">Terms &amp; Conditions.</a>
                                                </span>
                                            </label>
                                        </div>
                                        <button class="btn continue-btn" type="submit">
                                            Continue
                                            <svg xmlns="http://www.w3.org/2000/svg" width="11" height="12"
                                                viewBox="0 0 11 12" fill="none">
                                                <g clip-path="url(#down)">
                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                        d="M5.28956 0.546387C5.04611 0.546383 4.84876 0.743733 4.84875 0.987181L4.84862 9.59851L3.84237 8.56269C3.67274 8.38807 3.39367 8.38403 3.21905 8.55366C3.04443 8.72329 3.04039 9.00236 3.21002 9.17698L4.97323 10.992C5.05623 11.0774 5.17028 11.1257 5.2894 11.1257C5.40852 11.1257 5.52257 11.0774 5.60558 10.992L7.36878 9.17698C7.53841 9.00236 7.53437 8.72329 7.35975 8.55366C7.18514 8.38403 6.90606 8.38807 6.73643 8.56269L5.73022 9.59847L5.73035 0.987195C5.73036 0.743747 5.53301 0.54639 5.28956 0.546387Z"
                                                        fill="white"></path>
                                                </g>
                                                <defs>
                                                    <clipPath id="down">
                                                        <rect width="10.5792" height="10.5792" fill="white"
                                                            transform="translate(10.5791 0.546387) rotate(90)">
                                                        </rect>
                                                    </clipPath>
                                                </defs>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="set has-children">
                        <a href="javascript:;" class="acnav-label">
                            <span>Step 5: <b> Confirm Order</b></span>
                        </a>
                        <div class="acnav-list">
                            <h3 class="check-head">Confirm order</h3>
                            <p>Please select the preferred payment method to use on this order.</p>
                            <div class="order-confirmation-body">
                                <div class="row">
                                    <div class="col-md-4 col-sm-6 col-12">
                                        <div class="order-confirm-details">
                                            <h5>Billing informations:</h5>
                                            <ul>
                                                <li>1x Sunglasses with black ($24.99)</li>
                                                <li>1x Sunglasses with black ($24.99)</li>
                                                <li>1x Sunglasses with black ($24.99)</li>
                                                <li>1x Sunglasses with black ($24.99)</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-6 col-12">
                                        <div class="order-confirm-details">
                                            <h5>Delivery informations:</h5>
                                            <p>John Doe<br>
                                                King Street 30/21<br>
                                                00-211 Bridgeshire<br>
                                                United Kingdom</p>
                                            <div class="link"><a href="tel:000-111-222">Phone: 000-111-222</a>
                                            </div>
                                            <div class="link"><a href="mailto:shop@company.com">Email:
                                                    shop@company.com</a></div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-6 col-12">
                                        <div class="order-confirm-details">
                                            <h5>Billing informations:</h5>
                                            <p>John Doe<br>
                                                King Street 30/21<br>
                                                00-211 Bridgeshire<br>
                                                United Kingdom</p>
                                            <div class="link"><a href="tel:000-111-222">Phone: 000-111-222</a>
                                            </div>
                                            <div class="link"><a href="mailto:shop@company.com">Email:
                                                    shop@company.com</a></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="order-payment-box">
                                <div class="order-paymentcol">
                                    <div class="order-paycol-inner">
                                        <p>Payment method:</p>
                                        <img src="{{ asset('themes/' . $currentTheme . '/assets/images/paypal.png') }}" alt="paypal">
                                    </div>
                                </div>
                                <div class="order-paymentcol">
                                    <div class="order-paycol-inner">
                                        <p>Delivery method</p>
                                        <img src="{{ asset('themes/' . $currentTheme . '/assets/images/dhl.png') }}" alt="dhl">
                                    </div>
                                </div>
                                <div class="order-paymentcol">
                                    <div class="order-paycol-inner">
                                        <div
                                            class="d-flex align-items-center justify-content-between payment-ttl-row">
                                            <div class="payment-ttl-left">
                                                <span>
                                                    Sub-total:
                                                    <b> $290.00</b>
                                                </span>
                                                <span>
                                                    VAT (20%)
                                                    <b>$41.30</b>
                                                </span>
                                            </div>
                                            <div class="payment-ttl-left">
                                                <h5>Total:</h5>
                                                <div class="ttl-pric">
                                                    $2,107.00
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-container">
                                <div class="d-flex acc-back-btn-wrp align-items-center justify-content-end">
                                    <button class="btn continue-btn" type="submit">
                                        Confirm Order
                                        <svg xmlns="http://www.w3.org/2000/svg" width="35" height="14"
                                            viewBox="0 0 35 14" fill="none">
                                            <path
                                                d="M25.0749 14L35 7L25.0805 0L29.12 6.06667H0V7.93333H29.12L25.0749 14Z">
                                            </path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-12">
                <div class="checkout-page-right">
                    <div class="mini-cart-header">
                        <h4>My Cart:<span class="checkout-cartcount">[123]</span></h4>
                    </div>
                    <div id="cart-body" class="mini-cart-has-item">
                        <div class="mini-cart-body">
                            <div class="mini-cart-item">
                                <div class="mini-cart-image">
                                    <a href="product.html">
                                        <img src="{{ asset('themes/' . $currentTheme . '/assets/images/cart.png') }}" alt="img">
                                        <span>1</span>
                                    </a>
                                </div>
                                <div class="mini-cart-details">
                                    <p class="mini-cart-title"><a href="product.html">Chance chanel</a></p>
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
                                    <div class="pvarprice d-flex align-items-center justify-content-between">
                                        <div class="price">
                                            <ins>$69 </ins><del>$89</del>
                                        </div>
                                        <a class="remove_item" title="Remove item" href="#">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                viewBox="0 0 20 20" fill="none">
                                                <path
                                                    d="M12.7589 7.24609C12.5002 7.24609 12.2905 7.45577 12.2905 7.71448V16.5669C12.2905 16.8255 12.5002 17.0353 12.7589 17.0353C13.0176 17.0353 13.2273 16.8255 13.2273 16.5669V7.71448C13.2273 7.45577 13.0176 7.24609 12.7589 7.24609Z"
                                                    fill="#61AFB3"></path>
                                                <path
                                                    d="M7.23157 7.24609C6.97286 7.24609 6.76318 7.45577 6.76318 7.71448V16.5669C6.76318 16.8255 6.97286 17.0353 7.23157 17.0353C7.49028 17.0353 7.69995 16.8255 7.69995 16.5669V7.71448C7.69995 7.45577 7.49028 7.24609 7.23157 7.24609Z"
                                                    fill="#61AFB3"></path>
                                                <path
                                                    d="M3.20333 5.95419V17.4942C3.20333 18.1762 3.45344 18.8168 3.89035 19.2764C4.32525 19.7373 4.93049 19.9989 5.56391 20H14.4259C15.0594 19.9989 15.6647 19.7373 16.0994 19.2764C16.5363 18.8168 16.7864 18.1762 16.7864 17.4942V5.95419C17.6549 5.72366 18.2177 4.8846 18.1016 3.99339C17.9852 3.10236 17.2261 2.43583 16.3274 2.43565H13.9293V1.85017C13.932 1.35782 13.7374 0.885049 13.3888 0.537238C13.0403 0.18961 12.5668 -0.00396362 12.0744 6.15416e-05H7.91533C7.42298 -0.00396362 6.94948 0.18961 6.60093 0.537238C6.25239 0.885049 6.05772 1.35782 6.06046 1.85017V2.43565H3.66238C2.76367 2.43583 2.00456 3.10236 1.8882 3.99339C1.77202 4.8846 2.33481 5.72366 3.20333 5.95419ZM14.4259 19.0632H5.56391C4.76308 19.0632 4.14009 18.3753 4.14009 17.4942V5.99536H15.8497V17.4942C15.8497 18.3753 15.2267 19.0632 14.4259 19.0632ZM6.99723 1.85017C6.99412 1.60628 7.08999 1.37154 7.26307 1.19938C7.43597 1.02721 7.67126 0.932619 7.91533 0.936827H12.0744C12.3185 0.932619 12.5538 1.02721 12.7267 1.19938C12.8998 1.37136 12.9956 1.60628 12.9925 1.85017V2.43565H6.99723V1.85017ZM3.66238 3.37242H16.3274C16.793 3.37242 17.1705 3.74987 17.1705 4.21551C17.1705 4.68114 16.793 5.05859 16.3274 5.05859H3.66238C3.19674 5.05859 2.81929 4.68114 2.81929 4.21551C2.81929 3.74987 3.19674 3.37242 3.66238 3.37242Z"
                                                    fill="#61AFB3"></path>
                                                <path
                                                    d="M9.99523 7.24609C9.73653 7.24609 9.52686 7.45577 9.52686 7.71448V16.5669C9.52686 16.8255 9.73653 17.0353 9.99523 17.0353C10.2539 17.0353 10.4636 16.8255 10.4636 16.5669V7.71448C10.4636 7.45577 10.2539 7.24609 9.99523 7.24609Z"
                                                    fill="#61AFB3"></path>
                                                <defs>
                                                    <clipPath>
                                                        <rect width="20" height="20" fill="white"></rect>
                                                    </clipPath>
                                                </defs>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="mini-cart-item">
                                <div class="mini-cart-image">
                                    <a href="product.html">
                                        <img src="{{ asset('themes/' . $currentTheme . '/assets/images/cart.png') }}" alt="img">
                                        <span>1</span>
                                    </a>
                                </div>
                                <div class="mini-cart-details">
                                    <p class="mini-cart-title"><a href="product.html">Chance chanel</a></p>
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
                                    <div class="pvarprice d-flex align-items-center justify-content-between">
                                        <div class="price">
                                            <ins>$69 </ins><del>$89</del>
                                        </div>
                                        <a class="remove_item" title="Remove item" href="#">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                viewBox="0 0 20 20" fill="none">
                                                <path
                                                    d="M12.7589 7.24609C12.5002 7.24609 12.2905 7.45577 12.2905 7.71448V16.5669C12.2905 16.8255 12.5002 17.0353 12.7589 17.0353C13.0176 17.0353 13.2273 16.8255 13.2273 16.5669V7.71448C13.2273 7.45577 13.0176 7.24609 12.7589 7.24609Z"
                                                    fill="#61AFB3"></path>
                                                <path
                                                    d="M7.23157 7.24609C6.97286 7.24609 6.76318 7.45577 6.76318 7.71448V16.5669C6.76318 16.8255 6.97286 17.0353 7.23157 17.0353C7.49028 17.0353 7.69995 16.8255 7.69995 16.5669V7.71448C7.69995 7.45577 7.49028 7.24609 7.23157 7.24609Z"
                                                    fill="#61AFB3"></path>
                                                <path
                                                    d="M3.20333 5.95419V17.4942C3.20333 18.1762 3.45344 18.8168 3.89035 19.2764C4.32525 19.7373 4.93049 19.9989 5.56391 20H14.4259C15.0594 19.9989 15.6647 19.7373 16.0994 19.2764C16.5363 18.8168 16.7864 18.1762 16.7864 17.4942V5.95419C17.6549 5.72366 18.2177 4.8846 18.1016 3.99339C17.9852 3.10236 17.2261 2.43583 16.3274 2.43565H13.9293V1.85017C13.932 1.35782 13.7374 0.885049 13.3888 0.537238C13.0403 0.18961 12.5668 -0.00396362 12.0744 6.15416e-05H7.91533C7.42298 -0.00396362 6.94948 0.18961 6.60093 0.537238C6.25239 0.885049 6.05772 1.35782 6.06046 1.85017V2.43565H3.66238C2.76367 2.43583 2.00456 3.10236 1.8882 3.99339C1.77202 4.8846 2.33481 5.72366 3.20333 5.95419ZM14.4259 19.0632H5.56391C4.76308 19.0632 4.14009 18.3753 4.14009 17.4942V5.99536H15.8497V17.4942C15.8497 18.3753 15.2267 19.0632 14.4259 19.0632ZM6.99723 1.85017C6.99412 1.60628 7.08999 1.37154 7.26307 1.19938C7.43597 1.02721 7.67126 0.932619 7.91533 0.936827H12.0744C12.3185 0.932619 12.5538 1.02721 12.7267 1.19938C12.8998 1.37136 12.9956 1.60628 12.9925 1.85017V2.43565H6.99723V1.85017ZM3.66238 3.37242H16.3274C16.793 3.37242 17.1705 3.74987 17.1705 4.21551C17.1705 4.68114 16.793 5.05859 16.3274 5.05859H3.66238C3.19674 5.05859 2.81929 4.68114 2.81929 4.21551C2.81929 3.74987 3.19674 3.37242 3.66238 3.37242Z"
                                                    fill="#61AFB3"></path>
                                                <path
                                                    d="M9.99523 7.24609C9.73653 7.24609 9.52686 7.45577 9.52686 7.71448V16.5669C9.52686 16.8255 9.73653 17.0353 9.99523 17.0353C10.2539 17.0353 10.4636 16.8255 10.4636 16.5669V7.71448C10.4636 7.45577 10.2539 7.24609 9.99523 7.24609Z"
                                                    fill="#61AFB3"></path>
                                                <defs>
                                                    <clipPath>
                                                        <rect width="20" height="20" fill="white"></rect>
                                                    </clipPath>
                                                </defs>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="mini-cart-item">
                                <div class="mini-cart-image">
                                    <a href="product.html">
                                        <img src="{{ asset('themes/' . $currentTheme . '/assets/images/cart.png') }}" alt="img">
                                        <span>1</span>
                                    </a>
                                </div>
                                <div class="mini-cart-details">
                                    <p class="mini-cart-title"><a href="product.html">Chance chanel</a></p>
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
                                    <div class="pvarprice d-flex align-items-center justify-content-between">
                                        <div class="price">
                                            <ins>$69 </ins><del>$89</del>
                                        </div>
                                        <a class="remove_item" title="Remove item" href="#">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                viewBox="0 0 20 20" fill="none">
                                                <path
                                                    d="M12.7589 7.24609C12.5002 7.24609 12.2905 7.45577 12.2905 7.71448V16.5669C12.2905 16.8255 12.5002 17.0353 12.7589 17.0353C13.0176 17.0353 13.2273 16.8255 13.2273 16.5669V7.71448C13.2273 7.45577 13.0176 7.24609 12.7589 7.24609Z"
                                                    fill="#61AFB3"></path>
                                                <path
                                                    d="M7.23157 7.24609C6.97286 7.24609 6.76318 7.45577 6.76318 7.71448V16.5669C6.76318 16.8255 6.97286 17.0353 7.23157 17.0353C7.49028 17.0353 7.69995 16.8255 7.69995 16.5669V7.71448C7.69995 7.45577 7.49028 7.24609 7.23157 7.24609Z"
                                                    fill="#61AFB3"></path>
                                                <path
                                                    d="M3.20333 5.95419V17.4942C3.20333 18.1762 3.45344 18.8168 3.89035 19.2764C4.32525 19.7373 4.93049 19.9989 5.56391 20H14.4259C15.0594 19.9989 15.6647 19.7373 16.0994 19.2764C16.5363 18.8168 16.7864 18.1762 16.7864 17.4942V5.95419C17.6549 5.72366 18.2177 4.8846 18.1016 3.99339C17.9852 3.10236 17.2261 2.43583 16.3274 2.43565H13.9293V1.85017C13.932 1.35782 13.7374 0.885049 13.3888 0.537238C13.0403 0.18961 12.5668 -0.00396362 12.0744 6.15416e-05H7.91533C7.42298 -0.00396362 6.94948 0.18961 6.60093 0.537238C6.25239 0.885049 6.05772 1.35782 6.06046 1.85017V2.43565H3.66238C2.76367 2.43583 2.00456 3.10236 1.8882 3.99339C1.77202 4.8846 2.33481 5.72366 3.20333 5.95419ZM14.4259 19.0632H5.56391C4.76308 19.0632 4.14009 18.3753 4.14009 17.4942V5.99536H15.8497V17.4942C15.8497 18.3753 15.2267 19.0632 14.4259 19.0632ZM6.99723 1.85017C6.99412 1.60628 7.08999 1.37154 7.26307 1.19938C7.43597 1.02721 7.67126 0.932619 7.91533 0.936827H12.0744C12.3185 0.932619 12.5538 1.02721 12.7267 1.19938C12.8998 1.37136 12.9956 1.60628 12.9925 1.85017V2.43565H6.99723V1.85017ZM3.66238 3.37242H16.3274C16.793 3.37242 17.1705 3.74987 17.1705 4.21551C17.1705 4.68114 16.793 5.05859 16.3274 5.05859H3.66238C3.19674 5.05859 2.81929 4.68114 2.81929 4.21551C2.81929 3.74987 3.19674 3.37242 3.66238 3.37242Z"
                                                    fill="#61AFB3"></path>
                                                <path
                                                    d="M9.99523 7.24609C9.73653 7.24609 9.52686 7.45577 9.52686 7.71448V16.5669C9.52686 16.8255 9.73653 17.0353 9.99523 17.0353C10.2539 17.0353 10.4636 16.8255 10.4636 16.5669V7.71448C10.4636 7.45577 10.2539 7.24609 9.99523 7.24609Z"
                                                    fill="#61AFB3"></path>
                                                <defs>
                                                    <clipPath>
                                                        <rect width="20" height="20" fill="white"></rect>
                                                    </clipPath>
                                                </defs>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="mini-cart-item">
                                <div class="mini-cart-image">
                                    <a href="product.html">
                                        <img src="{{ asset('themes/' . $currentTheme . '/assets/images/cart.png') }}" alt="img">
                                        <span>1</span>
                                    </a>
                                </div>
                                <div class="mini-cart-details">
                                    <p class="mini-cart-title"><a href="product.html">Chance chanel</a></p>
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
                                    <div class="pvarprice d-flex align-items-center justify-content-between">
                                        <div class="price">
                                            <ins>$69 </ins><del>$89</del>
                                        </div>
                                        <a class="remove_item" title="Remove item" href="#">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                viewBox="0 0 20 20" fill="none">
                                                <path
                                                    d="M12.7589 7.24609C12.5002 7.24609 12.2905 7.45577 12.2905 7.71448V16.5669C12.2905 16.8255 12.5002 17.0353 12.7589 17.0353C13.0176 17.0353 13.2273 16.8255 13.2273 16.5669V7.71448C13.2273 7.45577 13.0176 7.24609 12.7589 7.24609Z"
                                                    fill="#61AFB3"></path>
                                                <path
                                                    d="M7.23157 7.24609C6.97286 7.24609 6.76318 7.45577 6.76318 7.71448V16.5669C6.76318 16.8255 6.97286 17.0353 7.23157 17.0353C7.49028 17.0353 7.69995 16.8255 7.69995 16.5669V7.71448C7.69995 7.45577 7.49028 7.24609 7.23157 7.24609Z"
                                                    fill="#61AFB3"></path>
                                                <path
                                                    d="M3.20333 5.95419V17.4942C3.20333 18.1762 3.45344 18.8168 3.89035 19.2764C4.32525 19.7373 4.93049 19.9989 5.56391 20H14.4259C15.0594 19.9989 15.6647 19.7373 16.0994 19.2764C16.5363 18.8168 16.7864 18.1762 16.7864 17.4942V5.95419C17.6549 5.72366 18.2177 4.8846 18.1016 3.99339C17.9852 3.10236 17.2261 2.43583 16.3274 2.43565H13.9293V1.85017C13.932 1.35782 13.7374 0.885049 13.3888 0.537238C13.0403 0.18961 12.5668 -0.00396362 12.0744 6.15416e-05H7.91533C7.42298 -0.00396362 6.94948 0.18961 6.60093 0.537238C6.25239 0.885049 6.05772 1.35782 6.06046 1.85017V2.43565H3.66238C2.76367 2.43583 2.00456 3.10236 1.8882 3.99339C1.77202 4.8846 2.33481 5.72366 3.20333 5.95419ZM14.4259 19.0632H5.56391C4.76308 19.0632 4.14009 18.3753 4.14009 17.4942V5.99536H15.8497V17.4942C15.8497 18.3753 15.2267 19.0632 14.4259 19.0632ZM6.99723 1.85017C6.99412 1.60628 7.08999 1.37154 7.26307 1.19938C7.43597 1.02721 7.67126 0.932619 7.91533 0.936827H12.0744C12.3185 0.932619 12.5538 1.02721 12.7267 1.19938C12.8998 1.37136 12.9956 1.60628 12.9925 1.85017V2.43565H6.99723V1.85017ZM3.66238 3.37242H16.3274C16.793 3.37242 17.1705 3.74987 17.1705 4.21551C17.1705 4.68114 16.793 5.05859 16.3274 5.05859H3.66238C3.19674 5.05859 2.81929 4.68114 2.81929 4.21551C2.81929 3.74987 3.19674 3.37242 3.66238 3.37242Z"
                                                    fill="#61AFB3"></path>
                                                <path
                                                    d="M9.99523 7.24609C9.73653 7.24609 9.52686 7.45577 9.52686 7.71448V16.5669C9.52686 16.8255 9.73653 17.0353 9.99523 17.0353C10.2539 17.0353 10.4636 16.8255 10.4636 16.5669V7.71448C10.4636 7.45577 10.2539 7.24609 9.99523 7.24609Z"
                                                    fill="#61AFB3"></path>
                                                <defs>
                                                    <clipPath>
                                                        <rect width="20" height="20" fill="white"></rect>
                                                    </clipPath>
                                                </defs>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mini-cart-footer">
                            <div
                                class="mini-cart-footer-total-row d-flex align-items-center justify-content-between">
                                <div class="mini-total-lbl">
                                    Subtotal :
                                </div>
                                <div class="mini-total-price">
                                    $207.00
                                </div>
                            </div>
                            <div class="u-save d-flex justify-end">
                                You Save: $60.00
                            </div>
                            <button class="btn checkout-btn">
                                checkout
                                <svg xmlns="http://www.w3.org/2000/svg" width="35" height="14"
                                    viewBox="0 0 35 14" fill="none">
                                    <path
                                        d="M25.0749 14L35 7L25.0805 0L29.12 6.06667H0V7.93333H29.12L25.0749 14Z">
                                    </path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>