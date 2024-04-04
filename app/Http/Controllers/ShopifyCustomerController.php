<?php

namespace App\Http\Controllers;

use App\Models\ShopifyConection;
use App\Models\User;
use App\Models\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ShopifyCustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (auth()->user()->isAbleTo('Manage Shopify Customer')) {
            try {
                $theme_name = !empty(env('DATA_INSERT_APP_THEME')) ? env('DATA_INSERT_APP_THEME') : APP_THEME();
                $shopify_store_url = \App\Models\Utility::GetValueByName('shopify_store_url', $theme_name);
                $shopify_access_token = \App\Models\Utility::GetValueByName('shopify_access_token', $theme_name);
                // dd($shopify_store_url,$shopify_access_token);

                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => "https://$shopify_store_url.myshopify.com/admin/api/2023-07/customers.json",
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
                    $customers = json_decode($response, true);

                    if (isset($customers['errors'])) {

                        $errorMessage = $customers['errors'];
                        return redirect()->back()->with('error', $errorMessage);
                    } else {
                        $upddata = User::get()->pluck('email')->toArray();
                    }
                }

                return  view('shopify.customer', compact('customers', 'upddata'));
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
        
        if (auth()->user()->isAbleTo('Create Shopify Customer')) {
            try {
                $theme_name = !empty(env('DATA_INSERT_APP_THEME')) ? env('DATA_INSERT_APP_THEME') : APP_THEME();
                $shopify_store_url = \App\Models\Utility::GetValueByName('shopify_store_url', $theme_name);
                $shopify_access_token = \App\Models\Utility::GetValueByName('shopify_access_token', $theme_name);
                // dd($shopify_store_url,$shopify_access_token);

                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => "https://$shopify_store_url.myshopify.com/admin/api/2023-07/customers.json?ids=$id",
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
                    $customer = json_decode($response, true);
                    // dd($customer['customers'][0]['first_name'] ,$customer);

                    if (isset($customer['errors'])) {

                        $errorMessage = $customer['errors'];
                        return redirect()->back()->with('error', $errorMessage);
                    } else {
                        if (isset($customer) && !empty($customer)) {

                            if (!empty($customer['customers'][0]['image']['src'])) {

                                $ImageUrl = $customer['customers'][0]['image']['src'];
                                $file_type = config('files_types');
                                $url = strtok($ImageUrl, '?');

                                foreach ($file_type as $f) {

                                    $name = basename($url, "." . $f);
                                }
                                $file2 = rand(10, 100) . '_' . time() . "_" . $name;
                                $path = 'themes/' . APP_THEME() . '/uploads/';
                                $uplaod = Utility::upload_woo_file($url, $file2, $path);


                            } else {
                                $url  = asset(Storage::url('uploads/woocommerce.png'));
                                $name = 'woocommerce.png';
                                $file2 = rand(10, 100) . '_' . time() . "_" . $name;
                                $path = 'themes/' . APP_THEME() . '/uploads/';
                                $uplaod = Utility::upload_woo_file($url, $file2, $path);
                            }
                            if (!empty($customer)) {
                                $cutomer                = new User();
                                $cutomer->first_name    = $customer['customers'][0]['first_name'];
                                $cutomer->last_name     = $customer['customers'][0]['last_name'];
                                $cutomer->profile_image = $uplaod['url'];
                                $cutomer->email         = $customer['customers'][0]['email'];
                                $cutomer->type          = 'customer';
                                $cutomer->password      = Hash::make('1234');
                                $cutomer->register_type = 'email';
                                $cutomer->mobile        = $customer['customers'][0]['phone'];
                                $cutomer->theme_id      = APP_THEME();
                                $cutomer->store_id      = getCurrentStore();
                                $cutomer->created_by    = auth()->user()->id;
                                $cutomer->save();

                                $Customer                   = new ShopifyConection();
                                $Customer->store_id         = getCurrentStore();
                                $Customer->theme_id         = APP_THEME();
                                $Customer->module           = 'customer';
                                $Customer->shopify_id       = $customer['customers'][0]['id'];
                                $Customer->original_id      = $cutomer->id;

                                $Customer->save();

                                return redirect()->back()->with('success', __('Customer successfully Add , We set customer password 1234.'));
                            } else {
                                return redirect()->back()->with('error', __('Customer Not Found.'));
                            }
                        }
                    }
                }
            } catch (\Exception $e) {
                // dd($e);
                return redirect()->back()->with('error', 'This email already used.');
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
        
        if (auth()->user()->isAbleTo('Edit Shopify Customer')) {
            try {
                $theme_name = !empty(env('DATA_INSERT_APP_THEME')) ? env('DATA_INSERT_APP_THEME') : APP_THEME();
                $shopify_store_url = \App\Models\Utility::GetValueByName('shopify_store_url', $theme_name);
                $shopify_access_token = \App\Models\Utility::GetValueByName('shopify_access_token', $theme_name);
                // dd($shopify_store_url,$shopify_access_token);

                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => "https://$shopify_store_url.myshopify.com/admin/api/2023-07/customers.json?ids=$id",
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
                    $customer = json_decode($response, true);

                    if (isset($customer['errors'])) {

                        $errorMessage = $customer['errors'];
                        return redirect()->back()->with('error', $errorMessage);
                    } else {
                        if (isset($customer) && !empty($customer)) {

                            if (!empty($customer['customers'][0]['image']['src'])) {

                                $ImageUrl = $customer['customers'][0]['image']['src'];
                                $file_type = config('files_types');
                                $url = strtok($ImageUrl, '?');

                                foreach ($file_type as $f) {

                                    $name = basename($url, "." . $f);
                                }
                                $file2 = rand(10, 100) . '_' . time() . "_" . $name;
                                $path = 'themes/' . APP_THEME() . '/uploads/';
                                $uplaod = Utility::upload_woo_file($url, $file2, $path);
                            } else {
                                $url  = asset(Storage::url('uploads/woocommerce.png'));
                                $name = 'woocommerce.png';
                                $file2 = rand(10, 100) . '_' . time() . "_" . $name;
                                $path = 'themes/' . APP_THEME() . '/uploads/';
                                $uplaod = Utility::upload_woo_file($url, $file2, $path);
                            }
                            if (!empty($customer)) {
                                $upddata = ShopifyConection::where('theme_id', $theme_name)->where('store_id', getCurrentStore())->where('module', '=', 'customer')->where('shopify_id', $customer['customers'][0]['id'])->pluck('original_id')->first();
                                $cutomer                 = User::find($upddata);
                                $cutomer->first_name    = $customer['customers'][0]['first_name'];
                                $cutomer->last_name     = $customer['customers'][0]['last_name'];
                                $cutomer->profile_image = $uplaod['url'];
                                $cutomer->email         = $customer['customers'][0]['email'];
                                $cutomer->type          = 'customer';
                                $cutomer->password      = Hash::make('1234');
                                $cutomer->register_type = 'email';
                                $cutomer->mobile        = $customer['customers'][0]['default_address']['phone'];
                                $cutomer->save();

                                return redirect()->back()->with('success', __('Customer successfully update.'));
                            } else {
                                return redirect()->back()->with('error', __('Customer Not Found.'));
                            }
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
