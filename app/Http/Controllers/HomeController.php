<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Utility;
use App\Models\{Customer, Country, Order, PlanOrder, Plan, PlanCoupon, PlanRequest, Store, Setting, User,OrderBillingDetail , PixelFields, Page};
use App\Models\Faq;
use App\Models\MainCategory;
use App\Models\SubCategory;
use App\Models\BlogCategory;
use App\Models\Blog;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\{FlashSale, ProductQuestion, Testimonial, Wishlist,TaxOption};
use Qirolab\Theme\Theme;
use App\Http\Controllers\Api\ApiController;
use Shetabit\Visitor\VisitorFacade as Visitor;
use Nwidart\Modules\Facades\Module;
use App\Models\AddOn;
use App\Models\AddOnManager;
use App\Models\ProductBrand;

class HomeController extends Controller
{
    public function __construct(Request $request)
    {
/* 
        if(!file_exists(storage_path() . "/installed"))
        {
            header('location:install');
            die;
        } */
    }

    /**
     * Display a listing of the resource.
     */

    public function Landing()
    {
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
        if($enable_domain || $enable_subdomain) {

            if($subdomain){
                $enable_subdomain = Setting::where('name','enable_subdomain')->where('value','on')->where('store_id',$subdomain->store_id)->first();
                if($enable_subdomain){
                    $admin = User::find($enable_subdomain->created_by);

                    if($enable_subdomain->value == 'on' &&  $enable_subdomain->store_id == $admin->current_store){
                        $store = Store::find($admin->current_store);
                        if($store){
                            return $this->storeSlug($store->slug);
                        }
                    }  elseif ($enable_subdomain->value == 'on' &&  $enable_subdomain->store_id != $admin->current_store){
                        $store = Store::find($enable_subdomain->store_id);
                        if($store){
                            return $this->storeSlug($store->slug);
                        }
                    }
                    else{
                        return $this->storeSlug($segments);
                    }
                }
            }


            if($domain){
                $enable_domain = Setting::where('name','enable_domain')->where('value','on')->where('store_id',$domain->store_id)->first();


                if($enable_domain){
                    $admin = User::find($enable_domain->created_by);

                    if($enable_domain->value == 'on' &&  $enable_domain->store_id == $admin->current_store){
                        $store = Store::find($admin->current_store);
                        if($store){
                            return $this->storeSlug($store->slug);
                        }
                    } elseif ($enable_domain->value == 'on' &&  $enable_domain->store_id != $admin->current_store){
                        $store = Store::find($enable_domain->store_id);
                        if($store){
                            return $this->storeSlug($store->slug);
                        }
                    }
                    else{
                        return $this->storeSlug($segments);
                    }
                }
            }
        }
        else{
            $settings = getSuperAdminAllSetting();
            if(isset($settings['display_landing']) && $settings['display_landing'] == 'on')
            {
                return view('landingpage::layouts.landingpage');
            }
            else
            {
                return redirect('login');
            }
        }
    }
    public function form(){
        $user = auth()->user();
return view('form_page', compact('user'));
    }

