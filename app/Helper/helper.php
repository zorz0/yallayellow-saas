<?php

use App\Models\Themes\{ ThemeSection, ThemeBestProductSection, ThemeArticelBlogSection, ThemeArticelBlogSectionDraft, ThemeTopProductSection, ThemeTopProductSectionDraft, ThemeModernProductSection, ThemeModernProductSectionDraft, ThemeBestProductSectionDraft,ThemeBestProductSecondSection, ThemeBestProductSecondSectionDraft, ThemeBestSellingSection, ThemeBestSellingSectionDraft, ThemeLogoSliderSection, ThemeLogoSliderSectionDraft, ThemeBestsellerSliderSection, ThemeBestsellerSliderSectionDraft, ThemeBestsellerSection, ThemeBestsellerSectionDraft, ThemeHeaderSection, ThemeSliderSection, ThemeCategorySection, ThemeReviewSection, ThemeSectionDraft, ThemeHeaderSectionDraft, ThemeSliderSectionDraft, ThemeCategorySectionDraft, ThemeReviewSectionDraft, ThemeSectionMap, ThemeBlogSection, ThemeBlogSectionDraft, ThemeDiscountSection, ThemeDiscountSectionDraft, ThemeProductCategorySection, ThemeProductCategorySectionDraft, ThemeFooterSection, ThemeFooterSectionDraft, ThemeProductSection, ThemeProductSectionDraft, ThemeSubscribeSection, ThemeSubscribeSectionDraft, ThemeVariantBackgroundSection, ThemeVariantBackgroundSectionDraft, ThemeProductBannerSliderSection, ThemeProductBannerSliderSectionDraft,ThemeNewestCateorySectionDraft,ThemeNewestCateorySection,ThemeServiceSection, ThemeServiceSectionDraft,ThemeVideoSection, ThemeVideoSectionDraft, ThemeNewestProductSection, ThemeNewestProductSectionDraft};
use App\Models\ProductAttributeOption;
use App\Models\Product;
use App\Models\Testimonial;
use App\Models\Page;
use App\Models\{MainCategory, SubCategory};
use App\Models\Setting;
use App\Models\User;
use App\Models\TaxOption;
use App\Models\Store;
use Illuminate\Support\Facades\Artisan;
use App\Models\Role;
use App\Models\Permission;
use App\Models\Utility;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\{Currency, PixelFields};
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\AddOn;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Hashids\Hashids;
use Nwidart\Modules\Facades\Module;
use App\Models\userActiveModule;
use Illuminate\Support\Facades\Session;
use App\Models\AddOnManager;

if(!function_exists('getMenu')){
    function getMenu(){
        $user = auth()->user();
        $role = $user->type ?? null;
        $menu = new \App\Classes\Menu($user);
        if($role && $role == 'super admin'){
            event(new \App\Events\SuperAdminMenuEvent($menu));
        }else{
            event(new \App\Events\CompanyMenuEvent($menu));
        }
        // $dashboardItem = collect($menu->menu)->first(function ($item) {
        //     return $item['parent'] === 'dashboard';
        // });
        // dd($dashboardItem['route']);
        return generateMenu($menu->menu,null);
    }
}

if (!function_exists('generateMenu')) {
    function generateMenu($menuItems, $parent = null)
    {
        $html = '';

        $filteredItems = array_filter($menuItems, function ($item) use ($parent) {
            return $item['parent'] == $parent;
        });
        usort($filteredItems, function ($a, $b) {
            return $a['order'] - $b['order'];
        });

        foreach ($filteredItems as $item) {
            $hasChildren = hasChildren($menuItems, $item['name']);
            if ($item['parent'] == null) {
                $html .= '<li class="dash-item dash-hasmenu">';
            } else {
                $html .= '<li class="dash-item">';
            }
            $html .= '<a href="' . (!empty($item['route']) ? route($item['route']) : '#!') . '" class="dash-link">';

            if ($item['parent'] == null) {
                $html .= ' <span class="dash-micon"><i class="ti ti-' . $item['icon'] . '"></i></span>
                <span class="dash-mtext">';
            }
            $html .= __($item['title']) . '</span>';
            if ($hasChildren) {
                $html .= '<span class="dash-arrow"> <i data-feather="chevron-right"></i> </span> </a>';
                $html .= '<ul class="dash-submenu">';
                $html .= generateMenu($menuItems, $item['name']);
                $html .= '</ul>';
            } else {
                $html .= '</a>';
            }
            $html .= '</li>';

        }
        return $html;
    }
}

if (!function_exists('hasChildren')) {
    function hasChildren($menuItems, $name)
    {
        foreach ($menuItems as $item) {
            if ($item['parent'] === $name) {
                return true;
            }
        }
        return false;
    }
}


if (!function_exists('SetNumberFormat')) {
    function SetNumberFormat($number = 0)
    {
        $settings = getAdminAllSetting();
        $currency = $settings['CURRENCY'] ?? 'USD';
        $number_output = number_format($number, 2);
        return $currency . str_replace(',', '', $number_output);
    }
}

if (!function_exists('SetNumber')) {
    function SetNumber($number = 0)
    {
        $number_output = number_format($number, 2);
        return str_replace(',', '', $number_output);
    }
}

if (!function_exists('get_file')) {
    function get_file($path, $theme_id = '')
    {
        $admin = User::where('type', 'super admin')->first();
        $theme_id = 'grocery';
        $settings = Setting::where('theme_id', $theme_id)->where('store_id', $admin->current_store)->pluck('value', 'name')->toArray();

        try {
            if (isset($settings) && (count($settings) > 0) && $settings['storage_setting'] == 'wasabi') {
                config(
                    [
                        'filesystems.disks.wasabi.key' => $settings['wasabi_key'],
                        'filesystems.disks.wasabi.secret' => $settings['wasabi_secret'],
                        'filesystems.disks.wasabi.region' => $settings['wasabi_region'],
                        'filesystems.disks.wasabi.bucket' => $settings['wasabi_bucket'],
                        'filesystems.disks.wasabi.endpoint' => 'https://s3.' . $settings['wasabi_region'] . '.wasabisys.com'
                    ]
                );
                return \Storage::disk($settings['storage_setting'])->url($path);
            } elseif (isset($settings) && (count($settings) > 0) && $settings['storage_setting'] == 's3') {
                config(
                    [
                        'filesystems.disks.s3.key' => $settings['s3_key'],
                        'filesystems.disks.s3.secret' => $settings['s3_secret'],
                        'filesystems.disks.s3.region' => $settings['s3_region'],
                        'filesystems.disks.s3.bucket' => $settings['s3_bucket'],
                        'filesystems.disks.s3.use_path_style_endpoint' => false,
                    ]
                );
                return \Storage::disk($settings['storage_setting'])->url($path);
            } else {
                $path = url($path);
                return $path;
            }
        } catch (\Throwable $th) {
            // dd($th);
            return '';
        }
    }
}

if (!function_exists('getEnableSetting')) {
    function getEnableSetting($remote)
    {
        $subdomain = Setting::where('name', 'subdomain')->where('value', $remote)->first();
        $domain = Setting::where('name', 'domains')->where('value', $remote)->first();

        return $subdomain ? $subdomain : $domain;
    }
}

if (!function_exists('isEnabled')) {
    function isEnabled($enableSetting)
    {
        $admin = User::find($enableSetting->created_by);

        return $enableSetting->value == 'on' && $enableSetting->store_id == $admin->current_store;
    }
}

if (!function_exists('getStoreIdFromUser')) {
    function getStoreIdFromUser($userId)
    {
        $user = User::find($userId);

        return $user ? $user->current_store : 1;
    }
}

if (!function_exists('getStoreIdFromSlugOrUser')) {
    function getStoreIdFromSlugOrUser($slug, $userId)
    {
        if ($slug) {
            return Store::where('slug', $slug)->value('id');
        }

        $userId = $userId ?? auth()->user()->id ?? 1;
        $user = User::find($userId);

        if (!$user) {
            return 1;
        }

        if ($user->type != 'admin' && $user->type != 'super admin') {
            $user = User::find($user->created_by);
        }

        return $user->current_store ?? Store::where('created_by', $user->id)->value('id') ?? 1;
    }
}

