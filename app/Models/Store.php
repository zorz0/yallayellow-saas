<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'theme_id',
        'slug',
        'default_language',
        'created_by',
        'is_active',
        'enable_pwa_store'
    ];

    public static function pwa_store($slug)
    {
        $store = Store::where('slug', $slug)->first();
        try {

            $pwa_data = \File::get(storage_path('uploads/customer_app/store_' . $store->id . '/manifest.json'));

            $pwa_data = json_decode($pwa_data);
        } catch (\Throwable $th) {
            $pwa_data = [];
        }
        return $pwa_data;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
