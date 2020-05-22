<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('slug')->index();
            $table->text('content');
            $table->string('excerpt')->nullable();
            $table->string('cover')->nullable();
            $table->string('caption')->nullable();
            $table->enum('type', ['article', 'page', 'image', 'video'])->default('article');
            $table->enum('enable_share', ['yes', 'no'])->default('no');
            $table->enum('enable_like', ['yes', 'no'])->default('no');
            $table->enum('enable_comment', ['yes', 'no'])->default('no');
            $table->integer('visited')->unsigned()->default(0);
            $table->integer('shared')->unsigned()->default(0);
            $table->integer('liked')->unsigned()->default(0);
            $table->integer('disliked')->unsigned()->default(0);
            $table->string('short_url')->nullable();
            $table->enum('status', ['active', 'inactive', 'draft'])->default('inactive');
            $table->timestamp('published_at')->nullable();
            $table->integer('created_by')->unsigned()->index();
            $table->integer('updated_by')->unsigned()->nullable();
            $table->integer('deleted_by')->unsigned()->nullable();
            $table->timestamps(6);
            $table->softDeletes('deleted_at', 6);
            $table->integer('site_id')->unsigned()->default(1);

            $table->foreign('created_by')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('deleted_by')->references('id')->on('users')
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
        Schema::dropIfExists('posts');
    }
}
