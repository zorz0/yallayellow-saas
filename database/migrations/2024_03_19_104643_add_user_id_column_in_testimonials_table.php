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
        if (Schema::hasTable('testimonials') && !Schema::hasColumn('testimonials', 'user_id')) {
            Schema::table('testimonials', function (Blueprint $table) {
                $table->integer('user_id')->default(1)->after('store_id');
            });
        }
       
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('testimonials', function (Blueprint $table) {
            $table->dropColumn('user_id');
        });
    }
};
