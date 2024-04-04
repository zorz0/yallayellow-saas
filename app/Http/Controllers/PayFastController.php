<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use App\Models\AppSetting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Models\PlanCoupon;
use App\Models\PlanUserCoupon;
use App\Models\Plan;
use App\Models\PlanOrder;
use App\Models\Setting;
use App\Models\Utility;
use App\Models\Store;
use App\Models\City;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderBillingDetail;
use App\Models\OrderCouponDetail;
use App\Models\OrderTaxDetail;
use App\Models\UserCoupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use App\Http\Controllers\Api\ApiController;
use App\Models\ActivityLog;
use App\Models\Coupon;
use Session;
use App\Models\Admin;
use App\Models\OrderNote;

class PayFastController extends Controller
{
    //
    protected $payfast_merchant_id, $payfast_merchant_key, $payfast_signature, $payfast_mode, $is_enabled, $currency;
    public function paymentConfig()
    {

        if (Auth::user()->type == 'admin') {
            $payment_setting = getSuperAdminAllSetting();
        }
        $this->payfast_merchant_id = isset($payment_setting['payfast_merchant_id']) ? $payment_setting['payfast_merchant_id'] : '';
        $this->payfast_merchant_key = isset($payment_setting['payfast_merchant_key']) ? $payment_setting['payfast_merchant_key'] : '';
        $this->payfast_signature = isset($payment_setting['payfast_salt_passphrase']) ? $payment_setting['payfast_salt_passphrase'] : '';
        $this->currency = isset($payment_setting['CURRENCY_NAME']) ? $payment_setting['CURRENCY_NAME'] : '';
        $this->payfast_mode = isset($payment_setting['payfast_mode']) ? $payment_setting['payfast_mode'] : 'off';
        $this->is_enabled = isset($payment_setting['is_payfast_enabled']) ? $payment_setting['is_payfast_enabled'] : 'off';

        return $this;
    }

    public function index(Request $request)
    {
        if (Auth::check()) {

            $planID = Crypt::decrypt($request->plan_id);
            $plan   = Plan::find($planID);

            $payment_setting   = $this->paymentConfig();


            if ($plan) {

                $plan_amount = $plan->price;
                $order_id = strtoupper(str_replace('.', '', uniqid('', true)));
                $user = Auth::user();

                if ($request->coupon_code != null) {
                    $coupons = PlanCoupon::where('code', $request->coupon_code)->first();

                    if (!empty($coupons)) {
                        $userCoupon = new PlanUserCoupon();
                        $userCoupon->user_id = $user->id;
                        $userCoupon->coupon_id = $coupons->id;
                        $userCoupon->order = $order_id;
                        $userCoupon->save();
                        $usedCoupun = $coupons->used_coupon();
                        if ($coupons->limit <= $usedCoupun) {
                            $coupons->is_active = 0;
                            $coupons->save();
                        }

                        $plan_amount = $request->coupon_amount;
                    }
                }
                $success = Crypt::encrypt([
                    'plan' => $plan->toArray(),
                    'order_id' => $order_id,
                    'plan_amount' => $plan_amount
                ]);

                $data = array(
                    // Merchant details
                    'merchant_id' => !empty($payment_setting->payfast_merchant_id) ? $payment_setting->payfast_merchant_id : '',
                    'merchant_key' => !empty($payment_setting->payfast_merchant_key) ? $payment_setting->payfast_merchant_key : '',
                    'return_url' => route('payfast.payment.success', $success),
                    'cancel_url' => route('plan.index'),
                    'notify_url' => route('plan.index'),
                    // Buyer details
                    'name_first' => $user->name,
                    'name_last' => '',
                    'email_address' => $user->email,
                    // Transaction details
                    'm_payment_id' => $order_id, //Unique payment ID to pass through to notify_url
                    'amount' => number_format(sprintf('%.2f', $plan_amount), 2, '.', ''),
                    'item_name' => $plan->name,
                );
                $passphrase = !empty($payment_setting->payfast_signature) ? $payment_setting->payfast_signature : '';

                $signature = $this->generateSignature($data, $passphrase);
                $data['signature'] = $signature;

                $htmlForm = '';

                foreach ($data as $name => $value) {
                    $htmlForm .= '<input name="' . $name . '" type="hidden" value=\'' . $value . '\' />';
                }
                return response()->json([
                    'success' => true,
                    'inputs' => $htmlForm
                ]);
            }
        }
    }

    public function generateSignature($data, $passPhrase = null)
    {

        $pfOutput = '';
        foreach ($data as $key => $val) {
            if ($val !== '') {
                $pfOutput .= $key . '=' . urlencode(trim($val)) . '&';
            }
        }

        $getString = substr($pfOutput, 0, -1);
        if ($passPhrase !== null) {
            $getString .= '&passphrase=' . urlencode(trim($passPhrase));
        }
        return md5($getString);
    }

    public function success($success)
    {
        try {
            $user = Auth::user();
            $data = Crypt::decrypt($success);

            $order = new PlanOrder();
            $order->order_id = $data['order_id'];
            $order->name = $user->name;
            $order->card_number = '';
            $order->card_exp_month = '';
            $order->card_exp_year = '';
            $order->plan_name = $data['plan']['name'];
            $order->plan_id = $data['plan']['id'];
            $order->price = $data['plan_amount'];
            $order->price_currency = 'USD';
            $order->txn_id = $data['order_id'];
            $order->payment_type = __('PayFast');
            $order->payment_status = 'success';
            $order->txn_id = '';
            $order->receipt = '';
            $order->user_id = $user->id;
            $order->save();
            $assignPlan = $user->assignPlan($data['plan']['id']);

            if ($assignPlan['is_success']) {
                return redirect()->route('plan.index')->with('success', __('Plan activated Successfully.'));
            } else {
                return redirect()->route('plan.index')->with('error', __($assignPlan['error']));
            }
        } catch (Exception $e) {
            return redirect()->route('plan.index')->with('error', __($e));
        }
    }

}
