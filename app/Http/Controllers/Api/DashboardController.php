<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use App\Models\Store;
use App\Models\Product;
use App\Models\Order;
use App\Models\Utility;
use App\Models\Coupon;
use App\Models\MainCategory;
use App\Models\Shipping;
use App\Models\Tax;
use App\Models\ProductVariant;
use App\Models\Plan;
use App\Models\ProductImage;
use App\Models\ProductStock;
use App\Models\Cart;
use App\Models\Wishlist;
use App\Models\OrderTaxDetail;
use App\Models\OrderCouponDetail;
use App\Models\OrderBillingDetail;
use App\Models\User;
use App\Models\Review;
use App\Models\Setting;
use App\Models\UserCoupon;
use App\Models\UserStore;
use App\Models\Page;
use App\Models\AppSetting;
use App\Models\Blog;
use App\Models\Contact;
use App\Models\Newsletter;
use App\Models\PlanOrder;
use App\Models\DeliveryBoy;
use DB;
use Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\TestMail;

class DashboardController extends Controller
{
    use ApiResponser;

    public function base_url(Request $request)
    {
        $img_url = get_file('themes', 'grocery');
        $data =  explode('themes',$img_url);

        return $this->success(['image_url' => $data[0] , 'payment_url' => url('/')]);
    }

    public function currency(Request $request)
    {
        $user = Auth::user();
        $currentstore = $user->current_store;

        $store = Store::find($currentstore);
        $theme_id = $store->theme_id;

        $array['currency'] = \App\Models\Utility::GetValByName('CURRENCY', $theme_id, $store->id);
        $array['currency_name'] = \App\Models\Utility::GetValByName('CURRENCY_NAME', $theme_id, $store->id);

        return $this->success($array);
    }

    public function login(Request $request)
    {
        $rules = [
            'email' => 'required|email',
        ];

        $validator = \Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return $this->error([
                'message' => $messages->first()
            ]);
        }

        if (!empty($request->password)) {
            $user = Auth::attempt(['email' => $request->email, 'password' => $request->password]);
            if (!$user) {
                return $this->error(['message' => 'Invalid login details']);
            }
            $user = Auth::user();

            $user = User::find($user->id);
        } else {
            return $this->error(['message' => 'Invalid login details']);
        }
        // Auth::loginUsingId(1)

        $user_data = User::find($user->id);

        $user_array['id'] = $user_data->id;
        $user_array['name'] = $user_data->name;
        $user_array['email'] = $user_data->email;
        $user_array['image'] = !empty($user_data->profile_image) ? $user_data->profile_image : "themes/style/uploads/require/user.png";
        $user_array['mobile'] = $user_data->mobile;
        $user_array['current_store'] = $user_data->current_store;

