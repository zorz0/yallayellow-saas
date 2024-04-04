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
        Schema::create('delivery_addresses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id')->default(0);
            $table->string('title')->nullable();
            $table->text('address');
            $table->unsignedBigInteger('country_id')->default('0');
            $table->unsignedBigInteger('state_id')->default('0');
            $table->unsignedBigInteger('city_id')->nullable();
            $table->integer('postcode')->nullable();
            $table->integer('default_address')->default(0)->comment('0 => no / 1 => yes');
            $table->string('theme_id');
            $table->integer('store_id');

            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
            $table->foreign('state_id')->references('id')->on('states')->onDelete('cascade');
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_addresses');
    }
};
