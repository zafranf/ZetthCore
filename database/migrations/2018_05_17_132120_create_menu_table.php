<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('url')->nullable();
            $table->boolean('url_external')->comment('0=false, 1=true')->unsigned()->default(0);
            $table->string('route_name')->nullable();
            $table->string('icon')->nullable();
            $table->enum('target', ['_self', '_blank'])->default('_self');
            $table->tinyInteger('order')->unsigned()->default(1);
            $table->boolean('status')->comment('0=inactive, 1=active')->unsigned();
            $table->integer('group_id')->unsigned()->default(1);
            $table->integer('parent_id')->unsigned()->default(0);
            $table->boolean('is_crud')->unsigned()->default(1);
            $table->boolean('index')->unsigned()->default(0);
            $table->boolean('create')->unsigned()->default(0);
            $table->boolean('read')->unsigned()->default(0);
            $table->boolean('update')->unsigned()->default(0);
            $table->boolean('delete')->unsigned()->default(0);
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
        Schema::dropIfExists('menus');
    }
}
