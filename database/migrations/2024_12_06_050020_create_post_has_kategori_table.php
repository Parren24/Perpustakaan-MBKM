<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostHasKategoriTable extends Migration
{
    public function up()
    {
        // Schema::create('post_has_kategori', function (Blueprint $table) {
        //     $table->unsignedInteger('post_id');
        //     $table->unsignedInteger('postkategori_id');
        //     $table->unique(['post_id', 'postkategori_id'], 'post_has_kategori_unique');
        // });
    }

    public function down()
    {
        Schema::dropIfExists('post_has_kategori');
    }
}
