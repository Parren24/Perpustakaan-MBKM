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
        Schema::create('dm_pegawai', function (Blueprint $table) {
            $table->string('nip_pegawai', 10)->primary();
            $table->string('nidn_pegawai', 50)->nullable();
            $table->text('inisial');
            $table->text('nama_pegawai');
            $table->text('email_pegawai');
            $table->text('gelar_depan_pegawai')->nullable();
            $table->text('gelar_belakang_pegawai')->nullable();
            $table->text('homebase_pegawai')->nullable();
            $table->text('jabatan_pegawai')->nullable();
            $table->text('fungsional_pegawai')->nullable();
            $table->text('profil_pegawai')->nullable();
            $table->text('media_id_pegawai')->nullable();
            $table->text('sync_log')->nullable();

            // Kolom tambahan standar (sesuai aturan kamu)
            $table->string('created_by', 10);
            $table->string('updated_by', 10)->nullable();
            $table->string('deleted_by', 10)->nullable();
            $table->timestamps();
            $table->dateTime('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dm_pegawai');
    }
};
