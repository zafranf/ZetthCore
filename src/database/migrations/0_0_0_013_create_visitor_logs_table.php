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
            $table->ipAddress('ip')->nullable();
            $table->string('page');
            $table->string('referral')->nullable();
            $table->string('agent')->nullable();
            $table->string('browser')->nullable();
            $table->string('browser_version')->nullable();
            $table->string('device')->nullable();
            $table->string('device_name')->nullable();
            $table->string('os')->nullable();
            $table->string('os_version')->nullable();
            $table->enum('is_robot', ['yes', 'no'])->default('no');
            $table->string('robot_name')->nullable();
            $table->integer('count')->unsigned()->default(0);
            $table->timestamps(6);
            $table->integer('site_id')->unsigned()->default(1);

            $table->foreign('site_id')->references('id')->on('sites')
                ->onUpdate('cascade')->onDelete('cascade');

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
