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
        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('maincategory_id')->default(0);
            $table->unsignedBigInteger('subcategory_id')->default(0);
            $table->unsignedBigInteger('product_id');
            $table->integer('rating_no')->default('0');
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->integer('status')->default('1')->comment('0 => inactive, 1 => active');
            $table->string('theme_id')->nullable();
            $table->integer('store_id');
            $table->timestamps();

            $table->foreign('maincategory_id')->references('id')->on('main_categories')->onDelete('cascade');
            $table->foreign('subcategory_id')->references('id')->on('sub_categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('testimonials');
    }
};
