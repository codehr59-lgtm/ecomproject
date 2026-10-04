<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasTable('order_items') || ! Schema::hasTable('products')) {
            return;
        }

        $driver = DB::getDriverName();

        if ($driver === 'mysql' || $driver === 'mariadb') {
            // 1. Ensure product_id column allows NULL
            try {
                DB::statement('ALTER TABLE order_items MODIFY product_id BIGINT UNSIGNED NULL');
            } catch (\Throwable) {}

            // 2. Drop existing foreign key constraint
            try {
                DB::statement('ALTER TABLE order_items DROP FOREIGN KEY order_items_product_id_foreign');
            } catch (\Throwable) {}

            // 3. Re-add foreign key with ON DELETE SET NULL
            try {
                DB::statement('ALTER TABLE order_items ADD CONSTRAINT order_items_product_id_foreign FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE SET NULL');
            } catch (\Throwable) {}

            return;
        }

        if ($driver === 'pgsql') {
            try {
                DB::statement('ALTER TABLE order_items ALTER COLUMN product_id DROP NOT NULL');
            } catch (\Throwable) {}

            try {
                DB::statement('ALTER TABLE order_items DROP CONSTRAINT IF EXISTS order_items_product_id_foreign');
            } catch (\Throwable) {}

            try {
                DB::statement('ALTER TABLE order_items ADD CONSTRAINT order_items_product_id_foreign FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE SET NULL');
            } catch (\Throwable) {}

            return;
        }

        // SQLite fallback
        try {
            Schema::table('order_items', function (Blueprint $table) {
                try {
                    $table->dropForeign(['product_id']);
                } catch (\Throwable) {}
                $table->foreign('product_id')->references('id')->on('products')->nullOnDelete();
            });
        } catch (\Throwable) {}
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No-op
    }
};
