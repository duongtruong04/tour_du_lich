<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('news', function (Blueprint $table) {
            if (!Schema::hasColumn('news', 'summary')) {
                $table->text('summary')->nullable()->after('slug');
            }
            if (!Schema::hasColumn('news', 'image_path')) {
                $table->string('image_path')->nullable()->after('content');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('news', function (Blueprint $table) {
            if (Schema::hasColumn('news', 'summary')) {
                $table->dropColumn('summary');
            }
            if (Schema::hasColumn('news', 'image_path')) {
                $table->dropColumn('image_path');
            }
        });
    }
};
