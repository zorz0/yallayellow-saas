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
use App\Models\UserCoupon;
use App\Models\Utility;
use CoinGate\CoinGate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Session;
use App\Models\Admin;
use App\Models\OrderNote;
use App\Models\Setting;
use Illuminate\Support\Facades\Crypt;

class CoingateController extends Controller
{
    public function coingatePaymentPrepare(Request $request)
    {
        $validator = \Validator::make(
            $request->all(), [
                               'plan_id' => 'required',
                               'total_price' => 'required',
                           ]
        );
        if($validator->fails())
        {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }
        $user    = Auth::user()->current_store;
        $authuser =Auth::user();
        $store   = Store::where('id', $user)->first();
        $plan_id = decrypt($request->plan_id);
        $plan    = Plan::find($plan_id);
        $price   = $request->total_price;
        $admin_payment_setting = getSuperAdminAllSetting();

        try {
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
            if(!empty($request->coupon))
            {
                $order_id = strtoupper(str_replace('.', '', uniqid('', true)));

                $userCoupon         = new PlanUserCoupon();
                $userCoupon->user_id   = Auth::user()->id;
                $userCoupon->coupon_id = $coupons->id;
                $userCoupon->order  = $order_id;
                $userCoupon->save();

                $usedCoupun = $coupons->used_coupon();
                if($coupons->limit <= $usedCoupun)
                {
                    $coupons->is_active = 0;
                    $coupons->save();
                }

            }
            if ($price <= 0) {
                $authuser->plan = $plan->id;
                $authuser->save();

                $assignPlan = $authuser->assignPlan($plan->id);

                if ($assignPlan['is_success'] == true && !empty($plan)) {

                    $orderID = time();
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
                            'price_currency' => !empty($admin_payment_setting['CURRENCY_NAME']) ? $admin_payment_setting['CURRENCY_NAME'] : 'USD',
                            'txn_id' => '',
                            'payment_type' => 'coingate',
                            'payment_status' => 'succeeded',
                            'receipt' => null,
                            'user_id' => $authuser->id,
                        ]
                    );
                    $assignPlan = $authuser->assignPlan($plan->id);

                    return redirect()->route('plan.index')->with('success', __('Plan activated Successfully!'));
                } else {
                    return redirect()->back()->with('error', __('Plan fail to upgrade.'));
                }
            }
            if($plan)
            {
                $order                 = $request->all();
                CoinGate::config(
                    array(
                        'environment' => $admin_payment_setting['coingate_mode'],
                        // sandbox OR live
                        'auth_token' => $admin_payment_setting['coingate_auth_token'],
                        'curlopt_ssl_verifypeer' => FALSE
                        // default is false
                    )
                );

                $post_params = array(
                    'order_id' => time(),
                    'price_amount' => $price,
                    'price_currency' =>$admin_payment_setting['CURRENCY_NAME'],
                    'receive_currency' =>$admin_payment_setting['CURRENCY_NAME'],
                    'callback_url' => route('coingate.coingate.callback') . '?plan_id=' . $plan->id . '&user_id=' . Auth::user()->id . '&coupon=' .$request->coupon ,
                    'cancel_url' => route('plan.index'),
                    'success_url' => route('coingate.coingate.callback') . '?plan_id=' . $plan->id . '&user_id=' . Auth::user()->id . '&coupon=' .$request->coupon ,
                    'title' => 'Order #' . time(),
                );
                $order = \CoinGate\Merchant\Order::create($post_params);

                if($order)
                {

                    return redirect($order->payment_url);
                }
                else
                {
                    return redirect()->back()->with('error', __('opps something wren wrong.'));
                }
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function coingatePlanGetPayment(Request $request)
    {

        $user                  = Auth::user();
        $plan_id               = $request->plan_id;
        $store_id              = Auth::user()->current_store;
        $admin_payment_setting = getSuperAdminAllSetting();
        $plan                  = Plan::find($plan_id);
        if($plan)
        {
            try
            {
                $order_id = strtoupper(str_replace('.', '', uniqid('', true)));
                $price     = $plan->price;
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
                        $coupon_id = $coupons->id;


                    if(!empty($request->coupon))
                    {

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
                }
                $planorder                 = new PlanOrder();
                $planorder->order_id       = $order_id;
                $planorder->name           = $user->name;
                $planorder->card_number    = '';
                $planorder->card_exp_month = '';
                $planorder->card_exp_year  = '';
                $planorder->plan_name      = $plan->name;
                $planorder->plan_id        = $plan->id;
                $planorder->price          = $price;
                $planorder->price_currency = $admin_payment_setting['CURRENCY_NAME'];
                $planorder->txn_id         = '-';
                $planorder->payment_type   = __('CoinGAte');
                $planorder->payment_status = 'success';
                $planorder->receipt        = '';
                $planorder->user_id        = $user->id;
                $planorder->store_id       = $store_id;
                $planorder->save();

                $assignPlan = $user->assignPlan($plan->id);

                if($assignPlan['is_success'])
                {
                    return redirect()->route('plan.index')->with('success', __('Plan activated Successfully.'));
                }
                else
                {


                    return redirect()->route('plan.index')->with('error', $assignPlan['error']);
                }
            }
            catch(\Exception $e)
            {
                return redirect()->route('plan.index')->with('error', __('Transaction has been failed.'));
            }
        }
        else
        {
            return redirect()->route('plan.index')->with('error', __('Plan is deleted.'));
        }
    }

}
