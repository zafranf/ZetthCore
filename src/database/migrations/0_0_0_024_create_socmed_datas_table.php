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
            $table->increments('id');
            $table->string('username');
            $table->integer('socmed_id')->unsigned()->index();
            $table->string('socmedable_type');
            $table->integer('socmedable_id')->unsigned();
            $table->timestamps();
            $table->softDeletes();
            $table->integer('site_id')->unsigned()->default(1);

            $table->index(['socmedable_type', 'socmedable_id', 'site_id']);
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
