<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_details', function (Blueprint $table) {
            $table->bigIncrements('user_id');
            $table->text('about')->nullable();
            $table->date('birthdate')->nullable();
            $table->enum('gender', ['na', 'm', 'f'])->default('na')->nullable();
            $table->text('address')->nullable();
            $table->string('street')->nullable();
            $table->string('town')->nullable();
            $table->string('zipcode')->nullable();
            $table->string('country')->default('Indonesia');
            $table->string('timezone')->default('Asia/Jakarta');
            $table->string('language')->default('id');
            $table->string('phone')->nullable();
            $table->string('company')->nullable();
            $table->string('website')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_details');
    }
}
