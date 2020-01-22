<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ZetthCreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email')->unique();
            $table->string('name')->unique();
            $table->string('fullname');
            $table->string('password');
            $table->text('about')->nullable();
            $table->string('image')->nullable();
            // $table->json('settings')->default('[]');
            $table->string('timezone')->default('Asia/Jakarta');
            $table->string('language')->default('id');
            $table->string('phone')->nullable();
            $table->string('company')->nullable();
            $table->string('website')->nullable();
            $table->string('token')->nullable();
            $table->datetime('token_expire')->nullable();
            // $table->integer('role_id')->unsigned();
            // $table->dateTime('login_last')->nullable();
            // $table->boolean('login_failed')->unsigned()->default(0);
            // $table->boolean('is_admin')->comment('0=no, 1=yes')->unsigned()->default(1);
            $table->boolean('status')->comment('0=inactive, 1=active, 2=banned')->unsigned();
            // $table->rememberToken();
            $table->string('verify_code')->nullable();
            $table->timestamp('verified_at')->nullable();
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
