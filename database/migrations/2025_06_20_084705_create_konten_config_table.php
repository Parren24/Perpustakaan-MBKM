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
        // Schema::create('konten_config', function (Blueprint $table) {
        //     $table->id('kontenconfig_id');
        //     $table->string('level', 100); // main-site, jurusan-site, prodi-site
        //     $table->string('level_id', 100)->nullable();
        //     $table->text('sequence_konten')->nullable(); // [[section-name]]

        //     // Kolom standar tambahan
        //     $table->string('created_by', 10);
        //     $table->string('updated_by', 10)->nullable();
        //     $table->string('deleted_by', 10)->nullable();
        //     $table->timestamps();
        //     $table->softDeletes();
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('konten_config');
    }
};
