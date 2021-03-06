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
            $table->increments('id');
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('url')->nullable();
            $table->enum('url_external', ['yes', 'no'])->default('no');
            $table->string('route_name')->nullable();
            $table->string('icon')->nullable();
            $table->enum('target', ['_self', '_blank'])->default('_self');
            $table->tinyInteger('order')->unsigned()->default(1);
            $table->enum('status', ['active', 'inactive'])->default('inactive');
            $table->integer('group_id')->unsigned()->default(1)->index();
            $table->integer('parent_id')->unsigned()->nullable()->index();
            $table->enum('is_crud', ['yes', 'no'])->default('no');
            $table->enum('index', ['yes', 'no'])->default('yes');
            $table->enum('create', ['yes', 'no'])->default('no');
            $table->enum('read', ['yes', 'no'])->default('no');
            $table->enum('update', ['yes', 'no'])->default('no');
            $table->enum('delete', ['yes', 'no'])->default('no');
            $table->timestamps(6);
            $table->softDeletes('deleted_at', 6);
            $table->integer('site_id')->unsigned()->default(1);

            $table->foreign('group_id')->references('id')->on('menu_groups')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('parent_id')->references('id')->on('menus')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('site_id')->references('id')->on('sites')
                ->onUpdate('cascade')->onDelete('cascade');
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