// setConfigEmail ( SMTP )
if(! function_exists('SetConfigEmail'))
{
    function SetConfigEmail($request)
    {
        try {
            config(
                [
                    'mail.driver' => $request->mail_driver,
                    'mail.host' => $request->mail_host,
                    'mail.port' => $request->mail_port,
                    'mail.encryption' => $request->mail_encryption,
                    'mail.username' => $request->mail_username,
                    'mail.password' => $request->mail_password,
                    'mail.from.address' => $request->mail_from_address,
                    'mail.from.name' => $request->mail_from_name,
                ]
            );
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}

if (!function_exists('upload_theme_image')) {
    function upload_theme_image($theme_name, $theme_image, $key = 0)
    {
        $return['status']       = false;
        $return['image_url']    = '';
        $return['image_path']   = '';
        $return['message']      = __('Something went wrong.');

        if (!empty($theme_image)) {
            $theme_image       = $theme_image;
            $filenameWithExt   = $theme_image->getClientOriginalName();
            $filename          = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension         = $theme_image->getClientOriginalExtension();
            $filedownloadable1 = $key . rand(0, 100) . date('Ymd') . '_' . time() . '.' . $extension;
            $dir               = 'themes/' . $theme_name . '/uploads';
            $save = Storage::disk('theme')->putFileAs(
                $dir,  // upload path
                $theme_image, // image name
                $filedownloadable1  // image new name
            );
            $return['status']       = true;
            $return['image_url']    = url('themes/' . $save);
            $return['image_path']   = $save;
            $return['message']      = __('Image upload succcessfully.');
        }
        return $return;
    }
}

if (!function_exists('getSuperAdminAllSetting')) {
    function getSuperAdminAllSetting()
    {
        // Laravel cache
        // return Cache::rememberForever('admin_settings',function () {
            $super_admin = User::where('type','super admin')->first();

            $settings = [];
            if($super_admin)
            {
                $settings = Setting::where('created_by',$super_admin->id)->where('store_id',$super_admin->current_store)->pluck('value', 'name')->toArray();
            }
            return $settings;
        // });
    }
}

if (!function_exists('getAdminAllSetting')) {
    function getAdminAllSetting($user_id = null, $store_id = null, $theme_id = null)
    {
        if(!empty($user_id)){
            $user = User::find($user_id);
        }
        else
        {
            $user =  auth()->user();
        }

        // Check if the user is not 'company' or 'super admin' and find the creator
        if (isset($user->type) && !in_array($user->type, ['admin', 'super admin'])) {
            $user = User::find($user->created_by);
        }
        if (!empty($user))
        {
            $store_id = $store_id ?? $user->current_store;
            $store = Store::find($store_id);
            $theme_id = $theme_id ?? $store->theme_id;

            // return Cache::rememberForever($key,function () use ($user, $store_id) {
                $settings = [];
                    $settings = Setting::where('created_by',$user->id)->where('store_id',$store_id)->where('theme_id',$theme_id)->pluck('value', 'name')->toArray();
                return $settings;
            // });
        } elseif (!empty($store_id) && !empty($theme_id)) {
            $settings = [];
            $settings = Setting::where('store_id',$store_id)->where('theme_id',$theme_id)->pluck('value', 'name')->toArray();
            return $settings;
        }

        return [];
    }
}

if (! function_exists('getCurrentStore')) {
    function getCurrentStore($user_id= null){
        if(!empty($user_id)){
            $user = User::find($user_id);
        }
        else
        {
            $user =  auth()->user();
        }
        if($user)
        {
            $storeId = $user->current_store;
            if(!empty($storeId)){
                return $storeId;
            }else{
                if($user->type == 'super admin'){
                    return 1;
                }else{
                    static $store= null;
                    if($store == null)
                    {
                        $store = Store::where('created_by',$user->id)->first();
                    }
                    return $store->id;
                }
            }
        }
    }
}

if (! function_exists('APP_THEME')) {
    function APP_THEME($user_id= null){
        if(!empty($user_id)){
            $user = User::find($user_id);
        }
        else
        {
            $user =  auth()->user();
        }

        if($user)
        {
            $storeId = $user->current_store;
            if(!empty($storeId)){
                $store =  Store::find($storeId);
                if (!$store) {
                    if (auth()->check()) {
                        auth()->logout();
                    }

                    return redirect()->route('login')->with('message', 'You have been logged out.');
                }
                return $store->theme_id ?? null;
            } else {
                $user->type == 'super admin';
                return 'grocery';
            }
        }

    }
}

if (! function_exists('currency')) {
    function currency($code=null){
        if($code==null){
            $c = Currency::get();
        }else{
            $c = Currency::where('code',$code)->first();
        }
        return $c;
    }
}

if (!function_exists('getThemeProductsBasedOnType')) {
    function getThemeProductsBasedOnType($type, $productIds, $storeId, $themeId) {
        $productQuery = Product::where('store_id', $storeId)->where('theme_id', $themeId)->where('status',1);

        $all_products = (clone $productQuery)->inRandomOrder()->limit(20)->get();
        $modern_products = (clone $productQuery)->orderByDesc('created_at')->limit(6)->get();
        $home_products = (clone $productQuery)->orderBy('created_at')->limit(4)->get();
        $home_page_products = (clone $productQuery)->orderByDesc('created_at')->limit(2)->get();

        switch ($type) {
            case 'tranding_product':
                $products = (clone $productQuery)->where('trending', 1)->limit(20)->get();
                break;
            case 'latest_product':
                $products = (clone $productQuery)->orderByDesc('created_at')->limit(20)->get();
                break;
            case 'random_product':
                $products = (clone $productQuery)->inRandomOrder()->limit(20)->get();
                break;
            case 'custom_product':
                $products = (clone $productQuery)->whereIn('id', $productIds)->get();
                break;
            case 'category_wise_product':
                $products = (clone $productQuery)->whereIn('category_id', $productIds)->get();
                break;
            case 'variant_product':
                $products = (clone $productQuery)->where('variant_product', 1)->limit(20)->get();
                break;
            case 'without_variant_product':
                $products = (clone $productQuery)->where('variant_product', 0)->limit(20)->get();
                break;
            default:
                $products = (clone $productQuery)->limit(20)->get();
            break;
        }

        return $products;
    }
}

if (!function_exists('getProductCategory')) {
    function getProductCategory($categoryId, $storeId, $themeId) {
        if (count($categoryId) > 0) {
            $categories = MainCategory::with('product_details')->where('status', 1)->whereIn('id', $categoryId)->where('store_id', $storeId)->where('theme_id', $themeId)->get();
        } else {
            $categories = MainCategory::with('product_details')->where('status', 1)->where('store_id', $storeId)->where('theme_id', $themeId)->get();
        }

        return $categories;
    }
}

if (!function_exists('GetCurrenctTheme')) {
    function GetCurrenctTheme($storeSlug) {

        if ($storeSlug == null) {
            $uri = url()->full();
            $segments = explode('/', str_replace(''.url('').'', '', $uri));
            $segments = $segments[1] ?? null;

            $local = parse_url(config('app.url'))['host'];
            // Get the request host
            $remote = request()->getHost();
            // Get the remote domain
            // remove WWW
            $remote = str_replace('www.', '', $remote);
            $subdomain = Setting::where('name','subdomain')->where('value',$remote)->first();
            $domain = Setting::where('name','domains')->where('value',$remote)->first();

            $enable_subdomain = "";
            $enable_domain = "";

            if($subdomain || $domain ){
                if($subdomain){
                    $enable_subdomain = Setting::where('name','enable_subdomain')->where('value','on')->where('store_id',$subdomain->store_id)->first();
                }

                if($domain){
                    $enable_domain = Setting::where('name','enable_domain')->where('value','on')->where('store_id',$domain->store_id)->first();
                }
            }
            if( $enable_domain || $enable_subdomain) {
                if($enable_subdomain) {
                    $admin = User::find($enable_subdomain->created_by);
                    if($enable_subdomain->value == 'on' &&  $enable_subdomain->store_id == $admin->current_store){
                        if(auth()->user() && auth()->user()->current_store){
                            $store_id = auth()->user()->current_store;
                        } else {
                            $store_id = $admin->current_store;
                        }                        }
                    else{
                        $store_id = $admin->current_store;
                    }
                } elseif($enable_domain) {
                    $admin = User::find($enable_domain->created_by);
                    if($enable_domain->value == 'on' &&  $enable_domain->store_id == $admin->current_store){
                        if(auth()->user() && auth()->user()->current_store){
                            $store_id = auth()->user()->current_store;
                        } else {
                            $store_id = $admin->current_store;
                        }                        }
                    else{
                        $store_id = $admin->current_store;
                    }
                }
            }


            $store = Store::where('id',$store_id)->first();
            $theme_id = $store->theme_id;
        } else {
            $store = Store::where('slug',$storeSlug)->first();
            $theme_id = $store->theme_id ?? '';
        }
        return $theme_id;
    }
}

if (!function_exists('getCurrenctStoreId')) {
    function getCurrenctStoreId($storeSlug) {
        if ($storeSlug == null) {
            $store_id = auth()->user()->current_store;
            $store = Store::where('slug',$storeSlug)->first();
            $id = $store->id;
        } else {
            $store = Store::where('slug',$storeSlug)->first();
            $id = $store->id ?? '';
        }
        return $id;
    }
}

if (!function_exists('getThemeSections')) {
    function getThemeSections($currentTheme, $storeSlug, $is_publish, $isLandingPage = false) {
        // Load site-wide settings
        $SITE_RTL =  null;
        $store = Store::where('slug', $storeSlug)->first();
        if (!$store) {
            abort(404);
        }
        $theme_id = $currentTheme;
        $store_id = $store->id;
        $slug = $store->slug;
        $stringid = $store->id;
		themeDefaultSection($store->theme_id, $store->id);
        $setting = Setting::where('theme_id', $currentTheme)->where('store_id', $store_id)->pluck('value', 'name')->toArray();

        $color = 'theme-3';
        $themeSectionQuery = ThemeSection::query();
        $themeSectionDraftQuery = ThemeSectionDraft::query();

        // Load theme sections
        if ($is_publish) {
            $theme_section = (clone $themeSectionQuery)
            ->where('theme_id', $currentTheme)
            ->where('store_id', $store_id);
            if ($isLandingPage) {
                $theme_section->where('is_hide', 0);
            }

            $theme_section = $theme_section->orderBy('order', 'asc')->get();
        } else {
            $theme_section = (clone $themeSectionDraftQuery)
            ->where('theme_id', $currentTheme)
            ->where('store_id', $store_id);
            if ($isLandingPage) {
                $theme_section->where('is_hide', 0);
            }

            $theme_section = $theme_section->orderBy('order', 'asc')->get();
            if (count($theme_section) == 0) {
                $theme_section = (clone $themeSectionQuery)
                ->where('theme_id', $currentTheme)
                ->where('store_id', $store_id);
                if ($isLandingPage) {
                    $theme_section->where('is_hide', 0);
                }

                $theme_section = $theme_section->orderBy('order', 'asc')->get();
            }
        }

        $jsonPaths = $theme_section->pluck('section_name')->toArray();
        // Load other JSON data

        $json_data = [];

        foreach ($jsonPaths as $jsonPath) {
            // Get Published or draft json file from database or root directory
            $data = arrayToObject(getThemeMainOrDraftSectionJson($is_publish, $currentTheme, $jsonPath, $store_id));

            $json_data[$jsonPath] = $data->json_data;
        }

        // Load discount products
        $productQuery = Product::where('theme_id', $store->theme_id)
            ->where('store_id', $store->id)->where('status',1);

        // Load currency and language information
        $currency = Utility::GetValueByName('CURRENCY_NAME', $theme_id, $store_id);
        $currency_icon = Utility::GetValueByName('CURRENCY', $theme_id, $store_id);
        $languages = Utility::languages();
        $currantLang = \Cookie::get('LANGUAGE') ?? $store->default_language;

        // Load theme logo
        $theme_logo = Utility::GetValueByName('theme_logo', $theme_id);

        $theme_favicon = Utility::GetValueByName('theme_favicon', $theme_id);
        $theme_favicons = $theme_favicon = get_file($theme_favicon , $theme_id);

       // $theme_logo = get_file(Utility::GetValueByName('logo_dark', $theme_id, $store_id), $theme_id);
        // Load main category information
        $category_options = MainCategory::where('theme_id', $store->theme_id)
            ->where('store_id', $store->id)
            ->where('status', 1)
            ->pluck('name', 'id')
            ->prepend('All Products', '0');

        // Load product information
        $products = (clone $productQuery)->get();

        $has_subcategory = Utility::themeSubcategory($store->theme_id);
        $section = (object) $json_data;
        $topNavItems = [];
        $menu_id = [];
        if (isset($section->header) && isset($section->header->section) && isset($section->header->section->menu_type) && isset($section->header->section->menu_type->menu_ids)) {
            $menu_id = (array) $section->header->section->menu_type->menu_ids;
        }

        $metakeyword = Utility::GetValueByName('metakeyword', $theme_id);
        $metadesc = Utility::GetValueByName('metadesc', $theme_id);
        $metaimage = Utility::GetValueByName('metaimage', $theme_id);
        $metaimage = get_file($metaimage , $theme_id);

        $google_analytic = Utility::GetValueByName('google_analytic', $theme_id);
        $storejs = Utility::GetValueByName('storejs', $theme_id);
        $storecss = Utility::GetValueByName('storecss', $theme_id);
        $fbpixel_code = Utility::GetValueByName('fbpixel_code', $theme_id);


        $pixels = PixelFields::where('store_id', $store->id)
            ->where('theme_id', $store->theme_id)
            ->get();
        $pixelScript = [];
        foreach ($pixels as $pixel) {
            $pixelScript[] = pixelSourceCode($pixel['platform'], $pixel['pixel_id']);
        }
        $topNavItems = get_nav_menu($menu_id);
        $whatsapp_setting_enabled = Utility::GetValueByName('is_whatsapp_enabled',$theme_id, $store_id);
        $whatsapp_setting_enabled = !empty($whatsapp_setting_enabled) && $whatsapp_setting_enabled == 'on' ? 1 : 0;
        $whatsapp_contact_number =  Utility::GetValueByName('whatsapp_number',$theme_id, $store_id);


        $tax_option = TaxOption::where('store_id', $store->id)
        ->where('theme_id', $store->theme_id)
        ->pluck('value', 'name')->toArray();

        return compact('currentTheme', 'pixelScript','whatsapp_setting_enabled', 'whatsapp_contact_number','theme_section', 'section', 'setting', 'theme_logo', 'currantLang', 'stringid', 'languages',  'currency', 'SITE_RTL', 'color', 'is_publish', 'slug', 'store', 'theme_id', 'category_options', 'store_id','topNavItems', 'products','tax_option','theme_favicon','theme_favicons', 'google_analytic', 'storejs', 'storecss', 'fbpixel_code', 'metaimage', 'metadesc', 'metakeyword','currency_icon');
    }
}

if(!function_exists('mergeThemeJson')) {
    function mergeThemeJson($theme_json, $database_json) {
        if (is_array($theme_json) && count($theme_json) > 0) {
            $new_json = [];
            foreach ($theme_json as $key => $value) {
                if (array_key_exists($key, $database_json)){
                // Merge arrays with custom logic
                    $new_json[$key] = arrayToObject(array_replace_recursive(objectToArray($value), objectToArray($database_json[$key])));
                } else {
                    $new_json[$key] = $value;
                }
            }
            return $new_json;
        } else {
            return arrayToObject(array_replace_recursive(objectToArray($theme_json), objectToArray($database_json)));
        }

    }
}

// Convert the object to an array recursively
if(!function_exists('objectToArray')) {
    function objectToArray($obj)
    {
        if (is_object($obj) || is_array($obj)) {
            $arr = [];
            foreach ($obj as $key => $value) {
                $arr[$key] = objectToArray($value);
            }
            return $arr;
        } else {
            return $obj;
        }
    }
}

// Convert the array to an object recursively
if(!function_exists('arrayToObject')) {
    function arrayToObject($array)
    {
        if (is_array($array)) {
            $obj = new stdClass();
            foreach ($array as $key => $value) {
                $obj->$key = arrayToObject($value);
            }
            return $obj;
        } else {
            return $array;
        }
    }
}

if (!function_exists('getCategoryBasedProduct')) {
    function getCategoryBasedProduct($categoryId, $storeId, $themeId) {
        if ($categoryId == 0) {
            $products = Product::where('store_id', $storeId)->where('theme_id', $themeId)->where('status',1)->get();
        } else {
            $products = Product::where('maincategory_id', $categoryId)->where('store_id', $storeId)->where('theme_id', $themeId)->where('status',1)->get();
        }
        return $products;
    }
}

if(!function_exists('get_nav_menu')) {
    function get_nav_menu($productIds) {
        if (is_array($productIds)) {
            $topNav = Menu::whereIn('id', $productIds)->first();
        } else {
            $topNav = Menu::where('id', $productIds)->first();
        }

        // dd($productIds);
        if ($topNav) {
            $topNavItems = json_decode($topNav->content);
            if ($topNavItems) {
                foreach ($topNavItems[0] as $menu) {
                    $menu->title = MenuItem::where('id', $menu->id)->value('title');
                    $menu->slug = MenuItem::where('id', $menu->id)->value('slug');
                    $menu->target = MenuItem::where('id', $menu->id)->value('target');
                    $menu->type = MenuItem::where('id', $menu->id)->value('type');

                    if (!empty($menu->children[0])) {
                        foreach ($menu->children[0] as $child) {

                            $child->title = MenuItem::where('id', $child->id)->value('title');
                            $child->slug = MenuItem::where('id', $child->id)->value('slug');
                            $child->target = MenuItem::where('id', $child->id)->value('target');
                            $child->type = MenuItem::where('id', $child->id)->value('type');
                        }
                    }
                }
                return $topNavItems[0];
            }
        } else {
            return '';
        }
    }
}

if (!function_exists('themeDefaultSection')) {
    function themeDefaultSection($themeId, $storeId)
    {
        $data = [
            ['section_name' => 'header', 'order' => '0'],
            ['section_name' => 'slider', 'order' => '1'],
            ['section_name' => 'category', 'order' => '2'],
            ['section_name' => 'variant_background', 'order' => '3'],
            ['section_name' => 'bestseller_slider', 'order' => '4'],
            ['section_name' => 'product_category', 'order' => '5'],
            ['section_name' => 'best_product', 'order' => '6'],
            ['section_name' => 'best_product_second', 'order' => '7'],
            ['section_name' => 'product', 'order' => '8'],
            ['section_name' => 'product_banner_slider', 'order' => '9'],
            ['section_name' => 'logo_slider', 'order' => '10'],
            ['section_name' => 'best_selling_slider', 'order' => '11'],
            ['section_name' => 'newest_category', 'order' => '12'],
            ['section_name' => 'feature_product', 'order' => '13'],
            ['section_name' => 'background_image', 'order' => '14'],
            ['section_name' => 'modern_product', 'order' => '15'],
            ['section_name' => 'category_slider', 'order' => '16'],
            ['section_name' => 'service_section', 'order' => '17'],
            ['section_name' => 'subscribe', 'order' => '18'],
            ['section_name' => 'review', 'order' => '19'],
            ['section_name' => 'blog', 'order' => '20'],
            ['section_name' => 'articel_blog', 'order' => '21'],
            ['section_name' => 'top_product', 'order' => '22'],
            ['section_name' => 'video', 'order' => '23'],
            ['section_name' => 'footer', 'order' => '24'],
        ];

        $themeSectionQuery = ThemeSection::query();
        $themeSectionDraftQuery = ThemeSectionDraft::query();
        foreach ($data as $value) {
            $existSection = (clone $themeSectionQuery)->where('section_name', $value['section_name'])->where('store_id', $storeId)->where('theme_id', $themeId)->first();
            if (!$existSection) {
                (clone $themeSectionQuery)->create([
                    'section_name' => $value['section_name'],
                    'store_id' => $storeId,
                    'theme_id' => $themeId,
                    'order' => $value['order'],
                    'is_hide' => 0,
                ]);
            }

            $existDraftSection = (clone $themeSectionDraftQuery)->where('section_name', $value['section_name'])->where('store_id', $storeId)->where('theme_id', $themeId)->first();
            if (!$existDraftSection) {
                (clone $themeSectionDraftQuery)->create([
                    'section_name' => $value['section_name'],
                    'store_id' => $storeId,
                    'theme_id' => $themeId,
                    'order' => $value['order'],
                    'is_hide' => 0,
                ]);
            }
        }
        return true;
    }
}

if (!function_exists('SetDateFormat')) {
    function SetDateFormat($date = '')
    {
        $date_format = Utility::GetValueByName('date_format');
        if (empty($date_format)) {
            $date_format = 'Y-m-d';
        }
        if (empty($date)) {
            $date = date('Y-m-d');
        }
        try {
            $date_new = date($date_format, strtotime($date));
        } catch (\Throwable $th) {
            $date_new = $date;
        }
        return $date_new;
    }
}

if(!function_exists('getThemeMainOrDraftSectionJson')) {
    function getThemeMainOrDraftSectionJson($is_publish, $currentTheme, $sectionName, $storeId)
    {
        $theme_id = $currentTheme;
        $store_id = $storeId;
        // Set json file path based on the section name
        $jsonFilePath = base_path("themes/{$currentTheme}/theme_json/{$sectionName}.json");
        if (!file_exists($jsonFilePath)) {
            $jsonFilePath = base_path("/theme_json/{$sectionName}.json");
        }

        // Decode json file content
        $json_data = file_exists($jsonFilePath) ? json_decode(json_encode(json_decode(file_get_contents($jsonFilePath), true))) : [];
        if (is_array($json_data)) {
            $json_data = arrayToObject($json_data);
        }
        // Get section data based on section name and publication status
        $databaseSection = null;
        $produtcs = $categories = $menus = [];

        switch ($sectionName) {
            case 'slider':
                $databaseSection = getDatabaseSection($is_publish, ThemeSliderSection::query(), ThemeSliderSectionDraft::query(), $sectionName, $currentTheme, $storeId);
                break;
            case 'category':
                    $categories = MainCategory::select('id','name')->get();
                    $databaseSection = getDatabaseSection($is_publish, ThemeCategorySection::query(), ThemeCategorySectionDraft::query(), $sectionName, $currentTheme, $storeId);
                break;
            case 'bestseller_slider':
                $produtcs = Product::select('id','name')->get();
                $categories = MainCategory::where('theme_id', $currentTheme)
                            ->where('store_id', $storeId)
                            ->pluck('name', 'id');
                //$categories = MainCategory::select('id','name')->get();
                $databaseSection = getDatabaseSection($is_publish, ThemeBestsellerSliderSection::query(), ThemeBestsellerSliderSectionDraft::query(), $sectionName, $currentTheme, $storeId);
                break;
            case 'modern_product':
                $databaseSection = getDatabaseSection($is_publish, ThemeModernProductSection::query(), ThemeModernProductSectionDraft::query(), $sectionName, $currentTheme, $storeId);
                 break;
            case 'header':
                $menus = Menu::select('id','name')->where('theme_id', $currentTheme)
                ->where('store_id', $storeId)->get();
                $databaseSection = getDatabaseSection($is_publish, ThemeHeaderSection::query(), ThemeHeaderSectionDraft::query(), $sectionName, $currentTheme, $storeId);
                break;
            case 'product_category':
                $categories = MainCategory::where('theme_id', $currentTheme)
                            ->where('store_id', $storeId)
                            ->pluck('name', 'id');
                $databaseSection = getDatabaseSection($is_publish, ThemeProductCategorySection::query(), ThemeProductCategorySectionDraft::query(), $sectionName, $currentTheme, $storeId);
            break;
            case 'product':
                $categories = MainCategory::where('theme_id', $currentTheme)
                            ->where('store_id', $storeId)
                            ->pluck('name', 'id');
                $databaseSection = getDatabaseSection($is_publish, ThemeProductSection::query(), ThemeProductSectionDraft::query(), $sectionName, $currentTheme, $storeId);
            break;
            case 'variant_background':
                $databaseSection = getDatabaseSection($is_publish, ThemeVariantBackgroundSection::query(), ThemeVariantBackgroundSectionDraft::query(), $sectionName, $currentTheme, $storeId);
                break;
            case 'subscribe':
                $databaseSection = getDatabaseSection($is_publish, ThemeSubscribeSection::query(), ThemeSubscribeSectionDraft::query(), $sectionName, $currentTheme, $storeId);
                break;
            case 'best_product':
                $databaseSection = getDatabaseSection($is_publish, ThemeBestProductSection::query(), ThemeBestProductSectionDraft::query(), $sectionName, $currentTheme, $storeId);
                break;
            case 'best_product_second':
                $databaseSection = getDatabaseSection($is_publish, ThemeBestProductSecondSection::query(), ThemeBestProductSecondSectionDraft::query(), $sectionName, $currentTheme, $storeId);
                break;
            case 'best_selling_slider':
                $databaseSection = getDatabaseSection($is_publish, ThemeBestSellingSection::query(), ThemeBestSellingSectionDraft::query(), $sectionName, $currentTheme, $storeId);
                break;
            case 'review':
                $databaseSection = getDatabaseSection($is_publish, ThemeReviewSection::query(), ThemeReviewSectionDraft::query(), $sectionName, $currentTheme, $storeId);
                break;
            case 'blog':
                $databaseSection = getDatabaseSection($is_publish, ThemeBlogSection::query(), ThemeBlogSectionDraft::query(), $sectionName, $currentTheme, $storeId);
                break;
            case 'logo_slider':
                $databaseSection = getDatabaseSection($is_publish, ThemeLogoSliderSection::query(), ThemeLogoSliderSectionDraft::query(), $sectionName, $currentTheme, $storeId);
                break;
            case 'product_banner_slider':
                $databaseSection = getDatabaseSection($is_publish, ThemeProductBannerSliderSection::query(), ThemeProductBannerSliderSectionDraft::query(), $sectionName, $currentTheme, $storeId);
                break;
            case 'newest_product':
                $databaseSection = getDatabaseSection($is_publish, ThemeNewestProductSection::query(), ThemeNewestProductSectionDraft::query(), $sectionName, $currentTheme, $storeId);
                break;
            case 'top_product':
                $databaseSection = getDatabaseSection($is_publish, ThemeTopProductSection::query(), ThemeTopProductSectionDraft::query(), $sectionName, $currentTheme, $storeId);
                break;
            case 'articel_blog':
                $databaseSection = getDatabaseSection($is_publish, ThemeArticelBlogSection::query(), ThemeArticelBlogSectionDraft::query(), $sectionName, $currentTheme, $storeId);
                        break;
            case 'service_section':
                $databaseSection = getDatabaseSection($is_publish, ThemeServiceSection::query(), ThemeServiceSectionDraft::query(), $sectionName, $currentTheme, $storeId);
                        break;
            case 'newest_category':
                $databaseSection = getDatabaseSection($is_publish, ThemeNewestCateorySection::query(), ThemeNewestCateorySectionDraft::query(), $sectionName, $currentTheme, $storeId);
                        break;
            case 'video':
                $databaseSection = getDatabaseSection($is_publish, ThemeVideoSection::query(), ThemeVideoSectionDraft::query(), $sectionName, $currentTheme, $storeId);
            case 'footer':
                $menus = Menu::select('id','name')->where('theme_id', $currentTheme)
                ->where('store_id', $storeId)->get();
                $databaseSection = getDatabaseSection($is_publish, ThemeFooterSection::query(), ThemeFooterSectionDraft::query(), $sectionName, $currentTheme, $storeId);
                break;
        }


        if (isset($databaseSection)) {
            $json_data = mergeThemeJson($json_data, $databaseSection);
        } else {
            $json_data = arrayToObject(objectToArray($json_data));
        }

        return compact('json_data', 'produtcs', 'categories','menus', 'currentTheme', 'theme_id', 'store_id');
    }
}

if(!function_exists('getDatabaseSection')){
    function getDatabaseSection($is_publish, $sectionModel, $draftModel, $sectionName, $currentTheme, $storeId)
    {
        if ($is_publish) {
            return $sectionModel->where('theme_id', $currentTheme)
                ->where('store_id', $storeId)
                ->where('section_name', $sectionName)
                ->value('theme_json');
        } else {
            $data = $draftModel->where('theme_id', $currentTheme)
                ->where('store_id', $storeId)
                ->where('section_name', $sectionName)
                ->value('theme_json');
            if (!$data) {
                $data =$sectionModel->where('theme_id', $currentTheme)
                ->where('store_id', $storeId)
                ->where('section_name', $sectionName)
                ->value('theme_json');
            }

            return $data;
        }
    }
}

if (!function_exists('pixelSourceCode')) {
    function pixelSourceCode($platform, $pixelId)
    {
        // Facebook Pixel script
        if ($platform === 'facebook') {
            $script = "
                <script>
                    !function(f,b,e,v,n,t,s)
                    {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
                    n.callMethod.apply(n,arguments):n.queue.push(arguments)};
                    if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
                    n.queue=[];t=b.createElement(e);t.async=!0;
                    t.src=v;s=b.getElementsByTagName(e)[0];
                    s.parentNode.insertBefore(t,s)}(window, document,'script',
                    'https://connect.facebook.net/en_US/fbevents.js');
                    fbq('init', '%s');
                    fbq('track', 'PageView');
                </script>

                <noscript><img height='1' width='1' style='display:none' src='https://www.facebook.com/tr?id=%d&ev=PageView&noscript=1'/></noscript>
            ";

            return sprintf($script, $pixelId, $pixelId);
        }


        // Twitter Pixel script
        if ($platform === 'twitter') {
            $script = "
            <script>
            !function(e,t,n,s,u,a){e.twq||(s=e.twq=function(){s.exe?s.exe.apply(s,arguments):s.queue.push(arguments);
            },s.version='1.1',s.queue=[],u=t.createElement(n),u.async=!0,u.src='https://static.ads-twitter.com/uwt.js',
            a=t.getElementsByTagName(n)[0],a.parentNode.insertBefore(u,a))}(window,document,'script');
            twq('config','%s');
            </script>
            ";

            return sprintf($script, $pixelId);
        }


        // Linkedin Pixel script
        if ($platform === 'linkedin') {
            $script = "
                <script type='text/javascript'>
                    _linkedin_data_partner_id = %d;
                </script>
                <script type='text/javascript'>
                    (function () {
                        var s = document.getElementsByTagName('script')[0];
                        var b = document.createElement('script');
                        b.type = 'text/javascript';
                        b.async = true;
                        b.src = 'https://snap.licdn.com/li.lms-analytics/insight.min.js';
                        s.parentNode.insertBefore(b, s);
                    })();
                </script>
                <noscript><img height='1' width='1' style='display:none;' alt='' src='https://dc.ads.linkedin.com/collect/?pid=%d&fmt=gif'/></noscript>
            ";

            return sprintf($script, $pixelId, $pixelId);
        }

        // Pinterest Pixel script
        if ($platform === 'pinterest') {
            $script = "
            <!-- Pinterest Tag -->
            <script>
            !function(e){if(!window.pintrk){window.pintrk = function () {
            window.pintrk.queue.push(Array.prototype.slice.call(arguments))};var
            n=window.pintrk;n.queue=[],n.version='3.0';var
            t=document.createElement('script');t.async=!0,t.src=e;var
            r=document.getElementsByTagName('script')[0];
            r.parentNode.insertBefore(t,r)}}('https://s.pinimg.com/ct/core.js');
            pintrk('load', '%s');
            pintrk('page');
            </script>
            <noscript>
            <img height='1' width='1' style='display:none;' alt=''
            src='https://ct.pinterest.com/v3/?event=init&tid=2613174167631&pd[em]=<hashed_email_address>&noscript=1' />
            </noscript>
            <!-- end Pinterest Tag -->

            ";

            return sprintf($script, $pixelId, $pixelId);
        }

        // Quora Pixel script
        if ($platform === 'quora') {
            $script = "
            <script>
                    !function (q, e, v, n, t, s) {
                        if (q.qp) return;
                        n = q.qp = function () {
                            n.qp ? n.qp.apply(n, arguments) : n.queue.push(arguments);
                        };
                        n.queue = [];
                        t = document.createElement(e);
                        t.async = !0;
                        t.src = v;
                        s = document.getElementsByTagName(e)[0];
                        s.parentNode.insertBefore(t, s);
                    }(window, 'script', 'https://a.quora.com/qevents.js');
                    qp('init', %s);
                    qp('track', 'ViewContent');
                </script>

                <noscript><img height='1' width='1' style='display:none' src='https://q.quora.com/_/ad/%d/pixel?tag=ViewContent&noscript=1'/></noscript>
            ";

            return sprintf($script, $pixelId, $pixelId);
        }

        // Bing Pixel script
        if ($platform === 'bing') {
            $script = '
                <script>
                (function(w,d,t,r,u){var f,n,i;w[u]=w[u]||[] ,f=function(){var o={ti:"%d"}; o.q=w[u],w[u]=new UET(o),w[u].push("pageLoad")} ,n=d.createElement(t),n.src=r,n.async=1,n.onload=n .onreadystatechange=function() {var s=this.readyState;s &&s!=="loaded"&& s!=="complete"||(f(),n.onload=n. onreadystatechange=null)},i= d.getElementsByTagName(t)[0],i. parentNode.insertBefore(n,i)})(window,document,"script"," //bat.bing.com/bat.js","uetq");
                </script>
                <noscript><img src="//bat.bing.com/action/0?ti=%d&Ver=2" height="0" width="0" style="display:none; visibility: hidden;" /></noscript>
            ';

            return sprintf($script, $pixelId, $pixelId);
        }

        // Google adwords Pixel script
        if ($platform === 'google-adwords') {
            $script = "
                <script type='text/javascript'>

                var google_conversion_id = '%s';
                var google_custom_params = window.google_tag_params;
                var google_remarketing_only = true;

                </script>
                <script type='text/javascript' src='//www.googleadservices.com/pagead/conversion.js'>
                </script>
                <noscript>
                <div style='display:inline;'>
                <img height='1' width='1' style='border-style:none;' alt='' src='//googleads.g.doubleclick.net/pagead/viewthroughconversion/%s/?guid=ON&amp;script=0'/>
                </div>
                </noscript>
            ";

            return sprintf($script, $pixelId, $pixelId);
        }


        // Google tag manager Pixel script
        if ($platform === 'google-analytics') {
            $script = "
                <script async src='https://www.googletagmanager.com/gtag/js?id=%s'></script>
                <script>

                window.dataLayer = window.dataLayer || [];

                function gtag(){dataLayer.push(arguments);}

                gtag('js', new Date());

                gtag('config', '%s');

                </script>
            ";

            return sprintf($script, $pixelId, $pixelId);
        }

        //snapchat
        if ($platform === 'snapchat') {
            $script = " <script type='text/javascript'>
            (function(e,t,n){if(e.snaptr)return;var a=e.snaptr=function()
            {a.handleRequest?a.handleRequest.apply(a,arguments):a.queue.push(arguments)};
            a.queue=[];var s='script';r=t.createElement(s);r.async=!0;
            r.src=n;var u=t.getElementsByTagName(s)[0];
            u.parentNode.insertBefore(r,u);})(window,document,
            'https://sc-static.net/scevent.min.js');

            snaptr('init', '%s', {
            'user_email': '__INSERT_USER_EMAIL__'
            });

            snaptr('track', 'PAGE_VIEW');

            </script>";
            return sprintf($script, $pixelId, $pixelId);
        }

        //tiktok
        if ($platform === 'tiktok') {
            $script = " <script>
            !function (w, d, t) {
            w.TiktokAnalyticsObject=t;
            var ttq=w[t]=w[t]||[];
            ttq.methods=['page','track','identify','instances','debug','on','off','once','ready','alias','group','enableCookie','disableCookie'],ttq.setAndDefer=function(t,e){t[e]=function(){t.push([e].concat(Array.prototype.slice.call(arguments,0)))}};
            for(var i=0;i<ttq.methods.length;i++)ttq.setAndDefer(ttq,ttq.methods[i]);ttq.instance=function(t){for(var e=ttq._i[t]||[],n=0;n<ttq.methods.length;
            n++)ttq.setAndDefer(e,ttq.methods[n]);
            return e},ttq.load=function(e,n){var i='https://analytics.tiktok.com/i18n/pixel/events.js';
            ttq._i=ttq._i||{},ttq._i[e]=[],ttq._i[e]._u=i,ttq._t=ttq._t||{},ttq._t[e]=+new Date,ttq._o=ttq._o||{},ttq._o[e]=n||{};
            var o=document.createElement('script');
            o.type='text/javascript',o.async=!0,o.src=i+'?sdkid='+e+'&lib='+t;
            var a=document.getElementsByTagName('script')[0];
            a.parentNode.insertBefore(o,a)};

            ttq.load('%s');
            ttq.page();
            }(window, document, 'ttq');
            </script>";

            return sprintf($script, $pixelId, $pixelId);
        }
    }
}

if (!function_exists('metaKeywordSetting')) {
    function metaKeywordSetting($metakeyword = '', $metadesc = '', $metaimage = '', $slug = '')
    {
        $url = route('landing_page', $slug);
        $script = "
        <meta name='title' content='$metakeyword'>
        <meta name='description' content='$metadesc'>

        <meta property='og:type' content='website'>
        <meta property='og:url' content='$url'>
        <meta property='og:title' content=' $metakeyword'>
        <meta property='og:description' content='$metadesc'>
        <meta property='og:image' content=' $metaimage'>

        <meta property='twitter:card' content='summary_large_image'>
        <meta property='twitter:url' content='$url'>
        <meta property='twitter:title' content=' $metakeyword'>
        <meta property='twitter:description' content=' $metadesc'>
        <meta property='twitter:image' content=' $metaimage'>
        ";

        return sprintf($script);
    }
}

if(!function_exists('getHomePageDatabaseSectionDataFromDatabase')){
    function getHomePageDatabaseSectionDataFromDatabase($data)
    {
        $categories = MainCategory::where('trending', 1)
        ->where('theme_id', $data['theme_id'])
        ->where('store_id', $data['store_id'])
        ->limit(4)
        ->get();

        // Load discount products
        $productQuery = Product::where('theme_id', $data['theme_id'])
            ->where('store_id', $data['store_id'])
            ->where('status' , 1);

        $discount_products = (clone $productQuery)
            ->orderByDesc('sale_price')
            ->limit(12)
            ->get();

        // Load product information
        $products = (clone $productQuery)->get();
        $all_products = (clone $productQuery)->inRandomOrder()->limit(20)->get();
        $modern_products = (clone $productQuery)->orderByDesc('created_at')->limit(6)->get();
        $home_products = (clone $productQuery)->orderBy('created_at')->limit(4)->get();
        $home_page_products = (clone $productQuery)->orderByDesc('created_at')->limit(2)->get();

        // Load reviews
        $reviews = Testimonial::where('status', 1)
            ->where('theme_id', $data['theme_id'])
            ->where('store_id', $data['store_id'])
            ->get();
        $pages = [];
        // Load main category list and subcategory information
        $category_id = MainCategory::with('product_details')->where('status', 1)
            ->where('theme_id',  $data['theme_id'])
            ->where('store_id', $data['store_id'])
            ->pluck('id')->toArray();

        $MainCategoryList = MainCategory::with('product_details')->where('status', 1)
        ->where('theme_id',  $data['theme_id'])
        ->where('store_id', $data['store_id'])->get();

        $SubCategoryList = SubCategory::where('status', 1)
        ->where('theme_id',  $data['theme_id'])
        ->where('store_id', $data['store_id'])->get();

        $has_subcategory = Utility::themeSubcategory($data['theme_id']);
        $latest_product = $landing_product = (clone $productQuery)->orderBy('created_at', 'Desc')->first();
        $random_product = (clone $productQuery)->inRandomOrder()->first();
        // bestseller
        $per_page = '12';
        $destination = 'web';
        $bestSeller_fun = Product::bestseller_guest( $data['theme_id'], $data['store_id'], $per_page, $destination);
        $bestSeller = [];
        if($bestSeller_fun['status'] == "success") {
            $bestSeller = $bestSeller_fun['bestseller_array'];
        }
        $search_products = Product::where('theme_id',  $data['theme_id'])->where('store_id', $data['store_id'])->get()->pluck('name','id');
        return compact('categories','latest_product', 'search_products', 'MainCategoryList', 'SubCategoryList', 'reviews', 'pages', 'category_id', 'has_subcategory', 'discount_products', 'products', 'all_products', 'modern_products', 'home_products', 'home_page_products', 'landing_product', 'random_product', 'bestSeller');
    }
}

if (!function_exists('GetCurrency')) {
    function GetCurrency()
    {
        return Utility::GetValueByName('CURRENCY');
    }
}

if (!function_exists('getProductSlug')) {
    function getProductSlug($productId) {
        $product = Product::where('id', $productId)->select('id', 'name', 'slug')->first();

        return $product->slug ?? null;
    }
}

if (!function_exists('defaultSetting')) {
    function defaultSetting($themeId, $storeId, $type, $user)
    {
        if ($type == 'super admin') {
            $settings = [
                "logo_dark" => "storage/uploads/logo/logo-dark.png",
                "logo_light" => "storage/uploads/logo/logo-light.png",
                "favicon" => "storage/uploads/logo/favicon.png",
                "title_text" => !empty(env('APP_NAME')) ? env('APP_NAME') : 'eCommerceGo SaaS',
                "footer_text" => "Copyright © ".(!empty(env('APP_NAME')) ? env('APP_NAME') : 'eCommerceGo SaaS'),
                "site_date_format" => "M j, Y",
                "site_time_format" => "g:i A",
                "SITE_RTL" => "off",
                "display_landing" => "on",
                "SIGNUP" => "on",
                "email_verification" => "off",
                "color" => "theme-3",
                "cust_theme_bg" => "on",
                "cust_darklayout" => "off",

                "storage_setting" => "local",
                "local_storage_validation" => "jpg,jpeg,png,csv,svg,pdf",
                "local_storage_max_upload_size" => "2048000",
                's3_key' => '',
                's3_secret' => '',
                's3_region' => '',
                's3_bucket' => '',
                's3_endpoint' => '',
                's3_max_upload_size' => '',
                's3_storage_validation' => '',
                'wasabi_key' => '',
                'wasabi_secret' => '',
                'wasabi_region' => '',
                'wasabi_bucket' => '',
                'wasabi_url' => '',
                'wasabi_root' => '',
                'wasabi_max_upload_size' => '',
                'wasabi_storage_validation' => '',

                "CURRENCY_NAME" => "USD",
                "CURRENCYCURRENCY" => "$",
                "currency_format" => "1",
                "defult_currancy" => "USD",
                "defult_language" => "en",
                "defult_timezone" => "Asia/Kolkata",

                // for cookie
                'enable_cookie'=>'on',
                'cookie_logging'=>'on',
                'necessary_cookies'=>'on',
                'cookie_title'=>'We use cookies!',
                'cookie_description'=>'Hi, this website uses essential cookies to ensure its proper operation and tracking cookies to understand how you interact with it',
                'strictly_cookie_title'=>'Strictly necessary cookies',
                'strictly_cookie_description'=>'These cookies are essential for the proper functioning of my website. Without these cookies, the website would not work properly',
                'more_information_description'=>'For any queries in relation to our policy on cookies and your choices, please contact us',
                "more_information_title" => "",
                'contactus_url'=>'#',

            ];
        } else {
            $settings = [
                "logo_dark" => "storage/uploads/logo/logo-dark.png",
                "logo_light" => "storage/uploads/logo/logo-light.png",
                "favicon" => "storage/uploads/logo/favicon.png",
                "title_text" => !empty(env('APP_NAME')) ? env('APP_NAME') : 'eCommerceGo SaaS',
                "footer_text" => "Copyright © ".(!empty(env('APP_NAME')) ? env('APP_NAME') : 'eCommerceGo SaaS'),
                "site_date_format" => "M j, Y",
                "site_time_format" => "g:i A",
                "SITE_RTL" => "off",
                "display_landing" => "on",
                "SIGNUP" => "on",
                "email_verification" => "off",
                "color" => "theme-3",
                "cust_theme_bg" => "on",
                "cust_darklayout" => "off",

                "CURRENCY_NAME" => "USD",
                "CURRENCYCURRENCY" => "$",
                "currency_format" => "1",
                "defult_currancy" => "USD",
                "defult_language" => "en",
                "defult_timezone" => "Asia/Kolkata",

                // for cookie
                'enable_cookie'=>'on',
                'cookie_logging'=>'on',
                'necessary_cookies'=>'on',
                'cookie_title'=>'We use cookies!',
                'cookie_description'=>'Hi, this website uses essential cookies to ensure its proper operation and tracking cookies to understand how you interact with it',
                'strictly_cookie_title'=>'Strictly necessary cookies',
                'strictly_cookie_description'=>'These cookies are essential for the proper functioning of my website. Without these cookies, the website would not work properly',
                'more_information_description'=>'For any queries in relation to our policy on cookies and your choices, please contact us',
                "more_information_title" => "",
                'contactus_url'=>'#',

            ];
        }

        $settingQuery = Setting::query();
        foreach($settings as $key => $value) {
            $exist = (clone $settingQuery)->where('name', $key)->where('theme_id', $themeId)->where('store_id', $storeId)->first();
            if (!$exist) {
                (clone $settingQuery)->create([
                    'name' => $key,
                    'value' => (string) $value,
                    'theme_id' => $themeId,
                    'store_id' => $storeId,
                    'created_by' => $user->id
                ]);
            }
        }
        Utility::WhatsappMeassage($user->id);
        return true;
    }
}

if (!function_exists('currency_format_with_sym')) {

    function currency_format_with_sym($price, $store_id = null, $theme_id = null)
    {
        if (!empty($store_id) && empty($theme_id)) {
            $company_settings = getAdminAllSetting(null, $store_id, null);
        } elseif (!empty($store_id) && !empty($theme_id)) {
            $company_settings = getAdminAllSetting(null, $store_id, $theme_id);
        } else {
            $company_settings = getAdminAllSetting();
        }

        $symbol_position = 'pre';
        $currancy_symbol = '$';
        $format = '1';
        $number = explode('.', $price);
        $length = strlen(trim($number[0]));

        if ($length > 3) {
            $decimal_separator  = isset($company_settings['decimal_separator']) && $company_settings['decimal_separator'] === 'dot' ? '.' : '.';
            $thousand_separator = isset($company_settings['thousand_separator']) && $company_settings['thousand_separator'] === 'dot' ? '.' : '.';
        } else {
            $decimal_separator  = isset($company_settings['decimal_separator']) == 'dot'  ? '.' : '.';

            $thousand_separator = isset($company_settings['thousand_separator']) == 'dot' ? '.' : '.';
        }
        if (isset($company_settings['site_currency_symbol_position'])) {
            $symbol_position = $company_settings['site_currency_symbol_position'];
        }
        if (isset($company_settings['defult_currancy_symbol'])) {
            $currancy_symbol = $company_settings['defult_currancy_symbol'];
        }
        if (isset($company_settings['currency_format'])) {
            $format = $company_settings['currency_format'];
        }
        if (isset($company_settings['currency_space'])) {
            $currency_space = isset($company_settings['currency_space']) ? $company_settings['currency_space'] : '';
        }
        if (isset($company_settings['site_currency_symbol_name'])) {
            $defult_currancy = $company_settings['defult_currancy'];
            $defult_currancy_symbol = $company_settings['defult_currancy_symbol'];
            $currancy_symbol = $company_settings['site_currency_symbol_name'] == 'symbol' ? $defult_currancy_symbol : $defult_currancy;
        }

        return (
            ($symbol_position == "pre")  ?  $currancy_symbol : '') . ((isset($currency_space) && $currency_space) == 'withspace' ? ' ' : '')
            . number_format($price, $format, $decimal_separator, $thousand_separator) . ((isset($currency_space) && $currency_space) == 'withspace' ? ' ' : '') .
            (($symbol_position == "post") ?  $currancy_symbol : '');
    }
}


// module alias name
if(! function_exists('Module_Alias_Name'))
{
    function Module_Alias_Name($module_name)
    {
        static $addons = [];
        static $resultArray = [];
        if(count($addons) == 0 && count($resultArray) == 0 )
        {
            $addons = AddOnManager::all()->toArray();
            $resultArray = array_reduce($addons, function ($carry, $item) {
                // Check if both "module" and "name" keys exist in the current item
                if (isset($item['module']) && isset($item['name'])) {
                    // Add a new key-value pair to the result array
                    $carry[$item['module']] = $item['name'];
                }
                return $carry;
            }, []);
        }

        $module = Module::find($module_name);
        if(isset($resultArray))
        {
           $module_name =  array_key_exists($module_name,$resultArray) ? $resultArray[$module_name] : ( !empty($module) ? $module->get('alias') : $module_name );
        }
        elseif(!empty($module))
        {
            $module_name = $module->get('alias');
        }
        return $module_name;
    }
}

if (! function_exists('get_permission_by_module')) {
    function get_permission_by_module($mudule){
        $user = auth()->user();

        // if($user->type == 'super admin')
        //     {
            $permissions = Permission::orderBy('name')->get();
               // $permissions = Permission::where('module',$mudule)->orderBy('name')->get();
           // }
            // else
            // {
            //     $permissions = new Collection();
            //     foreach($user->roles as $role)
            //     {
            //         $permissions = $permissions->merge($role->permissions);
            //     }
            //     $permissions = $permissions->where('module', $mudule);

            // }
        // $permissions = Spatie\Permission\Models\Permission::where('module',$mudule)->orderBy('name')->get();
        return $permissions;
    }


    if (!function_exists('sideMenuCacheForget')) {
        function sideMenuCacheForget($type = null, $user_id = null)
        {
            if ($type == 'all') {
                Cache::flush();
            }

            if (!empty($user_id)) {
                $user = User::find($user_id);
            } else {
                $user =  auth()->user();
            }
            if ($user->type == 'admin') {
                $users = User::select('id')->where('created_by', $user->id)->pluck('id');
                foreach ($users as $id) {
                    try {
                        $key = 'sidebar_menu_' . $id;
                        Cache::forget($key);
                    } catch (\Exception $e) {
                        \Log::error('comapnySettingCacheForget :' . $e->getMessage());
                    }
                }
                try {
                    $key = 'sidebar_menu_' . $user->id;
                    Cache::forget($key);
                } catch (\Exception $e) {
                    \Log::error('comapnySettingCacheForget :' . $e->getMessage());
                }
                return true;
            }

            try {
                $key = 'sidebar_menu_' . $user->id;
                Cache::forget($key);
            } catch (\Exception $e) {
                \Log::error('comapnySettingCacheForget :' . $e->getMessage());
            }

            return true;
        }
    }


}

if (!function_exists('module_is_active')) {
    function module_is_active($module, $user_id = null)
    {
        if (Module::has($module)) {
            $module = Module::find($module);
            if ($module->isEnabled()) {
                if (Auth::check()) {
                    $user = Auth::user();
                } elseif ($user_id != null) {
                    $user = User::find($user_id);
                }
                if (!empty($user)) {
                    if ($user->type == 'super admin') {
                        return true;
                    } else {
                        $active_module = ActivatedModule($user->id);
                        if ((count($active_module) > 0 && in_array($module->getName(), $active_module))) {
                            return true;
                        }
                        return false;
                    }
                }
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}
if (!function_exists('ActivatedModule')) {
    function ActivatedModule($user_id = null)
    {
        $available_modules = array_keys(Module::getByStatus(1));

        // dd($user_active_module);
        return $available_modules;
    }
}

if (!function_exists('get_module_img')) {
    function get_module_img($module)
    {
        $url = url("/Modules/" . $module . '/favicon.png');
        return $url;
    }
}

if (!function_exists('admin_setting')) {
    function admin_setting($key)
    {
        if ($key) {
            $admin_settings = getAdminAllSetting();
            $setting = (array_key_exists($key, $admin_settings)) ? $admin_settings[$key] : null;
            return $setting;
        }
    }
}

if (!function_exists('check_file')) {
    function check_file($path)
    {

        if (!empty($path)) {
            $storage_settings = getAdminAllSetting();
            if (isset($storage_settings['storage_setting']) && ($storage_settings['storage_setting'] == null || $storage_settings['storage_setting'] == 'local')) {

                return file_exists(base_path($path));
            } else {

                if (isset($storage_settings['storage_setting']) && $storage_settings['storage_setting'] == 's3') {
                    config(
                        [
                            'filesystems.disks.s3.key' => $storage_settings['s3_key'],
                            'filesystems.disks.s3.secret' => $storage_settings['s3_secret'],
                            'filesystems.disks.s3.region' => $storage_settings['s3_region'],
                            'filesystems.disks.s3.bucket' => $storage_settings['s3_bucket'],
                            'filesystems.disks.s3.url' => $storage_settings['s3_url'],
                            'filesystems.disks.s3.endpoint' => $storage_settings['s3_endpoint'],
                        ]
                    );
                } else if (isset($storage_settings['storage_setting']) && $storage_settings['storage_setting'] == 'wasabi') {
                    config(
                        [
                            'filesystems.disks.wasabi.key' => $storage_settings['wasabi_key'],
                            'filesystems.disks.wasabi.secret' => $storage_settings['wasabi_secret'],
                            'filesystems.disks.wasabi.region' => $storage_settings['wasabi_region'],
                            'filesystems.disks.wasabi.bucket' => $storage_settings['wasabi_bucket'],
                            'filesystems.disks.wasabi.root' => $storage_settings['wasabi_root'],
                            'filesystems.disks.wasabi.endpoint' => $storage_settings['wasabi_url']
                        ]
                    );
                }
                try {
                    return  Storage::disk($storage_settings['storage_setting'])->exists($path);
                } catch (\Throwable $th) {
                    return 0;
                }
            }
        } else {
            return 0;
        }
    }
}


// module price name
if (!function_exists('ModulePriceByName')) {
    function ModulePriceByName($module_name)
    {
        static $addons = [];
        static $resultArray = [];
        if (count($addons) == 0 && count($resultArray) == 0) {
            $addons = AddOnManager::all()->toArray();
            $resultArray = array_reduce($addons, function ($carry, $item) {
                // Check if both "module" and "name" keys exist in the current item
                if (isset($item['module'])) {
                    // Add a new key-value pair to the result array
                    $carry[$item['module']]['monthly_price'] = $item['monthly_price'];
                    $carry[$item['module']]['yearly_price'] = $item['yearly_price'];
                }
                return $carry;
            }, []);
        }

        $module = Module::find($module_name);
        $data = [];
        $data['monthly_price'] = 0;
        $data['yearly_price'] = 0;

        if (!empty($module)) {
            $path = $module->getPath() . '/module.json';
            $json = json_decode(file_get_contents($path), true);

            $data['monthly_price'] = (isset($json['monthly_price']) && !empty($json['monthly_price'])) ? $json['monthly_price'] : 0;
            $data['yearly_price'] = (isset($json['yearly_price']) && !empty($json['yearly_price'])) ? $json['yearly_price'] : 0;
        }

        if (isset($resultArray)) {
            $data['monthly_price'] = isset($resultArray[$module_name]['monthly_price']) ? $resultArray[$module_name]['monthly_price'] : $data['monthly_price'];
            $data['yearly_price'] = isset($resultArray[$module_name]['yearly_price']) ? $resultArray[$module_name]['yearly_price'] : $data['yearly_price'];
        }

        return $data;
    }
}

if (!function_exists('getshowModuleList')) {
    function getshowModuleList()
    {
        $all = Module::getOrdered();
        $list = [];
        foreach ($all as $module) {
            $path = $module->getPath() . '/module.json';
            $json = json_decode(file_get_contents($path), true);
            if (!isset($json['display']) || $json['display'] == true) {
                array_push($list, $module->getName());
            }
        }
        return $list;
    }
}

if(! function_exists('sidebar_logo')){
    function sidebar_logo(){
        $admin_settings = getSuperAdminAllSetting();
        if(\Auth::check() && (\Auth::user()->type != 'super admin'))
        {
            $company_settings = getAdminAllSetting();

            if((isset($company_settings['cust_darklayout']) ? $company_settings['cust_darklayout'] : 'off') == 'on')
            {
                if(!empty($company_settings['logo_light']))
                {
                    if(check_file($company_settings['logo_light']))
                    {
                        return $company_settings['logo_light'];
                    }
                    else
                    {
                        return 'storage/uploads/logo/logo-light.png';
                    }
                }else{
                    if(!empty($admin_settings['logo_light']))
                    {
                        if(check_file($admin_settings['logo_light']))
                        {
                            return $admin_settings['logo_light'];
                        }
                        else
                        {
                            return 'storage/uploads/logo/logo-light.png';
                        }
                    }else{
                        return 'storage/uploads/logo/logo-light.png';
                    }
                }
            }else{
                if(!empty($company_settings['logo_dark'])){
                    if(check_file($company_settings['logo_dark']))
                    {
                        return $company_settings['logo_dark'];
                    }
                    else
                    {
                        return 'uploads/logo/logo_dark.png';
                    }
                }else{
                    if(!empty($admin_settings['logo_dark'])){
                        if(check_file($admin_settings['logo_dark']))
                        {
                            return $admin_settings['logo_dark'];
                        }
                        else
                        {
                            return 'uploads/logo/logo_dark.png';
                        }
                    }else{
                        return 'uploads/logo/logo_dark.png';
                    }

                }
            }
        }
        else
        {
            if((isset($admin_settings['cust_darklayout']) ? $admin_settings['cust_darklayout'] : 'off') == 'on')
            {
                if(!empty($admin_settings['logo_light']))
                {
                    if(check_file($admin_settings['logo_light']))
                    {
                        return $admin_settings['logo_light'];
                    }
                    else
                    {
                        return 'storage/uploads/logo/logo-light.png';
                    }
                }else{
                    return 'storage/uploads/logo/logo-light.png';
                }
            }
            else
            {
                if(!empty($admin_settings['logo_dark'])){
                    if(check_file($admin_settings['logo_dark']))
                    {
                        return $admin_settings['logo_dark'];
                    }
                    else
                    {
                        return 'uploads/logo/logo_dark.png';
                    }
                }else{
                    return 'uploads/logo/logo_dark.png';
                }
            }
        }
    }
}

if(! function_exists('light_logo'))
{
    function light_logo(){
        if(\Auth::check())
        {
            $company_settings = getAdminAllSetting();
            $logo_light = isset($company_settings['logo_light']) ? $company_settings['logo_light'] : 'storage/uploads/logo/logo-light.png';
        }
        else{
            $admin_settings = getSuperAdminAllSetting();
            $logo_light = isset($admin_settings['logo_light']) ? $admin_settings['logo_light'] : 'storage/uploads/logo/logo-light.png';
        }
        if(check_file($logo_light))
        {
            return $logo_light;
        }
        else
        {
            return 'uploads/logo/logo_dark.png';
        }
    }
}

if(! function_exists('dark_logo')){
    function dark_logo(){
        if(\Auth::check())
        {
            $company_settings = getCompanyAllSetting();
            $logo_dark = isset($company_settings['logo_dark']) ? $company_settings['logo_dark'] :'uploads/logo/logo_dark.png';
        }
        else{
            $admin_settings = getSuperAdminAllSetting();
            $logo_dark = isset($admin_settings['logo_dark']) ? $admin_settings['logo_dark'] :'uploads/logo/logo_dark.png';
        }
        if(check_file($logo_dark))
        {
            return $logo_dark;
        }
        else
        {
            return 'uploads/logo/logo_dark.png';
        }
    }
}

if(! function_exists('delete_file'))
{
    function delete_file($path)
    {
        if(check_file($path))
        {
            $storage_settings = getAdminAllSetting();
            if(isset($storage_settings['storage_setting']))
            {
                    if($storage_settings['storage_setting'] == 'local' )
                    {
                        return File::delete($path);
                    }
                    else
                    {
                        if( $storage_settings['storage_setting'] == 's3')
                        {
                            config(
                                [
                                    'filesystems.disks.s3.key' => $storage_settings['s3_key'],
                                    'filesystems.disks.s3.secret' => $storage_settings['s3_secret'],
                                    'filesystems.disks.s3.region' => $storage_settings['s3_region'],
                                    'filesystems.disks.s3.bucket' => $storage_settings['s3_bucket'],
                                    'filesystems.disks.s3.url' => $storage_settings['s3_url'],
                                    'filesystems.disks.s3.endpoint' => $storage_settings['s3_endpoint'],
                                ]
                            );
                        }
                        else if($storage_settings['storage_setting'] == 'wasabi' ){
                        {
                            config(
                                [
                                    'filesystems.disks.wasabi.key' => $storage_settings['wasabi_key'],
                                    'filesystems.disks.wasabi.secret' => $storage_settings['wasabi_secret'],
                                    'filesystems.disks.wasabi.region' => $storage_settings['wasabi_region'],
                                    'filesystems.disks.wasabi.bucket' => $storage_settings['wasabi_bucket'],
                                    'filesystems.disks.wasabi.root' => $storage_settings['wasabi_root'],
                                    'filesystems.disks.wasabi.endpoint' => $storage_settings['wasabi_url']
                                ]
                            );
                        }
                        return Storage::disk($storage_settings['storage_setting'])->delete($path);
                    }
                }
            }
        }
    }
}

if(! function_exists('get_size'))
{
    function get_size($url){
        $url=str_replace(' ', '%20', $url);
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, TRUE);
        curl_setopt($ch, CURLOPT_NOBODY, TRUE);

        $data = curl_exec($ch);
        $size = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);

        curl_close($ch);
        return $size;
    }
}
if(! function_exists('delete_folder'))
{
    function delete_folder($path)
    {
        $storage_settings = getAdminAllSetting();
        if(isset($storage_settings['storage_setting']))
        {

            if($storage_settings['storage_setting'] == 'local' )
            {
                if(is_dir(Storage::path($path)))
                {
                    return \File::deleteDirectory(Storage::path($path));
                }
            }
            else
            {
                if( $storage_settings['storage_setting'] == 's3')
                {
                    config(
                        [
                            'filesystems.disks.s3.key' => $storage_settings['s3_key'],
                            'filesystems.disks.s3.secret' => $storage_settings['s3_secret'],
                            'filesystems.disks.s3.region' => $storage_settings['s3_region'],
                            'filesystems.disks.s3.bucket' => $storage_settings['s3_bucket'],
                            'filesystems.disks.s3.url' => $storage_settings['s3_url'],
                            'filesystems.disks.s3.endpoint' => $storage_settings['s3_endpoint'],
                        ]
                    );
                }
                else if($storage_settings['storage_setting'] == 'wasabi' )
                {
                    config(
                        [
                            'filesystems.disks.wasabi.key' => $storage_settings['wasabi_key'],
                            'filesystems.disks.wasabi.secret' => $storage_settings['wasabi_secret'],
                            'filesystems.disks.wasabi.region' => $storage_settings['wasabi_region'],
                            'filesystems.disks.wasabi.bucket' => $storage_settings['wasabi_bucket'],
                            'filesystems.disks.wasabi.root' => $storage_settings['wasabi_root'],
                            'filesystems.disks.wasabi.endpoint' => $storage_settings['wasabi_url']
                        ]
                    );
                }
                return Storage::disk($storage_settings['storage_setting'])->deleteDirectory($path);
            }
        }
    }
}
if (!function_exists('delete_directory'))
{
    function delete_directory($dir)
    {
        if (!file_exists($dir)) {
            return true;
        }

        if (!is_dir($dir)) {
            return unlink($dir);
        }

        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }

            if (!delete_directory($dir . DIRECTORY_SEPARATOR . $item)) {
                return false;
            }
        }

        return rmdir($dir);
    }
}
