<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKontenJurusanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('konten_jurusan', function (Blueprint $table) {
        //     $table->id('kontenjurusan_id');
        //     $table->string('alias_jurusan', 10);
        //     $table->text('header_jurusan')->nullable(); // [media_id]
        //     $table->text('sambutan_jurusan')->nullable(); // [title, media_id, sambutan, pemberi_sambutan, jabatan_sambutan]
        //     $table->text('tentang_jurusan')->nullable(); // [title, deskripsi]
        //     $table->text('prodi_jurusan')->nullable(); // [title, deskripsi]

        //     // Kolom standar tambahan
        //     $table->string('created_by', 10);
        //     $table->string('updated_by', 10)->nullable();
        //     $table->string('deleted_by', 10)->nullable();
        //     $table->timestamps();
        //     $table->softDeletes(); // menghasilkan kolom deleted_at
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('konten_jurusan');
    }
}