    public function index()
    {
        $user = auth()->user();
        Utility::defaultEmail();
        Utility::userDefaultData($user->id);
        if (auth()->user()->type == 'super admin') {
            $user['total_user'] = $user->countCompany();
            $user['total_orders'] = PlanOrder::total_orders();
            $user['total_plan'] = Plan::total_plan();
            $chartData = $this->getOrderChart(['duration' => 'week']);
            $topAdmins = $user->createdAdmins()
                ->with('stores')
                ->withCount('stores')
                ->orderBy('stores_count', 'desc')
                ->limit(5)
                ->get();

            $vistor = \DB::table('shetabit_visits')->pluck('store_id','id')->toarray();
            // Remove null values from the array
            $vistor = array_filter($vistor, function($value) {
                return $value !== null;
            });
            $visitors = array_count_values($vistor);

            arsort($visitors); // Sort the array in descending order maintaining index association
            $visitors = array_slice($visitors, 0, 5, true); // Get the top 5 counts
            $plan_order = Plan::most_purchese_plan();
            $coupons = PlanCoupon::get();
            $maxValue = 0; // Initialize with a minimum value
            $couponName = '';
            foreach ($coupons as $coupon) {
                $max = $coupon->used_coupon();
                if ($max > $maxValue) {
                    $maxValue = $max;
                    $couponName = $coupon->name;
                }
            }

            $allStores = Order::select('store_id', \DB::raw('SUM(final_price) as total_amount'))
                        ->groupBy('store_id')
                        ->orderByDesc('total_amount')
                        ->limit(5)
                        ->get();
            $plan_requests = PlanRequest::all()->count();
            return view('superadmin.dashboard', compact('user','chartData','couponName','plan_order','plan_requests','allStores','topAdmins', 'visitors'));
        } else {
            $productQuery = Product::where('theme_id', APP_THEME())->where('store_id',getCurrentStore());
            $orderQuery = Order::where('theme_id', APP_THEME())->where('store_id',getCurrentStore());
            $totalproduct = (clone $productQuery)->count();

            $totle_order =  (clone $orderQuery)->count();
            $totle_sales = Customer::where('theme_id', APP_THEME())->where('store_id',getCurrentStore())->count();
            $totle_cancel_order = (clone $orderQuery)->where('delivered_status',2)->count();

            // $total_refund_requests = OrderRefund::where('theme_id', APP_THEME())->where('refund_status','Refunded')->where('store_id', getCurrentStore())->count();

            $total_revenues = (clone $orderQuery)->where(function ($query) {
                $query->where(function ($subquery) {
                    $subquery->where('delivered_status', '!=', 2)
                            ->where('delivered_status', '!=', 3);
                })->orWhere('return_status', '!=', 2);
                })
                ->sum('final_price');
            $topSellingProductIds = (clone $orderQuery)->get()
                ->pluck('product_id')
                ->flatMap(function ($productIds) {
                    return explode(',', $productIds);
                })
                ->map(function ($productId) {
                    return (int)$productId;
                })
                ->groupBy(function ($productId) {
                    return $productId;
                })
                ->map(function ($group) {
                    return $group->count();
                })
                ->sortDesc()
                ->take(5)
                ->keys();

            $topSellingProducts = (clone $productQuery)->whereIn('id', $topSellingProductIds)->get();
            $theme_name = !empty(env('DATA_INSERT_APP_THEME')) ? env('DATA_INSERT_APP_THEME') : APP_THEME();
            $out_of_stock_threshold =\App\Models\Utility::GetValueByName('out_of_stock_threshold',$theme_name);
            $latests   = (clone $productQuery)->orderBy('created_at', 'Desc')->where('theme_id', APP_THEME())->where('store_id',getCurrentStore())->limit(5)->get();
            $new_orders =(clone $orderQuery)->orderBy('id', 'DESC')->limit(5)->get();
            $chartData = $this->getOrderChart(['duration' => 'week']);

            $topSellingProductIds = Order::where('theme_id', APP_THEME())
                ->where('store_id', getCurrentStore())
                ->get()
                ->pluck('product_id')
                ->flatMap(function ($productIds) {
                    return explode(',', $productIds);
                })
                ->map(function ($productId) {
                    return (int)$productId;
                })
                ->groupBy(function ($productId) {
                    return $productId;
                })
                ->map(function ($group) {
                    return $group->count();
                })
                ->sortDesc()
                ->take(5)
                ->keys();

            $topSellingProducts = (clone $productQuery)->whereIn('id', $topSellingProductIds)->get();


            $store = Store::where('id',getCurrentStore())->first();
            $slug = $store->slug;
            $storage_limit = 0;
            //for storage
            $users = User::find(auth()->user()->creatorId());
            if ($users) {
                $plan = Plan::find($users->plan_id);
                if($plan && $plan->storage_limit > 0)
                {
                    $storage_limit = ($users->storage_limit / $plan->storage_limit) * 100;
                }
            }
            $theme_url = route('landing_page',$slug);
            return view('dashboard', compact('totalproduct','totle_order','totle_sales','latests','new_orders','chartData','theme_url','store','users','plan','storage_limit','topSellingProducts','total_revenues','totle_cancel_order','out_of_stock_threshold','theme_name'));
        }
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function getOrderChart($arrParam)
    {
        $user = auth()->user();
        if (!$user) {
            return redirect()->route('login')->with('message', 'You have been logged out.');
        }
        $store = Store::where('id',$user->current_store)->first();

        if (!$store) {
            if (auth()->check()) {
                auth()->logout();
            }

            return redirect()->route('login')->with('message', 'You have been logged out.');
        }
        // $userstore = $this->APP_THEME;

        $userstore = $store->theme_id ?? '';
        $arrDuration = [];
        if ($arrParam['duration']) {
            if ($arrParam['duration'] == 'week') {
                $previous_week = strtotime("-1 week +1 day");

                for ($i = 0; $i < 7; $i++) {
                    $arrDuration[date('Y-m-d', $previous_week)] = date('d-M', $previous_week);
                    $previous_week = strtotime(date('Y-m-d', $previous_week) . " +1 day");
                }
            }
        }
        $arrTask = [];
        $arrTask['label'] = [];
        $arrTask['data'] = [];
        $registerTotal = '';
        $newguestTotal = '';
        foreach ($arrDuration as $date => $label) {
            if (auth()->user()->type == 'admin') {
                $data = Order::select(\DB::raw('count(*) as total'))
                    ->where('theme_id', $userstore)
                    ->where('store_id', getCurrentStore())
                    ->whereDate('created_at', '=', $date)
                    ->first();

                $registerTotal = Customer::select(\DB::raw('count(*) as total'))
                    ->where('theme_id', $userstore)
                    ->where('store_id', getCurrentStore())
                    ->where('regiester_date', '!=', NULL)
                    ->whereDate('regiester_date', '=', $date)
                    ->first();

                $newguestTotal = Customer::select(\DB::raw('count(*) as total'))
                    ->where('theme_id', $userstore)
                    ->where('store_id', getCurrentStore())
                    ->where('regiester_date', '=', NULL)
                    ->whereDate('last_active', '=', $date)
                    ->first();
            } else {
                $data = PlanOrder::select(\DB::raw('count(*) as total'))
                    ->whereDate('created_at', '=', $date)
                    ->first();
            }

            $arrTask['label'][] = $label;
            $arrTask['data'][] = $data ? $data->total : 0; // Check if $data is not null

            if (auth()->user()->isAbleTo('Manage Dashboard')) {
                $arrTask['registerTotal'][] = $registerTotal ? $registerTotal->total : 0; // Check if $registerTotal is not null
                $arrTask['newguestTotal'][] = $newguestTotal ? $newguestTotal->total : 0; // Check if $newguestTotal is not null
            }
        }

        return $arrTask;
    }

    public function landing_page($storeSlug)
    {
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


            if($subdomain){
                $enable_subdomain = Setting::where('name','enable_subdomain')->where('value','on')->where('store_id',$subdomain->store_id)->first();
                if($enable_subdomain){
                    $admin = User::find($enable_subdomain->created_by);
                    if($enable_subdomain->value == 'on' &&  $enable_subdomain->store_id == $admin->current_store){
                        $store = Store::find($admin->current_store);
                        if($store){
                            return $this->storeSlug($store->slug);
                        }
                    }
                    else{
                        return $this->storeSlug($segments);
                    }
                }
            }


            if($domain){
                $enable_domain = Setting::where('name','enable_domain')->where('value','on')->where('store_id',$domain->store_id)->first();
                if($enable_domain){
                    $admin = User::find($enable_domain->created_by);
                    if($enable_domain->value == 'on' &&  $enable_domain->store_id == $admin->current_store){
                        $store = Store::find($admin->current_store);
                        if($store){
                            return $this->storeSlug($store->slug);
                        }
                    }
                    else{
                        return $this->storeSlug($segments);
                    }
                }
            }
        }
        else{
            return $this->storeSlug($segments);
        }


    }

