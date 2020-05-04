<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('file');
            $table->enum('type', ['image', 'video', 'audio', 'file']);
            $table->string('mime');
            $table->integer('size')->unsigned()->comment('in Bytes');
            $table->string('fileable_type');
            $table->integer('fileable_id')->unsigned();
            $table->integer('created_by')->unsigned()->index();
            $table->integer('updated_by')->unsigned()->nullable();
            $table->integer('deleted_by')->unsigned()->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->integer('site_id')->unsigned()->default(1);

            $table->foreign('site_id')->references('id')->on('sites')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->index(['fileable_type', 'fileable_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('files');
    }
}
