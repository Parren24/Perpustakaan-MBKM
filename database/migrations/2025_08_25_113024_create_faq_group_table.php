<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('faq_group', function (Blueprint $table) {
            $table->id('faqgroup_id');
            $table->string('nama_group', 300);
            $table->string('status_group', 30);

            $table->string('created_by', 10);
            $table->string('updated_by', 10)->nullable();
            $table->string('deleted_by', 10)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('faq_group');
    }
};
