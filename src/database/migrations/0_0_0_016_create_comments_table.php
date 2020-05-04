<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentsTable extends Migration
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
            $table->enum('notify', ['yes', 'no'])->default('no');
            $table->enum('read', ['yes', 'no'])->default('no');
            $table->enum('status', ['active', 'inactive'])->default('inactive');
            $table->enum('is_owner', ['yes', 'no'])->default('no');
            $table->integer('parent_id')->unsigned()->nullable()->index();
            $table->string('commentable_type');
            $table->integer('commentable_id')->unsigned();
            $table->integer('approved_by')->unsigned()->nullable();
            $table->integer('created_by')->unsigned()->nullable();
            $table->integer('updated_by')->unsigned()->nullable();
            $table->integer('deleted_by')->unsigned()->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->integer('site_id')->unsigned()->default(1);

            $table->index(['commentable_type', 'commentable_id']);
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
