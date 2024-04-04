<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\{MainCategory, SubCategory};
use App\Models\Store;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductAttributeOption;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\ShopifyConection;
use App\Models\Customer;
use App\Models\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ShopifyProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (auth()->user()->isAbleTo('Manage Shopify Product')) {
            try {
                $theme_name = !empty(env('DATA_INSERT_APP_THEME')) ? env('DATA_INSERT_APP_THEME') : APP_THEME();
                $shopify_store_url = \App\Models\Utility::GetValueByName('shopify_store_url', $theme_name);
                $shopify_access_token = \App\Models\Utility::GetValueByName('shopify_access_token', $theme_name);
                // dd($shopify_store_url,$shopify_access_token);

                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => "https://$shopify_store_url.myshopify.com/admin/api/2023-07/products.json",
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
                    $product = json_decode($response, true);

                    if (isset($product['errors'])) {

                        $errorMessage = $product['errors'];
                        return redirect()->back()->with('error', $errorMessage);
                    } else {
                        $upddata = ShopifyConection::where('theme_id', $theme_name)->where('store_id', getCurrentStore())->where('module', '=', 'product')->pluck('shopify_id')->toArray();

                        return view('shopify.product', compact('product', 'upddata'));
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
        
        if (auth()->user()->isAbleTo('Create Shopify Product')) 
        {
            try {
                $theme_name = !empty(env('DATA_INSERT_APP_THEME')) ? env('DATA_INSERT_APP_THEME') : APP_THEME();
                $store_id = Store::where('id', getCurrentStore())->first();
                $shopify_store_url = \App\Models\Utility::GetValueByName('shopify_store_url', $theme_name);
                $shopify_access_token = \App\Models\Utility::GetValueByName('shopify_access_token', $theme_name);

                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => "https://$shopify_store_url.myshopify.com/admin/api/2023-07/products.json?ids=$id",
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
                    $themeId = APP_THEME(); 
                    $storeId = getCurrentStore(); 

                    $maincategory = MainCategory::where('theme_id',$themeId)->where('store_id', $storeId)->first();
                    $subcategory = SubCategory::where('maincategory_id', $maincategory->id)->where('theme_id',$themeId)->where('store_id', $storeId)->first();
                    
                    $products = json_decode($response, true);

                    $upddata = ShopifyConection::where('theme_id', $theme_name)->where('store_id', $storeId)->where('module', '=', 'category')->where('shopify_id', $products['products'][0]['product_type'])->pluck('shopify_id')->first();
                    // if(empty($upddata)){
                    //    return redirect()->back()->with('error', __('Add Shopify Product Category'));
                    // }
                    if (isset($products['errors'])) {

                        $errorMessage = $products['errors'];
                        return redirect()->back()->with('error', $errorMessage);
                    } else {

                        if (isset($products) && !empty($products)) {
                                                 

                            if (!empty($products['products'][0]['image']['src'])) {
                                $ImageUrl = $products['products'][0]['image']['src'];
                                $url =  strtok($ImageUrl, '?');
                                $file_type = config('files_types');

                                foreach ($file_type as $f) {
                                    $name = basename($url, "." . $f);
                                }
                                $file2 = rand(10, 100) . '_' . time() . "_" . $name;
                                $path = 'themes/' . $themeId . '/uploads/';
                                $uplaod = Utility::upload_woo_file($url, $file2, $path);
                            } else {

                                $url    = asset(Storage::url('uploads/woocommerce.png'));
                                $name   = 'woocommerce.png';
                                $file2  = rand(10, 100) . '_' . time() . "_" . $name;
                                $path   = 'themes/' . $themeId . '/uploads/';
                                $uplaod = Utility::upload_woo_file($url, $file2, $path);
                            }
                            $product                          = new Product();
                            $product->name                    = $products['products'][0]['title'];
                            $product->description             = strip_tags($products['products'][0]['body_html']);
                            $product->cover_image_path      = $uplaod['url'];
                            $product->cover_image_url       = $uplaod['full_url'];
                            if ($products['products'][0]['variants'][0]['title'] == 'Default Title') {
                                $product->product_weight          = $products['products'][0]['variants'][0]['weight'];
                            }
                            $product->maincategory_id             = $maincategory->id;
                            $product->subcategory_id              = $subcategory->id;

                            // $product->discount_type = 'flat';
                            // $discount_amount = $products['products'][0]['variants'][0]['compare_at_price'] - $products['products'][0]['variants'][0]['price'];
                            // $product->discount_amount = $discount_amount;
                            if ($products['products'][0]['variants'][0]['title'] == 'Default Title') {
                                $product->variant_product = 0;
                            } else {
                                $product->variant_product = 1;
                               // $product->variant_id =  json_encode([]);
                            }
                            $product->slug    = str_replace(' ', '_', strtolower($products['products'][0]['title']));
                            $product->status = 1;
                            if ($products['products'][0]['variants'][0]['title'] == 'Default Title') {
                                $product->track_stock = 1;
                                $product->stock_order_status = 'not_allow';
                                $product->price = $products['products'][0]['variants'][0]['price'];
                                $product->product_stock = $products['products'][0]['variants'][0]['inventory_quantity'];
                              //  $product->variant_id = NULL;
                               // $product->variant_attribute = NULL;
                            }
                            $product->track_stock = 1;
                            $product->theme_id              = $themeId;
                            $product->store_id              = $storeId;
                            $product->created_by            = auth()->user()->id;
                            $attribute_id = [];


                            $option_attribute_value = [];
                            foreach ($products['products'][0]['options'] as $option) {
                                $option_attribute_value[] = $option['values'];
                            }
                            $mergedArray = [];
                            foreach ($option_attribute_value as $array) {
                                $mergedArray = array_merge($mergedArray, $array);
                            }
                            $options_value_mergedArray = array_map(function ($element) {
                                return str_replace(' ', '', $element);
                            }, $mergedArray);


                            if ($products['products'][0]['variants'][0]['title'] != 'Default Title') {
                                foreach ($products['products'][0]['options'] as $option) {
                                    $product_Attrybute = ProductAttribute::where('name', $option['name'])->where('theme_id', $theme_name)->where('store_id', $storeId)->first();
                                    $slug = User::slugs($option['name']);

                                    if (!empty($product_Attrybute->name) != $option['name']) {
                                        $attribute                      = new ProductAttribute();

                                        $attribute->name                = $option['name'];
                                        $attribute->slug                = $slug;
                                        $attribute->theme_id            = $themeId;
                                        $attribute->store_id            = $storeId;
                                        $attribute->save();
                                    }

                                    foreach ($option['values'] as $ProductAttribute) {
                                        $title = str_replace(' ', '', $ProductAttribute);
                                        $product_AttributeOption = ProductAttributeOption::where('terms', $title)->where('theme_id', $theme_name)->where('store_id', $storeId)->first();
                                        if (!empty($product_AttributeOption->terms) != $title) {
                                            $attribute_option                      = new ProductAttributeOption();
                                            $attribute_option->attribute_id        = !empty($attribute->id) ? $attribute->id : $product_Attrybute->id;
                                            $attribute_option->terms               = $title;
                                            $attribute_option->theme_id            = $themeId;
                                            $attribute_option->store_id            = $storeId;
                                            $attribute_option->save();
                                        }
                                    }
                                    if (!empty($attribute)) {
                                        $attribute_id[] = $attribute->id;
                                    } else {
                                        $attribute_id[] = $product_Attrybute->id;
                                    }
                                }

                                $product->attribute_id = json_encode($attribute_id);
                                $attribute_options = [];
                                $options_value = array_map(function ($element) {
                                    return str_replace(' ', '', $element);
                                }, $option['values']);
                                $attribute_option_terms = ProductAttributeOption::whereIn('attribute_id', $attribute_id)->whereIn('terms', $options_value_mergedArray)->pluck('terms')->toArray();
                                // dd($attribute_option_terms);
                                foreach ($attribute_id as $key => $no) {


                                    $conditionMet = false;

                                    foreach ($options_value_mergedArray as $ProductAttribute) {
                                        if (in_array($ProductAttribute, $attribute_option_terms)) {
                                            $conditionMet = true;
                                            break;
                                        }
                                    }
                                    if ($conditionMet) {
                                        $attribute_option_id = ProductAttributeOption::where('attribute_id', $no)->whereIn('terms', $options_value_mergedArray)->pluck('id')->toArray();
                                    } else {
                                        $attribute_option_id = ProductAttributeOption::where('attribute_id', $no)->pluck('id')->toArray();
                                    }

                                    $enable_option = 1;
                                    $variation_option = 1;
                                    $item['attribute_id'] = $no;

                                    $item['values'] = explode(',', implode('|', $attribute_option_id));

                                    $item['visible_attribute_' . $no] = $enable_option;
                                    $item['for_variation_' . $no] = $variation_option;
                                    array_push($attribute_options, $item);
                                }
                                $attribute_options = json_encode($attribute_options);
                                $product->product_attribute = $attribute_options;
                            }


                            $product->save();

                            if ($products['products'][0]['variants'][0]['title'] != 'Default Title') {

                                foreach ($products['products'][0]['variants'] as $variants) {
                                    // $title_spase = str_replace(' ', '', $variants['title']);
                                    $title_spase = str_replace(' / ', '-', $variants['title']);
                                    $title = str_replace(' ', '', $title_spase);

                                    $sku = str_replace(' ', '_', $product->name . '-' . $title);
                                    $productVariant                 = new ProductVariant();
                                    $productVariant->product_id     = $product->id;
                                    $productVariant->variant        = $title;
                                    $productVariant->sku            = $sku;
                                    $productVariant->stock          = $variants['inventory_quantity'];
                                    $productVariant->price          = $variants['price'];
                                    $productVariant->variation_price = $variants['price'];
                                    $productVariant->stock_order_status = 'not_allow';
                                    $productVariant->variation_option = 'manage_stock';
                                    $productVariant->variation_option = 'manage_stock';
                                    $productVariant->theme_id            = $themeId;
                                    $productVariant->store_id            = $storeId;
                                    $productVariant->save();
                                }
                            }

                            if (empty($products['products'][0]['images'][0])) {
                                $url  = asset(Storage::url('uploads/woocommerce.png'));
                                $name = 'woocommerce.png';
                                $file2 = rand(10, 100) . '_' . time() . "_" . $name;
                                $path = 'themes/' . $themeId . '/uploads/';
                                $ulpaod = Utility::upload_woo_file($url, $file2, $path);

                                $ProductImage = new ProductImage();
                                $ProductImage->product_id = $product->id;

                                $ProductImage->image_path = $ulpaod['url'];
                                $ProductImage->image_url  = $ulpaod['full_url'];
                                $ProductImage->theme_id   = $store_id->theme_id;
                                $ProductImage->store_id   = $storeId;
                                $ProductImage->save();
                            } else {
                                for ($i = 1; $i < count($products['products'][0]['images']); $i++) {
                                    $image = $products['products'][0]['images'][$i];
                                    $id = $image['id'];
                                    $dateCreated = $image['created_at'];
                                    $src = $image['src'];

                                    $ImageUrl = $src;
                                    $url =  strtok($ImageUrl, '?');

                                    $file_type = config('files_types');

                                    foreach ($file_type as $f) {
                                        $name = basename($url, "." . $f);
                                    }
                                    $file2 = rand(10, 100) . '_' . time() . "_" . $name;
                                    $path = 'themes/' . $themeId . '/uploads/';
                                    $subimg = Utility::upload_woo_file($url, $file2, $path);

                                    $ProductImage = new ProductImage();
                                    $ProductImage->product_id = $product->id;

                                    $ProductImage->image_path = $subimg['url'];
                                    $ProductImage->image_url  = $subimg['full_url'];
                                    $ProductImage->theme_id   = $store_id->theme_id;
                                    $ProductImage->store_id   = $storeId;
                                    $ProductImage->save();
                                }
                            }

                            $products_connection                    = new ShopifyConection();
                            $products_connection->store_id          = $storeId;
                            $products_connection->theme_id          = $themeId;
                            $products_connection->module            = 'product';
                            $products_connection->shopify_id        = $products['products'][0]['id'];
                            $products_connection->original_id       = $product->id;
                            $products_connection->save();





                            return redirect()->back()->with('success', 'Product successfully add.');
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
        
        if (auth()->user()->isAbleTo('Edit Shopify Product')) {
            try {
                $theme_name = !empty(env('DATA_INSERT_APP_THEME')) ? env('DATA_INSERT_APP_THEME') : APP_THEME();
                $store_id = Store::where('id', getCurrentStore())->first();
                $shopify_store_url = \App\Models\Utility::GetValueByName('shopify_store_url', $theme_name);
                $shopify_access_token = \App\Models\Utility::GetValueByName('shopify_access_token', $theme_name);

                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => "https://$shopify_store_url.myshopify.com/admin/api/2023-07/products.json?ids=$id",
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
                        $themeId = APP_THEME();
                        $storeId = getCurrentStore();
                        $mainCategory = MainCategory::where('theme_id',$themeId)->where('store_id', $storeId)->first();
                        $subCategory = SubCategory::where('maincategory_id', $mainCategory->id)->where('theme_id',$themeId)->where('store_id', $storeId)->first();
                        if (isset($products) && !empty($products)) {

                            if (!empty($products['products'][0]['image']['src'])) {
                                $ImageUrl = $products['products'][0]['image']['src'];
                                $url =  strtok($ImageUrl, '?');
                                $file_type = config('files_types');

                                foreach ($file_type as $f) {
                                    $name = basename($url, "." . $f);
                                }
                                $file2 = rand(10, 100) . '_' . time() . "_" . $name;
                                $path = 'themes/' . $themeId . '/uploads/';
                                $uplaod = Utility::upload_woo_file($url, $file2, $path);
                            } else {

                                $url    = asset(Storage::url('uploads/woocommerce.png'));
                                $name   = 'woocommerce.png';
                                $file2  = rand(10, 100) . '_' . time() . "_" . $name;
                                $path   = 'themes/' . $themeId . '/uploads/';
                                $uplaod = Utility::upload_woo_file($url, $file2, $path);
                            }
                            $upddata = ShopifyConection::where('theme_id', $store_id->theme_id)->where('store_id', $storeId)->where('module', '=', 'product')->where('shopify_id', $id)->first();
                            $original_id = $upddata->original_id;

                            $product                          = Product::find($original_id);
                            $product->name                    = $products['products'][0]['title'];
                            $product->description             = strip_tags($products['products'][0]['body_html']);
                            $product->cover_image_path        = $uplaod['url'];
                            $product->cover_image_url         = $uplaod['full_url'];
                            if ($products['products'][0]['variants'][0]['title'] == 'Default Title') {
                                $product->product_weight          = $products['products'][0]['variants'][0]['weight'];
                            }
                            $product->maincategory_id             = $mainCategory->id;
                            $product->subcategory_id             = $subCategory->id;

                            // $product->discount_type = 'flat';
                            // $discount_amount = $products['products'][0]['variants'][0]['compare_at_price'] - $products['products'][0]['variants'][0]['price'];
                            // $product->discount_amount = $discount_amount;
                            if ($products['products'][0]['variants'][0]['title'] == 'Default Title') {
                                $product->variant_product = 0;
                            } else {
                                $product->variant_product = 1;
                                //$product->variant_id =  json_encode([]);
                            }
                            $product->slug    = str_replace(' ', '_', strtolower($products['products'][0]['title']));
                            $product->status = 1;
                            if ($products['products'][0]['variants'][0]['title'] == 'Default Title') {
                                $product->track_stock = 1;
                                $product->stock_order_status = 'not_allow';
                                $product->price = $products['products'][0]['variants'][0]['price'];
                                $product->product_stock = $products['products'][0]['variants'][0]['inventory_quantity'];
                                // $product->variant_id = NULL;
                                // $product->variant_attribute = NULL;
                            }
                            $product->track_stock = 1;
                            $attribute_id = [];


                            $option_attribute_value = [];
                            foreach ($products['products'][0]['options'] as $option) {
                                $option_attribute_value[] = $option['values'];
                            }
                            $mergedArray = [];
                            foreach ($option_attribute_value as $array) {
                                $mergedArray = array_merge($mergedArray, $array);
                            }
                            $options_value_mergedArray = array_map(function ($element) {
                                return str_replace(' ', '', $element);
                            }, $mergedArray);




                            if ($products['products'][0]['variants'][0]['title'] != 'Default Title') {
                                foreach ($products['products'][0]['options'] as $option) {
                                    $product_Attrybute = ProductAttribute::where('name', $option['name'])->where('theme_id', $theme_name)->where('store_id', $storeId)->first();
                                    $slug = User::slugs($option['name']);

                                    if (!empty($product_Attrybute->name) != $option['name']) {
                                        $attribute                      = new ProductAttribute();

                                        $attribute->name                = $option['name'];
                                        $attribute->slug                = $slug;
                                        $attribute->theme_id            = $themeId;
                                        $attribute->store_id            = $storeId;
                                        $attribute->save();
                                    }

                                    foreach ($option['values'] as $ProductAttribute) {
                                        $title = str_replace(' ', '', $ProductAttribute);
                                        $product_AttributeOption = ProductAttributeOption::where('terms', $title)->where('theme_id', $theme_name)->where('store_id', $storeId)->first();
                                        if (!empty($product_AttributeOption->terms) != $title) {
                                            $attribute_option                      = new ProductAttributeOption();
                                            $attribute_option->attribute_id        = !empty($attribute->id) ? $attribute->id : $product_Attrybute->id;
                                            $attribute_option->terms               = $title;
                                            $attribute_option->theme_id            = $themeId;
                                            $attribute_option->store_id            = $storeId;
                                            $attribute_option->save();
                                        }
                                    }
                                    if (!empty($attribute)) {
                                        $attribute_id[] = $attribute->id;
                                    } else {
                                        $attribute_id[] = $product_Attrybute->id;
                                    }
                                }

                                $product->attribute_id = json_encode($attribute_id);
                                $attribute_options = [];
                                $options_value = array_map(function ($element) {
                                    return str_replace(' ', '', $element);
                                }, $option['values']);
                                $attribute_option_terms = ProductAttributeOption::whereIn('attribute_id', $attribute_id)->whereIn('terms', $options_value_mergedArray)->pluck('terms')->toArray();
                                foreach ($attribute_id as $key => $no) {


                                    $conditionMet = false;

                                    foreach ($options_value_mergedArray as $ProductAttribute) {
                                        if (in_array($ProductAttribute, $attribute_option_terms)) {
                                            $conditionMet = true;
                                            break;
                                        }
                                    }
                                    if ($conditionMet) {
                                        $attribute_option_id = ProductAttributeOption::where('attribute_id', $no)->whereIn('terms', $options_value_mergedArray)->pluck('id')->toArray();
                                    } else {
                                        $attribute_option_id = ProductAttributeOption::where('attribute_id', $no)->pluck('id')->toArray();
                                    }

                                    $enable_option = 1;
                                    $variation_option = 1;
                                    $item['attribute_id'] = $no;

                                    $item['values'] = explode(',', implode('|', $attribute_option_id));

                                    $item['visible_attribute_' . $no] = $enable_option;
                                    $item['for_variation_' . $no] = $variation_option;
                                    array_push($attribute_options, $item);
                                }
                                $attribute_options = json_encode($attribute_options);
                                $product->product_attribute = $attribute_options;
                            }


                            $product->save();

                            if ($products['products'][0]['variants'][0]['title'] != 'Default Title') {
                                foreach ($products['products'][0]['variants'] as $variants) {
                                    $productVariant = ProductVariant::where('product_id', $product->id)->get();
                                    $title_spase = str_replace(' / ', '-', $variants['title']);
                                    $title = str_replace(' ', '', $title_spase);

                                    $sku = str_replace(' ', '_', $product->name . '-' . $title);
                                    foreach ($productVariant as $stock) {
                                        if ($stock['variant'] != $title) {
                                            $stock->delete();
                                        }
                                    }
                                }
                                foreach ($products['products'][0]['variants'] as $variants) {
                                    $title_spase = str_replace(' / ', '-', $variants['title']);
                                    $title = str_replace(' ', '', $title_spase);
                                    $sku = str_replace(' ', '_', $product->name . '-' . $title);
                                    $productVariant = ProductVariant::where('product_id', $product->id)->get();


                                    $productVariant = ProductVariant::where('product_id', $product->id)->where('variant', $title)->first();

                                    if ($productVariant != null) {
                                        $productVariant->variant        = $title;
                                        $productVariant->sku            = $sku;
                                        $productVariant->stock          = $variants['inventory_quantity'];
                                        $productVariant->price          = $variants['price'];
                                        $productVariant->variation_price = $variants['price'];
                                    }
                                    if ($productVariant == null) {
                                        $productVariant = new ProductVariant;
                                        $productVariant->product_id = $product->id;
                                        $productVariant->product_id     = $product->id;
                                        $productVariant->variant        = $title;
                                        $productVariant->sku            = $sku;
                                        $productVariant->stock          = $variants['inventory_quantity'];
                                        $productVariant->price          = $variants['price'];
                                        $productVariant->variation_price = $variants['price'];
                                        $productVariant->stock_order_status = 'not_allow';
                                        $productVariant->variation_option = 'manage_stock';
                                        $productVariant->variation_option = 'manage_stock';
                                        $productVariant->theme_id            = $themeId;
                                        $productVariant->store_id            = $storeId;
                                        $productVariant->save();
                                    }
                                }
                            }
                            $ProductImage = ProductImage::where('product_id', $product->id)->where('theme_id', $theme_name)->where('store_id', $storeId)->first();
                            if (empty($products['products'][0]['images'][0])) {
                                $url  = asset(Storage::url('uploads/woocommerce.png'));
                                $name = 'woocommerce.png';
                                $file2 = rand(10, 100) . '_' . time() . "_" . $name;
                                $path = 'themes/' . $themeId . '/uploads/';
                                $ulpaod = Utility::upload_woo_file($url, $file2, $path);

                                $ProductImage->product_id = $product->id;

                                $ProductImage->image_path = $ulpaod['url'];
                                $ProductImage->image_url  = $ulpaod['full_url'];
                                $ProductImage->theme_id   = $store_id->theme_id;
                                $ProductImage->store_id   = $storeId;
                                $ProductImage->save();
                            } else {
                                for ($i = 1; $i < count($products['products'][0]['images']); $i++) {
                                    $image = $products['products'][0]['images'][$i];
                                    $id = $image['id'];
                                    $dateCreated = $image['created_at'];
                                    $src = $image['src'];

                                    $ImageUrl = $src;
                                    $url =  strtok($ImageUrl, '?');

                                    $file_type = config('files_types');

                                    foreach ($file_type as $f) {
                                        $name = basename($url, "." . $f);
                                    }
                                    $file2 = rand(10, 100) . '_' . time() . "_" . $name;
                                    $path = 'themes/' . $themeId . '/uploads/';
                                    $subimg = Utility::upload_woo_file($url, $file2, $path);

                                    $ProductImage->product_id = $product->id;

                                    $ProductImage->image_path = $subimg['url'];
                                    $ProductImage->image_url  = $subimg['full_url'];
                                    $ProductImage->theme_id   = $store_id->theme_id;
                                    $ProductImage->store_id   = $storeId;
                                    $ProductImage->save();
                                }
                            }

                            return redirect()->back()->with('success', 'Product successfully update.');
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
