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
        Schema::create('add_on_managers', function (Blueprint $table) {
            $table->id();
            $table->string('module')->nullable();
            $table->string('name')->nullable();
            $table->string('monthly_price')->nullable();
            $table->string('yearly_price')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('add_on_managers');
    }
};
