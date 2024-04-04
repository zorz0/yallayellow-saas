<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingZone extends Model
{
    use HasFactory;

    protected $fillable = [
        'zone_name', 'country_id', 'state_id', 'shipping_method', 'theme_id', 'store_id'
    ];

    public static function modules()
    {
        $shippingMethod = [
            'Flat Rate' => 'Flat Rate',
            'Free shipping' => 'Free shipping',
            'Local pickup' => 'Local pickup',
        ];
        return $shippingMethod;
    }

    public function getCountryNameAttribute()
    {
        return Country::where('id', $this->country_id)->first();
    }

    public function getStateNameAttribute()
    {
        return State::where('id', $this->state_id)->first();
    }

    public function getShippingMethod()
    {
        return ShippingMethod::where('id',$this->shipping_method)->first();
    }
}