    private function storeSlug($storeSlug) {
        $theme_id = GetCurrenctTheme($storeSlug);
        Theme::set($theme_id);
        $data = getThemeSections($theme_id, $storeSlug, true, true);
        visitor()->visit();
        // Get Data from database
        $sqlData = getHomePageDatabaseSectionDataFromDatabase($data);

        return view('main_file', $data + $sqlData);
    }

    public function faqs_page(Request $request,$storeSlug)
    {
        $store = Store::where('slug',$storeSlug)->first();
        if (!$store) {
            abort(404);
        }
        $currentTheme = $store->theme_id;
        $slug = $store->slug;
        Theme::set($currentTheme);
        $languages = Utility::languages();
        $faqs = Faq::where('theme_id', $currentTheme)->where('store_id',$store->id)->get();
        $currantLang = \Cookie::get('LANGUAGE') ?? $store->default_language;
        $data = getThemeSections($currentTheme, $storeSlug, true, true);
        // Get Data from database
        $sqlData = getHomePageDatabaseSectionDataFromDatabase($data);
        $section = (object) $data['section'];
        $topNavItems = [];
        $menu_id = (array) $section->header->section->menu_type->menu_ids ??
        [];
        $topNavItems = get_nav_menu($menu_id);
        return view('front_end.sections.pages.faq_page_section', compact('faqs','currentTheme','currantLang', 'store','section','topNavItems')+$data+$sqlData);
    }

    public function blog_page(Request $request,$storeSlug)
    {
        $store = Store::where('slug',$storeSlug)->first();
        if (!$store) {
            abort(404);
        }
        $store_id = $store->id;
        $slug = $store->slug;
        $currentTheme = $store->theme_id;
        Theme::set($currentTheme);
        $currantLang = \Cookie::get('LANGUAGE') ?? $store->default_language;

        $data = getThemeSections($currentTheme, $storeSlug, true, true);

        $section = (object) $data['section'];
        // Get Data from database
        $sqlData = getHomePageDatabaseSectionDataFromDatabase($data);
        $topNavItems = [];
        $menu_id = (array) $section->header->section->menu_type->menu_ids ??
        [];
        $topNavItems = get_nav_menu($menu_id);

        $BlogCategory = BlogCategory::where('theme_id', $currentTheme)->where('store_id', $store_id)->get()->pluck('name','id');
        $BlogCategory->prepend('All','0');

        $blogs = Blog::where('theme_id', $currentTheme)->where('store_id',$store_id)->get();

        return view('front_end.sections.pages.blog_page_section', compact('BlogCategory','currentTheme','currantLang','store','section','topNavItems','blogs')+$data+$sqlData);
    }

    public function article_page(Request $request,$storeSlug,$id)
    {
        $store = Store::where('slug',$storeSlug)->first();
        if (!$store) {
            abort(404);
        }
        $store_id = $store->id;
        $slug = $store->slug;
        $currentTheme = $store->theme_id;
        Theme::set($currentTheme);
        $blogs = Blog::where('id' ,$id)->where('store_id',$store_id)->get();
        if($blogs->isEmpty()){
            abort(404);
        }

        $datas = Blog::where('theme_id', $currentTheme)->where('store_id',$store_id)->inRandomOrder()
                ->limit(3)
                ->get();

        $l_articles = Blog::where('theme_id', $currentTheme)->where('store_id',$store_id)->inRandomOrder()
                ->limit(5)
                ->get();

        $BlogCategory = BlogCategory::where('theme_id', $currentTheme)->where('store_id',$store_id)->get()->pluck('name','id');
        $BlogCategory->prepend('All Products','0');
        $homeproducts = Product::where('theme_id', $currentTheme)->where('store_id',$store_id)->get();
        $currantLang = \Cookie::get('LANGUAGE') ?? $store->default_language;
        $blog1 = Blog::where('theme_id', $currentTheme)->where('store_id',$store_id)->get();

        $data = getThemeSections($currentTheme, $storeSlug, true, true);
        $section = (object) $data['section'];
        // Get Data from database
        $sqlData = getHomePageDatabaseSectionDataFromDatabase($data);
        $topNavItems = [];
        $menu_id = (array) $section->header->section->menu_type->menu_ids ??
        [];
        $topNavItems = get_nav_menu($menu_id);
        return view('front_end.sections.pages.article', compact('currantLang','currentTheme','blogs','datas','l_articles','BlogCategory','homeproducts','blog1','section','topNavItems')+$data+$sqlData);

    }

