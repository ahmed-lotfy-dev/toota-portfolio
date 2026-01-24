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
        Schema::table('hero_images', function (Blueprint $table) {
            $table->integer('width')->nullable();
            $table->integer('height')->nullable();
            $table->decimal('aspect_ratio', 8, 4)->nullable();
            $table->string('ratio_mode')->default('original'); // 'original' or 'preset'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hero_images', function (Blueprint $table) {
            $table->dropColumn(['width', 'height', 'aspect_ratio', 'ratio_mode']);
        });
    }
};
