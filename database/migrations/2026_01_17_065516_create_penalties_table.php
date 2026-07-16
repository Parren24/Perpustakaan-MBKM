<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('penalties', function (Blueprint $table) {
            $table->id('penalty_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('loan_id')->nullable();
            $table->string('item_code', 100);
            $table->date('loan_date');
            $table->date('due_date');
            $table->enum('status', ['PENDING', 'PAID'])->default('PENDING');
            $table->decimal('amount', 10, 2);
            $table->string('created_by', 10)->nullable();
            $table->string('updated_by', 10)->nullable();
            $table->string('deleted_by', 10)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('penalties');
    }
};
