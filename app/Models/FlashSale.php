<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlashSale extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'sale_product',
        'discount_amount',
        'discount_type',
        'is_active',
        'created_by',
        'theme_id',
        'store_id'
    ];

    public static $options =[
        'Shop', //0
        'Product', //1
        'Category', //2
        'Product price', //3
    ];

    public static $condition =[
        'is',   //0
        'is not', //1
    ];

    public static $price_condition =[
        'is equal to',  //0
        'is not equal to',  //1
        'is greater than',  //2
        'is less than',     //3
        'is greater or equal to',   //4
        'is less or equal to',      //5
    ];
}