    public function product_page(Request $request ,$storeSlug, $categorySlug = null)
    {
        $store = Store::where('slug',$storeSlug)->first();
        if (!$store) {
            abort(404);
        }

        $store_id = $store->id;
        $slug = $store->slug;
        $currentTheme = $store->theme_id;
        $category_ids = [];
        if ($categorySlug) {
            $category_ids = MainCategory::where('name', 'like', $categorySlug)->where('theme_id', $currentTheme)->where('store_id',$store_id)->pluck('id')->toArray();
        }
        Theme::set($currentTheme);
        $languages = Utility::languages();
        $faqs = Faq::where('theme_id', $currentTheme)->where('store_id',$store_id)->get();
        $currantLang = \Cookie::get('LANGUAGE') ?? $store->default_language;
        $data = getThemeSections($currentTheme, $storeSlug, true, true);
        $section = (object) $data['section'];
        // Get Data from database
        $sqlData = getHomePageDatabaseSectionDataFromDatabase($data);
        $topNavItems = [];
        $menu_id = (array) $section->header->section->menu_type->menu_ids ??
        [];
        $topNavItems = get_nav_menu($menu_id);

        $filter_product = $request->filter_product;
        $MainCategoryList = MainCategory::where('status', 1)->where('theme_id', $currentTheme)->where('store_id',$store_id)->get();
        $SubCategoryList =SubCategory::where('status', 1)->where('theme_id', $currentTheme)->where('store_id',$store_id)->get();;
        $filter_tag = $SubCategoryList;
        $has_subcategory = Utility::ThemeSubcategory($currentTheme);
        $search_products = Product::where('theme_id',$currentTheme)->where('store_id',$store_id)->get()->pluck('name','id');
        $ApiController = new ApiController();

        $featured_products_data = $ApiController->featured_products($request, $store->slug);
        $featured_products = $featured_products_data->getData();
        $brands = ProductBrand::where('status', 1)->where('theme_id', $currentTheme)->where('store_id',$store_id)->get();
        if (!$has_subcategory) {
            $filter_tag = $MainCategoryList;
        }
        $sub_category_select = $brand_select = [];
        $main_category = $request->main_category;
        $category_slug = $request->category_slug;
        $sub_category = $request->sub_category;
        $product_brand = $request->brands;
        if(!empty($main_category)) {
            if (!$has_subcategory) {
                $sub_category_select = MainCategory::where('id', $main_category)->pluck('id')->toArray();
            } else {
                $sub_category_select = SubCategory::where('maincategory_id', $main_category)->pluck('id')->toArray();
            }
        }

        if(!empty($product_brand)) {
            $brand_select = ProductBrand::where('id', $product_brand)->pluck('id')->toArray();
        }

        if (is_array($sub_category_select) && count($sub_category_select) == 0 && isset($category_slug)) {
            $sub_category_select = MainCategory::where('slug', $category_slug)->pluck('id')->toArray();
        }
        if(!empty($sub_category)) {
            $sub_category_select = [];
            $sub_category_select[] = $sub_category;
        }
        // bestseller
        $per_page = '12';
        $destination = 'web';
        $bestSeller_fun = Product::bestseller_guest($currentTheme, $store_id, $per_page, $destination);
        $bestSeller = [];
        if($bestSeller_fun['status'] == "success") {
            $bestSeller = $bestSeller_fun['bestseller_array'];
        }

        $products_query = Product::where('theme_id', $currentTheme)->where('store_id',$store_id)->where('status',1);
        if(!empty($main_category)) {
            $products_query->where('maincategory_id', $main_category);
        }
        if(!empty($sub_category)) {
            $products_query->where('subcategory_id', $sub_category);
        }
        if (count($category_ids) > 0) {
            $products_query->whereIn('maincategory_id', $category_ids);
        }

        if(!empty($product_brand)) {
            $products_query->where('brand_id', $product_brand);
        }

        $product_count = $products_query->count();
        $products = $products_query->get();

        /* For Filter */
        $min_price = 0;
        $max_price = Product::where('variant_product', 0)->orderBy('price', 'DESC')->where('theme_id', $currentTheme)->where('store_id',$store_id)->first();
        $max_price = !empty($max_price->price) ? $max_price->price : '0';

        $currency = Utility::GetValueByName('CURRENCY_NAME');
        $currency_icon = Utility::GetValueByName('CURRENCY');

        $MainCategory = MainCategory::where('theme_id', $currentTheme)->where('store_id',$store_id)->get()->pluck('name','id');
        $MainCategory->prepend('All Products','0');
        $homeproducts = Product::where('theme_id', $currentTheme)->where('store_id',$store_id)->get();

        $product_list = Product::orderBy('created_at', 'asc')->where('theme_id', $currentTheme)->where('store_id',$store->id)->limit(4)->get();

        $product_tag = implode(',', $category_ids);
        $compact = ['slug','MainCategoryList','SubCategoryList','bestSeller','currency', 'currency_icon', 'min_price', 'max_price','product_count','has_subcategory','filter_tag','search_products','sub_category_select','featured_products','filter_product','MainCategory','homeproducts', 'products','product_list', 'brands', 'brand_select', 'product_tag'];

        return view('front_end.sections.pages.product_list', compact($compact) + $data+$sqlData);
    }

