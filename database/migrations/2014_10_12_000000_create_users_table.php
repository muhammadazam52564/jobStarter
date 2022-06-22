<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->unique();
            $table->string('profile_image')->default('profile_images/default.png');

            $table->string('password')->nullable();
            $table->string('school')->nullable();
            $table->string('address')->nullable();
            $table->text('description')->nullable();

            $table->date('dob')->nullable();
            $table->string('role')->nullable();
            $table->integer('status')->default(0);
            $table->integer('verified')->default(0);
            $table->integer('category_id')->default(0);
            $table->string('token')->nullable();

            $table->timestamp('email_verified_at')->nullable();
            $table->string('otp')->nullable();
            $table->rememberToken();
            $table->timestamps();

            // $table->unsignedBigInteger('product_id')->nullable();
            // $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
