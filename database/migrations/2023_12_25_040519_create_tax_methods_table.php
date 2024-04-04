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
        Schema::create('tax_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('tax_rate')->default(0);
            $table->unsignedBigInteger('tax_id')->nullable();
            $table->unsignedBigInteger('country_id')->nullable();
            $table->unsignedBigInteger('state_id')->nullable();
            $table->unsignedBigInteger('city_id')->nullable();
            $table->string('priority')->default(0);
            $table->string('enable_shipping')->nullable();
            $table->string('theme_id');
            $table->integer('store_id');
            $table->timestamps();

            $table->foreign('tax_id')->references('id')->on('taxes')->onDelete('cascade');
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
            $table->foreign('state_id')->references('id')->on('states')->onDelete('cascade');
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tax_methods');
    }
};
