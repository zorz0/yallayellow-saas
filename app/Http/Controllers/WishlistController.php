<?php

namespace App\Http\Controllers;

use App\Models\{Store, Wishlist};
use App\Models\ProductVariant;
use App\Models\Utility;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiController;
use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Facades\Crypt;

class WishlistController extends Controller
{
    public function index()
    {
        // if (auth()->user()->isAbleTo('Manage Wishlist')) {
            $wishlists = Wishlist::where('theme_id', APP_THEME())->where('store_id', getCurrentStore())->groupBy('customer_id')->get();



            return view('wishlist.index', compact('wishlists'));
        // } else {
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Wishlist  $wishlist
     * @return \Illuminate\Http\Response
     */
    public function show(Wishlist $wishlist)
    {
        // if (auth()->user()->isAbleTo('Show Wishlist')) {
            $wish_id = $wishlist->user_id;
            $wishlist_product = Wishlist::where('user_id', $wish_id)->where('theme_id', APP_THEME())->get();

            return view('wishlist.show', compact('wishlist_product'));
        // } else {
        //     return redirect()->back()->with('error', __('Permission denied.'));
        // }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Wishlist  $wishlist
     * @return \Illuminate\Http\Response
     */
    public function edit(Wishlist $wishlist)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Wishlist  $wishlist
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Wishlist $wishlist)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Wishlist  $wishlist
     * @return \Illuminate\Http\Response
     */
    public function destroy(Wishlist $wishlist)
    {

        if (auth()->user()->isAbleTo('Delete Wishlist')) {
            $wishlist->delete();
            return redirect()->back()->with('success', __('Wishlist delete successfully.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function abandon_wish_emailsend(Request $request)
    {
        // if (auth()->user()->isAbleTo('Abandon Wishlist')) {
            $wish  = Wishlist::find($request->wish_id);
            $user_id = $wish->user_id;
            $wish_product = Wishlist::where('customer_id', $user_id)->where('theme_id', APP_THEME())->get();
            $email = $wish->UserData->email ?? null;

            $store = Store::where('id', getCurrentStore())->first();
            $owner = User::find($store->created_by);
            $product_id    = Crypt::encrypt($wish->product_id);


            try {
                $dArr = Wishlist::where('customer_id', $user_id)->where('theme_id', APP_THEME())->get();

                $order_id = 1;
                $resp  = Utility::sendEmailTemplate('Abandon Wishlist', $email, $dArr, $owner, $store, $product_id,$user_id);
                // $return = 'Mail send successfully';
                if($resp['is_success'] == false)
                {
                    return response()->json(
                        [
                            'is_success' => false,
                            'message' => $resp['error'],
                        ]
                    );
                }
                else
                {
                    return response()->json(
                        [
                            'is_success' => true,
                            'message' => 'Mail send successfully',
                        ]
                    );
                }

            } catch (\Exception $e) {
                $smtp_error = __('E-Mail has been not sent due to SMTP configuration');
                return response()->json(
                    [
                        'is_success' => false,
                        'message' => $smtp_error,
                    ]
                );
            }
        // } else {
        //     return redirect()->back()->with('error', __('Permission denied.'));
        // }
    }

    public function product_wishlist(Request $request, $storeSlug)
    {
        $slug = $storeSlug;
        $store = Store::where('slug', $storeSlug)->first();
        $theme_id = $store->theme_id;

        $customer_id = auth('customers')->user()->id ?? null;
        $product_id = $request->product_id;
        $wishlist_type = $request->wishlist_type;

        $request->request->add(['product_id' => $product_id, 'customer_id' => $customer_id, 'wishlist_type' => $wishlist_type, 'store_id' => $store->id, 'slug' => $slug]);
        $api = new ApiController();
        $data = $api->wishlist($request, $slug);

        $response = $data->getData();

        return response()->json($response);
    }

    public function wish_list_sidebar(Request $request, $storeSlug)
    {
        // dd($request->all(),$slug);

        $slug = !empty($storeSlug) ? $storeSlug : '';
        $store = Store::where('slug', $slug)->first();
        $theme_id = $store->theme_id;
        $currentTheme = $theme_id = $store->theme_id;
        if (auth('customers')->guest()) {
            // $response = Wishlist::wish_list_cookie();
            // $response = json_decode(json_encode($response));

        } else {
            $request->merge(['customer_id' => auth('customers')->user()->id, 'slug' => $slug, 'theme_id' => $theme_id]);
            $api = new ApiController();
            $data = $api->wishlist_list($request, $slug);
            $response = $data->getData();
        }


        $return['status'] = $response->status ?? null;
        $return['message'] = $response->message ?? null;
        $return['sub_total'] = 0;
        if (isset($response) && $response->status == 1) {
            $currency = Utility::GetValueByName('CURRENCY', $theme_id);
            $currency_name = Utility::GetValueByName('CURRENCY_NAME', $theme_id);

            $return['html'] = view('front_end.sections.pages.wish-list-sidebar', compact('slug', 'response', 'currency', 'currency_name','store','currentTheme'))->render();
        }
        return response()->json($return);
    }

    public function abandon_wishlist_messsend(Request $request){
        $cart  = Wishlist::find($request->wish_id);
        $user_id = $cart->user_id;
        $mobile = $cart->UserData;
        // if (auth()->user()->isAbleTo('Abandon Cart')) {

            try {
                $dArr = Wishlist::where('user_id', $user_id)->where('theme_id', APP_THEME())->pluck('product_id')->toArray();

                $product = [];
                foreach ($dArr as $item) {
                    $product[] = Product::where('id', $item)->pluck('name')->first();
                }
                $product_name = implode(',',$product);
                $store = Store::where('id', getCurrentStore())->first();
                $msg = __("We noticed that you have been browsing our site and have added some fantastic items to your wishlist. Hurry, some of these items are selling out fast. With limited stock and high demand, now is the perfect time to make your dream purchases, Added Product name : $product_name");
                $resp  = Utility::SendMsgs('Abandon Cart', $mobile, $msg);

                // $return = 'Mail send successfully';
                if($resp  == false)
                {
                    return response()->json(
                        [
                            'is_success' => false,
                            'message' => "Invalid Auth access token - Cannot parse access token",
                        ]
                    );
                }
                else
                {
                    return response()->json(
                        [
                            'is_success' => true,
                            'message' => 'Message send successfully',
                        ]
                    );
                }
            } catch (\Exception $e) {

                $smtp_error = __('Invalid Auth access token - Cannot parse access token');
                return response()->json(
                    [
                        'is_success' => false,
                        'message' => $smtp_error,
                    ]
                );
            }

        // } else {
        //     return redirect()->back()->with('error', __('Permission denied.'));
        // }
    }
}
