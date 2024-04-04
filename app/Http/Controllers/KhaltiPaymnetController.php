<?php

namespace App\Http\Controllers;

use App\Khalti\Khalti;
use App\Models\User;
use App\Models\Order;
use App\Models\Plan;
use App\Models\PlanOrder;
use App\Models\PlanCoupon;
use App\Models\Coupon;
use App\Models\PlanUserCoupon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Services\PaymentService;
use App\Models\Store;
use App\Models\Utility;
use App\Models\Setting;
use App\Models\Product;
use App\Models\Cart;
use App\Models\City;
use App\Models\ActivityLog;
use App\Models\OrderTaxDetail;
use App\Models\OrderBillingDetail;
use App\Models\AppSetting;
use Stripe;
use App\Http\Controllers\CartController;
use Illuminate\Http\RedirectResponse;
use Session;
use App\Models\OrderNote;
use App\Models\Customer;
use Illuminate\Support\Facades\Crypt;
use Cookie;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use App\Traits\ApiResponser;
use App\Http\Controllers\Api\ApiController;

class KhaltiPaymnetController extends Controller
{
    //
    public function planPayWithKhalti(Request $request)
    {
        $payment_setting = getSuperAdminAllSetting();
        $api_key = isset($payment_setting['nepalste_public_key']) ? $payment_setting['nepalste_public_key'] : '';
        $currency = isset($payment_setting['CURRENCY_NAME']) ? $payment_setting['CURRENCY_NAME'] : 'USD';
        $planID = \Illuminate\Support\Facades\Crypt::decrypt($request->plan_id);

        $plan = Plan::find($planID);
        $orderID = strtoupper(str_replace('.', '', uniqid('', true)));

        config(
            [
                'khalti.public_key' => isset($payment_setting['khalti_public_key']) ? $payment_setting['khalti_public_key'] : '',
                'khalti.sck' => isset($payment_setting['khalti_secret_key']) ? $payment_setting['khalti_secret_key'] : '',
            ]
        );

        if ($plan) {
            $price     = $plan->price;
            try {
                if (!empty($request->coupon))
                {

                    $coupons = PlanCoupon::where('code', $request->coupon)->where('is_active', '1')->first();
                    if ($coupons) {
                        $coupon_code = $coupons->code;
                        $usedCoupun     = $coupons->used_coupon();
                        if ($coupons->limit == $usedCoupun) {
                            $res_data['error'] = __('This coupon code has expired.');
                        } else {
                            $discount_value = ($plan->price / 100) * $coupons->discount;
                            $price  = $price - $discount_value;
                            if ($price < 0) {
                                $price = $plan->price;
                            }
                            $coupon_id = $coupons->id;
                        }
                    }else {
                        return redirect()->back()->with('error', __('This coupon code is invalid or has expired.'));
                    }
                }
                $authuser       = \Auth::user();
                if($price <= 0){
                    $assignPlan = $authuser->assignPlan($plan->id);
                    if($assignPlan['is_success']){
                        $amount = $price;
                        return $amount;
                    }else{
                        return response()->json([
                            'success' => true,
                            'inputs' => __('Something into warning.'),
                        ]);
                    }
                }
                $secret = !empty($payment_setting['khalti_secret_key'])?$payment_setting['khalti_secret_key']:'';


                $amount = $price;
                return $amount;

            } catch (\Exception $e) {
                return redirect()->route('plan.index')->with('error', __($e->getMessage()));
            }
        } else {
            return redirect()->route('plan.index')->with('error', __('Plan is deleted.'));
        }
    }

