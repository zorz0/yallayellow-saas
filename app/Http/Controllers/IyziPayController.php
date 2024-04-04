<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\User;
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
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiController;
use App\Models\ActivityLog;
use App\Models\OrderNote;
use App\Models\Setting;
use Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Crypt;

class IyziPayController extends Controller
{
    //


    public function initiatePayment(Request $request)
    {
        $planID    = \Illuminate\Support\Facades\Crypt::decrypt($request->plan_id);
        $authuser  = \Auth::user();
        $adminPaymentSettings = getSuperAdminAllSetting();
        $iyzipay_private_key = $adminPaymentSettings['iyzipay_private_key'];
        $iyzipay_secret_key = $adminPaymentSettings['iyzipay_secret_key'];
        $iyzipay_mode = $adminPaymentSettings['iyzipay_mode'];
        $currency = $adminPaymentSettings['CURRENCY_NAME'];
        $plan = Plan::find($planID);
        $coupon_id = '0';
        $price = $plan->price;
        $coupon_code = null;
        $discount_value = null;
        $coupons = PlanCoupon::where('code', $request->coupon)->where('is_active', '1')->first();
        if ($coupons) {
            $coupon_code = $coupons->code;
            $usedCoupun     = $coupons->used_coupon();
            if ($coupons->limit == $usedCoupun) {
                $res_data['error'] = __('This coupon code has expired.');
            } else {
                $discount_value = ($plan->price / 100) * $coupons->discount;
                $price  = $price - $discount_value;
                if ($price < 0) {
                    $price = $plan->price;
                }
                $coupon_id = $coupons->id;
            }
        }
        $res_data['total_price'] = $price;
        $res_data['coupon']      = $coupon_id;
        // set your Iyzico API credentials
        try {
            // dd(route('iyzipay.payment.callback',[$plan->id,$price,$coupon_code]));
            $setBaseUrl = ($iyzipay_mode == 'sandbox') ? 'https://sandbox-api.iyzipay.com' : 'https://api.iyzipay.com';
            $options = new \Iyzipay\Options();
            $options->setApiKey($iyzipay_private_key);
            $options->setSecretKey($iyzipay_secret_key);
            $options->setBaseUrl($setBaseUrl); // or "https://api.iyzipay.com" for production
            $ipAddress = Http::get('https://ipinfo.io/?callback=')->json();
            $address = ($authuser->address) ? $authuser->address : 'Nidakule Göztepe, Merdivenköy Mah. Bora Sok. No:1';
            // create a new payment request
            $request = new \Iyzipay\Request\CreateCheckoutFormInitializeRequest();
            $request->setLocale('en');
            $request->setPrice($res_data['total_price']);
            $request->setPaidPrice($res_data['total_price']);
            $request->setCurrency($currency);
            $request->setCallbackUrl(route('iyzipay.payment.callback',[$plan->id,$price,$coupon_code,'user_id='.\Auth::user()->id]));

            $request->setEnabledInstallments(array(1));
            $request->setPaymentGroup(\Iyzipay\Model\PaymentGroup::PRODUCT);
            $buyer = new \Iyzipay\Model\Buyer();
            $buyer->setId($authuser->id);
            $buyer->setName(explode(' ', $authuser->name)[0]);
            $buyer->setSurname(explode(' ', $authuser->name)[0]);
            $buyer->setGsmNumber("+" . $authuser->dial_code . $authuser->phone);
            $buyer->setEmail($authuser->email);
            $buyer->setIdentityNumber(rand(0, 999999));
            $buyer->setLastLoginDate("2023-03-05 12:43:35");
            $buyer->setRegistrationDate("2023-04-21 15:12:09");
            $buyer->setRegistrationAddress($address);
            $buyer->setIp($ipAddress['ip']);
            $buyer->setCity($ipAddress['city']);
            $buyer->setCountry($ipAddress['country']);
            $buyer->setZipCode($ipAddress['postal']);
            $request->setBuyer($buyer);
            $shippingAddress = new \Iyzipay\Model\Address();
            $shippingAddress->setContactName($authuser->name);
            $shippingAddress->setCity($ipAddress['city']);
            $shippingAddress->setCountry($ipAddress['country']);
            $shippingAddress->setAddress($address);
            $shippingAddress->setZipCode($ipAddress['postal']);
            $request->setShippingAddress($shippingAddress);
            $billingAddress = new \Iyzipay\Model\Address();
            $billingAddress->setContactName($authuser->name);
            $billingAddress->setCity($ipAddress['city']);
            $billingAddress->setCountry($ipAddress['country']);
            $billingAddress->setAddress($address);
            $billingAddress->setZipCode($ipAddress['postal']);
            $request->setBillingAddress($billingAddress);
            $basketItems = array();
            $firstBasketItem = new \Iyzipay\Model\BasketItem();
            $firstBasketItem->setId("BI101");
            $firstBasketItem->setName("Binocular");
            $firstBasketItem->setCategory1("Collectibles");
            $firstBasketItem->setCategory2("Accessories");
            $firstBasketItem->setItemType(\Iyzipay\Model\BasketItemType::PHYSICAL);
            $firstBasketItem->setPrice($res_data['total_price']);
            $basketItems[0] = $firstBasketItem;
            $request->setBasketItems($basketItems);

            $checkoutFormInitialize = \Iyzipay\Model\CheckoutFormInitialize::create($request, $options);
            if($checkoutFormInitialize->getpaymentPageUrl() != null)
            {
                return redirect()->to($checkoutFormInitialize->getpaymentPageUrl());
            }else{
                return redirect()->route('plan.index')->with('error', 'Something went wrong, Please try again');
            }
            return redirect()->to($checkoutFormInitialize->getpaymentPageUrl());
        } catch (\Exception $e) {
            return redirect()->route('plan.index')->with('error', $e->getMessage());
        }
    }

    public function iyzipayCallback(Request $request, $planID, $price, $coupanCode = null)
    {
        $plan = Plan::find($planID);
        // $user = $request->user_id;
        $user = User::find($request->user_id);

        // dd($user , $request->all(), $request->user_id);
        $order = new PlanOrder();
        $order->order_id = time();
        $order->name = $user->name;
        $order->card_number = '';
        $order->card_exp_month = '';
        $order->card_exp_year = '';
        $order->plan_name = $plan->name;
        $order->plan_id = $plan->id;
        $order->price = $price;
        $order->price_currency = 'USD';
        $order->txn_id = time();
        $order->payment_type = __('Iyzipay');
        $order->payment_status = 'success';
        $order->txn_id = '';
        $order->receipt = '';
        $order->user_id = $user->id;
        $order->save();

        // dd($user);
        $coupons = PlanCoupon::where('code', $coupanCode)->where('is_active', '1')->first();
        if (!empty($coupons)) {
            $userCoupon         = new PlanUserCoupon();
            $userCoupon->user_id   = $user->id;
            $userCoupon->coupon_id = $coupons->id;
            $userCoupon->order  = $order->order_id;
            $userCoupon->save();
            $usedCoupun = $coupons->used_coupon();
            if ($coupons->limit <= $usedCoupun) {
                $coupons->is_active = 0;
                $coupons->save();
            }
        }
        $assignPlan = $user->assignPlan($plan->id);

        if ($assignPlan['is_success']) {
            return redirect()->route('plan.index')->with('success', __('Plan activated Successfully.'));
        } else {
            return redirect()->route('plan.index')->with('error', __($assignPlan['error']));
        }
    }

}
