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
            $table->bigIncrements('id');
            $table->string('email')->unique();
            $table->string('name')->unique();
            $table->string('fullname');
            $table->string('password');
            $table->string('image')->nullable();
            $table->boolean('is_first_login')->comment('0=no, 1=yes')->unsigned()->default(1);
            $table->boolean('status')->comment('0=inactive, 1=active, 2=suspend, 3=banned')->unsigned();
            $table->timestamps();
            $table->softDeletes();
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
