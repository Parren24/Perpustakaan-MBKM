<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('akreditasi', function (Blueprint $table) {
            $table->id('akreditasi_id');
            $table->string('level', 100); // institusi / prodi
            $table->string('level_id', 100);
            $table->string('lembaga_akreditasi', 300);
            $table->string('tahun_akreditasi', 20);
            $table->string('akreditasi', 20);
            $table->text('filename_akreditasi');

            $table->string('created_by', 10);
            $table->string('updated_by', 10)->nullable();
            $table->string('deleted_by', 10)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('akreditasi');
    }
};
