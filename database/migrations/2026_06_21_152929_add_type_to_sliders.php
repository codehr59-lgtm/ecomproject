<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE sliders ADD COLUMN type VARCHAR DEFAULT 'slider'");
    }

    public function down(): void
    {
        // SQLite can't drop columns easily
    }
};
