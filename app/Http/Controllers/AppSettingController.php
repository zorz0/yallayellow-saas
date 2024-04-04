<?php

namespace App\Http\Controllers;

use App\Models\AppSetting;
use App\Models\Setting;
use App\Models\Plan;
use App\Models\Store;
use App\Models\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class AppSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // if(auth()->user()->isAbleTo('Manage Store Setting'))
        // {
            $setting = getAdminAllSetting();
            $user = Auth::user();
            $store = Store::find($user->current_store);
            $slug = $store->slug;
            $plan = Plan::find($user->plan_id);
            $theme_id = !empty(APP_THEME()) ? APP_THEME() : 'grocery';
		    $serverIp = $subdomain_name = $subdomain_Ip = $subdomainPointing = $domainip = $domainPointing = null;
            if($setting) {
                if(isset($setting['domains']) && $setting['domains']) {
                    $serverIp   = $_SERVER['SERVER_ADDR'];
                    $domain = $setting['domains'];
                    if (isset($domain) && !empty($domain)) {
                        $domainip = gethostbyname($domain);
                    }
                    if ($serverIp == $domainip) {
                        $domainPointing = 1;
                    } else {
                        $domainPointing = 0;
                    }
                } else {
                    $serverIp   = $_SERVER['SERVER_ADDR'];
                    $domain = $serverIp;
                    $domainip = gethostbyname($domain);
                    $domainPointing = 0;
                }
                $serverName = str_replace(
                    [
                        'http://',
                        'https://',
                    ],
                    '',
                    env('APP_URL')
                );
                $serverIp   = gethostbyname($serverName);
    
                if ($serverIp == $_SERVER['SERVER_ADDR']) {
                    $serverIp;
                } else {
                    $serverIp = request()->server('SERVER_ADDR');
                }
    
                $app_url                     = trim(env('APP_URL'), '/');
    
                $store_settings['store_url'] = $app_url . '/' . $slug;
                // Remove the http://, www., and slash(/) from the URL
                $input = env('APP_URL');
    
                // If URI is like, eg. www.way2tutorial.com/
                $input = trim($input, '/');
                // If not have http:// or https:// then prepend it
                if (!preg_match('#^http(s)?://#', $input)) {
                    $input = 'http://' . $input;
                }
                $urlParts = parse_url($input);
    
                $serverIp   = $_SERVER['SERVER_ADDR'];
    
                if (!empty($setting['subdomain']) || !empty($urlParts['host'])) {
                    $subdomain_Ip   = gethostbyname($urlParts['host']);
                    if ($serverIp == $subdomain_Ip) {
                        $subdomainPointing = 1;
                    } else {
                        $subdomainPointing = 0;
                    }
                    // Remove www.
                    $subdomain_name = preg_replace('/^www\./', '', $urlParts['host']);
                } else {
                    $subdomain_Ip = $urlParts['host'];
                    $subdomainPointing = 0;
                    $subdomain_name = str_replace(
                        [
                            'http://',
                            'https://',
                        ],
                        '',
                        env('APP_URL')
                    );
                }
            }
           

            // Order Complete page
            $json_data_path = base_path('theme_json/order-complete.json');
            $json_data = json_decode(file_get_contents($json_data_path), true);

            $setting_json_data = AppSetting::select('theme_json')
                ->where('theme_id', $theme_id)
                ->where('page_name', 'order_complate')
                ->where('store_id', getCurrentStore())
                ->first();
            if(!empty($setting_json_data)) {
                $json_data = json_decode($setting_json_data->theme_json, true);
            }

            return view('AppSetting.index',compact('setting','plan','json_data','slug','serverIp','subdomain_name','subdomain_Ip','subdomainPointing','domainip','domainPointing'));
        // }
        // else
        // {
        //     return redirect()->back()->with('error', __('Permission denied.'));
        // }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
		$theme_id = APP_THEME();
        $json = $request->array;
        $array = $request->array;
        $dir        = 'themes/'.APP_THEME().'/uploads';
        $new_array = [];
        foreach ($array as $key => $jsn) {
            foreach ($jsn['inner-list'] as $IN_key => $js) {
                $new_array[$jsn['section_slug']][$js['field_slug']] = $js['field_default_text'];
                if($js['field_type'] == 'multi file upload') {
                    if(!empty($js['multi_image'])) {
                        foreach ($js['multi_image'] as $key_file => $file) {
                            $theme_name = $theme_id;
                            $theme_image = $file;
                            // $upload = upload_theme_image($theme_name, $theme_image, $key_file);

                            $fileName = rand(10,100).'_'.time() . "_" . $theme_image->getClientOriginalName();
                            $upload = Utility::upload_file($request,$theme_image,$fileName,$dir,[], $theme_image);

                            $img_path = '';
                            if( !empty($upload['flag']) && $upload['flag'] == 1)
                            {
                                $img_path = $upload['image_path'];
                            }
                            $array[$key]["inner-list"][$IN_key]['image_path'][] = $img_path;
                            $array[$key][$js['field_slug']][$key_file]['image'] = $img_path;
                            $array[$key][$js['field_slug']][$key_file]['field_prev_text'] = $img_path;
                        }

                        $next_key_p_image = !empty($key_file) ? $key_file : 0;
                        if(!empty($jsn['prev_image'])) {
                            foreach ($jsn['prev_image'] as $p_key => $p_value) {
                                $next_key_p_image = $next_key_p_image + 1;
                                $array[$key][$js['field_slug']][$next_key_p_image]['image'] = $p_value;
                                $array[$key][$js['field_slug']][$next_key_p_image]['field_prev_text'] = $p_value;
                            }
                        }
                    } else {
                        if(!empty($jsn['prev_image'])) {
                            foreach ($jsn['prev_image'] as $p_key => $p_value) {
                                $array[$key][$js['field_slug']][$p_key]['image'] = $p_value;
                                $array[$key][$js['field_slug']][$p_key]['field_prev_text'] = $p_value;
                            }
                        }
                    }
                }
                if($js['field_type'] == 'photo upload')
                {
                    if ($jsn['array_type'] == 'multi-inner-list')
                    {
                        $k = 0;
                        $img_path_multi = [];
                        for ($i = 0; $i < $jsn['loop_number']; $i++) {
                            $img_path_multi[$i] = '';
                            if(empty($array[$key][$js['field_slug']][$i]['field_prev_text'])) {
                                $array[$key][$js['field_slug']][$i]['field_prev_text'] = $js['field_default_text'];
                                $img_path_multi[$i] = $js['field_default_text'];
                            }else{
                                $img_path_multi[$i] = $array[$key][$js['field_slug']][$i]['field_prev_text'];
                            }
                            if (!empty($array[$key][$js['field_slug']][$i]['image']) && gettype($array[$key][$js['field_slug']][$i]['image']) == 'object')
                            {
                                $theme_name = $theme_id;
                                $theme_image = $array[$key][$js['field_slug']][$i]['image'];
                                // $upload = upload_theme_image($theme_name, $theme_image, $i);

                                $fileName = rand(10,100).'_'.time() . "_" . $theme_image->getClientOriginalName();
                                $upload = Utility::upload_file($request,$theme_image,$fileName,$dir,[], $theme_image);

                                $img_path = '';
                                if( !empty($upload['flag']) && $upload['flag'] == 1)
                                {
                                    $img_path = $upload['image_path'];
                                }
                                $array[$key][$js['field_slug']][$i]['image'] = $img_path;
                                $array[$key][$js['field_slug']][$i]['field_prev_text'] = $img_path;
                                $img_path_multi[$i] = $img_path;
                            }
                        }
                        $new_array[$jsn['section_slug']][$js['field_slug']] = $img_path_multi;
                    }
                    else
                    {
                        if (gettype($js['field_default_text']) == 'object') {
                            $theme_name = $theme_id;
                            $theme_image = $js['field_default_text'];
                            // $upload = upload_theme_image($theme_name, $theme_image);


                            $fileName = rand(10,100).'_'.time() . "_" . $theme_image->getClientOriginalName();
                            $upload = Utility::upload_file($request,$theme_image,$fileName,$dir,[], $theme_image);

                            $img_path = '';
                            if( !empty($upload['flag']) && $upload['flag'] == 1)
                            {
                                $img_path = $upload['image_path'];
                            }
                            $array[$key]['inner-list'][$IN_key]['field_default_text'] = $img_path;
                            $new_array[$jsn['section_slug']][$js['field_slug']] = $img_path;
                        }
                    }
                }
            }
        }
        AppSetting::updateOrInsert(
            ['theme_id' => $theme_id, 'page_name' => 'main', 'store_id' => getCurrentStore()], // Where condition
            ['theme_id' => $theme_id, 'page_name' => 'main','store_id' => getCurrentStore(), 'theme_json' => json_encode($array), 'theme_json_api' => json_encode($new_array)]   // Update or Insert
        );

        return redirect()->back()->with('success', __('App setting set successfully.'));
    }

    /**
     * Display the specified resource.
     */
    public function show(AppSetting $appSetting)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AppSetting $appSetting)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AppSetting $appSetting)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AppSetting $appSetting)
    {
        //
    }

    public function seoSettings(Request $request)
    {
        
        $theme_id = !empty(APP_THEME()) ? APP_THEME() : 'grocery';
        $validator = \Validator::make(
            $request->all(),
            [
                'google_analytic'   => 'required',
                'fbpixel_code'      => 'required',
                'metakeyword'       => 'required',
                'metadesc'          => 'required',
            ]
        );
        $post = $request->all();
        unset($post['_token']);

        $post['google_analytic']    = $request->google_analytic;
        $post['fbpixel_code']       = $request->fbpixel_code;
        $post['metakeyword']        = $request->metakeyword;
        $post['metadesc']           = $request->metadesc;

        $dir = 'themes/' . APP_THEME() . '/uploads';
        if ($request->metaimage) {
            $theme_image = $request->metaimage;
            $fileName = rand(10, 100) . '_' . time() . "_" . $request->metaimage->getClientOriginalName();
            $path = Utility::upload_file($request, 'metaimage', $fileName, $dir, []);

            if ($path['flag'] == '0') {
                return redirect()->back()->with('error', $path['msg']);
            } else {
                $where = ['name' => 'metaimage', 'theme_id' => $theme_id];
                $Setting = Setting::where($where)->first();

                if (!empty($Setting)) {
                    if (File::exists(base_path($Setting->value))) {
                        File::delete(base_path($Setting->value));
                    }
                }

                $post['metaimage'] = $path['url'];

            }
        }
        $settingQuery = Setting::query();
        foreach ($post as $key => $data) {
            (clone $settingQuery)->updateOrCreate(
                ['name' => $key,
                'theme_id'      => APP_THEME(),
                'store_id'      => getCurrentStore()
            ],
                [
                    'value'         => $data,
                    'name'          => $key,
                    'theme_id'      => APP_THEME(),
                    'store_id'      => getCurrentStore(),
                    'created_by'    => \Auth::user()->id,
                ]
            );
        }

        return redirect()->back()->with('success', 'Seo setting successfully saved.');
    }

    public function shippingLabelSettings(Request $request)
    {
        
        $theme_id = !empty(APP_THEME()) ? APP_THEME() : 'grocery';
        $validator = \Validator::make(
            $request->all(),
            [
                'store_address'   => 'required',
                'store_city'      => 'required',
                'store_state'       => 'required',
                'store_zipcode'          => 'required',
                'store_country'          => 'required',
            ]
        );
        $post = $request->all();
        unset($post['_token']);


        $post['store_address']    = $request->store_address;
        $post['store_city']       = $request->store_city;
        $post['store_state']        = $request->store_state;
        $post['store_zipcode']           = $request->store_zipcode;
        $post['store_country']           = $request->store_country;
        $settingQuery = Setting::query();
        foreach ($post as $key => $data) {
            (clone $settingQuery)->updateOrCreate(
                ['name' => $key,
                'theme_id'      => APP_THEME(),
                'store_id'      => getCurrentStore()
            ],
                [
                    'value'         => $data,
                    'name'          => $key,
                    'theme_id'      => APP_THEME(),
                    'store_id'      => getCurrentStore(),
                    'created_by'    => \Auth::user()->id,
                ]
            );
        }

        return redirect()->back()->with('success', 'Shipping Label setting successfully saved.');
    }

    public function product_page_setting(Request $request)
    {
        $theme_id = !empty(APP_THEME()) ? APP_THEME() : 'grocery';
        $page_name = $request->section_name ?? ( $request->page_name ?? null);
        $dir        = 'themes/'.APP_THEME().'/uploads';
        if(empty($page_name)) {
            return redirect()->back()->with('error', __('Page name not found.'));
        }

        $array = $request->array;
        if($page_name == 'home_page_web') {
            $array = $request->array;
        }
        $decodedData = json_encode($array, true);
        $new_array = [];
        foreach ($array as $key => $jsn) {
            if (isset($jsn['inner-list'])) {
                foreach ($jsn['inner-list'] as $IN_key => $js) {
                    $new_array[$jsn['section_slug']][$js['field_slug']] = $js['field_default_text'];
                    if($js['field_type'] == 'multi file upload') {
                        if(!empty($js['multi_image'])) {
                            foreach ($js['multi_image'] as $key_file => $file) {
                                $theme_name = $theme_id;
                                $theme_image = $file;
                                // $upload = upload_theme_image($theme_name, $theme_image, $key_file);
    
                                $fileName = rand(10,100).'_'.time() . "_" . $theme_image->getClientOriginalName();
                                $upload = Utility::upload_file($request,$theme_image,$fileName,$dir,[], $theme_image);
    
                                $img_path = '';
                                if( !empty($upload['flag']) && $upload['flag'] == 1)
                                {
                                    $img_path = $upload['image_path'];
                                }
                                $array[$key][$js['field_slug']][$key_file]['image'] = $img_path;
                                $array[$key][$js['field_slug']][$key_file]['field_prev_text'] = $img_path;
                            }
    
                            $next_key_p_image = !empty($key_file) ? $key_file : 0;
                            if(!empty($jsn['prev_image'])) {
                                foreach ($jsn['prev_image'] as $p_key => $p_value) {
                                    $next_key_p_image = $next_key_p_image + 1;
                                    $array[$key][$js['field_slug']][$next_key_p_image]['image'] = $p_value;
                                    $array[$key][$js['field_slug']][$next_key_p_image]['field_prev_text'] = $p_value;
                                }
                            }
                        } else {
                            if(!empty($jsn['prev_image'])) {
                                foreach ($jsn['prev_image'] as $p_key => $p_value) {
                                    $array[$key][$js['field_slug']][$p_key]['image'] = $p_value;
                                    $array[$key][$js['field_slug']][$p_key]['field_prev_text'] = $p_value;
                                }
                            }
                        }
                    }
                    if($js['field_type'] == 'photo upload')
                    {
                        if ($jsn['array_type'] == 'multi-inner-list')
                        {
                            $k = 0;
                            $img_path_multi = [];
                            for ($i = 0; $i < $jsn['loop_number']; $i++) {
                                $img_path_multi[$i] = '';
                                if(empty($array[$key][$js['field_slug']][$i]['field_prev_text'])) {
                                    $array[$key][$js['field_slug']][$i]['field_prev_text'] = $js['field_default_text'];
                                    $img_path_multi[$i] = $js['field_default_text'];
                                }
                                if (!empty($array[$key][$js['field_slug']][$i]['image']) && gettype($array[$key][$js['field_slug']][$i]['image']) == 'object')
                                {
                                    $theme_name = $theme_id;
                                    $theme_image = $array[$key][$js['field_slug']][$i]['image'];
    
                                    $image_size = File::size($theme_image);
                                    $result = Utility::updateStorageLimit(\Auth::user()->creatorId(), $image_size);
                                    if ($result == 1)
                                    {
                                        $fileName = rand(10,100).'_'.time() . "_" . $theme_image->getClientOriginalName();
                                        $upload = Utility::upload_file($request,$theme_image,$fileName,$dir,[], $theme_image);
                                        $img_path = '';
                                        if( !empty($upload['flag']) && $upload['flag'] == 1)
                                        {
                                            $img_path = $upload['image_path'];
                                        }
                                    }
                                    else{
                                        return redirect()->back()->with('error', $result);
                                    }
    
                                    // $upload = upload_theme_image($theme_name, $theme_image, $i);
                                    $array[$key][$js['field_slug']][$i]['image'] = $img_path;
                                    $array[$key][$js['field_slug']][$i]['field_prev_text'] = $img_path;
                                    $img_path_multi[$i] = $img_path;
                                }
                            }
                            $new_array[$jsn['section_slug']][$js['field_slug']] = $img_path_multi;
                        }
                        else
                        {
                            if (gettype($js['field_default_text']) == 'object') {
                                $theme_name = $theme_id;
                                $theme_image = $js['field_default_text'];
    
                                $image_size = File::size($theme_image);
                                $result = Utility::updateStorageLimit(\Auth::user()->creatorId(), $image_size);
                                if ($result == 1)
                                {
                                    $fileName = rand(10,100).'_'.time() . "_" . $theme_image->getClientOriginalName();
                                    $upload = Utility::upload_file($request,$theme_image,$fileName,$dir,[], $theme_image);
                                    // $upload = upload_theme_image($theme_name, $theme_image);
                                    $img_path = '';
                                    if( !empty($upload['flag']) && $upload['flag'] == 1)
                                    {
                                        $img_path = $upload['image_path'];
                                    }
                                }
                                else{
                                    return redirect()->back()->with('error', $result);
                                }
    
                                $array[$key]['inner-list'][$IN_key]['field_default_text'] = $img_path;
                                $new_array[$jsn['section_slug']][$js['field_slug']] = $img_path;
                            }
                        }
                    }
                }
            }
            
        }
        
        // dd($array);
        AppSetting::updateOrInsert(
            ['theme_id' => $theme_id, 'page_name' => $page_name, 'store_id' => getCurrentStore() ], // Where condition
            ['theme_id' => $theme_id, 'page_name' => $page_name,'store_id' => getCurrentStore(), 'theme_json' => json_encode($array), 'theme_json_api' => json_encode($new_array)]   // Update or Insert
        );

        return redirect()->back()->with('success', __('App setting set successfully.'));
    }

    public function ThemeSettings(Request $request)
    {
        //$store = Store::find(auth()->user()->current_store);
        $store = Store::find(getCurrentStore());
        $settings = Setting::where('theme_id', $store->theme_id)->where('store_id', $store->id)->pluck('value', 'name')->toArray();
        if ($request->enable_domain == 'enable_domain') {
            $validator = \Validator::make(
                $request->all(),
                [
                    'domains' => 'required',
                ]
            );
        }
        if ($request->enable_domain == 'enable_subdomain') {
            $validator = \Validator::make(
                $request->all(),
                [
                    'subdomain' => 'required',
                ]
            );
        }

        $user = \Auth::user();
        $theme_id = !empty($store->theme_id) ? $store->theme_id : '';
        $post = $request->all();
        $dir = 'themes/' . APP_THEME() . '/uploads';
        $settingQuery = Setting::query();
        if ($request->theme_logo) {
            $theme_image = $request->theme_logo;
            $fileName = rand(10, 100) . '_' . time() . "_" . $request->theme_logo->getClientOriginalName();
            $path = Utility::upload_file($request, 'theme_logo', $fileName, $dir, []);

            if ($path['flag'] == '0') {
                return redirect()->back()->with('error', $path['msg']);
            } else {
                $where = ['name' => 'theme_logo', 'theme_id' => $theme_id];
                $Setting = Setting::where($where)->first();

                if (!empty($Setting)) {
                    if (File::exists(base_path($Setting->value))) {
                        File::delete(base_path($Setting->value));
                    }
                }

                $post['theme_logo'] = $path['url'] ?? null;
            }
        }
        if ($request->invoice_logo) {
            $theme_image = $request->invoice_logo;
            $fileName = rand(10, 100) . '_' . time() . "_" . $request->invoice_logo->getClientOriginalName();
            $path = Utility::upload_file($request, 'invoice_logo', $fileName, $dir, []);

            if ($path['flag'] == '0') {
                return redirect()->back()->with('error', $path['msg']);
            } else {
                $where = ['name' => 'invoice_logo', 'theme_id' => $theme_id];
                $Setting = Setting::where($where)->first();

                if (!empty($Setting)) {
                    if (File::exists(base_path($Setting->value))) {
                        File::delete(base_path($Setting->value));
                    }
                }
                $post['invoice_logo'] = $path['url'] ?? null;
            }
        }
        if ($request->theme_favicon) {
            $theme_image = $request->theme_favicon;
            $fileName = rand(10, 100) . '_' . time() . "_" . $request->theme_favicon->getClientOriginalName();
            $path = Utility::upload_file($request, 'theme_favicon', $fileName, $dir, []);


            if ($path['flag'] == '0') {
                return redirect()->back()->with('error', $path['msg']);
            } else {
                $where = ['name' => 'theme_favicon', 'theme_id' => $theme_id];
                $Setting = Setting::where($where)->first();

                if (!empty($Setting)) {
                    if (File::exists(base_path($Setting->value))) {
                        File::delete(base_path($Setting->value));
                    }
                }

                $post['theme_favicon'] = $path['url'] ?? null;
            }
        }

        if ($request->enable_domain == 'enable_domain') {
            // Remove the http://, www., and slash(/) from the URL
            $input = $request->domains;
            // If URI is like, eg. www.way2tutorial.com/
            $input = trim($input, '/');
            // If not have http:// or https:// then prepend it
            if (!preg_match('#^http(s)?://#', $input)) {
                $input = 'http://' . $input;
            }

            $urlParts = parse_url($input);
            // Remove www.
            $post['domains'] = preg_replace('/^www\./', '', $urlParts['host']);
            // Output way2tutorial.com
        }
        if ($request->enable_domain == 'enable_subdomain') {
            // Remove the http://, www., and slash(/) from the URL
            $input = env('APP_URL');

            // If URI is like, eg. www.way2tutorial.com/
            $input = trim($input, '/');
            // If not have http:// or https:// then prepend it
            if (!preg_match('#^http(s)?://#', $input)) {
                $input = 'http://' . $input;
            }

            $urlParts = parse_url($input);

            // Remove www.
            $subdomain_name = preg_replace('/^www\./', '', $urlParts['host']);
            // Output way2tutorial.com
            $post['subdomain'] = $request->subdomain . '.' . $subdomain_name;
        }

        $settings['enable_storelink'] = ($request->enable_domain == 'enable_storelink' || empty($request->enable_domain)) ? 'on' : 'off';
        $settings['enable_domain'] = ($request->enable_domain == 'enable_domain') ? 'on' : 'off';
        $settings['enable_subdomain'] = ($request->enable_domain == 'enable_subdomain') ? 'on' : 'off';

        $post['enable_storelink'] = $settings['enable_storelink'];
        $post['enable_domain'] = $settings['enable_domain'];
        $post['enable_subdomain'] = $settings['enable_subdomain'];

        $additional_notes = $request->has('additional_notes') ? $request->additional_notes : 'off';
        $post['additional_notes'] = $additional_notes;

        $is_checkout_login_required = $request->has('is_checkout_login_required') ? $request->is_checkout_login_required : 'off';
        $post['is_checkout_login_required'] = $is_checkout_login_required;

        $post['store_address'] = $request->store_address;
        $post['store_city'] = $request->store_city;
        $post['store_state'] = $request->store_state;
        $post['store_zipcode'] = $request->store_zipcode;
        $post['store_country'] = $request->store_country;

        if (!empty($request->theme_name) || !empty($request->email) || !empty($request->google_analytic) || !empty($request->fbpixel_code) || !empty($request->storejs) || !empty($request->storecss)) {

            if (!isset($request->google_analytic)) {
                $post['google_analytic'] = !empty($request->google_analytic) ? $request->google_analytic : '';
            }
            if (!isset($request->fbpixel_code)) {
                $post['fbpixel_code'] = !empty($request->fbpixel_code) ? $request->fbpixel_code : '';
            }
            if (!isset($request->storejs)) {
                $post['storejs'] = !empty($request->storejs) ? $request->storejs : '';
            }
            if (!isset($request->storecss)) {
                $post['storecss'] = !empty($request->storecss) ? $request->storecss : '';
            }
        }

        $settingQuery = Setting::query();
        foreach ($post as $key => $data) {
            (clone $settingQuery)->updateOrCreate(
                ['name' => $key,
                'theme_id'      => APP_THEME(),
                'store_id'      => getCurrentStore(),
                'created_by'=>auth()->user()->id
               ],
                [
                    'value'         => $data,
                    'name'          => $key,
                    'theme_id'      => APP_THEME(),
                    'store_id'      => getCurrentStore(),
                    'created_by'    => auth()->user()->id,
                ]
            );
        }

        return redirect()->back()->with('success', __('Settings successfully updated.'));
    }

    public function FirebaseSettings(Request $request)
    {
        
        $theme_id = !empty(APP_THEME()) ? APP_THEME() : 'grocery';

        $firebase_enabled = !empty($request->firebase_enabled) ? $request->firebase_enabled : 'off';
        $fcm_Key = !empty($request->fcm_Key) ? $request->fcm_Key : '';

        $post['firebase_enabled'] = $firebase_enabled;
        $post['fcm_Key'] = $fcm_Key;
        $settingQuery = Setting::query();
        foreach($post as $key => $data)
        {
            (clone $settingQuery)->updateOrCreate(
                ['name' => $key,'created_by'=>auth()->user()->id],
                [
                    'value'         => $data,
                    'name'          => $key,
                    'theme_id'      => APP_THEME(),
                    'store_id'      => getCurrentStore(),
                    'created_by'    => auth()->user()->id,
                ]
            );
        }

        return redirect()->back()->with('success', __('Setting successfully updated.'));
    }

    public function MobileScreenContent()
    {
        // ie: /var/www/laravel/app/storage/json/filename.json
        $theme_id = !empty(env('DATA_INSERT_APP_THEME')) ? env('DATA_INSERT_APP_THEME') : APP_THEME();
        // Main page
        $path = base_path('themes/'.$theme_id.'/theme_json/homepage.json');
        $json = json_decode(file_get_contents($path), true);

        $setting_json = AppSetting::select('theme_json')
                            ->where('theme_id', $theme_id)
                            ->where('page_name', 'main')
                            ->where('store_id', getCurrentStore())
                            ->first();
        if(!empty($setting_json)) {
            $json = json_decode($setting_json->theme_json, true);
        }

        // Product Banner page
        $product_banner_json_path = base_path('theme_json/product-banner.json');
        $product_banner_json = json_decode(file_get_contents($product_banner_json_path), true);

        $setting_product_banner_json = AppSetting::select('theme_json')
                                ->where('theme_id', $theme_id)
                                ->where('page_name', 'product_banner')
                                ->where('store_id', getCurrentStore())
                                ->first();
        if(!empty($setting_product_banner_json)) {
            $product_banner_json = json_decode($setting_product_banner_json->theme_json, true);
        }

        // Order Complete page
        $order_complete_json_path = base_path('theme_json/order-complete.json');
        $order_complete_json = json_decode(file_get_contents($order_complete_json_path), true);

        $setting_order_complete_json = AppSetting::select('theme_json')
                                ->where('theme_id', $theme_id)
                                ->where('page_name', 'order_complete')
                                ->where('store_id', getCurrentStore())
                                ->first();
        if(!empty($setting_order_complete_json)) {
            $order_complete_json = json_decode($setting_order_complete_json->theme_json, true);
        }

        // Home pagw (WEBSITE)
        $homepage_web_json = [];
        $homepage_web_json_path = base_path('themes/'.$theme_id.'/theme_json/web/homepage.json');
        if(file_exists($homepage_web_json_path)) {
            $homepage_web_json = json_decode(file_get_contents($homepage_web_json_path), true);
        }

        $homepage_web_complete_json = AppSetting::select('theme_json')
                                ->where('theme_id', $theme_id)
                                ->where('page_name', 'home_page_web')
                                ->where('store_id', getCurrentStore())
                                ->first();
        if(!empty($homepage_web_complete_json)) {
            $homepage_web_json = json_decode($homepage_web_complete_json->theme_json, true);
        }


        // loyality program json
        $loyality_program_json = Utility::loyality_program_json($theme_id,getCurrentStore());

        $theme_name = !empty(env('DATA_INSERT_APP_THEME')) ? env('DATA_INSERT_APP_THEME') : APP_THEME();
        $settings = Setting::where('theme_id', APP_THEME())->where('store_id',getCurrentStore())->pluck('value', 'name')->toArray();
        $store = Store::find(getCurrentStore());
        $slug = $store->slug;
        if(empty($settings))
        {
            $settings = Utility::Seting();
        }
        $themes = [];

        $user = auth()->user();
        if($user->type == 'admin')
        {
            $plan = Plan::find($user->plan);
            if(!empty($plan->themes))
            {
              $themes =  explode(',',$plan->themes);
            }
        }

        $compact = ['json', 'product_banner_json', 'order_complete_json', 'homepage_web_json', 'loyality_program_json','slug','settings','themes','user'];
        return view('AppSetting.mobile_content', compact($compact));
    }

    public function SiteSetting(Request $request)
    {
        $validator = \Validator::make(
            $request->all(),
            [
                'date_format' => 'required'
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }

        $theme_id = !empty(APP_THEME()) ? APP_THEME() : 'grocery';
        $post['date_format'] = $request->date_format;

        $settingQuery = Setting::query();
        // Iterate over the data and insert/update in the 'settings' table
        foreach ($post as $key => $data) {
            (clone $settingQuery)->updateOrCreate(
                [
                    'name' => $key,
                    'theme_id' => APP_THEME(),
                    'store_id' => getCurrentStore()
                ],
                [
                    'value'         => $data,
                    'name'          => $key,
                    'theme_id'      => APP_THEME(),
                    'store_id'      => getCurrentStore(),
                    'created_by'    => auth()->user()->id,
                ]
            );
        }

        return redirect()->back()->with('success', __('Setting successfully updated.'));
    }
}
