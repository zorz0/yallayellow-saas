<?php

namespace App\Http\Controllers;
use App\Models\Plan;
use App\Models\Themes\{ ThemeSection, ThemeArticelBlogSection, ThemeArticelBlogSectionDraft, ThemeTopProductSection, ThemeTopProductSectionDraft,  ThemeBestProductSection, ThemeBestProductSectionDraft, ThemeModernProductSection, ThemeModernProductSectionDraft, ThemeBestProductSecondSection, ThemeBestProductSecondSectionDraft, ThemeBestSellingSection, ThemeBestSellingSectionDraft, ThemeLogoSliderSection, ThemeLogoSliderSectionDraft, ThemeBestsellerSliderSection, ThemeBestsellerSliderSectionDraft, ThemeHeaderSection, ThemeSliderSection, ThemeCategorySection, ThemeReviewSection, ThemeSectionDraft, ThemeHeaderSectionDraft, ThemeSliderSectionDraft, ThemeCategorySectionDraft, ThemeReviewSectionDraft, ThemeSectionMap, ThemeBlogSection, ThemeBlogSectionDraft, ThemeDiscountSection, ThemeDiscountSectionDraft, ThemeProductCategorySection, ThemeProductCategorySectionDraft, ThemeFooterSection, ThemeFooterSectionDraft, ThemeProductSection, ThemeProductSectionDraft, ThemeSubscribeSection, ThemeSubscribeSectionDraft, ThemeVariantBackgroundSection, ThemeVariantBackgroundSectionDraft, ThemeProductBannerSliderSection, ThemeProductBannerSliderSectionDraft,ThemeNewestCateorySectionDraft,ThemeNewestCateorySection, ThemeServiceSection, ThemeServiceSectionDraft,ThemeVideoSection, ThemeVideoSectionDraft, ThemeNewestProductSection, ThemeNewestProductSectionDraft};
use App\Models\{ProductAttributeOption, Testimonial};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Models\Utility;
use App\Models\AppSetting;
use App\Models\Store;
use App\Models\Product;
use App\Models\Review;
use App\Models\Page;
use App\Models\Menu;
use App\Models\MainCategory;

use App\Models\SubCategory;
use Storage;
use Illuminate\Support\Facades\View;
use Qirolab\Theme\Theme;
use App\Models\Addon;
use App\Models\Setting;


