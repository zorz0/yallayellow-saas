<?php

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\CouponController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\Http\Middleware\CheckAbilities;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('{slug}/register', [AuthController::class, 'register'])->middleware(['APILog']);
Route::post('{slug}/login', [AuthController::class, 'login'])->middleware(['APILog']);
Route::post('{slug}/forgot-password-send-otp', [AuthController::class, 'forgot_password_send_otp'])->middleware(['APILog']);
Route::post('{slug}/forgot-password-verify-otp', [AuthController::class, 'forgot_password_verify_otp'])->middleware(['APILog']);
Route::post('{slug}/forgot-password-save', [AuthController::class, 'forgot_password_save'])->middleware(['APILog']);
Route::post('{slug}/', [ApiController::class, 'base_url'])->middleware(['APILog']);
Route::post('{slug}/logout', [AuthController::class, 'logout'])->middleware(['auth:sanctum', 'APILog']);
Route::post('{slug}/currency', [ApiController::class, 'currency'])->middleware(['APILog']);
Route::post('{slug}/landingpage', [ApiController::class, 'landingpage'])->middleware(['APILog']);
Route::post('{slug}/product_banner', [ApiController::class, 'product_banner'])->middleware(['APILog']);
Route::post('{slug}/category', [ApiController::class, 'category'])->middleware(['APILog']);
Route::post('{slug}/category-list', [ApiController::class, 'main_category'])->middleware(['APILog']);
Route::post('{slug}/search', [ApiController::class, 'search'])->middleware(['APILog']);
Route::post('{slug}/search-guest', [ApiController::class, 'search'])->middleware(['APILog']);
Route::post('{slug}/apply-coupon', [ApiController::class, 'apply_coupon'])->middleware(['APILog']);
Route::post('{slug}/categorys-product', [ApiController::class, 'categorys_product'])->middleware(['APILog']);
Route::post('{slug}/categorys-product-guest', [ApiController::class, 'categorys_product_guest'])->middleware(['APILog']);
Route::post('{slug}/product-detail', [ApiController::class, 'product_detail'])->middleware(['APILog']);
Route::post('{slug}/product-detail-guest', [ApiController::class, 'product_detail_guest'])->middleware(['APILog']);
Route::post('{slug}/product-rating', [ApiController::class, 'product_rating'])->middleware(['APILog']);
Route::post('{slug}/random_review', [ApiController::class, 'random_review'])->middleware(['APILog']);
Route::post('{slug}/addtocart', [ApiController::class, 'addtocart'])->middleware(['APILog']);
Route::post('{slug}/cart-qty', [ApiController::class, 'cart_qty'])->middleware(['APILog']);
Route::post('{slug}/cart-list', [ApiController::class, 'cart_list'])->middleware(['APILog']);
Route::post('{slug}/cart-check', [ApiController::class, 'cart_check'])->middleware(['APILog']);
Route::post('{slug}/cart-check-guest', [ApiController::class, 'cart_check_guest'])->middleware(['APILog']);
Route::post('{slug}/wishlist', [ApiController::class, 'wishlist'])->middleware(['APILog']);
Route::post('{slug}/wishlist-list', [ApiController::class, 'wishlist_list'])->middleware(['APILog']);
Route::post('{slug}/bestseller', [ApiController::class, 'bestseller'])->middleware(['APILog']);
Route::post('{slug}/bestseller-guest', [ApiController::class, 'bestseller_guest'])->middleware(['APILog']);
Route::post('{slug}/tranding-category', [ApiController::class, 'tranding_category'])->middleware(['APILog']);
Route::post('{slug}/tranding-category-product', [ApiController::class, 'tranding_category_product'])->middleware(['APILog']);
Route::post('{slug}/tranding-category-product-guest', [ApiController::class, 'tranding_category_product_guest'])->middleware(['APILog']);
Route::post('{slug}/home-category', [ApiController::class, 'home_category'])->middleware(['APILog']); // Main category
Route::post('{slug}/sub-category', [ApiController::class, 'sub_category'])->middleware(['APILog']);
Route::post('{slug}/sub-category-guest', [ApiController::class, 'sub_category_guest'])->middleware(['APILog']);
Route::post('{slug}/featured-products', [ApiController::class, 'featured_products'])->middleware(['APILog']);
Route::post('{slug}/featured-products-guest', [ApiController::class, 'featured_products'])->middleware(['APILog']);
Route::post('{slug}/check-variant-stock', [ApiController::class, 'check_variant_stock'])->middleware(['APILog']);
Route::post('{slug}/delivery-list', [ApiController::class, 'delivery_list'])->middleware(['APILog']);
Route::post('{slug}/shipping', [ApiController::class, 'delivery_list'])->middleware(['APILog']);
Route::post('{slug}/delivery-charge', [ApiController::class, 'delivery_charge'])->middleware(['APILog']);
Route::post('{slug}/payment-list', [ApiController::class, 'payment_list'])->middleware(['APILog']);
Route::post('{slug}/country-list', [ApiController::class, 'country_list'])->middleware(['APILog']);
Route::post('{slug}/state-list', [ApiController::class, 'state_list'])->middleware(['APILog']);
Route::post('{slug}/city-list', [ApiController::class, 'city_list'])->middleware(['APILog']);
Route::post('{slug}/profile-update', [ApiController::class, 'profile_update'])->middleware(['auth:sanctum', 'APILog']);
Route::post('{slug}/change-password', [ApiController::class, 'change_password'])->middleware(['auth:sanctum', 'APILog']);
Route::post('{slug}/change-address', [ApiController::class, 'change_address'])->middleware(['auth:sanctum', 'APILog']);
Route::post('{slug}/user-detail', [ApiController::class, 'user_detail'])->middleware(['auth:sanctum', 'APILog']);
Route::post('{slug}/add-address', [ApiController::class, 'add_address'])->middleware(['auth:sanctum', 'APILog']);
Route::post('{slug}/address-list', [ApiController::class, 'address_list'])->middleware(['auth:sanctum', 'APILog']);
Route::post('{slug}/delete-address', [ApiController::class, 'delete_address'])->middleware(['auth:sanctum', 'APILog']);
Route::post('{slug}/update-address', [ApiController::class, 'update_address'])->middleware(['auth:sanctum', 'APILog']);
Route::post('{slug}/update-user-image', [ApiController::class, 'update_user_image'])->middleware(['auth:sanctum', 'APILog']);
Route::post('{slug}/confirm-order', [ApiController::class, 'confirm_order'])->middleware([ 'APILog']);
Route::post('{slug}/place-order', [ApiController::class, 'place_order'])->name('place-order')->middleware([ 'APILog']);
Route::post('{slug}/place-order-guest', [ApiController::class, 'place_order_guest'])->middleware(['APILog']);
Route::post('{slug}/order-list', [ApiController::class, 'order_list'])->middleware([ 'APILog']);
Route::post('{slug}/return-order-list', [ApiController::class, 'return_order_list'])->middleware([ 'APILog']);
Route::post('{slug}/order-detail', [ApiController::class, 'order_detail'])->middleware([ 'APILog']);
Route::post('{slug}/order-status-change', [ApiController::class, 'order_status_change'])->middleware([ 'APILog']);
Route::post('{slug}/product-return', [ApiController::class, 'product_return'])->middleware([ 'APILog']);
Route::post('{slug}/navigation', [ApiController::class, 'navigation'])->middleware(['APILog']);
Route::post('{slug}/tax-guest', [ApiController::class, 'tax_guest'])->middleware(['APILog']);
Route::post('{slug}/extra-url', [ApiController::class, 'extra_url'])->middleware(['APILog']);
Route::post('{slug}/loyality-program-json', [ApiController::class, 'loyality_program_json'])->middleware(['APILog']);
Route::post('{slug}/loyality-reward', [ApiController::class, 'loyality_reward'])->middleware(['APILog']);
Route::post('{slug}/notify_user', [ApiController::class, 'notify_user'])->middleware(['APILog']);
Route::post('{slug}/recent-product', [ApiController::class, 'recent_product'])->middleware([ 'APILog']);
Route::post('{slug}/recent-product-guest', [ApiController::class, 'recent_product'])->middleware(['APILog']);
Route::post('{slug}/releted-product', [ApiController::class, 'releted_product'])->middleware(['APILog']);
Route::post('{slug}/releted-product-guest', [ApiController::class, 'releted_product'])->middleware(['APILog']);

