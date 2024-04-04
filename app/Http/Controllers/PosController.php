<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Utility;
use App\Models\Store;
use App\Models\Product;
use App\Models\Order;
use App\Models\PurchasedProducts;
use App\Models\DeliveryAddress;
use App\Models\{Tax, TaxMethod};
use App\Models\OrderTaxDetail;
use Illuminate\Http\Request;

class PosController extends Controller
{
    public function index()
    {
        // if(auth()->user()->isAbleTo('Manage Pos')){

            $settings = getAdminAllSetting();
            $Tax =  Tax::where('store_id', getCurrentStore())->where('theme_id', APP_THEME())->get();
            $taxMethodQuery = TaxMethod::query();
            foreach ($Tax as $value) {
                $topPrority = (clone $taxMethodQuery)->where('tax_id', $value->id)->orderBy('priority', 'ASC')->first();
                if ($topPrority) {
                    $methods = (clone $taxMethodQuery)->where('tax_id', $value->id)->where('priority', $topPrority->priority)->get();
                }

                $value->tax_methods = $methods ?? [];
            }

            $customers = Customer::where('store_id', auth()->user()->current_store)->get()->pluck('first_name', 'first_name');
            $customers->prepend('Walk-in-customer', '');
            return view('pos.index',compact('customers', 'Tax', 'settings'));
        // }
        // else{
        //     return redirect()->back()->with('error', 'Permission denied.');
        // }
    }

    public function create(Request $request)
    {
        // if(auth()->user()->isAbleTo('Create Pos')){

            $sess = session()->get('pos_'.getCurrentStore());
            if (isset($sess) && !empty($sess) && count($sess) > 0) {
                $user = auth()->user();
                $settings = getAdminAllSetting();
                if(!empty( $request->vc_name)){
                    $customer_detail = Customer::where('first_name',$request->vc_name)->where('store_id', $request->store_id)->first();
                    $customer = DeliveryAddress::where('id', '=', $customer_detail->id)->where('store_id', $request->store_id)->first();
                }
                else{
                    $customer = [];
                }
                $store = Store::where('id',getCurrentStore())->where('created_by',$user->creatorId())->first();
                $details = [
                    'pos_id' => time(),
                    'customer' => $customer != null ? $customer->toArray() : [],
                    'store' => $store != null ? $store->toArray() : [],
                    'user' => $user != null ? $user->toArray() : [],
                    'date' => date('Y-m-d'),
                    'pay' => 'show',
                ];
                if (!empty($details['customer']) || isset($customer_detail))
                {
                    $storedetails = '<h7 class="text-dark">' . ucfirst($details['store']['name'])  . '</p></h7>';

                   if(!empty($details['customer'])){
                        $customerdetails = '<h6 class="text-dark">' . ucfirst($customer_detail->name) . '</h6> <p class="m-0 h6 font-weight-normal">' . $customer_detail->phone . '</p>' . '<p class="m-0 h6 font-weight-normal">' .  $details['customer']['address'] . '</p>' . '<p class="m-0 h6 font-weight-normal">' . $details['customer']['city_name'] . '</p>' . '<p class="m-0 h6 font-weight-normal">' . $details['customer']['country_name'] . '</p>' . '<p class="m-0 h6 font-weight-normal">' . $details['customer']['postcode'] ?? '' . '</p>';

                        $shippdetails = '<h6 class="text-dark"><b>' . ucfirst($customer_detail->name) . '</h6> </b>' . '<p class="m-0 h6 font-weight-normal">' . $customer_detail->phone . '</p>' . '<p class="m-0 h6 font-weight-normal">' . $details['customer']['address'] . '</p>' . '<p class="m-0 h6 font-weight-normal">' . $details['customer']['city_name']  . '</p>' . '<p class="m-0 h6 font-weight-normal">' . $details['customer']['country_name'] . '</p>' . '<p class="m-0 h6 font-weight-normal">' . $details['customer']['postcode'] . '</p>';
                   }
                   else{
                        $customerdetails = '<h2 class="h6"><b>' . ucfirst($customer_detail->name) . '</b><h2>';
                        $shippdetails = '-';
                   }
                }
                else {
                    $customerdetails = '<h2 class="h6"><b>' . __('Walk-in Customer') . '</b><h2>';
                    $storedetails = '<h7 class="text-dark">' . ucfirst($details['store']['name'])  . '</p></h7>';
                    $shippdetails = '-';
                }

                $store['city'] = !empty($store->city) ? ", " . $store->city . "," : '';
                $store['country'] = !empty($store->country) ? ", " . $store->country . "," : '';

                $userdetails = '<h6 class="text-dark"><b>' . ucfirst($details['user']['name']) . ' </b><p class="m-0 font-weight-normal">' . $store->address . $store['city'] .'</p>' . '<p class="m-0 font-weight-normal">'.  $store->state . $store['country']  . '</p>' . '<p class="m-0 h6 font-weight-normal">' . $store->zipcode . '</p>';
                $details['customer']['details'] = $customerdetails;
                $details['store']['details'] = $storedetails;
                $details['customer']['shippdetails'] = $shippdetails;

                $details['user']['details'] = $userdetails;
                $mainsubtotal = 0;
                $sales        = [];
                //$store_id = Store::where('id', getCurrentStore())->first();
                foreach ($sess as $key => $value) {
                    $subtotal = $value['orignal_price'] * $value['quantity'];

                    $Tax =  Tax::where('store_id', getCurrentStore())->where('theme_id', APP_THEME())->first();
                    $tax_price = 0;
                    $product_tax='';
                    if($Tax && count($Tax->tax_methods()) > 0) {
                        foreach ($Tax->tax_methods() as $key1 => $value1) {

                            $amount = $subtotal * $value1->tax_rate / 100;
                            $cart_array['tax_info'][$key1]["tax_price"] = SetNumber($amount);
                            $tax_price += $amount;
                        }
                    }
                    $subtotal = $subtotal + $tax_price;

                    $sales['data'][$key]['name']       = $value['name'];
                    $sales['data'][$key]['quantity']   = $value['quantity'];
                    $sales['data'][$key]['orignal_price'] = currency_format_with_sym( $value['orignal_price'], $store->id, $store->theme_id) ?? SetNumberFormat($value['orignal_price']);
                    $sales['data'][$key]['tax']        = $tax_price;


                    $sales['data'][$key]['tax_amount'] = currency_format_with_sym( $tax_price, $store->id, $store->theme_id) ?? SetNumberFormat($tax_price);
                    $sales['data'][$key]['total_orignal_price']   = currency_format_with_sym( $value['total_orignal_price'], $store->id, $store->theme_id) ?? SetNumberFormat($value['total_orignal_price']);
                    $mainsubtotal                      += $value['total_orignal_price'];
                }
                // test
                if($request->discount <= $mainsubtotal){
                    $discount=!empty($request->discount)?$request->discount:0;
                }
                else{
                    $discount=$mainsubtotal;
                }
                $sales['discount'] = currency_format_with_sym( $discount, $store->id, $store->theme_id) ?? SetNumberFormat($discount);
                $total= $mainsubtotal-$discount;
                $sales['sub_total'] = currency_format_with_sym( $mainsubtotal, $store->id, $store->theme_id) ?? SetNumberFormat($mainsubtotal);
                $sales['total'] = currency_format_with_sym( $total, $store->id, $store->theme_id) ?? SetNumberFormat($total);

                return view('pos.create', compact('sales', 'details'));
            } else {
                return response()->json(
                    [
                        'error' => __('Add some products to cart!'),
                    ],
                    '404'
                );
            }
        // }
        // else{
        //     return redirect()->back()->with('error', 'Permission denied.');
        // }

    }

