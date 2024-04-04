<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductQuestion extends Model
{
    use HasFactory;

    protected $table    = 'product_questions';
    protected $fillable = [
        'question',
        'answers',
        'customer_id',
        'theme_id',
        'store_id',
        'product_id',
        'created_by'
    ];

    public function users(){
        return $this->hasone('App\Models\Customer'  ,'id' , 'customer_id');
    }
    public function product(){
        return $this->hasone('App\Models\Product'  ,'id' , 'product_id');
    }

    public function admin(){
        return $this->hasone('App\Models\User'  ,'id' , 'created_by');
    }
}
