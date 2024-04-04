<?php
use Illuminate\Support\Facades\Route;
use Modules\LandingPage\Http\Controllers\LandingPageController;
use Modules\LandingPage\Http\Controllers\CustomPageController;
use Modules\LandingPage\Http\Controllers\HomeController;
use Modules\LandingPage\Http\Controllers\FeaturesController;
use Modules\LandingPage\Http\Controllers\ScreenshotsController;
use Modules\LandingPage\Http\Controllers\PricingPlanController;
use Modules\LandingPage\Http\Controllers\JoinUsController;
use Modules\LandingPage\Http\Controllers\FooterController;
use Modules\LandingPage\Http\Controllers\ReviewController;
use Modules\LandingPage\Http\Controllers\DedicatedSectionController;
use Modules\LandingPage\Http\Controllers\BuiltTechSectionController;
use Modules\LandingPage\Http\Controllers\PackageDetailsController;
use Modules\LandingPage\Http\Controllers\MarketPlaceController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('landing-pages/{slug}', 'CustomPageController@customPage')->name('custom.pages');
    Route::resource('landingpage', LandingPageController::class);


    Route::resource('custom_page', CustomPageController::class);
    Route::post('custom_store/', 'CustomPageController@customStore')->name('custom_store');

    Route::resource('homesection', HomeController::class);


    Route::resource('features', FeaturesController::class);

    Route::get('feature/create/', 'FeaturesController@feature_create')->name('feature_create');
    Route::post('feature/store/', 'FeaturesController@feature_store')->name('feature_store');
    Route::get('feature/edit/{key}', 'FeaturesController@feature_edit')->name('feature_edit');
    Route::post('feature/update/{key}', 'FeaturesController@feature_update')->name('feature_update');
    Route::get('feature/delete/{key}', 'FeaturesController@feature_delete')->name('feature_delete');

    Route::post('feature_highlight_create/', 'FeaturesController@feature_highlight_create')->name('feature_highlight_create');

    Route::get('features/create/', 'FeaturesController@features_create')->name('features_create');
    Route::post('features/store/', 'FeaturesController@features_store')->name('features_store');
    Route::get('features/edit/{key}', 'FeaturesController@features_edit')->name('features_edit');
    Route::post('features/update/{key}', 'FeaturesController@features_update')->name('features_update');
    Route::get('features/delete/{key}', 'FeaturesController@features_delete')->name('features_delete');



    Route::resource('discover', DiscoverController::class);
    Route::get('discover/create/', 'DiscoverController@discover_create')->name('discover_create');
    Route::post('discover/store/', 'DiscoverController@discover_store')->name('discover_store');
    Route::get('discover/edit/{key}', 'DiscoverController@discover_edit')->name('discover_edit');
    Route::post('discover/update/{key}', 'DiscoverController@discover_update')->name('discover_update');
    Route::get('discover/delete/{key}', 'DiscoverController@discover_delete')->name('discover_delete');



    Route::resource('screenshots', ScreenshotsController::class);
    Route::get('screenshots/create/', 'ScreenshotsController@screenshots_create')->name('screenshots_create');
    Route::post('screenshots/store/', 'ScreenshotsController@screenshots_store')->name('screenshots_store');
    Route::get('screenshots/edit/{key}', 'ScreenshotsController@screenshots_edit')->name('screenshots_edit');
    Route::post('screenshots/update/{key}', 'ScreenshotsController@screenshots_update')->name('screenshots_update');
    Route::get('screenshots/delete/{key}', 'ScreenshotsController@screenshots_delete')->name('screenshots_delete');


    Route::resource('pricing_plan', PricingPlanController::class);



    Route::resource('faq', FaqController::class);
    Route::get('faq/create/', 'FaqController@faq_create')->name('faq_create');
    Route::post('faq/store/', 'FaqController@faq_store')->name('faq_store');
    Route::get('faq/edit/{key}', 'FaqController@faq_edit')->name('faq_edit');
    Route::post('faq/update/{key}', 'FaqController@faq_update')->name('faq_update');
    Route::get('faq/delete/{key}', 'FaqController@faq_delete')->name('faq_delete');


    Route::resource('testimonials', TestimonialsController::class);
    Route::get('testimonials/create/', 'TestimonialsController@testimonials_create')->name('testimonials_create');
    Route::post('testimonials/store/', 'TestimonialsController@testimonials_store')->name('testimonials_store');
    Route::get('testimonials/edit/{key}', 'TestimonialsController@testimonials_edit')->name('testimonials_edit');
    Route::post('testimonials/update/{key}', 'TestimonialsController@testimonials_update')->name('testimonials_update');
    Route::get('testimonials/delete/{key}', 'TestimonialsController@testimonials_delete')->name('testimonials_delete');


    Route::resource('join_us', JoinUsController::class);


