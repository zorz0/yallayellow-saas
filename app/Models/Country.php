<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\State;

class Country extends Model
{
    use HasFactory;

    protected $fillable = [
        'id','name'
    ];

    public function states()
    {
        return $this->hasMany(State::class, 'country_id');
    }
}
