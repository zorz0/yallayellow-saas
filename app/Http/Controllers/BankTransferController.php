<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Plan;
use App\Models\Admin;
use App\Models\PlanOrder;
use App\Models\PlanCoupon;
use App\Models\PlanUserCoupon;
use App\Models\Utility;


class BankTransferController extends Controller
{
    public function planPayWithbank(Request $request)
    {
        if ($request->payment_receipt)
        {
            $request->validate(
                [
                    'payment_receipt' => 'image',
                ]
            );
        }
        $admin_payment_setting = getSuperAdminAllSetting();
        $currency       = $admin_payment_setting['CURRENCY_NAME'];
        $planID         = \Illuminate\Support\Facades\Crypt::decrypt($request->plan_id);
        $plan           = Plan::find($planID);
        $authuser       = Auth::user();
        $coupon_id = '';
        $user = Auth::user();
        $orderID = time();
        $order = PlanOrder::where('plan_id', $planID)->where('payment_status', 'Pending')->where('user_id', $authuser->id)->first();
        if ($order)
        {
            return redirect()->route('plan.index')->with('error', __('You already send Payment request to this plan.'));
        }
        if ($request->payment_receipt)
        {

            $dir = 'storage/uploads/receipt';
            if($request->payment_receipt) {
                $fileName = $request->payment_receipt->getClientOriginalName();
                $path = Utility::upload_file($request, 'payment_receipt', $fileName, $dir,[]);
            }
            if ($path['flag'] == 1) {
                $payment_receipt = $path['url'];
            } else {
                return redirect()->back()->with('error', __($path['msg']));
            }
            if ($plan) {
                $plan->discounted_price = false;
                $price                  = $plan->price;
                if (isset($request->coupon) && !empty($request->coupon)) {
                    $request->coupon = trim($request->coupon);
                    $coupons         = PlanCoupon::where('code', strtoupper($request->coupon))->where('is_active', '1')->first();
                    if (!empty($coupons)) {
                        $usedCoupun             = $coupons->used_coupon();
                        $discount_value         = ($price / 100) * $coupons->discount;
                        $plan->discounted_price = $price - $discount_value;
                        if ($usedCoupun >= $coupons->limit) {
                            return Utility::error_res(__('This coupon code has expired.'));
                        }
                        $price = $price - $discount_value;
                        $coupon_id = $coupons->id;
                    } else {
                        return Utility::error_res(__('This coupon code is invalid or has expired.'));
                    }
                }

                if ($request->has('coupon') && $request->coupon != '') {
                    $coupons = PlanCoupon::where('code', $request->coupon)->first();
                    $discount_value         = ($plan->price / 100) * $coupons->discount;
                    // dd($discount_value);
                    $discounted_price = $plan->price - $discount_value;
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
                $order                 = new PlanOrder();
                $order->order_id       = $orderID;
                $order->name           = $user->name;
                $order->card_number    = '';
                $order->card_exp_month = '';
                $order->card_exp_year  = '';
                $order->plan_name      = $plan->name;
                $order->plan_id        = $plan->id;
                $order->price          = isset($coupons) ? $plan->discounted_price : $plan->price;
                $order->price_currency = $currency;
                $order->txn_id         = isset($request->TXNID) ? $request->TXNID : '';
                $order->payment_type   = __('Bank Transfer');
                $order->payment_status = 'Pending';
                $order->receipt        = $payment_receipt;
                $order->user_id        = $user->id;
                $order->save();
                // dd($order);
                return redirect()->route('plan.index')->with('success', __('Plan payment request send successfully!'));
            }
        }
    }

    public function show(PlanOrder $order, $id)
    {
        $order = PlanOrder::find($id);
        // dd($order);
        $admin_payment_setting = getSuperAdminAllSetting();

        return view('order.show', compact('order', 'admin_payment_setting'));
    }

    public function orderapprove($id)
    {
        $order = PlanOrder::find($id);
        if ($order)
        {
            $order = PlanOrder::find($id);
            $user  = User::find($order->user_id);
            $plann = Plan::find($order->plan_id);
            $order->payment_status = 'Approved';
            $order->save();
            $assignPlan = $user->assignPlan($order->plan_id, $plann->duration);

            return redirect()->back()->with('success', __('Order Successfully Approved'));
        }
        else
        {
            return redirect()->back()->with('error', __('Something is wrong'));
        }
    }

    public function orderreject($id)
    {
        $order = PlanOrder::find($id);
        if ($order)
        {
            $order->payment_status = 'Rejected';
            $order->save();
            return redirect()->back()->with('success', __('Order Successfully Rejected'));
        } else {
            return redirect()->back()->with('error', __('Something is wrong'));
        }
    }

    public function destroy(PlanOrder $order)
    {
        if ($order) {
            $order->delete();
            return redirect()->back()->with('success', __('Order Successfully Deleted.'));
        } else {
            return redirect()->back()->with('error', __('Something is wrong.'));
        }
    }

}
