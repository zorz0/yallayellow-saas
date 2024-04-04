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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Mollie\Laravel\Facades\Mollie;
use Session;
use App\Models\Admin;
use App\Models\OrderNote;
use App\Models\Setting;
use Illuminate\Support\Facades\Crypt;

class MolliePaymentController extends Controller
{

    public $api_key;
    public $profile_id;
    public $partner_id;
    public $is_enabled;
    public $invoiceData;


    public function paymentConfig()
    {

        if (\Auth::check()) {

            $payment_setting = getSuperAdminAllSetting();
        }

        $this->api_key    = isset($payment_setting['mollie_api_key']) ? $payment_setting['mollie_api_key'] : '';
        $this->profile_id = isset($payment_setting['mollie_profile_id']) ? $payment_setting['mollie_profile_id'] : '';
        $this->partner_id = isset($payment_setting['mollie_partner_id']) ? $payment_setting['mollie_partner_id'] : '';
        $this->is_enabled = isset($payment_setting['is_mollie_enabled']) ? $payment_setting['is_mollie_enabled'] : 'off';

        return $this;
    }
    public function planPayWithMollie(Request $request)
    {

        $payment    = $this->paymentConfig();
        $planID     = \Illuminate\Support\Facades\Crypt::decrypt($request->plan_id);
        $plan       = Plan::find($planID);

        $authuser   = Auth::user();
        $coupon = '';
        $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
        $admin_payment_setting = getSuperAdminAllSetting();

        if ($plan) {
            $price = $plan->price;
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

                $userCoupon         = new PlanUserCoupon();
                $userCoupon->user_id   = $authuser->id;
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
                            'price_currency' =>!empty($admin_payment_setting['CURRENCY_NAME'])?$admin_payment_setting['CURRENCY_NAME']:'USD',
                            'txn_id' => '',
                            'payment_type' => __('Mollie'),
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
            $errormsg = "";
            try {
                $mollie = new \Mollie\Api\MollieApiClient();
                $mollie->setApiKey($this->api_key);


                $payment = $mollie->payments->create(
                    [
                        "amount" => [
                            "currency" =>!empty($admin_payment_setting['CURRENCY_NAME']) ? $admin_payment_setting['CURRENCY_NAME']:'USD',
                            "value" => str_replace(",","",number_format($price, 2)),
                        ],

                        "description" => "payment for product",
                        "redirectUrl" => route(
                            'plan.mollie',
                            [
                                $request->plan_id,
                                'coupon_id=' . $request->coupon,
                            ]
                        ),
                    ]
                );


                session()->put('mollie_payment_id', $payment->id);
            } catch (\Exception $e) {
                $errormsg = $e->getMessage();
                return redirect()->back()->with('error', 'The amount contains an invalid value.');
            }
            return redirect($payment->getCheckoutUrl())->with('payment_id', $payment->id);
        } else {
            return redirect()->back()->with('error', 'Plan is deleted.');
        }
    }

    public function getPaymentStatus(Request $request, $plan_id)
    {
        // dd($request->all());
        $user                  = Auth::user();
        $store_id              = Auth::user()->current_store;
        $admin_payment_setting = getSuperAdminAllSetting();
        $plan_id               = \Illuminate\Support\Facades\Crypt::decrypt($plan_id);
        $plan                  = Plan::find($plan_id);

        if($plan)
        {
            try
            {
                $orderID = strtoupper(str_replace('.', '', uniqid('', true)));

                $mollie = new \Mollie\Api\MollieApiClient();
                $mollie->setApiKey($admin_payment_setting['mollie_api_key']);

                if(session()->has('mollie_payment_id'))
                {
                    $payment = $mollie->payments->get(session()->get('mollie_payment_id'));

                    if($payment->isPaid())
                    {
                        if(!empty($request->coupon_id))
                        {
                            $coupons = PlanCoupon::where('code', strtoupper($request->coupon_id))->where('is_active', '1')->first();

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
                        $planorder                 = new PlanOrder();
                        $planorder->order_id       = $orderID;
                        $planorder->name           = $user->name;
                        $planorder->card_number    = '';
                        $planorder->card_exp_month = '';
                        $planorder->card_exp_year  = '';
                        $planorder->plan_name      = $plan->name;
                        $planorder->plan_id        = $plan->id;
                        $planorder->price          = $payment->amount->value;
                        $planorder->price_currency = !empty($admin_payment_setting['CURRENCY_NAME'])?$admin_payment_setting['CURRENCY_NAME']:'USD';
                        $planorder->txn_id         = $payment->id;
                        $planorder->payment_type   = __('Mollie');
                        $planorder->payment_status = $payment->status == 'paid' ? 'success' : 'failed';
                        $planorder->receipt        = '';
                        $planorder->user_id        = $user->id;
                        $planorder->store_id       = $store_id;
                        $planorder->save();


                        if(!empty($request->coupon_id))
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

                    session()->forget('mollie_payment_id');


                }
                else
                {
                    session()->flash('error', 'Transaction Error');

                    return redirect('/');
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


