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
        Schema::create('theme_video_section_drafts', function (Blueprint $table) {
            $table->id();
            $table->string('section_name')->nullable();
            $table->longText('theme_json')->nullable();
            $table->string('store_id')->index()->nullable();
            $table->string('theme_id')->index()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('theme_video_section_drafts');
    }
};
