<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestiHasKategoriTable extends Migration
{
    public function up()
    {
        Schema::create('testi_has_kategori', function (Blueprint $table) {
            $table->unsignedInteger('testi_id');
            $table->unsignedInteger('testikategori_id');
            $table->primary(['testi_id', 'testikategori_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('testi_has_kategori');
    }
}
