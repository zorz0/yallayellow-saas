<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsappMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'from',
        'user_id',
        'is_active',
        'theme_id',
        'store_id',
        'created_by',
    ];
}
