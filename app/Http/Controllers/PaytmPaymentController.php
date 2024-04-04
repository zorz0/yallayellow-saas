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
use App\Models\PlanCoupon;
use App\Models\PlanOrder;
use App\Models\PlanUserCoupon;
use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use App\Models\UserCoupon;
use App\Models\Utility;
use AWS\CRT\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use PaytmWallet;
use Session;
use App\Models\Admin;
use App\Models\OrderNote;
use App\Models\Setting;
use Illuminate\Support\Facades\Crypt;

class PaytmPaymentController extends Controller
{
    public $currancy;

    public function paymentConfig()
    {
        if (\Auth::check()) {
            $payment_setting = getSuperAdminAllSetting();
            config(
                [
                    'services.paytm-wallet.env' => isset($payment_setting['paytm_mode']) ? $payment_setting['paytm_mode'] : '',
                    'services.paytm-wallet.merchant_id' => isset($payment_setting['paytm_merchant_id']) ? $payment_setting['paytm_merchant_id'] : '',
                    'services.paytm-wallet.merchant_key' => isset($payment_setting['paytm_merchant_key']) ? $payment_setting['paytm_merchant_key'] : '',
                    'services.paytm-wallet.merchant_website' => 'WEBSTAGING',
                    'services.paytm-wallet.channel' => 'WEB',
                    'services.paytm-wallet.industry_type' => isset($payment_setting['paytm_industry_type']) ? $payment_setting['paytm_industry_type'] : '',
                ]
            );
        }

    }
    public function planPayWithPaytm(Request $request)
    {

        $this->paymentConfig();

        $planID         = \Illuminate\Support\Facades\Crypt::decrypt($request->plan_id);
        $plan           = Plan::find($planID);
        $authuser       = Auth::user();
        $coupons_id = '';
        if ($plan) {
            /* Check for code usage */
            $plan->discounted_price = false;
            $price                  = $plan->price;

            $coupons = PlanCoupon::where('code', strtoupper($request->coupon))->where('is_active', '1')->first();
            $admin_payments_details = getSuperAdminAllSetting();
            $CURRENCY = !empty($admin_payments_details['CURRENCY_NAME']) ? $admin_payments_details['CURRENCY_NAME'] : 'USD';
            if (!empty($coupons))
            {
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
                if($price <= 0)
                {

                    $authuser->plan = $plan->id;
                    $authuser->save();

                    $assignPlan = $authuser->assignPlan($plan->id);

                    if ($assignPlan['is_success'] == true && !empty($plan)) {
                        if (!empty($authuser->payment_subscription_id) && $authuser->payment_subscription_id != '') {
                            try {
                                $authuser->cancel_subscription($authuser->id);
                            } catch (\Exception $exception) {
                                Log::debug($exception->getMessage());
                            }
                        }

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
                                'payment_type' => 'Paytm',
                                'payment_status' => 'succeeded',
                                'receipt' => null,
                                'user_id' => $authuser->id,
                            ]
                        );
                        $res['msg'] = __("Plan successfully upgraded.");
                        $res['flag'] = 2;
                        return $res;
                    } else {
                        return redirect()->back()->with(__('Plan fail to upgrade.'));
                    }
                }
            }

            try {
                $call_back = route('plan.paytm', [$request->plan_id]);
                $payment = PaytmWallet::with('receive');
                $payment->prepare(
                    [
                        'order' => date('Y-m-d') . '-' . strtotime(date('Y-m-d H:i:s')),
                        'user' => Auth::user()->id,
                        'mobile_number' => $request->mobile_number,
                        'email' => Auth::user()->email,
                        'amount' => $price,
                        'plan' => $plan->id,
                        'callback_url' => $call_back
                    ]
                );
                return $payment->receive();

            } catch (\Exception $e) {
                return redirect()->route('plan.index')->with('error', __($e->getMessage()));
            }
        } else {
            return redirect()->back()->with(__('Plan is deleted.'));
        }
    }
    public function getPaymentStatus(Request $request, $plan, $coupon = null)
    {

        $transaction = $this->paymentConfig();
        $planID  = \Illuminate\Support\Facades\Crypt::decrypt($plan);
        $plan    = Plan::find($planID);
        $user   = Auth::user();
        $orderID = time();
        if ($plan) {
            try {
                $transaction = PaytmWallet::with('receive');
                $response    = $transaction->response();

                if ($transaction->isSuccessful()) {

                    if(!empty($request->coupon))
                    {
                        $coupons = PlanCoupon::where('code', strtoupper($request->coupon))->where('is_active', '1')->first();
                        if(!empty($coupons))
                        {
                            $usedCoupun     = $coupons->used_coupon();
                            $discount_value = ($plan->price / 100) * $coupons->discount;
                            $price          = $plan->price - $discount_value;

                            if($coupons->limit == $usedCoupun)
                            {
                                return redirect()->back()->with('error', __('This coupon code has expired.'));
                            }
                        }
                        else
                        {
                            return redirect()->back()->with('error', __('This coupon code is invalid or has expired.'));
                        }
                    }

                    $order                 = new PlanOrder();


                    $order->order_id       = $orderID;
                    $order->name           = isset($user->name) ? $user->name : '';
                    $order->card_number    = '';
                    $order->card_exp_month = '';
                    $order->card_exp_year  = '';
                    $order->plan_name      = $plan->name;
                    $order->plan_id        = $plan->id;
                    $order->price          = isset($request->TXNAMOUNT) ? $request->TXNAMOUNT : 0;
                    $order->price_currency = isset($request->CURRENCY_CODE) ? $request->CURRENCY_CODE : 'NFD';
                    $order->txn_id         = isset($request->TXNID) ? $request->TXNID : '';
                    $order->payment_type   = __('paytm');
                    $order->payment_status = 'success';
                    $order->receipt        = '';
                    $order->user_id        = $user->id;
                    $order->save();
                    $assignPlan = $user->assignPlan($plan->id, $request->payment_frequency);

                    if(!empty($request->coupon))
                    {
                        $userCoupon         = new PlanUserCoupon();
                        $userCoupon->user_id   = $objUser->id;
                        $userCoupon->coupon_id = $coupons->id;
                        $userCoupon->order  = $orderID;
                        $userCoupon->save();

                        $usedCoupun = $coupons->used_coupon();
                        if($coupons->limit <= $usedCoupun)
                        {
                            $coupons->is_active = 0;
                            $coupons->save();
                        }

                    }
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
