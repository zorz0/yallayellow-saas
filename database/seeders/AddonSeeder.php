<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Addon;

class AddonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $themes = [
            'grocery',
            'babycare'
        ];

        $addonQuery = Addon::query();

        foreach ($themes as $theme) {
            $exist = (clone $addonQuery)->where('theme_id', $theme)->first();
            if (!$exist) {
                (clone $addonQuery)->create([
                  'theme_id' => $theme,
                  'status' => 1
                ]);
            }
        }
    }
}
