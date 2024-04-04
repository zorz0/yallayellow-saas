<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Webhook extends Model
{
    use HasFactory;

    protected $fillable = [
        'module',
        'url',
        'method',
        'store_id',
        'theme_id',
    ];

    public static function modules()
    {
        $modules = [
            'New User' => 'New User',
            'New Product' => 'New Product',
            'New Order' => 'New Order',
            'Status Change' => 'Status Change',
        ];
        return $modules;
    }

    public static function methods()
    {
        $methods = [
            'GET' => 'GET',
            'POST' => 'POST'
        ];
        return $methods;
    }
}
