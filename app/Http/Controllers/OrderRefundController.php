<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderRefund;
use App\Models\OrderRefundSetting;
use App\Models\Plan;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class OrderRefundController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        // if(auth()->user()->isAbleTo('Manage Page'))
        // {
            $refund_requests = OrderRefund::where('theme_id', APP_THEME())->where('store_id', getCurrentStore())->get();
            return view('order.refund_request',compact('refund_requests'));
        // }
        // else
        // {
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $order = Order::order_detail($id);
            $refund_requests = OrderRefund::where('order_id', $order['id'])->first();

            $product_refund_id_json = $refund_requests["product_refund_id"];
            $product_refund_id_array = json_decode($product_refund_id_json, true);

            $productRefundData = []; // Initialize an array to store product_refund data

            foreach ($product_refund_id_array as $item) {
                if (isset($item['product_refund_id'])) {
                    $productRefundId = $item['product_refund_id'];
                    $quantity = $item['quantity'];
                    $returnPrice = $item['return_price'];

                    // Retrieve product data based on product_refund_id
                    $product = Product::find($productRefundId);

                    $productName = $product->name; // Product name by default

                    // Check if the product has a variant
                    if ($product->variant_product == '1') {
                        $product = ProductVariant::where('id',$product->id)->first();
                        $variantName = $product->variant; // Variant name if it's a variant product
                        $productName = "$productName - $variantName";
                    }

                    // Store the data in an associative array with product_refund_id as the key
                    $productRefundData[$productRefundId] = [
                        'product_name' => $productName,
                        'quantity' => $quantity,
                        'return_price' => $returnPrice,
                    ];
                }
            }

            $plan = Plan::find(Auth::user()->plan_id);
            $store = Store::find(auth::user()->current_store);
            $RefundStatus = OrderRefundSetting::where('theme_id', $store->theme_id)
                    ->where('store_id',$store->id)
                    ->pluck('is_active', 'name')->toArray();
            return view('order.refund_details', compact('order', 'refund_requests', 'RefundStatus', 'plan', 'productRefundData'));
        } catch (\Throwable $th) {
            dd($th);
            return redirect()->back()->with('error', __('Something Went wrong.'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    public function updateStatus(Request $request)
    {
        
        $post = $request->all();
        unset($post['_token']);
        $usr = Auth::user();
        if ($usr) {
            $id = $usr->id;
            foreach ($post as $key => $value) {
                $order_refunds = OrderRefundSetting::where('theme_id', APP_THEME())->where('store_id', getCurrentStore())->where('user_id',$usr->id)->where('name', $key)->first();
                if ($order_refunds) {
                    $order_refunds->is_active = $value;
                    $order_refunds->user_id = $usr->id;
                    $order_refunds->store_id = getCurrentStore();
                    $order_refunds->theme_id = APP_THEME();
                    $order_refunds->save();
                }else{
                    $status = OrderRefundSetting::updateOrCreate(
                        [
                            'name' => $key,
                            'user_id' => $id,
                            'is_active' => $value,
                            'theme_id' => APP_THEME(),
                            'store_id' => getCurrentStore(),
                        ],
                    );
                }
            }
            if (isset($status)) {
                return redirect()->back()->with('success', __('Refund Setting successfully updated!'));
            } else {
                return redirect()->back()->with('error', __('Something is wrong'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }

    }

    public function updateRefundStatus(Request $request)
    {
        $RefundId = $request->refund_id;

        $order = OrderRefund::findOrFail($RefundId);

        $order->update(['refund_status' => 'Accept']);

        return response()->json([
            'status' => $order->refund_status,
            'message' => 'Request accept successfully!'
        ]);
    }

    public function CancelRefundStatus(Request $request)
    {

        $order_refund = OrderRefund::findOrFail($request->refund_id);
        $order = Order::findOrFail($order_refund->order_id);

        $productJson = json_decode($order->product_json, true);
        $productRefunds = json_decode($order_refund->product_refund_id, true);

        $total_product_price = 0.0;
        foreach ($productJson as $key => &$product)
        {
            $quantitie = $productRefunds[$key]['quantity'];
            $old_qty = $product['qty'];

            $product['qty'] = $product['qty'] + $quantitie;

            if ($old_qty != 0) {
                $product['final_price'] = ($product['final_price'] * $product['qty']) / $old_qty;
            } else {
                $product['final_price'] = $request->subtotal;
            }

            $total_product_price = $total_product_price + $product['final_price'];
        }
        $grand_total_price = ($total_product_price + $order->delivery_price + $order->tax_price) - $order->coupon_price;
        $order->final_price = $grand_total_price;
        $order->product_price = $total_product_price;
        $order->product_json = json_encode($productJson);
        $order->save();

        $order_refund->update(['refund_status' => 'Cancel']);

        return response()->json([
            'status' => $order_refund->refund_status,
            'message' => 'Request cancel successfully!'
        ]);
    }

    public function RefundStock(Request $request)
    {
        $RefundId = $request->refundId;
        $order_refund = OrderRefund::findOrFail($RefundId);

        $product_refund_id_array = json_decode($order_refund->product_refund_id, true);
        foreach ($product_refund_id_array as $product_refund) {
            $product_id = $product_refund['product_refund_id'];
            $quantity = $product_refund['quantity'];

            $product = Product::find($product_id);

            if ($product->variant_product == 0) {
                $final_stock = $product->product_stock + $quantity;
                $product->product_stock = $final_stock;
                $product->save();
            } else {
                $variantProduct = ProductVariant::find($product->id);
                $newStock = $variantProduct->stock + $quantity;
                $variantProduct->stock = $newStock;
                $variantProduct->save();
            }
        }

        return response()->json([
            'message' => 'Manage Stock updated successfully!'
        ]);
    }

    public function updateFinalPrice(Request $request, $id)
    {
        $order = Order::find($id);
        $order_refund = OrderRefund::findorFail($request->refundId);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $refundAmount = $request->finalPrice;
        return response()->json([
            'refund_amount' => currency_format_with_sym( ($refundAmount ?? 0), getCurrentStore(), APP_THEME()) ?? SetNumberFormat($refundAmount),
            'message' => 'Add shipping price successfully!'
        ], 200);
    }

    public function RefundAmonut(Request $request, $id)
    {
        $order = Order::find($id);
        $order_refund = OrderRefund::findorFail($request->refundId);
        if($request->isTrendingChecked == 'true')
        {
            $order['delivery_price'] = 0.0;
            $order->save();
        }
        $amount = (float) preg_replace('/[^0-9.]/', '', $request->refund_amount);

        $order_refund->update([
            'refund_status' => 'Refunded',
            'refund_amount' => $amount
        ]);

        return response()->json([
            'message' => 'Refund Amount successfully!'
        ]);
    }
}
