<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductLabel extends Model
{
    use HasFactory;

    protected $fillable = [
        'name','slug', 'status', 'store_id', 'theme_id', 'created_by'
    ];

    public static function slugs($data)
    {
        $slug = '';
        $slug = strtolower(str_replace(" ", "-",$data));
        $table = with(new ProductLabel)->getTable();
        $allSlugs = self::getRelatedSlugs($table, $slug ,$id = 0);

        if (!$allSlugs->contains('slug', $slug)) {
            return $slug;
        }
        for ($i = 1; $i <= 100; $i++) {
            $newSlug = $slug . '-' . $i;
            if (!$allSlugs->contains('slug', $newSlug)) {
                return $newSlug;

            }
        }
    }

    protected static function getRelatedSlugs($table, $slug, $id = 0)
    {
        return ProductLabel::select()->where('slug', 'like', $slug . '%')->where('id', '<>', $id)->get();
    }
    
    public function store()
    {
       return $this->belongsTo(Store::class, 'store_id');
    }

    public function user()
    {
       return $this->belongsTo(User::class, 'created_by');
    }
}