    public function planGetKhaltiStatus(Request $request)
    {
        $payment_setting = getSuperAdminAllSetting();
        $currency = isset($payment_setting['CURRENCY_NAME']) ? $payment_setting['CURRENCY_NAME'] : 'USD';
        $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
        $planID = \Illuminate\Support\Facades\Crypt::decrypt($request->plan_id);

        $plan = Plan::find($planID);

        $payload = $request->payload;
        $price = $plan->price;
        $token = $payload['token'];
        $amount = $payload['amount'];
        $secret = !empty($payment_setting['khalti_secret_key'])?$payment_setting['khalti_secret_key']:'';
        $khalti = new Khalti();
        $response = $khalti->verifyPayment($secret,$token,$amount);

        if (!empty($request->coupon_code))
        {

            $coupons = PlanCoupon::where('code', $request->coupon)->where('is_active', '1')->first();
            if ($coupons) {
                $coupon_code = $coupons->code;
                $usedCoupun     = $coupons->used_coupon();
                if ($coupons->limit == $usedCoupun) {
                    $res_data['error'] = __('This coupon code has expired.');
                } else {
                    $discount_value = ($plan->price / 100) * $coupons->discount;
                    $price  = $price - $discount_value;
                    if ($price < 0) {
                        $price = $plan->price;
                    }
                    $coupon_id = $coupons->id;
                }
            }else {
                // return redirect()->back()->with('error', __('This coupon code is invalid or has expired.'));
            }
        }
        $coupanCode = $request->coupon_code;
        $user = \Auth::user();

        if($response['status_code'] == '200')
        {
            $order = new PlanOrder();
            $order->order_id = $orderID;
            $order->name = $user->name;
            $order->card_number = '';
            $order->card_exp_month = '';
            $order->card_exp_year = '';
            $order->plan_name = $plan->name;
            $order->plan_id = $plan->id;
            $order->price = $price;
            $order->price_currency = $currency;
            $order->txn_id = time();
            $order->payment_type = __('Khalti');
            $order->payment_status = 'success';
            $order->txn_id = '';
            $order->receipt = '';
            $order->user_id = $user->id;
            $order->save();
            $type = 'Subscription';

            $coupons = PlanCoupon::where('code', $coupanCode)->where('is_active', '1')->first();
            if (!empty($coupons)) {
                $userCoupon         = new PlanUserCoupon();
                $userCoupon->user_id   = $user->id;
                $userCoupon->coupon_id = $coupons->id;
                $userCoupon->order  = $order->order_id;
                $userCoupon->save();
                $usedCoupun = $coupons->used_coupon();
                if ($coupons->limit <= $usedCoupun) {
                    $coupons->is_active = 0;
                    $coupons->save();
                }
            }
            $assignPlan = $user->assignPlan($plan->id);
            if ($assignPlan['is_success'])
            {
                return $response;
                return redirect()->route('plan.index')->with('success', __('Plan activated Successfully.'));
            } else
            {
                return redirect()->route('plan.index')->with('error', __($assignPlan['error']));
            }
        }
        else {
            return redirect()->route('plans.index')->with('error', __('Transaction has been failed.'));
        }
    }

    public function getOrderPaymentStatus(Request $request,$slug)
    {
        $requests_data = (Session::get('request_data'));
        if($requests_data == null)
        {
            $requests_data = $request->all();
        }
        $slug = !empty($requests_data['slug']) ? $requests_data['slug'] : '';
        $store = Store::where('slug', $slug)->first();
        Session::forget('request_data');
        $customer_id = $requests_data['customer_id'] ?? '';

        if(!empty($requests_data['method_id'])){

            $request['method_id'] = $requests_data['method_id'];
        }
        $user = User::where('type', 'admin')->first();
        if ($user->type == 'admin') {
            $plan = Plan::find($user->plan_id);
        }
        $theme_id = !empty($request->theme_id) ? $request->theme_id : APP_THEME();
        if (!auth('customers')->user()) {
            if ($request->coupon_code != null) {
                $coupon = Coupon::where('id', $request->coupon_info['coupon_id'])->where('store_id', $store->id)->where('theme_id', $theme_id)->first();
                $coupon_email  = $coupon->PerUsesCouponCount();
                $i = 0;
                foreach ($coupon_email as $email) {
                    if ($email == $request->billing_info['email']) {
                        $i++;
                    }
                }

                if (!empty($coupon->coupon_limit_user)) {
                    if ($i  >= $coupon->coupon_limit_user) {
                        return $this->error(['message' => 'Coupon has been expiredd.']);
                    }
                }
            }
        }
        if (!auth('customers')->user()) {
            $rules = [
                'billing_info' => 'required',
                'payment_type' => 'required',
                //'delivery_id' => 'required',
            ];
        } else {
            $rules = [
                'customer_id' => 'required',
                'billing_info' => 'required',
                'payment_type' => 'required',
                //'delivery_id' => 'required',
            ];
        }

        $validator = \Validator::make($requests_data, $rules);
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            Utility::error([
                'message' => $messages->first()
            ]);
        }

