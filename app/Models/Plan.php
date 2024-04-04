<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'price',
        'duration',
        'max_stores',
        'max_products',
        'max_users',
        'storage_limit',
        'enable_domain',
        'enable_subdomain',
        'enable_chatgpt',
        'pwa_store',
        'shipping_method',
        'themes',
        'enable_tax',
        'description',
        'trial',
        'trial_days',
    ];

    public static $arrDuration = [
        'Unlimited' => 'Unlimited',
        'Month' => 'Per Month',
        'Year' => 'Per Year',
    ];

    public function status()
    {
        return [
            __('Unlimited'),
            __('Per Month'),
            __('Per Year'),
        ];
    }

    public function getThemes(){
        if(!empty($this->themes)){
            return explode(',',$this->themes);
        }else{
            return [];
        }
    }

    public static function total_plan()
    {
        return Plan::count();
    }

    public static function most_purchese_plan()
    {
        $plan_order = PlanOrder::pluck('plan_id','id')->toarray();
        $order= array_count_values($plan_order);
        $most_name = '';
        if(!empty($order))
        {
            $max_value = max($order);
            $plan_id = array_search($max_value, $order);
            $plan_orders = Plan::find($plan_id);
            $most_name = $plan_orders->name;

        }
        return $most_name;
    }
}
