<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\MainCategory;
use App\Models\SubCategory;
use App\Models\Shipping;
use App\Models\Tax;
use App\Models\Tag;
use App\Models\Setting;
use App\Models\User;
use App\Models\Plan;
use App\Models\Utility;
use App\Models\Store;
use App\Models\ProductAttributeOption;
use App\Models\ProductAttribute;
use App\Models\ProductVariant;
use App\Models\ProductImage;
use App\Models\ProductBrand;
use App\Models\ProductLabel;
use Illuminate\Support\Facades\File;
use App\Models\NotifyUser;

use Illuminate\Http\Request;


class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (auth()->user()->isAbleTo('Manage Products')) {
            $store_id = Store::where('id', getCurrentStore())->first();

            $products = Product::where('theme_id', $store_id->theme_id)->where('store_id', getCurrentStore())->orderBy('id', 'desc')->get();
            $settings = Setting::where('theme_id',$store_id->theme_id)->where('store_id', getCurrentStore())->pluck('value', 'name')->toArray();

            if($request->id ==1){
                $msg = __('Product Successfully Created');
                return view('product.index', compact('products','settings' ,'msg'));

            }elseif($request->id ==2){
                $msg = __('Product Successfully Updated');

                return view('product.index', compact('products','settings','msg'));


            }else{
                $msg = 0;

                return view('product.index', compact('products','settings','msg'));
            }
            // if($request->id)
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $link = env('APP_URL'). '/product/';
        $MainCategory = MainCategory::where('theme_id', APP_THEME())->where('store_id', getCurrentStore())->pluck('name', 'id')->prepend('Select Category', '');
        $Tax = Tax::where('theme_id', APP_THEME())->where('store_id', getCurrentStore())->pluck('name', 'id');
        $Tax_status = Tax::Taxstatus();

        $Shipping = Shipping::where('theme_id', APP_THEME())->where('store_id', getCurrentStore())->pluck('name', 'id')->prepend('Select Shipping', '');
        $brands = ProductBrand::where('status', 1)->where('theme_id', APP_THEME())->where('store_id', getCurrentStore())->pluck('name', 'id')->prepend('Select Brand', '');
        $labels = ProductLabel::where('status', 1)->where('theme_id', APP_THEME())->where('store_id', getCurrentStore())->pluck('name', 'id')->prepend('Select Label', '');
        
