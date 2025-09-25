<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostProgresTable extends Migration
{
    public function up()
    {
        Schema::create('post_progres', function (Blueprint $table) {
            $table->increments('postprogres_id');
            $table->unsignedInteger('post_id');
            $table->string('status_progres', 100);
            $table->text('keterangan_progres')->nullable();
            $table->text('catatan_progres')->nullable();
            $table->unsignedInteger('user_id_progres');
            $table->string('created_by', 10)->nullable();
            $table->string('updated_by', 10)->nullable();
            $table->string('deleted_by', 10)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('post_progres');
    }
}
