<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanOrder extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_id',
        'name',
        'card_number',
        'card_exp_month',
        'card_exp_year',
        'plan_name',
        'plan_id',
        'discount_price',
        'price',
        'price_currency',
        'txn_id',
        'payment_type',
        'payment_status',
        'receipt',
        'user_id',
        'store_id',
    ];
    public static function total_plan_price()
    {
        return PlanOrder::sum('price');
    }
    public static function total_orders()
    {
        return PlanOrder::count();
    }
    public function total_coupon_used()
    {

        return $this->hasOne('App\Models\PlanUserCoupon', 'order', 'order_id');
    }
}
