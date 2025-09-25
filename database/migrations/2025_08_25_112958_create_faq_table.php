<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('faq', function (Blueprint $table) {
            $table->id('faq_id');
            $table->unsignedBigInteger('faqgroup_id');
            $table->text('faq');
            $table->text('jawaban_faq');
            $table->string('status_faq', 30);

            $table->string('created_by', 10);
            $table->string('updated_by', 10)->nullable();
            $table->string('deleted_by', 10)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('faq');
    }
};
