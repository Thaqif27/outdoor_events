<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE events MODIFY COLUMN category VARCHAR(50) NULL DEFAULT 'running'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Assuming the previous state was an enum
        DB::statement("ALTER TABLE events MODIFY COLUMN category ENUM('running','cycling','obstacle') NOT NULL DEFAULT 'running'");
    }
};
