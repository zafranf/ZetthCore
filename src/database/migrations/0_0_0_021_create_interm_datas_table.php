<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIntermDatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('interm_data', function (Blueprint $table) {
            $table->increments('id');
            $table->string('host');
            $table->string('keyword');
            $table->integer('count')->unsigned();
            $table->integer('post_id')->unsigned()->nullable()->index();
            $table->timestamps();
            $table->softDeletes();
            $table->integer('site_id')->unsigned()->default(1);

            $table->foreign('host')->references('host')->on('interms')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('post_id')->references('id')->on('posts')
                ->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('interm_data');
    }
}
