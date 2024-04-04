<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Plan;
use App\Models\Coupon;
use App\Models\Cart;
use App\Models\Store;
use App\Models\UserCoupon;
use App\Models\Utility;
use App\Models\PlanOrder;
use App\Models\PlanUserCoupon;
use App\Models\PlanCoupon;
use App\Models\Product;
use App\Models\City;
use App\Models\OrderBillingDetail;
use App\Models\OrderCouponDetail;
use App\Models\OrderTaxDetail;
use App\Models\AppSetting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use App\Http\Controllers\Api\ApiController;
use App\Models\ActivityLog;
use Session;
use App\Models\Admin;
use App\Models\OrderNote;
use App\Models\Setting;

class PaystackPaymentController extends Controller
{
    //

    public $secret_key;
    public $public_key;
    public $is_enabled;

    public function paymentConfig()
    {
        $user = Auth::user();


        if (Auth::user()->type == 'admin') {
            $payment_setting = getSuperAdminAllSetting();
        } else {
            $payment_setting = Utility::getCompanyPaymentSetting($user);
        }

        $this->secret_key = isset($payment_setting['paystack_secret_key']) ? $payment_setting['paystack_secret_key'] : '';
        $this->public_key = isset($payment_setting['paystack_public_key']) ? $payment_setting['paystack_public_key'] : '';
        $this->is_enabled = isset($payment_setting['is_paystack_enabled']) ? $payment_setting['is_paystack_enabled'] : 'off';
        return $this;
    }

    public function planPayWithPaystack(Request $request)
    {
        $this->paymentConfig();

        $planID    = \Illuminate\Support\Facades\Crypt::decrypt($request->plan_id);
        $plan      = Plan::find($planID);
        $admin_payment_setting = getSuperAdminAllSetting();
        $currency = $admin_payment_setting['CURRENCY_NAME'];
        $coupon_id = '';

        if ($plan) {
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
                    $coupon_id = $coupons->id;
                } else {
                    return redirect()->back()->with('error', __('This coupon code is invalid or has expired.'));
                }
            }

            if ($price <= 0) {
                $authuser->plan = $plan->id;
                $authuser->save();

                $assignPlan = $authuser->assignPlan($plan->id);

                if ($assignPlan['is_success'] == true && !empty($plan)) {


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
                            'price_currency' => $currency,
                            'txn_id' => '',
                            'payment_type' => 'Paystack',
                            'payment_status' => 'succeeded',
                            'receipt' => null,
                            'user_id' => $authuser->id,
                        ]
                    );
                    $res['msg']  = __("Plan successfully upgraded.");
                    $res['flag'] = 2;

                    return $res;
                } else {
                    return redirect()->route('plans.index')->with('error', __('Plan fail to upgrade.'));
                }
            }

            $res_data['email']       = \Auth::user()->email;
            $res_data['total_price'] = $price;
            $res_data['currency']    = $currency;
            $res_data['flag']        = 1;
            $res_data['coupon']      = $coupon_id;
            // $res_data['payment_frequency'] = $request->paystack_payment_frequency;

            return $res_data;
        } else {
            return redirect()->route('plans.index')->with('error', __('Plan is deleted.'));
        }
    }

    public function getPaymentStatus(Request $request, $pay_id, $plan)
    {
        $payment = $this->paymentConfig();
        $planID  = \Illuminate\Support\Facades\Crypt::decrypt($plan);
        $plan    = Plan::find($planID);
        $user    = Auth::user();
        $result  = array();
        $admin_payment_setting = getSuperAdminAllSetting();
        $currency = $admin_payment_setting['CURRENCY_NAME'];
        if($plan)
        {
            try
            {
                $orderID = strtoupper(str_replace('.', '', uniqid('', true)));

                //The parameter after verify/ is the transaction reference to be verified
                $url = "https://api.paystack.co/transaction/verify/$pay_id";
                $ch  = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt(
                    $ch, CURLOPT_HTTPHEADER, [
                        'Authorization: Bearer ' . $payment->secret_key,
                        ]
                    );
                    $responce = curl_exec($ch);
                    curl_close($ch);
                    if($responce)
                    {
                        $result = json_decode($responce, true);
                    }

                    if(isset($result['status']) && $result['status'] == true)
                    {
                        $status = $result['data']['status'];
                        if($request->has('coupon_id') && $request->coupon_id != '')
                        {
                            $coupons = PlanCoupon::find($request->coupon_id);
                            if(!empty($coupons))
                            {
                                $userCoupon         = new PlanUserCoupon();
                                $userCoupon->user_id   = $user->id;
                                $userCoupon->coupon_id = $coupons->id;
                                $userCoupon->order  = $orderID;
                                $userCoupon->save();
                                $usedCoupun = $coupons->used_coupon();
                                if($coupons->limit <= $usedCoupun)
                                {
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
                    $order->price          = $result['data']['amount'] / 100;
                    $order->price_currency = $currency;
                    $order->txn_id         = $pay_id;
                    $order->payment_type   = __('Paystack');
                    $order->payment_status = $result['data']['status'];
                    $order->receipt        = '';
                    $order->user_id        = $user->id;
                    $order->save();

                    if(!empty($request->coupon))
                    {
                        $userCoupon         = new PlanUserCoupon();
                        $userCoupon->user   = $user->id;
                        $userCoupon->coupon = $coupons->id;
                        $userCoupon->order  = $orderID;
                        $userCoupon->save();

                        $usedCoupun = $coupons->used_coupon();
                        if($coupons->limit <= $usedCoupun)
                        {
                            $coupons->is_active = 0;
                            $coupons->save();
                        }

                    }
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
                    return redirect()->back()->with('error', __('Transaction Unsuccesfull'));
                }

            }
            catch(\Exception $e)
            {
                dd($e);
                return redirect()->route('plan.index')->with('error', __('Transaction has been failed.'));
            }
        }
        else
        {
            return redirect()->route('plan.index')->with('error', __('Plan is deleted.'));
        }
    }



}
