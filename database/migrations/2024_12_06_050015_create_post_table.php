<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostTable extends Migration
{
    public function up()
    {
        Schema::create('post', function (Blueprint $table) {
            $table->increments('post_id');
            // $table->string('bahasa', 5);
            $table->unsignedInteger('postkategori_id');
            $table->string('level', 100);
            $table->string('level_id', 10)->nullable();
            $table->string('judul_post', 300);
            $table->text('isi_post')->nullable();
            $table->dateTime('tanggal_post')->nullable();
            $table->unsignedInteger('user_id_author')->nullable();
            $table->string('status_post', 50);
            $table->string('meta_desc_post', 300)->nullable();
            $table->string('meta_keyword_post', 300)->nullable();
            $table->text('slug_post')->nullable();
            $table->text('filename_post')->nullable();
            $table->unsignedInteger('seq_spotlight_post')->nullable();
            $table->string('created_by', 10)->nullable();
            $table->string('updated_by', 10)->nullable();
            $table->string('deleted_by', 10)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('post');
    }
}
