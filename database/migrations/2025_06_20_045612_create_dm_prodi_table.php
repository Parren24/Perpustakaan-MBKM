<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDmProdiTable extends Migration
{
    public function up()
    {
        Schema::create('dm_prodi', function (Blueprint $table) {
            $table->id('prodi_id'); // Primary key
            $table->string('alias_prodi', 10);
            $table->string('alias_jurusan', 10);
            $table->text('nama_prodi');
            $table->text('deskripsi_prodi')->nullable();
            $table->unsignedInteger('media_id_prodi')->nullable();
            $table->text('sync_log')->nullable();

            // Kolom tambahan standar
            $table->string('created_by', 10);
            $table->string('updated_by', 10)->nullable();
            $table->string('deleted_by', 10)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('dm_prodi');
    }
}
