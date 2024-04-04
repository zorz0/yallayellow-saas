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
        $columnName = 'module';

        // Check if the column does not exist
        if (Schema::hasTable('roles') && !Schema::hasColumn('roles', 'module')) {
            Schema::table('roles', function (Blueprint $table) use ($columnName) {
                $table->string('module')->nullable()->default('Base')->after('guard_name');
            });
        }

        if (Schema::hasTable('permissions') && !Schema::hasColumn('permissions', 'module')) {
            Schema::table('permissions', function (Blueprint $table) use ($columnName) {
                $table->string('module')->nullable()->default('Base')->after('guard_name');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $columnName = 'module';
        if (Schema::hasColumn('roles', 'module')) {
            Schema::table('roles', function (Blueprint $table) use ($columnName) {
                // Drop the column from the table
                $table->dropColumn($columnName);
            });
        }

        if (Schema::hasColumn('permissions', 'module')) {
            Schema::table('permissions', function (Blueprint $table) use ($columnName) {
                // Drop the column from the table
                $table->dropColumn($columnName);
            });
        }
    }
};
