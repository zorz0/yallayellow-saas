<div class="checkout-page-right">
    <div class="mini-cart-header">
        <h4>{{ __('My Cart') }}:<span class="checkout-cartcount">[{{ $response->data->cart_total_product }}]</span></h4>
    </div>
    <div id="cart-body" class="mini-cart-has-item">
        <div class="mini-cart-body">
            @if (!empty($response->data->cart_total_product))
                @foreach ($response->data->product_list as $item)
                    <div class="mini-cart-item">
                        <div class="mini-cart-image">
                            <input type="hidden" id="product_id" value="{{ $item->product_id }}" >
                            <input type="hidden" id="product_qty" value="{{ $item->qty }}">
                            <a href="{{ url($slug.'/product/'.getProductSlug($item->product_id)) }}" title="SPACE BAG">
                                <img src="{{asset($item->image )}}"alt="img">
                            </a>
                        </div>
                        <div class="mini-cart-details">
                            <p class="mini-cart-title">
                                <a href="{{ url($slug.'/product/'.getProductSlug($item->product_id)) }}">{{ $item->name }}</a>
                            </p>
                            <div class="qty-spinner">

                                <button type="button" class="quantity-decrement change-cart-globaly" cart-id="{{ $item->cart_id }}" quantity_type="decrease">
                                    <svg width="12" height="2" viewBox="0 0 12 2" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M0 0.251343V1.74871H12V0.251343H0Z" fill="#61AFB3">
                                        </path>
                                    </svg>
                                </button>

                                <input type="hidden" name="product_id" value="45">
                                <input type="text" class="quantity 45_quatity" data-cke-saved-name="quantity" name="quantity" value="{{ $item->qty }}" min="01" id="cart_quantity{{ $item->qty }}" data-id="463">

                                <button type="button" class="quantity-increment change-cart-globaly"  cart-id="{{ $item->cart_id }}" quantity_type="increase">
                                    <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M6.74868 5.25132V0H5.25132V5.25132H0V6.74868H5.25132V12H6.74868V6.74868H12V5.25132H6.74868Z" fill="#61AFB3"></path>
                                    </svg>
                                </button>

                            </div>
                            <div class="pvarprice d-flex align-items-center justify-content-between">
                                <div class="price">
                                    <ins>{{ currency_format_with_sym($item->final_price, $store->id, $currentTheme) ?? SetNumberFormat($item->final_price) }} </ins>
                                </div>
                                <button class="btn remove_item_from_cart" title="Remove item" data-id="{{ $item->cart_id }}">
                                    <i class="ti ti-trash text-white py-1" data-bs-toggle="tooltip" title="delete"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        @php
            $user = App\Models\User::where('type','admin')->first();
            if ($user->type == 'admin') {
                $plan = App\Models\Plan::find($user->plan_id);
            }
        @endphp
        @if($plan->shipping_method == 'on')
            @if(auth('customers')->user())
                <div class="chnage_address">
                    <a href="{{ route('my-account.index',$slug) }}">{{ __('Change Address')}}</a>
                </div>
            @endif

            <div class="shiping-type">
                <h5 id="shipping_lable" class="d-none">{{__('Select Shipping')}}</h5>
                <div class="radio-group change_shipping" id="shipping_location_content">

                </div>
            </div>
        @endif

        <div class="mini-cart-footer">
            <div class="u-save d-flex justify-end">
                <input class="form-control mb-10 coupon_code" placeholder="Enter coupon code" required="" name="coupon" type="text" value="" theme_id = "{{ $currentTheme }}">

                <button class="btn checkout-btn apply_coupon">
                    {{ __('Apply Coupon') }}
                    <svg xmlns="http://www.w3.org/2000/svg" width="35" height="14"
                        viewBox="0 0 35 14" fill="none">
                        <path d="M25.0749 14L35 7L25.0805 0L29.12 6.06667H0V7.93333H29.12L25.0749 14Z">
                        </path>
                    </svg>
                </button>
            </div>

            <div class="mini-cart-footer-total-row d-flex align-items-center justify-content-between">
                <div class="mini-total-lbl">
                    {{ __('Subtotal') }} :
                </div>
                <input type="hidden" value="{{  $response->data->final_price ?? ($response->data->sub_total ?? 0) }}" id="subtotal">
                <div class="mini-total-price subtotal">
                    {{  currency_format_with_sym(($response->data->final_price ?? ($response->data->sub_total ?? 0)), $store->id, $currentTheme) ?? SetNumberFormat($response->data->final_price ?? ($response->data->sub_total ?? 0)) }}
                </div>
            </div>
            @if($plan->shipping_method == 'on')
                <div class="u-save d-flex justify-end">
                    {{ __('Shipping') }} : <span class="CURRENCY"></span> <span class="shipping_final_price"> - {{ currency_format_with_sym(0, $store->id, $currentTheme) ?? SetNumberFormat(0) }} </span>
                </div>
            @endif
            <input type="hidden" value="{{ $response->data->total_coupon_price ?? 0 }}" id="coupon_amount">
            <div class="u-save d-flex justify-end">
                {{ __('Coupon') }} : <span class="discount_amount_currency"> - {{ currency_format_with_sym(($response->data->total_coupon_price ?? 0), $store->id, $currentTheme) ?? SetNumberFormat($response->data->total_coupon_price ?? 0) }} </span>
            </div>
            
            <div class="u-save d-flex justify-end">
                {{ __('Tax') }} : <span
                    @if ($plan->shipping_method == 'on') class="CURRENCY" @else class="" @endif></span>
                <span class="final_tax_price"> {{ currency_format_with_sym(($response->data->tax_price ?? 0), $store->id, $currentTheme) ?? SetNumberFormat($response->data->tax_price) }} </span>
            </div>
            <div class="u-save d-flex justify-end" >
                {{ __('Total') }} : <span @if ($plan->shipping_method == 'on') class="CURRENCY" @else class="" @endif></span> <span class="final_amount_currency shipping_total_price" final_total="{{ $response->data->total_sub_price  }}">{{ currency_format_with_sym(($response->data->total_sub_price ?? 0), $store->id, $currentTheme) ?? SetNumberFormat($response->data->total_sub_price) }}</span>
            </div>

            <button class="btn checkout-btn">
                {{ __('checkout') }}
                <svg xmlns="http://www.w3.org/2000/svg" width="35" height="14"
                    viewBox="0 0 35 14" fill="none">
                    <path d="M25.0749 14L35 7L25.0805 0L29.12 6.06667H0V7.93333H29.12L25.0749 14Z">
                    </path>
                </svg>
            </button>
        </div>
    </div>
</div>
