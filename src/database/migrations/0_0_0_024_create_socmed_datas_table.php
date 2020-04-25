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
            $table->bigIncrements('id');
            $table->string('username');
            $table->bigInteger('socmed_id')->unsigned();
            $table->string('socmedable_type');
            $table->bigInteger('socmedable_id')->unsigned();
            $table->timestamps();
            $table->softDeletes();
            $table->bigInteger('site_id')->unsigned()->default(1);
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
