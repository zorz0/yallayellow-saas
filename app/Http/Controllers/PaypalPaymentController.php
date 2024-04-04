<?php

namespace App\Http\Controllers;

use App\Models\AppSetting;
use App\Http\Controllers\Api\ApiController;
use App\Models\ActivityLog;
use App\Models\Cart;
use App\Models\City;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderBillingDetail;
use App\Models\OrderCouponDetail;
use App\Models\OrderTaxDetail;
use App\Models\Plan;
use App\Models\PlanCoupon;
use App\Models\PlanOrder;
use App\Models\PlanUserCoupon;
use App\Models\Product;
use App\Models\Utility;
use App\Models\Store;
use App\Models\UserCoupon;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use App\Models\Admin;
use App\Models\OrderNote;
use App\Models\Setting;
use Illuminate\Support\Facades\Crypt;

class PaypalPaymentController extends Controller
{

    public function paymentConfig()
    {
        if (\Auth::check()) {
            $payment_setting = getSuperAdminAllSetting();
        }

        if ($payment_setting['paypal_mode'] == 'live') {
            config([
                'paypal.live.client_id' => isset($payment_setting['paypal_client_id']) ? $payment_setting['paypal_client_id'] : '',
                'paypal.live.client_secret' => isset($payment_setting['paypal_secret_key']) ? $payment_setting['paypal_secret_key'] : '',
                'paypal.mode' => isset($payment_setting['paypal_mode']) ? $payment_setting['paypal_mode'] : '',
            ]);
        } else {
            config([
                'paypal.sandbox.client_id' => isset($payment_setting['paypal_client_id']) ? $payment_setting['paypal_client_id'] : '',
                'paypal.sandbox.client_secret' => isset($payment_setting['paypal_secret_key']) ? $payment_setting['paypal_secret_key'] : '',
                'paypal.mode' => isset($payment_setting['paypal_mode']) ? $payment_setting['paypal_mode'] : '',
            ]);
        }
    }
    public function addpayment(Request $request)
    {

        $planID = \Illuminate\Support\Facades\Crypt::decrypt($request->plan_id);

        $plan   = Plan::find($planID);
        $user = Auth::user();
        $this->paymentconfig($user);
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $get_amount = $plan->price;
        $coupon_id = '';
        if ($plan) {
            try {
                $coupon_id = 0;
                $price     = $plan->price;

                    if(!empty($request->coupon))
                    {
                        $coupons = PlanCoupon::where('code', strtoupper($request->coupon))->where('is_active', '1')->first();
                        if(!empty($coupons))
                        {
                            $usedCoupun     = $coupons->used_coupon();
                            $discount_value = ($plan->price / 100) * $coupons->discount;
                            $price          = $plan->price - $discount_value;
                                //dd($usedCoupun);
                            if($coupons->limit == $usedCoupun)
                            {
                                return redirect()->route('plan.index')->with('error', __('This coupon code has expired.'));
                            }
                                $coupon_id = $coupons->id;

                        if (!empty($request->coupon)) {

                            $order_id = strtoupper(str_replace('.', '', uniqid('', true)));
                            $statuses = 'success';
                            if ($coupon_id != '') {


                                $userCoupon         = new PlanUserCoupon();
                                $userCoupon->user_id   = $user->id;
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
                    } else {
                        return redirect()->route('plan.index')->with('error', __('This coupon code is invalid or has expired.'));
                    }
                }
                //dd($price);
                $this->paymentConfig($user);
                $payment_setting = getSuperAdminAllSetting();
                $paypalToken = $provider->getAccessToken();
                $response = $provider->createOrder([
                    "intent" => "CAPTURE",
                    "application_context" => [
                        "return_url" => route('plan.get.payment.status', [$plan->id, $price, 'coupon_id' => $coupon_id]),
                        "cancel_url" =>  route('plan.get.payment.status', [$plan->id, $price, $coupon_id]),
                    ],
                    "purchase_units" => [
                        0 => [
                            "amount" => [
                                "currency_code" => !empty($payment_setting['CURRENCY_NAME']) ? $payment_setting['CURRENCY_NAME'] : 'USD',
                                "value" => $price
                            ]
                        ]
                    ]
                ]);
                if (isset($response['id']) && $response['id'] != null) {
                    // redirect to approve href
                    foreach ($response['links'] as $links) {
                        if ($links['rel'] == 'approve') {
                            return redirect()->away($links['href']);
                        }
                    }
                    return redirect()
                        ->route('plan.index')
                        ->with('error', 'Something went wrong.');
                } else {
                    return redirect()
                        ->route('plan.index')
                        ->with('error', $response['message'] ?? 'Something went wrong.');
                }
            } catch (\Exception $e) {

                return redirect()->route('plan.index')->with('error', __($e->getMessage()));
            }
        } else {
            return redirect()->route('plan.index')->with('error', __('Plan is deleted.'));
        }
    }

    public function planGetPaymentStatus(Request $request, $plan_id, $amount)
    {
        $this->paymentconfig();
        $user = Auth::user();
        $plan = Plan::find($plan_id);
        if ($plan) {

            // $this->paymentConfig();
            $provider = new PayPalClient;
            $provider->setApiCredentials(config('paypal'));
            $provider->getAccessToken();
            $response = $provider->capturePaymentOrder($request['token']);
            $order_id = strtoupper(str_replace('.', '', uniqid('', true)));

            // $status  = ucwords(str_replace('_', ' ', $result['state']));



            if (isset($response['status']) && $response['status'] == 'COMPLETED') {
                if ($response['status'] == 'COMPLETED') {
                    $statuses = 'success';
                }
                $admin_payment_setting = getSuperAdminAllSetting();
                $currency = $admin_payment_setting['CURRENCY_NAME'];

                $order = new PlanOrder();
                $order->order_id = $order_id;
                $order->name = $user->name;
                $order->card_number = '';
                $order->card_exp_month = '';
                $order->card_exp_year = '';
                $order->plan_name = $plan->name;
                $order->plan_id = $plan->id;
                $order->price = $amount;
                $order->price_currency = $currency;
                $order->payment_type = __('PAYPAL');
                $order->payment_status = $statuses;
                $order->txn_id = '';
                $order->receipt = '';
                $order->user_id = $user->id;
                $order->store_id = getCurrentStore();
                $order->save();


                if ($request->has('coupon_id') && $request->coupon_id != '') {
                    $coupons = PlanCoupon::find($request->coupon_id);
                    if (!empty($coupons)) {
                        $userCoupon = new PlanUserCoupon();
                        $userCoupon->user_id = $user->id;
                        $userCoupon->coupon_id = $coupons->id;
                        $userCoupon->order = $order_id;
                        $userCoupon->save();
                        $usedCoupun = $coupons->used_coupon();
                        if ($coupons->limit <= $usedCoupun) {
                            $coupons->is_active = 0;
                            $coupons->save();
                        }
                    }
                }

                $assignPlan = $user->assignPlan($plan->id);
                if ($assignPlan['is_success']) {
                    return redirect()->route('plan.index')->with('success', __('Plan activated Successfully.'));
                } else {
                    return redirect()->route('plan.index')->with('error', __($assignPlan['error']));
                }

                return redirect()
                    ->route('plan.index')
                    ->with('success', 'Transaction complete.');
            } else {
                return redirect()
                    ->route('plan.index')
                    ->with('error', $response['message'] ?? 'Something went wrong.');
            }
        } else {
            return redirect()->route('plan.index')->with('error', __('Plan is deleted.'));
        }
    }

}
