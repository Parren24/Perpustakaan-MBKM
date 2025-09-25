<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventHasKategoriTable extends Migration
{
    public function up()
    {
        Schema::create('event_has_kategori', function (Blueprint $table) {
            $table->unsignedInteger('event_id');
            $table->unsignedInteger('eventkategori_id');
            $table->unique(['event_id', 'eventkategori_id'], 'event_has_kategori_unique');
        });
    }

    public function down()
    {
        Schema::dropIfExists('event_has_kategori');
    }
}
