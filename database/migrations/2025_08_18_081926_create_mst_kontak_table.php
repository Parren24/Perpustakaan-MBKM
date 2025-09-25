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
        Schema::create('mst_kontak', function (Blueprint $table) {
            $table->id('kontak_id');
            $table->string('nama_kontak', 300);
            $table->string('icon_kontak', 300);
            $table->string('tipe_kontak', 300);
            $table->string('value_kontak', 300);
            $table->text('deskripsi_kontak')->nullable();
            $table->string('status_kontak', 300);

            $table->string('created_by', 10);
            $table->string('updated_by', 10)->nullable();
            $table->string('deleted_by', 10)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mst_kontak');
    }
};
