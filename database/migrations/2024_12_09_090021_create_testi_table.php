<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestiTable extends Migration
{
    public function up()
    {
        Schema::create('testi', function (Blueprint $table) {
            $table->increments('testi_id');
            $table->unsignedInteger('prodi_id');
            $table->unsignedInteger('angkatan');
            $table->string('nama_alumni', 300);
            $table->string('posisi_alumni', 300)->nullable();
            $table->string('tempat_kerja_alumni', 300)->nullable();
            $table->text('isi_testi')->nullable();
            $table->unsignedInteger('media_id_alumni')->nullable();
            $table->text('status_testi');
            $table->string('created_by', 10)->nullable();
            $table->string('updated_by', 10)->nullable();
            $table->string('deleted_by', 10)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('testi');
    }
}
