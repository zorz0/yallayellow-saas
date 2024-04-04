<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderRefundSetting extends Model
{
    protected $table = 'order_refund_settings';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'user_id',
        'is_active',
        'theme_id',
        'store_id',
    ];
}
