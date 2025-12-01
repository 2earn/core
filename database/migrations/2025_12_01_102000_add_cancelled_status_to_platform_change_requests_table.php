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
        DB::statement("ALTER TABLE platform_change_requests MODIFY COLUMN status VARCHAR(255) DEFAULT 'pending' COMMENT 'pending, approved, rejected, cancelled'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE platform_change_requests MODIFY COLUMN status VARCHAR(255) DEFAULT 'pending' COMMENT 'pending, approved, rejected'");
    }
};

