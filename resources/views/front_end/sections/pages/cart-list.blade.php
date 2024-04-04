@php
    $theme_name = !empty(env('DATA_INSERT_APP_THEME')) ? env('DATA_INSERT_APP_THEME') : $currentTheme;
    $is_checkout_login_required = \App\Models\Utility::GetValueByName('is_checkout_login_required', $theme_name);
@endphp
<div class="container">
    <div class="section-title">
        <a href="{{route('page.product-list',$slug)}}" class="back-btn">
            <span class="svg-ic">
                <svg xmlns="http://www.w3.org/2000/svg" width="11" height="5" viewBox="0 0 11 5" fill="none">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M10.5791 2.28954C10.5791 2.53299 10.3818 2.73035 10.1383 2.73035L1.52698 2.73048L2.5628 3.73673C2.73742 3.90636 2.74146 4.18544 2.57183 4.36005C2.40219 4.53467 2.12312 4.53871 1.9485 4.36908L0.133482 2.60587C0.0480403 2.52287 -0.000171489 2.40882 -0.000171488 2.2897C-0.000171486 2.17058 0.0480403 2.05653 0.133482 1.97353L1.9485 0.210321C2.12312 0.0406877 2.40219 0.044729 2.57183 0.219347C2.74146 0.393966 2.73742 0.673036 2.5628 0.842669L1.52702 1.84888L10.1383 1.84875C10.3817 1.84874 10.5791 2.04609 10.5791 2.28954Z"
                        fill="white"></path>
                </svg>
            </span>
            {{ __('Back to category') }}
        </a>
        <h2> {{ __('Fashion') }} <b> {{ __('category') }} </b> <span>({{ $response->data->cart_total_product }})</span></h2>
    </div>

    <div class="row">
        <div class="col-lg-9 col-12">
            <div class="order-historycontent">
                <table class="cart-tble">
                    <thead>
                        <tr>
                            <th scope="col"> {{ __('Product') }} </th>
                            <th scope="col"> {{ __('Name') }} </th>
                            <th scope="col"> {{ __('date') }} </th>
                            <th scope="col"> {{ __('quantity') }} </th>
                            <th scope="col"> {{ __('Price') }} </th>
                            <th scope="col"> {{ __('Total') }} </th>
                            <th scope="col"> {{ __('Delete') }} </th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (!empty($response->data->cart_total_product))
                            @foreach ($response->data->product_list as $item)
                            <tr>
                                <td data-label="Product">
                                    <a href="{{url($slug.'/product/'. getProductSlug($item->product_id))}}">
                                        <img src="{{asset($item->image )}}" alt="img">
                                    </a>
                                </td>

                                <td data-label="Name">
                                    <a href="{{url($slug.'/product/'. getProductSlug($item->product_id))}}">{{ $item->name }}</a>
                                    @if ($item->variant_id != 0)
                                        <div class="mt-5">
                                            {!! \App\Models\ProductVariant::variantlist($item->variant_id) !!}
                                        </div>
                                    @endif
                                </td>
                                <td data-label="date"> {{ SetDateFormat($item->cart_created) }} </td>

                                <td data-label="quantity">
                                    <div class="qty-spinner">
                                        <button type="button" class="quantity-decrement change-cart-globaly" cart-id="{{ $item->cart_id }}" quantity_type="decrease">
                                            <svg width="12" height="2" viewBox="0 0 12 2" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M0 0.251343V1.74871H12V0.251343H0Z" fill="#61AFB3">
                                                </path>
                                            </svg>
                                        </button>

                                        <input type="text" class="quantity 45_quatity" data-cke-saved-name="quantity" name="quantity" value="{{ $item->qty }}" min="01" id="cart_list_quantity{{ $item->qty }}">

                                        <button type="button" class="quantity-increment change-cart-globaly"  cart-id="{{ $item->cart_id }}" quantity_type="increase">
                                            <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M6.74868 5.25132V0H5.25132V5.25132H0V6.74868H5.25132V12H6.74868V6.74868H12V5.25132H6.74868Z" fill="#61AFB3"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                                <td data-label="Price">
                                    {{ currency_format_with_sym($item->final_price/$item->qty, $store->id, $currentTheme) ?? SetNumberFormat($item->final_price/$item->qty) }}
                                </td>
                                <td data-label="Total">
                                    {{ currency_format_with_sym($item->final_price, $store->id, $currentTheme) ?? SetNumberFormat($item->final_price) }}
                                </td>
                                <td>

                                    <button class="btn remove_item_from_cart" title="Remove item" href="JavaScript:void(0)" data-id="{{ $item->cart_id }}">
                                        <i class="ti ti-trash text-white py-1" data-bs-toggle="tooltip" title="delete"></i>
                                    </button>

                                </td>
                            </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6">
                                    <h3 class="text-center"> {{ __('You have no items in your shopping cart.') }} </h3>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>

            </div>

        </div>

        <div class="col-lg-3 col-12">
            <div class="cart-summery">
                <ul>
                    <li>
                        <span class="cart-sum-left">{{ $response->data->cart_total_product }} {{ __('item') }}</span>
                        <span class="cart-sum-right">{{  currency_format_with_sym($response->data->final_price ?? ($response->data->sub_total ?? 0), $store->id, $currentTheme) ?? SetNumberFormat($response->data->final_price ?? ($response->data->sub_total ?? 0))  }}</span>
                    </li>
                    <li>
                        <span class="cart-sum-left">{{ __('Taxes') }}: </span>
                        <span class="cart-sum-right">{{ currency_format_with_sym($response->data->tax_price, $store->id, $currentTheme) ?? SetNumberFormat($response->data->tax_price) }}</span>
                    </li>
                    <li>
                        <span class="cart-sum-left">{{ __('Discount') }}: </span>
                        <span class="cart-sum-right coupon_discount_amount">{{ currency_format_with_sym( 0, $store->id, $currentTheme) ?? SetNumberFormat(0) }}</span>
                    </li>
                    @php
                        $final = $response->data->sub_total+$response->data->tax_price;
                    @endphp
                    <li>
                        <span class="cart-sum-left">{{ __('Total') }} ({{ __('tax incl.') }})</span>
                        <span class="cart-sum-right discount_final_price">{{ currency_format_with_sym($final, $store->id, $currentTheme) ?? SetNumberFormat($final) }}</span>
                    </li>
                </ul>

                @if($is_checkout_login_required == 'on' && !auth('customers')->user())
                <a class="btn checkout-btn" href="{{ route('customer.login',['storeSlug'=>$slug]) }}">
                    {{ __('Proceed to checkout') }}
                </a>
                @else
                <a class="btn checkout-btn" href="{{ route('checkout',['storeSlug'=>$slug]) }}">
                    {{ __('Proceed to checkout') }}
                </a>
                @endif

            </div>
        </div>
    </div>
</div>


