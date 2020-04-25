<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sites', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('domain');
            $table->string('name');
            $table->string('tagline')->nullable();
            $table->string('logo')->nullable();
            $table->string('icon')->nullable();
            $table->string('description')->nullable();
            $table->string('keywords')->nullable();
            $table->bigInteger('template_id')->unsigned()->nullable();
            $table->string('timezone')->default('Asia/Jakarta');
            $table->string('language')->default('id');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('coordinate')->comment('latitude, longitude')->nullable();
            $table->string('google_analytics')->nullable();
            $table->enum('enable_subscribe', ['yes', 'no'])->default('yes');
            $table->enum('enable_like', ['yes', 'no'])->default('yes');
            $table->enum('enable_share', ['yes', 'no'])->default('yes');
            $table->enum('enable_comment', ['yes', 'no'])->default('yes');
            $table->tinyInteger('perpage')->unsigned()->default(10);
            $table->enum('status', ['active', 'comingsoon', 'maintenance', 'suspend'])->default('comingsoon');
            $table->dateTime('active_at');
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
        Schema::dropIfExists('sites');
    }
}
