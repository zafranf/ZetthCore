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
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('email');
            $table->string('site')->nullable();
            $table->string('phone')->nullable();
            $table->text('content');
            $table->enum('notify', ['yes', 'no'])->default('no');
            $table->enum('read', ['yes', 'no'])->default('no');
            $table->enum('status', ['active', 'inactive'])->default('inactive');
            $table->enum('is_owner', ['yes', 'no'])->default('no');
            $table->bigInteger('parent_id')->unsigned()->nullable();
            $table->string('commentable_type');
            $table->bigInteger('commentable_id')->unsigned();
            $table->bigInteger('approved_by')->unsigned()->nullable();
            $table->bigInteger('created_by')->unsigned()->nullable();
            $table->bigInteger('updated_by')->unsigned()->nullable();
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
