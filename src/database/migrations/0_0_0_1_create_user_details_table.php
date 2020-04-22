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
            $table->bigIncrements('user_id');
            $table->text('about')->nullable();
            $table->date('birthdate')->nullable();
            $table->enum('gender', ['na', 'm', 'f'])->default('na')->nullable();
            $table->tinyInteger('height')->nullable()->unsigned()->comment('in cm');
            $table->tinyInteger('weight')->nullable()->unsigned()->comment('in kg');
            $table->string('religion')->nullable();
            $table->string('blood_type')->nullable();
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
