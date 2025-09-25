<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Schema::create('konten_page_config', function (Blueprint $table) {
        //     $table->string('target_config', 100);
        //     $table->integer('kontentipe_id');
        //     $table->unique(['target_config', 'kontentipe_id'], 'konten_page_config_unique');
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('konten_page_config');
    }
};
