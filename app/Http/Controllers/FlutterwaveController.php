<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\ApiController;
use App\Models\ActivityLog;
use App\Models\AppSetting;
use App\Models\Cart;
use App\Models\City;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderBillingDetail;
use App\Models\OrderCouponDetail;
use App\Models\OrderTaxDetail;
use App\Models\Plan;
use App\Models\PlanOrder;
use App\Models\PlanCoupon;
use App\Models\PlanUserCoupon;
use App\Models\Product;
use App\Models\Store;
use App\Models\UserCoupon;
use App\Models\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Session;
use App\Models\Admin;
use App\Models\OrderNote;
use App\Models\Setting;
use Illuminate\Support\Facades\Crypt;

class FlutterwaveController extends Controller
{
    public $secret_key;
    public $flutterwave_public_key;
    public $is_enabled;
    public $currancy;



    public function paymentConfig()
    {
        if (Auth::check()) {
            $user = Auth::user();
        }

        // $creatorId = \Auth::user()->creatorId();
        if (\Auth::user()->type == 'admin') {
            $payment_setting = getSuperAdminAllSetting();
        } else {

            $payment_setting = Utility::getCompanyPaymentSetting($user);
        }
        $this->currancy = isset($payment_setting['currency']) ? $payment_setting['currency'] : '';
        $this->secret_key = isset($payment_setting['flutterwave_secret_key']) ? $payment_setting['flutterwave_secret_key'] : '';
        $this->flutterwave_public_key = isset($payment_setting['flutterwave_public_key']) ? $payment_setting['flutterwave_public_key'] : '';
        $this->is_enabled = isset($payment_setting['is_flutterwave_enabled']) ? $payment_setting['is_flutterwave_enabled'] : 'off';

        return $this;
    }


    public function addpayment(Request $request)
    {
        $planID    = \Illuminate\Support\Facades\Crypt::decrypt($request->plan_id);
        $plan      = Plan::find($planID);
        $authuser  = Auth::user();
        $coupon_id = '';
        $admin_payments_details = getSuperAdminAllSetting();
        $CURRENCY = !empty($admin_payments_details['CURRENCY_NAME']) ? $admin_payments_details['CURRENCY_NAME'] : 'USD';
        // dd($admin_payments_details);
        if ($plan) {
            $price = $plan->price;
            $coupons = PlanCoupon::where('code', strtoupper($request->coupon))->where('is_active', '1')->first();
            if (!empty($coupons)) {
                $usedCoupun     = $coupons->used_coupon();
                $discount_value = ($plan->price / 100) * $coupons->discount;
                $price          = $plan->price - $discount_value;
                //dd($usedCoupun);
                if ($coupons->limit == $usedCoupun) {
                    return redirect()->back()->with('error', __('This coupon code has expired.'));
                }
                $coupon_id = $coupons->id;


                if ($price < 1) {
                    $order_id = strtoupper(str_replace('.', '', uniqid('', true)));
                    $statuses = 'success';
                    if ($coupon_id != '') {


                        $userCoupon         = new PlanUserCoupon();
                        $userCoupon->user_id   = $authuser->id;
                        $userCoupon->coupon_id = $coupons->id;
                        $userCoupon->order  = $order_id;
                        $userCoupon->save();
                        $usedCoupun = $coupons->used_coupon();
                        if ($coupons->limit <= $usedCoupun) {
                            $coupons->is_active = 0;
                            $coupons->save();
                        }
                    }
                }
            }
            if ($price <= 0) {
                $authuser->plan = $plan->id;
                $authuser->save();

                $assignPlan = $authuser->assignPlan($plan->id);

                if ($assignPlan['is_success'] == true && !empty($plan)) {
                    $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
                    PlanOrder::create(
                        [
                            'order_id' => $orderID,
                            'name' => null,
                            'email' => null,
                            'card_number' => null,
                            'card_exp_month' => null,
                            'card_exp_year' => null,
                            'plan_name' => $plan->name,
                            'plan_id' => $plan->id,
                            'price' => $price == null ? 0 : $price,
                            'price_currency' => $CURRENCY,
                            'txn_id' => '',
                            'payment_type' => __('Flutterwave'),
                            'payment_status' => 'succeeded',
                            'receipt' => null,
                            'user_id' => $authuser->id,
                        ]
                    );
                    return redirect()->route('plan.index')->with('success', __('Plan activated Successfully!'));

                    $assignPlan = $authuser->assignPlan($plan->id);

                    $res['msg']  = __("Plan successfully upgraded.");
                    $res['flag'] = 2;
                    return $res;

                } else {

                    return redirect()->back()->with('error', __($assignPlan['error']));
                }
            }


            $res_data['email']       = \Auth::user()->email;;
            $res_data['total_price'] = $price;
            $res_data['currency']    = $CURRENCY;
            $res_data['flag']        = 1;
            $res_data['coupon']      = $coupon_id;

            return $res_data;


        } else {
            return Utility::error_res(__('Plan is deleted.'));
        }
    }