    public function product_page_filter(Request $request, $storeSlug)
    {
        $store = Store::where('slug',$storeSlug)->first();
        $theme_id = $currentTheme = $store->theme_id;
        $store_id = $store->id;
        $slug = $storeSlug;
        Theme::set($store->theme_id);
        $has_subcategory = Utility::ThemeSubcategory($currentTheme);
        if($request->ajax()) {
            $page = $request->page;
            $filter_value = $request->filter_product;
            $product_tag = $request->product_tag;
            $min_price = $request->min_price;
            $max_price = $request->max_price;
            $rating = $request->rating;

        } else {
            $page = $request->query('page', 1);
            $filter_value = $request->query('filter_product');
            $product_tag = $request->query('product_tag');
            $min_price = $request->query('min_price');
            $max_price = $request->query('max_price');
            $rating = $request->query('rating');
            // $queryParams = $request->query();
            // $page = 1;
        }
        $filter_value = $request->filter_product;
        $product_tag = $request->product_tag;
        $product_brand = $request->product_brand;
        $min_price   = $request->min_price;
        $max_price   = $request->max_price;
        $rating      = $request->rating;


        if(!empty($product_tag))
        {
            $tag = $product_tag;
            $product_tag = explode(",", $tag);
        }

        $products_query = Product::where('theme_id', $theme_id)->where('store_id',$store_id)->where('status',1);
        if(!empty($product_tag)) {
            if (!$has_subcategory) {
                $products_query->whereIn('maincategory_id',$product_tag);
            } else {
                $products_query->whereIn('subcategory_id',$product_tag);
            }
        }

        if(!empty($product_brand)) {
            $products_query->whereIn('brand_id',$product_brand);
        }
        if(!empty($max_price)) {
            $products_query->whereBetween('price',[$min_price,$max_price]);
        }
        if(!empty($rating) && $rating != 'undefined') {
            $products_query->where('average_rating',$rating);
        }
        if(!empty($filter_value)) {
            if($filter_value == 'best-selling') {
               // $products_query->where('tag_api','best seller');
            }
            if($filter_value == 'trending') {
                $products_query->where('trending','1');
            }
            if($filter_value == 'title-ascending') {
                $products_query->orderBy('name', 'asc');
            }
            if($filter_value == 'title-descending') {
                $products_query->orderBy('name', 'Desc');
            }
            if($filter_value == 'price-ascending') {
                $products_query->orderBy('price', 'asc');
            }
            if($filter_value == 'price-descending') {
                $products_query->orderBy('price', 'Desc');
            }
            if($filter_value == 'created-ascending') {
                $products_query->orderBy('created_at', 'asc');
            }
            if($filter_value == 'created-descending') {
                $products_query->orderBy('created_at', 'Desc');
            }
        }

        $products = $products_query->paginate(12);
        $data = getThemeSections($currentTheme, $storeSlug, true, true);
        $section = (object) $data['section'];
        // Get Data from database
        $sqlData = getHomePageDatabaseSectionDataFromDatabase($data);
        $topNavItems = [];
        $menu_id = (array) $section->header->section->menu_type->menu_ids ??
        [];
        $topNavItems = get_nav_menu($menu_id);

        $currency = Utility::GetValueByName('CURRENCY_NAME');
        $currency_icon = Utility::GetValueByName('CURRENCY');
        $setting = getAdminAllSetting();
        $defaultTimeZone = isset($setting['defult_timezone']) ? $setting['defult_timezone'] : 'Asia/Kolkata';
        date_default_timezone_set($defaultTimeZone);
        $currentDateTime = date('Y-m-d H:i:s A');
        $tax_option = TaxOption::where('store_id', $store->id)
        ->where('theme_id', $store->theme_id)
        ->pluck('value', 'name')->toArray();
        return view('front_end.sections.pages.product_list_filter', compact('tax_option','currentTheme','slug','products', 'currency', 'page','currency_icon','currentDateTime','topNavItems') + $data + $sqlData)->render();
    }

