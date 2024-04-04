<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'maincategory_id', 'image_url', 'image_path', 'icon_path', 'status', 'theme_id', 'store_id'
    ];

    protected $appends = ["icon_img_path","image_path_full_url","icon_path_full_url"];

    public function MainCategory()
    {
        return $this->hasOne(MainCategory::class, 'id', 'maincategory_id');
    }

    public function getIconImgPathAttribute($value)
    {
        $icon_path = 'themes/'.APP_THEME().'/upload/require/dot.png';        
        if(!empty($this->icon_path)) {
            $icon_path = $this->icon_path;
        }
        return $icon_path;
    }

    public function getImagePathFullUrlAttribute() {
        return get_file($this->image_path, $this->theme_id);
    }

    public function getIconPathFullUrlAttribute() {
        return get_file($this->icon_path, $this->theme_id);
    }
}
