<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'theme_id', 'store_id'
    ];

    public static function  Taxstatus(){
        $taxstatus =[
            'taxable' =>'Taxable',
            'shipping_only' =>'Shipping Only',
            'none' =>'None',

        ];

        return $taxstatus;
    }

    protected $appends = ["demo_field", "tax_string"];

    public function getDemoFieldAttribute()
    {
        return 'demo_field';
    }

    public function getTaxStringAttribute()
    {
        $type_percentage = ($this->tax_type == 'percentage') ? '%' : '';
        $type_flat = ($this->tax_type == 'flat') ? '-' : '';
        return $this->tax_name.' ('.$type_flat.$this->tax_amount.$type_percentage.')';
    }

    public static function TaxCount($data = [])
    {
        $store_id = !empty($data['store_id']) ? $data['store_id'] : 1;
        $theme_id = !empty($data['theme_id']) ? $data['theme_id'] : env('theme_id');
        $sub_total = !empty($data['sub_total']) ? $data['sub_total'] : 0;

        $Tax = Tax::where('theme_id', $theme_id)->where('store_id',$store_id)->first();
        $tax_price = 0;
        $tax_name = 0;
        $original_price = $sub_total;
        $cart_array = [];
        $cart_array['original_price'] = SetNumber(floatval($original_price));
        $cart_array['tax_info'] = [];
        if($Tax && count($Tax->tax_methods()) > 0) {
            foreach ($Tax->tax_methods() as $key1 => $value1) {
               
                $amount = $sub_total * $value1->tax_rate / 100;
                $cart_array['tax_info'][$key1]["tax_name"] = $value1->name;
                $cart_array['tax_info'][$key1]["tax_rate"] = $value1->tax_rate;
                $cart_array['tax_info'][$key1]["tax_amount"] = $amount;
                $cart_array['tax_info'][$key1]["id"] = $value1->id;
                $cart_array['tax_info'][$key1]["tax_price"] = SetNumber($amount);
                $tax_price += $amount;
                $tax_name = $value1->name;
            }
        }
       
        $CURRENCY_NAME = Utility::GetValueByName('CURRENCY_NAME',$theme_id);
        $CURRENCY= Utility::GetValueByName('CURRENCY',$theme_id);
        $cart_array['total_tax_price'] = SetNumber($tax_price);
        $total = $tax_price + $sub_total;
        $cart_array['final_price'] = SetNumber($total);
        $cart_array['currency_name'] = $CURRENCY_NAME;
        $cart_array['currency'] = $CURRENCY;
        $cart_array['tax_name'] = $tax_name;
        
        return $cart_array;
        
        
    }

    public function tax_methods() {
        $taxMethodQuery = TaxMethod::query();
            $topPrority = (clone $taxMethodQuery)->where('tax_id', $this->id)->orderBy('priority', 'ASC')->first();
            if ($topPrority) {
                $methods = (clone $taxMethodQuery)->where('tax_id', $this->id)->where('priority', $topPrority->priority)->get();
            }

        return $methods ?? [];
    }
}
