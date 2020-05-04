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
            $table->bigInteger('term_id')->unsigned()->index();
            $table->string('termable_type');
            $table->bigInteger('termable_id')->unsigned();
            $table->bigInteger('site_id')->unsigned()->default(1);

            $table->primary(['termable_id', 'term_id']);
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
