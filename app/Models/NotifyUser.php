<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotifyUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id', 'user_id', 'product_id'
    ];

    public function store()
    {
       return $this->hasOne(Store::class, 'store_id');
    }

    public function user()
    {
       return $this->hasOne(User::class, 'user_id');
    }

    public function product()
    {
       return $this->hasOne(Product::class, 'product_id');
    }
}
