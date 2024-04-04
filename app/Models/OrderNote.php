<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderNote extends Model
{
    use HasFactory;

    public static function order_note_data($data= [])
    {
        $ordernote = new OrderNote();
        $ordernote->order_id = $data['order_id'];
        $ordernote->customer_id = !empty($data['customer_id']) ? $data['customer_id']: null;
        $ordernote->status = $data['status'];
        if($data['status'] == 'Order Created')
        {
            $ordernote->notes = 'Successfully created order of ' . $data['product_order_id'] . '. Order status changed to ' . $data['delivery_status'] . '.
            Payment is to be made upon delivery. ';
        }elseif($data['status'] == 'Stock Manage')
        {
            if($data['variant_product'] == 0)
            {
                $ordernote->notes = 'Stock levels reduced: The stock of ' . $data['product_name'] . ' is below the specified stock. Current Stock: ' . $data['product_stock'] . '. ';
            }else{
                $ordernote->notes = 'Stock levels reduced: The stock of ' . $data['product_name'] . '-' . $data['product_variant_name'] . ' is below the specified stock. Current Stock: ' . $data['product_stock'] . '. ';
            }

        }elseif($data['status'] == 'Order status change')
        {
            $ordernote->notes = 'Order status changed to '.$data['changeble_status'].'.';
        }
        $ordernote->theme_id = $data['theme_id'];
        $ordernote->store_id = $data['store_id'];
        $ordernote->save();
    }


    public static function order_entry($status= '')
    {
        if($status == 'Order Created')
        {
            $message = 'Payment to be made upon delivery. Order status changed from Pending payment to Processing.';
        }elseif($status == 'Stock Manage')
        {
            $message = 'Stock levels reduced:';
        }elseif($status == 'Order status change'){
            $message = 'Order status changed from ';
        }
        return $message;

    }
}