    public function getPaymentStatus(Request $request, $pay_id, $plan)
    {

        $this->paymentConfig();

        $planID         = \Illuminate\Support\Facades\Crypt::decrypt($plan);
        $plan           = Plan::find($planID);
        $result = array();
        $user = Auth::user();
        if ($plan) {
            try {
                $orderID = time();
                $data    = array(
                    'txref' => $pay_id,
                    'SECKEY' => $this->secret_key,
                    //secret key from pay button generated on rave dashboard
                );
                // make request to endpoint using unirest.
                $headers = array('Content-Type' => 'application/json');
                $body    = \Unirest\Request\Body::json($data);
                $url     = "https://api.ravepay.co/flwv3-pug/getpaidx/api/v2/verify"; //please make sure to change this to production url when you go live

                // Make `POST` request and handle response with unirest
                $response = \Unirest\Request::post($url, $headers, $body);
                if (!empty($response)) {
                    $response = json_decode($response->raw_body, true);
                }
                if (isset($response['status']) && $response['status'] == 'success') {
                    $paydata = $response['data'];
                    if (!empty($request->coupon_id)) {
                        $coupons = PlanCoupon::where('id', strtoupper($request->coupon_id))->where('is_active', '1')->first();
                        if (!empty($coupons)) {
                            $usedCoupun     = $coupons->used_coupon();
                            $discount_value = ($plan->price / 100) * $coupons->discount;
                            $price          = $plan->price - $discount_value;

                            if ($coupons->limit == $usedCoupun) {
                                return redirect()->back()->with('error', __('This coupon code has expired.'));
                            }
                        } else {
                            return redirect()->back()->with('error', __('This coupon code is invalid or has expired.'));
                        }
                    }
                    if (!empty($request->coupon_id)) {
                        $userCoupon         = new PlanUserCoupon();
                        $userCoupon->user_id   = $user->id;
                        $userCoupon->coupon_id = $coupons->id;
                        $userCoupon->order  = $orderID;
                        $userCoupon->save();

                        $usedCoupun = $coupons->used_coupon();
                        if ($coupons->limit <= $usedCoupun) {
                            $coupons->is_active = 0;
                            $coupons->save();

                        }
                    }
                    $order                 = new PlanOrder();
                    $order->order_id       = $orderID;
                    $order->name           = $user->name;
                    $order->card_number    = '';
                    $order->card_exp_month = '';
                    $order->card_exp_year  = '';
                    $order->plan_name      = $plan->name;
                    $order->plan_id        = $plan->id;
                    $order->price          = isset($paydata['amount']) ? $paydata['amount'] : 0;
                    $order->price_currency = $this->currancy;
                    $order->txn_id         = isset($paydata['txid']) ? $paydata['txid'] : $pay_id;
                    $order->payment_type   = __('Flutterwave');
                    $order->payment_status = 'Succeeded';
                    $order->receipt        = '';
                    $order->user_id        = $user->id;
                    $order->save();

                    $assignPlan = $user->assignPlan($plan->id, $request->payment_frequency);


                    if ($assignPlan['is_success']) {
                        return redirect()->route('plan.index')->with('success', __('Plan activated Successfully!'));
                    } else {
                        return redirect()->route('plan.index')->with('error', __($assignPlan['error']));
                    }
                } else {
                    return redirect()->route('plan.index')->with('error', __('Transaction has been failed! '));
                }
            } catch (\Exception $e) {
                return redirect()->route('plan.index')->with('error', __('Plan not found!'));
            }
        }
    }

}
