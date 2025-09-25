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
        Schema::create('dm_kurikulum', function (Blueprint $table) {
            $table->string('alias_prodi', 10);
            $table->integer('tahun_kurikulum');
            $table->string('nama_kurikulum', 250);
            $table->text('matakuliah')->nullable(); // [[semester, [kode_mk, nama_mk, sks, jam]]]
            $table->text('sync_log')->nullable();

            // Kolom standar tambahan
            $table->string('created_by', 10);
            $table->string('updated_by', 10)->nullable();
            $table->string('deleted_by', 10)->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Primary key komposit
            $table->primary(['alias_prodi', 'tahun_kurikulum']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dm_kurikulum');
    }
};
