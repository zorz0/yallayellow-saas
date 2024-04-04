<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\ApiController;
use App\Models\ActivityLog;
use App\Models\Admin;
use App\Models\AppSetting;
use App\Models\Bill;
use App\Models\BillPayment;
use App\Models\Cart;
use App\Models\City;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderBillingDetail;
use App\Models\OrderCouponDetail;
use App\Models\OrderNote;
use App\Models\OrderTaxDetail;
use App\Models\Plan;
use App\Models\PlanCoupon;
use App\Models\PlanOrder;
use App\Models\PlanUserCoupon;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Store;
use App\Models\User;
use App\Models\UserCoupon;
use App\Models\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Crypt;
use Session;

class MidtransController extends Controller
{
    public function paywithMidtrans(Request $request)
    {
        $payment_setting = getSuperAdminAllSetting();
        $midtrans_secret = $payment_setting['midtrans_secret_key'];
        $currency = isset($payment_setting['currency']) ? $payment_setting['currency'] : 'USD';
        $user = Auth::user();
        $planID = \Illuminate\Support\Facades\Crypt::decrypt($request->plan_id);
        $plan = Plan::find($planID);
        $get_amount = $plan->price;

        $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
        // try
        // {
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

                    if(!empty($request->coupon))
                    {

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


                        $order                 = new PlanOrder();
                        $order->order_id       = $order_id;
                        $order->name           = $user->name;
                        $order->card_number    = '';
                        $order->card_exp_month = '';
                        $order->card_exp_year  = '';
                        $order->plan_name      = $plan->name;
                        $order->plan_id        = $plan->id;
                        $order->price          = $price;
                        $order->price_currency = !empty($admin_payment_setting['currency'])?$admin_payment_setting['currency']:'USD';
                        $order->txn_id         = '';
                        $order->payment_type   = 'Midtrans';
                        $order->payment_status = $statuses;
                        $order->receipt        = '';
                        $order->user_id        = $user->id;
                        $order->save();


                        $assignPlan = $user->assignPlan($plan->id);

                        if($assignPlan['is_success'])
                        {
                            return redirect()->route('plan.index')->with('success', __('Plan activated Successfully.'));
                        }
                        else
                        {
                            return redirect()->route('plan.index')->with('error', __($assignPlan['error']));
                        }

                    }

                }
                else
                {
                    return redirect()->route('plan.index')->with('error', __('This coupon code is invalid or has expired.'));
                }
            }
            // Set your Merchant Server Key
            \Midtrans\Config::$serverKey = $midtrans_secret;
            // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
            \Midtrans\Config::$isProduction = false;
            // Set sanitization on (default)
            \Midtrans\Config::$isSanitized = true;
            // Set 3DS transaction for credit card to true
            \Midtrans\Config::$is3ds = true;
            $params = array(
                'transaction_details' => array(
                    'order_id' => $orderID,
                    'gross_amount' => $get_amount,
                ),
                'customer_details' => array(
                    'first_name' => Auth::user()->name,
                    'last_name' => '',
                    'email' => Auth::user()->email,
                    'phone' => '8787878787',
                ),
            );
            $snapToken = \Midtrans\Snap::getSnapToken($params);

            $authuser = Auth::user();
            $authuser->plan_id = $plan->id;
            $authuser->save();
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
                    'price' => $get_amount == null ? 0 : $get_amount,
                    'price_currency' => $currency,
                    'txn_id' => '',
                    'payment_type' => __('Midtrans'),
                    'payment_status' => 'pending',
                    'receipt' => null,
                    'user_id' => $authuser->id,
                ]
            );
            $data = [
                'snap_token' => $snapToken,
                'midtrans_secret' => $midtrans_secret,
                'order_id' => $orderID,
                'plan_id' => $plan->id,
                'amount' => $get_amount,
                'fallback_url' => 'plan.get.midtrans.status'
            ];
            return view('midtras.payment', compact('data'));

        // }
        // catch(\Exception $e)
        // {
        //     return redirect()->route('plan.index')->with('error', __($e->getMessage()));
        // }
    }

    public function planGetMidtransStatus(Request $request)
    {
        $response = json_decode($request->json, true);
        if (isset($response['status_code']) && $response['status_code'] == 200) {
            $plan = Plan::find($request['plan_id']);
            $user = auth()->user();
            $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
            try {
                $Order                 = PlanOrder::where('order_id', $request['order_id'])->first();
                $Order->payment_status = 'succeeded';
                $Order->save();

                $assignPlan = $user->assignPlan($plan->id);

                if (!empty($request->coupon_id)) {
                    if (!empty($coupons)) {
                        $userCoupon = new PlanUserCoupon();
                        $userCoupon->user_id = $user->id;
                        $userCoupon->coupon_id = $coupons->id;
                        $userCoupon->order = $orderID;
                        $userCoupon->save();
                        $usedCoupun = $coupons->used_coupon();
                        if ($coupons->limit <= $usedCoupun) {
                            $coupons->is_active = 0;
                            $coupons->save();
                        }
                    }
                }

                if ($assignPlan['is_success']) {
                    return redirect()->route('plan.index')->with('success', __('Plan activated Successfully.'));
                } else {
                    return redirect()->route('plan.index')->with('error', __($assignPlan['error']));
                }
            } catch (\Exception $e) {
                dd($e);
                return redirect()->route('plan.index')->with('error', __($e->getMessage()));
            }
        } else {
            return redirect()->back()->with('error', $response['status_message']);
        }

    }

}
