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
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('url')->nullable();
            $table->enum('url_external', ['yes', 'no'])->default('no');
            $table->string('route_name')->nullable();
            $table->string('icon')->nullable();
            $table->enum('target', ['_self', '_blank'])->default('_self');
            $table->tinyInteger('order')->unsigned()->default(1);
            $table->enum('status', ['active', 'inactive'])->default('inactive');
            $table->bigInteger('group_id')->unsigned()->default(1);
            $table->bigInteger('parent_id')->unsigned()->nullable();
            $table->enum('is_crud', ['yes', 'no'])->default('no');
            $table->enum('index', ['yes', 'no'])->default('yes');
            $table->enum('create', ['yes', 'no'])->default('no');
            $table->enum('read', ['yes', 'no'])->default('no');
            $table->enum('update', ['yes', 'no'])->default('no');
            $table->enum('delete', ['yes', 'no'])->default('no');
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
