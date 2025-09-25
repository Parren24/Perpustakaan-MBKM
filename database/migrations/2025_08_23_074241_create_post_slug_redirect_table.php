<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('post_slug_redirect', function (Blueprint $table) {
            $table->unsignedInteger('post_id');
            $table->string('old_slug');
            $table->unique(['post_id', 'old_slug'], 'post_slug_redirect_unique');

            // lalu tambahkan foreign key yang merujuk ke post(post_id)
            $table->foreign('post_id')->references('post_id')->on('post')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('post_slug_redirect', function (Blueprint $table) {
            // drop foreign dulu (laravel otomatis beri nama, aman untuk drop)
            $table->dropForeign(['post_id']);
        });
        Schema::dropIfExists('post_slug_redirect');
    }
};
