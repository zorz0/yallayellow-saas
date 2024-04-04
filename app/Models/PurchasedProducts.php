<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchasedProducts extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'product_id',
        'order_id',
        'theme_id',
        'store_id',
    ];
}