    public function product_detail(Request $request, $storeSlug, $product_slug)
    {
        $product = Product::where('slug', $product_slug)->first();
        if (!$product) {
            abort(404);
        }
        $id = $product->id;
        $store = Store::where('slug',$storeSlug)->first();
        if (!$store) {
            abort(404);
        }
        $store_id = $store->id;
        $slug = $store->slug;
        $currentTheme = $store->theme_id;
        $storeId = $store->id;
        Theme::set($currentTheme);
        $languages = Utility::languages();
        $data = getThemeSections($currentTheme, $storeSlug, true, true);
        $section = (object) $data['section'];
        // Get Data from database
        $sqlData = getHomePageDatabaseSectionDataFromDatabase($data);
        $topNavItems = [];
        $menu_id = (array) $section->header->section->menu_type->menu_ids ??
        [];
        $topNavItems = get_nav_menu($menu_id);

        $MainCategoryList = MainCategory::where('status', 1)->where('theme_id', $currentTheme)->where('store_id',$storeId)->get();
        $SubCategoryList =SubCategory::where('status', 1)->where('theme_id', $currentTheme)->where('store_id',$storeId)->get();;
        $has_subcategory = Utility::ThemeSubcategory($currentTheme);
        $search_products = Product::where('theme_id',$currentTheme)->where('store_id',$storeId)->get()->pluck('name','id');
        $ApiController = new ApiController();

        $featured_products_data = $ApiController->featured_products($request, $store->slug);
        $featured_products = $featured_products_data->getData();


        $Stocks = ProductVariant::where('product_id', $id)->first();
        if ($Stocks) {
            $minPrice = ProductVariant::where('product_id', $id)->min('price');
            $maxPrice = ProductVariant::where('product_id', $id)->max('price');

            $min_vprice = ProductVariant::where('product_id', $id)->min('variation_price');
            $max_vprice = ProductVariant::where('product_id', $id)->max('variation_price');

            $mi_price = !empty($minPrice) ? $minPrice : $min_vprice;
            $ma_price = !empty($maxPrice) ? $maxPrice : $max_vprice;
        }
        else
        {
            $mi_price = 0;
            $ma_price = 0;
        }

        $currency = Utility::GetValueByName('CURRENCY_NAME') ?? '$';
        $currency_icon = Utility::GetValueByName('CURRENCY') ?? '$';

        $per_page = '12';
        $destination = 'web';
        $bestSeller_fun = Product::bestseller_guest($currentTheme, $storeId, $per_page, $destination);
        $bestSeller = [];
        if($bestSeller_fun['status'] == "success") {
            $bestSeller = $bestSeller_fun['bestseller_array'];
        }

        $products = Product::whereIn('id', [$id])->get();
        $product_review = Testimonial::where('product_id',$id)->get();
        // dd($product_review);
        if($products->isEmpty())
        {
            return redirect()->route('page.product-list',$slug)->with('error', __('Product not found.'));
        }

        $wishlist = Wishlist::where('product_id',$id)->get();
        $latest_product = Product::where('theme_id', $currentTheme)->where('store_id',$storeId)->latest()->first();

        $MainCategory = MainCategory::where('theme_id', $currentTheme)->where('store_id',$storeId)->get()->pluck('name','id');
        $MainCategory->prepend('All Products','0');
        $homeproducts = Product::where('theme_id', $currentTheme)->where('store_id',$storeId)->get();
        $M_products = Product::whereIn('id', [$id])->first();
        $product_stocks = ProductVariant::where('product_id',$id)->where('theme_id', $currentTheme)->limit(3)->get();
        $main_pro = Product::where('maincategory_id',$M_products->category_id)->where('theme_id',$currentTheme)->where('store_id',$storeId)->inRandomOrder()->limit(3)->get();

        $random_review = Testimonial::where('status',1)->where('theme_id', $currentTheme)->where('store_id',$storeId)->inRandomOrder()->get();
        $reviews = Testimonial::where('status',1)->where('theme_id', $currentTheme)->where('store_id',$storeId)->get();

        $lat_product = Product::orderBy('created_at', 'Desc')->where('theme_id', $currentTheme)->where('store_id',$storeId)->inRandomOrder()->limit(2)->get();

        $question = ProductQuestion::where('theme_id',$currentTheme)->where('product_id', $id)->where('store_id',$storeId)->get();

        $flashsales = FlashSale::where('theme_id', $currentTheme)->where('store_id',$storeId)->orderBy('created_at', 'Desc')->get();

        $setting = getAdminAllSetting();
        $defaultTimeZone = isset($setting['defult_timezone']) ? $setting['defult_timezone'] : 'Asia/Kolkata';
        date_default_timezone_set($defaultTimeZone);
        $currentDateTime = date('Y-m-d H:i:s A');

        return view('front_end.sections.pages.product', compact('currentTheme', 'section','slug', 'product', 'products','MainCategoryList','SubCategoryList','currency','currency_icon','bestSeller','product_review','wishlist','has_subcategory','latest_product','search_products','featured_products','MainCategory','homeproducts','M_products','product_stocks','main_pro','lat_product','random_review','reviews','question','mi_price','ma_price','flashsales','currentDateTime', 'topNavItems') + $data+$sqlData);

    }

    public function cart_page(Request $request ,$slug)
    {
        // dd($slug,$request->all());
        $store = Store::where('slug',$slug)->first();
        if (!$store) {
            abort(404);
        }
        $currentTheme = GetCurrenctTheme($slug);
        if($store)
        {
            $theme_id = $store->theme_id;

            $homepage_products = Product::orderBy('created_at', 'Desc')->where('theme_id', $theme_id)->get();


            $per_page = '12';
            $destination = 'web';
            $bestSeller_fun = Product::bestseller_guest($theme_id, $store->id, $store->id, $per_page, $destination);
            $bestSeller = [];
            if($bestSeller_fun['status'] == "success") {
                $bestSeller = $bestSeller_fun['bestseller_array'];
            }
            $data = getThemeSections($currentTheme,$slug, true, true);
            $section = (object) $data['section'];
            // Get Data from database
            $sqlData = getHomePageDatabaseSectionDataFromDatabase($data);
            $currantLang = \Cookie::get('LANGUAGE') ?? $store->default_language;
            $currency = Utility::GetValueByName('CURRENCY_NAME');
            $languages = Utility::languages();
            $MainCategory = MainCategory::where('theme_id', $theme_id)->where('store_id',getCurrentStore())->get()->pluck('name','id');
            $MainCategory->prepend('All Products','0');
            $homeproducts = Product::where('theme_id', $theme_id)->where('store_id',getCurrentStore())->get();

            return view('front_end.sections.pages.cart', compact('store','section','currentTheme','currency','currantLang','MainCategory','homeproducts','languages','bestSeller')+$data+$sqlData);
        }
        else
        {
            return redirect()->back()->with('error',__('Permission Denied.'));
        }

    }

