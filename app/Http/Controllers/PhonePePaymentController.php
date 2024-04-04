<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\Plan;
use App\Models\PlanOrder;
use App\Models\PlanCoupon;
use App\Models\Coupon;
use App\Models\PlanUserCoupon;
use Illuminate\Http\Request;
use Dipesh79\LaravelPhonePe\LaravelPhonePe;

class PhonePePaymentController extends Controller
{
    //

    public function planPayWithPhonePe(Request $request)
    {
        $payment_setting = getSuperAdminAllSetting();
        $tap_secret_key = isset($payment_setting['tap_secret_key']) ? $payment_setting['tap_secret_key'] : '';

        $currency = isset($payment_setting['CURRENCY_NAME']) ? $payment_setting['CURRENCY_NAME'] : 'USD';
        $planID = \Illuminate\Support\Facades\Crypt::decrypt($request->plan_id);

        $plan = Plan::find($planID);
        $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
        $user = \Auth::user();
        if ($plan) {
            try {

                if ($payment_setting['phonepe_mode'] == 'production') {
                    config(
                        [
                            'phonepe.production.merchant_id' => isset($payment_setting['phonepe_merchant_key']) ? $payment_setting['phonepe_merchant_key'] : '',
                            'phonepe.production.merchant_user_id' => isset($payment_setting['phonepe_merchant_user_id']) ? $payment_setting['phonepe_merchant_user_id'] : '',
                            'phonepe.production.salt_key' => isset($payment_setting['phonepe_salt_key']) ? $payment_setting['phonepe_salt_key'] : '',
                            'phonepe.env' => isset($payment_setting['phonepe_mode']) ? $payment_setting['phonepe_mode'] : '',
                            'phonepe.saltIndex' => '1',
                            'phonepe.callBackUrl' => route('plan.get.phonepe.status'),
                        ]
                    );
                } else {
                    config(
                        [
                            'phonepe.merchantId' => isset($payment_setting['phonepe_merchant_key']) ? $payment_setting['phonepe_merchant_key'] : '',
                            'phonepe.merchantUserId' => isset($payment_setting['phonepe_merchant_user_id']) ? $payment_setting['phonepe_merchant_user_id'] : '',
                            'phonepe.saltKey' => isset($payment_setting['phonepe_salt_key']) ? $payment_setting['phonepe_salt_key'] : '',
                            'phonepe.env' => isset($payment_setting['phonepe_mode']) ? $payment_setting['phonepe_mode'] : '',
                            'phonepe.saltIndex' => '1',
                            'phonepe.callBackUrl' => route('plan.get.phonepe.status'),
                        ]
                    );
                }


                $provider = new LaravelPhonePe();
                $price = $plan->price;
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

                $response = $provider->makePayment(
                    $price,
                    $user->mobile_no,
                    $callback_url = route('plan.get.phonepe.status', [
                        'plan' => $plan->id,
                        'amount' => $price,
                        'coupon_code' => $request->coupon_code
                    ]),
                    '1'
                );
                return redirect()->away($response);

            } catch (\Exception $e) {
                return redirect()->route('plan.index')->with('error', __($e->getMessage()));
            }
        }
        else{
            return redirect()->back()->with('error',__('Something went wrong.'));
        }
    }

    public function planGetPhonePeStatus(Request $request)
    {
        $payment_setting = getSuperAdminAllSetting();
        $currency = isset($payment_setting['CURRENCY_NAME']) ? $payment_setting['CURRENCY_NAME'] : 'USD';
        $orderID = strtoupper(str_replace('.', '', uniqid('', true)));

        $getAmount = $request->amount;
        $coupanCode = $request->coupon_code;
        $user = \Auth::user();
        $plan = Plan::find($request->plan);

        $order = new PlanOrder();
        $order->order_id = $orderID;
        $order->name = $user->name;
        $order->card_number = '';
        $order->card_exp_month = '';
        $order->card_exp_year = '';
        $order->plan_name = $plan->name;
        $order->plan_id = $plan->id;
        $order->price = $getAmount;
        $order->price_currency = $currency;
        $order->txn_id = time();
        $order->payment_type = __('Tap');
        $order->payment_status = 'success';
        $order->txn_id = '';
        $order->receipt = '';
        $order->user_id = $user->id;
        $order->save();

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
            return redirect()->route('plan.index')->with('success', __('Plan activated Successfully.'));
        } else
        {
            return redirect()->route('plan.index')->with('error', __($assignPlan['error']));
        }
    }
}
