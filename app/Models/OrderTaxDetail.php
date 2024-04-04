<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderTaxDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_id',
        'product_order_id',
        'tax_id',
        'tax_name',
        'tax_discount_amount',
        'tax_final_amount',
        'theme_id'
    ];

    protected $appends = ["demo_field", "tax_string"];

    public function getDemoFieldAttribute()
    {
        return 'demo_field';
    }

    public function getTaxStringAttribute()
    {
        $type_percentage = ($this->tax_discount_type == 'percentage') ? '%' : '';
        $type_flat = ($this->tax_type == 'flat') ? '-' : '';
        return $this->tax_name.' ('.$type_flat.$this->tax_discount_amount.$type_percentage.')';
    }
}
