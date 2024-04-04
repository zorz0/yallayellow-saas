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
        Schema::create('shipping_methods', function (Blueprint $table) {
            $table->id();
            $table->string('method_name');
            $table->unsignedBigInteger('zone_id');
            $table->string('cost')->default(0);
            $table->longText('product_cost')->nullable();
            $table->string('calculation_type')->nullable();
            $table->string('shipping_requires')->nullable();
            $table->string('theme_id');
            $table->integer('store_id');
            $table->timestamps();

            $table->foreign('zone_id')->references('id')->on('shipping_zones')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_methods');
    }
};
