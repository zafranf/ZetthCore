<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTermDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('term_data', function (Blueprint $table) {
            $table->integer('term_id')->unsigned();
            $table->string('termable_type');
            $table->integer('termable_id')->unsigned();
            $table->integer('site_id')->unsigned()->default(1);

            $table->primary(['term_id', 'termable_type', 'termable_id', 'site_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('term_data');
    }
}
