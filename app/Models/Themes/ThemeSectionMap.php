<?php

namespace App\Models\Themes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThemeSectionMap extends Model
{
    use HasFactory;

    protected $fillable = [
        'theme_id',
        'store_id',
        'is_publish',
    ];
}
