<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVisitorLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('visitor_logs', function (Blueprint $table) {
            // $table->bigIncrements('id');
            $table->ipAddress('ip');
            $table->string('page');
            $table->string('referral')->nullable();
            $table->string('agent');
            $table->string('browser');
            $table->string('browser_version');
            $table->string('device');
            $table->string('device_name');
            $table->string('os');
            $table->string('os_version');
            $table->boolean('is_robot');
            $table->string('robot_name')->nullable();
            $table->integer('count')->unsigned()->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('visitor_logs');
    }
}
