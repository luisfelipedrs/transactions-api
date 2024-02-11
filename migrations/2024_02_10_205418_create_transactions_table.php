<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('external_id')->index();
            $table->bigInteger('payer_id')->unsigned();
            $table->foreign('payer_id')->references('id')->on('users');
            $table->bigInteger('payee_id')->unsigned();
            $table->foreign('payee_id')->references('id')->on('users');
            $table->string('amount');
            $table->enum('status', ['CREATED', 'EXECUTED', 'ERROR']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
}
