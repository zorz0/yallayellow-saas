<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderRefund extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'refund_status',
        'refund_reason',
        'custom_refund_reason',
        'attachments',
        'product_refund_id',
        'product_refund_price',
        'refund_amount',
        'store_id',
        'theme_id',
    ];

    public static function RefundReason()
    {
        $RefundReason = [
            'The Customer Bought the Wrong Item.' => 'The Customer Bought the Wrong Item.',
            'The Product is No Longer Needed.' => 'The Product is No Longer Needed.',
            'The Product Didn`t Match the Description.' => 'The Product Didn`t Match the Description.',
            'The Product Was Damaged Upon Arrival.' => 'The Product Was Damaged Upon Arrival.',
            'The Merchant Shipped the Wrong Item.' => 'The Merchant Shipped the Wrong Item.',
            'Other' => 'Other',
        ];
        return $RefundReason;
    }
}
