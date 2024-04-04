<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cookie;
use Session;
use App\Http\Controllers\CartController;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id', 'product_id', 'variant_id', 'qty', 'price', 'theme_id'
    ];

    public function product_data()
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }

    public function variant_data()
    {
        return $this->hasOne(ProductVariant::class, 'id', 'variant_id');
    }

    public static function addtocart_cookie($product_id = 0, $variant_id = 0, $qty = 0, $theme_id, $store_id)
    {
        // $theme_id = APP_THEME();
        // $store_id = getCurrentStore();
        $settings = Utility::Seting();
        $final_price = 0;
        $cart_count = 0;
        $product = Product::find($product_id);
        if (!empty($variant_id) || $variant_id != 0) {
            $ProductVariant = ProductVariant::where('id', $variant_id)
                ->where('product_id', $product_id)
                ->first();
            $product->setAttribute('variantId', $variant_id);
            $variationOptions = explode(',', $ProductVariant->variation_option);
            $option = in_array('manage_stock', $variationOptions);
            if ($option  == true) {
                $stock = !empty($ProductVariant->stock) ? $ProductVariant->stock : 0;
                if (empty($ProductVariant)) {
                    return Utility::error(['message' => 'Product not found.']);
                } else {
                    if ($stock <= $settings['out_of_stock_threshold'] && $ProductVariant->stock_order_status == 'not_allow') {
                        return Utility::error(['message' => 'Product has out of stock.']);
                    }
                }
            } else {
                $stock = !empty($ProductVariant->stock) ? $ProductVariant->stock : $product->product_stock;
                if (empty($ProductVariant)) {
                    return Utility::error(['message' => 'Product not found.']);
                } else {
                    if ($stock <= $settings['out_of_stock_threshold'] && $product->stock_order_status == 'not_allow') {
                        return Utility::error(['message' => 'Product has out of stock.']);
                    }
                }
            }

            $final_price = $ProductVariant->final_price * $qty;
        } else {
            if (!empty($product)) {
                if ($product->variant_product == 1) {
                    $product_stock_datas = ProductVariant::find($variant_id);
                    $product->setAttribute('variantId', $variant_id);
                    $var_stock = !empty($product_stock_datas->stock) ? $product_stock_datas->stock : $product->product_stock;
                    if (empty($variant_id) || $variant_id == 0) {
                        return Utility::error(['message' => 'Please Select a variant in a product.']);
                    } else if ($var_stock <= $settings['out_of_stock_threshold'] && $product_stock_datas->stock_order_status == 'not_allow') {
                        return Utility::error(['message' => 'Please Select a diffrent variant in a product.']);
                    } else {
                        $product_stock_data = ProductVariant::find($variant_id);
                        if ($product_stock_data->stock_status == 'out_of_stock') {
                            return Utility::error(['message' => 'Product has out of stock.']);
                        }
                    }
                } else {
                    if ($product->product_stock <= $settings['out_of_stock_threshold'] && $product->stock_order_status == 'not_allow') {
                        return Utility::error(['message' => 'Product has out of stock.']);
                    }
                }
                $final_price = floatval($product->final_price) * floatval($qty);
            } else {
                return Utility::error(['message' => 'Product not found.']);
            }
        }

        $qty = $qty;
        $key_name = $product_id . '_' . $variant_id;
        $cart[$key_name]['product_id'] = $product_id;
        $cart[$key_name]['variant_id'] = $variant_id;
        $cart[$key_name]['qty'] = $qty;
        $cart[$key_name]['created_at'] = now();
        $cart[$key_name]['theme_id'] = $theme_id;
        $cart[$key_name]['store_id'] = $store_id;


        $cart_Cookie = Cookie::get('cart');
        if (!empty($cart_Cookie)) {
            $cart_array = json_decode($cart_Cookie, true);
            if (!empty($cart_array[$key_name])) {
                $cart_count = count($cart_array);
                return Utility::error(['message' => $product->name . ' already in cart.', 'count' => $cart_count]);
            } else {
                $cart_array = array_merge($cart_array, $cart);
                $cart_count = count($cart_array);
                $cart_json = json_encode($cart_array);
                Cookie::queue('cart', $cart_json, 1440);
            }
        } else {
            $cart_json = json_encode($cart);
            Cookie::queue('cart', $cart_json, 1440);
            $cart_count = 1;
        }

        if (!empty($cart_count)) {
            return Utility::success(['message' => $product->name . ' add successfully.', 'count' => $cart_count]);
        } else {
            return Utility::error(['message' => 'Cart is empty.', 'count' => $cart_count]);
        }
    }

    public static function cart_list_cookie($data,$storeid = '')
    {
        $store = Store::find($storeid);

        $theme_id = $store->theme_id ?? APP_THEME();
        $store_id = $store->id ?? getCurrentStore();
        $Carts = $cart_Cookie = Cookie::get('cart');

        $shipping_price_1 = $data['shipping_final_price'] ?? 0;

        $shipping_price = (int)$shipping_price_1;
        $coupon_amount = 0;
        Session::forget('coupon_price');
        if (empty($Carts)) {
            $na = [];
            $Carts = json_encode($na);
        }
        $Carts = json_decode($Carts);
        if (!empty($Carts)) {
            foreach ($Carts as $key => $cart_value) {
                if ($cart_value->theme_id != $theme_id || $cart_value->store_id != $store_id) {
                    unset($Carts->$key);
                }
            }
        }
        $cart_array = [];
        $original_price = 0;
        $discount_price = 0;
        $final_price = 0;
        $after_discount_final_price = 0;
        $cart_total_qty = 0;
        $cart_final_price = 0;
        $shipping_original_price = 0;
        $tax_price = $total_orignal_price = 0;
        $cart_array['product_list'] = [];
        if (!isset($data['billing_info'])) {
            $country = isset($data['countryId']) ? $data['countryId'] : null;
            $state_id =isset($data['stateId']) ? $data['stateId'] : null;
            $city_id = isset($data['cityId']) ? $data['cityId'] : null;
        } else {
            $other_info = is_string($data['billing_info']) ? (array)json_decode($data['billing_info']) : ($data['billing_info'] ?? []);
            $country = !empty($other_info['delivery_country']) ? $other_info['delivery_country'] : null;
            $state_id = !empty($other_info['delivery_state']) ? $other_info['delivery_state'] : null;
            $city_id = !empty($other_info['delivery_city']) ? $other_info['delivery_city'] : null;
        }

        $coupon_price = 0;
        $tax_id = null;
        if (!empty($Carts)) {
            foreach ($Carts as $key => $cart_value) {
                $variant_name = '';
                $cart_product_data = Product::find($cart_value->product_id);

                if (empty($cart_value->variant_id) && $cart_value->variant_id == 0) {

                    $per_product_discount_price = !empty($cart_product_data->discount_price) ? $cart_product_data->discount_price : 0;
                    $product_discount_price = $per_product_discount_price * $cart_value->qty;

                    $final_price = !empty($cart_product_data->sale_price) ? $cart_product_data->sale_price : 0;
                    $final_price = $final_price * $cart_value->qty;

                    $product_orignal_price = !empty($cart_product_data->price) ? $cart_product_data->price : 0;
                    $total_product_orignal_price = $product_orignal_price * $cart_value->qty;
                } else {
                    $ProductVariant = ProductVariant::find($cart_value->variant_id);
                    $variant_name = $ProductVariant->variant;

                    $per_product_discount_price = !empty($ProductVariant->discount_price) ? $ProductVariant->discount_price : 0;
                    $product_discount_price = $ProductVariant->discount_price * $cart_value->qty;

                    $final_price = !empty($ProductVariant->final_price) ? $ProductVariant->final_price : 0;
                    $final_price = $final_price * $cart_value->qty;

                    $product_orignal_price = !empty($ProductVariant->original_price) ? $ProductVariant->original_price : 0;
                    $total_product_orignal_price = $product_orignal_price * $cart_value->qty;
                }

                $cart_array['product_list'][$key]['cart_id'] = $key;
                $cart_array['product_list'][$key]['cart_created'] = $cart_value->created_at;
                $cart_array['product_list'][$key]['product_id'] = $cart_value->product_id;
                $cart_array['product_list'][$key]['image'] = !empty($cart_product_data->cover_image_path) ? $cart_product_data->cover_image_path : ' ';
                $cart_array['product_list'][$key]['name'] = !empty($cart_product_data->name) ? $cart_product_data->name : ' ';
                $cart_array['product_list'][$key]['orignal_price'] = SetNumber($product_orignal_price);
                $cart_array['product_list'][$key]['total_orignal_price'] = SetNumber($total_product_orignal_price);
                // $cart_array['product_list'][$key]['per_product_discount_price'] = SetNumber($per_product_discount_price);
                // $cart_array['product_list'][$key]['discount_price'] = SetNumber($product_discount_price);
                $cart_array['product_list'][$key]['final_price'] = SetNumber($final_price);
                $cart_array['product_list'][$key]['qty'] = $cart_value->qty;
                $cart_array['product_list'][$key]['variant_id'] = $cart_value->variant_id;
                $cart_array['product_list'][$key]['variant_name'] = $variant_name;
                $cart_array['product_list'][$key]['return'] = 0;
                $cart_array['product_list'][$key]['shipping_price'] = $shipping_price;

                $discount_price += $product_discount_price;
                $cart_total_qty += $cart_value->qty;
                $cart_final_price += $final_price;
                $original_price += $total_product_orignal_price;
                $shipping_original_price += $shipping_price;

                if (isset($data['coupon_code'])) {
                    $coupon = Coupon::where('coupon_code', $data['coupon_code'])->first();
                    if ($coupon) {
                        $coupon_apply_price = self::getCouponTotalAmount($coupon, $final_price, $cart_product_data->id, $cart_product_data->maincategory_id);
                        $coupon_price += $final_price - $coupon_apply_price;
                        $final_price = $coupon_apply_price;
                    }
                }

                if ($cart_product_data->tax_id) {
                    $tax_id = $cart_product_data->tax_id;
                    $tax_price += self::getProductTaxAmount($cart_product_data->tax_id,$final_price, $store->id, $theme_id, $city_id, $state_id, $country, true);
                } else {
                    $tax_price += 0;
                }
            }
        }

        $after_discount_final_price = $cart_final_price;

        $product_discount_price = (float)number_format((float)$discount_price, 2);
        $cart_array['product_discount_price'] = $product_discount_price;
        $after_discount_final_price = (float)$after_discount_final_price;

        $cart_array['sub_total'] = $after_discount_final_price + $shipping_price;

        $tax_option = TaxOption::where('store_id', $store_id)
        ->where('theme_id', $theme_id)
        ->pluck('value', 'name')->toArray();

        if(isset($tax_option['round_tax']) && $tax_option['round_tax'] == 1)
        {
            $tax_price = round($tax_price);
        }


        if ($coupon_price == '') {
            $final_total = $cart_final_price - $coupon_price + $shipping_price + $tax_price;
        } else {
            $final_total = ($cart_final_price - $coupon_price) + $shipping_price + $tax_price;
        }

        $cart_array['cart_total_product'] = count((array)$Carts);
        $cart_array['cart_total_qty'] = $cart_total_qty;
        $cart_array['original_price'] = SetNumber($original_price);
        $cart_array['final_price'] = SetNumber($cart_final_price);
        $cart_array['total_final_price'] = SetNumber($cart_final_price);
        $cart_array['total_tax_price'] = SetNumber($tax_price);
        $cart_array['tax_price'] = SetNumber($tax_price);
        $cart_array['tax_id'] = $tax_id ?? null;
        $cart_array['total_coupon_price'] = SetNumber($coupon_price);
        $cart_array['total_sub_price'] = SetNumber($final_total);
        $cart_array['coupon_code'] =  $data['coupon_code'] ?? null;
        $cart_array['shipping_original_price'] = $shipping_price;

        if (!empty($cart_array)) {
            return Utility::success($cart_array);
        } else {
            return Utility::error(['message' => 'Cart is empty.']);
        }
    }

    public static function CartCount($slug = '')
    {
        $return = 0;
        $store = Store::where('slug',$slug)->first();
        $theme_id = GetCurrenctTheme($store->slug);
        if (auth('customers')->user()) {
            $return = Cart::where('customer_id', auth('customers')->user()->id)
                ->where('theme_id',  $theme_id ?? APP_THEME())
                ->count();
        } else {
            $cart_Cookie = Cookie::get('cart');
            // $store_id = \Auth::user()->current_store;
            // $store = Store::where('id',$store_id)->first();

            if (!empty($cart_Cookie)) {
                $cart_array = json_decode($cart_Cookie, true);
                if ($cart_array !== null) {
                    foreach ($cart_array as $key => $cart_value) {
                        if ($cart_value['theme_id'] != $theme_id || $cart_value['store_id'] != $store->id) {
                            unset($cart_array[$key]);
                        }
                    }
                    $return = count($cart_array);
                }
            }
        }
        return $return;
    }

    public static function cart_qty_cookie($request)
    {
        $rules = [
            'product_id' => 'required',
            'variant_id' => 'required',
            'quantity_type' => 'required|in:increase,decrease,remove',
        ];

        $validator = \Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return Utility::error([
                'message' => $messages->first()
            ]);
        }

        $final_price = 0;
        $cart_id = $request->cart_id;
        if (!empty($request->variant_id) || $request->variant_id != 0) {
            $ProductVariant = ProductVariant::find($request->variant_id);
            $final_price = $ProductVariant->final_price;
        } else {
            $product = Product::find($request->product_id);
            if (!empty($product)) {
                if ($product->variant_product == 1) {
                    if (empty($request->variant_id) || $request->variant_id == 0) {
                        return Utility::error([
                            'message' => 'Please Select a variant in a product.'
                        ]);
                    }
                }
                $final_price = $product->final_price;
            }
        }

        $cart = Cookie::get('cart');
        $cart_array1 = json_decode($cart, true);
        $cart_count = count($cart_array1);

        $cart_array = json_decode($cart);

        if (empty($cart_array1[$cart_id])) {
            return Utility::error(['message' => 'Product not found.'], 'fail', 200, 0, $cart_count);
        } else {
            if ($request->quantity_type == 'increase') {
                if (!empty($request->variant_id) || $request->variant_id != 0) {
                    if ($cart_array->$cart_id->qty >= $ProductVariant->stock) {
                        return Utility::error(['message' => 'can not increase product quantity.'], 'fail', 200, 0, $cart_count);
                    } else {
                        $cart_array->$cart_id->qty += 1;
                    }
                } else {
                    if($product->stock_status == 'in_stock' || $product->stock_status == 'on_backorder')
                    {
                        $cart_array->$cart_id->qty += 1;
                    }
                    elseif ($cart_array->$cart_id->qty >= $product->product_stock) {
                        return Utility::error(['message' => 'can not increase product quantity.'], 'fail', 200, 0, $cart_count);
                    } else {
                        $cart_array->$cart_id->qty += 1;
                    }
                }
            }
            if ($request->quantity_type == 'decrease') {
                if ($cart_array->$cart_id->qty == 1) {
                    return Utility::error(['message' => 'can not decrease product quantity.'], 'fail', 200, 0, $cart_count);
                }
                if ($cart_array->$cart_id->qty > 0) {
                    $cart_array->$cart_id->qty -= 1;
                }
            }

            if ($request->quantity_type == 'remove') {
                unset($cart_array->$cart_id);
            }
            $cart_json = json_encode($cart_array);
            Cookie::queue('cart', $cart_json, 1440);

            $cart_count = Cart::where('customer_id', $request->user_id)->count();
            return Utility::success(['message' => 'Cart successfully updated.'], "successfull", 200, $cart_count);
        }
    }

    public static function cookie_to_cart($user_id = 0, $store_id = '')
    {
        if ($user_id != 0) {
            $cart_Cookie = Cookie::get('cart');
            if (!empty($cart_Cookie)) {
                $products = json_decode($cart_Cookie);
                foreach ($products as $key => $product) {
                    $cart = Cart::where('customer_id', $user_id)->where('product_id', $product->product_id)->where('variant_id', $product->variant_id)->first();
                    if (!empty($cart)) {
                        $cart->qty = $cart->qty + $product->qty;
                        $cart->save();
                    } else {
                        $price = 0;

                        $cart = new Cart();
                        $cart->customer_id = $user_id;
                        $cart->product_id = $product->product_id;
                        $cart->variant_id = $product->variant_id;
                        $cart->qty = $product->qty;

                        if ($product->variant_id == 0) {
                            $productss = Product::find($product->product_id);
                            $price = !empty($productss) ? ( $productss->sale_price ?? $productss->price) : 0;
                        } else {
                            $ProductVariant = ProductVariant::find($product->variant_id);
                            $price = !empty($ProductVariant) ? ($ProductVariant->variation_price ?? $ProductVariant->price ) : 0;
                        }
                        $cart->price = $price;
                        $cart->theme_id = APP_THEME();
                        $cart->store_id = $store_id;
                        $cart->save();
                    }
                }
                $empty_cart = [];
                $cart_json = json_encode($empty_cart);
                Cookie::queue('cart', $cart_json, 1440);
            }
        }
    }

    public static function CartPageBestseller($slug = '')
    {
        $MainCategory = MainCategory::where('theme_id', APP_THEME())->where('store_id', getCurrentStore())->get()->pluck('name', 'id');
        $MainCategory->prepend('All Products', '0');
        $homeproducts = Product::where('theme_id', APP_THEME())->where('store_id', getCurrentStore())->get();
        $currency_icon = Utility::GetValueByName('CURRENCY');

        return view('bestseller_cart', compact('slug', 'homeproducts', 'MainCategory','currency_icon'))->render();
    }

    public function UserData()
    {
        return $this->hasOne(Customer::class, 'id', 'customer_id');
    }

    public static function getProductTaxAmount($taxId = null, $sub_total,$storeId, $themeId, $city_id, $state_id, $country, $flag) {
        $tax_price = 0;

        $taxs = TaxMethod::where('theme_id', $themeId)->where('store_id', $storeId)->where('tax_id',$taxId)->orderBy('priority', 'asc')->get();

        foreach ($taxs as $tax) {
            if ($flag) {
                if ($tax->country_id && $tax->state_id && $tax->city_id && ($country == $tax->country_id) && ($state_id == $tax->state_id) && ($city_id == $tax->city_id)) {

                    $amount = $tax->tax_rate * $sub_total / 100;
                    $tax_price += $amount;
                    continue;
                }
                if ($tax->country_id && $tax->state_id && !$tax->city_id && ($country == $tax->country_id) && ($state_id == $tax->state_id)) {
                    $amount = $tax->tax_rate * $sub_total / 100;
                    $tax_price += $amount;
                    continue;
                }
                if ($tax->country_id && !$tax->state_id && !$tax->city_id && ($country == $tax->country_id)) {
                    $amount = $tax->tax_rate * $sub_total / 100;
                    $tax_price += $amount;
                    continue;
                }
                if (!$tax->country_id && !$tax->state_id && !$tax->city_id) {
                    $amount = $tax->tax_rate * $sub_total / 100;
                    $tax_price += $amount;

                    continue;
                }
            } else {
                $amount = $tax->tax_rate * $sub_total / 100;
                $tax_price += $amount;
            }

        }
        return $tax_price;
    }

    public static function getCouponTotalAmount($coupon, $subTotal, $productId, $categoryId) {
        $coupon_price = 0;

        //if ( $coupon->free_shipping_coupon == 0 ) {
           if ($coupon->coupon_type == 'percentage') {
                $coupon_price =  ($subTotal - (($coupon->discount_amount * $subTotal) /100));
           } elseif ($coupon->coupon_type == 'flat') {
                $coupon_price = $subTotal - $coupon->discount_amount;
           } elseif ( $coupon->coupon_type == 'fixed product discount' ) {
                $coupon_applied = explode( ',', ( $coupon->applied_product ) );
                $exclude_product = explode( ',', $coupon->exclude_product );
                $applied_categories = explode( ',', $coupon->applied_categories );
                $exclude_categories = explode( ',', $coupon->exclude_categories );

                // Check if coupon is applied
                if (count($coupon_applied) > 0) {
                    // Check if exclude_product, applied_categories, and exclude_categories are not empty
                    if (count($exclude_product) > 0 && count($applied_categories) > 0 && count($exclude_categories) > 0) {
                        // Check if productId is not in exclude_product and coupon_applied
                        if (!in_array($productId, $exclude_product) && !in_array($productId, $coupon_applied)) {
                            // Check if categoryId is in applied_categories and not in exclude_categories
                            if (in_array($categoryId, $applied_categories) && !in_array($categoryId, $exclude_categories)) {
                                $coupon_price = $subTotal - $coupon->discount_amount;
                            } elseif (!in_array($categoryId, $exclude_categories)) {
                                $coupon_price = $subTotal - $coupon->discount_amount;
                            }
                        }
                    }
                    // Check if exclude_product and applied_categories are not empty and exclude_categories is empty
                    elseif (count($exclude_product) > 0 && count($applied_categories) > 0 && count($exclude_categories) == 0) {
                        // Check if productId is not in exclude_product and coupon_applied
                        if (!in_array($productId, $exclude_product) && !in_array($productId, $coupon_applied)) {
                            // Check if categoryId is in applied_categories
                            if (in_array($categoryId, $applied_categories)) {
                                $coupon_price = $subTotal - $coupon->discount_amount;
                            }
                        }
                    }
                    // Check if exclude_product is not empty and applied_categories is empty and exclude_categories is not empty
                    elseif (count($exclude_product) > 0 && count($applied_categories) == 0 && count($exclude_categories) > 0) {
                        // Check if productId is not in exclude_product and coupon_applied
                        if (!in_array($productId, $exclude_product) && !in_array($productId, $coupon_applied)) {
                            // Check if categoryId is not in exclude_categories
                            if (!in_array($categoryId, $exclude_categories)) {
                                $coupon_price = $subTotal - $coupon->discount_amount;
                            }
                        }
                    }
                    // Check if exclude_product is empty and applied_categories is not empty and exclude_categories is not empty
                    elseif (count($exclude_product) == 0 && count($applied_categories) > 0 && count($exclude_categories) > 0) {
                        // Check if productId is not in coupon_applied
                        if (!in_array($productId, $coupon_applied)) {
                            // Check if categoryId is in applied_categories and not in exclude_categories
                            if (in_array($categoryId, $applied_categories) && !in_array($categoryId, $exclude_categories)) {
                                $coupon_price = $subTotal - $coupon->discount_amount;
                            } elseif (!in_array($categoryId, $exclude_categories)) {
                                $coupon_price = $subTotal - $coupon->discount_amount;
                            }
                        }
                    }
                    // Check if exclude_product, applied_categories, and exclude_categories are empty
                    elseif (count($exclude_product) == 0 && count($applied_categories) == 0 && count($exclude_categories) == 0) {
                        // Check if productId is in coupon_applied
                        if (in_array($productId, $coupon_applied)) {
                            $coupon_price = $subTotal - $coupon->discount_amount;
                        }
                    }
                }
           }
        //}
        return $coupon_price;
    }

    public static function calculateFlatRateShippingAmount($shippingMethods, $shippingCost, $productList) {
        $cost_totle =  $shippingCost;
        $price = 0;
        foreach ($productList as $key => $Product) {
            $productId = $Product->product_id;
            $product_data = Product::find($productId);
            if ($product_data->variant_product == 0) {
                if ($shippingMethods['product_cost'] != null) {
                    $shippingClass = Shipping::find($product_data->shipping_id);


                    $value = $shippingMethods['product_cost'];
                    $product_cost = json_decode($value, true);
                    if ($shippingClass == null) {
                        $price  += $product_cost['product_no_cost'];
                    } else {
                        foreach ($product_cost['product_cost'] as $key => $value) {
                            if ($key == $shippingClass->id) {
                                $price  += $value;
                            } else {
                                $price  += 0;
                            }
                        }
                    }
                } else {
                    $cost_totle = $shippingMethods->cost;
                }
            }
            else {
                if ($shippingMethods['product_cost'] != null) {
                    $productVariants = [];

                    foreach ($productList as $item) {
                        $productId = $item->product_id;
                        $variantId = $item->variant_id;

                        if (!isset($productVariants[$productId])) {
                            $productVariants[$productId] = [];
                        }
                        $productVariants[$productId][] = $variantId;
                    }
                    $uniqueVariantIds = [];
                    foreach ($productVariants as $variants) {
                        $uniqueVariantIds = array_merge($uniqueVariantIds, $variants);
                    }

                    $uniqueVariantIds = array_values(array_unique($uniqueVariantIds));
                    $product_stock = ProductVariant::whereIn('id', $uniqueVariantIds)->where('product_id', $Product->product_id)->get();
                    $value = $shippingMethods['product_cost'];
                    $product_cost = json_decode($value, true);

                    foreach ($product_stock as $stock) {
                        $shippingClass = Shipping::find($stock->shipping);

                        if ($stock->shipping == 'same_as_parent') {
                            $shipping = Shipping::find($product_data->shipping_id);
                            if ($shipping == null) {
                                $price  += $product_cost['product_no_cost'];
                            }
                            foreach ($product_cost['product_cost'] as $key => $value) {
                                if ($shipping) {
                                    if ($key == $shipping->id) {
                                        $price  += $value;
                                    } else {
                                        $price  += 0;
                                    }
                                }
                            }
                        } else {
                            foreach ($product_cost['product_cost'] as $key => $value) {
                                if ($shippingClass) {
                                    if ($key == $shippingClass->id) {
                                        $price  += $value;
                                    } else {
                                        $price  += 0;
                                    }
                                }
                            }
                        }
                    }
                } else {
                    $cost_totle = $shippingMethods->cost;
                }
            }
        }
        return $cost_totle + $price;
    }
}