    public function store(Request $request)
    {
        if(auth()->user()->isAbleTo('Create Pos')){
            $discount=$request->discount;
            $price = floatval(str_replace(',', '', str_replace('$', '', $request->price)));
            $user_id = auth()->user()->creatorId();
            $theme_id = !empty($request->theme_id) ? $request->theme_id : APP_THEME();
            if(!empty( $request->vc_name)){
                $customer = Customer::where('first_name',$request->vc_name)->where('store_id', $request->store_id)->first();
                $cust_details = DeliveryAddress::where('id', '=', $customer->id)->where('store_id', $request->store_id)->first();
            }
            else{
                $cust_details = [];
            }
            $store = Store::where('id',getCurrentStore())->where('created_by',$user_id)->first();
            $sales            = session()->get('pos_'.getCurrentStore());
            if (isset($sales) && !empty($sales) && count($sales) > 0) {
                    foreach ($sales as $key => $value) {
                        // dd($value);
                        $product_id = $value['id'];
                        $original_quantity = ($value == null) ? 0 : (int)$value['originalquantity'];

                        $product_quantity = $original_quantity - $value['quantity'];
                        if ($value != null && !empty($value)) {
                            Product::where('id', $product_id)->update(['product_stock' => $product_quantity]);
                        }
                        $tax_amount = 0;
                        if(!empty($value['tax'])){
                            $tax_amount = $value['tax'];
                        }
                    }
                    $is_guest = 1;
                    if(auth()->check()) {
                        $product_order_id  = $request->customer_id . date('YmdHis');
                        $is_guest = 0;
                    }
                    $pos                  = new Order();
                    $pos->product_order_id = time();
                    $pos->order_date = date('Y-m-d H:i:s');
                    $pos->customer_id            = isset($customer->id) ? $customer->id : '0' ;
                    $pos->is_guest = $is_guest;
                    $pos->product_id = $product_id;
                    $pos->product_json = json_encode($sales);
                    $pos->final_price = $price;
                    $pos->product_price = $value['orignal_price'] * $value['quantity'];
                    $pos->coupon_price = (float)$discount;
                    $pos->delivery_price = '';
                    $pos->tax_price = $tax_amount;
                    $pos->payment_type = __('POS');
                    $pos->payment_status = 'Paid';
                    $pos->theme_id = $theme_id;
                    $pos->store_id = $store->id;
                    $pos->save();

                    //webhook
                    // $module = 'New Order';
                    // $webhook =  Utility::webhook($module, $store->id);
                    // if ($webhook) {
                    //     $parameter = json_encode($sales);
                    //     //
                    //     // 1 parameter is  URL , 2 parameter is data , 3 parameter is method
                    //     $status = Utility::WebhookCall($webhook['url'], $parameter, $webhook['method']);
                    //     if ($status != true) {
                    //         $msg  = 'Webhook call failed.';
                    //     }
                    // }
                    foreach ($sales as $product_id) {
                        $purchased_products = new PurchasedProducts();
                        $purchased_products->product_id = $product_id['id'];
                        $purchased_products->customer_id = isset($cust_details->id) ? $cust_details->id : '';
                        $purchased_products->order_id = $pos->id;
                        $purchased_products->theme_id = $theme_id;
                        $purchased_products->store_id = $store->id;

                        $purchased_products->save();
                    }


                    session()->forget('pos_'.getCurrentStore());


                    $msg = response()->json(
                        [
                            'status' => 'success',
                            'code' => 200,
                            'success' => __('Payment completed successfully!'),
                            'order_id' => \Crypt::encrypt($pos->id),
                        ]
                    );

                    return $msg;

            } else {
                return response()->json(
                    [
                        'code' => 404,
                        'success' => __('Items not found!'),
                    ]
                );
            }
        }
        else{
            return redirect()->back()->with('error', 'Permission denied.');
        }

    }

