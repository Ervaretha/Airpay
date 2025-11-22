<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->decimal('total_amount', 10, 2);
            $table->decimal('cash_received', 10, 2)->nullable();
            $table->decimal('change', 10, 2)->nullable();
            $table->enum('payment_method', ['cash', 'transfer']);
            $table->foreignId('user_id')->constrained();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};