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
        Schema::create('mst_social_media', function (Blueprint $table) {
            $table->id('socialmedia_id');
            $table->string('platform', 300);
            $table->string('icon_social_media', 300);
            $table->string('url_social_media', 300);
            $table->text('deskripsi_social_media')->nullable();
            $table->string('status_social_media', 300);

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
        Schema::dropIfExists('mst_social_media');
    }
};
