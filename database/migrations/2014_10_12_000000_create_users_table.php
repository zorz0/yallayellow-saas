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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('profile_image')->nullable();
            $table->string('type')->default('admin');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('mobile')->nullable();
            $table->string('register_type')->default('email');
            $table->string('is_assign_store')->nullable();
            $table->string('current_store')->nullable();
            $table->string('language')->default('en');
            $table->string('default_language')->nullable();
            if (Schema::hasTable('plans')) {
                $table->unsignedBigInteger('plan_id')->nullable();
                $table->foreign('plan_id')->references('id')->on('plans')->onDelete('cascade');
            }
            else{
                $table->string('plan_id')->nullable();
            }
            $table->string('plan_expire_date')->nullable();
            $table->string('plan_is_active')->nullable();
            $table->string('requested_plan')->nullable();
            $table->string('storage_limit')->nullable();
            $table->string('is_active')->default('1');
            $table->string('created_by')->nullable();
            $table->string('theme_id')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
