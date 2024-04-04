<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApikeySetiings extends Model
{
    use HasFactory;

    protected $fillable = [
    	'key',
    	'created_by'
    ];
}
