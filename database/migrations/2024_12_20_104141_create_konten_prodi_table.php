<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKontenProdiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('konten_prodi', function (Blueprint $table) {
        //     $table->id('kontenprodi_id');
        //     $table->string('alias_prodi', 10);
        //     $table->text('header_prodi')->nullable(); // [media_id]
        //     $table->text('tentang_prodi')->nullable(); // [deskripsi]
        //     $table->text('prospek_karir_prodi')->nullable(); // [keterangan, [prospek_karir]]
        //     $table->text('milestone_prodi')->nullable(); // [keterangan, [tahun, keterangan]]
        //     $table->text('visi_prodi')->nullable(); // [visi]
        //     $table->text('misi_prodi')->nullable(); // [[icon, misi]]

        //     // Kolom standar tambahan
        //     $table->string('created_by', 10);
        //     $table->string('updated_by', 10)->nullable();
        //     $table->string('deleted_by', 10)->nullable();
        //     $table->timestamps();
        //     $table->softDeletes();
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('konten_prodi');
    }
}