        $cartlist_final_price = 0;
        $final_price = 0;
        $tax_price = 0;
        // cart list api call
        if (!auth('customers')->user()) {

            $response = Cart::cart_list_cookie($requests_data,$store->id);
            $response = json_decode(json_encode($response));
            $cartlist = (array)$response->data;
            if (empty($cartlist['product_list'])) {
                return Utility::error(['message' => 'Cart is empty.']);
            }
            $cartlist_final_price = !empty($cartlist['final_price']) ? $cartlist['final_price'] : 0;
            $final_sub_total_price = !empty($cartlist['total_sub_price']) ? $cartlist['total_sub_price'] : 0;
            $final_price = $response->data->total_final_price;
            $tax_price = !empty($requests_data['tax_price']) ? $requests_data['tax_price'] : '';
            $billing = $requests_data['billing_info'];

            $products = $cartlist['product_list'];
        } elseif (!empty($customer_id)) {
            $cart_list['customer_id']   = $customer_id;
            $request->merge($requests_data);
            $cartt = new ApiController();
            $cartlist_response = $cartt->cart_list($request, $slug);
            $cartlist = (array)$cartlist_response->getData()->data;

            if (empty($cartlist['product_list'])) {
                return Utility::error(['message' => 'Cart is empty.']);
            }

            $cartlist_final_price = !empty($cartlist['final_price']) ? $cartlist['final_price'] : 0;
            $final_sub_total_price = !empty($cartlist['total_sub_price']) ? $cartlist['total_sub_price'] : 0;
            $final_price = $cartlist['total_final_price'];
            $tax_price = !empty($requests_data['tax_price']) ? $requests_data['tax_price'] : '';
            $billing = is_string($request->billing_info) ? (array) json_decode($request->billing_info) : $request->billing_info;
            $products = $cartlist['product_list'];
        } else {
            return Utility::error(['message' => 'User not found.']);
        }

        $coupon_price = 0;
        // coupon api call
        if (!empty($requests_data['coupon_info'])) {
            $coupon_data = $requests_data['coupon_info'];
            $apply_coupon = [
                'coupon_code' => $coupon_data['coupon_code'],
                'sub_total' => $cartlist_final_price,
                'theme_id' => $requests_data['theme_id'],
                'slug' => $requests_data['slug']

            ];
            $request->merge($apply_coupon);
            $couponss = new ApiController();
            $apply_coupon_response = $couponss->apply_coupon($request, $slug);
            $apply_coupon = (array)$apply_coupon_response->getData()->data;

            $order_array['coupon']['message'] = $apply_coupon['message'];
            $order_array['coupon']['status'] = false;
            if (!empty($apply_coupon['final_price'])) {
                $cartlist_final_price = $apply_coupon['final_price'];
                $coupon_price = $apply_coupon['amount'];
                $order_array['coupon']['status'] = true;
            }
        }

        $delivery_price = 0;
        if ($plan->shipping_method == 'on') {
            if (!empty($request->method_id)) {
                $del_charge = new CartController();
                $delivery_charge = $del_charge->get_shipping_method($request, $slug);
                $content = $delivery_charge->getContent();
                $data = json_decode($content, true);
                $delivery_price = $data['shipping_final_price'];
                $tax_price = $requests_data['tax_price'] ?? 0;
            } else {
                return $this->error(['message' => 'Shipping Method not found']);
            }
        } else {
            if (!empty($tax_price)) {
                $tax_price = $tax_price;
            }else{
                $tax_price = 0;
            }
        }

        $settings = Setting::where('theme_id', $theme_id)->where('store_id', $store->id)->pluck('value', 'name')->toArray();