Route::post('join_us/store/', 'JoinUsController@joinUsUserStore')->name('join_us_store');

Route::resource('footer', FooterController::class);

Route::post('footer_store', [FooterController::class, 'store'])
    ->name('footer_store')
    ->middleware(['auth']);

Route::get('footer/create', [FooterController::class, 'footer_section_create'])
    ->name('footer_section_create')
    ->middleware(['auth']);

Route::post('footer/store', [FooterController::class, 'footer_section_store'])
    ->name('footer_section_store')
    ->middleware(['auth']);

Route::get('footer/edit/{key}', [FooterController::class, 'footer_section_edit'])
    ->name('footer_section_edit')
    ->middleware(['auth']);

Route::post('footer/update/{key}', [FooterController::class, 'footer_section_update'])
    ->name('footer_section_update')
    ->middleware(['auth']);

Route::get('footers/delete/{key}', [FooterController::class, 'footer_section_delete'])
    ->name('footer_section_delete')
    ->middleware(['auth']);

Route::resource('review', ReviewController::class);

Route::get('review/create', [ReviewController::class, 'review_create'])
    ->name('review_create')
    ->middleware(['auth']);

Route::post('review/store', [ReviewController::class, 'review_store'])
    ->name('review_store')
    ->middleware(['auth']);

Route::get('review/edit/{key}', [ReviewController::class, 'review_edit'])
    ->name('review_edit')
    ->middleware(['auth']);

Route::post('review/update/{key}', [ReviewController::class, 'review_update'])
    ->name('review_update')
    ->middleware(['auth']);

Route::get('review/delete/{key}', [ReviewController::class, 'review_delete'])
    ->name('review_delete')
    ->middleware(['auth']);

Route::resource('dedicated', DedicatedSectionController::class)->middleware(['auth']);

Route::post('dedicated/store', [DedicatedSectionController::class, 'dedicated_store'])
    ->name('dedicated_store')
    ->middleware(['auth']);

Route::get('dedicated/create', [DedicatedSectionController::class, 'dedicated_card_create'])
    ->name('dedicated_card_create')
    ->middleware(['auth']);

Route::post('dedicateds/store', [DedicatedSectionController::class, 'dedicated_card_store'])
    ->name('dedicated_card_store')
    ->middleware(['auth']);

Route::get('dedicated/edit/{key}', [DedicatedSectionController::class, 'dedicated_card_edit'])
    ->name('dedicated_card_edit')
    ->middleware(['auth']);

Route::post('dedicated/update/{key}', [DedicatedSectionController::class, 'dedicated_card_update'])
    ->name('dedicated_card_update')
    ->middleware(['auth']);

Route::get('dedicated/delete/{key}', [DedicatedSectionController::class, 'dedicated_card_delete'])
    ->name('dedicated_card_delete')
    ->middleware(['auth']);

Route::resource('buildtech', BuiltTechSectionController::class)->middleware(['auth']);

Route::post('buildtech/store', [BuiltTechSectionController::class, 'buildtech_store'])
    ->name('buildtech_store')
    ->middleware(['auth']);

Route::get('buildtech/create', [BuiltTechSectionController::class, 'buildtech_card_create'])
    ->name('buildtech_card_create')
    ->middleware(['auth']);

Route::post('buildtechs/store', [BuiltTechSectionController::class, 'buildtech_card_store'])
    ->name('buildtech_card_store')
    ->middleware(['auth']);

Route::get('buildtech/edit/{key}', [BuiltTechSectionController::class, 'buildtech_card_edit'])
    ->name('buildtech_card_edit')
    ->middleware(['auth']);

Route::post('buildtech/update/{key}', [BuiltTechSectionController::class, 'buildtech_card_update'])
    ->name('buildtech_card_update')
    ->middleware(['auth']);

Route::get('buildtech/delete/{key}', [BuiltTechSectionController::class, 'buildtech_card_delete'])
    ->name('buildtech_card_delete')
    ->middleware(['auth']);

Route::resource('packagedetails', PackageDetailsController::class)->middleware(['auth']);

Route::post('packagedetails/store', [PackageDetailsController::class, 'packagedetails_store'])
    ->name('packagedetails_store')
    ->middleware(['auth']);


// *******************// Marketplace Controller starts// ************************//


// Route::resource('marketplace', MarketPlaceController::class);

Route::any('marketplace/{slug?}', [MarketPlaceController::class, 'marketplaceindex'])
    ->name('marketplace.index')
    ->middleware(['auth']);
// *******************// Product Main Section Starts// ************************//
Route::any('marketplace/{slug}/product', [MarketPlaceController::class, 'productindex'])
    ->name('marketplace_product')
    ->middleware(['auth']);

Route::post('marketplace/{slug}/product/store', [MarketPlaceController::class, 'product_main_store'])
    ->name('product_main_store')
    ->middleware(['auth']);

 // *******************// Product Main Section Ends// ************************//



