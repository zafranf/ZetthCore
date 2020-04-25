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
            $table->bigIncrements('id');
            $table->string('title')->index();
            $table->string('slug')->index();
            $table->text('content');
            $table->string('excerpt')->nullable();
            $table->string('cover')->nullable();
            $table->enum('type', ['article', 'page', 'video'])->default('article');
            $table->enum('share', ['yes', 'no'])->default('no');
            $table->enum('like', ['yes', 'no'])->default('no');
            $table->enum('comment', ['yes', 'no'])->default('no');
            $table->integer('visited')->unsigned()->default(0);
            $table->integer('shared')->unsigned()->default(0);
            $table->integer('liked')->unsigned()->default(0);
            $table->integer('disliked')->unsigned()->default(0);
            $table->string('short_url')->nullable();
            $table->enum('status', ['active', 'inactive', 'draft'])->default('inactive');
            $table->bigInteger('created_by')->unsigned()->index();
            $table->bigInteger('updated_by')->unsigned()->nullable();
            $table->bigInteger('deleted_by')->unsigned()->nullable();
            $table->timestamp('published_at')->nullable();
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
        Schema::dropIfExists('posts');
    }
}
