<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopifyConection extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'theme_id',
        'module',
        'shopify_id',
        'original_id'
    ];
}