    public function checkout(Request $request, $slug)
    {
        $store = Store::where('slug',$slug)->first();
        if (!$store) {
            abort(404);
        }
        $theme_id = $store->theme_id;
        $currentTheme = GetCurrenctTheme($slug);
        $data = getThemeSections($currentTheme,$slug, true, true);
        $section = (object) $data['section'];
        // Get Data from database
        $sqlData = getHomePageDatabaseSectionDataFromDatabase($data);
        $currantLang = \Cookie::get('LANGUAGE') ?? $store->default_language;
        $currency = Utility::GetValueByName('CURRENCY_NAME');
        $languages = Utility::languages();

        $param = [
            'theme_id' => $theme_id,
            'customer_id' => !empty(\Auth::guard('customers')->user()) ? \Auth::guard('customers')->user()->id : 0
        ];
        $request->merge($param);
        $api = new ApiController();

        $address_list_data = $api->address_list($request);
        $address_list = $address_list_data->getData();


        $country_option = Country::pluck('name', 'id')->prepend('Select country', ' ');
        $settings = Setting::where('theme_id',$theme_id)->where('store_id', $store->id)->pluck('value', 'name')->toArray();

        return view('front_end.sections.pages.checkout',compact('store','address_list','country_option','settings','currentTheme','currency','currantLang','languages','section') + $data+$sqlData);
    }

    public function order_track(Request $request,$slug)
    {
        $store = Store::where('slug',$slug)->first();
        if (!$store) {
            abort(404);
        }
        $currentTheme = $store->theme_id;
        $user = User::where('email',$request->email)->first();
        $currency = Utility::GetValueByName('CURRENCY_NAME');
        $currantLang = \Cookie::get('LANGUAGE') ?? $store->default_language;
        $languages = Utility::languages();

        $pixels = PixelFields::where('store_id', $store->id)
            ->where('theme_id', $store->theme_id)
            ->get();
        $pixelScript = [];
        foreach ($pixels as $pixel) {
            $pixelScript[] = pixelSourceCode($pixel['platform'], $pixel['pixel_id']);
        }

        if(!empty($request->order_number) ||  !empty($request->email)){

            $product_order_id = Order::where('store_id',$store->id)->get();
            $order_id =[];
            foreach($product_order_id as $order){
                $order_id[] = $order['product_order_id'];

            }
            $order_email = OrderBillingDetail::whereIn('product_order_id' ,$order_id)->pluck('email','email')->toArray();
            $order_number = Order::where('store_id',$store->id)->pluck('product_order_id','product_order_id')->toArray();

            if(in_array($request->email,$order_email) &&  in_array($request->order_number,$order_number)){
                $order_d = OrderBillingDetail::where('email',$request->email)->where('product_order_id' ,$request->order_number)->first();
                $order = Order::where('id' ,$order_d->order_id)->where('store_id',$store->id)->first();
                $order_status = Order::where('product_order_id' ,$request->order_number)->where('store_id',$store->id)->where('theme_id',$store->theme_id)->first();
            }
            elseif ( in_array($request->email,$order_email)){
                $order_d = OrderBillingDetail::where('email',$request->email)->first();
                $order = Order::where('id' ,$order_d->order_id)->where('store_id',$store->id)->first();
                $order_status = Order::where('id' ,$order_d->order_id)->where('store_id',$store->id)->where('theme_id',$store->theme_id)->first();

            }
            elseif(in_array($request->order_number,$order_number)){
                $order = Order::where('product_order_id' ,$request->order_number)->where('store_id',$store->id)->first();
                $order_status = Order::where('product_order_id' ,$request->order_number)->where('store_id',$store->id)->where('theme_id',$store->theme_id)->first();

            }else{
                return view('front_end.sections.pages.order_track',compact('currentTheme','slug','currency','currantLang','languages','store','pixelScript'));

            }

            $order_detail = Order::order_detail($order->id);
            if(!empty($order))
            {
                $customer = User::where('email' ,$order->email)->first();
            } else {
                return redirect()->back()->with('error',__('Order not found.'));
            }

            return view('front_end.sections.pages.order_track',compact('order','order_status','order_detail','customer','slug','currentTheme','currency','currantLang','languages','store','pixelScript'));
        }else{
            return view('front_end.sections.pages.order_track',compact('currentTheme','slug','currency','currantLang','languages','store','pixelScript'));

        }

    }

    public function contactUs(Request $request,$slug)
    {
        $store = Store::where('slug',$slug)->first();
        if (!$store) {
            abort(404);
        }
        $currentTheme = $store->theme_id;
        Theme::set($currentTheme);
        $currency = Utility::GetValueByName('CURRENCY_NAME');
        $currantLang = \Cookie::get('LANGUAGE') ?? $store->default_language;
        $languages = Utility::languages();
        $data = getThemeSections($currentTheme, $slug, true, true);
        // Get Data from database
        $sqlData = getHomePageDatabaseSectionDataFromDatabase($data);
        $section = (object) $data['section'];
        return view('front_end.sections.pages.contact-us',compact('slug','currentTheme','currency','currantLang','languages','section','store')+$sqlData+ $data);
    }

