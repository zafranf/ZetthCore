<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateErrorLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('error_logs', function (Blueprint $table) {
            $table->string('code');
            $table->string('message');
            $table->string('file');
            $table->string('line');
            $table->string('path');
            $table->json('params')->nullable();
            $table->json('trace')->nullable();
            $table->json('data')->nullable();
            $table->integer('count')->unsigned()->default(0);
            $table->text('time_history')->nullable();
            $table->timestamps();

            $table->primary(['file', 'line', 'path', 'code', 'message']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('error_logs');
    }
}
