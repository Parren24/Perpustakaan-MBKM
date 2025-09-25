<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventTable extends Migration
{
    public function up()
    {
        Schema::create('event', function (Blueprint $table) {
            $table->increments('event_id');
            $table->string('eventkategori_id', 100);
            $table->string('level', 100);
            $table->string('level_id', 10)->nullable();
            $table->string('nama_event', 300);
            $table->text('deskripsi_event')->nullable();
            $table->date('tanggal_event');
            $table->time('waktu_event')->nullable();
            $table->string('lokasi_event', 300)->nullable();
            $table->string('url_event', 300)->nullable();
            $table->string('status_event', 50);
            $table->unsignedInteger('media_id_event')->nullable();
            $table->string('created_by', 10)->nullable();
            $table->string('updated_by', 10)->nullable();
            $table->string('deleted_by', 10)->nullable();
            $table->timestamps();
            $table->softDeletes();
            // $table->date('tanggal_start_event');
            // $table->date('tanggal_end_event')->nullable();
            // $table->time('waktu_start_event')->nullable();
            // $table->time('waktu_end_event')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('event');
    }
}
