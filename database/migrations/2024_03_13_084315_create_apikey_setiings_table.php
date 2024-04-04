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
        Schema::create('apikey_setiings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->longText('key')->nullable();
            $table->unsignedBigInteger('created_by')->nullable(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apikey_setiings');
    }
};
