<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventProgresTable extends Migration
{
    public function up()
    {
        Schema::create('event_progres', function (Blueprint $table) {
            $table->increments('eventprogres_id');
            $table->unsignedInteger('event_id');
            $table->string('status_progres', 100);
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
        Schema::dropIfExists('event_progres');
    }
}
