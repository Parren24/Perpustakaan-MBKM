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
        Schema::create('cart_loan', function (Blueprint $table) {
            $table->id('loan_id'); // Primary Key
            $table->integer('member_id');
            $table->dateTime('tanggal');
            $table->json('list_item');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_loan');
    }
};
