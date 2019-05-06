<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSocmedDatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('socmed_data', function (Blueprint $table) {
            $table->string('username');
            $table->enum('type', ['config', 'user'])->default('user');
            $table->integer('socmed_id')->unsigned();
            $table->integer('data_id')->unsigned()->nullable()->index();
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
        Schema::dropIfExists('socmed_data');
    }
}
