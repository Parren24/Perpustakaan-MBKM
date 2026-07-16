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
        Schema::table('penalties', function (Blueprint $table) {
            //
            $table->string('approve_by')->nullable()->after('status');
            $table->timestamp('approve_at')->nullable()->after('approve_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penalties', function (Blueprint $table) {
            //
            $table->dropColumn('approve_by');
            $table->dropColumn('approve_at');
        });
    }
};
