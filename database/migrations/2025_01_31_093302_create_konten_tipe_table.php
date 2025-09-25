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
        // Schema::create('konten_tipe', function (Blueprint $table) {
        //     $table->increments('kontentipe_id');
        //     $table->string('kode_tipe', 100);
        //     $table->string('nama_tipe', 100);
        //     $table->text('deskripsi_tipe')->nullable();
        //     $table->text('component_tipe')->nullable();
        //     $table->string('created_by', 10)->nullable();
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
        Schema::dropIfExists('konten_tipe');
    }
};
