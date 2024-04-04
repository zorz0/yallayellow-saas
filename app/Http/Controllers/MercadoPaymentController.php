<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\ApiController;
use App\Models\ActivityLog;
use App\Models\Coupon;
use App\Models\Cart;
use App\Models\PlanUserCoupon;
use App\Models\PlanCoupon;
use App\Models\PlanOrder;
use App\Models\Order;
use App\Models\Plan;
use App\Models\Store;
use App\Models\Utility;
use App\Models\City;
use App\Models\AppSetting;
use App\Models\OrderBillingDetail;
use App\Models\OrderTaxDetail;
use App\Models\Product;
use App\Models\UserCoupon;
use App\Models\OrderCouponDetail;
use Illuminate\Console\View\Components\Alert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use LivePixel\MercadoPago\MP;
use Illuminate\Support\Facades\Cookie;
use Session;
use App\Models\Admin;
use App\Models\OrderNote;
use App\Models\Setting;
use Illuminate\Support\Facades\Crypt;

class MercadoPaymentController extends Controller
{
    //
    public $token;
    public $is_enabled;
    public $currancy;
    public $mode;
    public $secret_key;
    public $public_key;


    public function paymentConfig()
    {
        if (Auth::check()) {
            $user = Auth::user();
        }

        if ($user->type == 'admin') {
            $payment_setting = getSuperAdminAllSetting();
        } else {
            $payment_setting = Utility::getCompanyPaymentSetting($user);
        }

        $this->token = isset($payment_setting['mercado_access_token']) ? $payment_setting['mercado_access_token'] : '';
        $this->mode = isset($payment_setting['mercado_mode']) ? $payment_setting['mercado_mode'] : '';
        $this->is_enabled = isset($payment_setting['is_mercado_enabled']) ? $payment_setting['is_mercado_enabled'] : 'off';
        return $this;
    }


