<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company')->nullable();
            $table->foreign('company')->references('id')->on('users')->onDelete('cascade');
            $table->string('transaction_id')->nullable();
            $table->string('description')->nullable();
            $table->float('amount')->default(0);
            $table->date('subscription_start')->nullable();
            $table->date('subscription_expiry')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
