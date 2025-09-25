<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKontenMainTable extends Migration
{
    public function up(): void
    {
        // Schema::create('konten_main', function (Blueprint $table) {
        //     $table->id('kontenmain_id');
        //     $table->string('level', 100); // main-site, jurusan-site, prodi-site
        //     $table->string('level_id', 100)->nullable(); // main-site, jurusan-site, prodi-site
        //     $table->text('hero_main')->nullable(); // [title, media_type, media]
        //     $table->text('infografis_main')->nullable(); // title, deskripsi, media
        //     $table->text('jurusan_main')->nullable(); // title, deskripsi
        //     $table->text('pmb_main')->nullable(); // title, deskripsi, media
        //     $table->text('partner_main')->nullable(); // title, deskripsi

        //     // Kolom standar tambahan
        //     $table->string('created_by', 10);
        //     $table->string('updated_by', 10)->nullable();
        //     $table->string('deleted_by', 10)->nullable();
        //     $table->timestamps();
        //     $table->softDeletes(); // menghasilkan kolom deleted_at
        // });
    }

    public function down(): void
    {
        Schema::dropIfExists('konten_main');
    }
}
