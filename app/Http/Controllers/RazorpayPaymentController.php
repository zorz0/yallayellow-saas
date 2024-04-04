<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\PlanUserCoupon;
use App\Models\PlanCoupon;
use App\Models\PlanOrder;
use App\Models\Cart;
use App\Models\AppSetting;
use App\Models\Order;
use App\Models\Plan;
use App\Models\Store;
use App\Models\Utility;
use App\Models\Product;
use App\Models\OrderBillingDetail;
use App\Models\City;
use App\Models\OrderCouponDetail;
use App\Models\UserCoupon;
use App\Models\OrderTaxDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cookie;
use App\Http\Controllers\Api\ApiController;
use App\Models\ActivityLog;
use Session;
use App\Models\Admin;
use App\Models\OrderNote;
use App\Models\Setting;
use Illuminate\Support\Facades\Crypt;

class RazorpayPaymentController extends Controller
{
    //
    public $secret_key;
    public $public_key;
    public $is_enabled;


    public function paymentConfig()
    {
        $user = Auth::user();


        if (Auth::user()->type == 'admin') {
            $payment_setting = getSuperAdminAllSetting();
        } else {
            $payment_setting = Utility::getCompanyPaymentSetting($user);
        }
        $this->secret_key = isset($payment_setting['razorpay_secret_key']) ? $payment_setting['razorpay_secret_key'] : '';
        $this->public_key = isset($payment_setting['razorpay_public_key']) ? $payment_setting['razorpay_public_key'] : '';
        $this->is_enabled = isset($payment_setting['is_razorpay_enabled']) ? $payment_setting['is_razorpay_enabled'] : 'off';

        return $this;
    }

    public function planPayWithRazorpay(Request $request)
    {
        $admin_payment_setting = getSuperAdminAllSetting();
        $currency = $admin_payment_setting['CURRENCY_NAME'];
        $planID    = \Illuminate\Support\Facades\Crypt::decrypt($request->plan_id);
        $plan      = Plan::find($planID);
        $authuser  = \Auth::user();
        $coupon_id = '';
        if ($plan) {

            $price = $plan->price;
            if (isset($request->coupon) && !empty($request->coupon)) {
                $request->coupon = trim($request->coupon);
                $coupons         = PlanCoupon::where('code', strtoupper($request->coupon))->where('is_active', '1')->first();
                if (!empty($coupons)) {
                    $usedCoupun             = $coupons->used_coupon();
                    $discount_value         = ($price / 100) * $coupons->discount;
                    $plan->discounted_price = $price - $discount_value;

                    if ($usedCoupun >= $coupons->limit) {
                        return redirect()->back()->with('error', __('This coupon code has expired.'));
                    }
                    $price     = $price - $discount_value;
                    $coupon_id = $coupons->id;
                } else {
                    return redirect()->back()->with('error', __('This coupon code is invalid or has expired.'));
                }
            }

            if ($price <= 0) {
                $authuser->plan = $plan->id;
                $authuser->save();

                $assignPlan = $authuser->assignPlan($plan->id);

                if ($assignPlan['is_success'] == true && !empty($plan)) {

                    $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
                    Order::create(
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
                            'price_currency' =>$currency,
                            'txn_id' => '',
                            'payment_type' => 'Razorpay',
                            'payment_status' => 'succeeded',
                            'receipt' => null,
                            'user_id' => $authuser->id,
                        ]
                    );
                    $res['msg']  = __("Plan successfully upgraded.");
                    $res['flag'] = 2;

                    return $res;
                } else {
                    return Utility::error_res(__('Plan fail to upgrade.'));
                }
            }

            $res_data['email']       = Auth::user()->email;
            $res_data['total_price'] = $price;
            $res_data['currency']    = $currency;
            $res_data['flag']        = 1;
            $res_data['coupon']      = $coupon_id;

            return $res_data;
        } else {
            return Utility::error_res(__('Plan is deleted.'));
        }


    }

    public function getPaymentStatus(Request $request, $pay_id, $plan)
    {
        $payment = $this->paymentConfig();
        $planID  = \Illuminate\Support\Facades\Crypt::decrypt($plan);
        $plan    = Plan::find($planID);
        $user    = \Auth::user();
        $admin_payment_setting = getSuperAdminAllSetting();
        $currency = $admin_payment_setting['CURRENCY_NAME'];
        if ($plan) {
            try {
                $orderID = time();
                $ch      = curl_init('https://api.razorpay.com/v1/payments/' . $pay_id . '');
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
                curl_setopt($ch, CURLOPT_USERPWD, $this->public_key . ':' . $this->secret_key); // Input your Razorpay Key Id and Secret Id here
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $response = json_decode(curl_exec($ch));


                    if ($request->has('coupon_id') && $request->coupon_id != '') {
                        $coupons = PlanCoupon::find($request->coupon_id);
                        if (!empty($coupons)) {
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
                    }

                    $price = $plan->price;

                    $order                 = new PlanOrder();
                    $order->order_id       = $orderID;
                    $order->name           = $user->name;
                    $order->card_number    = '';
                    $order->card_exp_month = '';
                    $order->card_exp_year  = '';
                    $order->plan_name      = $plan->name;
                    $order->plan_id        = $plan->id;
                    $order->price          =  $response->amount / 100;
                    $order->price_currency = $currency;
                    $order->txn_id         = isset($response->id) ? $response->id : $pay_id;
                    $order->payment_type   = __('Razorpay');
                    $order->payment_status = 'success';
                    $order->receipt        = '';
                    $order->user_id        = $user->id;
                    $order->save();


                    $assignPlan = $user->assignPlan($plan->id, $request->payment_frequency);

                    if ($assignPlan['is_success']) {
                        return redirect()->route('plan.index')->with('success', __('Plan activated Successfully!'));
                    } else {
                        return redirect()->route('plan.index')->with('error', __($assignPlan['error']));
                    }

            } catch (\Exception $e) {
               return redirect()->route('plans.index')->with('error', __('Plan not found!'));
            }
        }
    }

}
