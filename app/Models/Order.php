<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_order_id',
        'order_date',
        'user_id',
        'is_guest',
        'product_json',
        'product_id',
        'product_price',
        'coupon_price',
        'delivery_price',
        'tax_price',
        'final_price',
        'return_price',
        'payment_comment',
        'payment_type',
        'payment_status',
        'delivery_id',
        'delivery_comment',
        'delivered_status',
        'delivery_date',
        'return_status',
        'return_date',
        'cancel_date',
        'reward_points',
        'additional_note',
        'deliveryboy_id',
        'store_id',
        'theme_id'
    ];

    protected $appends = [
        'demo_field',
        'delivered_status_string',
        'delivered_image',
        'order_id_string',
        'return_date',
        'delivery_date',
        'user_name'
    ];

    public function getDemoFieldAttribute()
    {
        return 'demo_field';
    }

    public function getDeliveredImageAttribute()
    {
        $return = '';
        if(!empty($this->delivery_id)) {
            $Shipping = Shipping::find($this->delivery_id);
            $return = !empty($Shipping->image_path) ? $Shipping->image_path : '';
        }
        return $return;
    }

    public function getDeliveredStatusStringAttribute()
    {
        $order = Order::find($this->id);
        $return = __('Pending');
        if(!empty($order)) {
            if($order->delivered_status == 0) {
                $return = __('Pending');
            }
            if($order->delivered_status == 1) {
                $return = __('Delivered');
            }
            if($order->delivered_status == 2) {
                $return = __('Cancel');
            }
            if($order->delivered_status == 4) {
                $return = __('Comfirmed');
            }
            if($order->delivered_status == 5) {
                $return = __('Picked Up');
            }
            if($order->delivered_status == 6) {
                $return = __('Shipped');
            }

            if($order->delivered_status == 3 && $order->return_status == 1) {
               $return = __('Return request processing');
            }
            if($order->delivered_status == 3 && $order->return_status == 2) {
                $return = __('Return');
            }
            if($order->delivered_status == 3 && $order->return_status == 3) {
                $return = __('Return cancelled');
            }
        }

        return $return;
    }

    public function getOrderIdStringAttribute() {
        return '#'.$this->attributes['product_order_id'];
    }

    public function getReturnDateAttribute() {
        $return = date('Y-m-d', strtotime($this->order_date));
        $Shipping = Shipping::find($this->delivery_id);
        if(!empty($Shipping)) {
            if(!empty($this->order_date)) {
                $return_date = $Shipping->return_order_dutation * 2;
                $return_date = date('Y-m-d', strtotime($this->delivery_date. ' + '.$return_date.' days'));
                if($return_date > date('Y-m-d')) {
                    $return = $return_date;
                }
            }

            if($this->delivered_status == 1 && !empty($this->delivery_date)) {
                $return_date = $Shipping->return_order_dutation;
                $return_date = date('Y-m-d', strtotime($this->delivery_date. ' + '.$return_date.' days'));
                if($return_date > date('Y-m-d')) {
                    $return = $return_date;
                }
            }
        }
        return $return;
    }

    // public function getDeliveryDateAttribute() {
    //     $return = date('Y-m-d', strtotime($this->order_date));
    //     $Shipping = Shipping::find($this->delivery_id);
    //     if(!empty($Shipping)) {
    //         $return_date = $Shipping->return_order_dutation;
    //         if(!empty($this->delivery_date)) {
    //             $return_date = date('Y-m-d', strtotime($this->delivery_date. ' + '.$return_date.' days'));
    //             if($return_date > date('Y-m-d')) {
    //                 $return = $return_date;
    //             }
    //         }
    //     }
    //     return $return;
    // }
    public function getDeliveryDateAttribute()
    {
        $return = null;
        $status = $this->attributes['delivered_status'];

        if ($status == '1') {
            $return =  $this->attributes['delivery_date'];
        }

        return $return;
    }
    public function getUserNameAttribute() {
        $return = $this->is_guest;
        if($return == '1')
        {
            $return = 'Guest';
        }else{
            $user = User::find($this->user_id);
            $return = !empty($user->first_name) ? $user->first_name : '';

        }
        return $return;
    }

    // **********************************************

    public static $discount_types = [
        '' => 'Select Option',
        'percentage' => 'Percentage',
        'flat' => 'Flat'
    ];

    public function UserData()
    {
        return $this->hasOne(User::class, 'id',
        'user_id');
    }

    public static function order_detail($order_id = 0)
    {
        $order = Order::find($order_id);
        // $theme_id = !empty($order) ? $order->theme_id : APP_THEME();

        if(!empty($order)) {
            $theme_id = !empty($order) ? $order->theme_id : APP_THEME();
            $store_id = !empty($order) ? $order->store_id : getCurrentStore();

            $order_array = [];
            $products = json_decode($order->product_json, true);

            if(!empty($products)) {
                foreach ($products as $key => $product) {


                    if($order->payment_type == 'POS')
                    {
                        $products[$key] = [
                            "product_id" => $product['product_id'],
                            "image" => $product["image"],
                            "name" => $product["name"],
                            "orignal_price" => $product["orignal_price"],
                            "total_orignal_price" => SetNumber($product["total_orignal_price"]),
                            "final_price" => SetNumber($product["final_price"]),
                            "quantity" => $product["quantity"],
                            "variant_id" => $product["variant_id"],
                            "variant_name" => $product["variant_name"],
                            "return" => $product["return"],
                        ];
                    }else{
                        $products[$key] = [
                            "product_id" => $product['product_id'],
                            "image" => $product["image"],
                            "name" => $product["name"],
                            "orignal_price" => $product["orignal_price"],
                            "total_orignal_price" => SetNumber($product["total_orignal_price"]),
                            "final_price" => SetNumber($product["final_price"]),
                            "qty" => $product["qty"],
                            "variant_id" => $product["variant_id"],
                            "variant_name" => $product["variant_name"],
                            "return" => $product["return"],

                        ];
                    }

                }
            }

            $order_array['id'] = $order->id;
            $order_array['is_guest'] = $order->is_guest;
            $order_array['order_id'] = '#'. $order->product_order_id;
            $order_array['delivery_date'] = $order->delivery_date;
            $order_array['order_reward_point'] = $order->reward_points;
            $order_array['return_price'] = $order->return_price;
            // $order_array['return_status'] = $order->return_status;
            $order_array['return_status_message'] = $order->return_status_message;
            $order_array['return_date'] = $order->return_date;
            $order_array['order_status_text'] = __('Pending');

            $order_array['order_status'] = $order->delivered_status;
            $order_array['order_status_message'] = '';
            $order_array['payment_status'] =$order->payment_status;
            $order_array['deliveryboy_id'] =$order->deliveryboy_id;

            if($order->payment_type == 'POS')
            {
                $order_array['tax_price'] = $order->tax_price;
                $order_array['coupon_price'] = $order->coupon_price;
            }

            if($order->delivered_status == 0) {
                $order_array['order_status_text'] = __('Pending');
            }
            if($order->delivered_status == 4) {
                $order_array['order_status_text'] = __('Comfirmed');
            }
            if($order->delivered_status == 5) {
                $order_array['order_status_text'] = __('Picked Up');
            }
            if($order->delivered_status == 6) {
                $order_array['order_status_text'] = __('Shipped');
            }
            if($order->delivered_status == 1) {
                $order_array['order_status_text'] = __('Delivered');
            }
            if($order->delivered_status == 2) {
                $order_array['order_status_text'] = __('Cancel');
                $order_array['order_status_message'] = __('Your order has been canced.');
            }
            if($order->delivered_status == 3 && $order->return_status == 1) {
                $order_array['order_status_text'] = __('Return request processing');
                $order_array['order_status_message'] = __('Your order return request has been sent.');
            }

            if($order->delivered_status == 3 && $order->return_status == 2) {
                $order_array['order_status_text'] = __('Return');
                $order_array['order_status_message'] = __('Your order has been returnd.');
            }

            if($order->delivered_status == 3 && $order->return_status == 3) {
                $order_array['order_status_text'] = __('Return cancelled');
                $order_array['order_status_message'] = __('Your order return request has been cancelled.');
                $order_array['order_tracking'] = '';
            }

            $order_array['product'] = $products;
            // 1 => review (hide) and 0 => no review (show)
            $order_array['is_review'] = 0;


            $OrderBillingDetail= OrderBillingDetail::where('order_id', $order_id)->first();
            $bi_f_name = !empty($OrderBillingDetail->first_name) ? $OrderBillingDetail->first_name : '';
            $bi_l_name = !empty($OrderBillingDetail->last_name) ? $OrderBillingDetail->last_name : '';
            $order_array['billing_informations']['name'] = $bi_f_name.' '.$bi_l_name;
            $order_array['billing_informations']['address'] = !empty($OrderBillingDetail->address) ? $OrderBillingDetail->address : '';
            $order_array['billing_informations']['state'] = !empty($OrderBillingDetail->BillingState->name) ? $OrderBillingDetail->BillingState->name : '';
            $order_array['billing_informations']['country'] = !empty($OrderBillingDetail->BillingCountry->name) ? $OrderBillingDetail->BillingCountry->name : '';
            // $order_array['billing_informations']['city'] = !empty($OrderBillingDetail->city) ? $OrderBillingDetail->city : '';
            $order_array['billing_informations']['city'] = !empty($OrderBillingDetail->BillingCity->name) ? $OrderBillingDetail->BillingCity->name : '';
            $order_array['billing_informations']['post_code'] = !empty($OrderBillingDetail->postcode) ? $OrderBillingDetail->postcode : '';
            $order_array['billing_informations']['email'] = !empty($OrderBillingDetail->email) ? $OrderBillingDetail->email : '';
            $order_array['billing_informations']['phone'] = !empty($OrderBillingDetail->telephone) ? $OrderBillingDetail->telephone : '';

            $order_array['delivery_informations']['name'] = $bi_f_name.' '.$bi_l_name;
            $order_array['delivery_informations']['address'] = !empty($OrderBillingDetail->delivery_address) ? $OrderBillingDetail->delivery_address : '';
            $order_array['delivery_informations']['state'] = !empty($OrderBillingDetail->DeliveryState->name) ? $OrderBillingDetail->DeliveryState->name : '';
            $order_array['delivery_informations']['country'] = !empty($OrderBillingDetail->DeliveryCountry->name) ? $OrderBillingDetail->DeliveryCountry->name : '';
            // $order_array['delivery_informations']['city'] = !empty($OrderBillingDetail->delivery_city) ? $OrderBillingDetail->delivery_city : '';
            $order_array['delivery_informations']['city'] = !empty($OrderBillingDetail->DeliveryCity->name) ? $OrderBillingDetail->DeliveryCity->name : '';
            $order_array['delivery_informations']['post_code'] = !empty($OrderBillingDetail->delivery_postcode) ? $OrderBillingDetail->delivery_postcode : '';
            $order_array['delivery_informations']['email'] = !empty($OrderBillingDetail->email) ? $OrderBillingDetail->email : '';
            $order_array['delivery_informations']['phone'] = !empty($OrderBillingDetail->telephone) ? $OrderBillingDetail->telephone : '';


            $payment_data = Utility::payment_data($order->payment_type , $theme_id ,$store_id);
            $order_array['paymnet_type'] = $payment_data['name'];
            $order_array['paymnet'] = $payment_data['image'];
            $order_array['delivery'] = $order->delivered_image;
            $order_array['delivered_charge'] = SetNumber($order->delivery_price);
            // Coupon
            $OrderCouponDetail = OrderCouponDetail::where('order_id', $order_id)->first();
            $order_array['coupon_info'] = null;
            if(!empty($OrderCouponDetail)) {
                $discount_string = '-'.$OrderCouponDetail->coupon_discount_amount;
                $CURRENCY = Utility::GetValueByName('CURRENCY',$theme_id , $store_id);
                $CURRENCY_NAME = Utility::GetValueByName('CURRENCY_NAME',$theme_id , $store_id);

                $discount = '-'.$OrderCouponDetail->coupon_discount_amount;
                $discount_string2 = '( '.$discount.' '.$CURRENCY_NAME.')';
                if($OrderCouponDetail->coupon_discount_type == 'flat') {
                    $discount_string .= $CURRENCY;
                } else {
                    $discount_string .= '%';
                    $dis_amt = $OrderCouponDetail->coupon_final_amount * $OrderCouponDetail->coupon_discount_amount / 100;
                    $discount_string2 = '( -'.$dis_amt.' '.$CURRENCY_NAME.')';
                }
                $discount_string .= ' '.__('for all products');
                $order_array['coupon_info']['status'] = true;
                $order_array['coupon_info']['message'] = "Coupon is valid.";
                $order_array['coupon_info']['code'] = $OrderCouponDetail->coupon_code;
                $order_array['coupon_info']['discount_string'] = $discount_string;
                $order_array['coupon_info']['price'] = SetNumber($OrderCouponDetail->coupon_final_amount);
                $order_array['coupon_info']['discount_string2'] = $discount_string2;
                // $order_array['coupon_info']['discount_amount'] = SetNumber($discount) ;
                // $order_array['coupon_info']['discount'] = SetNumber($OrderCouponDetail->coupon_discount_number);

                $order_array['coupon_info']['discount_amount'] = SetNumber($OrderCouponDetail->coupon_discount_number);
                $order_array['coupon_info']['discount'] = SetNumber($discount);
            }

            // Tax
            $OrderTaxDetail = OrderTaxDetail::where('order_id', $order_id)->get();
            $tax_price = 0;
            if(!empty($OrderTaxDetail)) {
                foreach ($OrderTaxDetail as $tax_key => $value) {
                    $order_array['tax'][$tax_key]['tax_string']  = $value->tax_string;
                    $tax_price = $value->tax_final_amount;
                }
                $order_array['tax']['amountstring']  = $tax_price;
            }
            $order_array['sub_total'] = SetNumber($order->product_price);
            $order_array['final_price'] = SetNumber($order->final_price);
            $order_array['tax_price'] = SetNumber($order->tax_price);
            return $order_array;
        } else {
            $return['message'] = 'Order not found.';
            return $return;
        }
    }

    public static function order_status_change($date = [])
    {
        $order_id = $date['order_id'];
        $order_status = $date['order_status'];

        $order = Order::find($order_id);
        if(!empty($order)) {
            if($order_status == 'cancel') {
                if($order->delivered_status != 0) {
                    $return['status'] = 'error';
                    $return['message'] = 'The order has been delivered. So you cannot cancel the order.';
                    return $return;
                }
                $order->cancel_date = now();
                $order->delivered_status = 2;
                $order->save();

                $return['status'] = 'success';
                $return['message'] = 'Order status changed.';
                return $return;
            }
            if($order_status == 'confirmed') {


                $order->confirmed_date = now();
                $order->delivered_status = 4;
                $order->save();

                $return['status'] = 'success';
                $return['message'] = 'Order status changed.';
                return $return;
            }
            if($order_status == 'pickedup') {


                $order->picked_date = now();
                $order->delivered_status = 5;
                $order->save();

                $return['status'] = 'success';
                $return['message'] = 'Order status changed.';
                return $return;
            }

            if($order_status == 'shipped') {


                $order->shipped_date = now();
                $order->delivered_status = 6;
                $order->save();

                $return['status'] = 'success';
                $return['message'] = 'Order status changed.';
                return $return;
            }
            if($order_status == 'return') {
                if($order->delivered_status == 0) {
                    $return['status'] = 'error';
                    $return['message'] = 'Your order has not been delivered. You can return the order after delivery.';
                    return $return;
                }

                $order->return_date = now();
                $order->delivered_status = 3;
                $order->return_status = 1;
                $order->save();

                $return['status'] = 'success';
                $return['message'] = 'Order status changed.';
                return $return;
            }

            if($order_status ==  'delivered')
            {
                $order->delivery_date = now();
                $order->delivered_status = 1;
                $order->save();

                $return['status'] = 'success';
                $return['message'] = 'Order status changed.';
                return $return;
            }
        } else {
            $return['status'] = 'error';
            $return['message'] = 'Order not found.';
            return $return;
        }
    }

    public static function product_return($date = [])
    {
        $product_id = $date['product_id'];
        $variant_id = $date['variant_id'];
        $order_id   = $date['order_id'];

        $Order = Order::find($order_id);
        if($Order->delivered_status != 1) {
            $return['status'] = 'error';
            $return['message'] = __('The product has not been delivered. Therefore you cannot return the product.');
            return $return;
        }

        if(!empty($Order->product_json)) {
            $product_json = json_decode($Order->product_json, true);
            foreach ($product_json as $key => $product) {
                if($product['product_id'] == $product_id && $product['variant_id'] == $variant_id) {
                    $product_json[$key]['return'] = 1;

                    if( $variant_id == 0 || empty($variant_id) ) {
                        $product = Product::find($product_id);
                        if(!empty($product)) {
                            $product->product_stock += $product['qty'];
                            $product->save();
                        }
                    } else {
                        $ProductStock = ProductVariant::where('product_id', $product_id)->where('id', $variant_id)->first();
                        if(!empty($ProductStock)) {
                            $ProductStock->stock += $product['qty'];
                            $ProductStock->save();
                        }
                    }
                }
            }
        }
        $Order->product_json = json_encode($product_json);
        $Order->save();

        $qty = 0;
        $return_price = 0;
        $rp = [];
        if(!empty($Order->product_json)) {
            $product_json = json_decode($Order->product_json, true);
            foreach ($product_json as $key => $product) {
                $qty += $product['qty'];
                if($product['return'] == 1) {
                    $rp[] = $product['final_price'];
                    $return_price += $product['final_price'];
                }
            }
        }

        $tax_price = 0;
        $tax_price_ind = 0;
        $order_taxes = OrderTaxDetail::where('order_id', $order_id)->get();
        if(!empty($order_taxes)) {
            foreach ($order_taxes as $key => $tax) {
                $tax_price_ind = $tax->tax_discount_amount;
                if($tax->tax_discount_type == 'percentage') {
                    $tax_price_ind = $return_price * $tax->tax_discount_amount / 100;
                }
                $tax_price += $tax_price_ind;
            }
        }
        $return_price += $tax_price;

        $coupon_price = 0;
        $order_coupon_detail = OrderCouponDetail::where('order_id', $order_id)->first();
        if(!empty($order_coupon_detail)) {
            $coupon_price = $order_coupon_detail->coupon_discount_number;
            if($order_coupon_detail->coupon_discount_type == 'percentage') {
                $coupon_price = $return_price * $order_coupon_detail->coupon_discount_number / 100;
            }
        }
        $return_price -= $coupon_price;

        $Order->product_json = json_encode($product_json);
        $Order->return_price = SetNumber($return_price);
        $Order->save();

        $return['status'] = 'success';
        $return['message'] = 'Product return successfully.';
        return $return;
    }

    public static function payment_status(){
        $payment_status = [
            'Paid' => 'Paid',
            'Unpaid' => 'Unpaid',
        ];
        return $payment_status;

    }

    public function DeliveryBoy()
    {
        return $this->hasOne(DeliveryBoy::class, 'id',
        'deliveryboy_id');
    }
}
