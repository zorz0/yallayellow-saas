<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddOnManager extends Model
{
    use HasFactory;

    protected $fillable = [
        'module', 'name', 'monthly_price', 'yearly_price'
    ];
}
