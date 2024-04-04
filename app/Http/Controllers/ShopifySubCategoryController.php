<?php

namespace App\Http\Controllers;

use App\Models\{MainCategory, SubCategory};
use App\Models\ShopifyConection;
use App\Models\User;
use App\Models\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ShopifySubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (auth()->user()->isAbleTo('Manage Shopify SubCategory')) {
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
                    $sub_category = json_decode($response, true);
                    if (isset($sub_category['errors'])) {
                        $errorMessage = $sub_category['errors'];
                        return redirect()->back()->with('error', $errorMessage);
                    } else {
                        if (isset($sub_category) && !empty($sub_category)) {
                            $upddata = ShopifyConection::where('theme_id',$theme_name)->where('store_id',getCurrentStore())->where('module', 'sub_category')->pluck('shopify_id')->toArray();
                            return  view('shopify.sub_category', compact('sub_category', 'upddata'));
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
        
        if (auth()->user()->isAbleTo('Create Shopify SubCategory')) {
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
                            $themeId = APP_THEME(); 
                            $storeId = getCurrentStore(); 
                            if (!empty($category['custom_collections'][0]['image']['src'])) {

                                $ImageUrl = $category['custom_collections'][0]['image']['src'];
                                $file_type = config('files_types');
                                $url = strtok($ImageUrl, '?');

                                foreach ($file_type as $f) {

                                    $name = basename($url, "." . $f);
                                }
                                $file2 = rand(10, 100) . '_' . time() . "_" . $name;
                                $path = 'themes/' . $themeId . '/uploads/';
                                $uplaod = Utility::upload_woo_file($url, $file2, $path);
                            } else {
                                $url  = asset(Storage::url('uploads/shopify.png'));
                                $name = 'shopify.png';
                                $file2 = rand(10, 100) . '_' . time() . "_" . $name;
                                $path = 'themes/' . $themeId . '/uploads/';
                                $uplaod = Utility::upload_woo_file($url, $file2, $path);
                            }

                            
                            if (!empty($category)) {
                                $categoryQuery = MainCategory::query();
                                $subCategoryQuery = SubCategory::query();
                                $shopifyConectionQuery = ShopifyConection::query();
                                $existCategory = (clone $categoryQuery)->where('name',  $category['custom_collections'][0]['title'])->where('store_id', $storeId)->where('theme_id', $themeId)->first();

                                if (!$existCategory) {
                                    $existCategory = (clone $categoryQuery)->create([
                                        'name'       => $category['custom_collections'][0]['title'],
                                        'image_url'  => $uplaod['full_url'],
                                        'image_path' => $uplaod['url'],
                                        'icon_path'  => $uplaod['url'],
                                        'trending'   => 0,
                                        'status'     => 1,
                                        'theme_id'   => $themeId,
                                        'store_id'   => $storeId,
                                    ]);

                                    (clone $shopifyConectionQuery)->create([
                                        'module'       => 'category',
                                        'original_id'  => $existCategory->id,
                                        'shopify_id'   => $category['custom_collections'][0]['id'],
                                        'theme_id'     => $themeId,
                                        'store_id'     => $storeId,
                                    ]);
                                }
                                $existSubCategory = (clone $subCategoryQuery)->where('name',  $category['custom_collections'][0]['title'])->where('maincategory_id', $existCategory->id)->where('store_id', $storeId)->where('theme_id', $themeId)->first();
                                if (!$existSubCategory) {
                                    $existSubCategory = (clone $subCategoryQuery)->create([
                                        'name'       => $category['custom_collections'][0]['title'],
                                        'image_url'  => $uplaod['full_url'],
                                        'image_path' => $uplaod['url'],
                                        'icon_path'  => $uplaod['url'],
                                        'maincategory_id'  =>$existCategory->id,
                                        'status'     => 1,
                                        'theme_id'   => $themeId,
                                        'store_id'   => $storeId,
                                    ]);

                                    (clone $shopifyConectionQuery)->create([
                                        'module'       => 'sub_category',
                                        'original_id'  => $existSubCategory->id,
                                        'shopify_id'   => $category['custom_collections'][0]['id'],
                                        'theme_id'     => $themeId,
                                        'store_id'     => $storeId,
                                    ]);
                                }
                                return redirect()->back()->with('success', __('SubCategory successfully add.'));
                            } else {
                                return redirect()->back()->with('error', __('SubCategory Not Found.'));
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
        
        if (auth()->user()->isAbleTo('Edit Shopify SubCategory')) {
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
                        $themeId = APP_THEME(); 
                        $storeId = getCurrentStore(); 
                        $shopifyConectionQuery = ShopifyConection::query();
                        $categoryData = (clone $shopifyConectionQuery)->where('theme_id', $theme_name)->where('store_id', $storeId)->where('module', 'category')->where('shopify_id', $id)->first();

                        $category_id = $categoryData->original_id;
                        $subCategoryData = (clone $shopifyConectionQuery)->where('theme_id', $theme_name)->where('store_id', $storeId)->where('module', 'sub_category')->where('shopify_id', $id)->first();

                        $sub_category_id = $subCategoryData->original_id;

                        if (!empty($category['custom_collections'][0]['image']['src'])) {

                            $ImageUrl = $category['custom_collections'][0]['image']['src'];
                            $file_type = config('files_types');
                            $url = strtok($ImageUrl, '?');

                            foreach ($file_type as $f) {

                                $name = basename($url, "." . $f);
                            }
                            $file2 = rand(10, 100) . '_' . time() . "_" . $name;
                            $path = 'themes/' . $themeId . '/uploads/';
                            $uplaod = Utility::upload_woo_file($url, $file2, $path);
                        } else {
                            $url  = asset(Storage::url('uploads/shopify.png'));
                            $name = 'shopify.png';
                            $file2 = rand(10, 100) . '_' . time() . "_" . $name;
                            $path = 'themes/' . $themeId . '/uploads/';
                            $uplaod = Utility::upload_woo_file($url, $file2, $path);
                        }
                        if (!empty($category)) {
                            $existCategory = MainCategory::find($category_id);
                            if ($existCategory) {
                                $existCategory->name         = $category['custom_collections'][0]['title'];
                                $existCategory->image_url    = $uplaod['full_url'];
                                $existCategory->image_path   = $uplaod['url'];
                                $existCategory->icon_path    = $uplaod['url'];
                                $existCategory->trending     = 0;
                                $existCategory->status       = 1;
                                $existCategory->save();
                            }
                            $existSubCategory= SubCategory::find($sub_category_id);
                            if ($existSubCategory) {
                                $existSubCategory->name         = $category['custom_collections'][0]['title'];
                                $existSubCategory->image_url    = $uplaod['full_url'];
                                $existSubCategory->image_path   = $uplaod['url'];
                                $existSubCategory->icon_path    = $uplaod['url'];
                                $existSubCategory->status       = 1;
                                $existSubCategory->save();
                            }
                            

                            return redirect()->back()->with('success', __('SubCategory successfully update.'));
                        } else {
                            return redirect()->back()->with('error', __('SubCategory Not Found.'));
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
