<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('PRAGMA foreign_keys = OFF');

        DB::statement('ALTER TABLE reviews RENAME TO _reviews_old');

        DB::statement('CREATE TABLE reviews (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            product_id INTEGER NOT NULL,
            user_id INTEGER,
            author_name VARCHAR,
            rating INTEGER NOT NULL,
            body TEXT,
            approved BOOLEAN NOT NULL DEFAULT 1,
            created_at DATETIME,
            updated_at DATETIME,
            FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
        )');

        DB::statement('INSERT INTO reviews SELECT * FROM _reviews_old');

        DB::statement('DROP TABLE _reviews_old');

        DB::statement('PRAGMA foreign_keys = ON');
    }

    public function down(): void
    {
        // body was NOT NULL before
    }
};
