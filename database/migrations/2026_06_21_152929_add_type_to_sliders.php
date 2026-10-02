<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('sliders') && !Schema::hasColumn('sliders', 'type')) {
            Schema::table('sliders', function (Blueprint $table) {
                $table->string('type', 50)->default('slider');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('sliders') && Schema::hasColumn('sliders', 'type')) {
            Schema::table('sliders', function (Blueprint $table) {
                $table->dropColumn('type');
            });
        }
    }
};
