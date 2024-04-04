<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxMethod extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'tax_rate','tax_id', 'country_id', 'state_id', 'city_id', 'priority', 'enable_shipping'
    ];

    public function getCountryNameAttribute()
    {
        return Country::where('id', $this->country_id)->first();
    }

    public function getStateNameAttribute()
    {
        return State::where('id', $this->state_id)->first();
    }

    public function getCityNameAttribute()
    {
        return City::where('id', $this->city_id)->first();
    }
}