// *******************// Dedicated Section Starts// ************************//

Route::any('marketplace/{slug}/dedicated', [MarketPlaceController::class, 'dedicatedindex'])
    ->name('marketplace_dedicated')
    ->middleware(['auth']);

Route::post('marketplaces/{slug}/dedicated/store', [MarketPlaceController::class, 'dedicated_theme_header_store'])
    ->name('dedicated_theme_header_store')
    ->middleware(['auth']);

Route::get('marketplace/{slug}/dedicated/create', [MarketPlaceController::class, 'dedicated_theme_create'])
    ->name('dedicated_theme_section_create')
    ->middleware(['auth']);

Route::post('marketplace/{slug}/dedicated/store', [MarketPlaceController::class, 'dedicated_theme_store'])
    ->name('dedicated_theme_section_store')
    ->middleware(['auth']);

Route::get('marketplace/{slug}/dedicated/edit/{key}', [MarketPlaceController::class, 'dedicated_theme_edit'])
    ->name('dedicated_theme_section_edit')
    ->middleware(['auth']);

Route::post('marketplace/{slug}/dedicated/update/{key}', [MarketPlaceController::class, 'dedicated_theme_update'])
    ->name('dedicated_theme_section_update')
    ->middleware(['auth']);

Route::get('marketplace/{slug}/dedicated/delete/{key}', [MarketPlaceController::class, 'dedicated_theme_delete'])
    ->name('dedicated_theme_section_delete')
    ->middleware(['auth']);

// *******************// Dedicated Section ends// ************************//



// *******************// Whychoose Section Starts// ************************//

Route::any('marketplace/{slug}/whychoose', [MarketPlaceController::class, 'whychooseindex'])
    ->name('marketplace_whychoose')
    ->middleware(['auth']);

Route::post('marketplace/{slug}/whychoose/store', [MarketPlaceController::class, 'whychoose_store'])
    ->name('whychoose_store')
    ->middleware(['auth']);

Route::get('marketplace/{slug}/create', [MarketPlaceController::class, 'pricing_plan_create'])
    ->name('pricing_plan_create')
    ->middleware(['auth']);

Route::post('marketplace/{slug}/store', [MarketPlaceController::class, 'pricing_plan_store'])
    ->name('pricing_plan_store')
    ->middleware(['auth']);

Route::get('marketplace/{slug}/edit/{key}', [MarketPlaceController::class, 'pricing_plan_edit'])
    ->name('pricing_plan_edit')
    ->middleware(['auth']);

Route::post('marketplace/{slug}/update/{key}', [MarketPlaceController::class, 'pricing_plan_update'])
    ->name('pricing_plan_update')
    ->middleware(['auth']);

Route::get('marketplace/{slug}/delete/{key}', [MarketPlaceController::class, 'pricing_plan_delete'])
    ->name('pricing_plan_delete')
    ->middleware(['auth']);

// *******************// Whychoose Section Ends// ************************//

// *******************// Screenshot Section Starts// ************************//

Route::any('marketplace/{slug}/screenshot', [MarketPlaceController::class, 'screenshotindex'])
    ->name('marketplace_screenshot')
    ->middleware(['auth']);

Route::get('marketplace/{slug}/screenshot/create', [MarketPlaceController::class, 'screenshots_create'])
    ->name('marketplace_screenshots_create')
    ->middleware(['auth']);

Route::post('marketplace/{slug}/screenshot/store', [MarketPlaceController::class, 'screenshots_store'])
    ->name('marketplace_screenshots_store')
    ->middleware(['auth']);

Route::get('marketplace/{slug}/screenshot/edit/{key}', [MarketPlaceController::class, 'screenshots_edit'])
    ->name('marketplace_screenshots_edit')
    ->middleware(['auth']);

Route::post('marketplace/{slug}/screenshot/update/{key}', [MarketPlaceController::class, 'screenshots_update'])
    ->name('marketplace_screenshots_update')
    ->middleware(['auth']);

Route::get('marketplace/{slug}/screenshot/delete/{key}', [MarketPlaceController::class, 'screenshots_delete'])
    ->name('marketplace_screenshots_delete')
    ->middleware(['auth']);

// *******************// Screenshot Section Ends// ************************//



// *******************// Add-on Section Starts// ************************//

Route::any('marketplace/{slug}/addon', [MarketPlaceController::class, 'addonindex'])
    ->name('marketplace_addon')
    ->middleware(['auth']);

Route::post('marketplaces/{slug}/addon/store', [MarketPlaceController::class, 'addon_store'])
    ->name('addon_store')
    ->middleware(['auth']);

// *******************// Add-on Section Ends// ************************//

Route::any('image-view/{slug}/{section?}', [LandingPageController::class ,'getInfoImages'])
    ->name('info.image.view')
    ->middleware(['auth']);