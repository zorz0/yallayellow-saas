<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\ApiController;
use App\Models\ActivityLog;
use App\Models\Admin;
use App\Models\AppSetting;
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
use App\Models\UserCoupon;
use App\Models\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Crypt;
use Xendit\Xendit;
use Illuminate\Support\Facades\Session;

class XenditPaymentController extends Controller
{
    public function PaywithXendit(Request $request)
    {
        $planID = \Illuminate\Support\Facades\Crypt::decrypt($request->plan_id);
        $plan   = Plan::find($planID);

        $slug = !empty($request->slug) ? $request->slug : '';
        $store = Store::where('slug', $slug)->first();
        $user = \Auth::user();

        $theme_id = $request->theme_id;
        $admin_payment_setting = getSuperAdminAllSetting();
        $xendit_api = $admin_payment_setting['Xendit_api_key'];
        $CURRENCY_NAME = $admin_payment_setting['CURRENCY_NAME'];
        $CURRENCY = $admin_payment_setting['CURRENCY'];

        $orderID = $request->user_id . date('YmdHis');
        try
        {
            if ($plan) {
                $get_amount = $plan->price;


                if (isset($request->coupon) && !empty($request->coupon)) {
                    $request->coupon = trim($request->coupon);

                    $coupons         = PlanCoupon::where('code', strtoupper($request->coupon))->where('is_active', '1')->first();
                    if (!empty($coupons)) {
                        $usedCoupun             = $coupons->used_coupon();
                        $discount_value         = ($get_amount / 100) * $coupons->discount;
                        $plan->discounted_price = $get_amount - $discount_value;

                        if ($usedCoupun >= $coupons->limit) {
                            return redirect()->back()->with('error', __('This coupon code has expired.'));
                        }
                        $get_amount     = $get_amount - $discount_value;
                        $coupon_id = $coupons->id;
                    } else {
                        return redirect()->back()->with('error', __('This coupon code is invalid or has expired.'));
                    }
                }
                if(empty($coupons)){
                    $response = ['orderId' => $orderID, 'user' => $user, 'get_amount' => $get_amount, 'plan' => $plan, 'currency' => $CURRENCY_NAME ];
                }else{

                    $response = ['orderId' => $orderID, 'user' => $user, 'get_amount' => $get_amount, 'plan' => $plan, 'currency' => $CURRENCY_NAME ,'coupon_id' => $coupons->id];
                }

                Xendit::setApiKey($xendit_api);
                $params = [
                    'external_id' => $orderID,
                    'payer_email' => Auth::user()->email,
                    'description' => 'Payment for order ' . $orderID,
                    'amount' => $get_amount,
                    'callback_url' =>  route('plan.xendit.status'),
                    'success_redirect_url' => route('plan.xendit.status', $response),
                    'failure_redirect_url' => route('plan.index'),
                ];

                $invoice = \Xendit\Invoice::create($params);

                Session()->put('invoice',$invoice);

                return redirect($invoice['invoice_url']);
            }
        }
        catch(\Exception $e)
        {

            return redirect()->route('plan.index')->with('error', __($e->getMessage()));
        }

    }

    public function planGetXenditStatus(Request $request)
    {
        // $datas = $request['amp;plan'];
        $theme_id = $request->theme_id;
        $admin_payment_setting = getSuperAdminAllSetting();

        $xendit_api = $admin_payment_setting['Xendit_api_key'];
        $currency = $admin_payment_setting['CURRENCY_NAME'];

        $plan    = Plan::find($request['plan']);
        $user    = \Auth::user();
        Xendit::setApiKey($xendit_api);

        $session = Session()->get('invoice');
        $getInvoice = \Xendit\Invoice::retrieve($session['id']);
        $orderID = time();
        if($getInvoice['status'] == 'PAID'){
            if ($request->has('coupon_id') && $request->coupon_id != '') {
                $coupons = PlanCoupon::find($request->coupon_id);
                if (!empty($coupons)) {
                    $userCoupon         = new PlanUserCoupon();
                    $userCoupon->user_id   = $user->id;
                    $userCoupon->coupon_id = $coupons->id;
                    $userCoupon->order  = $request->orderId;
                    $userCoupon->save();

                    $usedCoupun = $coupons->used_coupon();
                    if ($coupons->limit <= $usedCoupun) {
                        $coupons->is_active = 0;
                        $coupons->save();
                    }
                }
            }

            $order                 = new PlanOrder();
            $order->order_id       = $request->orderId;
            $order->name           = $user->name;
            $order->card_number    = '';
            $order->card_exp_month = '';
            $order->card_exp_year  = '';
            $order->plan_name      = $plan->name;
            $order->plan_id        = $plan->id;
            $order->price          = $request['get_amount'] == null ? 0 : $request['get_amount'];
            $order->price_currency = $currency;
            $order->txn_id         = '';
            $order->payment_type   = __('Xendit');
            $order->payment_status = 'succeeded';
            $order->receipt        = null;
            $order->user_id        = $user->id;
            $order->save();


            $assignPlan = $user->assignPlan($plan->id, $request->payment_frequency);

            if ($assignPlan['is_success']) {
                return redirect()->route('plan.index')->with('success', __('Plan activated Successfully!'));
            } else {
                return redirect()->route('plan.index')->with('error', __($assignPlan['error']));
            }
        }

    }

}
