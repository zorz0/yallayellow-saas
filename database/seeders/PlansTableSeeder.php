<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Plan;


class PlansTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $plan = Plan::get();
        if(count($plan) <= 0)
        {
            Plan::create(
                [
                    'name' => 'Free Plan',
                    'price' => 0,
                    'duration' => 'Unlimited',
                    'max_stores' => 2,
                    'max_products' => 5,
                    'max_users' => 5,
                    'storage_limit' => 100,
                    'enable_domain' => 'off',
                    'enable_subdomain' => 'off',
                    'enable_chatgpt' => 'off',
                    'pwa_store' => 'off',
                    'shipping_method' => 'off',
                    'themes' => 'grocery,babycare',
                    'description' => 'For companies that need a robust full-featured time tracker.',
                ]
            );
        }
    }
}
