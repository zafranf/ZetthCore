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
            $table->increments('id')->unsigned();
            $table->string('title')->index();
            $table->string('slug')->index();
            $table->longText('content');
            $table->text('excerpt')->nullable();
            $table->string('cover')->nullable();
            $table->enum('type', ['article', 'page', 'video'])->default('article');
            $table->boolean('share')->unsigned()->default(0);
            $table->boolean('like')->unsigned()->default(0);
            $table->boolean('comment')->unsigned()->default(0);
            $table->integer('visited')->unsigned()->default(0);
            $table->integer('shared')->unsigned()->default(0);
            $table->integer('liked')->unsigned()->default(0);
            $table->timestamp('published_at')->nullable();
            $table->string('short_url')->nullable();
            $table->boolean('status')->comment('0=pending, 1=active, 2=draft')->unsigned();
            $table->integer('created_by')->unsigned()->index();
            $table->integer('updated_by')->unsigned()->nullable();
            $table->integer('deleted_by')->unsigned()->nullable();
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
