<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\ProductImage;
use App\Models\Setting;
use App\Models\Store;
use App\Models\Utility;
use App\Models\WoocommerceConection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Codexshaper\WooCommerce\Facades\Product;
use Codexshaper\WooCommerce\Facades\Variation;
use Codexshaper\WooCommerce\Facades\Category;
use App\Models\ProductAttribute;
use App\Models\ProductAttributeOption;
use App\Models\ProductVariant;

class WoocomProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // if(auth()->user()->isAbleTo('Manage Woocommerce Product'))
        // {
            try{

                $store_id = Store::where('id', getCurrentStore())->first();
                $theme_name = !empty(env('DATA_INSERT_APP_THEME')) ? env('DATA_INSERT_APP_THEME') : APP_THEME();
                $woocommerce_store_url =Utility::GetValueByName('woocommerce_store_url',$theme_name);
                $woocommerce_consumer_secret =Utility::GetValueByName('woocommerce_consumer_secret',$theme_name);
                $woocommerce_consumer_key =Utility::GetValueByName('woocommerce_consumer_key',$theme_name);

                config(['woocommerce.store_url' => $woocommerce_store_url]);
                config(['woocommerce.consumer_key' => $woocommerce_consumer_key]);
                config(['woocommerce.consumer_secret' => $woocommerce_consumer_secret]);

                $jsonData = Product::all(['per_page' => 100]);

                $woocommerce_store_url =Utility::GetValueByName('woocommerce_store_url',$theme_name);
                // $response = \Http::get($woocommerce_store_url . '/wp-json/wc/v3/products');
                // $jsonData = $response->json();

                // $path = base_path('themes/' . APP_THEME() . '/theme_json/' . 'product-detail.json');
                $upddata = WoocommerceConection::where('theme_id',$store_id->theme_id)->where('store_id',getCurrentStore())->where('module','=','product')->get()->pluck('woocomerce_id')->toArray();

                return view('woocommerce.product', compact('jsonData','upddata'));
            }

            catch(\Exception $e){
                return redirect()->back()->with('error' , 'Something went wrong.');
            }
        // }
        // else
        // {
        //     return redirect()->back()->with('error', __('Permission denied.'));
        // }
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
        $woocommerce_store_url = Utility::GetValueByName('woocommerce_store_url',$theme_name);
        $response = \Http::get($woocommerce_store_url . '/wp-json/wc/v3/products');
        $jsonData = $response->json();

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        
        // if(auth()->user()->isAbleTo('Create Woocommerce Product'))
        // {
            
            $store_id = Store::where('id', getCurrentStore())->first();
            $theme_name = !empty(env('DATA_INSERT_APP_THEME')) ? env('DATA_INSERT_APP_THEME') : APP_THEME();
            $woocommerce_store_url =Utility::GetValueByName('woocommerce_store_url',$theme_name);
            $woocommerce_consumer_secret =Utility::GetValueByName('woocommerce_consumer_secret',$theme_name);
            $woocommerce_consumer_key =Utility::GetValueByName('woocommerce_consumer_key',$theme_name);

            config(['woocommerce.store_url' => $woocommerce_store_url]);
            config(['woocommerce.consumer_key' => $woocommerce_consumer_key]);
            config(['woocommerce.consumer_secret' => $woocommerce_consumer_secret]);
            $jsonData = Product::find($id);
            
            $variations = [];
            if (isset($jsonData['variations']) && count($jsonData['variations']) > 0) {
                
                foreach ($jsonData['variations'] as $variationId) {
                   $variations[] = Variation::find($jsonData['id'],$variationId);
                }
            }
           // dd($variations);
            $store = Store::where('id', getCurrentStore())->first();
           
            $woocommerceConectionQuery = WoocommerceConection::query();
            if (isset($jsonData['categories']) && count($jsonData['categories']) > 0) {
                $parent = $child= false;
                $categoryId = $subCatgoryId = 0;
                foreach ($jsonData['categories'] as $category) {
                   $category = Category::find($category->id);
                    if ($category['parent'] == 0) {
                        $exitsCategory = (clone $woocommerceConectionQuery)->where('theme_id', $store->theme_id)->where('store_id',getCurrentStore())->where('module','=','category')->where('woocomerce_id' , $category['id'])->first();
                        if($exitsCategory) {
                            $parent = true;
                            $categoryId = $exitsCategory->original_id;
                        }                            
                    } elseif ($category['parent'] != 0) {
                        $exitsSubCategory = (clone $woocommerceConectionQuery)->where('theme_id', $store->theme_id)->where('store_id',getCurrentStore())->where('module','=','sub_category')->where('woocomerce_id' , $category['id'])->first();
                        if($exitsSubCategory) {
                            $child = true;
                            $subCatgoryId = $exitsSubCategory->original_id;
                        }
                    }
                }
                if(!$parent && !$child) {
                    return redirect()->back()->with('error', __('Add Woocommerce Product Category and Sub Category.'));
                }
            } else {
                return redirect()->back()->with('error', __('Add Woocommerce Product Category and Sub Category.'));
            }

            // Create Product Attribute
            $attribute = $this->createProductAttribute($jsonData['attributes'], $jsonData['default_attributes']);
            
            if(!empty($jsonData['regular_price']) && !empty($jsonData['sale_price']) ){
                $discount_amount =$jsonData['regular_price'] - $jsonData['sale_price'];
            }
            else{
                $discount_amount = 0;
            }

            if(!empty($jsonData['images'][0]->src)) {
                $url = $jsonData['images'][0]->src;

                $file_type = config('files_types');

                foreach($file_type as $f){
                    $name = basename($url, ".".$f);
                }
                $file2 = rand(10,100).'_'.time() . "_" . $name;
                $path = 'themes/'.APP_THEME().'/uploads/';
                $uplaod = Utility::upload_woo_file($url,$file2,$path);
            }
            else{

                $url    = asset(Storage::url('uploads/woocommerce.png'));
                $name   = 'woocommerce.png';
                $file2  = rand(10,100).'_'.time() . "_" . $name;
                $path   = 'themes/'.APP_THEME().'/uploads/';
                $uplaod = Utility::upload_woo_file($url,$file2,$path);

            }

            if (!empty($jsonData)) {
                $product                        = new \App\Models\Product();
                $product->name                  = $jsonData['name'];
                $product->slug                  = $jsonData['slug'];
                $product->description           = strip_tags($jsonData['description']);
                $product->specification           = $jsonData['short_description'];
                
                $product->cover_image_path      = $uplaod['url'];
                $product->cover_image_url       = $uplaod['full_url'];
                $product->maincategory_id       = $categoryId;
                $product->subcategory_id        = $subCatgoryId;
                $product->variant_product       = (count($variations) > 0) ? 1 : 0;
                $product->product_stock         = !empty($jsonData['stock_quantity']) ? $jsonData['stock_quantity'] : 0;
                $product->slug                  = str_replace(' ','_', strtolower($jsonData['name']));
                $product->price                 = $jsonData['price'];
                $product->attribute_id          = $attribute['id'] ?? null;
                
                $product->theme_id              = APP_THEME();
                $product->store_id              = getCurrentStore();
                $product->created_by            = auth()->user()->id;
                $product->save();

                $products                    = new WoocommerceConection();
                $products->store_id          = getCurrentStore();
                $products->theme_id          = APP_THEME();
                $products->module            = 'product';
                $products->woocomerce_id     = $jsonData['id'];
                $products->original_id       = $product->id;
                $products->save();

                if(empty($jsonData['images'][1])){
                    $url  = asset(Storage::url('uploads/woocommerce.png'));
                    $name = 'woocommerce.png';
                    $file2 = rand(10,100).'_'.time() . "_" . $name;
                    $path = 'themes/'.APP_THEME().'/uploads/';
                    $ulpaod =Utility::upload_woo_file($url,$file2,$path);

                    $ProductImage = new ProductImage();
                    $ProductImage->product_id = $product->id;
                   
                    $ProductImage->image_path = $ulpaod['url'];
                    $ProductImage->image_url  = $ulpaod['full_url'];
                    $ProductImage->theme_id   = $store_id->theme_id;
                    $ProductImage->store_id   = getCurrentStore();
                    $ProductImage->save();
                }else{
                    for ($i = 1; $i < count($jsonData['images']); $i++) {
                        $image = $jsonData['images'][$i];
                        $id = $image->id;
                        $dateCreated = $image->date_created;
                        $src = $image->src;

                        $url = $src;

                        $file_type = config('files_types');

                        foreach($file_type as $f){
                            $name = basename($url, ".".$f);
                        }
                        $file2 = rand(10,100).'_'.time() . "_" . $name;
                        $path = 'themes/'.APP_THEME().'/uploads/';
                        $subimg =Utility::upload_woo_file($url,$file2,$path);

                        $ProductImage = new ProductImage();
                        $ProductImage->product_id = $product->id;
                       
                        $ProductImage->image_path = $subimg['url'];
                        $ProductImage->image_url  = $subimg['full_url'];
                        $ProductImage->theme_id   = $store_id->theme_id;
                        $ProductImage->store_id   = getCurrentStore();
                        $ProductImage->save();
                    }

                }

                // Create Product Variations
                $this->createProductVariant($product, $variations, $attribute['variant'] ?? null);
                return redirect()->back()->with('success', __('Product successfully Add.'));

            } else {
                return redirect()->back()->with('error', __('Product Not Found.'));
            }

        // }
        // else
        // {
        //     return redirect()->back()->with('error', __('Permission denied.'));
        // }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
        // if(auth()->user()->isAbleTo('Edit Woocommerce Product'))
        // {
            $store_id = Store::where('id', getCurrentStore())->first();
            $theme_name = !empty(env('DATA_INSERT_APP_THEME')) ? env('DATA_INSERT_APP_THEME') : APP_THEME();
            $woocommerce_store_url = Utility::GetValueByName('woocommerce_store_url',$theme_name);
            $woocommerce_consumer_secret = Utility::GetValueByName('woocommerce_consumer_secret',$theme_name);
            $woocommerce_consumer_key = Utility::GetValueByName('woocommerce_consumer_key',$theme_name);

            config(['woocommerce.store_url' => $woocommerce_store_url]);
            config(['woocommerce.consumer_key' => $woocommerce_consumer_key]);
            config(['woocommerce.consumer_secret' => $woocommerce_consumer_secret]);
            $jsonData = Product::find($id);
            
            $variations = [];
            if (isset($jsonData['variations']) && count($jsonData['variations']) > 0) {
                
                foreach ($jsonData['variations'] as $variationId) {
                   $variations[] = Variation::find($jsonData['id'],$variationId);
                }
            }

            $store = Store::where('id', getCurrentStore())->first();
           
            $woocommerceConectionQuery = WoocommerceConection::query();
            if (isset($jsonData['categories']) && count($jsonData['categories']) > 0) {
                $parent = $child= false;
                $categoryId = $subCatgoryId = 0;
                foreach ($jsonData['categories'] as $category) {
                   $category = Category::find($category->id);
                    if ($category['parent'] == 0) {
                        $exitsCategory = (clone $woocommerceConectionQuery)->where('theme_id', $store->theme_id)->where('store_id',getCurrentStore())->where('module','=','category')->where('woocomerce_id' , $category['id'])->first();
                        if($exitsCategory) {
                            $parent = true;
                            $categoryId = $exitsCategory->original_id;
                        }                            
                    } elseif ($category['parent'] != 0) {
                        $exitsSubCategory = (clone $woocommerceConectionQuery)->where('theme_id', $store->theme_id)->where('store_id',getCurrentStore())->where('module','=','sub_category')->where('woocomerce_id' , $category['id'])->first();
                        if($exitsSubCategory) {
                            $child = true;
                            $subCatgoryId = $exitsSubCategory->original_id;
                        }
                    }
                }
                if(!$parent && !$child) {
                    return redirect()->back()->with('error', __('Add Woocommerce Product Category and Sub Category.'));
                }
            } else {
                return redirect()->back()->with('error', __('Add Woocommerce Product Category and Sub Category.'));
            }

            // Create Product Attribute
            $attribute = $this->createProductAttribute($jsonData['attributes'], $jsonData['default_attributes']);
            
            if(!empty($jsonData['images'][0]->src)) {
                $url = $jsonData['images'][0]->src;
                $file_type = config('files_types');

                foreach($file_type as $f){
                    $name = basename($url, ".".$f);
                }
                $file2 = rand(10,100).'_'.time() . "_" . $name;
                $path = 'themes/'.APP_THEME().'/uploads/';
                $uplaod =Utility::upload_woo_file($url,$file2,$path);


            }
            $woocommerceProduct = (clone $woocommerceConectionQuery)->where('module', 'product')->where('woocomerce_id', $jsonData['id'])->first();
            $original_id = $woocommerceProduct->original_id;
            $product = \App\Models\Product::find($original_id);
            $discount_amount = (!empty($jsonData['regular_price']) ? $jsonData['regular_price'] : 0) - (!empty($jsonData['sale_price']) ? $jsonData['sale_price'] : 0);

           
            if (!empty($jsonData)) {
                $product->name                  = $jsonData['name'];
                $product->slug                  = $jsonData['slug'];
                $product->description           = strip_tags($jsonData['description']);
                $product->specification           = $jsonData['short_description'];
                
                $product->cover_image_path      = $uplaod['url'];
                $product->cover_image_url       = $uplaod['full_url'];
                $product->maincategory_id       = $categoryId;
                $product->subcategory_id        = $subCatgoryId;
                $product->variant_product       = (count($variations) > 0) ? 1 : 0;
                $product->product_stock         = !empty($jsonData['stock_quantity']) ? $jsonData['stock_quantity'] : 0;
                $product->slug                  = str_replace(' ','_', strtolower($jsonData['name']));
                $product->price                 = $jsonData['price'];
                $product->attribute_id          = $attribute['id'] ?? null;
                
                $product->theme_id              = APP_THEME();
                $product->store_id              = getCurrentStore();
                $product->created_by            = auth()->user()->id;

                $product->save();

                // Create Product Variations
                $this->createProductVariant($product, $variations, $attribute['variant'] ?? null);
                return redirect()->back()->with('success', __('Product successfully Updated.'));
            }
            else{
                return redirect()->back()->with('error', __('Product Not Found.'));

            }

        // }
        // else
        // {
        //     return redirect()->back()->with('error', __('Permission denied.'));
        // }
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

    private function createProductVariant($product, $variants, $variant_name) {
        $default_variant_id = 0;
        $is_in_stock = false;
        $variantQuery = ProductVariant::query();
        foreach ($variants as $item) {
            $product_stock = [];
                $existVariant = (clone $variantQuery)->where('product_id', $product->id)->where('variant', $variant_name)->first();
                
                $product_stock['product_id'] = $product->id;
               
                $product_stock['variant'] = $variant_name;
                $product_stock['sku'] = $item['sku'];
                $product_stock['downloadable_product'] = $item['downloadable'] ?? null;
                $product_stock['variation_price'] = $item['regular_price'] ?? null;
                $product_stock['weight'] = $item['weight'] ?? null;
                if ($item['stock_status'] == 'instock' && $item['stock_status'] == 'in_stock') {
                    $status  = 'in_stock';
                }
                if ($item['stock_status'] == 'outofstock' || $item['stock_status'] == 'out_of_stock') {
                    $status  = 'out_of_stock';
                }
                $product_stock['stock_status'] = $status ?? null;
                $product_stock['price'] = $item['price'] ?? 0;
                $product_stock['low_stock_threshold'] = $item['low_stock_amount'] ?? null;
                $product_stock['description'] = $item['description'] ?? null;
                $product_stock['stock'] = $item['stock_quantity'] ?? 0;
                
                $product_stock['theme_id'] = APP_THEME();
                $product_stock['store_id'] = getCurrentStore();
               
                if (!$existVariant) {
                    $existVariant = (clone $variantQuery)->create($product_stock);
                } else {
                    $existVariant->update($product_stock);
                }
             
               
                if ($existVariant->stock_status == 'in_stock' || $existVariant->stock_status == 'instock') {
                    $is_in_stock = true;
                }
        }
        if (!$is_in_stock) {
            $product->stock_status = 'out_of_stock';
        } else {
            $product->stock_status = 'in_stock';
        }
        $product->save();
    }

    private function createProductAttribute($attributes, $defaultAttribute) {
        $attributeQuery  = ProductAttribute::query();
        $optionQuery = ProductAttributeOption::query();
        foreach ($attributes as $attribute) {
            $existAttribute = (clone $attributeQuery)->where('name', $attribute->name)->where('theme_id', APP_THEME())->where('store_id', getCurrentStore())->first();
            if (!$existAttribute) {
                $newAttribute = (clone $attributeQuery)->create([
                    'name' => $attribute->name,
                    'theme_id' => APP_THEME(),
                    'store_id' => getCurrentStore(),
                ]);
                if (count($attribute->options) > 0) {
                    foreach ($attribute->options as $option) {
                        (clone $optionQuery)->create([
                            'attribute_id' => $newAttribute->id,
                            'terms' => $option,
                            'order' => 0,
                            'theme_id' => APP_THEME(),
                            'store_id' => getCurrentStore(),
                        ]);
                        
                    }
                }
            } else {
                if (count($attribute->options) > 0) {
                    foreach ($attribute->options as $key => $option) {
                        $existOption= (clone $optionQuery)->where('attribute_id', $existAttribute->id)->where('terms', $option)->where('theme_id', APP_THEME())->where('store_id', getCurrentStore())->first();
                        if (!$existOption) {
                            (clone $optionQuery)->create([
                                'attribute_id' => $existAttribute->id,
                                'terms' => $option,
                                'order' => 0,
                                'theme_id' => APP_THEME(),
                                'store_id' => getCurrentStore(),
                            ]);
                        }                        
                    }
                }
            }
        }

        $return = [];
        foreach ($defaultAttribute as $default) {
            $existAttribute = (clone $attributeQuery)->where('name', $default->name)->where('theme_id', APP_THEME())->where('store_id', getCurrentStore())->first();
            if ($existAttribute) {
                $existOption= (clone $optionQuery)->where('attribute_id', $existAttribute->id)->where('terms', $default->option)->where('theme_id', APP_THEME())->where('store_id', getCurrentStore())->first();
                $return['id'] = $existAttribute->id;
                $return['variant'] = $default->option;
            }
        }
        return $return;
    }
}