        $preview_type = [
            'Video File' => 'Video File',
            'Video Url' => 'Video Url',
            'iFrame' => 'iFrame'
        ];
        $tag = Tag::where('store_id', getCurrentStore())->where('theme_id',APP_THEME())->pluck('name', 'id');
        $ProductAttribute = ProductAttribute::where('theme_id', APP_THEME())->where('store_id', getCurrentStore())->pluck('name', 'id');
        return view('product.create' ,compact('link','MainCategory','ProductAttribute','Tax','Tax_status','Shipping','preview_type','tag', 'brands', 'labels'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // if(auth()->user()->isAbleTo('Create Product'))
        // {
            try{

                $user = \Auth::user();
                $creator = User::find($user->creatorId());
                $total_products = $user->countProducts();
                $plan = Plan::find($creator->plan_id);
                $store_id = Store::where('id', getCurrentStore())->first();

                if($request->variant_product == 0){
                    $rules = [
                        'name' => 'required',
                        'maincategory_id' => 'required',
                        'cover_image' => 'required',
                        'product_image' => 'required',
                        'status' => 'required',
                        'variant_product' => 'required',
                        'price' =>'required',
                        'sale_price' =>'required',
                        'subcategory_id' =>'required',
                        'brand_id' =>'nullable',
                        'label_id' =>'nullable',

                    ];

                }else{
                    $rules = [
                        'name' => 'required',
                        'maincategory_id' => 'required',
                        'cover_image' => 'required',
                        'product_image' => 'required',
                        'status' => 'required',
                        'variant_product' => 'required',
                        'subcategory_id' =>'required',
                        'brand_id' =>'nullable',
                        'label_id' =>'nullable',
                    ];
                }

                $validator = \Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();
                    $msg['flag'] = 'error';
                    $msg['msg'] = $messages->first();

                    return $msg;

                }

                $dir        = 'themes/' . APP_THEME() . '/uploads';
                if ($request->variant_product == 0) {
                if ($total_products < $plan->max_products || $plan->max_products == -1) {
                    $input = $request->all();
                    $input['attribute_options'] = [];
                    if ($request->has('attribute_no')) {
                        foreach ($request->attribute_no as $key => $no) {
                            $str = 'attribute_options_' . $no;
                            $enable_option = $input['visible_attribute_' . $no];
                            $variation_option = $input['for_variation_' . $no];
                            $item['attribute_id'] = $no;

                            if(!empty($request[$str])){
                                $item['values'] = explode(',', implode('|', $request[$str]));

                            }else{
                                $item['values'] = [];

                            }

                            $item['visible_attribute_' . $no] = $enable_option;
                            $item['for_variation_' . $no] = $variation_option;
                            array_push($input['attribute_options'], $item);
                        }
                    }



                if (!empty($request->attribute_no)) {
                    $input['product_attributes'] = implode(',',$request->attribute_no);
                }else{
                    $input['product_attributes'] = 0;

                }
                $input['attribute_options'] = json_encode($input['attribute_options']);
                $Product = new Product();
                $Product->name = $request->name;
                $Product->slug = $request->slug;
                $Product->maincategory_id = $request->maincategory_id;
                $Product->subcategory_id = $request->subcategory_id;
                $Product->brand_id = $request->brand_id ?? null;
                $Product->label_id = $request->label_id ?? null;
                if(!empty($request->tax_id)){
                    $Product->tax_id =implode(',', $request->tax_id);
                }else{
                    $tax = Tax::where('store_id' ,getCurrentStore())->where('theme_id',APP_THEME())->first();
                    if(isset($tax))
                    {
                        $Product->tax_id =$tax->id;
                    }
                }

                if ($request->cover_image) {

                    $image_size = $request->file('cover_image')->getSize();
                    $result = Utility::updateStorageLimit(\Auth::user()->creatorId(), $image_size);
                    if ($result == 1) {
                        $fileName = rand(10, 100) . '_' . time() . "_" . $request->cover_image->getClientOriginalName();
                        $path = Utility::upload_file($request, 'cover_image', $fileName, $dir, []);
                    } else {

                        $msg['flag'] = 'error';
                        $msg['msg'] = $result;

                        return $msg;
                    }

                    $Product->cover_image_path = $path['url'] ?? null;
                    $Product->cover_image_url = $path['full_url'] ?? null;
                }

                $Product->preview_type = $request->preview_type;

                if(!empty($request->preview_video)){

                    $ext = $request->file('preview_video')->getClientOriginalExtension();
                    $fileName = 'video_' . time() . rand() . '.' . $ext;
                    $dir_video = 'themes/' . APP_THEME() . '/uploads/preview_image';
                    $image_size = $request->file('preview_video')->getSize();
                    $result = Utility::updateStorageLimit(\Auth::user()->creatorId(), $image_size);
                    if ($result == 1) {
                        $path_video = Utility::upload_file($request, 'preview_video', $fileName, $dir_video, []);
                        if ($path_video['flag'] == 1) {
                            $url = $path_video['url'] ?? '';
                        } else {
                            $msg['flag'] = 'error';
                            $msg['msg'] = $path_video['msg'];

                            return $msg;
                        }
                    } else {

                        $msg['flag'] = 'error';
                        $msg['msg'] =  $result;

                        return $msg;
                    }
                    $Product->preview_content = $path_video['url'] ?? null;
                }
                if (!empty($request->video_url)) {
                    $Product->preview_content = $request->video_url;
                }
                if (!empty($request->preview_iframe)) {
                    $Product->preview_content = $request->preview_iframe;
                }
                $Product->tax_status = $request->tax_status;
                $Product->shipping_id = $request->shipping_id;
                $Product->product_weight = $request->product_weight;
                $Product->variant_product = $request->variant_product;
                $Product->trending = $request->trending;
                $Product->status = $request->status;
                $Product->description = $request->description;
                $Product->specification = $request->specification;
                $Product->detail = $request->detail;
                $Product->price = $request->price;
                $Product->sale_price = $request->sale_price;

                $Product->stock_status = $request->stock_status;
                $Product->attribute_id = $input['product_attributes'];
                $Product->product_attribute = $input['attribute_options'];

                $Product->product_stock = !empty($request->product_stock) ? $request->product_stock : 0;
                if ($request->track_stock == 1) {
                    $Product->track_stock = $request->track_stock;
                    $Product->stock_order_status = $request->stock_order_status;
                    $Product->low_stock_threshold = !empty($request->low_stock_threshold) ? $request->low_stock_threshold :  '';
                } else {
                    $Product->track_stock = !empty($request->track_stock)? $request->track_stock : 0;
                    $Product->stock_order_status = '';
                    $Product->low_stock_threshold = !empty($request->low_stock_threshold) ? $request->low_stock_threshold :  '';
                }
                if ($request->custom_field_status == '1') {
                    $Product->custom_field_status = '1';
                    $Product->custom_field = json_encode($request->custom_field_repeater_basic);
                }

                if (!empty($request->downloadable_product)) {
                    $image_size = $request->file('downloadable_product')->getSize();
                    $result = Utility::updateStorageLimit(\Auth::user()->creatorId(), $image_size);

                    if ($result == 1) {
                        $fileName = rand(10, 100) . '_' . time() . "_" . $request->downloadable_product->getClientOriginalName();
                        $path = Utility::upload_file($request, 'downloadable_product', $fileName, $dir, []);

                    } else {
                        $msg['flag'] = 'error';
                        $msg['msg'] =  $result;

                        return $msg;
                    }
                    $Product->downloadable_product = $path['url'];
                }




                $tag_data_id = [];
                $tag_ids =[];

                if(isset($request->tag_id)){

                    foreach($request->tag_id as $tag){
                        $tags = Tag::where('id' ,$tag)->where('store_id' ,getCurrentStore())->where('theme_id',APP_THEME())->first();
                        if(!empty($tags)){
                            $tag_id = $tags->id;
                            $tag_ids[] = $tag_id;
                        }else{
                        $tag_id = 0;
                        }
                        if($tag_id != $tag){
                            $tag_data = new Tag();
                            $tag_data->name = $tag;
                            $tag_data->store_id = getCurrentStore();
                            $tag_data->theme_id = APP_THEME();
                            $tag_data->created_by = \Auth::user()->id;
                            $tag_data->save();

                            $tag_data_id[] = $tag_data->id;

                        }
                    }
                }
                $tag_product_id = array_merge($tag_data_id,$tag_ids);
                if(!empty($tag_product_id)){

                    $Product->tag_id =  implode(',',$tag_product_id);
                }
                $Product->store_id = getCurrentStore();
                $Product->theme_id = APP_THEME();
                $Product->created_by = \Auth::user()->id;

                $Product->save();

                // if (!empty($Product))
                // {
                //     //webhook
                //     $module = 'New Product';
                //     $webhook =  Utility::webhook($module, $store_id->id);
                //     if ($webhook) {
                //         $parameter = json_encode($Product);
                //         // 1 parameter is  URL , 2 parameter is data , 3 parameter is method
                //         $status = Utility::WebhookCall($webhook['url'], $parameter, $webhook['method']);

                //         if ($status != true) {
                //             $msgs  = 'Webhook call failed.';
                //         }

                //         $msg['flag'] = 'success';
                //         $msg['msg']  = __('Product Successfully Created') . ((isset($msgs)) ? '<br> <span class="text-danger">' . $msgs . '</span>' : '');
                //         // $msg['msg']  = __('Product Successfully Created');
                //     }
                // }
                foreach ($request->product_image as $key => $image) {
                    $theme_image = $image;

                    $image_size = File::size($theme_image);
                    $result = Utility::updateStorageLimit(\Auth::user()->creatorId(), $image_size);
                    if ($result == 1) {
                        $fileName = rand(10, 100) . '_' . time() . "_" . $image->getClientOriginalName();
                        $pathss = Utility::keyWiseUpload_file($request, 'product_image', $fileName, $dir, $key, []);

                    } else {
                        $msg['flag'] = 'error';
                        $msg['msg'] =  $result;

                        return $msg;
                    }

                    if (isset($pathss['url'])) {
                        $ProductImage = new ProductImage();
                        $ProductImage->product_id = $Product->id;
                        $ProductImage->image_path = $pathss['url'];
                        $ProductImage->image_url  = $pathss['full_url'];
                        $ProductImage->theme_id   = $store_id->theme_id;
                        $ProductImage->store_id   = getCurrentStore();
                        $ProductImage->save();
                    }

                }


                } else {
                    $msg['flag'] = 'error';
                    $msg['msg'] =   __('Your Product limit is over, Please upgrade plan');

                    return $msg;
                }
                } else {

                    $input = $request->all();

                    $input['choice_options'] = [];
                    $input['attribute_options'] = [];
                    if ($request->has('choice_no')) {
                        foreach ($request->choice_no as $key => $no) {
                            $str = 'choice_options_' . $no;

                            $item['attribute_id'] = $no;
                            $item['values'] = explode(',', implode('|', $request[$str]));
                            array_push($input['choice_options'], $item);
                        }
                    }

                    if (!empty($request->choice_no)) {
                        $input['attributes'] = json_encode($request->choice_no);
                    } else {
                        $input['attributes'] = json_encode([]);
                    }

                    $input['choice_options'] = json_encode($input['choice_options']);
                    $input['slug'] = $input['name'];

                    if ($request->has('attribute_no')) {
                        foreach ($request->attribute_no as $key => $no) {
                            $str = 'attribute_options_' . $no;
                            $enable_option = $input['visible_attribute_' . $no];
                            $variation_option = $input['for_variation_' . $no];

                                $item['attribute_id'] = $no;
                                if(!empty($request[$str])){
                                    $item['values'] = explode(',', implode('|', $request[$str]));

                                }else{
                                    $item['values'] = [];

                                }
                                $item['visible_attribute_' . $no] = $enable_option;
                                $item['for_variation_' . $no] = $variation_option;

                                array_push($input['attribute_options'], $item);
                        }
                    }
                    if (!empty($request->attribute_no)) {
                        $input['product_attributes'] = implode(',',$request->attribute_no);
                    }
                    $input['attribute_options'] = json_encode($input['attribute_options']);

                    $theme_name = APP_THEME();
                    if ($request->cover_image) {

                        $image_size = $request->file('cover_image')->getSize();
                        $result = Utility::updateStorageLimit(\Auth::user()->creatorId(), $image_size);
                        if ($result == 1) {
                            $fileName = rand(10, 100) . '_' . time() . "_" . $request->cover_image->getClientOriginalName();
                            $path = Utility::upload_file($request, 'cover_image', $fileName, $dir, []);
                        } else {
                            $msg['flag'] = 'error';
                            $msg['msg'] =  $result;

                            return $msg;
                        }
                    }
                    if ($total_products < $plan->max_products || $plan->max_products == -1) {


                        $product = new Product();
                        $product->name = $request->name;
                        $product->slug = $request->slug;
                        $product->maincategory_id = $request->maincategory_id;
                        $product->subcategory_id = $request->subcategory_id;
                        if(!empty($request->tax_id)){
                            $product->tax_id =implode(',', $request->tax_id);
                        }else{
                            $tax = Tax::where('store_id' ,getCurrentStore())->where('theme_id',APP_THEME())->first();
                            if(isset($tax))
                            {
                                $product->tax_id =$tax->id;
                            }
                        }
                        $product->tax_status = $request->tax_status;
                        $product->shipping_id = $request->shipping_id;
                        $product->status = $request->status;
                        $product->description = $request->description;
                        $product->specification = $request->specification;
                        $product->detail = $request->detail;
                        $tag_id = $request->tag;
                        $product->cover_image_path = $path['url'] ?? null;
                        $product->cover_image_url = $path['full_url'] ?? null;
                        $product->attribute_id = $input['product_attributes'];
                        $product->product_attribute = $input['attribute_options'];


                        $product->preview_type = $request->preview_type;
                        if (!empty($request->video_url)) {
                            $product->preview_content = $request->video_url;
                        }
                        if (!empty($request->preview_video)) {
                            $ext = $request->file('preview_video')->getClientOriginalExtension();
                            $fileName = 'video_' . time() . rand() . '.' . $ext;

                            $dir_video = 'themes/' . APP_THEME() . '/uploads/preview_image';

                            $image_size = $request->file('preview_video')->getSize();
                            $result = Utility::updateStorageLimit(\Auth::user()->creatorId(), $image_size);

                            if ($result == 1) {
                                $path_video = Utility::upload_file($request, 'preview_video', $fileName, $dir_video, []);
                                if ($path_video['flag'] == 1) {
                                    $url = $path_video['url'];
                                } else {
                                    $msg['flag'] = 'error';
                                    $msg['msg'] =  $path_video['msg'];

                                    return $msg;
                                }
                            } else {
                                $msg['flag'] = 'error';
                                $msg['msg'] =  $result;

                                return $msg;
                            }
                            $product->preview_content = $path_video['url'];
                        }
                        if (!empty($request->preview_iframe)) {
                            $product->preview_content = $request->preview_iframe;
                        }
                        if (!empty($request->downloadable_product)) {

                            $image_size = $request->file('downloadable_product')->getSize();
                            $result = Utility::updateStorageLimit(\Auth::user()->creatorId(), $image_size);

                            if ($result == 1) {
                                $fileName = rand(10, 100) . '_' . time() . "_" . $request->downloadable_product->getClientOriginalName();
                                $path = Utility::upload_file($request, 'downloadable_product', $fileName, $dir, []);

                            } else {
                                $msg['flag'] = 'error';
                                $msg['msg'] =  $result;

                                return $msg;
                            }
                            $product->downloadable_product = $path['url'];
                        }
                        $product->product_stock = !empty($request->product_stock) ? $request->product_stock : 0;
                        $product->variant_product = $request->variant_product;
                        $product->trending = $request->trending ;
                        if ($request->track_stock == 1) {
                            $product->track_stock = $request->track_stock;
                            $product->stock_order_status = $request->stock_order_status;
                            $product->low_stock_threshold = !empty($request->low_stock_threshold) ? $request->low_stock_threshold : 0;
                        } else {
                            $product->track_stock = $request->track_stock;
                            $product->stock_order_status = '';
                            $product->low_stock_threshold = !empty($request->low_stock_threshold) ? $request->low_stock_threshold : 0;
                        }


                        $product->attribute_id = $input['product_attributes'];
                        $product->product_attribute = $input['attribute_options'];
                        // $product->product_option = json_encode($option_array);
                        // $product->product_option_api = json_encode($option_array_api);
                        $product->shipping_id = $request->shipping_id;
                        $product->theme_id = $store_id->theme_id;
                        $product->store_id = getCurrentStore();
                        $product->created_by = \Auth::user()->id;
                        if ($request->custom_field_status == '1') {
                            $product->custom_field_status = '1';
                            $product->custom_field = json_encode($request->custom_field_repeater_basic);
                        }
                        $tag_data_id = [];
                        $tag_ids =[];

                        if(isset($request->tag_id)){
                            foreach($request->tag_id as $tag){
                                $tags = Tag::where('id' ,$tag)->where('store_id' ,getCurrentStore())->where('theme_id',APP_THEME())->first();
                                if(!empty($tags)){
                                    $tag_id = $tags->id;
                                    $tag_ids[] = $tag_id;
                                }else{
                                $tag_id = 0;
                                }
                                if($tag_id != $tag){
                                    $tag_data = new Tag();
                                    $tag_data->name = $tag;
                                    $tag_data->store_id = getCurrentStore();
                                    $tag_data->theme_id = APP_THEME();
                                    $tag_data->created_by = \Auth::user()->id;
                                    $tag_data->save();

                                    $tag_data_id[] = $tag_data->id;

                                }
                            }
                        }
                        $tag_product_id = array_merge($tag_data_id,$tag_ids);
                        if(!empty($tag_product_id)){

                            $product->tag_id =  implode(',',$tag_product_id);
                        }
                        $product->save();
                        // if (!empty($product))
                        // {
                        //     //webhook
                        //     $module = 'New Product';
                        //     $webhook =  Utility::webhook($module, $store_id->id);
                        //     if ($webhook) {
                        //     $parameter = json_encode($product);
                        //     // 1 parameter is  URL , 2 parameter is data , 3 parameter is method
                        //     $status = Utility::WebhookCall($webhook['url'], $parameter, $webhook['method']);

                        //     if ($status != true) {
                        //         $msgs  = 'Webhook call failed.';
                        //     }

                        //     $msg['flag'] = 'success';
                        //             // $msg['msg']  = __('Product Successfully Created') . ((isset($msgs)) ? '<br> <span class="text-danger">' . $msgs . '</span>' : '');
                        //             $msg['msg']  = __('Product Successfully Created');
                        //     }
                        // } else {
                        //     $msg['flag'] = 'error';
                        //     $msg['msg']  = __('Product Created Failed');

                        //     return redirect()->back()->with($msg['flag'], $msg['msg']);
                        // }

                        foreach ($request->product_image as $key => $image) {
                            $theme_image = $image;

                            $image_size = File::size($theme_image);
                            $result = Utility::updateStorageLimit(\Auth::user()->creatorId(), $image_size);
                            if ($result == 1) {
                                $fileName = rand(10, 100) . '_' . time() . "_" . $image->getClientOriginalName();
                                $pathss = Utility::keyWiseUpload_file($request, 'product_image', $fileName, $dir, $key, []);
                            } else {
                                $msg['flag'] = 'error';
                                $msg['msg'] =  $result;

                                return $msg;
                            }

                            if (isset($pathss['url'])) {
                                $ProductImage = new ProductImage();
                                $ProductImage->product_id = $product->id;
                                $ProductImage->image_path = $pathss['url'];
                                $ProductImage->image_url  = $pathss['full_url'];
                                $ProductImage->theme_id   = $store_id->theme_id;
                                $ProductImage->store_id   = getCurrentStore();
                                $ProductImage->save();
                            }
                        }

                        $options = [];
                        $a_option = [];
                        if ($request->has('choice_no')) {
                            foreach ($request->choice_no as $key => $no) {
                                $name = 'choice_options_' . $no;
                                $my_str = implode('|', $request[$name]);
                                array_push($options, explode(',', $my_str));
                            }
                        }

                        $combinations = $this->combinations($options);
                        foreach ($request->attribute_no as $key => $no) {
                            $forVariationName = 'for_variation_' . $no;
                            if ($request->has($forVariationName) && $request->input($forVariationName) == 1) {
                                $name = 'attribute_options_' . $no;
                                $options = 'options';
                                $for_variation = isset($request->{'for_variation_' . $no}) ? $request->{'for_variation_' . $no} : 0;
                                if ($for_variation == 1) {
                                    if ($request->has($options) && is_array($request[$options])) {
                                        $my_str = $request[$options];
                                        $optionValues = [];

                                        foreach ($request[$options] as $term) {
                                            $optionValues[] = $term;
                                        }
                                        array_push($a_option, $my_str);
                                    }
                                }
                            }
                        }

                        $default_variant_id = 0;
                        if(!empty($a_option[0])){
                            if (count($a_option[0]) > 0) {
                                $product->variant_product = 1;
                                $is_in_stock = false;
                                foreach ($a_option as $key => $com) {
                                    $str = '';
                                    foreach ($com as $key => $item) {

                                        $str = $item;

                                        $product_stock = ProductVariant::where('product_id', $product->id)->where('variant', $str)->first();
                                        if ($product_stock == null) {
                                            $product_stock = new ProductVariant;
                                            $product_stock->product_id = $product->id;

                                        }

                                        $theme_name = APP_THEME();
                                        if ($request['downloadable_product_' . str_replace('.', '_', $str)]) {
                                            $fileName = rand(10, 100) . '_' . time() . "_" . $request->file('downloadable_product_' . $str)->getClientOriginalName();

                                            $path1 = Utility::upload_file($request, 'downloadable_product_' . $str, $fileName, $dir, []);
                                            $product_stock->downloadable_product = $path1['url'];

                                        }


                                        $var_option = "";
                                        $variation_option = !empty($request['variation_option_' . str_replace('.', '_', $str)]) ? $request['variation_option_' . str_replace('.', '_', $str)] : '';
                                        if (is_array($variation_option)) {
                                            foreach ($variation_option as $option) {
                                                $var_option .= $option . ",";
                                            }
                                        }

                                        $sku = str_replace(' ', '_', $request->name) . $request['product_sku_' . str_replace('.', '_', $str)];


                                        $product_stock->variant = $str;

                                        $product_stock->variation_option = $var_option;

                                        $product_stock->price = !empty($request['product_sale_price_' . str_replace('.', '_', $str)]) ? $request['product_sale_price_' . str_replace('.', '_', $str)] : 0;

                                        $product_stock->variation_price = !empty($request['product_variation_price_' . str_replace('.', '_', $str)]) ? $request['product_variation_price_' . str_replace('.', '_', $str)] : 0;
                                        $product_stock->sku = $sku;

                                        $product_stock->stock_status = !empty($request['stock_status_' . str_replace('.', '_', $str)]) ? $request['stock_status_' . str_replace('.', '_', $str)] : '';
                                        if ($variation_option) {
                                            if (in_array('manage_stock', $variation_option)) {
                                                $product_stock->stock_order_status = !empty($request['stock_order_status_' . str_replace('.', '_', $str)]) ? $request['stock_order_status_' . str_replace('.', '_', $str)] : 0;

                                                $product_stock->stock = !empty($request['product_stock_' . str_replace('.', '_', $str)]) ? $request['product_stock_' . str_replace('.', '_', $str)] : 0;
                                                $product_stock->low_stock_threshold = !empty($request['low_stock_threshold_' . str_replace('.', '_', $str)]) ? $request['low_stock_threshold_' . str_replace('.', '_', $str)] : 0;
                                            } else {

                                                $product_stock->stock = 0;
                                                $product_stock->stock_order_status = '';
                                                $product_stock->low_stock_threshold = !empty($request['low_stock_threshold_' . str_replace('.', '_', $str)]) ? $request['low_stock_threshold_' . str_replace('.', '_', $str)] : 0;
                                            }
                                        }
                                        $product_stock->weight = !empty($request['product_weight_' . str_replace('.', '_', $str)]) ? $request['product_weight_' . str_replace('.', '_', $str)] : 0;

                                        $product_stock->description = !empty($request['product_description_' . str_replace('.', '_', $str)]) ? $request['product_description_' . str_replace('.', '_', $str)] : '';

                                        $product_stock->shipping = !empty($request['shipping_id_' . str_replace('.', '_', $str)]) ? $request['shipping_id_' . str_replace('.', '_', $str)] : 'same_as_parent';

                                        $product_stock->theme_id = APP_THEME();
                                        $product_stock->store_id = getCurrentStore();
                                        $product_stock->save();

                                        if ($request->default_variant ==  '-' . $str) {
                                            $product_update = Product::find($product->id);
                                            $product_update->default_variant_id = $product_stock->id;
                                            $product_update->save();
                                        }
                                        if ($product_stock->stock == 'in_stock') {
                                            $is_in_stock = true;
                                        }
                                    }
                                }
                                if (!$is_in_stock) {
                                    $product->stock = 'out_of_stock';
                                }
                            } else {
                                $product->variant_product = 0;
                            }
                        }else{
                            $product->variant_product = 0;

                        }
                    } else {
                        $msg['flag'] = 'error';
                        $msg['msg'] =  __('Your Product limit is over, Please upgrade plan');

                        return $msg;
                    }
                }

                    $msg['flag'] = 'success';
                    $msg['msg'] =  __('Product saved successfully.');
                    return $msg;
            } catch(\Exception $e){
               \Log::info(['error' => $e]);
                $msg['flag'] = 'error';
                $msg['msg'] = $e->getMessage();
                return $msg;
            }
        // }
                // else
                // {
                //     return redirect()->back()->with('error', __('Permission denied.'));
                // }

        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $link = env('APP_URL'). '/product/';
        $MainCategory = MainCategory::where('theme_id', APP_THEME())->where('store_id', getCurrentStore())->pluck('name', 'id')->prepend('Select Category', '');
        $SubCategory = SubCategory::where('theme_id', APP_THEME())->where('store_id', getCurrentStore())->pluck('name', 'id')->prepend('Select Category', '');
        $Tax = Tax::where('theme_id', APP_THEME())->where('store_id', getCurrentStore())->pluck('name', 'id');
        $Tax_status = Tax::Taxstatus();
        $Shipping = Shipping::where('theme_id', APP_THEME())->where('store_id', getCurrentStore())->pluck('name', 'id')->prepend('Select Shipping', '');
        $preview_type = [
            'Video File' => 'Video File',
            'Video Url' => 'Video Url',
            'iFrame' => 'iFrame'
        ];
        $ProductAttribute = ProductAttribute::where('theme_id', APP_THEME())->where('store_id', getCurrentStore())->pluck('name', 'id');
        $product_image = ProductImage::where('product_id' ,$product->id)->where('theme_id', APP_THEME())->where('store_id', getCurrentStore())->get();
        $get_tax = explode(',',$product->tax_id);
        $get_datas = explode(',',$product->attribute_id);
        $tag = Tag::where('store_id', getCurrentStore())->where('theme_id',APP_THEME())->pluck('name', 'id');
        $get_tags = explode(',',$product->tag_id);

        $brands = ProductBrand::where('status', 1)->where('theme_id', APP_THEME())->where('store_id', getCurrentStore())->pluck('name', 'id')->prepend('Select Brand', '');
        $labels = ProductLabel::where('status', 1)->where('theme_id', APP_THEME())->where('store_id', getCurrentStore())->pluck('name', 'id')->prepend('Select Label', '');

        $compact = ['link','product','MainCategory','Tax','Tax_status','Shipping','preview_type','ProductAttribute','SubCategory','product_image','get_tax','get_datas','tag' ,'get_tags', 'brands', 'labels'];
        return view('product.edit', compact($compact));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        if (auth()->user()->isAbleTo('Edit Products')) {
            // try{


                $dir        = 'themes/' . APP_THEME() . '/uploads';

                $rules = [
                    'name' => 'required',
                    'maincategory_id' => 'required',
                    'status' => 'required',
                    'variant_product' => 'required',
                    'brand_id' => 'nullable',
                    'label_id' => 'nullable',
                ];
                $validator = \Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();
                    $msg['flag'] = 'error';
                    $msg['msg'] =  $messages->first();
                    return $msg;

                }

                if ($request->cover_image) {
                    $image_size = $request->file('cover_image')->getSize();
                    $file_path =  $product->cover_image_path;

                    $result = Utility::updateStorageLimit(\Auth::user()->creatorId(), $image_size);
                    if ($result == 1) {
                        Utility::changeStorageLimit(\Auth::user()->creatorId(), $file_path);

                        $fileName = rand(10, 100) . '_' . time() . "_" . $request->cover_image->getClientOriginalName();
                        $path = Utility::upload_file($request, 'cover_image', $fileName, $dir, []);
                        if (File::exists(base_path($product->cover_image_path))) {
                            File::delete(base_path($product->cover_image_path));
                        }
                    } else {
                        $msg['flag'] = 'error';
                        $msg['msg'] = $result;

                        return $msg;
                    }
                    $product->cover_image_path = $path['url'];
                    $product->cover_image_url = $path['full_url'];
                }



                $product->name = $request->name;
                $product->slug = $request->slug;
                $product->description = $request->description;
                $product->specification = $request->specification;
                $product->detail = $request->detail;
                $product->stock_status = $request->stock_status;
                $product->product_weight = $request->product_weight;
                $tag_id = $request->tag;
                $product->maincategory_id = $request->maincategory_id;
                $product->subcategory_id = $request->subcategory_id;
                if ($request->brand_id) {
                    $product->brand_id = $request->brand_id ?? null;
                }
                if ($request->label_id) {
                    $product->label_id = $request->label_id ?? null;
                }
                
                $product->tax_status = $request->tax_status;


                if(!empty($request->tax_id)){
                    $product->tax_id =implode(',',$request->tax_id);
                }else{
                    $tax = Tax::where('store_id' ,getCurrentStore())->where('theme_id',APP_THEME())->first();
                    if(isset($tax))
                    {
                        $product->tax_id =$tax->id;
                    }
                }
                $product->preview_type = $request->preview_type;
                if (!empty($request->video_url)) {
                    $product->preview_content = $request->video_url;
                }
                if (!empty($request->preview_video)) {
                    $ext = $request->file('preview_video')->getClientOriginalExtension();
                    $fileName = 'video_' . time() . rand() . '.' . $ext;

                    $dir_video = 'themes/' . APP_THEME() . '/uploads/preview_image';

                    $file_paths = $product->preview_video;
                    $image_size = $request->file('preview_video')->getSize();
                    $result = Utility::updateStorageLimit(\Auth::user()->creatorId(), $image_size);
                    if ($result == 1) {
                        Utility::changeStorageLimit(\Auth::user()->creatorId(), $file_paths);
                        $path_video = Utility::upload_file($request, 'preview_video', $fileName, $dir_video, []);
                        if ($path_video['flag'] == 1) {
                            $url = $path_video['url'];
                        } else {
                            $msg['flag'] = 'error';
                            $msg['msg'] =$path_video['msg'];
                            return $msg;
                        }
                    } else {
                        $msg['flag'] = 'error';
                        $msg['msg'] =$result;
                        return $msg;
                    }



                    $product->preview_content = $path_video['url'];
                }

                if (!empty($request->preview_iframe)) {
                    $product->preview_content = $request->preview_iframe;
                }
                    $product->variant_product = $request->variant_product;
                    $product->shipping_id = $request->shipping_id;
                    $product->status = $request->status;
                    $product->trending = $request->trending;


                if ($request->track_stock == 1) {
                    $product->track_stock = $request->track_stock;
                    $product->stock_order_status = $request->stock_order_status;
                    $product->low_stock_threshold = !empty($request->low_stock_threshold) ? $request->low_stock_threshold : 0;
                } else {
                    $product->track_stock = $request->track_stock;
                    $product->stock_order_status = '';
                    $product->low_stock_threshold = !empty($request->low_stock_threshold) ? $request->low_stock_threshold : 0;
                }

                if ($request->custom_field_status == '1') {
                    $product->custom_field_status = '1';
                    $product->custom_field = json_encode($request->custom_field_repeater_basic);
                } else {
                    $product->custom_field = NULL;
                }

                if (!empty($request->downloadable_product))
                {
                    $image_size = $request->file('downloadable_product')->getSize();
                    $result = Utility::updateStorageLimit(\Auth::user()->creatorId(), $image_size);
                    $file_paths = $product->downloadable_product;

                    if ($result == 1) {
                        Utility::changeStorageLimit(\Auth::user()->creatorId(), $file_paths);

                        $fileName = rand(10, 100) . '_' . time() . "_" . $request->downloadable_product->getClientOriginalName();
                        $path = Utility::upload_file($request, 'downloadable_product', $fileName, $dir, []);
                        if (File::exists(base_path($product->downloadable_product))) {
                            File::delete(base_path($product->downloadable_product));
                        }

                    } else {

                        $msg['flag'] = 'error';
                        $msg['msg'] =$result;
                        return $msg;
                    }
                    $product->downloadable_product = $path['url'];
                }


                if(!empty($request->product_image)){

                    foreach ($request->product_image as $key => $image) {
                        $theme_image = $image;

                        $image_size = File::size($theme_image);
                        $result = Utility::updateStorageLimit(\Auth::user()->creatorId(), $image_size);
                        if ($result == 1) {
                            $fileName = rand(10, 100) . '_' . time() . "_" . $image->getClientOriginalName();
                            $pathss = Utility::keyWiseUpload_file($request, 'product_image', $fileName, $dir, $key, []);
                        } else {
                            $msg['flag'] = 'error';
                            $msg['msg'] =  $result;

                            return $msg;
                        }

                        if (isset($pathss['url'])) {
                            $ProductImage = new ProductImage();
                            $ProductImage->product_id = $product->id;
                            $ProductImage->image_path = $pathss['url'];
                            $ProductImage->image_url  = $pathss['full_url'];
                            $ProductImage->theme_id   = APP_THEME();
                            $ProductImage->store_id   = getCurrentStore();
                            $ProductImage->save();
                        }

                    }
                }

                if ($request->variant_product == 0) {


                    $product->price = $request->price;
                    $product->sale_price = $request->sale_price;


                    if ($request->track_stock == 0) {
                        $product->product_stock = 0;
                    } else {
                        $product->product_stock = $request->product_stock;
                    }


                    $input = $request->all();

                    $input['attribute_options'] = [];
                    if ($request->has('attribute_no')) {
                        foreach ($request->attribute_no as $key => $no) {
                            $str = 'attribute_options_' . $no;
                            $enable_option = $input['visible_attribute_' . $no];
                            $variation_option = $input['for_variation_' . $no];

                            $item['attribute_id'] = $no;

                            $optionValues = [];
                            if(isset($request[$str])){
                                foreach ($request[$str] as $fValue) {
                                    $id = ProductAttributeOption::where('terms', $fValue)->first()->toArray();
                                    $optionValues[] = $id['id'];
                                }
                            }

                            $item['values'] = explode(',', implode('|', $optionValues));
                            $item['visible_attribute_' . $no] = $enable_option;
                            $item['for_variation_' . $no] = $variation_option;
                            array_push($input['attribute_options'], $item);
                        }
                    }

                    if (!empty($request->attribute_no)) {
                        $input['product_attributes'] = implode(',',$request->attribute_no);
                    }
                    else {
                        $input['product_attributes'] = 0;
                    }
                    $input['attribute_options'] = json_encode($input['attribute_options']);
                    $product->attribute_id = $input['product_attributes'];
                    $product->product_attribute = $input['attribute_options'];
                    $tag_data_id = [];
                    $tag_ids =[];

                    if(isset($request->tag_id)){

                        foreach($request->tag_id as $tag){

                            $tags = Tag::where('id' ,$tag)->where('store_id' ,getCurrentStore())->where('theme_id',APP_THEME())->first();

                            if(!empty($tags)){
                                $tag_id = $tags->id;
                                $tag_ids[] = $tag_id;
                            }else{
                              $tag_id = 0;
                            }
                            if($tag_id != $tag){
                                $tag_data = new Tag();
                                $tag_data->name = $tag;
                                $tag_data->store_id = getCurrentStore();
                                $tag_data->theme_id = APP_THEME();
                                $tag_data->created_by = \Auth::user()->id;
                                $tag_data->save();

                                $tag_data_id[] = $tag_data->id;

                            }
                        }
                        $tag_product_id = array_merge($tag_data_id,$tag_ids);

                        if(!empty($tag_product_id)){
                            $product->tag_id =  implode(',',$tag_product_id);

                        }
                    }
                    $product->save();


                } else {

                    $input = $request->all();
                    $input['choice_options'] = [];
                    $input['attribute_options'] = [];
                    if ($request->has('choice_no')) {
                        foreach ($request->choice_no as $key => $no) {
                            $str = 'choice_options_' . $no;

                            $item['attribute_id'] = $no;
                            $item['values'] = explode(',', implode('|', $request[$str]));
                            array_push($input['choice_options'], $item);
                        }
                    }

                    if (!empty($request->choice_no)) {
                        $input['attributes'] = json_encode($request->choice_no);
                    } else {
                        $input['attributes'] = json_encode([]);
                    }

                    $input['choice_options'] = json_encode($input['choice_options']);
                    $input['slug'] = $input['name'];

                    if ($request->has('attribute_no')) {
                        foreach ($request->attribute_no as $key => $no) {
                            $str = 'attribute_options_' . $no;
                            $enable_option = $input['visible_attribute_' . $no];
                            $variation_option = $input['for_variation_' . $no];

                            $item['attribute_id'] = $no;
                            $optionValues = [];
                            if (isset($request[$str])) {
                                foreach ($request[$str] as $fValue) {
                                $id = ProductAttributeOption::where('terms', $fValue)->first()->toArray();
                                $optionValues[] = $id['id'];
                            }
                        }
                            $item['values'] = explode(',', implode('|', $optionValues));
                            $item['visible_attribute_' . $no] = $enable_option;
                            $item['for_variation_' . $no] = $variation_option;
                            array_push($input['attribute_options'], $item);
                        }
                    }

                    if (!empty($request->attribute_no)) {
                        $input['product_attributes'] = implode(',',$request->attribute_no);
                    } else {
                        $input['product_attributes'] = json_encode([]);
                    }
                    $input['attribute_options'] = json_encode($input['attribute_options']);

                    $product->price = 0;
                    $product->product_stock = 0;
                    $product->attribute_id = $input['product_attributes'];
                    $product->product_attribute = $input['attribute_options'];

                    $product->preview_type = $request->preview_type;
                    if (!empty($request->video_url)) {
                        $product->preview_content = $request->video_url;
                        // $product->save();
                    }
                    if (!empty($request->preview_video)) {
                        $ext = $request->file('preview_video')->getClientOriginalExtension();
                        $fileName = 'video_' . time() . rand() . '.' . $ext;

                        $dir_video = 'themes/' . APP_THEME() . '/uploads/preview_image';
                        $file_paths = $product->preview_video;
                        $image_size = $request->file('preview_video')->getSize();
                        $result = Utility::updateStorageLimit(\Auth::user()->creatorId(), $image_size);
                        if ($result == 1) {
                            $path_video = Utility::upload_file($request, 'preview_video', $fileName, $dir_video, []);
                            if ($path_video['flag'] == 1) {
                                $url = $path_video['url'];
                            } else {
                                return redirect()->back()->with('error', __($path_video['msg']));
                            }
                        } else {
                            return redirect()->back()->with('error', $result);
                        }
                        $product->preview_content = $path_video['url'];
                    }
                    if (!empty($request->preview_iframe)) {
                        $product->preview_content = $request->preview_iframe;
                    }
                    $product->shipping_id = $request->shipping_id;
                    if ($request->custom_field_status == '1') {
                        $product->custom_field = json_encode($request->custom_field_repeater_basic);
                    } else {
                        $product->custom_field = NULL;
                    }
                    $product->save();

                    $options = [];
                    if ($request->has('choice_no')) {
                        foreach ($request->choice_no as $key => $no) {
                            $name = 'choice_options_' . $no;
                            $my_str = implode('|', $request[$name]);
                            array_push($options, explode(',', $my_str));
                        }
                    }

                    $sku_array = [];
                    $total_stock = 0;
                    $combinations = $this->combinations($options);
                    if (count($combinations[0]) > 0) {
                        $product->variant_product = 1;
                        foreach ($combinations as $key => $combination) {
                            $str = '';
                            foreach ($combination as $key => $item) {
                                if ($key > 0) {
                                    $str .= '-' . str_replace(' ', '', $item);
                                } else {
                                    $str .= str_replace(' ', '', $item);
                                }
                            }

                            $product_stock = ProductVariant::where('product_id', $product->id)->where('variant', $str)->first();
                            if ($product_stock == null) {
                                $product_stock = new ProductVariant;
                                $product_stock->product_id = $product->id;
                            }
                            array_push($sku_array, $str);

                            $sku = str_replace(' ', '_', $request->name) . $request['sku_' . str_replace('.', '_', $str)];
                            $total_stock += $request['stock_' . str_replace('.', '_', $str)];
                            $product_stock->variant = $str;
                            $product_stock->price = $request['price_' . str_replace('.', '_', $str)];
                            $product_stock->sku = $sku;
                            $product_stock->stock = $request['stock_' . str_replace('.', '_', $str)];
                            $product_stock->theme_id = APP_THEME();


                            $product_stock->save();


                            if ($request->default_variant == '-' . $str) {
                                $product_update = Product::find($product->id);
                                $product_update->default_variant_id = $product_stock->id;
                                $product_update->save();
                            }
                        }
                        ProductVariant::where('product_id', $product->id)->where('theme_id', APP_THEME())->whereNotIn('variant', $sku_array)->delete();
                        $product->product_stock = $total_stock;
                        $tag_data_id = [];
                        $tag_ids =[];
                        if(isset($request->tag_id)){

                            foreach($request->tag_id as $tag){
                                $tags = Tag::where('id' ,$tag)->where('store_id' ,getCurrentStore())->where('theme_id',APP_THEME())->first();
                                if(!empty($tags)){
                                    $tag_id = $tags->id;
                                    $tag_ids[] = $tag_id;
                                }else{
                                $tag_id = 0;
                                }
                                if($tag_id != $tag){
                                    $tag_data = new Tag();
                                    $tag_data->name = $tag;
                                    $tag_data->store_id = getCurrentStore();
                                    $tag_data->theme_id = APP_THEME();
                                    $tag_data->created_by = \Auth::user()->id;
                                    $tag_data->save();

                                    $tag_data_id[] = $tag_data->id;

                                }
                            }
                        }
                        $tag_product_id = array_merge($tag_data_id,$tag_ids);
                        if(!empty($tag_product_id)){

                            $product->tag_id =  implode(',',$tag_product_id);
                        }
                        $product->save();
                    } else {
                        $product->variant_product = 0;
                    }

                    $attribute_option = [];
                    if ($request->attribute_no) {
                        foreach ($request->attribute_no as $key => $no) {
                            $forVariationName = 'for_variation_' . $no;
                            if ($request->has($forVariationName) && $request->input($forVariationName) == 1) {
                                $name = 'attribute_options_' . $no;
                                $options_data = 'options_datas';
                                $for_variation = isset($request->{'for_variation_' . $no}) ? $request->{'for_variation_' . $no} : 0;
                                if ($for_variation == 1) {
                                    if ($request->has($options_data) && is_array($request[$options_data])) {
                                        $my_str = $request[$options_data];
                                        $optionValues = [];

                                        foreach ($request[$options_data] as $term) {

                                            $optionValues[] = $term;
                                        }

                                        array_push($attribute_option, $my_str);
                                    }
                                }
                            }
                        }
                    }
                    if ($attribute_option) {
                        if (count($attribute_option[0]) > 0) {
                            $product->variant_product = 1;
                            $is_in_stock = false;
                            foreach ($attribute_option as $key => $com) {
                                $str = '';
                                foreach ($com as $key => $item) {
                                    $str = $item;

                                    $product_stock = ProductVariant::where('product_id', $product->id)->where('variant', $str)->first();
                                    if ($product_stock == null) {
                                        $product_stock = new ProductVariant;
                                        $product_stock->product_id = $product->id;
                                    }

                                    $theme_name = APP_THEME();
                                    if ($request['downloadable_product_' . str_replace('.', '_', $str)]) {
                                        $fileName = rand(10, 100) . '_' . time() . "_" . $request->file('downloadable_product_' . $str)->getClientOriginalName();

                                        $path1 = Utility::upload_file($request, 'downloadable_product_' . $str, $fileName, $dir, []);
                                        $product_stock->downloadable_product = $path1['url'];
                                    }


                                    $var_option = "";
                                    $variation_option = !empty($request['variation_option_' . str_replace('.', '_', $str)]) ? $request['variation_option_' . str_replace('.', '_', $str)] : '';

                                    if (is_array($variation_option)) {
                                        foreach ($variation_option as $option) {
                                            $var_option .= $option . ",";
                                        }
                                    }

                                    $sku = str_replace(' ', '_', $request->name) . $request['product_sku_' . str_replace('.', '_', $str)];
                                    $product_stock->variant = $str;

                                    if ($product_stock->track_stock == 1) {
                                        $product_stock->stock_status = '';
                                    } else {

                                        $product_stock->stock_status = !empty($request['stock_status_' . str_replace('.', '_', $str)]) ? $request['stock_status_' . str_replace('.', '_', $str)] : '';
                                    }

                                    $product_stock->variation_option = $var_option;

                                    $product_stock->price = !empty($request['product_sale_price_' . str_replace('.', '_', $str)]) ? $request['product_sale_price_' . str_replace('.', '_', $str)] : 0;

                                    $product_stock->variation_price = !empty($request['product_variation_price_' . str_replace('.', '_', $str)]) ? $request['product_variation_price_' . str_replace('.', '_', $str)] : 0;
                                    $product_stock->sku = $sku;

                                    $product_stock->stock = !empty($request['product_stock_' . str_replace('.', '_', $str)]) ? $request['product_stock_' . str_replace('.', '_', $str)] : 0;

                                    $product_stock->low_stock_threshold = !empty($request['low_stock_threshold_' . str_replace('.', '_', $str)]) ? $request['low_stock_threshold_' . str_replace('.', '_', $str)] : 0;

                                    $product_stock->weight = !empty($request['product_weight_' . str_replace('.', '_', $str)]) ? $request['product_weight_' . str_replace('.', '_', $str)] : 0;

                                    $product_stock->stock_order_status = !empty($request['stock_order_status_' . str_replace('.', '_', $str)]) ? $request['stock_order_status_' . str_replace('.', '_', $str)] : 0;

                                    $product_stock->description = !empty($request['product_description_' . str_replace('.', '_', $str)]) ? $request['product_description_' . str_replace('.', '_', $str)] : '';

                                    $product_stock->shipping = !empty($request['shipping_id_' . str_replace('.', '_', $str)]) ? $request['shipping_id_' . str_replace('.', '_', $str)] : 'same_as_parent';

                                    $product_stock->theme_id = APP_THEME();
                                    $product_stock->store_id = getCurrentStore();
                                    $product_stock->save();

                                    if ($request->default_variant ==  '-' . $str) {
                                        $product_update = Product::find($product->id);
                                        $product_update->default_variant_id = $product_stock->id;
                                        $product_update->save();
                                    }

                                    if ($product_stock->stock == 'in_stock') {
                                        $is_in_stock = true;
                                    }
                                }
                                ProductVariant::where('product_id', $product->id)
                                    ->whereNotIn('variant', $com)
                                    ->delete();
                            }
                            if (!$is_in_stock) {
                                $product->stock = 'out_of_stock';
                            }
                        } else {
                            $product->variant_product = 0;
                        }
                    }
                }


                $firebase_enabled = Utility::GetValueByName('firebase_enabled');
                if (!empty($firebase_enabled) && $firebase_enabled == 'on') {
                    $fcm_Key = Utility::GetValueByName('fcm_Key');
                    if (!empty($fcm_Key)) {
                        $NotifyUsers = NotifyUser::where('product_id', $product->id)->get();
                        if (!empty($NotifyUsers)) {
                            foreach ($NotifyUsers as $key => $value) {
                                $User_data = User::find($value->user_id);
                                if (!empty($User_data->firebase_token)) {
                                    $device_id = $User_data->firebase_token;
                                    $message = 'now ' . $product->name . ' is available in stock';
                                    Utility::sendFCM($device_id, $fcm_Key, $message);
                                    NotifyUser::where('product_id', $product->id)->where('user_id', $User_data->id)->delete();
                                }
                            }
                        }
                    }
                }
                $msg['flag'] = 'success';
                $msg['msg'] =__('Product update successfully.');
                return $msg;
            // }catch(\Exception $e){
            //     dd($e);
            //     $msg['flag'] = 'error';
            //     $msg['msg'] = $e->getMessage();
            //     return $msg;

            // }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {

        if (auth()->user()->isAbleTo('Delete Products')) {
            $ProductImages = ProductImage::where('product_id', $product->id)->get();

            $Product = Product::find($product->id);
            $file_path1 = [];
            foreach ($ProductImages as $key => $ProductImage) {
                $file_path1[] =  $ProductImage->image_path;
            }
            $file_paths2[] = $Product->cover_image_path;
            $file_path = array_merge($file_path1, $file_paths2);
            Utility::changeproductStorageLimit(\Auth::user()->creatorId(), $file_path, $file_path1);
            if (!empty($ProductImages)) {
                // image remove from product variant image
                foreach ($ProductImages as $key => $ProductImage) {
                    if (File::exists(base_path($ProductImage->image_path))) {
                        File::delete(base_path($ProductImage->image_path));
                    }
                }
            }

            ProductImage::where('product_id', $product->id)->delete();

            ProductVariant::where('product_id', $product->id)->delete();

            $Product = Product::find($product->id);
            if (!empty($Product)) {
                // image remove from description json
                $description_json = $Product->other_description_api;
                if (!empty($description_json)) {
                    $description_json = json_decode($Product->other_description_api, true);
                    foreach ($description_json['product-other-description'] as $key => $value) {
                        if ($value['field_type'] == 'photo upload') {
                            if (File::exists(base_path($value['value']))) {
                                File::delete(base_path($value['value']));
                            }
                        }
                    }
                }

                // image remove from cover image
                if (File::exists(base_path($Product->cover_image_path))) {
                    File::delete(base_path($Product->cover_image_path));
                }
                Product::where('id', $product->id)->delete();
            }
            return redirect()->back()->with('success', __('Product delete successfully.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function get_slug(Request $request){
        $result = Product::slugs($request->value);
        return response()->json(['result' => $result]);

    }

    public function get_subcategory(Request $request){
        $id = $request->id;
        $value = $request->val;
        $SubCategory = SubCategory::where('maincategory_id', $id)->get();
        $option = '<option value="">' . __('Select Product') . '</option>';
        foreach ($SubCategory as $key => $Category) {
            $select = $value == $Category->id ? 'selected' : '';
            $option .= '<option value="' . $Category->id . '" '.$select.'>' . $Category->name . '</option>';
        }

        $select =  '<select class="form-control" data-role="tagsinput" id="subcategory_id" name="subcategory_id">'.$option.'</select>';
        $return['status'] = true;
        $return['html'] = $select;
        return response()->json($return);
    }

    public function attribute_option(Request $request)
    {
        $Attribute_option = ProductAttributeOption::where('attribute_id', $request->attribute_id)->where('theme_id',APP_THEME())->where('store_id', getCurrentStore())
            ->get()->pluck('terms', 'id')->toArray();

        return response()->json($Attribute_option);
    }

    public function attribute_combination(Request $request)
    {

        $options = array();
        $unit_price = !empty($request->price) ? $request->price : 0;
        $product_name = !empty($request->sku) ? $request->sku : '';
        $stock = !empty($request->product_stock) ? $request->product_stock : 0;
        $input = $request->all();

        foreach ($request->attribute_no as $key => $no) {
            $forVariationName = 'for_variation_' . $no;
            $for_variation = isset($request->{'for_variation_' . $no}) ? $request->{'for_variation_' . $no} : 0;

            if ($request->has($forVariationName) && $request->input($forVariationName) == 1) {
                $name = 'attribute_options_' . $no;
                $value = 'options_' . $no;
                if ($for_variation == 1) {
                    if ($request->has($name) && is_array($request[$name])) {
                        $my_str = $request[$name];
                        $optionValues = [];

                        foreach ($request[$name] as $id) {
                            $option = ProductAttributeOption::where('id', $id)->first();

                            if ($option) {
                                $optionValues[] = $option->terms;
                            }
                        }

                        array_push($options, $optionValues);
                    }
                }
            }
        }

        $combinations = $this->combination($options);

        $Shipping = Shipping::where('theme_id',APP_THEME())->where('store_id', getCurrentStore())->pluck('name', 'id')->prepend('Same as Parent', '');

        return view('product.attribute_combinations', compact('combinations', 'input', 'unit_price', 'product_name', 'stock', 'Shipping'));
    }

    public function sku_combination(Request $request)
    {
        $options = array();
        $unit_price = !empty($request->price) ? $request->price : 0;
        $product_name = !empty($request->sku) ? $request->sku : '';
        $stock = !empty($request->product_stock) ? $request->product_stock : 0;

        if ($request->has('choice_no')) {
            foreach ($request->choice_no as $key => $no) {
                $name = 'choice_options_' . $no;
                $my_str = implode('', $request[$name]);
                array_push($options, explode(',', $my_str));
            }
        }
        $combinations = $this->combinations($options);
        return view('product.sku_combinations', compact('combinations', 'unit_price', 'product_name', 'stock'));
    }

    public function attribute_combination_data(Request $request)
    {
        $product_stock = ProductVariant::where('product_id', $request->id)->where('theme_id',APP_THEME())->where('store_id', getCurrentStore())
            ->get();
        $Shipping = Shipping::where('theme_id',APP_THEME())->where('store_id', getCurrentStore())->pluck('name', 'id')->prepend('Same as parent', '');
        return view('product.attribute_combinations_data', compact('product_stock', 'Shipping'));
    }

    public function combinations($arrays)
    {

        $result = array(array());
        foreach ($arrays as $property => $property_values) {
            $tmp = array();
            foreach ($result as $result_item) {
                foreach ($property_values as $property_value) {
                    $tmp[] = array_merge($result_item, array($property => $property_value));
                }
            }
            $result = $tmp;
        }
        return $result;
    }

    public function combination($arrays)
    {
        $result = array(array());
        foreach ($arrays as $property => $property_values) {
            $tmp = array();
            foreach ($result as $result_item) {
                foreach ($property_values as $property_value) {
                    $tmp[] = array_merge($result_item, array($property => $property_value));
                }
            }
            $result = $tmp;
        }
        return $result;
    }

    public function file_delete($id){

        $product_img_id = ProductImage::find($id);
        if (File::exists(base_path($product_img_id->image_path))) {
            File::delete(base_path($product_img_id->image_path));
        }
        $product_img_id->delete();
        $msg['flag'] = 'success';
        $msg['msg'] = __('Image delete Successfully');
        return $msg;
    }

    public function attribute_combination_edit(Request $request)
    {
        $product = Product::find($request->id);
        $options = array();
        $product_name = !empty($request->sku) ? $request->sku : '';
        $unit_price = !empty($request->price) ? $request->price : 0;

        foreach ($request->attribute_no as $key => $no) {
            $forVariationName = 'for_variation_' . $no;
            $for_variation = isset($request->{'for_variation_' . $no}) ? $request->{'for_variation_' . $no} : 0;
            if ($for_variation == 1) {
                if ($request->has($forVariationName) && $request->input($forVariationName) == 1) {
                    $name = 'attribute_options_' . $no;

                    if ($request->has($name) && is_array($request[$name])) {
                        $my_str = $request[$name];
                        $optionValues = [];
                        array_push($options, $my_str);
                    }
                }
            }
        }

        $combinations = $this->combination($options);
        $Shipping = Shipping::where('theme_id', APP_THEME())->where('store_id', getCurrentStore())->pluck('name', 'id')->prepend('Same as parent', '');
        return view('product.attribute_combinations_edit', compact('combinations', 'unit_price', 'product_name', 'product', 'Shipping'));
    }

    public function product_attribute_delete($id)
    {
        $attribute = ProductVariant::findOrFail($id);
        $attribute->delete();

        return "true";
    }

    public function collectionAll($storeSlug,Request $request, $list)
    {
    }

    public function product_price(Request $request, $slug)
    {
        $store = Store::where('slug', $slug)->first();
        $theme_id = $store->theme_id;
        $settings = Setting::where('theme_id', $theme_id)->where('store_id', $store->id)->pluck('value', 'name')->toArray();

        $varint = $request->varint;
        $qty = $request->qty;
        $product_id = $request->product_id;
        $return['qty'] = $qty;
        $return['variant_id'] = 0;

        $product = Product::find($product_id);
        $return['variant_product'] = $product->variant_product ?? 0;
        if (!empty($product)) {
            if ($product->variant_product == 0) {
                // no varint
                if (isset($settings['out_of_stock_threshold']) && ($product->product_stock < $settings['out_of_stock_threshold']) && $product->stock_order_status == 'not_allow') {
                    $return['status'] = 'error';
                    $return['message'] = __('Product has been reached max quantity.');
                } else {
                    $product_original_price = $product->original_price * $qty;
                    $product_final_price = $product->final_price * $qty;

                    $data['theme_id'] =  APP_THEME();
                    $data['store_id'] = $store->id;
                    $data['sub_total'] = $product_final_price;
                    $data['product_original_price'] = $product_original_price;
                    $cart_array  = Tax::TaxCount($data);

                    $return['sub_total'] = $product_final_price;
                    $return['product_original_price'] = $product_original_price;
                    $return['variant_id'] = 0;
                    $return['original_price'] = $cart_array['original_price'];
                    $return['final_price'] = $cart_array['final_price'];
                    $return['currency_name'] = $cart_array['currency_name'];
                    $return['total_tax_price'] = $cart_array['total_tax_price'];
                    return response()->json($return);
                }
            } elseif ($product->variant_product == 1) {
                // has varint
                if (is_array($varint)) {
                    $variant_name = implode('-', $varint);
                } else {
                    $variant_name = $varint;
                }
                
                $product->setAttribute('variantName', $variant_name);
                $ProductStock = ProductVariant::where('product_id', $product_id)
                    ->where('variant', $variant_name)
                    ->first();
                    if ($ProductStock)
                    {
                        $stock = !empty($ProductStock->stock) ? $ProductStock->stock : $product->product_stock;
                        $variationOptions = explode(',', $ProductStock->variation_option);
                        $option = in_array('manage_stock', $variationOptions);

                        if ($option == true) {
                            $stock_status = $ProductStock->stock_order_status;
                        } else {
                            $stock_status = $product->stock_order_status;
                        }

                        if ($stock < $qty && $stock_status == 'not_allow') {
                            $return['status'] = 'error';
                            $return['variant_id'] = $ProductStock->id;
                            $return['message'] = __('Product has been reached max quantity.');
                        } else {
                            $sale_price = !empty($ProductStock->price) ? $ProductStock->price : $ProductStock->variation_price;

                            $variation_price = !empty($ProductStock->variation_price) ? $ProductStock->variation_price : $ProductStock->price;

                            $var_price = !empty($sale_price) ? $sale_price : 0;

                            $product_original_price = $product->original_price * $qty;
                            $product_final_price = $product->final_price * $qty;

                            // $product_original_price = $variation_price * $qty;
                            // $product_final_price = $var_price * $qty;
                            if ($option == true) {
                                $variat_stock = !empty($ProductStock->stock) ? $ProductStock->stock : 0;
                            }else{
                                $variat_stock = !empty($ProductStock->stock) ? $ProductStock->stock : $product->product_stock;
                            }
                            $data['theme_id'] =  $theme_id;
                            $data['store_id'] = $store->id;
                            $data['sub_total'] = $product_final_price;
                            $data['product_original_price'] = $product_original_price;
                            $cart_array  = Tax::TaxCount($data);

                            $return['sub_total'] = $product_final_price;
                            $return['product_original_price'] = $product_original_price;
                            $return['variant_id'] = $ProductStock->id;
                            $return['original_price'] = $cart_array['original_price'];
                            $return['final_price'] = $cart_array['final_price'];
                            $return['currency_name'] = $cart_array['currency_name'];
                            $return['currency'] = $cart_array['currency'];
                            $return['total_tax_price'] = $cart_array['total_tax_price'];
                            $return['enable_option_data'] = !empty($option) ? $option : '';
                            $return['stock'] = !empty($variat_stock) ? $variat_stock : 0;
                            $return['stock_status'] = !empty($ProductStock->stock_status) ? $ProductStock->stock_status : '';
                            $return['description'] = !empty($ProductStock->description) ? $ProductStock->description : $product->descripion;
                            $return['variant_name'] = !empty($variant_name) ? $variant_name : '';
                            return response()->json($return);
                        }
                    }


            } else {
            }
        } else {
            $return['status'] = 'error';
            $return['message'] = __('Whoops! Something went wrong.');
        }
        return response()->json($return);
    }

    public function searchProducts(Request $request)
    {
        $lastsegment = $request->session_key;
        $store_id = Store::where('id', getCurrentStore())->first();
        if ($request->ajax() && isset($lastsegment) && !empty($lastsegment)) {
            $output = "";
            if ($request->cat_id !== '' && $request->search == '') {
                if ($request->cat_id == '0') {
                    $products = Product::where('store_id', getCurrentStore())->where('theme_id', $store_id->theme_id)->get();
                } else {
                    $products = Product::where('maincategory_id', $request->cat_id)->where('store_id', getCurrentStore())->where('theme_id', $store_id->theme_id)->get();
                }
            } else {
                if ($request->cat_id == '0') {
                    $products = Product::where('name', 'LIKE', "%{$request->search}%")->where('store_id', getCurrentStore())->where('theme_id', $store_id->theme_id)->get();
                } else {
                    $products = Product::where('name', 'LIKE', "%{$request->search}%")->where('store_id', getCurrentStore())->where('theme_id', $store_id->theme_id)->Where('maincategory_id', $request->cat_id)->get();
                }
            }
            if (count($products) > 0) {
                foreach ($products as $key => $product) {
                    if (!empty($product->cover_image_path)) {
                        $image_url = get_file($product->cover_image_path, APP_THEME());
                    } else {
                        $image_url = ('uploads/cover_image_path') . '/default.jpg';
                    }

                    if ($product->variant_product != '1' ) {
                        if($product->track_stock == 0) {
                            $quantity = $product->stock_status;
                            if($product->stock_status == 'in_stock'){
                                $quantity = 'In Stock';
                            }elseif($product->stock_status == 'on_backorder'){
                                $quantity = 'On Backorder';
                            }else{
                                $quantity = 'Out of Stock';
                            }
                        } else {
                            $quantity = $product->product_stock . ' Qty';
                        }

                        if ($request->session_key == 'purchases') {
                            $productprice = $product->price != 0 ? $product->price : 0;
                        } else if ($request->session_key == 'pos_'.getCurrentStore()) {
                            $productprice = $product->price != 0 ? $product->price : 0;
                        } else {
                            $productprice = $product->price != 0 ? $product->price : $product->price;
                        }

                        $productprice = currency_format_with_sym($productprice, $store_id->id, $store_id->theme_id);
                        $output .= ' <div class="col-xxl-3 col-xl-3 col-lg-4 col-md-6 col-sm-6 col-xs-6 col-12">
                                    <div class="tab-pane fade show active toacart w-100" data-url="' . url('/addToCart/' . $product->id . '/' . $lastsegment) . '">
                                        <div class="position-relative card">
                                            <img alt="Image placeholder" src="' . asset($image_url) . '" class="card-image avatar hover-shadow-lg" style=" height: 6rem; width: 100%;">
                                            <div class="p-0 custom-card-body card-body d-flex ">
                                                <div class="card-body my-2 p-2 text-left card-bottom-content">
                                                <h6 class="mb-2 text-dark product-title-name">' . $product->name . '</h6>
                                                <small class="badge badge-primary mb-0">' . $productprice . '</small>
                                                <small class="top-badge badge badge-danger mb-0">' . $quantity . '</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> ';
                    } else {

                        $output .= ' <div class="col-xxl-3 col-xl-3 col-lg-4 col-md-6 col-sm-6 col-xs-6 col-12">
                                <div class="tab-pane fade show active toacart w-100" data-url="' . url('/pos/product-variant/' . $product->id . '/' . $lastsegment) . '" data-ajax-popup="true" data-size="lg" data-align="centered" data-title="Product Variant">
                                    <div class="position-relative card">
                                        <img alt="Image placeholder" src="' . asset($image_url) . '" class="card-image avatar hover-shadow-lg" style=" height: 6rem; width: 100%;">
                                        <div class="p-0 custom-card-body card-body d-flex ">
                                            <div class="card-body my-2 p-2 text-left card-bottom-content">
                                                <h6 class="mb-2 text-dark product-title-name">' . $product->name . '</h6>
                                                <small class="badge badge-primary mb-0">In Variant</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> ';
                    }

                }
                return Response($output);
            } else {
                $output = '<div class="card card-body col-12 text-center">
                    <h5>' . __("No Product Available") . '</h5>
                    </div>';
                return Response($output);
            }
        }
    }

    public function addToCart(Request $request, $id, $session_key, $variant_id =0)
    {
        $store_id = Store::where('id', getCurrentStore())->first();

        $product = Product::find($id);
        $settings = Utility::Seting();
        if (!$product) {
            return response()->json(
                [
                    'code' => 404,
                    'status' => 'Error',
                    'error' => __('This product is not found!'),
                ],
                404
            );
        }

        $productname = $product->name;

        $variant = null;
        $productquantity = $productprice = 0;
        if (isset($request->variants)) {
            $variant = ProductVariant::where('product_id', $id)->where('variant', $request->variants)->first();
            if ($variant) {
                $productquantity = $variant->stock;
                if ($session_key ==  'pos_'.getCurrentStore() && $productquantity <= 0) {
                    return response()->json(
                        [
                            'code' => 404,
                            'status' => 'Error',
                            'error' => __('This product is out of stock!'),
                        ],
                        404
                    );
                }

                if ($session_key == 'pos_'.getCurrentStore()) {

                    $productprice = $variant->price != 0 ? $variant->price : 0;
                } else {
                    $productprice = 0;
                }
            }
        } else {
            if ($product->track_stock == 0 && $product->stock_status == 'out_of_stock' ||
                ($product->track_stock != 0 && isset($settings['out_of_stock_threshold']) && ($product->product_stock < $settings['out_of_stock_threshold']) && $product->stock_order_status == 'not_allow') ||
                (!$product || ($session_key != 'pos_'.getCurrentStore() ))) {
                return response()->json(
                    [
                        'code' => 404,
                        'status' => 'Error',
                        'error' => __('This product is out of stock!'),
                    ],
                    404
                );
            }

            $productquantity = $product->product_stock;
            if ($session_key == 'pos_'.getCurrentStore()) {

                $productprice = $product->price != 0 ? $product->price : 0;
            } else {
                $productprice = 0;
            }
        }

        $originalquantity = (int) $productquantity;

        $Tax = Tax::where('id', $product->tax_id)->where('store_id', $store_id->id)->where('theme_id', $store_id->theme_id)->first();
        $tax_price = 0;
        $product_tax = '';
        if ($Tax && count($Tax->tax_methods()) > 0) {
            foreach ($Tax->tax_methods() as $mkey => $method) {
                $amount = $method->tax_rate * $productprice / 100;
                $tax_price += $amount;
                $cart_array['tax_info'][$mkey]["tax_name"] = $method->name;
                $cart_array['tax_info'][$mkey]["tax_type"] = $method->tax_rate;
                $cart_array['tax_info'][$mkey]["tax_amount"] = $amount;
                $product_tax .= !empty($method) ? "<span class='badge bg-primary'>" . $method->name . ' (' . $method->tax_rate . '%)' . "</span><br>" : '';
                $cart_array['tax_info'][$mkey]["id"] = $method->id;
                $cart_array['tax_info'][$mkey]["tax_price"] = SetNumber($amount);
            }
        }

        $subtotal = $productprice + $tax_price;
        $cart            = session()->get($session_key);
        if (!empty($product->cover_image_path)) {
            $image_url = get_file($product->cover_image_path, APP_THEME());
        } else {
            $image_url = ('uploads/is_cover_image') . '/default.jpg';
        }

        $model_delete_id = 'delete-form-' . $id;

        $carthtml = '';

        $carthtml .= '<tr data-product-id="' . $id . '" id="product-id-' . $id . '">
                        <td class="cart-images">
                            <img alt="Image placeholder" src="' . ($image_url) . '" class="card-image avatar shadow hover-shadow-lg">
                        </td>

                        <td class="name">' . $productname . '</td>

                        <td class="">
                                <span class="quantity buttons_added">
                                        <input type="button" value="-" class="minus">
                                        <input type="number" step="1" min="1" max="" name="quantity" title="' . __('Quantity') . '" class="input-number" size="4" data-url="' . url('update-cart/') . '" data-id="' . $id . '">
                                        <input type="button" value="+" class="plus">
                                </span>
                        </td>
                        <td class="tax">' . $product_tax . '</td>

                        <td class="price">' . (currency_format_with_sym( $productprice, $store_id->id, $store_id->theme_id) ?? SetNumberFormat($productprice)) . '</td>

                        <td class="total_orignal_price">' . (currency_format_with_sym( $subtotal, $store_id->id, $store_id->theme_id) ?? SetNumberFormat($subtotal)). '</td>

                        <td class="">
                            <form method="post" class="mb-0" action="' . route('remove-from-cart') . '"  accept-charset="UTF-8" id="' . $model_delete_id . '">
                            <button type="button" class="show_confirm btn btn-sm btn-danger p-2">
                            <span class=""><i class="ti ti-trash"></i></span>
                            </button>
                                <input name="_method" type="hidden" value="DELETE">
                                <input name="_token" type="hidden" value="' . csrf_token() . '">
                                <input type="hidden" name="session_key" value="' . $session_key . '">
                                <input type="hidden" name="id" value="' . $id . '">
                            </form>
                        </td>
                    </td>';
        // if cart is empty then this the first product

        if (!$cart) {
            $cart = [
                $id => [
                    "product_id" => $product->id,
                    "name" => $productname,
                    "image" => $product->cover_image_path,
                    "quantity" => 1,
                    "orignal_price" => $productprice,
                    "per_product_discount_price" => $product->discount_amount,
                    "discount_price" => $product->discount_amount,
                    "final_price" => $subtotal,
                    "id" => $id,
                    "tax" => $tax_price,
                    "total_orignal_price" => $subtotal,
                    "originalquantity" => $originalquantity,
                    'variant_id' => $variant->id ?? 0,
                    "variant_name" => $product->variant_attribute,
                    "return" => 0,
                ],
            ];

            if ((($product->track_stock != 0 && $originalquantity < $cart[$id]['quantity']) || ($product->track_stock == 0 && $product->stock_status == 'out_of_stock')) && $session_key != 'pos_'.getCurrentStore())
            {
                return response()->json(
                    [
                        'code' => 404,
                        'status' => 'Error',
                        'error' => __('This product is out of stock!'),
                    ],
                    404
                );
            }

            session()->put($session_key, $cart);

            return response()->json(
                [
                    'code' => 200,
                    'status' => 'Success',
                    'success' => $productname . __(' added to cart successfully!'),
                    'product' => $cart[$id],
                    'carthtml' => $carthtml,
                ]
            );
        }

        // if cart not empty then check if this product exist then increment quantity
        if (isset($cart[$id])) {
            $cartProduct = Product::find($id);
            $cart[$id]['quantity']++;
            $cart[$id]['id'] = $id;

            $subtotal = $cart[$id]["orignal_price"] * $cart[$id]["quantity"];
            $tax = 0;
            $taxes            = !empty($cart[$id]["tax"]) ? $cart[$id]["tax"] : '';


            $Tax = Tax::where('id', $cartProduct->tax_id)->where('store_id', $store_id->id)->where('theme_id', $store_id->theme_id)->first();
            $tax_price = 0;
            $product_tax = '';
            $price = $cart[$id]["orignal_price"] * $cart[$id]["quantity"];
            if ($Tax && count($Tax->tax_methods()) > 0) {
                foreach ($Tax->tax_methods() as $mkey => $method) {
                    $amount = $method->tax_rate * $price / 100;
                    $tax_price += $amount;
                    $cart_array['tax_info'][$mkey]["tax_name"] = $method->name;
                    $cart_array['tax_info'][$mkey]["tax_type"] = $method->tax_rate;
                    $cart_array['tax_info'][$mkey]["tax_amount"] = $amount;
                    $product_tax .= !empty($method) ? "<span class='badge bg-primary'>" . $method->name . ' (' . $method->tax_rate . '%)' . "</span><br>" : '';
                    $cart_array['tax_info'][$mkey]["id"] = $method->id;
                    $cart_array['tax_info'][$mkey]["tax_price"] = SetNumber($amount);
                }
            }

            if (!empty($taxes)) {
                $productprice          = $cart[$id]["orignal_price"] *  (float)$cart[$id]["quantity"];
                $subtotal = $productprice +  $tax_price;
            } else {

                $productprice          = $cart[$id]["orignal_price"];
                $subtotal = $productprice  *  (float)$cart[$id]["quantity"];
            }
            $cart[$id]["total_orignal_price"] = $subtotal;

            $cart[$id]["total_orignal_price"]         = $subtotal + $tax;
            $cart[$id]["originalquantity"] = $originalquantity;
            $cart[$id]["tax"]      = $tax_price;
            if ((($product->track_stock != 0 && $originalquantity < $cart[$id]['quantity']) || ($product->track_stock == 0 && $product->stock_status == 'out_of_stock')) && $session_key != 'pos_'.getCurrentStore()) {
                return response()->json(
                    [
                        'code' => 404,
                        'status' => 'Error',
                        'error' => __('This product is out of stock!'),
                    ],
                    404
                );
            }

            session()->put($session_key, $cart);

            return response()->json(
                [
                    'code' => 200,
                    'status' => 'Success',
                    'success' => $productname . __(' added to cart successfully!'),
                    'product' => $cart[$id],
                    'carttotal' => $cart,
                ]
            );
        }

        // if item not exist in cart then add to cart with quantity = 1
        $cart[$id] = [
            "product_id" => $product->id,
            "name" => $productname,
            "image" => $product->cover_image_path,
            "quantity" => 1,
            "orignal_price" => $productprice,
            "per_product_discount_price" => $product->discount_amount,
            "discount_price" => $product->discount_amount,
            "final_price" => $subtotal,
            "id" => $id,
            "tax" => $tax_price,
            "total_orignal_price" => $subtotal,
            "originalquantity" => $originalquantity,
            'variant_id' => $variant->id ?? 0,
            "variant_name" => $product->variant_attribute,
            "return" => 0,
        ];
        if ((($product->track_stock != 0 && $originalquantity < $cart[$id]['quantity']) || ($product->track_stock == 0 && $product->stock_status == 'out_of_stock')) && $session_key != 'pos_'.getCurrentStore()) {
            return response()->json(
                [
                    'code' => 404,
                    'status' => 'Error',
                    'error' => __('This product is out of stock!'),
                ],
                404
            );
        }

        session()->put($session_key, $cart);
        return response()->json(
            [
                'code' => 200,
                'status' => 'Success',
                'success' => $productname . __(' added to cart successfully!'),
                'product' => $cart[$id],
                'carthtml' => $carthtml,
                'carttotal' => $cart,
            ]
        );
    }

    public function updateCart(Request $request)
    {
        $id          = $request->id;
        $quantity    = $request->quantity;
        $discount    = $request->discount;
        $session_key = $request->session_key;
        $store_id = Store::where('id', getCurrentStore())->first();

        if ($request->ajax() && isset($id) && !empty($id) && isset($session_key) && !empty($session_key)) {
            $cart = session()->get($session_key);

            if (isset($cart[$id]) && $quantity == 0) {
                unset($cart[$id]);
            }

            if ($quantity) {

                $cart[$id]["quantity"] = $quantity;
                $taxes            = !empty($cart[$id]["tax"]) ? $cart[$id]["tax"] : '';

                $price = $cart[$id]["orignal_price"] * $quantity;

                $product = Product::where('id', $id)->first();
                $Tax = Tax::where('store_id', $store_id->id)->where('id', $product->tax_id)->where('theme_id', $store_id->theme_id)->first();
                $tax_price = 0;
                $product_tax = '';
                if ($Tax) {
                    if (count($Tax->tax_methods()) > 0) {
                        foreach ($Tax->tax_methods() as $mkey => $method) {
                            $tax_price += $method->tax_rate * $price / 100;
                            // $cart_array['tax_info'][$mkey]['name'] = $method->name;
                            // $cart_array['tax_info'][$mkey]['tax_rate'] = $method->tax_rate;
                            // $cart_array['tax_info'][$mkey]["tax_name"] = $method->name;
                            // $cart_array['tax_info'][$mkey]["tax_amount"] = $tax_price;

                            // $cart_array['tax_info'][$mkey]["id"] = $Tax->id;
                            // $cart_array['tax_info'][$mkey]["tax_string"] = $method->name.' ('.$method->tax_rate.'%)';
                            // $cart_array['tax_info'][$mkey]["tax_price"] = SetNumber($tax_price);
                            $product_tax .= !empty($Tax) ? "<span class='badge bg-primary'>" . $method->name . ' (' . $method->tax_rate . '%)' . "</span><br>" : '';
                        }
                    }
                }

                $subtotal = $price + $tax_price;
                $cart[$id]["tax"] = $tax_price;
                $producttax = 0;
                if (!empty($taxes)) {
                    $productprice          = $cart[$id]["orignal_price"] *  (float)$quantity;
                    $subtotal = $productprice +  $tax_price;
                } else {
                    $productprice          = $cart[$id]["orignal_price"];
                    $subtotal = ($productprice  *  (float) $quantity) + $tax_price;
                }

                $cart[$id]["total_orignal_price"] = $subtotal;
            }

            if (isset($cart[$id]) && isset($cart[$id]["originalquantity"]) < $cart[$id]['quantity'] && $session_key == 'pos_'.getCurrentStore()) {
                return response()->json(
                    [
                        'code' => 404,
                        'status' => 'Error',
                        'error' => __('This product is out of stock!'),
                    ],
                    404
                );
            }

            $subtotal = array_sum(array_column($cart, 'total_orignal_price'));
            $discount = $request->discount;
            $total = $subtotal - (float)$discount;
            $totalDiscount = currency_format_with_sym( $total, $store_id->id, $store_id->theme_id) ?? SetNumberFormat($total);
            $discount = $totalDiscount;


            session()->put($session_key, $cart);
            return response()->json(
                [
                    'code' => 200,
                    'success' => __('Cart updated successfully!'),
                    'product' => $cart,
                    'discount' => $discount,
                ]
            );
        } else {
            return response()->json(
                [
                    'code' => 404,
                    'status' => 'Error',
                    'error' => __('This Product is not found!'),
                ],
                404
            );
        }
    }

    public function removeFromCart(Request $request)
    {
        $id          = $request->id;
        $session_key = $request->session_key;
        if (isset($id) && !empty($id) && isset($session_key) && !empty($session_key)) {
            $cart = session()->get($session_key);
            if (isset($cart[$id])) {
                unset($cart[$id]);
                session()->put($session_key, $cart);
            }
            return redirect()->back()->with('success', __('Product removed from cart!'));
        } else {
            return redirect()->back()->with('error', __('This Product is not found!'));
        }
    }

    public function emptyCart(Request $request)
    {
        $session_key = $request->session_key;

        if (isset($session_key) && !empty($session_key)) {
            $cart = session()->get($session_key);
            if (isset($cart) && count($cart) > 0) {
                session()->forget($session_key);
            }

            return redirect()->back()->with('error', __('Cart is empty!'));
        } else {
            return redirect()->back()->with('error', __('Cart cannot be empty!.'));
        }
    }

    public function productVariant(Request $request, $id, $session_key)
    {
        $product = Product::where('id', $id)->first();
        $product_variant_names = ProductVariant::where('product_id', $product->id)->get();

        return view('pos.product_variant', compact('product', 'product_variant_names', 'session_key'));
    }

    public function getProductsVariantQuantity(Request $request)
    {
        $status = false;
        $quantity = $variant_id = 0;
        $quantityHTML = '<strong>' . __('Please select variants to get available quantity.') . '</strong>';
        $priceHTML = '';
        $product = Product::find($request->product_id);
        $price = currency_format_with_sym($product->price, getCurrentStore(), APP_THEME());
        //dd($request->variants);
        $status = false;

        if ($product && $request->variants != '') {
            $variant = ProductVariant::where('product_id', $product['id'])->where('variant', $request->variants)->first();
            if ($variant) {
                $status = true;
                $quantity = $variant->stock - (isset($cart[$variant->id]['quantity']) ? $cart[$variant->id]['quantity'] : 0);
                $price = currency_format_with_sym($variant->price, getCurrentStore(), APP_THEME());
                $variant_id = $variant->id;
            }
        }

        return response()->json(
            [
                'status' => $status,
                'price' => $price,
                'quantity' => $quantity,
                'variant_id' => $variant_id
            ]
        );

    }

    public function VariantDelete(Request $request, $id, $product_id)
    {
        // if(\Auth::user()->can('Delete Variants')){
            $product = Product::find($product_id);
            if (!empty($product->variants_json) && ProductVariantOption::find($id)->exists()) {
                $var_json = json_decode($product->variants_json, true);

                $i = 0;
                foreach ($var_json[0] as $key => $value) {
                    $var_ops = explode(' : ', ProductVariantOption::find($id)->name);
                    $count = ProductVariantOption::where('product_id', $product->id)->where('name', 'LIKE', '%' . $var_ops[0] . '%')->count();
                    if ($count == 1 && $i == 0) {
                        $unsetIndex = array_search($var_ops[0], $var_json[0]['variant_options'], true);
                        unset($var_json[0]['variant_options'][$unsetIndex]);
                    }
                    $i++;
                }
                $variants = ProductVariantOption::where('product_id',$product->id)->count();
                if($variants == 1){
                    $product->variants_json = '{}';
                    $product->update();
                }else{
                    $product->variants_json = json_encode($var_json);
                    $product->update();
                }

            }
            ProductVariantOption::find($id)->delete();
            return redirect()->back()->with('success', __('Variant successfully deleted.'));
        // }
        // else{
        //     return redirect()->back()->with('error', 'Permission denied.');
        // }
    }
}
