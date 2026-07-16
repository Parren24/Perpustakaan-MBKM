<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('posisi', 50)->nullable();
            $table->string('inisial', 10)->nullable();
            $table->string('prodi', 100)->nullable();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['posisi', 'inisial', 'prodi']);
        });
    }
};
