<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Utility;
use App\Models\PlanOrder;
use App\Models\PlanUserCoupon;
use App\Models\PlanCoupon;
use Stripe;

use Illuminate\Http\Request;

class StripePaymentController extends Controller
{
    public function stripe($code)
    {
        try {
            $plan_id = \Illuminate\Support\Facades\Crypt::decrypt($code);
            $plan    = Plan::find($plan_id);
            if ($plan) {
                $admin_payments_details = getSuperAdminAllSetting();

                return view('plans/stripe', compact('plan', 'admin_payments_details'));
            } else {
                return redirect()->back()->with('error', __('Plan is deleted.'));
            }
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', __('Plan is not found.'));
        }
    }

    public function addpayment(Request $request)
    {
        $objUser               = \Auth::user();
        $planID                = \Illuminate\Support\Facades\Crypt::decrypt($request->plan_id);
        $plan                  = Plan::find($planID);
        $admin_payment_setting = getSuperAdminAllSetting();
        $currency = $admin_payment_setting['CURRENCY_NAME'];
        if ($plan) {
            try {
                $price = $plan->price;
                if (!empty($request->coupon)) {
                    $coupons = PlanCoupon::where('code', strtoupper($request->coupon))->where('is_active', '1')->first();
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

                $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
                if ($price > 0.0) {
                    Stripe\Stripe::setApiKey($admin_payment_setting['stripe_secret_key']);
                    $data = Stripe\Charge::create(
                        [
                            "amount" => 100 * $price,
                            "currency" => $currency,
                            "source" => $request->stripeToken,
                            "description" => " Plan - " . $plan->name,
                            "metadata" => ["order_id" => $orderID],
                        ]
                    );
                } else {
                    $data['amount_refunded'] = 0;
                    $data['failure_code']    = '';
                    $data['paid']            = 1;
                    $data['captured']        = 1;
                    $data['status']          = 'succeeded';
                }

                if ($data['amount_refunded'] == 0 && empty($data['failure_code']) && $data['paid'] == 1 && $data['captured'] == 1) {

                    PlanOrder::create(
                        [
                            'order_id' => $orderID,
                            'name' => $request->name,
                            'card_number' => isset($data['payment_method_details']['card']['last4']) ? $data['payment_method_details']['card']['last4'] : '',
                            'card_exp_month' => isset($data['payment_method_details']['card']['exp_month']) ? $data['payment_method_details']['card']['exp_month'] : '',
                            'card_exp_year' => isset($data['payment_method_details']['card']['exp_year']) ? $data['payment_method_details']['card']['exp_year'] : '',
                            'plan_name' => $plan->name,
                            'plan_id' => $plan->id,
                            'price' => $price,
                            'price_currency' => $currency,
                            'txn_id' => isset($data['balance_transaction']) ? $data['balance_transaction'] : '',
                            'payment_type' => __('STRIPE'),
                            'payment_status' => isset($data['status']) ? $data['status'] : 'succeeded',
                            'receipt' => isset($data['receipt_url']) ? $data['receipt_url'] : 'free coupon',
                            'user_id' => $objUser->id,
                            'store_id' => getCurrentStore(),
                        ]
                    );

                    if (!empty($request->coupon)) {
                        $userCoupon         = new PlanUserCoupon();
                        $userCoupon->user_id   = $objUser->id;
                        $userCoupon->coupon_id = $coupons->id;
                        $userCoupon->order  = $orderID;
                        $userCoupon->save();

                        $usedCoupun = $coupons->used_coupon();
                        if ($coupons->limit <= $usedCoupun) {
                            $coupons->is_active = 0;
                            $coupons->save();
                        }
                    }
                    if ($data['status'] == 'succeeded') {
                        $assignPlan = $objUser->assignPlan($plan->id);
                        if ($assignPlan['is_success']) {
                            return redirect()->route('plan.index')->with('success', __('Plan successfully activated.'));
                        } else {
                            return redirect()->back()->with('error', __($assignPlan['error']));
                        }
                    } else {
                        return redirect()->back()->with('error', __('Your payment has failed.'));
                    }
                } else {
                    return redirect()->back()->with('error', __('Transaction has been failed.'));
                }
            } catch (\Exception $e) {
                return redirect()->back()->with('error', __($e->getMessage()));
            }
        } else {
            return redirect()->back()->with('error', __('Plan is deleted.'));
        }
    }
}
