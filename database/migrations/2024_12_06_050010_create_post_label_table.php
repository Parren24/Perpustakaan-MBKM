<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostLabelTable extends Migration
{
    public function up()
    {
        Schema::create('post_label', function (Blueprint $table) {
            $table->increments('postlabel_id');
            $table->integer('postkategori_id');
            $table->string('kode_label', 100);
            $table->string('nama_label', 100);
            $table->text('deskripsi_label')->nullable();
            $table->string('created_by', 10)->nullable();
            $table->string('updated_by', 10)->nullable();
            $table->string('deleted_by', 10)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('post_label');
    }
}
