<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLikesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('likes', function (Blueprint $table) {
            $table->integer('user_id')->unsigned();
            $table->enum('like', ['yes', 'no'])->default('no');
            $table->string('likeable_type');
            $table->integer('likeable_id')->unsigned();
            $table->integer('site_id')->unsigned()->default(1);

            $table->primary(['user_id', 'likeable_type', 'likeable_id', 'site_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('likes');
    }
}