    public function printView(Request $request)
    {
        $sess = session()->get('pos_'.getCurrentStore());

        $user = auth()->user();
        $store = Store::where('id',getCurrentStore())->where('created_by',$user->creatorId())->first();
        $settings = getAdminAllSetting();

        if(!empty( $request->vc_name)){
            $customer_detail = Customer::where('first_name',$request->vc_name)->where('store_id', $request->store_id)->first();
            $customer = DeliveryAddress::where('id', '=', $customer_detail->id)->where('store_id', $request->store_id)->first();
        }
        else{
            $customer_detail = '';
            $customer = [];
        }

        $details = [
            'pos_id' => time(),
            'customer' => $customer != null ? $customer->toArray() : [],
            'store' => $store != null ? $store->toArray() : [],
            'user' => $user != null ? $user->toArray() : [],
            'date' => date('Y-m-d'),
            'pay' => 'show',
        ];
        if (!empty($details['customer']) || !empty($customer_detail))
        {
            $storedetails = '<h7 class="text-dark">' . ucfirst($details['store']['name'])  . '</p></h7>';

            if(!empty($details['customer'])){
                $customerdetails = '<h6 class="text-dark">' . ucfirst($customer_detail->name) . '</h6> <p class="m-0 h6 font-weight-normal">' . $customer_detail->phone . '</p>' . '<p class="m-0 h6 font-weight-normal">' .  $details['customer']['address'] . '</p>' . '<p class="m-0 h6 font-weight-normal">' . $details['customer']['city_name'] . '</p>' . '<p class="m-0 h6 font-weight-normal">' . $details['customer']['country_name'] . '</p>' . '<p class="m-0 h6 font-weight-normal">' . $details['customer']['postcode'] ?? '' . '</p>';

                $shippdetails = '<h6 class="text-dark"><b>' . ucfirst($customer_detail->name) . '</h6> </b>' . '<p class="m-0 h6 font-weight-normal">' . $customer_detail->phone . '</p>' . '<p class="m-0 h6 font-weight-normal">' . $details['customer']['address'] . '</p>' . '<p class="m-0 h6 font-weight-normal">' . $details['customer']['city_name']  . '</p>' . '<p class="m-0 h6 font-weight-normal">' . $details['customer']['country_name'] . '</p>' . '<p class="m-0 h6 font-weight-normal">' . $details['customer']['postcode'] . '</p>';
            }
            else{
                $customerdetails = '<h2 class="h6"><b>' . ucfirst($customer_detail->name) . '</b><h2>';
                $shippdetails = '-';
            }
        }
        else {
            $customerdetails = '<h2 class="h6"><b>' . __('Walk-in Customer') . '</b><h2>';
            $storedetails = '<h7 class="text-dark">' . ucfirst($details['store']['name'])  . '</p></h7>';
            $shippdetails = '-';

        }


        $store['city'] = !empty($store->city) ? ", " . $store->city . "," : '';
        $store['country'] = !empty($store->country) ? ", " . $store->country . "," : '';

        $userdetails = '<h6 class="text-dark"><p class="m-0 font-weight-normal">' . $store->address . $store['city'] .'</p>' . '<p class="m-0 font-weight-normal">'.  $store->state . $store['country']  . '</p>' . '<p class="m-0 h6 font-weight-normal">' . $store->zipcode . '</p>';

        $details['customer']['details'] = $customerdetails;
        $details['store']['details'] = $storedetails;

        $details['customer']['shippdetails'] = $shippdetails;

        $details['user']['details'] = $userdetails;
        $mainsubtotal = 0;
        $sales        = [];

        if(!empty($sess))
        {
            foreach ($sess as $key => $value) {
                $subtotal = $value['orignal_price'] * $value['quantity'];

                $Tax = Tax::where('store_id', $store->id)->where('theme_id', $store->theme_id)->first();
                $tax_price = 0;
                $product_tax='';
                if($Tax && count($Tax->tax_methods()) > 0) {
                    foreach ($Tax->tax_methods() as $key1 => $value1) {

                        $amount = $subtotal * $value1->tax_rate / 100;
                        $cart_array['tax_info'][$key1]["tax_price"] = SetNumber($amount);
                        $tax_price += $amount;
                    }
                }
                $subtotal = $subtotal + $tax_price;

                $sales['data'][$key]['name']       = $value['name'];
                $sales['data'][$key]['quantity']   = $value['quantity'];
                $sales['data'][$key]['orignal_price'] = currency_format_with_sym( $value['orignal_price'], $store->id, $store->theme_id) ?? SetNumberFormat($value['orignal_price']);
                $sales['data'][$key]['tax']        = $tax_price;

                $sales['data'][$key]['tax_amount'] = currency_format_with_sym( $tax_price, $store->id, $store->theme_id) ?? SetNumberFormat($tax_price);
                $sales['data'][$key]['total_orignal_price']   = currency_format_with_sym( $value['total_orignal_price'], $store->id, $store->theme_id) ?? SetNumberFormat($value['total_orignal_price']);
                $mainsubtotal                      += $value['total_orignal_price'];
            }

            if($request->discount <= $mainsubtotal){
                $discount=!empty($request->discount)?$request->discount:0;
            }
            else{
                $discount=$mainsubtotal;
            }
            $sales['discount'] = currency_format_with_sym( $discount, $store->id, $store->theme_id) ?? SetNumberFormat($discount);
            $total= $mainsubtotal-$discount;
            $sales['sub_total'] = currency_format_with_sym( $mainsubtotal, $store->id, $store->theme_id) ?? SetNumberFormat($mainsubtotal);
            $sales['total'] = currency_format_with_sym( $total, $store->id, $store->theme_id) ?? SetNumberFormat($total);
        }else{
            return redirect()->back()->with('error', __('Cart is empty!'));
        }
        session()->forget('pos_'.getCurrentStore());
        return view('pos.printview', compact('details', 'sales', 'customer','customer_detail'));

    }

    public function cartDiscount(Request $request)
    {
        if($request->discount){
            $sess = session()->get('pos_'.getCurrentStore());
            $subtotal = !empty($sess)?array_sum(array_column($sess, 'total_orignal_price')):0;
            $discount = $request->discount;
            $total = $subtotal - $discount;
            $total = currency_format_with_sym( $total, auth()->user()->current_store, APP_THEME()) ?? SetNumberFormat($total);

        }else{
            $sess = session()->get('pos_'.getCurrentStore());
            $subtotal = !empty($sess)?array_sum(array_column($sess, 'total_orignal_price')):0;
            $discount = 0;
            $total = $subtotal - $discount;
            $total = currency_format_with_sym( $total, auth()->user()->current_store, APP_THEME()) ?? SetNumberFormat($total);
        }

        return response()->json(['total' => $total], '200');

    }
}
