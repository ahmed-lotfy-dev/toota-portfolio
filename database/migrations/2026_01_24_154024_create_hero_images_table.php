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
        Schema::create('hero_images', function (Blueprint $table) {
            $table->id();
            $table->string('label', 50);
            $table->string('image_path');
            $table->tinyInteger('position')->unsigned();
            $table->timestamps();

            $table->unique('position');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hero_images');
    }
};
