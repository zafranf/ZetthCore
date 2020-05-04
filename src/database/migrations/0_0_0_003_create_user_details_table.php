<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_details', function (Blueprint $table) {
            $table->integer('user_id')->unsigned()->index();
            $table->text('about')->nullable();
            $table->date('birthdate')->nullable();
            $table->enum('gender', ['m', 'f'])->nullable();
            $table->tinyInteger('height')->nullable()->unsigned()->comment('in cm');
            $table->tinyInteger('weight')->nullable()->unsigned()->comment('in kg');
            $table->string('religion')->nullable();
            $table->enum('blood_type', ['o-', 'o+', 'a-', 'a+', 'b-', 'b+', 'ab-', 'ab+'])->nullable();
            $table->text('address')->nullable();
            $table->string('street1')->nullable();
            $table->string('street2')->nullable();
            $table->string('town')->nullable();
            $table->string('zipcode')->nullable();
            $table->string('country')->default('Indonesia');
            $table->string('timezone')->default('Asia/Jakarta');
            $table->string('language')->default('id_ID');
            $table->string('phone')->nullable();
            $table->string('company')->nullable();
            $table->string('website')->nullable();
            $table->timestamps();
            $table->integer('site_id')->unsigned()->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_details');
    }
}
