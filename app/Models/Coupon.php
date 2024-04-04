<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'coupon_name', 'coupon_code', 'coupon_type', 'coupon_limit', 'coupon_expiry_date', 'discount_amount', 'status','free_shipping_coupon', 'theme_id'
    ];

    public function coupon_uses()
    {
        return UserCoupon::where('coupon_id', $this->id)->count();
    }

    public function UsesCouponCount()
    {
        return $this->hasMany(UserCoupon::class, 'coupon_id', 'id')->count();
    }

    public function PerUsesCouponCount()
    {
        $coupon_user = UserCoupon::where('coupon_id' ,$this->id )->get();
        $user_email =[];
        foreach($coupon_user as $coupon){
            $user_email[] = OrderBillingDetail::where('order_id' ,$coupon->order_id)->pluck('email')->first();

        }
        return $user_email;
    }
}
