<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_profiles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('registration_number')->nullable();
            $table->string('logo');
            $table->string('name');
            $table->string('branch')->nullable();
            $table->string('postal_address')->nullable();
            $table->string('city');
            $table->string('country');
            $table->string('phone')->nullable();
            $table->string('mobile')->nullable();
            $table->string('email');
            $table->string('website')->nullable();
            $table->string('kra_pin');
            $table->string('nssf')->nullable();
            $table->string('nhif')->nullable();
            $table->integer('currency_id')->unsigned();
            $table->timestamps();

            $table->foreign('currency_id')
                ->references('id')
                ->on('currencies')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('company_profiles');
    }
}