        // Order stock decrease start
        $prodduct_id_array = [];
        if (!empty($products)) {
            foreach ($products as $key => $product) {
                $prodduct_id_array[] = $product->product_id;

                $product_id = $product->product_id;
                $variant_id = $product->variant_id;
                $qtyy = !empty($product->qty) ? $product->qty : 0;

                $Product = Product::where('id', $product_id)->first();
                $datas = Product::find($product_id);
                if($settings['stock_management'] ?? '' == 'on')
                {
                    if (!empty($product_id) && !empty($variant_id) && $product_id != 0 && $variant_id != 0) {
                        $ProductStock = ProductVariant::where('id', $variant_id)->where('product_id', $product_id)->first();
                        $variationOptions = explode(',', $ProductStock->variation_option);
                        $option = in_array('manage_stock', $variationOptions);
                        if (!empty($ProductStock)) {
                            if($option == true)
                            {
                                $remain_stock = $ProductStock->stock - $qtyy;
                                $ProductStock->stock = $remain_stock;
                                $ProductStock->save();

                                if($ProductStock->stock <= $ProductStock->low_stock_threshold)
                                {
                                    if (!empty(json_decode($settings['notification'])) && in_array("enable_low_stock",json_decode($settings['notification'])))
                                    {
                                        if(isset($settings['twilio_setting_enabled']) && $settings['twilio_setting_enabled'] =="on")
                                        {
                                            Utility::variant_low_stock_threshold($product,$ProductStock,$theme_id,$settings);
                                        }

                                    }
                                }
                                if($ProductStock->stock <= $settings['out_of_stock_threshold'])
                                {
                                    if (!empty(json_decode($settings['notification'])) && in_array("enable_out_of_stock",json_decode($settings['notification'])))
                                    {
                                        if(isset($settings['twilio_setting_enabled']) && $settings['twilio_setting_enabled'] =="on")
                                        {
                                            Utility::variant_out_of_stock($product,$ProductStock,$theme_id,$settings);
                                        }
                                    }
                                }
                            }
                            else
                            {
                                $remain_stock = $datas->product_stock - $qtyy;
                                $datas->product_stock = $remain_stock;
                                $datas->save();
                                if($datas->product_stock <= $datas->low_stock_threshold)
                                {
                                    if (!empty(json_decode($settings['notification'])) && in_array("enable_low_stock",json_decode($settings['notification'])))
                                    {
                                        if(isset($settings['twilio_setting_enabled']) && $settings['twilio_setting_enabled'] =="on")
                                        {
                                            Utility::variant_low_stock_threshold($product,$datas,$theme_id,$settings);
                                        }

                                    }
                                }
                                if($datas->product_stock <= $settings['out_of_stock_threshold'])
                                {
                                    if (!empty(json_decode($settings['notification'])) && in_array("enable_out_of_stock",json_decode($settings['notification'])))
                                    {
                                        if(isset($settings['twilio_setting_enabled']) && $settings['twilio_setting_enabled'] =="on")
                                        {
                                            Utility::variant_out_of_stock($product,$datas,$theme_id,$settings);
                                        }
                                    }
                                }
                                if($datas->product_stock <= $settings['out_of_stock_threshold'] && $datas->stock_order_status == 'notify_customer')
                                {
                                    //Stock Mail
                                    $order_email = $billing['email'];
                                    $owner=Admin::find($store->created_by);
                                    $ProductId    = '';

                                    try
                                    {
                                        $dArr = [
                                            'item_variable' => $Product->id,
                                            'product_name' => $Product->name,
                                            'customer_name' => $billing['firstname'],
                                        ];

                                        // Send Email
                                        $resp = Utility::sendEmailTemplate('Stock Status', $order_email, $dArr, $owner,$store, $ProductId);
                                    }
                                    catch(\Exception $e)
                                    {
                                        $smtp_error = __('E-Mail has been not sent due to SMTP configuration');
                                    }
                                    try
                                    {
                                        $mobile_no =$request['billing_info']['billing_user_telephone'];
                                        $customer_name =$request['billing_info']['firstname'];
                                        $msg =   __("Dear,$customer_name .Hi,We are excited to inform you that the product you have been waiting for is now back in stock.Product Name: :$Product->name. ");
                                        $resp  = Utility::SendMsgs('Stock Status', $mobile_no, $msg);
                                    }
                                    catch(\Exception $e)
                                    {
                                        $smtp_error = __('Invalid OAuth access token - Cannot parse access token');
                                    }
                                }
                            }
                        } else {
                            return $this->error(['message' => 'Product not found .']);
                        }
                    } elseif (!empty($product_id) && $product_id != 0) {

                        if (!empty($Product)) {
                            $remain_stock = $Product->product_stock - $qtyy;
                            $Product->product_stock = $remain_stock;
                            $Product->save();
                            if($Product->product_stock <= $Product->low_stock_threshold)
                            {
                                if (!empty(json_decode($settings['notification'])) && in_array("enable_low_stock",json_decode($settings['notification'])))
                                {
                                    if(isset($settings['twilio_setting_enabled']) && $settings['twilio_setting_enabled'] =="on")
                                    {
                                        Utility::low_stock_threshold($Product,$theme_id,$settings);
                                    }
                                }
                            }

                            if($Product->product_stock <= $settings['out_of_stock_threshold'])
                            {
                                if (!empty(json_decode($settings['notification'])) && in_array("enable_out_of_stock",json_decode($settings['notification'])))
                                {
                                    if(isset($settings['twilio_setting_enabled']) && $settings['twilio_setting_enabled'] =="on")
                                    {
                                        Utility::out_of_stock($Product,$theme_id,$settings);
                                    }
                                }
                            }

                            if($Product->product_stock <= $settings['out_of_stock_threshold'] && $Product->stock_order_status == 'notify_customer')
                            {
                                //Stock Mail
                                $order_email = $billing['email'];
                                $owner=Admin::find($store->created_by);
                                $ProductId    = '';

                                try
                                {
                                $dArr = [
                                'item_variable' => $Product->id,
                                'product_name' => $Product->name,
                                'customer_name' => $billing['firstname'],
                                ];

                                // Send Email
                                $resp = Utility::sendEmailTemplate('Stock Status', $order_email, $dArr, $owner,$store, $ProductId);
                                }
                                catch(\Exception $e)
                                {
                                $smtp_error = __('E-Mail has been not sent due to SMTP configuration');
                                }
                                try
                                {
                                    $mobile_no =$request['billing_info']['billing_user_telephone'];
                                    $customer_name =$request['billing_info']['firstname'];
                                    $msg =   __("Dear,$customer_name .Hi,We are excited to inform you that the product you have been waiting for is now back in stock.Product Name: :$Product->name. ");
                                    $resp  = Utility::SendMsgs('Stock Status', $mobile_no, $msg);
                                }
                                catch(\Exception $e)
                                {
                                    $smtp_error = __('Invalid OAuth access token - Cannot parse access token');
                                }
                            }

                        } else {
                            return $this->error(['message' => 'Product not found .']);
                        }
                    } else {
                        return $this->error(['message' => 'Please fill proper product json field .']);
                    }
                }
                // remove from cart
                Cart::where('customer_id', $request->customer_id)->where('product_id', $product_id)->where('variant_id', $variant_id)->where('theme_id', $theme_id)->where('store_id',$store->id)->delete();
            }
        }
        // Order stock decrease end
        if (!empty($prodduct_id_array)) {
            $prodduct_id_array = $prodduct_id_array = array_unique($prodduct_id_array);
            $prodduct_id_array = implode(',', $prodduct_id_array);
        } else {
            $prodduct_id_array = '';
        }

