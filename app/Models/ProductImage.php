<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'image_path',
        'image_url',
        'theme_id'
    ];

    protected $appends = ["demo_field","image_path_full_url"];
    protected $hidden = ["image_url"];
    
    public function getDemoFieldAttribute()
    {
        return 'demo field';
    }

    public function getImagePathFullUrlAttribute() {
        return get_file($this->image_path,$this->theme_id);
    }

}
