<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('domain');
            $table->string('name');
            $table->string('tagline')->nullable();
            $table->string('logo')->nullable();
            $table->string('icon')->nullable();
            $table->string('description')->nullable();
            $table->string('keywords')->nullable();
            $table->integer('template_id')->unsigned()->nullable();
            $table->string('timezone')->default('Asia/Jakarta');
            $table->boolean('status')->comment('0=coming soon, 1=active, 2=maintenance')->unsigned();
            $table->dateTime('active_at');
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->string('coordinate')->comment('latitude, longitude')->nullable();
            $table->string('phone')->nullable();
            $table->string('google_analytics')->nullable();
            $table->boolean('enable_subscribe')->comment('0=no, 1=yes')->default(1);
            $table->boolean('enable_like')->comment('0=no, 1=yes')->default(1);
            $table->boolean('enable_share')->comment('0=no, 1=yes')->default(1);
            $table->boolean('enable_comment')->comment('0=no, 1=yes')->default(1);
            $table->tinyInteger('perpage')->unsigned()->default(20);
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
        Schema::dropIfExists('applications');
    }
}