Route::post('{slug}/random-product', [ApiController::class, 'random_product'])->middleware(['APILog']);

Route::post('{slug}/payment-sheet', [ApiController::class, 'payment_sheet'])->middleware(['APILog']);
Route::post('{slug}/user-delete', [ApiController::class, 'user_delete'])->middleware(['auth:sanctum', 'APILog']);
Route::post('{slug}/subscribe', [ApiController::class, 'subscribe'])->middleware(['APILog']);
Route::post('{slug}/discount-products', [ApiController::class, 'discountProducts'])->middleware(['APILog']);
Route::post('{slug}/add-review', [ApiController::class, 'add_review'])->middleware(['APILog']);

// Route::prefix('admin')->as('admin.')->group(function(){
    Route::post('adminlogin', [DashboardController::class, 'login'])->middleware(['AdminApiLog']);
    Route::get('base_url', [DashboardController::class, 'base_url'])->middleware(['AdminApiLog']);
    Route::get('currency', [DashboardController::class, 'currency'])->middleware(['AdminApiLog']);
    Route::post('dashboard', [DashboardController::class, 'dashboard'])->middleware(['AdminApiLog']);
    Route::get('categorylist', [DashboardController::class, 'CategoryList'])->middleware(['AdminApiLog']);
    Route::get('productlist', [DashboardController::class, 'ProductList'])->middleware(['AdminApiLog']);
    Route::get('variantlist', [DashboardController::class, 'VariantList'])->middleware(['AdminApiLog']);
    Route::get('orderlist', [DashboardController::class, 'OrderList'])->middleware(['AdminApiLog']);
    Route::get('couponlist', [DashboardController::class, 'CouponList'])->middleware(['AdminApiLog']);
    Route::get('shippinglist', [DashboardController::class, 'ShippingList'])->middleware(['AdminApiLog']);
    Route::get('taxlist', [DashboardController::class, 'TaxList'])->middleware(['AdminApiLog']);

    Route::post('addcategory', [DashboardController::class, 'AddCategory'])->middleware(['auth:sanctum','AdminApiLog']);
    Route::post('updatecategory', [DashboardController::class, 'UpdateCategory'])->middleware(['auth:sanctum','AdminApiLog']);
    Route::delete('deletecategory', [DashboardController::class, 'DeleteCategory'])->middleware(['auth:sanctum','AdminApiLog']);

    Route::post('addcoupon', [DashboardController::class, 'AddCoupon'])->middleware(['auth:sanctum','AdminApiLog']);
    Route::post('updatecoupon', [DashboardController::class, 'UpdateCoupon'])->middleware(['auth:sanctum','AdminApiLog']);
    Route::delete('deletecoupon', [DashboardController::class, 'DeleteCoupon'])->middleware(['auth:sanctum','AdminApiLog']);
    Route::post('generatecode', [DashboardController::class, 'GenerateCode'])->middleware(['auth:sanctum','AdminApiLog']);

    Route::post('addshipping', [DashboardController::class, 'AddShipping'])->middleware(['auth:sanctum','AdminApiLog']);
    Route::post('updateshipping', [DashboardController::class, 'UpdateShipping'])->middleware(['auth:sanctum','AdminApiLog']);
    Route::delete('deleteshipping', [DashboardController::class, 'DeleteShipping'])->middleware(['auth:sanctum','AdminApiLog']);

    Route::post('addtax', [DashboardController::class, 'AddTax'])->middleware(['auth:sanctum','AdminApiLog']);
    Route::post('updatetax', [DashboardController::class, 'UpdateTax'])->middleware(['auth:sanctum','AdminApiLog']);
    Route::delete('deletetax', [DashboardController::class, 'DeleteTax'])->middleware(['auth:sanctum','AdminApiLog']);

    Route::post('addvariant', [DashboardController::class, 'AddVariant'])->middleware(['auth:sanctum','AdminApiLog']);
    Route::post('updatevariant', [DashboardController::class, 'UpdateVariant'])->middleware(['auth:sanctum','AdminApiLog']);
    Route::delete('deletevariant', [DashboardController::class, 'DeleteVariant'])->middleware(['auth:sanctum','AdminApiLog']);

    Route::get('vieworder', [DashboardController::class, 'ViewOrder'])->middleware(['auth:sanctum','AdminApiLog']);
    Route::delete('deleteorder', [DashboardController::class, 'DeleteOrder'])->middleware(['auth:sanctum','AdminApiLog']);

    Route::get('createproduct', [DashboardController::class, 'CreateProduct'])->middleware(['auth:sanctum','AdminApiLog']);
    Route::post('addproduct', [DashboardController::class, 'AddProduct'])->middleware(['auth:sanctum','AdminApiLog']);
    Route::get('viewproduct', [DashboardController::class, 'ViewProduct'])->middleware(['auth:sanctum','AdminApiLog']);
    Route::post('updateproduct', [DashboardController::class, 'UpdateProduct'])->middleware(['auth:sanctum','AdminApiLog']);
    Route::delete('deleteproduct', [DashboardController::class, 'DeleteProduct'])->middleware(['auth:sanctum','AdminApiLog']);

    Route::post('searchdata', [DashboardController::class, 'SearchData'])->middleware(['auth:sanctum','AdminApiLog']);

    Route::get('createreview', [DashboardController::class, 'CreateReview'])->middleware(['auth:sanctum','AdminApiLog']);
    Route::get('categoryproduct', [DashboardController::class, 'CategoryProduct'])->middleware(['auth:sanctum','AdminApiLog']);
    Route::post('addreview', [DashboardController::class, 'AddReview'])->middleware(['auth:sanctum','AdminApiLog']);
    Route::delete('deletereview', [DashboardController::class, 'DeleteReview'])->middleware(['auth:sanctum','AdminApiLog']);
    Route::post('updatereview', [DashboardController::class, 'UpdateReview'])->middleware(['auth:sanctum','AdminApiLog']);
    Route::post('reviewstatus', [DashboardController::class, 'ReviewStatus'])->middleware(['auth:sanctum','AdminApiLog']);

    Route::get('productdropdown', [DashboardController::class, 'ProductDropdown'])->middleware(['auth:sanctum','AdminApiLog']);
    Route::post('searchproduct', [DashboardController::class, 'SearchProduct'])->middleware(['auth:sanctum','AdminApiLog']);

    Route::get('reviewlist', [DashboardController::class, 'ReviewList'])->middleware(['auth:sanctum','AdminApiLog']);

    Route::get('staticsdata', [DashboardController::class, 'StaticsData'])->middleware(['auth:sanctum','AdminApiLog']);

    Route::post('editprofile', [DashboardController::class, 'EditProfile'])->middleware(['auth:sanctum','AdminApiLog']);
    Route::post('updatepassword', [DashboardController::class, 'UpdatePassword'])->middleware(['auth:sanctum','AdminApiLog']);

    Route::post('emailsetting', [DashboardController::class, 'EmailSetting'])->middleware(['auth:sanctum','AdminApiLog']);
    Route::get('getemailsetting', [DashboardController::class, 'GetEmailSetting'])->middleware(['auth:sanctum','AdminApiLog']);

    Route::post('loyalitysetting', [DashboardController::class, 'LoyalitySetting'])->middleware(['auth:sanctum','AdminApiLog']);
    Route::get('getloyalitysetting', [DashboardController::class, 'GetLoyalitySetting'])->middleware(['auth:sanctum','AdminApiLog']);

    Route::post('cod-payment', [DashboardController::class, 'CodPayment'])->middleware(['auth:sanctum','AdminApiLog']);
    Route::get('get-cod-payment', [DashboardController::class, 'GetCodPayment'])->middleware(['auth:sanctum','AdminApiLog']);

    Route::post('bank-payment', [DashboardController::class, 'BankPayment'])->middleware(['auth:sanctum','AdminApiLog']);
    Route::get('get-bank-payment', [DashboardController::class, 'GetBankPayment'])->middleware(['auth:sanctum','AdminApiLog']);

    Route::post('stripe-payment', [DashboardController::class, 'StripePayment'])->middleware(['auth:sanctum','AdminApiLog']);
    Route::get('get-stripe-payment', [DashboardController::class, 'GetStripePayment'])->middleware(['auth:sanctum','AdminApiLog']);

    Route::post('testmail', [DashboardController::class, 'TestMail'])->middleware(['auth:sanctum','AdminApiLog']);

    Route::post('changestore', [DashboardController::class, 'ChangeStore'])->middleware(['auth:sanctum','AdminApiLog']);

    Route::get('view-used-coupon', [DashboardController::class, 'ViewUsedCoupon'])->middleware(['auth:sanctum','AdminApiLog']);

    Route::delete('deletestore', [DashboardController::class, 'DeleteStore'])->middleware(['auth:sanctum','AdminApiLog']);

    //delivery boy
    Route::post('delivery-login', [DashboardController::class, 'deliveryLogin'])->middleware(['AdminApiLog']);
    Route::get('delivery-order-list', [DashboardController::class, 'DeliveryBoyOrderList'])->middleware(['auth:sanctum','AdminApiLog']);
    Route::get('order-detail', [DashboardController::class, 'orderDetail'])->middleware(['auth:sanctum','AdminApiLog']);
    Route::post('status-change', [DashboardController::class, 'statusChange'])->middleware(['auth:sanctum','AdminApiLog']);
    Route::get('Home', [DashboardController::class, 'deliveryHome'])->middleware(['auth:sanctum','AdminApiLog']);
    Route::get('delivery-transaction', [DashboardController::class, 'deliveryTransaction'])->middleware(['auth:sanctum','AdminApiLog']);
    Route::post('change-profile', [DashboardController::class, 'changeProfile'])->middleware(['auth:sanctum','AdminApiLog']);
    Route::post('logOut', [DashboardController::class, 'logout'])->middleware(['auth:sanctum','AdminApiLog']);
    Route::post('delete-user', [DashboardController::class, 'delete_user'])->middleware(['auth:sanctum','AdminApiLog']);
    Route::post('order-cancel', [DashboardController::class, 'orderCancel'])->middleware(['auth:sanctum','AdminApiLog']);
// });
