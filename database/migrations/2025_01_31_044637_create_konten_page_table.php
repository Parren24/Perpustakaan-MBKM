<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Schema::create('konten_page', function (Blueprint $table) {
        //     $table->increments('kontenpage_id');
        //     $table->integer('kontentipe_id');
        //     $table->string('level', 100);
        //     $table->string('level_id', 10)->nullable();
        //     $table->string('title_page', 255);
        //     $table->string('subtitle_page', 255)->nullable();
        //     $table->text('konten_page')->nullable();
        //     $table->text('component_page')->nullable(); //json jika terdapat data tambahan
        //     $table->string('status_page', 50);
        //     $table->text('meta_desc_page')->nullable();
        //     $table->text('meta_keyword_page')->nullable();
        //     $table->string('created_by', 10)->nullable();
        //     $table->string('updated_by', 10)->nullable();
        //     $table->string('deleted_by', 10)->nullable();
        //     $table->timestamps();
        //     $table->softDeletes();
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('konten_page');
    }
};
