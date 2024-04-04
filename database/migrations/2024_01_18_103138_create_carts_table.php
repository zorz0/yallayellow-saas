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
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->integer('customer_id')->default(0)->nullable();
            $table->integer('product_id')->default(0);
            $table->integer('variant_id')->default(0);
            $table->integer('qty')->default(0);
            $table->integer('price')->default(0);
            $table->string('theme_id')->default(' ');
            $table->integer('store_id');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
