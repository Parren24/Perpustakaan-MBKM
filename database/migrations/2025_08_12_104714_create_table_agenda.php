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
        Schema::create('agenda', function (Blueprint $table) {
            $table->increments('agenda_id');
            $table->string('agendakategori_id', 100);
            $table->string('level', 100);
            $table->string('level_id', 10)->nullable();
            $table->string('nama_agenda', 300);
            $table->text('deskripsi_agenda')->nullable();
            $table->date('tanggal_start_agenda');
            $table->date('tanggal_end_agenda')->nullable();
            $table->time('waktu_start_agenda')->nullable();
            $table->time('waktu_end_agenda')->nullable();
            $table->string('lokasi_agenda', 300)->nullable();
            $table->string('url_agenda', 300)->nullable();
            $table->string('status_agenda', 50);
            // $table->unsignedInteger('media_id_agenda')->nullable();
            $table->string('created_by', 10)->nullable();
            $table->string('updated_by', 10)->nullable();
            $table->string('deleted_by', 10)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agenda');
    }
};
