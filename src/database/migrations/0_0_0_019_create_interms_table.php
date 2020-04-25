<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIntermsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('interms', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('host')->index();
            $table->string('param');
            $table->enum('status', ['active', 'inactive'])->default('inactive');
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
        Schema::dropIfExists('interms');
    }
}
