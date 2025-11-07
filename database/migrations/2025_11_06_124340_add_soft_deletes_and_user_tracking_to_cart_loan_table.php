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
        Schema::table('cart_loan', function (Blueprint $table) {
            // Add soft deletes
            $table->softDeletes();
            
            // Add user tracking columns
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
            
            // Make tanggal nullable if it should be optional
            $table->dateTime('tanggal')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cart_loan', function (Blueprint $table) {
            // Remove soft deletes
            $table->dropSoftDeletes();
            
            // Remove user tracking columns
            $table->dropColumn(['created_by', 'updated_by', 'deleted_by']);
        });
    }
};