    public function planPayWithMercado(Request $request)
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
            if (isset($request->coupon) && !empty($request->coupon)) {
                $request->coupon = trim($request->coupon);
                $coupons         = PlanCoupon::where('code', strtoupper($request->coupon))->where('is_active', '1')->first();
                if (!empty($coupons)) {
                    $usedCoupun             = $coupons->used_coupon();
                    $discount_value         = ($price / 100) * $coupons->discount;
                    $plan->discounted_price = $price - $discount_value;
                    $coupons_id = $coupons->id;
                    if ($usedCoupun >= $coupons->limit) {
                        return redirect()->back()->with('error', __('This coupon code has expired.'));
                    }
                    $price = $price - $discount_value;
                } else {
                    return redirect()->back()->with('error', __('This coupon code is invalid or has expired.'));
                }
            }
            if ($price <= 0) {
                $authuser->plan = $plan->id;
                $authuser->save();

                $assignPlan = $authuser->assignPlan($plan->id);
                // dd($assignPlan);
                if ($assignPlan['is_success'] == true && !empty($plan)) {

                    $orderID = time();

                    Order::create(
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
                            'price_currency' => !empty($this->currancy) ? $this->currancy : 'USD',
                            'txn_id' => '',
                            'payment_type' => 'Mercado',
                            'payment_status' => 'succeeded',
                            'receipt' => null,
                            'user_id' => $authuser->id,
                        ]
                    );
                    $res['msg'] = __("Plan successfully upgraded.");
                    $res['flag'] = 2;

                    return $res;
                } else {
                    return Utility::error_res(__('Plan fail to upgrade.'));
                }
            }

            $payment_setting = getSuperAdminAllSetting();
            $this->token = isset($payment_setting['mercado_access_token']) ? $payment_setting['mercado_access_token'] : '';
            $this->mode = isset($payment_setting['mercado_mode']) ? $payment_setting['mercado_mode'] : '';
            $this->is_enabled = isset($payment_setting['is_mercado_enabled']) ? $payment_setting['is_mercado_enabled'] : 'off';


            \MercadoPago\SDK::setAccessToken($this->token);
            try {

                // Create a preference object
                $preference = new \MercadoPago\Preference();

                // Create an item in the preference
                $item = new \MercadoPago\Item();
                $item->title = "Plan : " . $plan->name;
                $item->quantity = 1;
                $item->unit_price = (float)$price;
                $preference->items = array($item);
                $success_url = route('plan.mercado', [$request->plan_id, 'payment_frequency=' . $request->mercado_payment_frequency, 'coupon_id=' . $coupons_id, 'flag' => 'success']);

                $failure_url = route('plan.mercado', [$request->plan_id, 'flag' => 'failure']);
                $pending_url = route('plan.mercado', [$request->plan_id, 'flag' => 'pending']);

                $preference->back_urls = array(
                    "success" => $success_url,
                    "failure" => $failure_url,
                    "pending" => $pending_url
                );

                $preference->auto_return = "approved";
                $preference->save();

                // Create a customer object
                $payer = new \MercadoPago\Payer();


                // Create payer information
                $payer->name = \Auth::user()->name;
                $payer->email = \Auth::user()->email;
                $payer->address = array(
                    "street_name" => ''
                );
                if ($this->mode == 'live') {
                    $redirectUrl = $preference->init_point;
                } else {

                    $redirectUrl = $preference->sandbox_init_point;
                }
                // dd($redirectUrl);
                return redirect($redirectUrl);
            } catch (Exception $e) {

                return redirect()->back()->with('error', $e->getMessage());
            }
            // callback url :  domain.com/plan/mercado

        } else {
            return redirect()->back()->with('error', 'Plan is deleted.');
        }
    }

    public function getPaymentStatus(Request $request, $plan)
    {

        $this->paymentConfig();
        $planID         = \Illuminate\Support\Facades\Crypt::decrypt($plan);
        $plan           = Plan::find($planID);
        $user = Auth::user();
        $orderID = time();
        if ($plan) {
            $price                  = $plan->price;
            // try
            // {

            if ($plan && $request->has('status')) {

                if ($request->status == 'approved' && $request->flag == 'success') {
                    if (!empty($user->payment_subscription_id) && $user->payment_subscription_id != '') {
                        try {
                            $user->cancel_subscription($user->id);
                        } catch (\Exception $exception) {
                            \Log::debug($exception->getMessage());
                        }
                    }
                    if ($request->has('coupon_id') && $request->coupon_id != '') {
                        $coupons = PlanCoupon::find($request->coupon_id);

                        if (!empty($coupons)) {

                            $discount_value = ($price / 100) * $coupons->discount;

                            $userCoupon            = new PlanUserCoupon();
                            $userCoupon->user_id  = $user->id;
                            $userCoupon->coupon_id = $coupons->id;
                            $userCoupon->order  = $orderID;
                            $userCoupon->save();

                            $usedCoupun = $coupons->used_coupon();
                            if ($coupons->limit <= $usedCoupun) {
                                $coupons->is_active = 0;
                                $coupons->save();
                            }
                            $price = $price - $discount_value;
                        }
                    }
                    // dd($price);
                    $order                 = new PlanOrder();
                    $order->order_id       = $orderID;
                    $order->name           = $user->name;
                    $order->card_number    = '';
                    $order->card_exp_month = '';
                    $order->card_exp_year  = '';
                    $order->plan_name      = $plan->name;
                    $order->plan_id        = $plan->id;
                    $order->price          = $price == null ? 0 : $price;
                    $order->price_currency = isset($this->currancy) ? $this->currancy : '';
                    $order->txn_id         = $request->has('preference_id') ? $request->preference_id : '';
                    $order->payment_type   = 'Mercado Pago';
                    $order->payment_status = 'succeeded';
                    $order->receipt        = '';
                    $order->user_id        = $user->id;
                    $order->save();
                    $assignPlan = $user->assignPlan($plan->id, $request->payment_frequency);
                    if ($assignPlan['is_success']) {
                        return redirect()->route('plan.index')->with('success', __('Plan activated Successfully!'));
                    } else {
                        return redirect()->route('plan.index')->with('error', __($assignPlan['error']));
                    }
                } else {
                    return redirect()->route('plan.index')->with('error', __('Transaction has been failed! '));
                }
            } else {
                return redirect()->route('plan.index')->with('error', __('Transaction has been failed! '));
            }
            // }
            // catch(\Exception $e)
            // {
            //     return redirect()->route('plans.index')->with('error', __('Plan not found!'));
            // }
        }
    }

}