        $product_reward_point = 1;

        $product_order_id  = '0' . date('YmdHis');
        $is_guest = 1;
        if (auth('customers')->check()) {
            $product_order_id  = $request->customer_id . date('YmdHis');
            $is_guest = 0;
        }

        $payload = $request->payload;
        $theme_name = !empty(env('DATA_INSERT_APP_THEME')) ? env('DATA_INSERT_APP_THEME') : APP_THEME();
        $secret = Utility::GetValueByName('khalti_secret_key',$theme_name);
        $token = $payload['token'];
        $amount = $payload['amount'];
        $khalti = new Khalti();
        $response = $khalti->verifyPayment($secret,$token,$amount);
        if($response['status_code'] == '200')
        {
        // add in  Order table  start
        $order = new Order();
        $order->product_order_id = $product_order_id;
        $order->order_date = date('Y-m-d H:i:s');
        $order->customer_id = !empty($request->customer_id) ? $request->customer_id : 0;
        $order->is_guest = $is_guest;
        $order->product_id = $prodduct_id_array;
        $order->product_json = json_encode($products);
        $order->product_price = $final_sub_total_price;
        $order->coupon_price = $coupon_price;
        $order->delivery_price = $delivery_price;
        $order->tax_price = $tax_price;
        if (!auth('customers')->user()) {
            if ($plan->shipping_method == "on") {
                $order->final_price = $data['shipping_total_price'];
            } else {
                $order->final_price = $final_price + $tax_price;
            }
        }else{
            if ($plan->shipping_method == "on") {
                $order->final_price = $data['shipping_total_price'] + $tax_price;
            } else {
                $order->final_price = $final_price + $tax_price;
            }
        }
        $order->payment_comment = !empty($requests_data['payment_comment']) ? $requests_data['payment_comment'] : '';
        $order->payment_type = $requests_data['payment_type'];
        $order->payment_status = 'Paid';
        $order->delivery_id = $requests_data['method_id'] ?? 0;
        $order->delivery_comment = !empty($requests_data['delivery_comment']) ? $requests_data['delivery_comment'] : '';
        $order->delivered_status = 0;
        $order->reward_points = SetNumber($product_reward_point);
        $order->additional_note = $request->additional_note;
        $order->theme_id = $theme_id;
        $order->store_id = $store->id;
        $order->save();
        // add in  Order table end
        // Utility::paymentWebhook($order);

        $billing_city_id = 0;
        if (!empty($billing['billing_city'])) {
            $cityy = City::where('name', $billing['billing_city'])->first();
            if (!empty($cityy)) {
                $billing_city_id = $cityy->id;
            } else {
                $new_billing_city = new City();
                $new_billing_city->name = $billing['billing_city'];
                $new_billing_city->state_id = $billing['billing_state'];
                $new_billing_city->country_id = $billing['billing_country'];
                $new_billing_city->save();
                $billing_city_id = $new_billing_city->id;
            }
        }

        $delivery_city_id = 0;
        if (!empty($billing['delivery_city'])) {
            $d_cityy = City::where('name', $billing['delivery_city'])->first();
            if (!empty($d_cityy)) {
                $delivery_city_id = $d_cityy->id;
            } else {
                $new_delivery_city = new City();
                $new_delivery_city->name = $billing['delivery_city'];
                $new_delivery_city->state_id = $billing['delivery_state'];
                $new_delivery_city->country_id = $billing['delivery_country'];
                $new_delivery_city->save();
                $delivery_city_id = $new_delivery_city->id;
            }
        }

        $OrderBillingDetail = new OrderBillingDetail();
        $OrderBillingDetail->order_id = $order->id;
        $OrderBillingDetail->product_order_id = $order->product_order_id;
        $OrderBillingDetail->first_name = !empty($billing['firstname']) ? $billing['firstname'] : '';
        $OrderBillingDetail->last_name = !empty($billing['lastname']) ? $billing['lastname'] : '';
        $OrderBillingDetail->email = !empty($billing['email']) ? $billing['email'] : '';
        $OrderBillingDetail->telephone = !empty($billing['billing_user_telephone']) ? $billing['billing_user_telephone'] : '';
        $OrderBillingDetail->address = !empty($billing['billing_address']) ? $billing['billing_address'] : '';
        $OrderBillingDetail->postcode = !empty($billing['billing_postecode']) ? $billing['billing_postecode'] : '';
        $OrderBillingDetail->country = !empty($billing['billing_country']) ? $billing['billing_country'] : '';
        $OrderBillingDetail->state = !empty($billing['billing_state']) ? $billing['billing_state'] : '';
        $OrderBillingDetail->city = $billing_city_id;
        $OrderBillingDetail->theme_id = $theme_id;
        $OrderBillingDetail->delivery_address = !empty($billing['delivery_address']) ? $billing['delivery_address'] : '';
        $OrderBillingDetail->delivery_city = $delivery_city_id;
        $OrderBillingDetail->delivery_postcode = !empty($billing['delivery_postcode']) ? $billing['delivery_postcode'] : '';
        $OrderBillingDetail->delivery_country = !empty($billing['delivery_country']) ? $billing['delivery_country'] : '';
        $OrderBillingDetail->delivery_state = !empty($billing['delivery_state']) ? $billing['delivery_state'] : '';
        $OrderBillingDetail->save();

        // add in Order Coupon Detail table start
        if (!empty($requests_data['coupon_info'])) {
            $coupon_data = $requests_data['coupon_info'];
            $Coupon = Coupon::find($coupon_data['coupon_id']);
            // coupon stock decrease end

            // Order Coupon history
            $OrderCouponDetail = new OrderCouponDetail();
            $OrderCouponDetail->order_id = $order->id;
            $OrderCouponDetail->product_order_id = $order->product_order_id;
            $OrderCouponDetail->coupon_id = $coupon_data['coupon_id'];
            $OrderCouponDetail->coupon_name = $coupon_data['coupon_name'];
            $OrderCouponDetail->coupon_code = $coupon_data['coupon_code'];
            $OrderCouponDetail->coupon_discount_type = $coupon_data['coupon_discount_type'];
            $OrderCouponDetail->coupon_discount_number = $coupon_data['coupon_discount_number'];
            $OrderCouponDetail->coupon_discount_amount = $coupon_data['coupon_discount_amount'];
            $OrderCouponDetail->coupon_final_amount = $coupon_data['coupon_final_amount'];
            $OrderCouponDetail->theme_id = $theme_id;
            $OrderCouponDetail->save();

            // Coupon history
            $UserCoupon = new UserCoupon();
            $UserCoupon->user_id = !empty($request->user_id) ? $request->user_id : '0';
            $UserCoupon->coupon_id = $Coupon->id;
            $UserCoupon->amount = $coupon_data['coupon_discount_amount'];
            $UserCoupon->order_id = $order->id;
            $UserCoupon->date_used = now();
            $UserCoupon->theme_id = $theme_id;
            $UserCoupon->save();

            $discount_string = '-' . $coupon_data['coupon_discount_amount'];
            $CURRENCY = Utility::GetValueByName('CURRENCY');
            $CURRENCY_NAME = Utility::GetValueByName('CURRENCY_NAME');
            if ($coupon_data['coupon_discount_type'] == 'flat') {
                $discount_string .= $CURRENCY;
            } else {
                $discount_string .= '%';
            }

            $discount_string .= ' ' . __('for all products');
            $order_array['coupon']['code'] = $coupon_data['coupon_code'];
            $order_array['coupon']['discount_string'] = $discount_string;
            $order_array['coupon']['price'] = SetNumber($coupon_data['coupon_final_amount']);
        }
        // add in Order Coupon Detail table end
        if($requests_data['tax_id_value'] ?? ''){
            $taxes = TaxMethod::where('tax_id',$request['tax_id_value'])->where('theme_id', $theme_id)->where('store_id', $store->id)->orderBy('priority', 'asc')->get();
            $other_info = json_decode($requests_data['billing_info']);
            $country = !empty($other_info->delivery_country) ? $other_info->delivery_country :'';
            $state_id = !empty($other_info->delivery_state) ? $other_info->delivery_state : '';
            $city_id = !empty($other_info->delivery_city) ? $other_info->delivery_city : '';
            foreach ($taxes as $tax) {
                $countryMatch = (!$tax->country_id || $country == $tax->country_id);
                $stateMatch = (!$tax->state_id || $state_id == $tax->state_id);
                $cityMatch = (!$tax->city_id || $city_id == $tax->city_id);

                if ($countryMatch && $stateMatch && $cityMatch) {
                    $OrderTaxDetail = new OrderTaxDetail();
                    $OrderTaxDetail->order_id = $order->id;
                    $OrderTaxDetail->product_order_id = $order->product_order_id;
                    $OrderTaxDetail->tax_id = $tax->id;
                    $OrderTaxDetail->tax_name = $tax->name;
                    $OrderTaxDetail->tax_discount_amount = $tax->tax_rate;
                    $OrderTaxDetail->tax_final_amount = $requests_data['tax_price'];
                    $OrderTaxDetail->theme_id = $theme_id;
                    $OrderTaxDetail->save();
                }
            }
        }

        //activity log
        ActivityLog::order_entry(['customer_id'=>$order->customer_id ,
        'order_id'=> $order->product_order_id ,
        'order_date' => $order->order_date ,
        'products' =>$order->product_id,
        'final_price' =>$order->final_price,
        'payment_type' =>$order->payment_type,
        'theme_id'=>$order->theme_id,
        'store_id'=>$order->store_id]);
        $other_info = $request->billing_info;

        //Order Mail
        $order_email = !empty($other_info->email) ? $other_info->email : '';
        $owner=User::find($store->created_by);
        $owner_email=$owner->email;
        $order_id    = Crypt::encrypt($order->id);

        // try
        // {
        //     $dArr = [
        //     'order_id' => $order->product_order_id,
        //     ];

        //     // Send Email
        //     $resp = Utility::sendEmailTemplate('Order Created', $order_email, $dArr, $owner,$store, $order_id);
        //     $resp1=Utility::sendEmailTemplate('Order Created For Owner', $owner_email, $dArr,$owner, $store, $order_id);
        // }
        // catch(\Exception $e)
        // {
        //     $smtp_error = __('E-Mail has been not sent due to SMTP configuration');
        // }

        foreach ($products as $product) {
            $product_data = Product::find($product->product_id);

            if ($product_data) {
                if ($product_data->variant_product == 0) {
                    if ($product_data->track_stock == 1) {
                        OrderNote::order_note_data([
                            'customer_id' => !empty($request->customer_id) ? $request->customer_id : '0',
                            'order_id' => $order->id,
                            'product_name' => !empty($product_data->name)?$product_data->name: '',
                            'variant_product' => $product_data->variant_product,
                            'product_stock' => !empty($product_data->product_stock) ? $product_data->product_stock : '',
                            'status' => 'Stock Manage',
                            'theme_id' => $order->theme_id,
                            'store_id' => $order->store_id,
                        ]);
                    }
                } else {
                    $variant_data = ProductVariant::find($product->variant_id);
                    $variationOptions = explode(',', $variant_data->variation_option);
                    $option = in_array('manage_stock', $variationOptions);
                    if ($option == true) {
                        OrderNote::order_note_data([
                            'customer_id' => !empty($request->customer_id) ? $request->customer_id : '0',
                            'order_id' => !empty($order->id) ? $order->id : '',
                            'product_name' => !empty($product_data->name)?$product_data->name: '',
                            'variant_product' => $product_data->variant_product,
                            'product_variant_name' => !empty($variant_data->variant) ? $variant_data->variant : '',
                            'product_stock' => !empty($variant_data->stock) ? $variant_data->stock : '',
                            'status' => 'Stock Manage',
                            'theme_id' => $order->theme_id,
                            'store_id' => $order->store_id,
                        ]);
                    }
                }
            }
        }

        OrderNote::order_note_data([
            'customer_id' => !empty($request->customer_id) ? $request->customer_id : '0',
            'order_id' => $order->id,
            'product_order_id' => $order->product_order_id,
            'delivery_status' => 'Pending',
            'status' => 'Order Created',
            'theme_id' => $order->theme_id,
            'store_id' => $order->store_id
        ]);

        try{
            $msg = __("Hello, Welcome to $store->name .Hi,your order id is $order->product_order_id, Thank you for Shopping We received your purchase request, we'll be in touch shortly!. ") ;
            // $mess = Utility::SendMsgs('Order Created',$OrderBillingDetail->telephone, $msg);
        } catch(\Exception $e)
        {
            $smtp_error = __('Invalid OAuth access token - Cannot parse access token');
        }
        // add in Order Tax Detail table end

        if (!empty($order) && !empty($OrderBillingDetail)) {

            $order_array['order_id'] = $order->id;
            $cart_array = [];
            $cart_json = json_encode($cart_array);
            Cookie::queue('cart', $cart_json, 1440);
            $arr = [
                'data' => $order->product_order_id,
                'status_code' => $response['status_code'],
                'store_complete'=>route('order.summary',$slug),
            ];
            Session::put('khalti_order_id', $order->product_order_id);
            return $arr;
        } else {
            return $this->error(['message' => 'Somthing went wrong.']);
        }
    } else {
        return redirect()->back()->with('error', __('Transaction has been failed'));
    }
    }
}
