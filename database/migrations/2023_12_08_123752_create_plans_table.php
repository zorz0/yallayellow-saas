<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->string('price')->default(0);
            $table->string('description')->nullable();
            $table->string('duration')->nullable();
            $table->text('themes');
            $table->string('max_stores')->default(0);
            $table->string('max_products')->default(0);
            $table->string('max_users')->default(0);
            $table->string('storage_limit');
            $table->string('enable_domain')->default('off');
            $table->string('enable_subdomain')->default('off');
            $table->string('enable_chatgpt')->default('off');
            $table->string('pwa_store')->default('off');
            $table->string('shipping_method')->default('off');
            $table->string('enable_tax')->default('off');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
