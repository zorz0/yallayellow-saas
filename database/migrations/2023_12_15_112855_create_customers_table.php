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
        if (!Schema::hasTable('customers')) {
            Schema::create('customers', function (Blueprint $table) {
                $table->id();
                $table->string('first_name')->nullable();
                $table->string('last_name')->nullable();
                $table->string('email');
                $table->string('profile_image')->nullable();
                $table->string('type')->default('cutsomer');
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password')->nullable();
                $table->string('mobile')->nullable();
    
                $table->date('regiester_date')->nullable();
                $table->date('last_active')->nullable();
                $table->integer('status')->default(0)->comment('0 => on, 1 => off ');
    
                $table->date('date_of_birth')->nullable();
    
                $table->text('firebase_token')->nullable();
                $table->string('device_type')->nullable();
                $table->string('register_type')->nullable();
                $table->string('google_id')->nullable();
                $table->string('apple_id')->nullable();
                $table->string('facebook_id')->nullable();
    
                $table->integer('password_otp')->nullable();
                $table->dateTime('password_otp_datetime')->nullable();
    
                $table->integer('created_by')->nullable();
                $table->string('theme_id')->nullable();
                $table->integer('store_id')->nullable();
                $table->rememberToken();
                $table->timestamps();
            });
        } else {
            Schema::table('customers', function (Blueprint $table) {
                $table->string('first_name')->nullable();
                $table->string('last_name')->nullable();
                $table->string('email');
                $table->string('profile_image')->nullable();
                $table->string('type')->default('cutsomer');
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password')->nullable();
                $table->string('mobile')->nullable();

                $table->date('regiester_date')->nullable();
                $table->date('last_active')->nullable();
                $table->integer('status')->default(0)->comment('0 => on, 1 => off ');

                $table->date('date_of_birth')->nullable();

                $table->text('firebase_token')->nullable();
                $table->string('device_type')->nullable();
                $table->string('register_type')->nullable();
                $table->string('google_id')->nullable();
                $table->string('apple_id')->nullable();
                $table->string('facebook_id')->nullable();

                $table->integer('password_otp')->nullable();
                $table->dateTime('password_otp_datetime')->nullable();

                $table->integer('created_by')->nullable();
                $table->string('theme_id')->nullable();
                $table->integer('store_id')->nullable();
                $table->rememberToken();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
