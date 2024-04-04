<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\ShopifyConection;
use App\Models\Store;
use Illuminate\Http\Request;

class ShopifyCouponController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (auth()->user()->isAbleTo('Manage Shopify Coupon')) {
            try {
                $theme_name = !empty(env('DATA_INSERT_APP_THEME')) ? env('DATA_INSERT_APP_THEME') : APP_THEME();
                $shopify_store_url = \App\Models\Utility::GetValueByName('shopify_store_url', $theme_name);
                $shopify_access_token = \App\Models\Utility::GetValueByName('shopify_access_token', $theme_name);
                // dd($shopify_store_url,$shopify_access_token);

                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => "https://$shopify_store_url.myshopify.com/admin/api/unstable/price_rules.json",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'GET',
                    CURLOPT_HTTPHEADER => array(
                        "X-Shopify-Access-Token: $shopify_access_token"
                    ),
                ));

                $response = curl_exec($curl);
                curl_close($curl);
                if ($response == false) {
                    return redirect()->back()->with('error', 'Something went wrong.');
                } else {
                    $coupon = json_decode($response, true);

                    if (isset($coupon['errors'])) {

                        $errorMessage = $coupon['errors'];
                        return redirect()->back()->with('error', $errorMessage);
                    } else {
                // dd($coupon)
                $upddata = ShopifyConection::where('theme_id', $theme_name)->where('store_id', getCurrentStore())->where('module', '=', 'coupon')->get()->pluck('shopify_id')->toArray();

                return  view('shopify.coupon', compact('coupon', 'upddata'));
                    }
                }
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Something went wrong.');
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
        if (auth()->user()->isAbleTo('Create Shopify Coupon')) {
            $theme_name = !empty(env('DATA_INSERT_APP_THEME')) ? env('DATA_INSERT_APP_THEME') : APP_THEME();
            $shopify_store_url = \App\Models\Utility::GetValueByName('shopify_store_url', $theme_name);
            $shopify_access_token = \App\Models\Utility::GetValueByName('shopify_access_token', $theme_name);
            // dd($shopify_store_url,$shopify_access_token);

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://$shopify_store_url.myshopify.com/admin/api/2023-04/price_rules/$id.json",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    "X-Shopify-Access-Token: $shopify_access_token"
                ),
            ));

            $response = curl_exec($curl);
            curl_close($curl);
            if (isset($customer['errors'])) {

                $errorMessage = $customer['errors'];
                return redirect()->back()->with('error', $errorMessage);
            } else {
                $coupons = json_decode($response, true);
                if ($coupons['price_rule']['value_type'] == 'fixed_amount') {
                    $coupon_type = 'flat';
                } else {
                    $coupon_type = 'percentage';
                }

                if (!empty($coupons)) {
                    $coupon                    = new Coupon();
                    $coupon->coupon_name       = $coupons['price_rule']['title'];
                    $coupon->coupon_type       = $coupon_type;
                    $coupon->discount_amount   =  str_replace('-', '', $coupons['price_rule']['value']);
                    $coupon->coupon_limit      =  !empty($coupons['price_rule']['usage_limit']) ? $coupons['price_rule']['usage_limit'] : -1;
                    $coupon->coupon_expiry_date = $coupons['price_rule']['ends_at'];
                    $coupon->coupon_code       = $coupons['price_rule']['title'];
                    $coupon->status            = 1;
                    $coupon->theme_id          = APP_THEME();
                    $coupon->store_id          = getCurrentStore();
                    $coupon->save();


                    $Coupon                   = new ShopifyConection();
                    $Coupon->store_id         = getCurrentStore();
                    $Coupon->theme_id         = APP_THEME();
                    $Coupon->module           = 'coupon';
                    $Coupon->shopify_id       = $coupons['price_rule']['id'];
                    $Coupon->original_id      = $coupon->id;
                    $Coupon->save();
                    return redirect()->back()->with('success', __('Coupon successfully Add.'));
                } else {

                    return redirect()->back()->with('error', __('Coupon Not Found.'));
                }
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
        if (auth()->user()->isAbleTo('Edit Shopify Coupon')) {
            try {
                $theme_name = !empty(env('DATA_INSERT_APP_THEME')) ? env('DATA_INSERT_APP_THEME') : APP_THEME();
                $store_id = Store::where('id', getCurrentStore())->first();
                $shopify_store_url = \App\Models\Utility::GetValueByName('shopify_store_url', $theme_name);
                $shopify_access_token = \App\Models\Utility::GetValueByName('shopify_access_token', $theme_name);

                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => "https://$shopify_store_url.myshopify.com/admin/api/2023-04/price_rules/$id.json",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'GET',
                    CURLOPT_HTTPHEADER => array(
                        "X-Shopify-Access-Token: $shopify_access_token"
                    ),
                ));

                $response = curl_exec($curl);
                curl_close($curl);
                if ($response == false) {
                    return redirect()->back()->with('error', 'Something went wrong.');
                } else {
                    $products = json_decode($response, true);
                    if (isset($products['errors'])) {

                        $errorMessage = $products['errors'];
                        return redirect()->back()->with('error', $errorMessage);
                    } else {
                        $coupons = json_decode($response, true);
                        if ($coupons['price_rule']['value_type'] == 'fixed_amount') {
                            $coupon_type = 'flat';
                        } else {
                            $coupon_type = 'percentage';
                        }

                        if (!empty($coupons)) {
                            $upddata = ShopifyConection::where('theme_id',$store_id->theme_id)->where('store_id',getCurrentStore())->where('module','=','coupon')->where('shopify_id' , $id)->first();
                            $original_id = $upddata->original_id;

                            $coupon                    = Coupon::find($original_id);                            $coupon->coupon_name       = $coupons['price_rule']['title'];
                            $coupon->coupon_type       = $coupon_type;
                            $coupon->discount_amount   =  str_replace('-', '', $coupons['price_rule']['value']);
                            $coupon->coupon_limit      =  !empty($coupons['price_rule']['usage_limit']) ? $coupons['price_rule']['usage_limit'] : -1;
                            $coupon->coupon_expiry_date = $coupons['price_rule']['ends_at'];
                            $coupon->coupon_code       = $coupons['price_rule']['title'];
                            $coupon->save();
                            return redirect()->back()->with('success', __('Coupon successfully update.'));
                        } else {

                            return redirect()->back()->with('error', __('Coupon Not Found.'));
                        }
                    }
                }
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'This email already used.');
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }    
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
