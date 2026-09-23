<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('PRAGMA foreign_keys=OFF');
        DB::statement('ALTER TABLE products RENAME TO _products_old2');

        DB::statement('
            CREATE TABLE products (
                id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
                slug VARCHAR NOT NULL,
                name VARCHAR NOT NULL,
                weight VARCHAR,
                price INTEGER NOT NULL DEFAULT 0,
                old_price INTEGER,
                category_id INTEGER NOT NULL,
                brand_id INTEGER,
                badge VARCHAR,
                rating NUMERIC DEFAULT 0,
                reviews NUMERIC DEFAULT 0,
                blurb TEXT,
                certified TINYINT(1) DEFAULT 0,
                stock INTEGER NOT NULL DEFAULT 0,
                is_active TINYINT(1) NOT NULL DEFAULT 1,
                image VARCHAR,
                sort INTEGER NOT NULL DEFAULT 0,
                created_at DATETIME,
                updated_at DATETIME,
                product_type VARCHAR NOT NULL DEFAULT \'simple\',
                sku VARCHAR,
                video_url VARCHAR
            )
        ');

        DB::statement('INSERT INTO products SELECT * FROM _products_old2');
        DB::statement('DROP TABLE _products_old2');
        DB::statement('CREATE UNIQUE INDEX products_slug_unique ON products(slug)');

        DB::statement('PRAGMA foreign_keys=ON');
    }

    public function down(): void {}
};
