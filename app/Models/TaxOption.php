<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxOption extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name', 'value', 'theme_id', 'created_by', 'store_id'
    ];
}
