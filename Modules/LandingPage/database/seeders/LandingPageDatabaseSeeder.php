<?php

namespace Modules\LandingPage\database\seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Nwidart\Modules\Facades\Module;
use Modules\LandingPage\Entities\LandingPageSetting;

class LandingPageDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Model::unguard();

        $this->call(LandingPageDataTableSeeder::class);
        $this->call(PermissionTableSeeder::class);
    }
}
