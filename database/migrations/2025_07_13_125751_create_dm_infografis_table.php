<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('dm_infografis', function (Blueprint $table) {
            $table->id('infografis_id');
            $table->text('nama_infografis');
            $table->text('value_infografis');
            $table->text('icon_infografis');
            $table->integer('seq');
            $table->text('sync_url');
            $table->text('sync_log');

            // Kolom tambahan sesuai preferensi Anda
            $table->string('created_by', 10);
            $table->string('updated_by', 10)->nullable();
            $table->string('deleted_by', 10)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dm_infografis');
    }
};
