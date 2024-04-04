<?php

namespace App\Http\Controllers;

use App\Models\SubCategory;
use App\Models\Setting;
use App\Models\Store;
use App\Models\Utility;
use App\Models\WoocommerceConection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Codexshaper\WooCommerce\Facades\Category;
use Xendit\Xendit;

class WoocomSubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (auth()->user()->isAbleTo('Manage Woocommerce SubCategory'))
        {
            try{
                $theme_name = !empty(env('DATA_INSERT_APP_THEME')) ? env('DATA_INSERT_APP_THEME') : APP_THEME();
                $woocommerce_store_url =Utility::GetValueByName('woocommerce_store_url',$theme_name);
                $woocommerce_consumer_secret =Utility::GetValueByName('woocommerce_consumer_secret',$theme_name);
                $woocommerce_consumer_key =Utility::GetValueByName('woocommerce_consumer_key',$theme_name);

                config(['woocommerce.store_url' => $woocommerce_store_url]);
                config(['woocommerce.consumer_key' => $woocommerce_consumer_key]);
                config(['woocommerce.consumer_secret' => $woocommerce_consumer_secret]);

                $jsonData = Category::all(['per_page' => 100])->filter(function ($category) {
                    return $category->parent !== 0;
                });

                $store_id = Store::where('id', getCurrentStore())->first();
                $subCategory = SubCategory::where('theme_id',$store_id->theme_id)->where('store_id',getCurrentStore())->get();
                $upddata = WoocommerceConection::where('theme_id',$store_id->theme_id)->where('store_id',getCurrentStore())->where('module','=','sub_category')->get()->pluck('woocomerce_id')->toArray();
                return view('woocommerce.sub_category', compact('jsonData','upddata','subCategory'));

            }
            catch(\Exception $e){
                return redirect()->back()->with('error' , 'Something went wrong.');
            }
        }
        else
        {
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
        $theme_name = !empty(env('DATA_INSERT_APP_THEME')) ? env('DATA_INSERT_APP_THEME') : APP_THEME();
        $woocommerce_store_url =Utility::GetValueByName('woocommerce_store_url',$theme_name);
        $response = \Http::get($woocommerce_store_url . '/wp-json/wc/v3/products/categories');
        $jsonData = $response->json();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request ,$id)
    {
        
        if (auth()->user()->isAbleTo('Create Woocommerce SubCategory'))
        {
            $theme_name = !empty(env('DATA_INSERT_APP_THEME')) ? env('DATA_INSERT_APP_THEME') : APP_THEME();
            $woocommerce_store_url =Utility::GetValueByName('woocommerce_store_url',$theme_name);
            $woocommerce_consumer_secret =Utility::GetValueByName('woocommerce_consumer_secret',$theme_name);
            $woocommerce_consumer_key =Utility::GetValueByName('woocommerce_consumer_key',$theme_name);

            config(['woocommerce.store_url' => $woocommerce_store_url]);
            config(['woocommerce.consumer_key' => $woocommerce_consumer_key]);
            config(['woocommerce.consumer_secret' => $woocommerce_consumer_secret]);

            $jsonData = Category::find($id);
            if (isset($jsonData['parent'])) {
                $exist = WoocommerceConection::where('module', 'category')->where('woocomerce_id', $jsonData['parent'])->first();
                if (!$exist) {
                    return redirect()->back()->with('error', __('This Sub Category Main Category Not Synced. Please First Create Sync Main Category.'));
                }
            }
            
            if(!empty($jsonData['image']->src)) {

                $url = $jsonData['image']->src;
                $file_type = config('files_types');

                foreach($file_type as $f){
                    $name = basename($url, ".".$f);
                }
                $file2 = rand(10,100).'_'.time() . "_" . $name;
                $path = 'themes/'.APP_THEME().'/uploads/';
                $uplaod =Utility::upload_woo_file($url,$file2,$path);
            }
            else{
                $url  = asset(Storage::url('uploads/woocommerce.png'));
                $name = 'woocommerce.png';
                $file2 = rand(10,100).'_'.time() . "_" . $name;
                $path = 'themes/'.APP_THEME().'/uploads/';
                $uplaod =Utility::upload_woo_file($url,$file2,$path);


            }
            if (!empty($jsonData)) {

            $subCategory               = new SubCategory();
            $subCategory->name         = $jsonData['name'];
            $subCategory->maincategory_id = $exist->original_id ?? 0;
            $subCategory->image_url    = $uplaod['full_url'];
            $subCategory->image_path   = $uplaod['url'];
            $subCategory->icon_path    = $uplaod['url'];
            $subCategory->status       = 1;
            $subCategory->theme_id     = APP_THEME();
            $subCategory->store_id     = getCurrentStore();
            $subCategory->save();

            $Category                   = new WoocommerceConection();
            $Category->store_id         = getCurrentStore();
            $Category->theme_id         = APP_THEME();
            $Category->module           = 'sub_category';
            $Category->woocomerce_id    = $jsonData['id'];
            $Category->original_id      = $subCategory->id;
            $Category->save();

            return redirect()->back()->with('success', __('Category successfully created.'));
            } else {
                return redirect()->back()->with('error', __('Category Not Found.'));
            }
        }
        else
        {
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
        
        if (auth()->user()->isAbleTo('Edit Woocommerce SubCategory'))
        {
            $theme_name = !empty(env('DATA_INSERT_APP_THEME')) ? env('DATA_INSERT_APP_THEME') : APP_THEME();
            $woocommerce_store_url =Utility::GetValueByName('woocommerce_store_url',$theme_name);
            $woocommerce_consumer_secret =Utility::GetValueByName('woocommerce_consumer_secret',$theme_name);
            $woocommerce_consumer_key =Utility::GetValueByName('woocommerce_consumer_key',$theme_name);

            config(['woocommerce.store_url' => $woocommerce_store_url]);
            config(['woocommerce.consumer_key' => $woocommerce_consumer_key]);
            config(['woocommerce.consumer_secret' => $woocommerce_consumer_secret]);

            $jsonData = Category::find($id);
            $woocommerceConectionQuery = WoocommerceConection::query();
            $woocommerceSubCat = (clone $woocommerceConectionQuery)->where('module', 'sub_category')->where('woocomerce_id', $jsonData['id'])->first();
            if (isset($jsonData['parent'])) {
                $exist = (clone $woocommerceConectionQuery)->where('module', 'category')->where('woocomerce_id', $jsonData['parent'])->first();
                if (!$exist) {
                    return redirect()->back()->with('error', __('This Sub Category Main Category Not Synced. Please First Create Sync Main Category.'));
                }
            }
            if (!$woocommerceSubCat) {
                return redirect()->back()->with('error', __('This Sub Category Main Category Not Synced. Please First Create Sync Main Category.'));
            }
            $store_id = Store::where('id', getCurrentStore())->first();
            $subCategory = SubCategory::find($woocommerceSubCat->original_id);
            if(!empty($jsonData['image']->src)) {

                $url = $jsonData['image']->src;
                $file_type = config('files_types');

                foreach($file_type as $f){
                    $name = basename($url, ".".$f);
                }
                $file2 = rand(10,100).'_'.time() . "_" . $name;
                $path = 'themes/'.APP_THEME().'/uploads/';
                $uplaod =Utility::upload_woo_file($url,$file2,$path);
            }
            else{
                $url  = asset(Storage::url('uploads/woocommerce.png'));
                $name = 'woocommerce.png';
                $file2 = rand(10,100).'_'.time() . "_" . $name;
                $path = 'themes/'.APP_THEME().'/uploads/';
                $uplaod =Utility::upload_woo_file($url,$file2,$path);

            }
            if (!empty($jsonData)) {
                $subCategory->name         = $jsonData['name'];
                $subCategory->image_url    = $uplaod['full_url'];
                $subCategory->image_path   = $uplaod['url'];
                $subCategory->icon_path    = $uplaod['url'];
                $subCategory->status       = 1;
                $subCategory->theme_id     = APP_THEME();
                $subCategory->store_id     = getCurrentStore();
                $subCategory->save();
                return redirect()->back()->with('success', __('Category update successfully.'));
            } else {
                return redirect()->back()->with('error', __('Category Not Found.'));
            }

        }
        else
        {
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
