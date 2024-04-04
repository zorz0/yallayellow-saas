<?php

namespace App\Models\Themes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThemeReviewSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'theme_id',
        'store_id',
        'section_name',
        'theme_json',
    ];

    public function getThemeJsonAttribute($value) {
        return json_decode($value);
    }
}
