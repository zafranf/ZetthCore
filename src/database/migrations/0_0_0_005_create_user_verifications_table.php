<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserVerificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_verifications', function (Blueprint $table) {
            $table->integer('user_id')->unsigned();
            $table->string('code');
            $table->datetime('expired_at');
            $table->timestamp('verified_at')->nullable();
            $table->integer('site_id')->unsigned()->default(1);

            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('site_id')->references('id')->on('sites')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->unique(['user_id', 'code']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_verifications');
    }
}
