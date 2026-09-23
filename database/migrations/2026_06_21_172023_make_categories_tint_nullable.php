<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement('ALTER TABLE categories ALTER COLUMN tint DROP NOT NULL');
            return;
        }

        // SQLite doesn't support ALTER COLUMN — rebuild the table
        DB::statement('PRAGMA foreign_keys = OFF');

        DB::statement('CREATE TABLE categories_new (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            slug VARCHAR NOT NULL,
            name VARCHAR NOT NULL,
            image VARCHAR,
            tint VARCHAR,
            note VARCHAR,
            sort INTEGER NOT NULL DEFAULT 0,
            is_active TINYINT(1) NOT NULL DEFAULT 1,
            created_at DATETIME,
            updated_at DATETIME,
            parent_id INTEGER
        )');

        DB::statement('INSERT INTO categories_new SELECT id, slug, name, image, tint, note, sort, is_active, created_at, updated_at, parent_id FROM categories');
        DB::statement('DROP TABLE categories');
        DB::statement('ALTER TABLE categories_new RENAME TO categories');

        DB::statement('PRAGMA foreign_keys = ON');
    }

    public function down(): void
    {
        // no-op
    }
};
