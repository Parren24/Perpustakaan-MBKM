<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostHasLabelTable extends Migration
{
    public function up()
    {
        Schema::create('post_has_label', function (Blueprint $table) {
            $table->unsignedInteger('post_id');
            $table->unsignedInteger('postlabel_id');
            $table->unique(['post_id', 'postlabel_id'], 'post_has_label_unique');
        });
    }

    public function down()
    {
        Schema::dropIfExists('post_has_label');
    }
}
