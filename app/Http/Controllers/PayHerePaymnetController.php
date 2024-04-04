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
use Illuminate\Support\Facades\Auth;
use Lahirulhr\PayHere\PayHere;

class PayHerePaymnetController extends Controller
{
    //

    public function planPayWithPayHere(Request $request)
    {
        try {
        $user = User::find(\Auth::user()->id);
        $planID = \Illuminate\Support\Facades\Crypt::decrypt($request->plan_id);

        $plan = Plan::find($planID);
        $admin_settings = getSuperAdminAllSetting();
        $admin_currancy = !empty($admin_settings['CURRENCY_NAME']) ? $admin_settings['CURRENCY_NAME'] : 'LKR';
        $authuser = Auth::user();
        $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
        if ($plan) {
            /* Check for code usage */
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


                $config = [
                    'payhere.api_endpoint' => $admin_settings['payhere_mode'] === 'sandbox'
                        ? 'https://sandbox.payhere.lk/'
                        : 'https://www.payhere.lk/',
                ];
                $config['payhere.merchant_id'] = $admin_settings['payhere_merchant_id'] ?? '';
                $config['payhere.merchant_secret'] = $admin_settings['payhere_merchant_secret'] ?? '';
                $config['payhere.app_secret'] = $admin_settings['payhere_app_secret'] ?? '';
                $config['payhere.app_id'] = $admin_settings['payhere_app_id'] ?? '';
                config($config);

                $hash = strtoupper(
                    md5(
                        $admin_settings['payhere_merchant_id'] .
                            $orderID .
                            number_format($price, 2, '.', '') .
                            'LKR' .
                            strtoupper(md5($admin_settings['payhere_merchant_secret']))
                    )
                );
                $data = [
                    'first_name' => $user->name,
                    'last_name' => '',
                    'email' => $user->email,
                    'phone' => $user->mobile_no ?? '',
                    'address' => 'Main Rd',
                    'city' => 'Anuradhapura',
                    'country' => 'Sri lanka',
                    'order_id' => $orderID,
                    'items' => $plan->name,
                    'currency' => $admin_currancy,
                    'amount' => $price,
                    'hash' => $hash,
                ];

                return PayHere::checkOut()
                    ->data($data)
                    ->successUrl(route('plan.get.payhere.status', [
                        $plan->id,
                        'amount' => $price,
                        'coupon_code' => $request->coupon_code,
                    ]))
                    ->failUrl(route('plan.get.payhere.status', [
                        $plan->id,
                        'amount' => $price,
                        'coupon_code' => $request->coupon_code,
                    ]))
                    ->renderView();

        } else {
            return redirect()->route('plan.index')->with('error', __('Plan is deleted.'));
        }
    } catch (\Exception $e) {
        \Log::debug($e->getMessage());
        return redirect()->route('plan.index')->with('error', $e->getMessage());
    }
    }

    public function planGetPayHereStatus(Request $request)
    {
        dd('cd');
        $payment_setting = getSuperAdminAllSetting();
        $currency = isset($payment_setting['CURRENCY_NAME']) ? $payment_setting['CURRENCY_NAME'] : 'USD';
        $orderID = strtoupper(str_replace('.', '', uniqid('', true)));

        $getAmount = $request->get_amount;
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
        $order->payment_type = __('PayHere');
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
