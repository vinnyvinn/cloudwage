<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePayGradesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pay_grades', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('payment_structure_id')->unsigned()->index();
            $table->integer('currency_id')->unsigned()->index();
            $table->double('basic_salary');
            $table->string('annual_increment');
            $table->string('default_allowances')->nullable();
            $table->string('default_deductions')->nullable();
            $table->timestamps();

            $table->foreign('currency_id')
                ->references('id')
                ->on('currencies')
                ->onDelete('cascade');

            $table->foreign('payment_structure_id')
                ->references('id')
                ->on('payment_structures')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('pay_grades');
    }
}
