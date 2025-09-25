<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestiKategoriTable extends Migration
{
    public function up()
    {
        Schema::create('testi_kategori', function (Blueprint $table) {
            $table->increments('testikategori_id');
            $table->string('kode_kategori', 100);
            $table->string('nama_kategori', 100);
            $table->text('deskripsi_kategori')->nullable();
            $table->string('created_by', 10)->nullable();
            $table->string('updated_by', 10)->nullable();
            $table->string('deleted_by', 10)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('testi_kategori');
    }
}

