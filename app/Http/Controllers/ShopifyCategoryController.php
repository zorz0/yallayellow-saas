<?php

namespace App\Http\Controllers;

use App\Models\MainCategory;
use App\Models\ShopifyConection;
use App\Models\User;
use App\Models\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ShopifyCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (auth()->user()->isAbleTo('Manage Shopify Category')) {
            try {
                $theme_name = !empty(env('DATA_INSERT_APP_THEME')) ? env('DATA_INSERT_APP_THEME') : APP_THEME();
                $shopify_store_url = \App\Models\Utility::GetValueByName('shopify_store_url', $theme_name);
                $shopify_access_token = \App\Models\Utility::GetValueByName('shopify_access_token', $theme_name);
                // dd($shopify_store_url,$shopify_access_token);

                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => "https://$shopify_store_url.myshopify.com/admin/api/2023-07/custom_collections.json",
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
                    $category = json_decode($response, true);
                    if (isset($category['errors'])) {
                        $errorMessage = $category['errors'];
                        return redirect()->back()->with('error', $errorMessage);
                    } else {
                        if (isset($category) && !empty($category)) {
                            $upddata = ShopifyConection::where('theme_id',$theme_name)->where('store_id',getCurrentStore())->where('module','=','category')->pluck('shopify_id')->toArray();
                            return  view('shopify.category', compact('category', 'upddata'));
                        }
                    }
                }
                // dd($shopify_store_url);



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
    public function store(Request $request, $id)
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
        
        if (auth()->user()->isAbleTo('Create Shopify Category')) {
            try {
                $theme_name = !empty(env('DATA_INSERT_APP_THEME')) ? env('DATA_INSERT_APP_THEME') : APP_THEME();
                $shopify_store_url = \App\Models\Utility::GetValueByName('shopify_store_url', $theme_name);
                $shopify_access_token = \App\Models\Utility::GetValueByName('shopify_access_token', $theme_name);
                // dd($shopify_store_url,$shopify_access_token);

                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => "https://$shopify_store_url.myshopify.com/admin/api/2023-07/custom_collections.json?ids=$id",
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
                    $category = json_decode($response, true);

                    if (isset($category['errors'])) {

                        $errorMessage = $category['errors'];
                        return redirect()->back()->with('error', $errorMessage);
                    } else {
                        if (isset($category) && !empty($category)) {
                            if (!empty($category['custom_collections'][0]['image']['src'])) {

                                $ImageUrl = $category['custom_collections'][0]['image']['src'];
                                $file_type = config('files_types');
                                $url = strtok($ImageUrl, '?');

                                foreach ($file_type as $f) {

                                    $name = basename($url, "." . $f);
                                }
                                $file2 = rand(10, 100) . '_' . time() . "_" . $name;
                                $path = 'themes/' . APP_THEME() . '/uploads/';
                                $uplaod = Utility::upload_woo_file($url, $file2, $path);
                            } else {
                                $url  = asset(Storage::url('uploads/shopify.png'));
                                $name = 'shopify.png';
                                $file2 = rand(10, 100) . '_' . time() . "_" . $name;
                                $path = 'themes/' . APP_THEME() . '/uploads/';
                                $uplaod = Utility::upload_woo_file($url, $file2, $path);
                            }
                            if (!empty($category)) {
                                $MainCategory               = new MainCategory();
                                $MainCategory->name         = $category['custom_collections'][0]['title'];
                                $MainCategory->image_url    = $uplaod['full_url'];
                                $MainCategory->image_path   = $uplaod['url'];
                                $MainCategory->icon_path    = $uplaod['url'];
                                $MainCategory->trending     = 0;
                                $MainCategory->status       = 1;
                                $MainCategory->theme_id     = APP_THEME();
                                $MainCategory->store_id     = getCurrentStore();
                                $MainCategory->save();

                                $Category                   = new ShopifyConection();
                                $Category->store_id         = getCurrentStore();
                                $Category->theme_id         = APP_THEME();
                                $Category->module           = 'category';
                                $Category->shopify_id       = $category['custom_collections'][0]['id'];
                                $Category->original_id  = $MainCategory->id;

                                $Category->save();

                                return redirect()->back()->with('success', __('Category successfully add.'));
                            } else {
                                return redirect()->back()->with('error', __('Category Not Found.'));
                            }
                        }
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
        if (auth()->user()->isAbleTo('Edit Shopify Category')) {
            try {
                $theme_name = !empty(env('DATA_INSERT_APP_THEME')) ? env('DATA_INSERT_APP_THEME') : APP_THEME();
                $shopify_store_url = \App\Models\Utility::GetValueByName('shopify_store_url', $theme_name);
                $shopify_access_token = \App\Models\Utility::GetValueByName('shopify_access_token', $theme_name);
                // dd($shopify_store_url,$shopify_access_token);

                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => "https://$shopify_store_url.myshopify.com/admin/api/2023-07/custom_collections.json?ids=$id",
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
                    $category = json_decode($response, true);

                    if (isset($category['errors'])) {

                        $errorMessage = $category['errors'];
                        return redirect()->back()->with('error', $errorMessage);
                    } else {
                        $upddata = ShopifyConection::where('theme_id', $theme_name)->where('store_id', getCurrentStore())->where('module', '=', 'category')->where('shopify_id', $id)->first();
                        $original_id = $upddata->original_id;

                        if (!empty($category['custom_collections'][0]['image']['src'])) {

                            $ImageUrl = $category['custom_collections'][0]['image']['src'];
                            $file_type = config('files_types');
                            $url = strtok($ImageUrl, '?');

                            foreach ($file_type as $f) {

                                $name = basename($url, "." . $f);
                            }
                            $file2 = rand(10, 100) . '_' . time() . "_" . $name;
                            $path = 'themes/' . APP_THEME() . '/uploads/';
                            $uplaod = Utility::upload_woo_file($url, $file2, $path);
                        } else {
                            $url  = asset(Storage::url('uploads/shopify.png'));
                            $name = 'shopify.png';
                            $file2 = rand(10, 100) . '_' . time() . "_" . $name;
                            $path = 'themes/' . APP_THEME() . '/uploads/';
                            $uplaod = Utility::upload_woo_file($url, $file2, $path);
                        }
                        if (!empty($category)) {
                            $MainCategory               = MainCategory::find($original_id);
                            $MainCategory->name         = $category['custom_collections'][0]['title'];
                            $MainCategory->image_url    = $uplaod['full_url'];
                            $MainCategory->image_path   = $uplaod['url'];
                            $MainCategory->icon_path    = $uplaod['url'];
                            $MainCategory->trending     = 0;
                            $MainCategory->status       = 1;
                            $MainCategory->save();

                            return redirect()->back()->with('success', __('Category successfully update.'));
                        } else {
                            return redirect()->back()->with('error', __('Category Not Found.'));
                        }
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
