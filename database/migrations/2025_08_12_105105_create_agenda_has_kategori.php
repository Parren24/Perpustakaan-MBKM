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
        Schema::create('agenda_has_kategori', function (Blueprint $table) {
            $table->unsignedInteger('agenda_id');
            $table->unsignedInteger('agendakategori_id');
            $table->unique(['agenda_id', 'agendakategori_id'], 'agenda_has_kategori_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agenda_has_kategori');
    }
};
