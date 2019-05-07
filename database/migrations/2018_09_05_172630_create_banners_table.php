<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBannersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('title');
            $table->string('description')->nullable();
            $table->string('image')->nullable();
            $table->string('url')->nullable();
            $table->boolean('url_external')->comment('0=false, 1=true')->unsigned();
            $table->enum('target', ['_self', '_blank'])->default('_self');
            $table->tinyInteger('order')->default(1)->unsigned();
            $table->boolean('only_image')->comment('0=false, 1=true')->unsigned()->default(0);
            $table->boolean('status')->comment('0=inactive, 1=active')->unsigned();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('banners');
    }
}
