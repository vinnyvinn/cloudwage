<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReliefsTable extends Migration
{
    // https://laravel.com/docs/5.2/eloquent-relationships#polymorphic-relations
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reliefs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('reliefable_id')->unsigned();
            $table->enum('reliefable_type', ['Deduction', 'Allowance']);
            $table->double('amount')->nullable();
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
        Schema::drop('reliefs');
    }
}
