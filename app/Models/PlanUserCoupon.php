<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanUserCoupon extends Model
{
    use HasFactory;
    protected $fillable = [
        'user',
        'coupon_id',
    ];

    public function userDetail()
    {
        return $this->hasOne('App\Models\User', 'id', 'user');
    }

    public function coupon_detail()
    {
        return $this->hasOne('App\Models\PlanCoupon', 'id', 'coupon_id');
    }

}
