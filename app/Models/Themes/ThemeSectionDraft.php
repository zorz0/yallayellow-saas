<?php

namespace App\Models\Themes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThemeSectionDraft extends Model
{
    use HasFactory;

    protected $fillable = [
        'theme_id',
        'store_id',
        'section_id',
        'section_name',
        'order',
        'is_hide',
        'class_name'
    ];
}
