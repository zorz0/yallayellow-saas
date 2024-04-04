<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PixelFields extends Model
{
    use HasFactory;

    protected $fillable = [
        'platform', 'pixel_id' ,'store_id', 'theme_id'
    ];
}
