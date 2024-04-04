<?php

namespace App\Http\Controllers;

use App\Models\MainCategory;
use App\Models\Setting;
use App\Models\Store;
use App\Models\Utility;
use App\Models\WoocommerceConection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Codexshaper\WooCommerce\Facades\Category;
use Xendit\Xendit;

class WoocomCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (auth()->user()->isAbleTo('Manage Woocommerce Category'))
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
                    return $category->parent == 0;
                });
                $store_id = Store::where('id', getCurrentStore())->first();
                $MainCategory = MainCategory::where('theme_id',$store_id->theme_id)->where('store_id',getCurrentStore())->get();
                $upddata = WoocommerceConection::where('theme_id',$store_id->theme_id)->where('store_id',getCurrentStore())->where('module','=','category')->get()->pluck('woocomerce_id')->toArray();
                return view('woocommerce.category', compact('jsonData','upddata','MainCategory'));

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
         
        if (auth()->user()->isAbleTo('Create Woocommerce Category'))
        {
            $theme_name = !empty(env('DATA_INSERT_APP_THEME')) ? env('DATA_INSERT_APP_THEME') : APP_THEME();
            $woocommerce_store_url =Utility::GetValueByName('woocommerce_store_url',$theme_name);
            $woocommerce_consumer_secret =Utility::GetValueByName('woocommerce_consumer_secret',$theme_name);
            $woocommerce_consumer_key =Utility::GetValueByName('woocommerce_consumer_key',$theme_name);

            config(['woocommerce.store_url' => $woocommerce_store_url]);
            config(['woocommerce.consumer_key' => $woocommerce_consumer_key]);
            config(['woocommerce.consumer_secret' => $woocommerce_consumer_secret]);

            $jsonData = Category::find($id);
            
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

            $MainCategory               = new MainCategory();
            $MainCategory->name         = $jsonData['name'];
            $MainCategory->image_url    = $uplaod['full_url'];
            $MainCategory->image_path   = $uplaod['url'];
            $MainCategory->icon_path    = $uplaod['url'];
            $MainCategory->trending     = 0;
            $MainCategory->status       = 1;
            $MainCategory->theme_id     = APP_THEME();
            $MainCategory->store_id     = getCurrentStore();
            $MainCategory->save();

            $Category                   = new WoocommerceConection();
            $Category->store_id         = getCurrentStore();
            $Category->theme_id         = APP_THEME();
            $Category->module           = 'category';
            $Category->woocomerce_id    = $jsonData['id'];
            $Category->original_id      =$MainCategory->id;
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
         
        if (auth()->user()->isAbleTo('Edit Woocommerce Category'))
        {
            $theme_name = !empty(env('DATA_INSERT_APP_THEME')) ? env('DATA_INSERT_APP_THEME') : APP_THEME();
            $woocommerce_store_url =Utility::GetValueByName('woocommerce_store_url',$theme_name);
            $woocommerce_consumer_secret =Utility::GetValueByName('woocommerce_consumer_secret',$theme_name);
            $woocommerce_consumer_key =Utility::GetValueByName('woocommerce_consumer_key',$theme_name);

            config(['woocommerce.store_url' => $woocommerce_store_url]);
            config(['woocommerce.consumer_key' => $woocommerce_consumer_key]);
            config(['woocommerce.consumer_secret' => $woocommerce_consumer_secret]);

            $jsonData = Category::find($id);
            
            $store_id = Store::where('id', getCurrentStore())->first();
            $upddata = WoocommerceConection::where('theme_id',$store_id->theme_id)->where('store_id',getCurrentStore())->where('module','=','category')->where('woocomerce_id' , $id)->first();
            $original_id = $upddata->original_id;
            $mainCategory = MainCategory::find($original_id);

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
                $mainCategory->name         = $jsonData['name'];
                $mainCategory->image_url    = $uplaod['full_url'];
                $mainCategory->image_path   = $uplaod['url'];
                $mainCategory->icon_path    = $uplaod['url'];
                $mainCategory->trending     = 0;
                $mainCategory->status       = 1;
                $mainCategory->theme_id     = APP_THEME();
                $mainCategory->store_id     = getCurrentStore();
                $mainCategory->save();
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
