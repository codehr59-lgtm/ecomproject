<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
            return;
        }

        DB::statement('PRAGMA foreign_keys=OFF');

        // 1. product_variations
        DB::statement('ALTER TABLE product_variations RENAME TO _pv_old');
        DB::statement('
            CREATE TABLE product_variations (
                "id" integer primary key autoincrement not null,
                "product_id" integer not null,
                "label" varchar not null,
                "price" integer not null,
                "stock" integer not null default \'0\',
                "sku" varchar,
                "created_at" datetime,
                "updated_at" datetime,
                "type" varchar not null default \'size\',
                "image" varchar,
                foreign key("product_id") references "products"("id") on delete cascade
            )
        ');
        DB::statement('INSERT INTO product_variations SELECT * FROM _pv_old');
        DB::statement('DROP TABLE _pv_old');

        // 2. product_images
        DB::statement('ALTER TABLE product_images RENAME TO _pi_old');
        DB::statement('
            CREATE TABLE product_images (
                "id" integer primary key autoincrement not null,
                "product_id" integer not null,
                "path" varchar not null,
                "sort" integer not null default \'0\',
                "created_at" datetime,
                "updated_at" datetime,
                foreign key("product_id") references "products"("id") on delete cascade
            )
        ');
        DB::statement('INSERT INTO product_images SELECT * FROM _pi_old');
        DB::statement('DROP TABLE _pi_old');

        // 3. reviews
        DB::statement('ALTER TABLE reviews RENAME TO _rev_old');
        DB::statement('
            CREATE TABLE reviews (
                "id" integer primary key autoincrement not null,
                "product_id" integer not null,
                "user_id" integer,
                "author_name" varchar,
                "rating" integer not null,
                "body" text not null,
                "approved" tinyint(1) not null default \'1\',
                "created_at" datetime,
                "updated_at" datetime,
                foreign key("product_id") references "products"("id") on delete cascade,
                foreign key("user_id") references "users"("id") on delete set null
            )
        ');
        DB::statement('INSERT INTO reviews SELECT * FROM _rev_old');
        DB::statement('DROP TABLE _rev_old');

        // 4. wishlists
        DB::statement('ALTER TABLE wishlists RENAME TO _wl_old');
        DB::statement('
            CREATE TABLE wishlists (
                "id" integer primary key autoincrement not null,
                "user_id" integer not null,
                "product_id" integer not null,
                "created_at" datetime,
                "updated_at" datetime,
                foreign key("user_id") references "users"("id") on delete cascade,
                foreign key("product_id") references "products"("id") on delete cascade
            )
        ');
        DB::statement('INSERT INTO wishlists SELECT * FROM _wl_old');
        DB::statement('DROP TABLE _wl_old');

        // 5. order_items
        DB::statement('ALTER TABLE order_items RENAME TO _oi_old');
        DB::statement('
            CREATE TABLE order_items (
                "id" integer primary key autoincrement not null,
                "order_id" integer not null,
                "product_id" integer,
                "name" varchar not null,
                "weight" varchar,
                "price" integer not null,
                "qty" integer not null,
                "line_total" integer not null,
                "created_at" datetime,
                "updated_at" datetime,
                foreign key("order_id") references "orders"("id") on delete cascade,
                foreign key("product_id") references "products"("id")
            )
        ');
        DB::statement('INSERT INTO order_items SELECT * FROM _oi_old');
        DB::statement('DROP TABLE _oi_old');

        // 6. product_tag
        DB::statement('ALTER TABLE product_tag RENAME TO _pt_old');
        DB::statement('
            CREATE TABLE product_tag (
                "product_id" integer not null,
                "tag_id" integer not null,
                foreign key("product_id") references "products"("id") on delete cascade,
                foreign key("tag_id") references "tags"("id") on delete cascade,
                primary key ("product_id", "tag_id")
            )
        ');
        DB::statement('INSERT INTO product_tag SELECT * FROM _pt_old');
        DB::statement('DROP TABLE _pt_old');

        // 7. product_specifications
        DB::statement('ALTER TABLE product_specifications RENAME TO _ps_old');
        DB::statement('
            CREATE TABLE product_specifications (
                "id" integer primary key autoincrement not null,
                "product_id" integer not null,
                "label" varchar not null,
                "value" varchar not null,
                "sort" integer not null default \'0\',
                "created_at" datetime,
                "updated_at" datetime,
                foreign key("product_id") references "products"("id") on delete cascade
            )
        ');
        DB::statement('INSERT INTO product_specifications SELECT * FROM _ps_old');
        DB::statement('DROP TABLE _ps_old');

        // 8. product_faqs
        DB::statement('ALTER TABLE product_faqs RENAME TO _pf_old');
        DB::statement('
            CREATE TABLE product_faqs (
                "id" integer primary key autoincrement not null,
                "product_id" integer not null,
                "question" varchar not null,
                "answer" text not null,
                "sort" integer not null default \'0\',
                "created_at" datetime,
                "updated_at" datetime,
                foreign key("product_id") references "products"("id") on delete cascade
            )
        ');
        DB::statement('INSERT INTO product_faqs SELECT * FROM _pf_old');
        DB::statement('DROP TABLE _pf_old');

        // 9. related_products
        DB::statement('ALTER TABLE related_products RENAME TO _rp_old');
        DB::statement('
            CREATE TABLE related_products (
                "product_id" integer not null,
                "related_product_id" integer not null,
                foreign key("product_id") references "products"("id") on delete cascade,
                foreign key("related_product_id") references "products"("id") on delete cascade,
                primary key ("product_id", "related_product_id")
            )
        ');
        DB::statement('INSERT INTO related_products SELECT * FROM _rp_old');
        DB::statement('DROP TABLE _rp_old');

        DB::statement('PRAGMA foreign_keys=ON');
    }

    public function down(): void {}
};
