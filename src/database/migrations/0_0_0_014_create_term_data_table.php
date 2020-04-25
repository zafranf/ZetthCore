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
            $table->bigInteger('term_id')->unsigned();
            $table->string('termable_type');
            $table->bigInteger('termable_id')->unsigned();

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
