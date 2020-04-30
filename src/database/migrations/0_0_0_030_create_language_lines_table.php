<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLanguageLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('language_lines', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('group')->index();
            $table->string('key');
            $table->text('text');
            $table->timestamps();
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
        Schema::drop('language_lines');
    }
}
