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
            $table->string('id');
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
            $table->enum('is_robot', ['yes', 'no'])->default('no');
            $table->string('robot_name')->nullable();
            $table->bigInteger('count')->unsigned()->default(0);
            $table->timestamps();
            $table->bigInteger('site_id')->unsigned()->default(1);

            $table->primary(['id', 'site_id']);
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
