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
        Schema::table('tours', function (Blueprint $table) {
            $table->string('transportation')->nullable()->after('duration');
            $table->text('service_includes')->nullable()->after('transportation');
            $table->text('service_excludes')->nullable()->after('service_includes');
        });

        Schema::table('promotions', function (Blueprint $table) {
            $table->string('title')->nullable()->after('code');
            $table->text('description')->nullable()->after('title');
            $table->string('image_path')->nullable()->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tours', function (Blueprint $table) {
            $table->dropColumn(['transportation', 'service_includes', 'service_excludes']);
        });

        Schema::table('promotions', function (Blueprint $table) {
            $table->dropColumn(['title', 'description', 'image_path']);
        });
    }
};