        $user->tokens()->delete();
        $token = $user->createToken('auth_token')->plainTextToken;
        $user_array['token'] = $token;
        $user_array['token_type'] = 'Bearer';
        return $this->success($user_array);
    }

    public function dashboard(Request $request)
    {
        $user = Auth::user();
        $currentstore = $user->current_store;

        $DashboardData=[];
        //user info
        $users = [];
        $userdata = User::find($user->id);
        if(!empty($userdata))
        {
            $users['name'] = $userdata->name;
            $users['image'] = $userdata->profile_image;
        }
        $DashboardData['user'] = $users;

        //Storedata dropdown
        $storearray=[];
        $Allstore = Store::where('created_by',$user->id)->get();
        if(!empty($Allstore))
        {
            foreach ($Allstore as $key => $value) {

                $storearray[$key]['id'] = $value->id;
                $storearray[$key]['name'] = $value->name;
            }
        }
        $DashboardData['store'] = $storearray;

        //statics
        $statics = [];
        $store = Store::find($currentstore);
        $theme_url = route('landing_page',$store->slug);
        $statics['url'] = $theme_url;
        $statics['name'] = $store->name;

        $DashboardData['qr-code'] = $statics;

        //product
        $TotalProducts = Product::where('store_id',$store->id)->where('theme_id',$store->theme_id)->count();
        $TotalSales = Order::where('store_id',$store->id)->where('theme_id',$store->theme_id)->where('delivered_status',1)->count();
        $TotalOrder = Order::where('store_id',$store->id)->where('theme_id',$store->theme_id)->count();

        $DashboardData['products'] = $TotalProducts;
        $DashboardData['sales'] = $TotalSales;
        $DashboardData['order'] = $TotalOrder;

        //top products
        $topproducts = [];
        $Products = Product::orderBy('created_at', 'Desc')->where('store_id',$store->id)->where('theme_id',$store->theme_id)->where('variant_product',0)->limit('2')->get();
        if(!empty($Products))
        {
            // foreach ($Products as $key => $value) {

            //     $topproducts[$key]['id']  = $value->id;
            //     $topproducts[$key]['name']  = $value->name;
            //     $topproducts[$key]['category_id']  = $value->maincategory_id;
            //     $topproducts[$key]['image']  = $value->cover_image_path;
            //     $topproducts[$key]['category']  = $value->category_name;
            //     $topproducts[$key]['final_price']  = $value->final_price;
            //     $topproducts[$key]['quantity']  = $value->product_stock;
            // }
            $DashboardData['product'] = $Products;
        }else{
            return $this->error(['message' => 'Product Data not found!']);
        }
        //recent order
        $recentOrder = [];
        $orders = Order::orderBy('id', 'DESC')->where('theme_id',$store->theme_id)->where('store_id',$store->id)->limit(4)->get();
        if(!empty($orders))
        {
            foreach ($orders as $key => $value) {
                $recentOrder[$key]['id'] = $value->id;
                $recentOrder[$key]['product_order_id'] = $value->product_order_id;
                $recentOrder[$key]['user_name'] = $value->user_name;
                $recentOrder[$key]['order_date'] = date('M d, Y', strtotime($value->order_date));
                $recentOrder[$key]['final_price'] = $value->final_price;
                $recentOrder[$key]['delivery_status'] = $value->delivered_status_string;
                $recentOrder[$key]['currency'] = Utility::GetValByName('CURRENCY_NAME',$store->theme_id,$store->id);
            }
            $DashboardData['recent_order'] = $recentOrder;
        }
        else{
            return $this->error(['message' => 'Order Data not found!']);
        }

        //coupons
        $recentcoupon = [];
        $coupons = Coupon::orderBy('id', 'DESC')->where('theme_id',$store->theme_id)->where('store_id',$store->id)->limit(3)->get();
        if(!empty($coupons))
        {
            foreach ($coupons as $key => $value) {

                $recentcoupon[$key]['id'] = $value->id;
                $recentcoupon[$key]['coupon_name'] = $value->coupon_name;
                $recentcoupon[$key]['coupon_code'] = $value->coupon_code;
                $recentcoupon[$key]['coupon_type'] = $value->coupon_type;
                $recentcoupon[$key]['discount_amount'] = $value->discount_amount;
            }
            $DashboardData['coupons'] = $recentcoupon;
        }

        //order chart
        $arrDuration = [];
        $arrParam = ['duration' => 'week'];


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
        $i = 0;
        $arrTask[$i]['date'] = [];
        $arrTask[$i]['order'] = [];
        foreach ($arrDuration as $date => $label) {
                $data = Order::select(\DB::raw('count(*) as total'))->where('theme_id', $store->theme_id)->where('store_id',$store->id)->whereDate('created_at', '=', $date)->first();


            $arrTask[$i]['date'] = $label;
            $arrTask[$i]['order'] = $data->total;
            $i++;
        }

        $DashboardData['orderchart'] = $arrTask;

        //visitor chart
        $visistorchart = [];
        $arrDuration = [];
        $arrParam = ['duration' => 'month'];
        if($arrParam['duration'])
        {

            if($arrParam['duration'] == 'month')
            {
                $previous_month = strtotime("-2 week +0 day");
                for($i = 0; $i < 15; $i++)
                {
                    $arrDuration[date('Y-m-d', $previous_month)] = date('d-M', $previous_month);
                    $previous_month                              = strtotime(date('Y-m-d', $previous_month) . " +1 day");
                }
            }
        }
        $arrTask          = [];
        $i = 0;
        $arrTask[$i]['date'] = [];
        $arrTask[$i]['pageview']  = [];

        foreach($arrDuration as $date => $label)
        {
            $data['visitor'] = \DB::table('shetabit_visits')->select(\DB::raw('count(*) as total'))->where('theme_id', $store->theme_id)->where('store_id',$store->id)->whereDate('created_at', '=', $date)->first();
            $uniq            = \DB::table('shetabit_visits')->select('ip')->distinct()->where('theme_id', $store->theme_id)->where('store_id',$store->id)->whereDate('created_at', '=', $date)->get();

            $data['unique']           = $uniq->count();
            $arrTask[$i]['date']      = $label;
            $arrTask[$i]['pageview']        = $data['visitor']->total;
            $arrTask[$i]['unique_pageview'] = $data['unique'];
            $i++;
        }

        $DashboardData['visitorchart'] = $arrTask;

        return $this->success($DashboardData);
    }

    public function CategoryList(Request $request)
    {
        $store = Store::find($request->store_id);
        if(!empty($store))
        {
            $theme_id = $store->theme_id;
        }else{
			return $this->error(['message' => 'Store not found!']);
		}

        $MainCategory = MainCategory::where('theme_id', $theme_id)->where('store_id',$store->id)->OrderBy('id', 'desc')->paginate(10);

        if(!empty($MainCategory))
        {
            return $this->success($MainCategory);
        }else{
            return $this->error(['message' => 'Category Data not found.']);
        }

    }

    public function ProductList(Request $request)
    {
        $store = Store::find($request->store_id);
        if(!empty($store))
        {
            $theme_id = $store->theme_id;
        }else{
			return $this->error(['message' => 'Store not found!']);
		}

        $Products = Product::where('theme_id', $theme_id)->where('store_id',$store->id)->OrderBy('id', 'desc')->paginate(10);

        if(!empty($Products))
        {
            return $this->success($Products);
        }else{
            return $this->error(['message' => 'Product Data not found.']);
        }
    }

    public function VariantList(Request $request)
    {
        $store = Store::find($request->store_id);
        if(!empty($store))
        {
            $theme_id = $store->theme_id;
        }else{
			return $this->error(['message' => 'Store not found!']);
		}

        $ProductVariant = ProductVariant::where('theme_id', $theme_id)->where('store_id',$store->id)->OrderBy('id', 'desc')->paginate(10);

        if(!empty($ProductVariant))
        {
            return $this->success($ProductVariant);
        }else{
            return $this->error(['message' => 'Product Variant Data not found.']);
        }
    }

    public function OrderList(Request $request)
    {
        $store = Store::find($request->store_id);
        if(!empty($store))
        {
            $theme_id = $store->theme_id;
        }else{
			return $this->error(['message' => 'Store not found!']);
		}

        $orders = Order::where('theme_id', $theme_id)->where('store_id',$store->id)->OrderBy('id', 'desc')->paginate(10);

        if(!empty($orders))
        {
            return $this->success($orders);
        }else{
            return $this->error(['message' => 'Order Data not found.']);
        }
    }

    public function CouponList(Request $request)
    {
        $store = Store::find($request->store_id);
        if(!empty($store))
        {
            $theme_id = $store->theme_id;
        }else{
			return $this->error(['message' => 'Store not found!']);
		}

        $coupons = Coupon::where('theme_id', $theme_id)->where('store_id',$store->id)->OrderBy('id', 'desc')->paginate(10);

        if(!empty($coupons))
        {
            return $this->success($coupons);
        }else{
            return $this->error(['message' => 'Coupon Data not found.']);
        }
    }

    public function ShippingList(Request $request)
    {
        $store = Store::find($request->store_id);
        if(!empty($store))
        {
            $theme_id = $store->theme_id;
        }else{
			return $this->error(['message' => 'Store not found!']);
		}

        $shipping = Shipping::where('theme_id', $theme_id)->where('store_id',$store->id)->OrderBy('id', 'desc')->paginate(10);

        if(!empty($shipping))
        {
            return $this->success($shipping);
        }else{
            return $this->error(['message' => 'Shipping Data not found.']);
        }
    }

    public function TaxList(Request $request)
    {
        $store = Store::find($request->store_id);
        if(!empty($store))
        {
            $theme_id = $store->theme_id;
        }else{
			return $this->error(['message' => 'Store not found!']);
		}

        $tax = Tax::where('theme_id', $theme_id)->where('store_id',$store->id)->OrderBy('id', 'desc')->paginate(10);

        if(!empty($tax))
        {
            return $this->success($tax);
        }else{
            return $this->error(['message' => 'Tax Data not found.']);
        }
    }

    public function AddCategory(Request $request)
    {
        $store = Store::find($request->store_id);
        if(!empty($store))
        {
            $theme_id = $store->theme_id;
        }else{
			return $this->error(['message' => 'Store not found!']);
		}

        $validator = \Validator::make(
            $request->all(), [
                               'name' => 'required',
                               'image' => 'required',
                               'icon_image' => 'required',
                               'trending' => 'required',
                               'status' => 'required',
                            ]
        );

        if($validator->fails())
        {
            $messages = $validator->getMessageBag();
            return $this->error([
                'message' => $messages->first()
            ]);
        }

        $dir        = 'themes/'.$theme_id.'/uploads';
        if($request->image) {
            $fileName = rand(10,100).'_'.time() . "_" . $request->image->getClientOriginalName();
            $path = Utility::upload_file($request,'image',$fileName,$dir,[]);
            // dd(\Storage::disk('wasabi')->url($path['url']));
        }

        if($request->icon_image) {
            $fileName = rand(10,100).'_'.time() . "_" . $request->icon_image->getClientOriginalName();
            $paths = Utility::upload_file($request,'icon_image',$fileName,$dir,[]);
        }
        // dd(\Storage::disk('wasabi')->url($path['url']) , \Storage::disk('wasabi')->url($paths['url']));

        $MainCategory = new MainCategory();
        $MainCategory->name         = $request->name;
        $MainCategory->image_url    = $path['full_url'];
        $MainCategory->image_path   = $path['url'];
        $MainCategory->icon_path    = $paths['url'];
        $MainCategory->trending     = $request->trending;
        $MainCategory->status       = $request->status;
        $MainCategory->theme_id     = $theme_id;
        $MainCategory->store_id     = $request->store_id;
        $MainCategory->save();

        return $this->success(['message' => 'Maincategory added successfully!']);
    }

    public function UpdateCategory(Request $request)
    {
        $store = Store::find($request->store_id);
        if(!empty($store))
        {
            $theme_id = $store->theme_id;
        }else{
			return $this->error(['message' => 'Store not found!']);
		}

        $rules = [
            'maincategory_id' => 'required'
        ];

        $validator = \Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return $this->error([
                'message' => $messages->first()
            ]);
        }

        $MainCategory = MainCategory::find($request->maincategory_id);
        if(!empty($MainCategory))
        {
            $dir        = 'themes/'.$theme_id.'/uploads';
            if($request->image) {
                $fileName = rand(10,100).'_'.time() . "_" . $request->image->getClientOriginalName();
                $path = Utility::upload_file($request,'image',$fileName,$dir,[]);

                $MainCategory->image_url    = $path['full_url'];
                $MainCategory->image_path   = $path['url'];
            }

            if($request->icon_image) {
                $fileName = rand(10,100).'_'.time() . "_" . $request->icon_image->getClientOriginalName();
                $paths = Utility::upload_file($request,'icon_image',$fileName,$dir,[]);

                $MainCategory->icon_path    = $paths['url'];
            }
            if(!empty($request->name))
            {
                $MainCategory->name         = $request->name;
            }
            if(!empty($request->trending))
            {
                $MainCategory->trending     = $request->trending;
            }
            $MainCategory->save();

            return $this->success(['message' => 'Maincategory Updated successfully!']);
        }
        else{
            return $this->error(['message' => 'Maincategory not found!']);
        }
    }

    public function DeleteCategory(Request $request)
    {
        $store = Store::find($request->store_id);
        if(!empty($store))
        {
            $theme_id = $store->theme_id;
        }else{
			return $this->error(['message' => 'Store not found!']);
		}

        $rules = [
            'maincategory_id' => 'required'
        ];

        $validator = \Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return $this->error([
                'message' => $messages->first()
            ]);
        }

        $MainCategory = MainCategory::find($request->maincategory_id);

        if (!empty($MainCategory)) {
            if(File::exists(base_path($MainCategory->image_path))) {
                File::delete(base_path($MainCategory->image_path));
            }
            if(File::exists(base_path($MainCategory->icon_path))) {
                File::delete(base_path($MainCategory->icon_path));
            }

            $MainCategory->delete();
            return $this->success(['message' => 'Maincategory deleted successfully!.']);
        } else {
            return $this->error(['message' => 'Maincategory data not found.']);
        }
    }

    public function AddCoupon(Request $request)
    {
        $store = Store::find($request->store_id);
        if(!empty($store))
        {
            $theme_id = $store->theme_id;
        }else{
			return $this->error(['message' => 'Store not found!']);
		}

        $validator = \Validator::make(
            $request->all(),
            [
                'coupon_name' => 'required',
                'coupon_type' => 'required',
                'discount_amount' => 'required',
                'coupon_limit' => 'required',
                'coupon_expiry_date' => 'required',
                'coupon_code' => 'required',
            ]
        );

        if($validator->fails())
        {
            $messages = $validator->getMessageBag();
            return $this->error([
                'message' => $messages->first()
            ]);
        }


        $coupon                    = new Coupon();
        $coupon->coupon_name       = $request->coupon_name;
        $coupon->coupon_type       = $request->coupon_type;
        $coupon->discount_amount   = $request->discount_amount;
        $coupon->coupon_limit      = $request->coupon_limit;
        $coupon->coupon_expiry_date= $request->coupon_expiry_date;
        $coupon->coupon_code       = trim($request->coupon_code);
        $coupon->status            = $request->status;
        $coupon->theme_id          = $theme_id;
        $coupon->store_id          = $request->store_id;
        $coupon->save();

        return $this->success(['message' => 'Coupon added successfully!']);
    }

    public function UpdateCoupon(Request $request)
    {
        $store = Store::find($request->store_id);
        if(!empty($store))
        {
            $theme_id = $store->theme_id;
        }else{
			return $this->error(['message' => 'Store not found!']);
		}

        $validator = \Validator::make(
            $request->all(),
            [
                'coupon_id' => 'required',
            ]
        );

        if($validator->fails())
        {
            $messages = $validator->getMessageBag();
            return $this->error([
                'message' => $messages->first()
            ]);
        }

        $coupon = Coupon::find($request->coupon_id);
        if(!empty($request->coupon_name))
        {
            $coupon->coupon_name       = $request->coupon_name;
        }
        if(!empty($request->coupon_type))
        {
            $coupon->coupon_type       = $request->coupon_type;
        }
        if(!empty($request->discount_amount))
        {
            $coupon->discount_amount   = $request->discount_amount;
        }
        if(!empty($request->coupon_limit))
        {
            $coupon->coupon_limit      = $request->coupon_limit;
        }
        if(!empty($request->coupon_expiry_date))
        {
            $coupon->coupon_expiry_date= $request->coupon_expiry_date;
        }
        if(!empty(trim($request->coupon_code)))
        {
            $coupon->coupon_code       = trim($request->coupon_code);
        }
        if(!empty($request->status))
        {
            $coupon->status            = $request->status;
        }
        $coupon->save();


        return $this->success(['message' => 'Coupon Updated successfully!']);
    }

    public function DeleteCoupon(Request $request)
    {
        $store = Store::find($request->store_id);
        if(!empty($store))
        {
            $theme_id = $store->theme_id;
        }else{
			return $this->error(['message' => 'Store not found!']);
		}

        $rules = [
            'coupon_id' => 'required'
        ];

        $validator = \Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return $this->error([
                'message' => $messages->first()
            ]);
        }

        $coupon = Coupon::find($request->coupon_id);
        if(!empty($coupon)){
            $coupon->delete();
            return $this->success(['message' => 'Coupon deleted successfully!.']);
        }else {
                return $this->error(['message' => 'Coupon data not found.']);
        }

    }

    public function GenerateCode(Request $request)
    {
        $store = Store::find($request->store_id);
        if(!empty($store))
        {
            $theme_id = $store->theme_id;
        }else{
			return $this->error(['message' => 'Store not found!']);
		}

        $length = 10;
        $result = '';
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $charactersLength = strlen($characters);
        for ($i = 0; $i < $length; $i++) {
            $result .= $characters[rand(0, $charactersLength - 1)];
        }

        return $this->success($result);
    }

    public function AddShipping(Request $request)
    {
        $store = Store::find($request->store_id);
        if(!empty($store))
        {
            $theme_id = $store->theme_id;
        }else{
			return $this->error(['message' => 'Store not found!']);
		}

        $validator = \Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'charges_type' => 'required',
                'amount' => 'required',
                'return_order_dutation' => 'required',
                'image' => 'required',
                'description' => 'required',
            ]
        );

        if($validator->fails())
        {
            $messages = $validator->getMessageBag();
            return $this->error([
                'message' => $messages->first()
            ]);
        }

        $dir        = 'themes/'.$theme_id.'/uploads';

        if($request->image) {
            $fileName = rand(10,100).'_'.time() . "_" . $request->image->getClientOriginalName();
            $path = Utility::upload_file($request,'image',$fileName,$dir,[]);
        }

        $shipping                   = new Shipping();
        $shipping->name             = $request->name;
        $shipping->description      = $request->description;
        $shipping->charges_type     = $request->charges_type;
        $shipping->image_path       = $path['url'];
        $shipping->image_url        = $path['full_url'];
        $shipping->return_order_dutation = $request->return_order_dutation;
        $shipping->amount           = $request->amount;
        $shipping->status           = $request->status;
        $shipping->theme_id         = $theme_id;
        $shipping->store_id         = $request->store_id;
        $shipping->save();

        return $this->success(['message' => 'Shipping added successfully!']);
    }

    public function UpdateShipping(Request $request)
    {
        $store = Store::find($request->store_id);
        if(!empty($store))
        {
            $theme_id = $store->theme_id;
        }else{
			return $this->error(['message' => 'Store not found!']);
		}

        $rules = [
            'shipping_id' => 'required'
        ];

        $validator = \Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return $this->error([
                'message' => $messages->first()
            ]);
        }

        $shipping = Shipping::find($request->shipping_id);

        if(!empty($shipping))
        {
            $dir        = 'themes/'.$theme_id.'/uploads';

            if($request->image) {
                $fileName = rand(10,100).'_'.time() . "_" . $request->image->getClientOriginalName();
                $path = Utility::upload_file($request,'image',$fileName,$dir,[]);

                $shipping->image_path       = $path['url'];
                $shipping->image_url        = $path['full_url'];
            }

            if(!empty($request->name))
            {
                $shipping->name         = $request->name;
            }
            if(!empty($request->charges_type))
            {
                $shipping->charges_type     = $request->charges_type;
            }
            if(!empty($request->amount))
            {
                $shipping->amount           = $request->amount;
            }
            if(!empty($request->return_order_dutation))
            {
                $shipping->return_order_dutation = $request->return_order_dutation;
            }
            if(!empty($request->description))
            {
                $shipping->description      = $request->description;
            }
            $shipping->save();

            return $this->success(['message' => 'Shipping Updated successfully!']);
        }
        else{
            return $this->error(['message' => 'Shipping not found!']);
        }
    }

    public function DeleteShipping(Request $request)
    {
        $store = Store::find($request->store_id);
        if(!empty($store))
        {
            $theme_id = $store->theme_id;
        }else{
			return $this->error(['message' => 'Store not found!']);
		}

        $rules = [
            'shipping_id' => 'required'
        ];

        $validator = \Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return $this->error([
                'message' => $messages->first()
            ]);
        }

        $shipping = Shipping::find($request->shipping_id);

        if (!empty($shipping)) {
            if(File::exists(base_path($shipping->image_path))) {
                File::delete(base_path($shipping->image_path));
            }

            $shipping->delete();
            return $this->success(['message' => 'Shipping deleted successfully!.']);
        } else {
            return $this->error(['message' => 'Shipping data not found.']);
        }
    }

    public function AddTax(Request $request)
    {
        $store = Store::find($request->store_id);
        if(!empty($store))
        {
            $theme_id = $store->theme_id;
        }else{
			return $this->error(['message' => 'Store not found!']);
		}

        $validator = \Validator::make(
            $request->all(),
            [
                'tax_name' => 'required',
                'tax_type' => 'required',
                'tax_amount' => 'required',
            ]
        );

        if($validator->fails())
        {
            $messages = $validator->getMessageBag();
            return $this->error([
                'message' => $messages->first()
            ]);
        }

        $tax               = new Tax();
        $tax->tax_name     = $request->tax_name;
        $tax->tax_type     = $request->tax_type;
        $tax->tax_amount   = $request->tax_amount;
        $tax->status       = $request->status;
        $tax->theme_id     = $theme_id;
        $tax->store_id     = $request->store_id;
        $tax->save();

        return $this->success(['message' => 'Tax added successfully!']);
    }

    public function UpdateTax(Request $request)
    {
        $store = Store::find($request->store_id);
        if(!empty($store))
        {
            $theme_id = $store->theme_id;
        }else{
			return $this->error(['message' => 'Store not found!']);
		}

        $validator = \Validator::make(
            $request->all(),
            [
                'tax_id' => 'required',
            ]
        );

        if($validator->fails())
        {
            $messages = $validator->getMessageBag();
            return $this->error([
                'message' => $messages->first()
            ]);
        }

        $tax = Tax::find($request->tax_id);

        if(!empty($request->tax_name))
        {
            $tax->tax_name     = $request->tax_name;
        }
        if(!empty($request->tax_type))
        {
            $tax->tax_type     = $request->tax_type;
        }
        if(!empty($request->tax_amount))
        {
            $tax->tax_amount   = $request->tax_amount;
        }
        if(!empty($request->status))
        {
            $tax->status       = $request->status;
        }
        $tax->save();
        return $this->success(['message' => 'Tax Updated successfully!']);
    }

    public function DeleteTax(Request $request)
    {
        $store = Store::find($request->store_id);
        if(!empty($store))
        {
            $theme_id = $store->theme_id;
        }else{
			return $this->error(['message' => 'Store not found!']);
		}

        $rules = [
            'tax_id' => 'required'
        ];

        $validator = \Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return $this->error([
                'message' => $messages->first()
            ]);
        }

        $tax = Tax::find($request->tax_id);

        if(!empty($tax)){
            $tax->delete();
            return $this->success(['message' => 'Tax deleted successfully!.']);
        }else {
                return $this->error(['message' => 'Tax data not found.']);
        }
    }

    public function AddVariant(Request $request)
    {
        $store = Store::find($request->store_id);
        if(!empty($store))
        {
            $theme_id = $store->theme_id;
        }else{
			return $this->error(['message' => 'Store not found!']);
		}

        $validator = \Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'type' => 'required',
            ]
        );

        if($validator->fails())
        {
            $messages = $validator->getMessageBag();
            return $this->error([
                'message' => $messages->first()
            ]);
        }

        $ProductVariant             = new ProductVariant();
        $ProductVariant->name       = $request->name;
        $ProductVariant->type       = $request->type;
        $ProductVariant->theme_id   = $theme_id;
        $ProductVariant->store_id   = $request->store_id;
        $ProductVariant->save();

        return $this->success(['message' => 'Product Variant added successfully!']);
    }

    public function UpdateVariant(Request $request)
    {
        $store = Store::find($request->store_id);
        if(!empty($store))
        {
            $theme_id = $store->theme_id;
        }else{
			return $this->error(['message' => 'Store not found!']);
		}

        $validator = \Validator::make(
            $request->all(),
            [
                'productvariant_id' => 'required',
            ]
        );

        if($validator->fails())
        {
            $messages = $validator->getMessageBag();
            return $this->error([
                'message' => $messages->first()
            ]);
        }

        $ProductVariant = ProductVariant::find($request->productvariant_id);

        if(!empty($request->name))
        {
            $ProductVariant->name       = $request->name;
        }
        if(!empty($request->type))
        {
            $ProductVariant->type       = $request->type;
        }
        $ProductVariant->save();

        return $this->success(['message' => 'Product Variant Updated successfully!']);
    }

    public function DeleteVariant(Request $request)
    {
        $store = Store::find($request->store_id);
        if(!empty($store))
        {
            $theme_id = $store->theme_id;
        }else{
			return $this->error(['message' => 'Store not found!']);
		}

        $rules = [
            'variant_id' => 'required'
        ];

        $validator = \Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return $this->error([
                'message' => $messages->first()
            ]);
        }

        $ProductVariant = ProductVariant::find($request->variant_id);
        if(!empty($ProductVariant)){
            $ProductVariant->delete();
            return $this->success(['message' => 'Product Variant deleted successfully!.']);
        }else {
                return $this->error(['message' => 'Product Variant data not found.']);
        }
    }

    public function CreateProduct(Request $request)
    {
        $store = Store::find($request->store_id);
        if(!empty($store))
        {
            $theme_id = $store->theme_id;
        }else{
			return $this->error(['message' => 'Store not found!']);
		}

        $ProductData=[];

        $categoryarray=[];
        $maincategory = MainCategory::where('theme_id', $theme_id)->where('store_id',$store->id)->get()->toArray();
        if(!empty($maincategory))
        {
            foreach ($maincategory as $key => $value) {
                $categoryarray[$key]['id'] = $value['id'];
                $categoryarray[$key]['name'] = $value['name'];

                $ProductData['category'] = $categoryarray;
            }
        }else {
            return $this->error(['message' => 'Category data not found']);
        }

        $variantarray=[];
        $variant = ProductVariant::where('theme_id', $theme_id)->where('store_id',$store->id)->get()->toArray();

        if(!empty($variant))
        {
            foreach ($variant as $key => $value) {
                $variantarray[$key]['id'] = $value['id'];
                $variantarray[$key]['name'] = $value['name'];

                $ProductData['variant'] = $variantarray;
            }
        }
        // else {
        //     return $this->error(['message' => 'Variant data not found']);
        // }

        $product_details_json_path = base_path('themes/' . $theme_id . '/theme_json/product-detail.json');
            if (file_exists($product_details_json_path)) {
                $product_detail_json = json_decode(file_get_contents($product_details_json_path), true);
                $product_detail_array = [];
                foreach ($product_detail_json as $key => $value) {
                    foreach ($value['inner-list'] as $key1 => $val) {
                        $product_detail_array[$value['section_slug']][$val['field_slug']] = $val['field_default_text'];
                    }
                }
                $ProductData['details'] = $product_detail_array;
            } else {
                return $this->error(['message' => 'Product Details not found.']);
            }

        return $this->success($ProductData);

    }

    public function ViewOrder(Request $request)
    {
        $store = Store::find($request->store_id);
        if(!empty($store))
        {
            $theme_id = $store->theme_id;
        }else{
			return $this->error(['message' => 'Store not found!']);
		}

        $validator = \Validator::make(
            $request->all(),
            [
                'order_id' => 'required',
            ]
        );

        if($validator->fails())
        {
            $messages = $validator->getMessageBag();
            return $this->error([
                'message' => $messages->first()
            ]);
        }

        $order = Order::order_detail($request->order_id);

        if (!empty($order['message'])) {
            return $this->error($order);
        } else {
            return $this->success($order);
        }
    }

    public function DeleteProduct(Request $request)
    {
        $store = Store::find($request->store_id);
        if(!empty($store))
        {
            $theme_id = $store->theme_id;
        }else{
			return $this->error(['message' => 'Store not found!']);
		}

        $rules = [
            'product_id' => 'required'
        ];

        $validator = \Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return $this->error([
                'message' => $messages->first()
            ]);
        }

        $ProductImages = ProductImage::where('product_id', $request->product_id)->get();

        if (!empty($ProductImages)) {
            // image remove from product variant image
            foreach ($ProductImages as $key => $ProductImage) {
                if (File::exists(base_path($ProductImage->image_path))) {
                    File::delete(base_path($ProductImage->image_path));
                }
            }
        }

        ProductImage::where('product_id', $request->product_id)->delete();

        ProductStock::where('product_id', $request->product_id)->delete();

        $Product = Product::find($request->product_id);
        if (!empty($Product)) {
            // image remove from description json
            $description_json = $Product->other_description_api;
            if(!empty($description_json)) {
                $description_json = json_decode($Product->other_description_api, true);
                foreach ($description_json['product-other-description'] as $key => $value) {
                    if($value['field_type'] == 'photo upload') {
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

            Cart::where('product_id', $request->product_id)->delete();
            Wishlist::where('product_id', $request->product_id)->delete();

            Product::where('id', $request->product_id)->delete();
        }else{
            return $this->error(['message' => 'Product not found.']);
        }

        return $this->success(['message' => 'Product deleted successfully!.']);
    }

    public function AddProduct(Request $request)
    {
        // dd($request->all());
        $store = Store::find($request->store_id);
        if(!empty($store))
        {
            $theme_id = $store->theme_id;
        }else{
			return $this->error(['message' => 'Store not found!']);
		}

        $validator = \Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'category_id' => 'required',
                'description' => 'required',
                'cover_image' => 'required',
                'product_image' => 'required',
                'price' => 'required',
                'discount_type' => 'required',
                'discount_amount' => 'required',
                'product_stock' => 'required',
                'trending' => 'required',
                'variant_product' => 'required',
            ]
        );

        if($validator->fails())
        {
            $messages = $validator->getMessageBag();
            return $this->error([
                'message' => $messages->first()
            ]);
        }
        $user = \Auth::user();
        $total_products = $user->countProducts();
        $plan = Plan::find($user->plan);
        $dir        = 'themes/'.$theme_id.'/uploads';

        //tag array
        $product_tag_json_path = base_path('themes/' . $theme_id . '/theme_json/product-tag.json');
            if (file_exists($product_tag_json_path)) {
                $product_tag_json = json_decode(file_get_contents($product_tag_json_path), true);
            } else {
                return $this->error(['message' => 'Product Tag not found.']);
            }

        // description array
        $array = !empty($request->array) ? $request->array : [];
        $array_api = [];
        foreach ($array as $array_key => $slug) {
            foreach ($slug['inner-list'] as $slug_key => $value) {

                $array_api[$slug['section_slug']][$slug_key]['field_type'] = $value['field_type'];
                $array_api[$slug['section_slug']][$slug_key]['name'] = $value['field_name'];
                $array_api[$slug['section_slug']][$slug_key]['value'] = !empty($value['value']) ? $value['value'] : '';

                if($value['field_type'] == 'photo upload') {

                    $theme_name = $theme_id;
                    $theme_image = !empty($value['value']) ? $value['value'] : '';
                    $upload_image_path = !empty($value['value']) ? $value['value'] : '';
                    if(gettype($theme_image) == 'object')  {

                        $fileName = rand(10,100).'_'.time() . "_" . $theme_image->getClientOriginalName();
                        $upload = Utility::jsonUpload_file($theme_image,$fileName,$dir,[]);

                        // $upload = upload_theme_image($theme_name, $theme_image, $slug_key);
                        if($upload['flag'] == true) {
                            $upload_image_path = $upload['image_path'];
                        }
                    }

                    $array[$array_key]['inner-list'][$slug_key]['image_path'] = $upload_image_path;
                    $array[$array_key]['inner-list'][$slug_key]['value'] = $upload_image_path;

                    $array_api[$slug['section_slug']][$slug_key]['field_type'] = $value['field_type'];
                    $array_api[$slug['section_slug']][$slug_key]['name'] = $value['field_name'];
                    $array_api[$slug['section_slug']][$slug_key]['value'] = $upload_image_path;
                }
            }
        }

        // if ($request->variant_product == 0)
        // {
            if ($total_products < $plan->max_products || $plan->max_products == -1)
            {
                $theme_name = $store->theme_id;
                if($request->cover_image) {
                    $fileName = rand(10,100).'_'.time() . "_" . $request->cover_image->getClientOriginalName();
                    $path = Utility::upload_file($request,'cover_image',$fileName,$dir,[]);
                }

                $Product = new Product();
                $Product->name = $request->name;
                $Product->description = $request->description;
                $Product->other_description = !empty($array) ? json_encode($array) : '';
                $Product->other_description_api = !empty($array_api) ? json_encode($array_api) : '';

                $Product->tag = !empty($product_tag_json) ? json_encode($product_tag_json) : '';
                $Product->tag_api = $request->tag;

                $Product->category_id = $request->category_id;
                // if($ThemeSubcategory == 1) {
                //     $Product->subcategory_id = $request->subcategory_id;
                // }
                $Product->cover_image_path = $path['url'];
                $Product->cover_image_url = $path['full_url'];
                $Product->price = $request->price;
                $Product->discount_type = $request->discount_type;
                $Product->discount_amount = $request->discount_amount;
                $Product->product_stock = $request->product_stock;
                $Product->variant_product = $request->variant_product;
                $Product->trending = $request->trending;
                $Product->slug = str_replace(' ','_', strtolower($request->name));
                $Product->theme_id = $theme_id;
                $Product->status = $request->status;
                // $Product->product_option = !empty($option_array) ? json_encode($option_array) : '';
                // $Product->product_option_api = !empty($option_array_api) ? json_encode($option_array_api) : '';
                $Product->store_id     = $request->store_id;
                $Product->created_by   = $user->id;
                $Product->save();

                foreach ($request->product_image as $key => $image) {
                    $theme_image = $image;
                    $theme_name = $store->theme_id;
                    $fileName = rand(10,100).'_'.time() . "_" . $image->getClientOriginalName();
                    $pathss = Utility::keyWiseUpload_file($request,'product_image',$fileName,$dir,$key,[]);

                    $ProductImage = new ProductImage();
                    $ProductImage->product_id = $Product->id;
                    // if($ThemeSubcategory == 1) {
                    //     $Product->subcategory_id = $Product->subcategory_id;
                    // }
                    $ProductImage->image_path = $pathss['url'];
                    $ProductImage->image_url  = $pathss['full_url'];
                    $ProductImage->theme_id   = $theme_id;
                    $ProductImage->store_id   = $request->store_id;
                    $ProductImage->save();
                }
                return $this->success(['message' => 'Product added successfully!']);
            }
            else{
                return $this->error(['message' => 'Your Product limit is over, Please upgrade plan']);
            }

        // }
        // else{

        // }
    }

    public function UpdateProduct(Request $request)
    {
        $store = Store::find($request->store_id);
        if(!empty($store))
        {
            $theme_id = $store->theme_id;
        }else{
			return $this->error(['message' => 'Store not found!']);
		}

        $validator = \Validator::make(
            $request->all(),
            [
                'product_id' => 'required',
                'name' => 'required',
                'category_id' => 'required',
                'description' => 'required',
                'cover_image' => 'required',
                'product_image' => 'required',
                'price' => 'required',
                'discount_type' => 'required',
                'discount_amount' => 'required',
                'product_stock' => 'required',
                'trending' => 'required',
                'variant_product' => 'required',
            ]
        );

        if($validator->fails())
        {
            $messages = $validator->getMessageBag();
            return $this->error([
                'message' => $messages->first()
            ]);
        }


        $dir        = 'themes/'.$theme_id.'/uploads';
        $product = Product::find($request->product_id);
        if(!empty($product))
        {
            // description array
            $old_data = '';
            if(!empty($product->other_description_api)) {
                $old_data = json_decode($product->other_description_api, true);
            }

            $array = $request->array;
            $array_api = [];
            foreach ($array as $array_key => $slug) {
                foreach ($slug['inner-list'] as $slug_key => $value) {

                    $array_api[$slug['section_slug']][$slug_key]['field_type'] = $value['field_type'];
                    $array_api[$slug['section_slug']][$slug_key]['name'] = $value['field_name'];
                    $array_api[$slug['section_slug']][$slug_key]['value'] = !empty($value['value']) ? $value['value'] : '';

                    if($value['field_type'] == 'photo upload') {

                        $theme_name = $theme_id;
                        $theme_image = !empty($value['value']) ? $value['value'] : '';
                        $upload_image_path = !empty($value['value']) ? $value['value'] : '';

                        if(gettype($theme_image) == 'object')  {
                            $image_path = (!empty($old_data[$slug['section_slug']][$slug_key]['value'])) ? $old_data[$slug['section_slug']][$slug_key]['value'] : '';
                            if (File::exists(base_path($image_path))) {
                                File::delete(base_path($image_path));
                            }

                            // $upload = upload_theme_image($theme_name, $theme_image, $slug_key);

                            $fileName = rand(10,100).'_'.time() . "_" . $theme_image->getClientOriginalName();
                            $upload = Utility::jsonUpload_file($theme_image,$fileName,$dir,[]);

                            if($upload['flag'] == true) {
                                $upload_image_path = $upload['image_path'];
                            }
                        }

                        $array[$array_key]['inner-list'][$slug_key]['image_path'] = $upload_image_path;
                        $array[$array_key]['inner-list'][$slug_key]['value'] = $upload_image_path;

                        $array_api[$slug['section_slug']][$slug_key]['field_type'] = $value['field_type'];
                        $array_api[$slug['section_slug']][$slug_key]['name'] = $value['field_name'];
                        $array_api[$slug['section_slug']][$slug_key]['value'] = $upload_image_path;
                    }
                }
            }

            //tag_array
            $product_tag_json_path = base_path('themes/' . $theme_id . '/theme_json/product-tag.json');
            if (file_exists($product_tag_json_path)) {
                $product_tag_json = json_decode(file_get_contents($product_tag_json_path), true);
            } else {
                return $this->error(['message' => 'Product Tag not found.']);
            }

            if($request->cover_image) {
                $fileName = rand(10,100).'_'.time() . "_" . $request->cover_image->getClientOriginalName();
                $path = Utility::upload_file($request,'cover_image',$fileName,$dir,[]);

                $product->cover_image_path = $path['url'];
                $product->cover_image_url = $path['full_url'];
            }

            $product->name = $request->name;
            $product->description = $request->description;
            $product->other_description = !empty($array) ? json_encode($array) : '';
            $product->other_description_api = !empty($array_api) ? json_encode($array_api) : '';

            $product->tag = !empty($product_tag_json) ? json_encode($product_tag_json) : '';
            $product->tag_api = $request->tag;
            $product->category_id = $request->category_id;
            // if($ThemeSubcategory == 1) {
            //         $product->subcategory_id = $request->subcategory_id;
            //     }
            $product->price = $request->price;
            $product->discount_type = $request->discount_type;
            $product->discount_amount = $request->discount_amount;
            $product->product_stock = $request->product_stock;
            $product->variant_product = $request->variant_product;
            $product->trending = $request->trending;
            $product->slug = str_replace(' ','_', strtolower($request->name));
            // $product->theme_id = $theme_id;
            $product->status = $request->status;
            // $product->product_option = !empty($option_array) ? json_encode($option_array) : '';
            // $product->product_option_api = !empty($option_array_api) ? json_encode($option_array_api) : '';
            // $product->store_id     = $request->store_id;
            // $product->created_by   = $user->id;
            $product->save();

            //sub_image
            if(!empty($request->product_image))
            {
                $product_images = ProductImage::where('product_id',$request->product_id)->get();
                if(!empty($product_images))
                {
                    foreach($product_images as $key => $value)
                    {
                        if(File::exists(base_path($value->image_path))) {
                            File::delete(base_path($value->image_path));
                        }
                        $value->delete();
                    }
                }
            }

            foreach ($request->product_image as $key => $image) {
                $theme_image = $image;
                $theme_name = $store->theme_id;
                $fileName = rand(10,100).'_'.time() . "_" . $image->getClientOriginalName();
                $pathss = Utility::keyWiseUpload_file($request,'product_image',$fileName,$dir,$key,[]);

                $ProductImage = new ProductImage();
                $ProductImage->product_id = $product->id;
                // if($ThemeSubcategory == 1) {
                //     $Product->subcategory_id = $Product->subcategory_id;
                // }
                $ProductImage->image_path = $pathss['url'];
                $ProductImage->image_url  = $pathss['full_url'];
                $ProductImage->theme_id   = $theme_id;
                $ProductImage->store_id   = $request->store_id;
                $ProductImage->save();
            }
            return $this->success(['message' => 'Product Updated Successfully!']);

        }else{
            return $this->error(['message' => 'Product not found!']);
        }
    }

    public function ViewProduct(Request $request)
    {

        $store = Store::find($request->store_id);
        if(!empty($store))
        {
            $theme_id = $store->theme_id;
        }else{
			return $this->error(['message' => 'Store not found!']);
		}

        $validator = \Validator::make(
            $request->all(),
            [
                'product_id' => 'required',
            ]
        );

        if($validator->fails())
        {
            $messages = $validator->getMessageBag();
            return $this->error([
                'message' => $messages->first()
            ]);
        }
        $ProductDetail = [];

        $Products = Product::find($request->product_id);
        if(!empty($Products))
        {
            $ProductDetail['Product'] = $Products;
        }
        else{
            return $this->error(['message' => 'Product not found']);
        }

        $subimage = Product::Sub_image($request->product_id);
        if(!empty($subimage))
        {
            $ProductDetail['Sub_Image'] = $subimage['data'];
        }

        if (!empty($ProductDetail)) {
            return $this->success($ProductDetail);
        } else {
            return $this->error(['message' => 'Something went wrong!']);
        }
    }

    public function DeleteOrder(Request $request)
    {
        $store = Store::find($request->store_id);
        if(!empty($store))
        {
            $theme_id = $store->theme_id;
        }else{
			return $this->error(['message' => 'Store not found!']);
		}

        $rules = [
            'order_id' => 'required'
        ];

        $validator = \Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return $this->error([
                'message' => $messages->first()
            ]);
        }

        OrderTaxDetail::where('order_id', $request->order_id)->delete();
        OrderCouponDetail::where('order_id', $request->order_id)->delete();
        OrderBillingDetail::where('order_id', $request->order_id)->delete();
        Order::where('id', $request->order_id)->delete();

        return $this->success(['message' => 'Order deleted successfully!.']);
    }

    public function SearchData(Request $request)
    {
        $store = Store::find($request->store_id);
        if(!empty($store))
        {
            $theme_id = $store->theme_id;
        }else{
			return $this->error(['message' => 'Store not found!']);
		}

        $rules = [
            'type' => 'required'
        ];

        $validator = \Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return $this->error([
                'message' => $messages->first()
            ]);
        }

        if($request->type == 'order')
        {
            $order = Order::where('theme_id',$theme_id)->where('store_id',$request->store_id)->where('product_order_id', 'like',  '%' . $request->name . '%');
            $Data = $order->get()->toArray();
        }
        if($request->type == 'category')
        {
            $category = MainCategory::where('theme_id',$theme_id)->where('store_id',$request->store_id)->where('name', 'like',  '%' . $request->name . '%');
            $Data = $category->get()->toArray();
        }

        if (!empty($Data)) {
            return $this->success($Data);
        } else {
            return $this->error(['message' => 'not found.']);
        }
    }

    public function CreateReview(Request $request)
    {
        $store = Store::find($request->store_id);
        if(!empty($store))
        {
            $theme_id = $store->theme_id;
        }else{
			return $this->error(['message' => 'Store not found!']);
		}
        $ReviewData=[];

        $userarray=[];
        $users = User::where('theme_id', $theme_id)->where('store_id',$store->id)->get()->toArray();
        if(!empty($users))
        {
            foreach ($users as $key => $value) {
                $userarray[$key]['id'] = $value['id'];
                $userarray[$key]['name'] = $value['first_name'];

                $ReviewData['users'] = $userarray;
            }
        }else {
            return $this->error(['message' => 'Users data not found']);
        }

        // $categoryarray=[];
        // $MainCategory = MainCategory::where('theme_id', $theme_id)->where('store_id',$store->id)->get()->toArray();

        // if(!empty($MainCategory))
        // {
        //     foreach ($MainCategory as $key => $value) {

        //         $categoryarray[$key]['id'] = $value['id'];
        //         $categoryarray[$key]['name'] = $value['name'];

        //         $ReviewData['category'] = $categoryarray;
        //     }
        // }else {
        //     return $this->error(['message' => 'Category data not found']);
        // }

        return $this->success($ReviewData);

    }

    public function CategoryProduct(Request $request)
    {
        $store = Store::find($request->store_id);
        if(!empty($store))
        {
            $theme_id = $store->theme_id;
        }else{
			return $this->error(['message' => 'Store not found!']);
		}

        $validator = \Validator::make(
            $request->all(),
            [
                'category_id' => 'required',
            ]
        );

        if($validator->fails())
        {
            $messages = $validator->getMessageBag();
            return $this->error([
                'message' => $messages->first()
            ]);
        }


        $ProductData=[];

        $productarray=[];
        $products = Product::select('id','name')->where('theme_id', $theme_id)->where('store_id',$store->id)->where('category_id',$request->category_id)->get();

        if(!empty($products))
        {
            foreach ($products as $key => $value) {
                $productarray[$key]['id'] = $value['id'];
                $productarray[$key]['name'] = $value['name'];

                $ProductData['products'] = $productarray;
            }
        }else {
            return $this->error(['message' => 'Products data not found']);
        }

        return $this->success($ProductData);

    }

    public function AddReview(Request $request)
    {
        $store = Store::find($request->store_id);
        if(!empty($store))
        {
            $theme_id = $store->theme_id;
        }else{
			return $this->error(['message' => 'Store not found!']);
		}

        $validator = \Validator::make(
            $request->all(),
            [
                'user_id' => 'required',
                'title' => 'required',
                'description' => 'required',
                'category_id' => 'required',
                'product_id' => 'required',
                'rating' => 'required',
                'status' => 'required',
            ]
        );

        if($validator->fails())
        {
            $messages = $validator->getMessageBag();
            return $this->error([
                'message' => $messages->first()
            ]);
        }
        $review                 = new Review();
        $review->user_id        = $request->user_id;
        $review->category_id    = $request->category_id;
        $review->product_id     = $request->product_id;
        $review->rating_no      = $request->rating;
        $review->title          = $request->title;
        $review->description    = $request->description;
        $review->status         = $request->status;
        $review->theme_id       = $theme_id;
        $review->store_id       = $request->store_id;
        $review->save();

        Review::AvregeRating($request->product_id);


        return $this->success(['message' => 'Product Review added successfully!']);
    }

    public function DeleteReview(Request $request)
    {

        $store = Store::find($request->store_id);
        if(!empty($store))
        {
            $theme_id = $store->theme_id;
        }else{
			return $this->error(['message' => 'Store not found!']);
		}

        $rules = [
            'review_id' => 'required'
        ];

        $validator = \Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return $this->error([
                'message' => $messages->first()
            ]);
        }

        $review = Review::find($request->review_id);
        if(!empty($review))
        {
            $review->delete();
            return $this->success(['message' => 'Review deleted successfully!.']);
        }else{
            return $this->error(['message' => 'Review not found']);
        }

    }

    public function UpdateReview(Request $request)
    {

        $store = Store::find($request->store_id);
        if(!empty($store))
        {
            $theme_id = $store->theme_id;
        }else{
			return $this->error(['message' => 'Store not found!']);
		}

        $validator = \Validator::make(
            $request->all(),
            [
                'review_id' => 'required',
            ]
        );

        if($validator->fails())
        {
            $messages = $validator->getMessageBag();
            return $this->error([
                'message' => $messages->first()
            ]);
        }

        $review = Review::find($request->review_id);

        if(!empty($request->user_id))
        {
            $review->user_id        = $request->user_id;
        }
        if(!empty($request->category_id))
        {
            $review->category_id    = $request->category_id;
        }
        if(!empty($request->product_id))
        {
            $review->product_id     = $request->product_id;
        }
        if(!empty($request->rating))
        {
            $review->rating_no      = $request->rating;
        }
        if(!empty($request->title))
        {
            $review->title          = $request->title;
        }
        if(!empty($request->description))
        {
            $review->description    = $request->description;
        }
        if(!empty($request->status))
        {
            $review->status         = $request->status;
        }
        $review->save();

        return $this->success(['message' => 'Product Review Updated successfully!']);
    }

    public function ReviewStatus(Request $request)
    {
        $store = Store::find($request->store_id);
        if(!empty($store))
        {
            $theme_id = $store->theme_id;
        }else{
			return $this->error(['message' => 'Store not found!']);
		}

        $validator = \Validator::make(
            $request->all(),
            [
                'review_id' => 'required',

            ]
        );

        if($validator->fails())
        {
            $messages = $validator->getMessageBag();
            return $this->error([
                'message' => $messages->first()
            ]);
        }
        $review = Review::find($request->review_id);
        if(!empty($review))
        {

            if($review->status == '1')
            {
                $review->status = '0';
                $review->save();
                return $this->success(['message' => 'Product Review status Updated successfully!']);
            }else{
                $review->status = '1';
                $review->save();
                return $this->success(['message' => 'Product Review status Updated successfully!']);
            }
        }
        else{
            return $this->error(['message' => 'Review not found']);
        }

    }

    public function ProductDropdown(Request $request)
    {
        $store = Store::find($request->store_id);
        if(!empty($store))
        {
            $theme_id = $store->theme_id;
        }else{
			return $this->error(['message' => 'Store not found!']);
		}

        $categoryDropdown = [];

        $cat_array = [];
        $product = MainCategory::where('theme_id', $theme_id)->where('store_id',$store->id)->get();

        if(!empty($product))
        {
            foreach($product as $key => $value){
                $cat_array[$key]['id'] = $value->id;
                $cat_array[$key]['name'] = $value->name;
            }
            $categoryDropdown['category'] = $cat_array;
            return $this->success($categoryDropdown);
        }else{
            return $this->error(['message' => 'Product Data not found.']);
        }
    }

    public function SearchProduct(Request $request)
    {
        $store = Store::find($request->store_id);
        if(!empty($store))
        {
            $theme_id = $store->theme_id;
        }else{
			return $this->error(['message' => 'Store not found!']);
		}

        $rules = [
            'category_id' => 'required'
        ];

        $validator = \Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return $this->error([
                'message' => $messages->first()
            ]);
        }

        $products = Product::where('theme_id',$theme_id)->where('store_id',$request->store_id)->where('category_id',$request->category_id)->get()->toArray();

        if(!empty($products))
        {
            return $this->success($products);
        }else{
            return $this->error(['message' => 'Product not found.']);
        }

    }

    public function ReviewList(Request $request)
    {
        $store = Store::find($request->store_id);
        if(!empty($store))
        {
            $theme_id = $store->theme_id;
        }else{
			return $this->error(['message' => 'Store not found!']);
		}

        $rules = [
            'product_id' => 'required'
        ];

        $validator = \Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return $this->error([
                'message' => $messages->first()
            ]);
        }

        $reviewlist = Review::where('product_id', $request->product_id)->where('theme_id', $theme_id)->where('store_id',$store->id)->OrderBy('id', 'desc')->get()->toarray();

        if(!empty($reviewlist))
        {
            return $this->success($reviewlist);
        }else{
            return $this->error(['message' => 'Review Data not found.']);
        }

    }

    public function StaticsData(Request $request)
    {
        $store = Store::find($request->store_id);
        if(!empty($store))
        {
            $theme_id = $store->theme_id;
        }else{
			return $this->error(['message' => 'Store not found!']);
		}

        $DashboardData=[];

        //visitor chart
        $arrDuration = [];
        $arrParam = ['duration' => 'month'];
        if($arrParam['duration'])
        {

            if($arrParam['duration'] == 'month')
            {
                $previous_month = strtotime("-2 week +0 day");
                for($i = 0; $i < 15; $i++)
                {
                    $arrDuration[date('Y-m-d', $previous_month)] = date('d-M', $previous_month);
                    $previous_month                              = strtotime(date('Y-m-d', $previous_month) . " +1 day");
                }
            }
        }
        $arrTask          = [];
        $i = 0;
        $arrTask[$i]['date'] = [];
        $arrTask[$i]['pageview']  = [];

        foreach($arrDuration as $date => $label)
        {
            $data['visitor'] = \DB::table('shetabit_visits')->select(\DB::raw('count(*) as total'))->where('theme_id', $store->theme_id)->where('store_id',$store->id)->whereDate('created_at', '=', $date)->first();
            $uniq            = \DB::table('shetabit_visits')->select('ip')->distinct()->where('theme_id', $store->theme_id)->where('store_id',$store->id)->whereDate('created_at', '=', $date)->get();

            $data['unique']           = $uniq->count();
            $arrTask[$i]['date']      = $label;
            $arrTask[$i]['pageview']        = $data['visitor']->total;
            $arrTask[$i]['unique_pageview'] = $data['unique'];
            $i++;
        }
        $DashboardData['visitorchart'] = $arrTask;

        //Top url
        $topurl = [];
        $visitor_url   = \DB::table('shetabit_visits')->selectRaw("count('*') as total, url")->where('theme_id', $store->theme_id)->where('store_id',$store->id)->groupBy('url')->orderBy('total', 'DESC')->get();
        if(!empty($visitor_url))
        {
            foreach($visitor_url as $key => $value)
            {

                $topurl[$key]['total_click'] =  $value->total;
                $topurl[$key]['url'] =  $value->url;
            }

            $DashboardData['topurl'] = $topurl;
        }

        //platform chart
        $platform = [];
        $color = [
            '#4942a8',
            '#41c9d6',
            '#f8a21e',
            '#e83e8c',
            '#E9FFDF',
            '#045dff',
            '#c972f5',
            '#df5dab',
			'#8ee332',
			'#8c6576',
            '#366451',
        ];


        $user_platform = \DB::table('shetabit_visits')->selectRaw("count('*') as total, platform")->where('theme_id', $store->theme_id)->where('store_id',$store->id)->groupBy('platform')->orderBy('platform', 'DESC')->get();
        if(!empty($user_platform))
        {
            $count = count($color);
            $val = 0;
            foreach($user_platform as $key => $value)
            {

                $platform[$key]['platform'] = !empty($value->platform) ? $value->platform : 'other';
                $platform[$key]['data'] = $value->total;
                $platform[$key]['colour'] = $color[$val];
                $val +=1;
                if($val == ($count-1))
                {
                    $val = 0;
                }
            }

            $DashboardData['platform'] = $platform;
        }

        //browser chart
        $browser = [];
        $color = [
            '#4942a8',
            '#41c9d6',
            '#f8a21e',
            '#e83e8c',
            '#E9FFDF',
            '#045dff',
            '#c972f5',
            '#df5dab',
			'#8ee332',
            '#8c6576',
            '#366451',
        ];
        $user_browser  = \DB::table('shetabit_visits')->selectRaw("count('*') as total, browser")->where('theme_id', $store->theme_id)->where('store_id',$store->id)->groupBy('browser')->orderBy('browser', 'DESC')->get();
        if(!empty($user_browser))
        {
            $count = count($color);
            $val = 0;
            foreach($user_browser as $key => $value)
            {
                $browser[$key]['browser'] = !empty($value->browser) ? $value->browser : 'other';
                $browser[$key]['data'] = $value->total;
                $browser[$key]['colour'] = $color[$val];
                $val +=1;
                if($val == ($count-1))
                {
                    $val = 0;
                }
            }
            $DashboardData['browser'] = $browser;
        }

        //device chart
        $device = [];
        $color = [
            '#4942a8',
            '#41c9d6',
            '#f8a21e',
            '#e83e8c',
            '#E9FFDF',
            '#045dff',
            '#c972f5',
            '#df5dab',
			'#8ee332',
            '#8c6576',
            '#366451',

        ];
        $user_device   = \DB::table('shetabit_visits')->selectRaw("count('*') as total, device")->where('theme_id', $store->theme_id)->where('store_id',$store->id)->groupBy('device')->orderBy('device', 'DESC')->get();
        if(!empty($user_device))
        {
            $count = count($color);
            $val = 0;
            foreach($user_device as $key => $value)
            {
                $device[$key]['device'] = !empty($value->device) ? $value->device : 'other' ;
                $device[$key]['data'] = $value->total;
                $device[$key]['colour'] = $color[$val];
                $val +=1;
                if($val == ($count-1))
                {
                    $val = 0;
                }
            }
            $DashboardData['device'] = $device;
        }

        return $this->success($DashboardData);

    }

    public function EditProfile(Request $request)
    {
        $store = Store::find($request->store_id);
        if(!empty($store))
        {
            $theme_id = $store->theme_id;
        }else{
			return $this->error(['message' => 'Store not found!']);
		}
        $dir        = 'themes/'.$theme_id.'/uploads';

        $rules = [
            'user_id' => 'required'
        ];

        $validator = \Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return $this->error([
                'message' => $messages->first()
            ]);
        }

        $admin = User::find($request->user_id);
        if(!empty($admin))
        {
            if(!empty($request->name))
            {
                $admin->name = $request->name;
            }
            if(!empty($request->email))
            {
                $admin->email = $request->email;
            }
            if ($request->hasFile('profile_image')) {
                $fileName = rand(10,100).'_'.time() . "_" . $request->profile_image->getClientOriginalName();
                $path = Utility::upload_file($request,'profile_image',$fileName,$dir,[]);

                $admin->profile_image = $path['url'];
            }
            $admin->save();
            return $this->success(['message' => 'User Data updated successfully!']);
        }else{
            return $this->error(['message' => 'User not found.']);
        }

    }

    public function UpdatePassword(Request $request)
    {
        $store = Store::find($request->store_id);
        if(!empty($store))
        {
            $theme_id = $store->theme_id;
        }else{
			return $this->error(['message' => 'Store not found!']);
		}

        $rules = [
            'user_id' => 'required',
            'old_password' => 'required',
            'password' => 'required|string|min:4|same:password',
            'password_confirmation' => 'required|string|min:4|same:password',
        ];

        $validator = \Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return $this->error([
                'message' => $messages->first()
            ]);
        }

        $admin = User::find($request->user_id);
        $current_password = $admin->password;

        if(!empty($admin))
        {
            if(Hash::check($request->old_password, $current_password))
            {
                $admin->password = bcrypt($request->password);
                $admin->save();
                return $this->success(['message' => 'User Password updated successfully.']);
            }
            else{
                return $this->error(['message' => 'Please Enter Correct Current Password!']);
            }
        }else{
            return $this->error(['message' => 'User not found.']);
        }

    }

    public function EmailSetting(Request $request)
    {
        $store = Store::find($request->store_id);
        if(!empty($store))
        {
            $theme_id = $store->theme_id;
        }else{
			return $this->error(['message' => 'Store not found!']);
		}

        $rules = [
            'mail_driver' => 'required|string|max:50',
            'mail_host' => 'required|string|max:50',
            'mail_port' => 'required|string|max:50',
            'mail_username' => 'required|string|max:50',
            'mail_password' => 'required|string|max:50',
            'mail_encryption' => 'required|string|max:50',
            'mail_from_address' => 'required|string|max:50',
            'mail_from_name' => 'required|string|max:50',
        ];

        $validator = \Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return $this->error([
                'message' => $messages->first()
            ]);
        }

        $post['MAIL_DRIVER'] = $request->mail_driver;
        $post['MAIL_HOST'] = $request->mail_host;
        $post['MAIL_PORT'] = $request->mail_port;
        $post['MAIL_USERNAME'] = $request->mail_username;
        $post['MAIL_PASSWORD'] = $request->mail_password;
        $post['MAIL_ENCRYPTION'] = $request->mail_encryption;
        $post['MAIL_FROM_NAME'] = $request->mail_from_name;
        $post['MAIL_FROM_ADDRESS'] = $request->mail_from_address;

        foreach ($post as $key => $data) {
            $arr = [
                $data,
                $key,
                $theme_id,
                $request->store_id,
                Auth::user()->id,
            ];

            \DB::insert(
                'insert into settings (`value`, `name`,`theme_id`,`store_id`,`created_by`) values (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ',
                $arr
            );
        }
        return $this->success(['message' => 'Email Setting successfully updated.']);
    }

    public function GetEmailSetting(Request $request)
    {
        $store = Store::find($request->store_id);
        if(!empty($store))
        {
            $theme_id = $store->theme_id;
        }else{
			return $this->error(['message' => 'Store not found!']);
		}

        $emaildata = [];
        $settings = Setting::where('theme_id',$store->theme_id)->where('store_id',$request->store_id)->pluck('value', 'name')->toArray();

        if(!empty($settings))
        {
            $emaildata['MAIL_DRIVER'] = isset($settings['MAIL_DRIVER']) ? $settings['MAIL_DRIVER'] : '';
            $emaildata['MAIL_HOST'] = isset($settings['MAIL_HOST']) ? $settings['MAIL_HOST'] : '';
            $emaildata['MAIL_PORT'] = isset($settings['MAIL_PORT']) ? $settings['MAIL_PORT'] : '';
            $emaildata['MAIL_USERNAME'] = isset($settings['MAIL_USERNAME']) ? $settings['MAIL_USERNAME'] :'';
            $emaildata['MAIL_PASSWORD'] = isset($settings['MAIL_PASSWORD']) ? $settings['MAIL_PASSWORD'] : '';
            $emaildata['MAIL_ENCRYPTION'] = isset($settings['MAIL_ENCRYPTION']) ? $settings['MAIL_ENCRYPTION'] : '';
            $emaildata['MAIL_FROM_NAME'] = isset($settings['MAIL_FROM_NAME']) ? $settings['MAIL_FROM_NAME'] : '';
            $emaildata['MAIL_FROM_ADDRESS'] = isset($settings['MAIL_FROM_ADDRESS']) ? $settings['MAIL_FROM_ADDRESS'] :'';

            return $this->success($emaildata);

        }else{
            return $this->error(['message' => 'Email Setting not found!']);
        }

    }

    public function LoyalitySetting(Request $request)
    {
        $store = Store::find($request->store_id);
        if(!empty($store))
        {
            $theme_id = $store->theme_id;
        }else{
			return $this->error(['message' => 'Store not found!']);
		}

        $rules = [
            'loyality_program_enabled' => 'required',
        ];

        $validator = \Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return $this->error([
                'message' => $messages->first()
            ]);
        }

        $loyality_program_enabled = !empty($request->loyality_program_enabled) ? $request->loyality_program_enabled : 'off';
        $reward_point = !empty($request->reward_point) ? $request->reward_point : 0;

        $post['loyality_program_enabled'] = $loyality_program_enabled;
        $post['reward_point'] = $reward_point;

        foreach ($post as $key => $data) {
            $arr = [
                $data,
                $key,
                $theme_id,
                $request->store_id,
                Auth::user()->id,
            ];

            \DB::insert(
                'insert into settings (`value`, `name`,`theme_id`,`store_id`,`created_by`) values (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ',
                $arr
            );
        }
        return $this->success(['message' => 'Loyality Setting successfully updated.']);
    }

    public function GetLoyalitySetting(Request $request)
    {
        $store = Store::find($request->store_id);
        if(!empty($store))
        {
            $theme_id = $store->theme_id;
        }else{
			return $this->error(['message' => 'Store not found!']);
		}

        $loyalitydata = [];
        $settings = Setting::where('theme_id',$store->theme_id)->where('store_id',$request->store_id)->pluck('value', 'name')->toArray();

        if(!empty($settings))
        {
            $loyalitydata['loyality_program_enabled'] = isset($settings['loyality_program_enabled']) ? $settings['loyality_program_enabled'] : '';
            $loyalitydata['reward_point'] = isset($settings['reward_point']) ? $settings['reward_point'] : '';

            return $this->success($loyalitydata);

        }else{
            return $this->error(['message' => 'Loyality Setting not found!']);
        }

    }

    public function CodPayment(Request $request)
    {

        $store = Store::find($request->store_id);
        if(!empty($store))
        {
            $theme_id = $store->theme_id;
        }else{
			return $this->error(['message' => 'Store not found!']);
		}

        $rules = [
            'CURRENCY_NAME' => 'required',
            'CURRENCY' => 'required',
        ];

        $validator = \Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return $this->error([
                'message' => $messages->first()
            ]);
        }

        $post['CURRENCY'] = $request->CURRENCY;
        $post['CURRENCY_NAME'] = $request->CURRENCY_NAME;


        $is_cod_enabled = !empty($request->is_cod_enabled) ? $request->is_cod_enabled : 'off';

        if ($request->is_cod_enabled == 'on' && !empty($request->cod_image)) {
            $theme_image = $request->cod_image;
            $image = upload_theme_image($theme_id, $theme_image);
            if ($image['status'] == false) {
                return $this->error($image['message']);
            } else {
                $where = ['name' => 'cod_image', 'theme_id' => $theme_id];
                $Setting = Setting::where($where)->first();

                if (!empty($Setting)) {
                    if (File::exists(base_path($Setting->value))) {
                        File::delete(base_path($Setting->value));
                    }
                }
                $post['cod_image'] = $image['image_path'];
            }
        }

        if ($request->is_cod_enabled == 'off') {
            $request->cod_info = '';
        }

        $post['is_cod_enabled'] = $is_cod_enabled;
        $post['cod_info'] = $request->cod_info;


        foreach ($post as $key => $data) {
            $arr = [
                $data,
                $key,
                $theme_id,
                $request->store_id,
                Auth::user()->id,
            ];

            \DB::insert(
                'insert into settings (`value`, `name`,`theme_id`,`store_id`,`created_by`) values (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ',
                $arr
            );
        }
        return $this->success(['message' => 'Cod payment Setting successfully updated.']);
    }

    public function GetCodPayment(Request $request)
    {
        $store = Store::find($request->store_id);
        if(!empty($store))
        {
            $theme_id = $store->theme_id;
        }else{
			return $this->error(['message' => 'Store not found!']);
		}

        $coddata = [];
        $settings = Setting::where('theme_id',$store->theme_id)->where('store_id',$request->store_id)->pluck('value', 'name')->toArray();

        if(!empty($settings))
        {
            $coddata['CURRENCY'] = !empty($settings['CURRENCY']) ? $settings['CURRENCY'] : '';
            $coddata['CURRENCY_NAME'] = !empty($settings['CURRENCY_NAME']) ? $settings['CURRENCY_NAME'] : '';
            $coddata['is_cod_enabled'] = !empty($settings['is_cod_enabled']) ? $settings['is_cod_enabled'] : '';
            $coddata['cod_info'] = !empty($settings['cod_info']) ? $settings['cod_info'] : '';
            $coddata['cod_image'] = !empty($settings['cod_image']) ? $settings['cod_image'] : '';

            return $this->success($coddata);

        }else{
            return $this->error(['message' => 'COD Setting Data not found!']);
        }

    }

    public function BankPayment(Request $request)
    {
        $store = Store::find($request->store_id);
        if(!empty($store))
        {
            $theme_id = $store->theme_id;
        }else{
			return $this->error(['message' => 'Store not found!']);
		}

        $rules = [
            'CURRENCY_NAME' => 'required',
            'CURRENCY' => 'required',
        ];

        $validator = \Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return $this->error([
                'message' => $messages->first()
            ]);
        }

        $post['CURRENCY'] = $request->CURRENCY;
        $post['CURRENCY_NAME'] = $request->CURRENCY_NAME;


        $is_bank_transfer_enabled = !empty($request->is_bank_transfer_enabled) ? $request->is_bank_transfer_enabled : 'off';

        if ($request->is_bank_transfer_enabled == 'on' && !empty($request->bank_transfer_image)) {
            $theme_image = $request->bank_transfer_image;
            $image = upload_theme_image($theme_id, $theme_image);
            if ($image['status'] == false) {
                return $this->error($image['message']);
            } else {
                $where = ['name' => 'bank_transfer_image', 'theme_id' => $theme_id];
                $Setting = Setting::where($where)->first();

                if (!empty($Setting)) {
                    if (File::exists(base_path($Setting->value))) {
                        File::delete(base_path($Setting->value));
                    }
                }
                $post['bank_transfer_image'] = $image['image_path'];
            }
        }

        if ($request->is_bank_transfer_enabled == 'off') {
            $request->bank_transfer = '';
        }

        $post['is_bank_transfer_enabled'] = $is_bank_transfer_enabled;
        $post['bank_transfer'] = $request->bank_transfer;


        foreach ($post as $key => $data) {
            $arr = [
                $data,
                $key,
                $theme_id,
                $request->store_id,
                Auth::user()->id,
            ];

            \DB::insert(
                'insert into settings (`value`, `name`,`theme_id`,`store_id`,`created_by`) values (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ',
                $arr
            );
        }
        return $this->success(['message' => 'Bank payment Setting successfully updated.']);
    }

    public function GetBankPayment(Request $request)
    {
        $store = Store::find($request->store_id);
        if(!empty($store))
        {
            $theme_id = $store->theme_id;
        }else{
			return $this->error(['message' => 'Store not found!']);
		}

        $bankdata = [];
        $settings = Setting::where('theme_id',$store->theme_id)->where('store_id',$request->store_id)->pluck('value', 'name')->toArray();

        if(!empty($settings))
        {
            $bankdata['CURRENCY'] = !empty($settings['CURRENCY']) ? $settings['CURRENCY'] : '';
            $bankdata['CURRENCY_NAME'] = !empty($settings['CURRENCY_NAME']) ? $settings['CURRENCY_NAME'] : '';
            $bankdata['is_bank_transfer_enabled'] = !empty($settings['is_bank_transfer_enabled']) ? $settings['is_bank_transfer_enabled'] : '';
            $bankdata['bank_transfer'] = !empty($settings['bank_transfer']) ? $settings['bank_transfer'] : '';
            $bankdata['bank_transfer_image'] = !empty($settings['bank_transfer_image']) ? $settings['bank_transfer_image'] : '';

            return $this->success($bankdata);

        }else{
            return $this->error(['message' => 'Bank Payment Setting Data not found!']);
        }

    }

    public function StripePayment(Request $request)
    {
        $store = Store::find($request->store_id);
        if(!empty($store))
        {
            $theme_id = $store->theme_id;
        }else{
			return $this->error(['message' => 'Store not found!']);
		}

        $rules = [
            'CURRENCY_NAME' => 'required',
            'CURRENCY' => 'required',
        ];

        $validator = \Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return $this->error([
                'message' => $messages->first()
            ]);
        }

        $post['CURRENCY'] = $request->CURRENCY;
        $post['CURRENCY_NAME'] = $request->CURRENCY_NAME;


        $is_stripe_enabled = !empty($request->is_stripe_enabled) ? $request->is_stripe_enabled : 'off';

        if ($request->is_stripe_enabled == 'on' && !empty($request->stripe_image)) {
            $theme_image = $request->stripe_image;
            $image = upload_theme_image($theme_id, $theme_image);
            if ($image['status'] == false) {
                return $this->error($image['message']);
            } else {
                $where = ['name' => 'stripe_image', 'theme_id' => $theme_id];
                $Setting = Setting::where($where)->first();

                if (!empty($Setting)) {
                    if (File::exists(base_path($Setting->value))) {
                        File::delete(base_path($Setting->value));
                    }
                }
                $post['stripe_image'] = $image['image_path'];
            }
        }



        $post['is_stripe_enabled'] = $is_stripe_enabled;
        $post['stripe_unfo'] = !empty($request->stripe_unfo) ? $request->stripe_unfo :'';
        $post['publishable_key'] = !empty($request->publishable_key) ? $request->publishable_key : '';
        $post['stripe_secret'] = !empty($request->stripe_secret) ? $request->stripe_secret :'';

        if ($request->is_stripe_enabled == 'off') {
            $post['stripe_unfo'] = '';
            $post['publishable_key'] = '';
            $post['stripe_secret'] = '';
        }

        foreach ($post as $key => $data) {
            $arr = [
                $data,
                $key,
                $theme_id,
                $request->store_id,
                Auth::user()->id,
            ];

            \DB::insert(
                'insert into settings (`value`, `name`,`theme_id`,`store_id`,`created_by`) values (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ',
                $arr
            );
        }
        return $this->success(['message' => 'Stripe payment Setting successfully updated.']);
    }

    public function GetStripePayment(Request $request)
    {
        $store = Store::find($request->store_id);
        if(!empty($store))
        {
            $theme_id = $store->theme_id;
        }else{
			return $this->error(['message' => 'Store not found!']);
		}

        $stripedata = [];
        $settings = Setting::where('theme_id',$store->theme_id)->where('store_id',$request->store_id)->pluck('value', 'name')->toArray();

        if(!empty($settings))
        {
            $stripedata['CURRENCY'] = !empty($settings['CURRENCY']) ? $settings['CURRENCY'] : '';
            $stripedata['CURRENCY_NAME'] = !empty($settings['CURRENCY_NAME']) ? $settings['CURRENCY_NAME'] : '';
            $stripedata['is_stripe_enabled'] = !empty($settings['is_stripe_enabled']) ? $settings['is_stripe_enabled'] : '';
            $stripedata['publishable_key'] = !empty($settings['publishable_key']) ? $settings['publishable_key'] : '';
            $stripedata['stripe_secret'] = !empty($settings['stripe_secret']) ? $settings['stripe_secret'] : '';
            $stripedata['stripe_unfo'] = !empty($settings['stripe_unfo']) ? $settings['stripe_unfo'] : '';
            $stripedata['stripe_image'] = !empty($settings['stripe_image']) ? $settings['stripe_image'] : '';

            return $this->success($stripedata);

        }else{
            return $this->error(['message' => 'Stripe Payment Setting Data not found!']);
        }

    }

    public function TestMail(Request $request)
    {
        $store = Store::find($request->store_id);
        if(!empty($store))
        {
            $theme_id = $store->theme_id;
        }else{
			return $this->error(['message' => 'Store not found!']);
		}

        $rules = [
            'email' => 'required|email'
        ];

        $validator = \Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return $this->error([
                'message' => $messages->first()
            ]);
        }

        $settings = Setting::where('theme_id',$store->theme_id)->where('store_id',$request->store_id)->pluck('value', 'name')->toArray();

        try {
            config(
                [
                    'mail.driver' => $settings['MAIL_DRIVER'],
                    'mail.host' => $settings['MAIL_HOST'],
                    'mail.port' => $settings['MAIL_PORT'],
                    'mail.encryption' => $settings['MAIL_ENCRYPTION'],
                    'mail.username' => $settings['MAIL_USERNAME'],
                    'mail.password' => $settings['MAIL_PASSWORD'],
                    'mail.from.address' => $settings['MAIL_FROM_ADDRESS'],
                    'mail.from.name' => $settings['MAIL_FROM_NAME'],
                ]
            );

            Mail::to($request->email)->send(new TestMail());
        } catch (\Exception $e) {
            $smtp_error = __('E-Mail has been not sent due to SMTP configuration');
            return $this->error(['message' =>$smtp_error]);
        }
        return $this->success(['message' => 'Email send Successfully.']);

    }

    public function ChangeStore(Request $request)
    {
        $user = \Auth::user();

        $rules = [
            'store_id' => 'required',
        ];

        $validator = \Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return $this->error([
                'message' => $messages->first()
            ]);
        }

        $store = Store::find($request->store_id);
        if(!empty($store))
        {
            $user->current_store = $store->id;
            $user->update();
            return $this->success(['message' => 'Store Change Successfully!']);
        }else{
            return $this->error(['message' => 'Store not found!']);
        }

    }

    public function ViewUsedCoupon(Request $request)
    {
        $store = Store::find($request->store_id);
        if(!empty($store))
        {
            $theme_id = $store->theme_id;
        }else{
			return $this->error(['message' => 'Store not found!']);
		}

        $rules = [
            'coupon_id' => 'required'
        ];

        $validator = \Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return $this->error([
                'message' => $messages->first()
            ]);
        }


        $UserCoupons = UserCoupon::where('coupon_id', $request->coupon_id)->get()->toArray();
        if(!empty($UserCoupons))
        {
            return $this->success($UserCoupons);
        }else{
            return $this->error(['message' => 'Data not found!']);

        }
    }

    public function DeleteStore(Request $request)
    {
        $store = Store::find($request->store_id);
        if(!empty($store))
        {
            $theme_id = $store->theme_id;
        }else{
			return $this->error(['message' => 'Store not found!']);
		}

        $user = \Auth::user();
        $userstore = UserStore::where('user_id',$user->id)->count();

        if($userstore > 1){
            UserStore::where('store_id', $store->id)->delete();
            Page::where('store_id', $store->id)->delete();
            Order::where('store_id', $store->id)->delete();
            AppSetting::where('store_id', $store->id)->delete();
            Blog::where('store_id', $store->id)->delete();
            Contact::where('store_id', $store->id)->delete();
            Coupon::where('store_id', $store->id)->delete();
            MainCategory::where('store_id', $store->id)->delete();
            Newsletter::where('store_id', $store->id)->delete();
            PlanOrder::where('store_id', $store->id)->delete();
            Review::where('store_id', $store->id)->delete();
            Setting::where('store_id', $store->id)->delete();
            Shipping::where('store_id', $store->id)->delete();
            Tax::where('store_id', $store->id)->delete();
            ProductVariant::where('store_id', $store->id)->delete();

            $products = Product::where('store_id', $store->id)->get();
            $pro_img = new ProductController();
            foreach ($products as $pro) {
                $pro_img->destroy($pro);
            }

            $store->delete();
            $userstore = UserStore::where('user_id', $user->id)->first();

            $user->current_store = $userstore->id;
            $user->save();

            $user_data['id'] = $user->id;
            $user_data['name'] = $user->name;
            $user_data['email'] = $user->email;
            $user_data['current_store'] = $user->current_store;

            return $this->success(['message' => 'Store deleted successfully!','user_info'=>$user_data ] );

        }else{
            return $this->error(['message' => 'You may have only 1 store!']);
        }

    }

    //Delivery APP
    //login

    public function deliveryLogin(Request $request)
    {
        $rules = [
            'email' => 'required|email',
        ];

        $validator = \Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return $this->error([
                'message' => $messages->first()
            ]);
        }

        if (!empty($request->password)) {
            $user = Auth::guard('deliveryboy')->attempt(['email' => $request->email, 'password' => $request->password]);
            if (!$user) {
                return $this->error(['message' => 'Invalid login details']);
            }
            $user = Auth::guard('deliveryboy')->user();

            $user = DeliveryBoy::find($user->id);
            
        } else {
            return $this->error(['message' => 'Invalid login details']);
        }

        $user_data = DeliveryBoy::find($user->id);

        $user_array['currency'] = \App\Models\Utility::GetValByName('CURRENCY', $user_data->theme_id, $user_data->store_id);
        $user_array['currency_name'] = \App\Models\Utility::GetValByName('CURRENCY_NAME', $user_data->theme_id, $user_data->store_id);


        $user_array['id'] = $user_data->id;
        $user_array['name'] = $user_data->name;
        $user_array['email'] = $user_data->email;
        $user_array['image'] = !empty($user_data->profile_image) ? $user_data->profile_image : "/storage/uploads/avatar.png";
        $user_array['contact'] = $user_data->contact;

        $user->tokens()->delete();
        $token = $user->createToken('auth_token')->plainTextToken;
        $user_array['token'] = $token;
        $user_array['token_type'] = 'Bearer';
        return $this->success($user_array);
    }

    public function DeliveryBoyOrderList(Request $request)
    {
        $user  = Auth::user();
        if($request->type == 'pending')
        {
            $orders = Order::select('id', 'product_order_id as product_order_id', 'order_date as date', 'final_price as amount','delivered_status','delivery_date','payment_status')
                ->where('deliveryboy_id', $user->id)
                ->whereIn('delivered_status',[0,4,5,6])
                ->paginate(10);
        }
        if($request->type == 'complete')
        {
            $orders = Order::select('id', 'product_order_id as product_order_id', 'order_date as date', 'final_price as amount','delivered_status','delivery_date','payment_status')
                ->where('deliveryboy_id', $user->id)
                ->where('delivered_status',1)
                ->paginate(10);
        }
        if($request->type == 'cancel')
        {
            $orders = Order::select('id', 'product_order_id as product_order_id', 'order_date as date', 'final_price as amount','delivered_status','delivery_date','payment_status')
                ->where('deliveryboy_id', $user->id)
                ->where('delivered_status',2)
                ->paginate(10);
        }
        
        if (!empty($orders)) {
            $orders->load('billingData');
            return $this->success($orders);
        } else {
            return $this->error(['message' => 'Order Data not found.']);
        }
    }

    public function orderDetail(Request $request)
    {
        $validator = \Validator::make(
            $request->all(),
            [
                'order_id' => 'required',
            ]
        );

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return $this->error([
                'message' => $messages->first()
            ]);
        }

        $order = Order::order_detail($request->order_id);

        if (!empty($order['message'])) {
            return $this->error($order);
        } else {
            return $this->success($order);
        }
    }

    public function statusChange(Request $request)
    {
        $validator = \Validator::make(
            $request->all(),
            [
                'id' => 'required',
                'delivered' => 'required',
            ]
        );

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return $this->error([
                'message' => $messages->first()
            ]);
        }

        $data['order_id'] = $request->id;
        $data['order_status'] = $request->delivered;
        
        $responce = Order::order_status_change($data);
        $order = Order::find($request->id);
        
        if($order->delivered_status == 4)
        {
            $responce['order_status'] = 'pickedup';
        }
        if($order->delivered_status == 5)
        {
            $responce['order_status'] = 'shipped';
        }
        if($order->delivered_status == 6)
        {
            $responce['order_status'] = 'delivered';
        }

        if ($responce) {
            return $this->success($responce);
        } 
        else {
            return $this->error(['message' => 'Something went wrong.']);
        }
    }

    public function deliveryHome(Request $request)
    {
        $user  = Auth::user();

        $counterData = [];
        $total_pending = Order::where('deliveryboy_id',$user->id)
                                ->whereIn('delivered_status',[0,4,5,6])
                                ->count();

        $total_complete = Order::where('deliveryboy_id',$user->id)
                                ->where('delivered_status',1)
                                ->count();
        
        $total_cancel = Order::where('deliveryboy_id',$user->id)
                                ->where('delivered_status',2)
                                ->count();

        $counterData['pending'] = $total_pending;
        $counterData['complete'] = $total_complete;
        $counterData['cancel'] = $total_cancel;
        
        //total earning
        $total_earning = Order::select(\DB::raw('SUM(final_price) as total_amount'))
                        ->where('deliveryboy_id', $user->id)
                        ->get();
                        
        $counterData['total_earning'] =  (int)($total_earning->isEmpty() ? 0 : $total_earning->first()->total_amount);
        
        //today earning
        $today_earning = Order::select(\DB::raw('SUM(final_price) as total_amount'))
                        ->where('deliveryboy_id', $user->id)
                        ->whereRaw('DATE(order_date) = CURDATE()')
                        ->get();

        $counterData['today_earning'] =  (int)($today_earning->isEmpty() ? 0 : $today_earning->first()->total_amount);

        //order
        $assign_order = Order::orderBy('created_at', 'Desc')
                            ->select('id', 'product_order_id as product_order_id', 'order_date as date', 'final_price as amount','delivered_status','delivery_date','payment_type','payment_status')
                            ->where('deliveryboy_id', $user->id)
                            ->limit('5')
                            ->get();

        $counterData['transaction'] = $assign_order;
        
        return $this->success($counterData);
    }
    
    public function deliveryTransaction(Request $request)
    {
        // {
        //     $user  = Auth::user();
            
        //     $transactions = Order::select('id', 'product_order_id as product_order_id', 'order_date as date', 'final_price as amount','delivered_status','delivery_date','payment_status','payment_type')
        //                         ->where('agent_id', $user->id)
        //                         ->paginate(10);
    
        //     if (!empty($transactions)) {
        //         return $this->success($transactions);
        //     } else {
        //         return $this->error(['message' => 'Transaction Data not found.']);
        //     }
    
        // }

        $user  = Auth::user();
        
        $transactions = Order::select('id', 'product_order_id as product_order_id', 'order_date as date', 'final_price as amount','delivered_status','delivery_date','payment_status','payment_type')
                            ->where('deliveryboy_id', $user->id)
                            ->paginate(10);

        //total earning
        $total_earning = Order::select(\DB::raw('SUM(final_price) as total_amount'))
                        ->where('deliveryboy_id', $user->id)
                        ->get();
                        
        $counterData['total_earning'] =  (int)($total_earning->isEmpty() ? 0 : $total_earning->first()->total_amount);
        

        //today earning
        $today_earning = Order::select(\DB::raw('SUM(final_price) as total_amount'))
                        ->where('deliveryboy_id', $user->id)
                        ->whereRaw('DATE(order_date) = CURDATE()')
                        ->get();

        $counterData['today_earning'] =  (int)($today_earning->isEmpty() ? 0 : $today_earning->first()->total_amount);
        
        if (!empty($transactions)) {
            $counterData['transaction'] = $transactions;
        }

        return $this->success($counterData);

    }
    
    public function changeProfile(Request $request)
    {
        $rules = [
            'id' => 'required'
        ];

        $validator = \Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return $this->error([
                'message' => $messages->first()
            ]);
        }
        $deliveryboy = DeliveryBoy::find($request->id);

        $dir        = 'themes/grocery/uploads';

        if (!empty($deliveryboy)) {
            if (!empty($request->name)) {
                $deliveryboy->name = $request->name;
            }
            if (!empty($request->email)) {
                $deliveryboy->email = $request->email;
            }
            if (!empty($request->contact)) {
                $deliveryboy->contact = $request->contact;
            }
            
            if ($request->hasFile('profile_image')) {
                $fileName = rand(10, 100) . '_' . time() . "_" . $request->profile_image->getClientOriginalName();
                $path = Utility::upload_file($request, 'profile_image', $fileName, $dir, []);

                $deliveryboy->profile_image = $path['url'];
            }
            $deliveryboy->save();

            $user_array['id'] = $deliveryboy->id;
            $user_array['name'] = $deliveryboy->name;
            $user_array['email'] = $deliveryboy->email;
            $user_array['contact'] = $deliveryboy->contact;
            $user_array['image'] = !empty($deliveryboy->profile_image) ? $deliveryboy->profile_image : "/storage/uploads/avatar.png";
            
            return $this->success($user_array);
        } else {
            return $this->error(['message' => 'Delivery Boy not found.']);
        }

    }

    public function logout(Request $request)
    {
        $user = DeliveryBoy::find($request->user_id);
        if (!empty($user)) {
            return $this->success([
                'message' => 'User Logout',
                'logout' => $user->tokens()->delete()
            ]);
        } else {
            return $this->error([
                'message' => 'User not found'
            ]);
        }
    }

    public function delete_user(Request $request)
    {
        $rules = [
            'user_id' => 'required'
        ];
        
        $validator = \Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return $this->error([
                'message' => $messages->first()
            ]);
        }
        
        $user = DeliveryBoy::find($request->user_id);
        if (!empty($user)) {
            $user->delete();

            return $this->success(['message' => 'User Deleted successfully']);
        } else {
            return $this->error(['message' => 'User not found.']);
        }
    }
    
    public function orderCancel(Request $request)
    {

        $validator = \Validator::make(
            $request->all(),
            [
                'order_id' => 'required',
            ]
        );

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return $this->error([
                'message' => $messages->first()
            ]);
        }

        $user = DeliveryBoy::find($request->user_id);
        $order = Order::find($request->order_id);

        if(!empty($order))
        {
            if($order->delivered_status == '0' && $order->deliveryboy_id == $user->id)
            {
                $order->deliveryboy_id =  'null';
                $order->save();

                return $this->success(['message' => 'Order cancel Successfully.']);
            }
            else{
                return $this->error(['message' => 'You cannot cancel this order.']);
            }
        }
        else
        {
            return $this->error(['message' => 'Order not found.']);
        }
    }
}
