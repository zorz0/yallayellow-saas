<?php

namespace App\Http\Controllers;

use App\Models\Utility;
use App\Models\Plan;
use App\Models\Store;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Coupon;
use App\Models\UserCoupon;
use App\Models\City;
use App\Models\OrderBillingDetail;
use App\Models\OrderCouponDetail;
use App\Models\OrderTaxDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\AppSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use App\Http\Controllers\Api\ApiController;
use App\Models\ActivityLog;
use Session;
use App\Models\Admin;
use App\Models\OrderNote;
use App\Models\Setting;
use Illuminate\Support\Facades\Crypt;

class PaymentWallPaymentController extends Controller
{
    public $secret_key;
    public $public_key;
    public $is_enabled;

    public function paymentwall(Request $request)
    {
        $data = $request->all();
        $admin_payment_setting = getSuperAdminAllSetting();

        return view('plans.paymentwall', compact('data', 'admin_payment_setting'));
    }
    public function paymentConfig($user)
    {
        if (Auth::check()) {
            $user = Auth::user();
        }
        if ($user->type == 'admin') {
            $payment_setting = getSuperAdminAllSetting();
        } else {
            $payment_setting = Utility::getCompanyPaymentSetting();
        }

        $this->secret_key = isset($payment_setting['paymentwall_private_key ']) ? $payment_setting['paymentwall_private_key  '] : '';
        $this->public_key = isset($payment_setting['paymentwall_public_key']) ? $payment_setting['paymentwall_public_key'] : '';
        $this->is_enabled = isset($payment_setting['is_paymentwall_enabled']) ? $payment_setting['is_paymentwall_enabled'] : 'off';

        return $this;
    }

    public function planPayWithPaymentWall(Request $request, $plan_id)
    {
        $planID    = \Illuminate\Support\Facades\Crypt::decrypt($plan_id);

        $plan      = Plan::find($planID);
        $user   = Auth::user();
        $coupon_id = '';
        // dd($plan);
        if ($plan) {
            /* Check for code usage */
            $plan->discounted_price = false;
            $price                  = $plan->price;
            //  dd($price);
            if (isset($request->coupon) && !empty($request->coupon)) {
                $request->coupon = trim($request->coupon);
                $coupons         = Coupon::where('code', strtoupper($request->coupon))->where('is_active', '1')->first();

                if (!empty($coupons)) {
                    $usedCoupun             = $coupons->used_coupon();
                    $discount_value         = ($price / 100) * $coupons->discount;
                    $plan->discounted_price = $price - $discount_value;

                    // if($usedCoupun >= $coupons->limit)
                    // {
                    //     return redirect()->back()->with('error', __('This coupon code has expired.'));
                    // }

                    if ($coupons->limit == $usedCoupun) {
                        return redirect()->back()->with('error', __('This coupon code has expired.'));
                    }

                    $price     = $price - $discount_value;
                    $coupon_id = $coupons->id;
                } else {
                    return redirect()->back()->with('error', __('This coupon code is invalid or has expired.'));
                }
            }
            if ($price <= 0) {

                $user->plan = $plan->id;
                $user->save();

                $assignPlan = $user->assignPlan($plan->id, $request->paymentwall_payment_frequency);

                if ($assignPlan['is_success'] == true && !empty($plan)) {
                    if (!empty($user->payment_subscription_id) && $user->payment_subscription_id != '') {
                        try {
                            $user->cancel_subscription($user->id);
                        } catch (\Exception $exception) {
                            \Log::debug($exception->getMessage());
                        }
                    }
                    $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
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
                            'price' => $price,
                            'price_currency' => !empty($this->currancy) ? $this->currancy : 'usd',
                            'txn_id' => '',
                            'payment_type' => __('Manually'),
                            'payment_status' => 'succeeded',
                            'receipt' => null,
                            'user_id' => $user->id,
                        ]
                    );

                    $res['msg']  = __("Plan successfully upgraded.");
                    $res['flag'] = 2;

                    return $res;
                }
            } else {
                //  dd('222222');
                $orderID = time();
                \Paymentwall_Config::getInstance()->set(array(
                    'private_key' => 'sdrsefrszdef'
                ));
                $parameters = $request->all();
                $chargeInfo = array(
                    'email' => $parameters['email'],
                    'history[registration_date]' => '1489655092',
                    'amount' => $price,
                    'currency' => !empty($this->currancy) ? $this->currancy : 'USD',
                    'token' => $parameters['brick_token'],
                    'fingerprint' => $parameters['brick_fingerprint'],
                    'description' => 'Order #123'
                );
                $charge = new \Paymentwall_Charge();
                $charge->create($chargeInfo);
                $responseData = json_decode($charge->getRawResponseData(), true);
                $response = $charge->getPublicData();
                //  dd($response);
                if ($charge->isSuccessful() and empty($responseData['secure'])) {
                    if ($charge->isCaptured()) {
                        if ($request->has('coupon_id') && $request->coupon_id != '') {
                            $coupons = Coupon::find($request->coupon_id);
                            if (!empty($coupons)) {
                                $userCoupon         = new UserCoupon();
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

                        $user->is_plan_purchased = 1;
                        if ($user->is_trial_done == 1) {
                            $user->is_trial_done = 2;
                            $user->save();
                        }

                        $order                 = new Order();
                        $order->order_id       = $orderID;
                        $order->name           = $user->name;
                        $order->card_number    = '';
                        $order->card_exp_month = '';
                        $order->card_exp_year  = '';
                        $order->plan_name      = $plan->name;
                        $order->plan_id        = $plan->id;
                        $order->price          = isset($result['data']['amount']) ? ($result['data']['amount'] / 100) : 0;
                        $order->price_currency = $this->currancy;
                        $order->txn_id         = isset($result['data']['id']) ? $result['data']['id'] : $pay_id;
                        $order->payment_type   = 'PaymentWall';
                        $order->payment_status = 'success';
                        $order->receipt        = '';
                        $order->user_id        = $user->id;
                        $order->save();
                        $assignPlan = $authuser->assignPlan($plan->id);
                        if ($assignPlan['is_success']) {
                            $res['msg'] = __("Plan successfully upgraded.");
                            $res['flag'] = 1;
                            return $res;
                        }
                    } elseif ($charge->isUnderReview()) {
                    }
                } elseif (!empty($responseData['secure'])) {
                    $response = json_encode(array('secure' => $responseData['secure']));
                } else {
                    $errors = json_decode($response, true);
                    $res['flag'] = 2;
                    return $res;
                }
                echo $response;
            }
        }
    }
    public function planeerror(Request $request, $flag)
    {
        if ($flag == 1) {
            return redirect()->route("plans.index")->with('error', __('Transaction has been Successfull! '));
        } else {

            return redirect()->route("plans.index")->with('error', __('Transaction has been failed! '));
        }
    }

}