class ThemeSettingController extends Controller
{
    public function formStore(Request $request)
    {
        $user = auth()->user();
        $user->default_language = $request->default_language;
        $user->language = $request->default_language;
        $user->theme_id = strtolower($request->theme);
        $user->mobile = $request->personal_number;
        $user->country = $request->filter_country;
    
        if (!$user->save()) {
        }
    
        $store = Store::where('id', $user->current_store)->first();
        $store->name = $request->store_name;
        $store->default_language = $request->default_language;
        $store->theme_id = strtolower($request->theme);
    
        if (!$store->save()) {
        }
    
        $setting = new Setting();
    
        if ($request->whatsapp_contact_number) {
            $setting->name = 'whatsapp_contact_number';
            $setting->value = $request->whatsapp_contact_number;
            $setting->theme_id = strtolower($request->theme);
            $setting->store_id = $user->current_store;
            $setting->created_by = auth()->user()->id;
    
            if (!$setting->save()) {
            }
        }
    
        if ($request->hasFile('theme_logo')) {
            $dir = 'themes/' . APP_THEME() . '/uploads';
            $theme_image = $request->file('theme_logo');
            $fileName = rand(10, 100) . '_' . time() . "_" . $theme_image->getClientOriginalName();
            $path = Utility::upload_file($request, 'theme_logo', $fileName, $dir, []);
    
            if ($path['flag'] == '0') {
                return redirect()->back()->with('error', $path['msg']);
            } else {
                $where = ['name' => 'theme_logo', 'theme_id' => strtolower($request->theme)];
                $setting = Setting::where($where)->first();
    
                if (!empty($setting)) {
                    if (File::exists(base_path($setting->value))) {
                        File::delete(base_path($setting->value));
                    }
                }
    
                $setting = new Setting();
                $setting->name = 'theme_logo';
                $setting->value = $path['url'] ?? null;
                $setting->theme_id = strtolower($request->theme);
                $setting->store_id = $user->current_store; 
                $setting->created_by = auth()->user()->id;
    
                if (!$setting->save()) {
                }
            }
        }
    
        $setting = new Setting();
        $setting->name = 'theme_name';
        $setting->value = $request->store_name;
        $setting->created_by = auth()->user()->id;
        $setting->theme_id = strtolower($request->theme);
        $setting->store_id = $user->current_store; 
    
        if (!$setting->save()) {
        }
    
        $headerSection = new ThemeHeaderSection();
        $headerSection->section_name = 'header';
    
        $jsonData = [
            'section_name' => 'Homepage - Header',
            'section_slug' => 'header',
            'unique_section_slug' => 'header',
            'section_enable' => 'on',
            'array_type' => 'inner-list',
            'loop_number' => '1',
            'section' => [
                'title' => [
                    'slug' => 'announcement_text',
                    'lable' => 'Announcement Title',
                    'type' => 'text',
                    'placeholder' => 'Please enter here...',
                    'text' => '<b>Monday - Friday:</b> 8:00 AM - 9:00 PM'
                ],
                'support_title' => [
                    'slug' => 'support_title',
                    'lable' => 'Support Title',
                    'type' => 'text',
                    'placeholder' => 'Please enter here...',
                    'text' => 'Support 24/7:'
                ],
                'support_value' => [
                    'slug' => 'support_value',
                    'lable' => 'Support Value',
                    'type' => 'text',
                    'placeholder' => 'Please enter here...',
                    'text' => $request->support_number // Insert the dynamic value here
                ],
                'menu_type' => [
                    'menu_ids' => [null]
                ]
            ]
        ];
    
        $headerSection->theme_json = json_encode($jsonData);
        $headerSection->store_id = $user->current_store; 
        $headerSection->theme_id = strtolower($request->theme);
    
        if (!$headerSection->save()) {
        }
        /* make fake data */
        
       // Create and save 3 fake main categories
       for ($i = 1; $i <= 3; $i++) {
        $MainCategory = new MainCategory();
        $MainCategory->name         = 'تجربة ' . $i;
        $MainCategory->slug         = 'collections/' . strtolower(preg_replace("/[^\w]+/", "-", 'تجربة ' . $i));
        $MainCategory->image_url    = 'https://yallayellow.com/themes/gifts/uploads/71_1713382775_download%20(1).png';
        $MainCategory->image_path   = 'https://yallayellow.com/themes/gifts/uploads/71_1713382775_download%20(1).png';
        $MainCategory->icon_path    = 'https://yallayellow.com/themes/gifts/uploads/71_1713382775_download%20(1).png';
        $MainCategory->trending     = 1;
        $MainCategory->status       = 1;
        $MainCategory->theme_id     = strtolower($request->theme);
        $MainCategory->store_id     = $user->current_store;
    
        $MainCategory->save();
    
        // Get the ID of the saved main category
        $mainCategoryId = $MainCategory->id;
    
        // Create and save the subcategory
        $subcategory                    = new SubCategory();
        $subcategory->name              = 'تجربة فرعية ' . $i;
        $subcategory->maincategory_id   = $mainCategoryId; // Use the ID of the main category
        $subcategory->image_url         = 'https://yallayellow.com/themes/gifts/uploads/71_1713382775_download%20(1).png';
        $subcategory->image_path        = 'https://yallayellow.com/themes/gifts/uploads/71_1713382775_download%20(1).png';
        $subcategory->icon_path        = 'https://yallayellow.com/themes/gifts/uploads/71_1713382775_download%20(1).png';
        $subcategory->status            = 1;
        $subcategory->theme_id          = strtolower($request->theme);
        $subcategory->store_id          = $user->current_store;
        $subcategory->save();
    }
    

    
        return redirect()->route('dashboard')->with('success', 'Setting saved successfully.');
    }
    

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        $plan = Plan::find($user->plan_id);
        $addons = Addon::where('status','1')->pluck('theme_id')->toArray();
        if(!empty($plan->themes))
        {
            $themes =  explode(',',$plan->themes);
        } else {
            $themes = ['grocery', 'babycare'];
        }
        $currentTheme = Theme::active();
        return view('theme_preview.index',compact('themes', 'currentTheme', 'addons'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        if (isset($request->theme_id)) {
            $currentTheme = $request->theme_id;
        } else {
            $currentTheme = Theme::active();
        }

        Theme::set($currentTheme);
        // Determine if the theme is published
        $mapping = ThemeSectionMap::where('theme_id', $currentTheme)
            ->where('store_id', getCurrentStore())
            ->first();

        $store = Store::where('id', getCurrentStore())->first();
        $storeSlug = $store->slug;
        themeDefaultSection($currentTheme, $store->id);
        $is_publish = $mapping && $mapping->is_publish == 1;
        $data = getThemeSections($currentTheme,$storeSlug, $is_publish, false);

        // Get Data from database
        $sqlData = getHomePageDatabaseSectionDataFromDatabase($data);

        return view('theme_preview.customize', $sqlData + $data);
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

    public function saveThemeLayout(Request $request)
    {

        $options = $request->all();
        $sectionDraftQuery = ThemeSectionDraft::query();
        foreach($options['array'] as $key => $item)
        {
            if (!isset($item['id'])) {
                continue;
            }
            if (isset($item['section']) && isset($item['store'])) {
                $exist = (clone $sectionDraftQuery)->where('store_id', $item['store'])->where('theme_id', $item['theme'])->where('section_name', $item['section'])->first();
                if ($exist) {
                    $exist->update(['order' => $item['order'],'is_hide' => $item['is_hide']]);
                } else {
                    $lastSection = (clone $sectionDraftQuery)->where('store_id', $item['store'])->where('theme_id', $item['theme'])->orderBy('order', 'DESC')->first();
                    if ($lastSection) {
                        (clone $sectionDraftQuery)->create([
                            'section_name' => $item['section'],
                            'store_id' => $item['store'],
                            'theme_id' => $item['theme'],
                            'order' => $lastSection->order + 1,
                            'is_hide' => $item['is_hide'],
                        ]);
                    }
                    else {
                        (clone $sectionDraftQuery)->create([
                            'section_name' => $item['section'],
                            'store_id' => $item['store'],
                            'theme_id' => $item['theme'],
                            'order' => 0,
                            'is_hide' => $item['is_hide'],
                        ]);
                    }
                }
            }
        }
        return response()->json(["msg"=>"Theme saved successfully","data"=> "success", "is_publish" => 0]);
    }


    public function publishTheme(Request $request)
    {

        $mapping = ThemeSectionMap::where('theme_id', $request['theme_id'])->where('store_id', $request['store_id'])->first();
        $store = Store::where('id', getCurrentStore())->first();
        $storeSlug = $store->slug;
        if ($mapping) {
            $mapping->is_publish = 1;
            $mapping->save();

            $this->updateOrCreateThemeSection(ThemeHeaderSectionDraft::class, ThemeHeaderSection::class, $request);

            $this->updateOrCreateThemeSection(ThemeSliderSectionDraft::class, ThemeSliderSection::class, $request);

            $this->updateOrCreateThemeSection(ThemeCategorySectionDraft::class, ThemeCategorySection::class, $request);

            $this->updateOrCreateThemeSection(ThemeReviewSectionDraft::class, ThemeReviewSection::class, $request);

            $this->updateOrCreateThemeSection(ThemeBestProductSectionDraft::class, ThemeBestProductSection::class, $request);

            $this->updateOrCreateThemeSection(ThemeBestProductSecondSectionDraft::class, ThemeBestProductSecondSection::class, $request);

            $this->updateOrCreateThemeSection(ThemeBlogSectionDraft::class, ThemeBlogSection::class, $request);

            $this->updateOrCreateThemeSection(ThemeSubscribeSectionDraft::class, ThemeSubscribeSection::class, $request);

            $this->updateOrCreateThemeSection(ThemeVariantBackgroundSectionDraft::class, ThemeVariantBackgroundSection::class, $request);

            $this->updateOrCreateThemeSection(ThemeProductCategorySectionDraft::class, ThemeProductCategorySection::class, $request);

            $this->updateOrCreateThemeSection(ThemeProductSectionDraft::class, ThemeProductSection::class, $request);

            $this->updateOrCreateThemeSection(ThemeBestsellerSliderSectionDraft::class, ThemeBestsellerSliderSection::class, $request);

            $this->updateOrCreateThemeSection(ThemeLogoSliderSectionDraft::class, ThemeLogoSliderSection::class, $request);

            $this->updateOrCreateThemeSection(ThemeFooterSectionDraft::class, ThemeFooterSection::class, $request);

            $this->updateOrCreateThemeSection(ThemeProductBannerSliderSectionDraft::class, ThemeProductBannerSliderSection::class, $request);

            $this->updateOrCreateThemeSection(ThemeNewestCateorySectionDraft::class, ThemeNewestCateorySection::class, $request);

            $this->updateOrCreateThemeSection(ThemeBestSellingSectionDraft::class, ThemeBestSellingSection::class, $request);

            $this->updateOrCreateThemeSection(ThemeModernProductSectionDraft::class, ThemeModernProductSection::class, $request);

            $this->updateOrCreateThemeSection(ThemeArticelBlogSectionDraft::class, ThemeArticelBlogSection::class, $request);

            $this->updateOrCreateThemeSection(ThemeTopProductSectionDraft::class, ThemeTopProductSection::class, $request);

            $this->updateOrCreateThemeSection(ThemeServiceSectionDraft::class, ThemeServiceSection::class, $request);

            $this->updateOrCreateThemeSection(ThemeVideoSectionDraft::class, ThemeVideoSection::class, $request);

            $this->updateOrCreateThemeSection(ThemeNewestProductSectionDraft::class, ThemeNewestProductSection::class, $request);

            // Create or Update Section Orders
            $this->updateOrCreateThemeSectionOrder($request);
        }

        $data = getThemeSections($request['theme_id'], $storeSlug, true, false);
        // Get Data from database
        $sqlData = getHomePageDatabaseSectionDataFromDatabase($data);
        Theme::set($request['theme_id']);
        // Render the HTML view
        $html = View::make('main_file', $data + $sqlData)->render();
        // Return JSON response
        return response()->json([
            "msg" => "Theme published successfully",
            "data" => ['content' => $html],
            "is_publish" => true,
            "token" => csrf_token()
        ]);
    }

    private function updateOrCreateThemeSection($draftSectionClass, $publishedSectionClass, $request)
    {
        $sectionDraft = $draftSectionClass::where('theme_id', $request['theme_id'])->where('store_id', $request['store_id'])->first();
        if ($sectionDraft) {
            $publishedSectionClass::updateOrCreate(
                ['theme_id' => $sectionDraft->theme_id, 'section_name' => $sectionDraft->section_name, 'store_id' => $sectionDraft->store_id],
                ['theme_id' => $sectionDraft->theme_id, 'section_name' => $sectionDraft->section_name, 'store_id' => $sectionDraft->store_id, 'theme_json' => is_array($sectionDraft->theme_json) ? json_encode($sectionDraft->theme_json) : (is_object($sectionDraft->theme_json) ? json_encode($sectionDraft->theme_json) : $sectionDraft->theme_json)]
            );
            // Remove theme Draft records
            $sectionDraft->delete();
        }
    }

    private function updateOrCreateThemeSectionOrder($request)
    {
        $sectionOrders = ThemeSectionDraft::where('theme_id', $request['theme_id'])->where('store_id', $request['store_id'])->get();
        $themeSectionQuery = ThemeSection::query();
        foreach ($sectionOrders as $sectionOrder) {
           $exist = (clone $themeSectionQuery)->where('theme_id', $request['theme_id'])->where('store_id', $request['store_id'])->where('section_name', $sectionOrder->section_name)->first();
           if ($exist) {
                if ($exist->order != $sectionOrder->order) {
                    $exist->update([ 'order' => $sectionOrder->order, 'is_hide' => $sectionOrder->is_hide]);
                } elseif ($exist->order == $sectionOrder->order) {
                    $exist->update([ 'is_hide' => $sectionOrder->is_hide]);
                }
           } else {
                (clone $themeSectionQuery)->create([
                    'section_name' => $sectionOrder->section_name,
                    'store_id' => $sectionOrder->store_id,
                    'theme_id' => $sectionOrder->theme_id,
                    'order' => $sectionOrder->order,
                    'is_hide' => $sectionOrder->is_hide,
                ]);
           }
        }
    }

    public function sidebarOption(Request $request)
    {
        $currentTheme = $request->theme_id;
        // Retrieve theme section map
        $mapping = ThemeSectionMap::where('theme_id', $request->theme_id)
            ->where('store_id', $request->store_id)
            ->first();

        // Check if mapping exists and is published
        $is_publish = $mapping && $mapping->is_publish == 1;

        // Initialize json data from the specified section or use default
        if (isset($request->section_name)) {
            $sectionName = $request->section_name;
        } else {
            $sectionName = 'header';
        }

        // Get Published or draft json file from database or root directory
        $data = getThemeMainOrDraftSectionJson($is_publish, $currentTheme, $sectionName, $request->store_id);
        // Get Data from database
        $sqlData = getHomePageDatabaseSectionDataFromDatabase($data);
        // Render the HTML view
        $html = View::make('theme_preview.section_form', $data+ $sqlData)->render();

        // Return JSON response
        return response()->json([
            "msg" => "Theme saved successfully",
            "data" => ['content' => $html],
            "is_publish" => $is_publish ?? false,
            "token" => csrf_token()
        ]);
    }

    public function pageSetting(Request $request) {

        $theme_id = $request->theme_id ?? APP_THEME();
        Theme::set($theme_id);
        $page_name = $request->section_name;
        $dir        = 'themes/'.$theme_id.'/uploads';
        if(empty($page_name)) {
            return response()->json(["error"=>'Page name not found.',"data"=> 'Page name not found.']);
        }

        $array = $request->array;

        // Upload slider background image
        if (isset($array['section']['background_image']['text']) && gettype($array['section']['background_image']['text']) == 'object') {
            $theme_name = $theme_id;
            $theme_image = $array['section']['background_image']['text'];
            $upload = $this->uploadThemeMedia($request, $theme_id, $theme_image, $dir);
            if (!$upload['error']) {
                $array['section']['background_image']['text'] = $upload['data']['image_path'];
                $array['section']['background_image']['image'] = $upload['data']['image_path'];
            } else {
                return response()->json(["error"=>"Something went wrong","data"=> ["Something went wrong"]]);
            }
        }

        if (isset($array['section']['background_image_second']['text']) && gettype($array['section']['background_image_second']['text']) == 'object') {
            $theme_name = $theme_id;
            $theme_image = $array['section']['background_image_second']['text'];
            $upload = $this->uploadThemeMedia($request, $theme_id, $theme_image, $dir);
            if (!$upload['error']) {
                $array['section']['background_image_second']['text'] = $upload['data']['image_path'];
                $array['section']['background_image_second']['image'] = $upload['data']['image_path'];
            } else {
                return response()->json(["error"=>"Something went wrong","data"=> ["Something went wrong"]]);
            }
        }

        // Upload section inside image
        if (isset($array['section']['image']['text']) && !is_array($array['section']['image']['text']) && gettype($array['section']['image']['text']) == 'object') {
            $theme_image = $array['section']['image']['text'];
            $upload = $this->uploadThemeMedia($request, $theme_id, $theme_image, $dir);
            if (!$upload['error']) {
                $array['section']['image']['text'] = $upload['data']['image_path'];
                $array['section']['image']['image'] = $upload['data']['image_path'];
            } else {
                return response()->json(["error"=>"Something went wrong","data"=> ["Something went wrong"]]);
            }
        }

        if (isset($array['section']['image_right']['text']) && !is_array($array['section']['image_right']['text']) && gettype($array['section']['image_right']['text']) == 'object') {
            $theme_image = $array['section']['image_right']['text'];
            $upload = $this->uploadThemeMedia($request, $theme_id, $theme_image, $dir);
            if (!$upload['error']) {
                $array['section']['image_right']['text'] = $upload['data']['image_path'];
                $array['section']['image_right']['image'] = $upload['data']['image_path'];
            } else {
                return response()->json(["error"=>"Something went wrong","data"=> ["Something went wrong"]]);
            }
        }

        if (isset($array['section']['image_left']['text']) && !is_array($array['section']['image_left']['text']) && gettype($array['section']['image_left']['text']) == 'object') {
            $theme_image = $array['section']['image_left']['text'];
            $upload = $this->uploadThemeMedia($request, $theme_id, $theme_image, $dir);
            if (!$upload['error']) {
                $array['section']['image_left']['text'] = $upload['data']['image_path'];
                $array['section']['image_left']['image'] = $upload['data']['image_path'];
            } else {
                return response()->json(["error"=>"Something went wrong","data"=> ["Something went wrong"]]);
            }
        }

        if (isset($array['section']['image']['text']) && is_array($array['section']['image']['text']) && count($array['section']['image']['text']) > 0) {
            $k = 0;
            foreach ($array['section']['image']['text'] as $keys => $theme_image) {
                if (gettype($theme_image) == 'object') {
                    $upload = $this->uploadThemeMedia($request, $theme_id, $theme_image, $dir);
                    if (!$upload['error']) {
                        $array['section']['image']['text'][$k] = $upload['data']['image_path'];
                        $array['section']['image']['image'][$keys] = $upload['data']['image_path'];
                        $k++;
                    } else {
                        return response()->json(["error"=>"Something went wrong","data"=> ["Something went wrong"]]);
                    }
                } else {
                    $array['section']['image']['text'][$keys] = $theme_image;
                        $array['section']['image']['image'][$keys] = $theme_image;
                }
            }

        } elseif (isset($array['section']['image']['text']) && is_array($array['section']['image']['text']) && count($array['section']['image']['text'] == 0)) {
            $array['section']['image']['image']  = json_decode($array['section']['image']['image']);
        }


        // Upload footer social image
        if (isset($array['section']['footer_link']['social_icon']) && count($array['section']['footer_link']['social_icon']) > 0) {
            $social_icons = $array['section']['footer_link']['social_icon'];
            $socialImages = [];
            foreach ($social_icons as $key => $value) {
                if (isset($value['text']) && gettype($value['text']) == 'object') {
                    $theme_image = $value['text'];
                    $upload = $this->uploadThemeMedia($request, $theme_id, $theme_image, $dir);
                    if (!$upload['error']) {
                        $socialImages[$key]['text'] = $upload['data']['image_path'];
                        $socialImages[$key]['image'] = $upload['data']['image_path'];
                    } else {
                        return response()->json(["error"=>"Something went wrong","data"=> ["Something went wrong"]]);
                    }
                } else {
                    $socialImages[$key]['text'] = $value['text'] ?? ($value['image'] ?? null);
                    $socialImages[$key]['image'] = $value['image'];
                }
            }
            $array['section']['footer_link']['social_icon'] = $socialImages;
        }

        // Define a mapping between page names and model classes
        $pageNameToModel = [
            'header' => ThemeHeaderSectionDraft::class,
            'slider' => ThemeSliderSectionDraft::class,
            'review' => ThemeReviewSectionDraft::class,
            'bestseller_slider' => ThemeBestsellerSliderSectionDraft::class,
            'best_product' => ThemeBestProductSectionDraft::class,
            'best_product_second' => ThemeBestProductSecondSectionDraft::class,
            'blog' => ThemeBlogSectionDraft::class,
            'category' => ThemeCategorySectionDraft::class,
            'subscribe' => ThemeSubscribeSectionDraft::class,
            'variant_background' => ThemeVariantBackgroundSectionDraft::class,
            'product_category' => ThemeProductCategorySectionDraft::class,
            'modern_product' => ThemeModernProductSectionDraft::class,
            'product' => ThemeProductSectionDraft::class,
            'footer' => ThemeFooterSectionDraft::class,
            'logo_slider' => ThemeLogoSliderSectionDraft::class,
            'newest_category' => ThemeNewestCateorySectionDraft::class,
            'best_selling_slider' => ThemeBestSellingSectionDraft::class,
            'product_banner_slider' => ThemeProductBannerSliderSectionDraft::class,
            'top_product' => ThemeTopProductSectionDraft::class,
            'articel_blog' =>  ThemeArticelBlogSectionDraft::class,
            'service_section' => ThemeServiceSectionDraft::class,
            'video' => ThemeVideoSectionDraft::class,
            'newest_product' => ThemeNewestProductSectionDraft::class
        ];

        // Check if the page name exists in the mapping
        if (array_key_exists($page_name, $pageNameToModel)) {
            $modelClass = $pageNameToModel[$page_name];
            // Use the dynamic model class
            $modelClass::updateOrCreate(
                ['theme_id' => $theme_id, 'section_name' => $page_name, 'store_id' => getCurrentStore()],
                ['theme_id' => $theme_id, 'section_name' => $page_name, 'store_id' => getCurrentStore(), 'theme_json' => json_encode($array)]
            );
        }

        // Update or create the ThemeSectionMap
        ThemeSectionMap::updateOrCreate(
            ['theme_id' => $theme_id, 'store_id' => getCurrentStore()],
            ['theme_id' => $theme_id, 'store_id' => getCurrentStore(), 'is_publish' => 0]
        );

        $store = Store::where('id', getCurrentStore())->first();
        $storeSlug = $store->slug;
        $data = getThemeSections($theme_id,$storeSlug, false, false);
        // Get Data from database
        $sqlData = getHomePageDatabaseSectionDataFromDatabase($data);
        // Render the HTML view
        $html = View::make('main_file', $data + $sqlData)->render();

        // Return JSON response
        return response()->json([
            "msg" => "Theme saved successfully",
            "data" => ['content' => $html],
            "is_publish" => false,
            "token" => csrf_token()
        ]);
    }

    private function uploadThemeMedia($request, $theme_id, $theme_image, $dir) {

            $image_size = File::size($theme_image);
            $result = Utility::updateStorageLimit(\Auth::user()->creatorId(), $image_size);
            if ($result == 1)
            {
                $fileName = rand(10,100).'_'.time() . "_" . $theme_image->getClientOriginalName();
                $upload = Utility::upload_file($request, $theme_image, $fileName, $dir, [], $theme_image);
                $img_path = '';
                return ["error"=>false,"data"=> $upload];
            }
            else{
                return ["error"=>true,"data"=> []];
            }
    }


    public function landingPageSetting(Request $request) {
        return view('landing_page');
    }

    public function makeActiveTheme(Request $request) {

        if (isset($request->theme_id)) {
            auth()->user()->update(['theme_id' => $request->theme_id]);
            themeDefaultSection($request->theme_id, auth()->user()->current_store);
            Store::where('id', auth()->user()->current_store)->update(['theme_id' => $request->theme_id]);
        }
        return redirect()->back()->with('success', 'Theme active set successfully');
    }

    public function page($slug,$page_slug)
    {
        if ($page_slug) {
            $page   = Page::where('page_slug', $page_slug)->where('page_status', 1)->first();
            if ($page) {

                $store = Store::find(getCurrentStore());
                $slug = $store->slug;
                $currentTheme = $store->theme_id;
                $currantLang = \Cookie::get('LANGUAGE') ?? ($store->default_language ?? 'en');
                $data = getThemeSections($currentTheme, $slug, true);
                // Get Data from database
                $sqlData = getHomePageDatabaseSectionDataFromDatabase($data);
                $section = (object) $data['section'];
                $topNavItems = [];
                $menu_id = [];
                if (isset($section->header) && isset($section->header->section) && isset($section->header->section->menu_type) && isset($section->header->section->menu_type->menu_ids)) {
                    $menu_id = (array) $section->header->section->menu_type->menu_ids;
                }
                $topNavItems = get_nav_menu($menu_id);
                $currency = Utility::GetValueByName('CURRENCY_NAME');
                $languages = Utility::languages();
                return view('front_end.sections.pages.page', compact('page','currentTheme','currantLang','store','section','topNavItems','slug','currency','languages')+$sqlData);
            } else {
                abort(404);
            }
        }
    }
}

