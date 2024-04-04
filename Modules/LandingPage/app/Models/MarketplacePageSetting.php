<?php

namespace Modules\LandingPage\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\LandingPage\Database\factories\MarketplacePageSettingFactory;

class MarketplacePageSetting extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];
    
    protected static function newFactory(): MarketplacePageSettingFactory
    {
        //return MarketplacePageSettingFactory::new();
    }
}
