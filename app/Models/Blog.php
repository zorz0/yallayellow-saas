<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Qirolab\Theme\Theme;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'short_description', 'content', 'category_id', 'cover_image_url', 'cover_image_path', 'theme_id', 'store_id'
    ];

    public function category() {
        return $this->hasOne(BlogCategory::class, 'id', 'category_id');
    }

    public static function HomePageBlog($theme, $slug='',$no = 2)
    {
        $store_id = getCurrenctStoreId($slug);
        $landing_blogs = Blog::where('theme_id', $theme)->where('store_id',$store_id)->inRandomOrder()->limit($no)->get();
        $currentTheme = Theme::set($theme);
        return view('front_end.sections.homepage.blog_slider', compact('slug','landing_blogs','currentTheme'))->render();
    }

    public static function ArticlePageBlog($currentTheme, $slug=''){
        $store_id = getCurrenctStoreId($slug);
        $MainCategory =BlogCategory::where('theme_id', $currentTheme)->where('store_id', $store_id)->get()->pluck('name','id');
        $MainCategory->prepend('All','0');
        $blogs = Blog::where('theme_id', $currentTheme)->where('store_id', $store_id)->get();
        return view('front_end.sections.homepage_article', compact('MainCategory','blogs', 'currentTheme', 'slug'))->render();
    }
}
