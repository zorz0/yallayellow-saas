<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Store;
use App\Models\Coupon;
use App\Models\User;
use App\Models\Plan;
use App\Models\Utility;
use App\Models\Order;
use App\Models\Setting;
use App\Models\Product;
use App\Models\OrderNote;
use App\Models\OrderTaxDetail;
use App\Models\OrderCouponDetail;
use App\Models\OrderBillingDetail;
use App\Models\ProductVariant;
use App\Models\MainCategory;
use Stripe;
use App\Models\AppSetting;
use Illuminate\Http\RedirectResponse;
use Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\Api\ApiController;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\OrderExport;
use Qirolab\Theme\Theme;
use Carbon\Carbon;
use App\Models\DeliveryBoy;

class OrderController extends Controller
{

    public function index(Request $request)
    {
        if (auth()->user()->isAbleTo('Manage Order')) {
            $id = Auth::user()->id;
            $store_id = Store::where('id', getCurrentStore())->first();
            $orders = Order::where('theme_id', $store_id->theme_id)->where('store_id', getCurrentStore())->get();
            return view('order.index', compact('orders'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function show(Order $order,$id)
    {

        $id = Crypt::decrypt($id);
        $order = Order::order_detail($id);
        $orders = Order::find($id);
        return view('order.order_show',compact('order','orders'));
    }

    public function order_view(Request $request, $id)
    {
        if (auth()->user()->isAbleTo('Show Order')) {
            try {
                $id = Crypt::decrypt($id);
                $order = Order::order_detail($id);
                $store_id = Store::where('id', getCurrentStore())->first();
                $order_notes = OrderNote::where('order_id',$id)->where('theme_id', $store_id->theme_id)->where('store_id', getCurrentStore())->get();
                $store = Store::where('id' ,getCurrentStore())->first();
                $deliveryboys = DeliveryBoy::where('theme_id', $store_id->theme_id)->where('store_id', getCurrentStore())->pluck('name', 'id')->prepend('Assign to deliveryboy',"");

                if (!empty($order['message'])) {
                    return redirect()->back()->with('error', __('Order Not Found.'));
                }
                return view('order.view', compact('order','store','order_notes','deliveryboys'));
            } catch (DecryptException $e) {
                return redirect()->back()->with('error', __('Something was wrong.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function applycoupon(Request $request,$slug)
    {
        $store = Store::where('slug',$slug)->first();
        $coupon = Coupon::where('coupon_code',$request->coupon_code)->where('theme_id',$request->theme_id)->where('store_id',$store->id)->first();
        $theme_id = $store->theme_id;

        $param = [
            'theme_id' => $theme_id,
            'slug' => $slug,
            'store_id' => $store->id,
            'coupon'    => $coupon,
        ];

        $request->merge($param);
        $api = new ApiController();

        $apply_coupon = $api->apply_coupon($request, $slug);
        $coupon = $apply_coupon->getData();
        return response()->json($coupon);
    }

    public function paymentlist(Request $request,$slug)
    {
        $store = Store::where('slug',$slug)->first();
        $theme_id = $store->theme_id;

        $param = [
            'theme_id' => $theme_id,
            'customer_id' => !empty(\Auth::guard('customers')->user()) ? \Auth::guard('customers')->user()->id : 0,
            'slug' => $slug,
            'store_id' => $store->id,
        ];
        $request->merge($param);
        $api = new ApiController();

        $payment_list_data = $api->payment_list($request);
        $payment_list = $payment_list_data->getData();
        $return['html_data'] = view('front_end.sections.payment_list', compact('slug','payment_list'))->render();
        return response()->json($return);
    }

    public function order_summary(Request $request, $slug)
    {
        $store = Store::where('slug',$slug)->first();
        $theme_id = $store->theme_id;
        $order_id = (Session::get('khalti_order_id'));

        if(session('data'))
        {
            $currentTheme = GetCurrenctTheme($slug);
            $currantLang = \Cookie::get('LANGUAGE') ?? $store->default_language;
            $data = getThemeSections($currentTheme,$slug, true, true);
            // Get Data from database
            $sqlData = getHomePageDatabaseSectionDataFromDatabase($data);
            $currency = Utility::GetValueByName('CURRENCY_NAME');
            $languages = Utility::languages();
            $section = (object) $data['section'];
            $order_id =  session('data');
            $orders_data = Order::where('product_order_id',$order_id)->where('store_id',$store->id)->where('theme_id',$theme_id)->first();
            // Order jason
            $order_complete_json_path = base_path('/theme_json/order-complete.json');
            $order_complete_json = json_decode(file_get_contents($order_complete_json_path), true);

            $order_complate_title = $order_complete_json['section']['title']['text'];
            $order_complate_description = $order_complete_json['section']['description']['text'];

            $setting_order_complete_json = AppSetting::select('theme_json')
            ->where('theme_id', $theme_id)
            ->where('page_name', 'order_complate')
            ->where('store_id', getCurrentStore())
            ->first();
            if (!empty($setting_order_complete_json)) {
                $order_complete_json_array_data = json_decode($setting_order_complete_json->theme_json, true);

                $order_complate_title = $order_complete_json_array_data['section']['title']['text'];
                $order_complate_description = $order_complete_json_array_data['section']['description']['text'];
            }
            $order_complete_json_array["order-complate"]["order-complate-title"] = $order_complate_title . ' #' . $order_id;
            $order_complete_json_array["order-complate"]["order-complate-description"] = $order_complate_description;

            return view('front_end.sections.pages.order-summary', compact('slug','store','order_id','orders_data','currentTheme','currantLang','currency','languages','section','order_complate_title','order_complate_description')+$sqlData+$data);

        }
        elseif((isset($request['data']['order_id']) && !empty($request['data']['order_id'])) || (isset($request->responce['data']['order_id']) && ($request->responce['data']['order_id'])) || (isset($order_id) && !empty($order_id)) )
        {
            if(!empty($request['data']['order_id']))
            {
                $order = $request['data']['order_id'];
            }elseif(!empty($request->responce['data']['order_id'])){
                $order = $request->responce['data']['order_id'];
            }else{
                $order_data = Order::where('product_order_id',$order_id)->first();
                $order = $order_data->id;
            }
            $order_data = Order::find($order);
            $currentTheme = GetCurrenctTheme($slug);
            $currantLang = \Cookie::get('LANGUAGE') ?? $store->default_language;
            $data = getThemeSections($currentTheme,$slug, true, true);
            $currency = Utility::GetValueByName('CURRENCY_NAME');
            $languages = Utility::languages();
            $section = (object) $data['section'];

            // Order jason
            $order_complete_json_path = base_path('/theme_json/order-complete.json');
            $order_complete_json = json_decode(file_get_contents($order_complete_json_path), true);

            $order_complate_title = $order_complete_json['section']['title']['text'];
            $order_complate_description = $order_complete_json['section']['description']['text'];

            $setting_order_complete_json = AppSetting::select('theme_json')
            ->where('theme_id', $theme_id)
            ->where('page_name', 'order_complate')
            ->where('store_id', getCurrentStore())
            ->first();
            if (!empty($setting_order_complete_json)) {
                $order_complete_json_array_data = json_decode($setting_order_complete_json->theme_json, true);

                $order_complate_title = $order_complete_json_array_data['section']['title']['text'];
                $order_complate_description = $order_complete_json_array_data['section']['description']['text'];
            }
            $order_complete_json_array["order-complate"]["order-complate-title"] = $order_complate_title . ' #' . $order;
            $order_complete_json_array["order-complate"]["order-complate-description"] = $order_complate_description;
            $MainCategoryList = MainCategory::where('status', 1)->where('theme_id', $currentTheme)->where('store_id',getCurrentStore())->get();
            return view('front_end.sections.pages.order-summary',compact('store','order_data','slug','currentTheme','currantLang','currency','languages','section','order_complate_description','order_complate_title','MainCategoryList'));
        }
        else{
            return redirect()->route('landing_page',$slug);
        }

    }

    public function shippinglabel(Request $request ,$id)
    {
        try{
            $id =crypt::decrypt($id);
            $order = Order::order_detail($id);
            $settings = Utility::Seting();
            $product = [];
            $products = [];
            foreach($order['product'] as $product_item){
                if($product_item['variant_id'] == '0')
                $product[] =Product::where('id' ,$product_item)->pluck('product_weight','id')->first();

                else{
                    $products[] =ProductVariant::where('id' ,$product_item['variant_id'])->pluck('weight' ,'id')->first();

                }
            }
            $newArray = [];

            foreach ($product as $key => $value) {
                $newArray[$key] = intval($value);
            }
            $product = array_merge($newArray ,$products);
            $product_sum =array_sum($product);



            if(!empty($order['message'])) {
                return redirect()->back()->with('error', __('Order Not Found.'));
            }
            return view('order.shippinglabel', compact('order','product_sum','settings'));

        } catch (DecryptException $e) {
            return redirect()->back()->with('error', __('Something was wrong.'));
        }
    }

    public function order_receipt($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $order = Order::order_detail($id);

            if(!empty($order['message'])) {
                return redirect()->back()->with('error', __('Order Not Found.'));
            }
            return view('order.receipt', compact('order'));

        } catch (DecryptException $e) {
            return redirect()->back()->with('error', __('Something was wrong.'));
        }
    }

    public function order_return(Request $request)
    {
        $data['product_id'] = $request->product_id;
        $data['variant_id'] = $request->variant_id;
        $data['order_id']   = $request->order_id;

        $responce = Order::product_return($data);
        if ($responce['status'] == 'success') {
            $return['status'] = 'success';
            $return['message'] = $responce['message'];
            return response()->json($return);
        } else {
            $return['status'] = 'error';
            $return['message'] = $responce['message'];
            return response()->json($return);
        }
    }

    public function order_return_request(Request $request)
    {
        $id = $request->id;
        $status = $request->status;

        $order = Order::find($id);
        if(!empty($order)) {
            if($status == 2) {
                $product_json = json_decode($order->product_json, true);
                foreach ($product_json as $key => $product) {
                    $product_id = $product['product_id'];
                    $variant_id = $product['variant_id'];
                    if( $variant_id == 0 || empty($variant_id) ) {
                        $product = Product::find($product_id);
                        if(!empty($product)) {
                            $product->product_stock += $product['qty'];
                            $product->save();
                        }
                    } else {
                        $productVariant = ProductVariant::where('product_id', $product_id)->where('id', $variant_id)->first();
                        if(!empty($productVariant)) {
                            $productVariant->stock += $product['qty'];
                            $productVariant->save();
                        }
                    }
                }
            }
            $order->return_price = $order->final_price;
            $order->return_status = $status;
            $order->save();
        }

        $return['message'] = __('Return request approve successfully');
        if($status == 3) {
            $return['message'] = __('Return request reject successfully');
        }
        return response()->json($return);
    }

    public function order_status_change(Request $request)
    {
        $data['order_id'] = $request->id;
        $data['order_status'] = $request->delivered;

        $responce = Order::order_status_change($data);
        $order = Order::order_detail($data['order_id']);
        $d_order = Order::find($data['order_id']);
        $store = Store::where('id', $d_order->store_id)->first();
        $dArr  = [
            'order_id' => $data['order_id'],
            'order_status' => $data['order_status']
        ];
        $owner= User::find($store->created_by);
        try
        {
            $order_id = Crypt::encrypt($data['order_id']);
            $resp  = Utility::sendEmailTemplate('Status Change', $order['delivery_informations']['email'], $dArr,$owner, $store, $order_id);
        }
        catch(\Exception $e)
        {
            $smtp_error = __('E-Mail has been not sent due to SMTP configuration');
        }
        OrderNote::order_note_data([
            'customer_id' => $d_order->customer_id,
            'order_id' => $request->id,
            'status' => 'Order status change',
            'changeble_status' => $request->delivered,
            'theme_id' => $store->theme_id,
            'store_id' => $d_order->store_id
        ]);
        try
        {
            $mobile_no =$order['delivery_informations']['phone'];
            $msg = __("Hello, Welcome to $store->name .Your Order is $request->delivered, !Hi $request->id, Thank you for Shopping");

            $resp  = Utility::SendMsgs('Status Change', $mobile_no, $msg);
        }
        catch(\Exception $e)
        {
            $smtp_error = __('Invalid OAuth access token - Cannot parse access token');
        }

        if ($responce['status'] == 'success') {
            $module = 'Status Change';
            $store = Store::find(getCurrentStore());
            $webhook =  Utility::webhook($module, $store->id);
            if ($webhook) {
                $order = Order::order_detail($request->id);
                $parameter = json_encode($order);

                // 1 parameter is  URL , 2 parameter is data , 3 parameter is method
                $status = Utility::WebhookCall($webhook['url'], $parameter, $webhook['method']);
                if ($status != true) {
                    $msgs  = 'Webhook call failed.';
                }
            }

            $return['status'] = 'success';
            $return['message'] = $responce['message']. ((isset($msgs)) ? '<br> <span class="text-danger">' . $msgs . '</span>' : '');
            return response()->json($return);
        } else {
            $return['status'] = 'error';
            $return['message'] = $responce['message'];
            return response()->json($return);
        }

    }

    public function order_payment_status(Request $request)
    {
        $order =Order::find($request->order_id);
        $order->payment_status =$request->payment_status;
        $order->save();
        $return['status'] = 'success';
        $return['message'] = 'Payment status has been changed.';
        return response()->json($return);
    }

    public function orderdetails($slug , $order_id)
    {
        try {

            $id = Crypt::decrypt($order_id);
            $order = Order::order_detail($id);
            $store = Store::where('slug', $slug)->first();
            $order_data =  Order::where('id' ,$id)->where('store_id',$store->id)->first();
            $currentTheme = $store->theme_id;
            Theme::set($currentTheme);
            $languages = Utility::languages();
            $data = getThemeSections($currentTheme, $slug, true, true);
            // Get Data from database
            $sqlData = getHomePageDatabaseSectionDataFromDatabase($data);
            $currency = Utility::GetValueByName('CURRENCY_NAME');
            $currantLang = \Cookie::get('LANGUAGE') ?? $store->default_language;
            $section = (object) $data['section'];
            $order_note = OrderNote::where('order_id', $order['id'])
            ->where('note_type', 'to_customer')
            ->where('theme_id', $currentTheme)
            ->get();

            if(!empty($order['message'])) {
                return redirect()->back()->with('error', __('Order Not Found.'));
            }

            return view('front_end.sections.order_detail', compact('slug','section','order','store','currentTheme','order_data','currency','currantLang','languages','order_note')+ $sqlData + $data);

        } catch (DecryptException $e) {
            return redirect()->back()->with('error', __('Something was wrong.'));
        }
    }

    public function status_cancel(Request $request ,$slug){

        $data['order_id'] = $request->order_id;
        $data['order_status'] = $request->order_status;

        $responce = Order::order_status_change($data);
        $order_detail = Order::order_detail($request->order_id);

        foreach ($order_detail['product'] as $item){
            if($item['variant_id'] == 0){
                $product =Product::where('id' ,$item['product_id'])->first();
                $prdduct_stock =$product->product_stock + $item['qty'];
                $product->product_stock  = $prdduct_stock ;
                $product->save();
            }
            else{
                $product =ProductVariant::where('id' ,$item['variant_id'])->first();
                $prdduct_stock =$product->stock + $item['qty'];
                $product->stock  = $prdduct_stock ;
                $product->save();
            }
        }
        if ($responce['status'] == 'success') {
            $return['status'] = 'success';
            $return['message'] = 'Order cancel successfully!';
            return response()->json($return);
        } else {
            $return['status'] = 'error';
            $return['message'] = $responce['message'];
            return response()->json($return);
        }

    }

    public function destroy(Order $order)
    {

        if (auth()->user()->isAbleTo('Delete Order')) {
            OrderTaxDetail::where('order_id', $order->id)->delete();
            OrderCouponDetail::where('order_id', $order->id)->delete();
            OrderBillingDetail::where('order_id', $order->id)->delete();
            Order::where('id', $order->id)->delete();
            return redirect()->back()->with('success', __('Order Delete succefully.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function updateStatus(Request $request,$id)
    {
        $order = Order::find($id);
        if($order->delivered_status == 0)
        {
            $order->delivered_status = 1;
            $order->delivery_date = Carbon::now();
        }
        $order->save();

        return response()->json([
            'delivered_status' => $order->delivered_status,
            'delivery_date' => $order->delivery_date,
            'message' => __('Order Status change successfully.')
        ]);
    }

    public function fileExport()
    {
        $fileName = 'Order.xlsx';
        return Excel::download(new OrderExport, $fileName);
    }

    public function additionalnote(Request $request,$slug)
    {
        $store = Store::where('slug',$slug)->first();
        $theme_id = $store->theme_id;

        $return['html_data'] = view('front_end.sections.additional_note', compact('slug'))->render();
        return response()->json($return);
    }

    public function order_assign(Request $request)
    {
        $order =Order::find($request->order_id);
        $order->deliveryboy_id =$request->delivery_boy;
        $order->save();
        $return['status'] = 'success';
        $return['message'] = 'Order assigned successfully';
        return response()->json($return);

    }

}
