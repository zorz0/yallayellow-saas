<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    public function update(Request $request, Product $product)
    {
        if (auth()->user()->isAbleTo('Edit Products')) {
            $ThemeSubcategory = Utility::ThemeSubcategory();

            $dir        = 'themes/' . APP_THEME() . '/uploads';

            $rules = [
                'name' => 'required',
                'category_id' => 'required',
                // 'discount_type' => 'required',
                // 'discount_amount' => 'required',
                'status' => 'required',
                'variant_product' => 'required'
            ];

            $validator = \Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            // description array
            $old_data = '';
            if (!empty($product->other_description_api)) {
                $old_data = json_decode($product->other_description_api, true);
            }


            // Tag Array
            $tag_array = !empty($request->tag) ? $request->tag : [];
            $tag_array_api = '';
            if (!empty($request->tag)) {
                foreach ($request->tag as $array_key => $slug) {
                    $tag_array_api = $slug['value'];
                }
            }


            $product->name = $request->name;
            $product->description = $request->description;

            $product->other_description = json_encode($array);
            $product->other_description_api = json_encode($array_api);
            $product->tag = !empty($tag_array) ? json_encode($tag_array) : '';
            $product->tag_api = $tag_array_api;
            $product->stock_status = $request->stock_status;
            $product->product_weight = $request->product_weight;

            $product->category_id = $request->category_id;
            if ($ThemeSubcategory == 1) {
                $product->subcategory_id = $request->subcategory_id;
            }

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
                    Utility::changeStorageLimit(\Auth::user()->creatorId(), $file_paths);
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
                // $product->save();
            }
            if (!empty($request->preview_iframe)) {
                $product->preview_content = $request->preview_iframe;
                // $product->save();
            }



            $product->discount_type = !empty($request->discount_type) ? $request->discount_type : '';
            $product->discount_amount = !empty($request->discount_amount) ? $request->discount_amount : '0';
            $product->variant_product = $request->variant_product;
            $product->slug = $request->name;
            if (!empty($request->arrays)) {

                $product->product_option = json_encode($option_array);
                $product->product_option_api = json_encode($option_array_api);
            }
            $product->shipping_id = $request->shipping_id;
            $product->status = $request->status;
            $product->trending = $request->trending;
            if ($request->track_stock == 1) {

                $product->track_stock = $request->track_stock;
                $product->stock_order_status = $request->stock_order_status;
                $product->low_stock_threshold = !empty($request->low_stock_threshold) ? $request->low_stock_threshold :  '';
            } else {
                $product->track_stock = $request->track_stock;
                $product->stock_order_status = '';
                $product->low_stock_threshold = !empty($request->low_stock_threshold) ? $request->low_stock_threshold :  '';
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

                } else {
                    return redirect()->back()->with('error', $result);
                }
                $product->downloadable_product = $path['url'];
            }

            if ($request->variant_product == 0) {
                $product->price = $request->price;

                if ($request->track_stock == 0) {
                    $product->product_stock = 0;
                } else {
                    $product->product_stock = $request->product_stock;
                }
                $product->variant_id = NULL;
                $product->variant_attribute = NULL;

                $input = $request->all();

                $input['attribute_options'] = [];
                if ($request->has('attribute_no')) {
                    foreach ($request->attribute_no as $key => $no) {
                        $str = 'attribute_options_' . $no;
                        $enable_option = $input['visible_attribute_' . $no];
                        $variation_option = $input['for_variation_' . $no];

                        $item['attribute_id'] = $no;

                        $optionValues = [];
                        foreach ($request[$str] as $fValue) {

                            $id = ProductAttributeOption::where('terms', $fValue)->first()->toArray();
                            $optionValues[] = $id['id'];
                        }

                        $item['values'] = explode(',', implode('|', $optionValues));
                        $item['visible_attribute_' . $no] = $enable_option;
                        $item['for_variation_' . $no] = $variation_option;
                        array_push($input['attribute_options'], $item);
                    }
                }

                if (!empty($request->attribute_no)) {
                    $input['product_attributes'] = json_encode($request->attribute_no);
                } else {
                    $input['product_attributes'] = json_encode([]);
                }
                $input['attribute_options'] = json_encode($input['attribute_options']);
                $product->attribute_id = $input['product_attributes'];
                $product->product_attribute = $input['attribute_options'];

                Cart::where('product_id', $product->id)->where('theme_id', $product->theme_id)->where('store_id', $product->store_id)->delete();
                $product->save();

                // ProductStock::where('product_id', $product->id)->where('theme_id', APP_THEME())->delete();
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
                        foreach ($request[$str] as $fValue) {

                            $id = ProductAttributeOption::where('terms', $fValue)->first()->toArray();
                            $optionValues[] = $id['id'];
                        }

                        $item['values'] = explode(',', implode('|', $optionValues));
                        $item['visible_attribute_' . $no] = $enable_option;
                        $item['for_variation_' . $no] = $variation_option;
                        array_push($input['attribute_options'], $item);
                    }
                }

                if (!empty($request->attribute_no)) {
                    $input['product_attributes'] = json_encode($request->attribute_no);
                } else {
                    $input['product_attributes'] = json_encode([]);
                }
                $input['attribute_options'] = json_encode($input['attribute_options']);

                $product->price = 0;
                $product->product_stock = 0;
                $product->variant_id = $input['attributes'];
                $product->variant_attribute = $input['choice_options'];
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
                    // $product->save();
                }
                if (!empty($request->preview_iframe)) {
                    $product->preview_content = $request->preview_iframe;
                    // $product->save();
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
                    $product->save();
                } else {
                    $product->variant_product = 0;
                }

                $attribute_option = [];
                // if ($request->has('attribute_no')) {
                //     foreach ($request->attribute_no as $key => $no) {
                //         $name = 'attribute_options_' . $no;
                //         $my_str =  $request[$name];
                //         array_push($option,$my_str);
                //     }
                // }
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
                // $a_combinations = $this->combination($a_option);
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

                                if ($product->track_stock == 1) {
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

            $Carts = Cookie::get('cart');
            $Carts = json_decode($Carts, true);

            // Iterate through the cart items and remove items with matching product ID
            if (is_array($Carts)) {
                foreach ($Carts as $cartId => $cartItem) {
                    if ($cartItem['product_id'] == $product->id) {
                        unset($Carts[$cartId]);
                    }
                }
            }
            $cart_json = json_encode($Carts);
            Cookie::queue('cart', $cart_json, 60);


            Cart::where('product_id', $product->id)->where('theme_id', $product->theme_id)->where('store_id', $product->store_id)->delete();

            $firebase_enabled = Utility::GetValueByName('firebase_enabled');
            if (!empty($firebase_enabled) && $firebase_enabled == 'on') {
                $fcm_Key = Utility::GetValueByName('fcm_Key');
                if (!empty($fcm_Key)) {
                    $NotifyUsers = DB::table('NotifyUser')->where('product_id', $request->id)->get();
                    if (!empty($NotifyUsers)) {
                        foreach ($NotifyUsers as $key => $value) {
                            $User_data = User::find($value->user_id);
                            if (!empty($User_data->firebase_token)) {
                                $device_id = $User_data->firebase_token;
                                $message = 'now ' . $request->name . ' is available in stock';
                                Utility::sendFCM($device_id, $fcm_Key, $message);
                                DB::table('NotifyUser')->where('product_id', $request->id)->where('user_id', $User_data->id)->delete();
                            }
                        }
                    }
                }
            }


            return redirect()->back()->with('success', __('Product update successfully.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
