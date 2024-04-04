<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'method_name', 'zone_id', 'cost', 'product_cost', 'calculation_type', 'shipping_requires', 'theme_id', 'store_id'
    ];


    public static function freeShipping()
    {
        $modules = [
            '1' => 'N/A',
            '2' => 'A valid free shipping coupon',
            '3' => 'A minimum order amount',
            '4' => 'A minimum order amount OR a coupon',
            '5' => 'A minimum order amount AND a coupon',
        ];
        return $modules;
    }
}
