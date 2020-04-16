<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email');
            $table->string('site')->nullable();
            $table->string('phone')->nullable();
            $table->text('content');
            $table->boolean('notify')->comment('0=no, 1=yes')->unsigned();
            $table->boolean('read')->comment('0=unread, 1=read')->unsigned();
            $table->boolean('status')->comment('0=pending, 1=active')->unsigned();
            $table->boolean('is_owner')->comment('0=false, 1=true')->unsigned();
            $table->integer('parent_id')->unsigned()->nullable();
            $table->string('commentable_type');
            $table->integer('commentable_id')->unsigned();
            $table->integer('approved_by')->unsigned()->nullable();
            $table->integer('created_by')->unsigned()->nullable();
            $table->integer('updated_by')->unsigned()->nullable();
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
        Schema::dropIfExists('comments');
    }
}
