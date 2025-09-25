<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostSpotlightTable extends Migration
{
    public function up()
    {
        Schema::create('post_spotlight', function (Blueprint $table) {
            $table->increments('postspotlight');
            $table->unsignedInteger('post_id');
            $table->unsignedInteger('label_id');
            $table->string('status_spotlight', 100);
            $table->dateTime('tanggal_awal_spotlight')->nullable();
            $table->dateTime('tanggal_akhir_spotlight')->nullable();
            $table->string('created_by', 10)->nullable();
            $table->string('updated_by', 10)->nullable();
            $table->string('deleted_by', 10)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('post_spotlight');
    }
}
