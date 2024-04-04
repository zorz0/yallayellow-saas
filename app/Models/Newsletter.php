<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Newsletter extends Model
{
    use HasFactory;

    public static function Subscribe($currentTheme, $slug='', $section)
    {
        return view('front_end.sections.pages.subscribe_form', compact('currentTheme','slug', 'section'))->render();
    }
}
