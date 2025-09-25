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
        Schema::create('mst_partner', function (Blueprint $table) {
            $table->id('partner_id');
            $table->string('jenis_partner', 300);
            $table->string('nama_partner', 300);
            $table->text('deskripsi_partner')->nullable();
            $table->string( 'url_partner', 300)->nullable();
            $table->text('filemedia_partner')->nullable();
            $table->string('status_partner', 20);

            // Kolom standar tambahan
            $table->string('created_by', 10);
            $table->string('updated_by', 10)->nullable();
            $table->string('deleted_by', 10)->nullable();
            $table->timestamps();
            $table->softDeletes(); // kolom deleted_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mst_partner');
    }
};
