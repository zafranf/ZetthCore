<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTermsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('terms', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('slug');
            $table->string('description')->nullable();
            $table->enum('type', ['tag', 'category'])->default('category');
            $table->enum('group', ['post', 'product'])->default('post');
            $table->integer('parent_id')->unsigned()->nullable()->index();
            $table->enum('status', ['active', 'inactive'])->default('inactive');
            $table->timestamps();
            $table->softDeletes();
            $table->integer('site_id')->unsigned()->default(1);

            $table->foreign('parent_id')->references('id')->on('terms')
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
        Schema::dropIfExists('terms');
    }
}