    public function search_products(Request $request ,$slug)
    {
        $store = Store::where('slug',$slug)->first();
        $theme_id = $store->theme_id;

        $search_pro = $request->product;

        $products = Product::where('name', 'LIKE', '%' . $search_pro . '%')->where('store_id', $store->id)->get();
        // Check if any matching products were found
        if (!$products->isEmpty()) {
            // Create an array of product URLs
            $productData = [];

            // Populate the array with product names and URLs
            foreach ($products as $product) {
                $url = url($slug.'/product/'.$product->slug);

                $productData[] = [
                    'name' => $product->name,
                    'url' => $url,
                ];
            }

            return response()->json($productData);
        } else {
            // Handle the case where no matching products were found
            return response()->json([]);
        }
    }

    public function privacy_page(Request $request, $slug)
    {
        $store = Store::where('slug', $slug)->first();
        if (empty($store)) {
            return redirect()->back();
        } else {
            $currentTheme = $theme_id = $store->theme_id;
        }

        $currentTheme = $store->theme_id;
        Theme::set($currentTheme);
        $currantLang = \Cookie::get('LANGUAGE') ?? $store->default_language;

        $data = getThemeSections($currentTheme, $store->slug, true, true);

        $section = (object) $data['section'];
        // Get Data from database
        $sqlData = getHomePageDatabaseSectionDataFromDatabase($data);
        $topNavItems = [];
        $menu_id = (array) $section->header->section->menu_type->menu_ids ??
        [];
        $topNavItems = get_nav_menu($menu_id);

        $ApiController = new ApiController();
        $featured_products_data = $ApiController->featured_products($request, $store->slug);
        $featured_products = $featured_products_data->getData();

        $pages_data = Page::where('theme_id', $currentTheme)->where('store_id', $store->id)->where('page_name', 'Privacy Policy')->get();
        return view('front_end.sections.pages.privacy_policys', compact('slug',  'pages_data', 'featured_products', 'topNavItems') + $data + $sqlData);
    }

    public function SoftwareDetails($slug)
    {
        $modules_all = Module::getByStatus(1);
        $modules = [];
        if(count($modules_all) > 0)
        {
            $modules = array_intersect_key(
                $modules_all,  // the array with all keys
                array_flip(array_rand($modules_all,(count($modules_all) <  6) ? count($modules_all) : 6 )) // keys to be extracted
            );
        }
        $plan = Plan::first();

        $addon = AddOnManager::where('name',$slug)->first();

        if(!empty($addon) && !empty($addon->module))
        {
            $module = Module::find($addon->module);

            if(!empty($module))
            {
                try {
                    if(module_is_active('LandingPage'))
                    {
                        return view('landingpage::marketplace.index',compact('modules','module','plan'));
                    } else {
                        return view($module->getLowerName().'::marketplace.index',compact('modules','module','plan'));
                    }
                } catch (\Throwable $th) {

                }
            }
        }
        if (module_is_active('LandingPage')) {
            $layout = 'landingpage::layouts.marketplace';

        } else {
            $layout = 'marketplace.marketplace';
        }
        return view('marketplace.detail_not_found',compact('modules','layout'));

    }

    public function Software(Request $request)
    {
        // Get the query parameter from the request
        $query = $request->query('query');
        // Get all modules (assuming Module::getByStatus(1) returns all modules)
        $modules = Module::getByStatus(1);

        // Filter modules based on the query parameter
        if ($query) {
            $modules = array_filter($modules, function ($module) use ($query) {
                // You may need to adjust this condition based on your requirements
                return stripos($module->getName(), $query) !== false;
            });
        }
        // Rest of your code
        if (module_is_active('LandingPage')) {
            $layout = 'landingpage::layouts.marketplace';
        } else {
            $layout = 'marketplace.marketplace';
        }

        return view('marketplace.software', compact('modules', 'layout'));
    }

    public function Pricing()
    {
        $admin_settings = getAdminAllSetting();
        if(module_is_active('GoogleCaptcha') && (isset($admin_settings['google_recaptcha_is_on']) ? $admin_settings['google_recaptcha_is_on'] : 'off') == 'on' )
        {
            config(['captcha.secret' => isset($admin_settings['google_recaptcha_secret']) ? $admin_settings['google_recaptcha_secret'] : '']);
            config(['captcha.sitekey' => isset($admin_settings['google_recaptcha_key']) ? $admin_settings['google_recaptcha_key'] : '']);
        }
        if(auth()->check())
        {
            if(auth()->user()->type == 'company')
            {
                return redirect('plans');
            }
            else
            {
                return redirect('dashboard');
            }
        }
        else
        {
            $plan = Plan::first();
            $modules = Module::getByStatus(1);

            if (module_is_active('LandingPage')) {
                $layout = 'landingpage::layouts.marketplace';
                return view('landingpage::layouts.pricing',compact('modules','plan','layout'));

            } else {
                $layout = 'marketplace.marketplace';
            }

            return view('marketplace.pricing',compact('modules','plan','layout'));
        }
    }

}
