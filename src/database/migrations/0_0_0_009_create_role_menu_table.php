<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoleMenuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role_menu', function (Blueprint $table) {
            $table->bigInteger('role_id')->unsigned();
            $table->bigInteger('menu_group_id')->unsigned();
            $table->bigInteger('site_id')->unsigned()->default(1);

            $table->foreign('role_id')->references('id')->on('roles')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('menu_group_id')->references('id')->on('menu_groups')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['role_id', 'menu_group_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('role_menu');
    }
}
