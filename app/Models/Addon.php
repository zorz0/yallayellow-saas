<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddOn extends Model
{
    protected $table = 'addons'; // Corrected table name
    use HasFactory;
    protected $fillable = [
        'module', 'name', 'monthly_price', 'yearly_price'
    ];
}
