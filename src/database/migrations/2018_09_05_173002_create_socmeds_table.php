<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSocmedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('socmeds', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('name');
            $table->string('url');
            $table->string('icon');
            $table->string('color');
            $table->boolean('status')->comment('0=inactive, 1=active')->unsigned();
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
        Schema::dropIfExists('socmeds');
    }
}
