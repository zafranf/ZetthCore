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
            $table->increments('id');
            $table->string('email')->index();
            $table->string('name')->index();
            $table->string('fullname')->index();
            $table->string('password');
            $table->string('image')->nullable();
            $table->string('timezone')->default('Asia/Jakarta');
            $table->string('language')->default('id_ID');
            $table->enum('is_first_login', ['yes', 'no'])->default('yes');
            $table->enum('status', ['active', 'inactive', 'suspend', 'banned'])->default('inactive');
            $table->timestamps(6);
            $table->softDeletes('deleted_at', 6);
            $table->integer('site_id')->unsigned()->default(1);

            $table->foreign('site_id')->references('id')->on('sites')
                ->onUpdate('cascade')->onDelete('cascade');
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
