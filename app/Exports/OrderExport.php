<?php

  namespace App\Exports;

use App\Models\Order;
use App\Models\Product;
use App\Models\Store;
use App\Models\Customer;
use DB;

  use Maatwebsite\Excel\Concerns\FromCollection;

  use Maatwebsite\Excel\Concerns\WithHeadings;



class OrderExport implements FromCollection, WithHeadings {




   public function headings(): array {




    // according to users table




    return [

        "Order Id",

        "Order Date",

        "User Name",

        "product Name",

        "Product Price",

        "Coupon Price",

        "Delivery Price",

        "Tax Price",

        "Final Price",

        "Return Price",

        "Payment Type",

        "Payment status",

        "Delivered status",

        "Delivered Date",

        "Return status",

        "Cancel Date",

        "Reward Points",
       ];

    }




   public function collection(){
    $store = Store::where('id', \Auth::user()->current_store)->first();
    $data = Order::where('store_id', $store->id)->where('theme_id',APP_THEME())->get();

    foreach($data as $k => $order){
        $order->setHidden(['demo_field', 'delivered_status_string', 'delivered_image','order_id_string','return_date','
        delivery_date','user_name']);
        unset($order->id,$order->is_guest	,$order->product_json	,$order->payment_comment,$order->delivery_comment,$order->theme_id,$order->store_id ,$order->delivery_id	,$order->created_at,$order->updated_at);
        $products=Product::find($order->product_id);
        $product_id=isset($products)?$products->name:'';
        $customer=Customer::find($order->customer_id);
        $customer_id=isset($customer)?$customer->name:'';

         $data[$k]["product_id"]=$product_id;
         $data[$k]["customer_id"]=$customer_id;

    }


       return collect($data);

   }

}
