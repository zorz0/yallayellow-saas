<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\ApiController;
use App\Models\ActivityLog;
use Paytabscom\Laravel_paytabs\Facades\paypage;
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

class PaytabsController extends Controller
{
    public function paymentConfig()
    {

        $payment_setting = getSuperAdminAllSetting();

        $currancy = $payment_setting['CURRENCY_NAME'];
        config(
        [
            'paytabs.profile_id' => isset($payment_setting['paytabs_profile_id']) ? $payment_setting['paytabs_profile_id'] : '',
            'paytabs.server_key' => isset($payment_setting['paytabs_server_key']) ? $payment_setting['paytabs_server_key'] : '',
            'paytabs.region' => isset($payment_setting['paytabs_region']) ? $payment_setting['paytabs_region'] : '',
            'paytabs.currency' => isset($currancy) ? $currancy : '',
            ]
        );

    }

    public function PaytabsPaymentPrepare(Request $request)
    {

        $this->paymentConfig();
        $request->coupon = 0;
        $user = \Auth::user();
        $planID = \Illuminate\Support\Facades\Crypt::decrypt($request->plan_id);
        $plan    = Plan::find($planID);
        $price   = $request->total_price;
        $payment_setting = getSuperAdminAllSetting();
        $currency = !empty($payment_setting['CURRENCY_NAME']) ? $payment_setting['CURRENCY_NAME'] : 'USD';
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
            $userCoupon->user_id   = $user->id;
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
        if ($price <= 0)
        {
            $authuser->plan = $plan->id;
            $authuser->save();

            $assignPlan = $authuser->assignPlan($plan->id);

            // dd($assignPlan['is_success'] == true && !empty($plan));
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
                        'price_currency' => $currency,
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


        try {
            $pay= paypage::sendPaymentCode('all')
                ->sendTransaction('sale')
                ->sendCart(1,$request->total_price,'plan payment')
                ->sendCustomerDetails(isset($user->name) ?  $user->name : "" , isset($user->email) ? $user->email : '', '', '', '', '', '', '','')
                ->sendURLs(route('plan.paytabs.callback',['plan_id'=>$planID,'price'=>$request->total_price,'coupon'=>$request->coupon]),
                route('plan.paytabs.callback',['plan_id'=>$planID,'price'=>$request->total_price,'coupon'=>$request->coupon]))
                ->sendLanguage('en')
                ->sendFramed($on=false)
                ->create_pay_page();
                if(!empty($pay))
                {
                    return $pay;
                }else{
                  return redirect()->back()->with('error', __('Something went wrong!'));
                }
        } catch (\Throwable $th) {
            return redirect()->route('plan.index')->with('error',  __($th->getMessage()));
        }
    }

    public function planGetPaymentStatus(Request $request, $plan_id, $amount, $couponCode)
    {
        try
        {
            if($request->respMessage=="Authorised")
            {
				$couponCode =  $request->coupon;
                $plan_id               = $request->plan_id;
                $plan                  = Plan::find($plan_id);
                $order_id = strtoupper(str_replace('.', '', uniqid('', true)));
                $user = \Auth::user();
                $admin_payment_setting = getSuperAdminAllSetting();
                $store_id              = $user->current_store;
                $price     = $plan->price;

                if ($couponCode != 0)
                {
                    $coupons = PlanCoupon::where('code', strtoupper($couponCode))->where('is_active', '1')->first();
                    $request['coupon_id'] = $coupons->id;
                } else {
                    $coupons = null;
                }

                if(!empty($coupons))
                {
                    $usedCoupun     = $coupons->used_coupon();
                    $discount_value = ($plan->price / 100) * $coupons->discount;
                    $price          = $plan->price - $discount_value;
                    if($coupons->limit == $usedCoupun)
                    {

                        return redirect()->route('plan.index')->with('error', __('This coupon code has expired.'));
                    }
                        $coupon_id = $coupons->id;


                    if(!empty($request->coupon))
                    {

                        $statuses = 'success';
                        if ($coupon_id != '')
                        {
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
                $planorder->payment_type   = __('Paytabs');
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
            else
            {
                return redirect()->route('plan.index')->with('error', __('opps something wren wrong.'));
            }
        }
        catch(\Exception $e)
       {
            return redirect()->route('plan.index')->with('error', __('Transaction has been failed.'));
		}
    }

}
