<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeductionPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deduction_payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('deduction_id')->unsigned()->index();
            $table->bigInteger('employee_id')->unsigned()->index();
            $table->string('deduction_number');
            $table->double('amount');
            $table->date('for_month');
            $table->timestamps();

            $table->foreign('employee_id')
                ->references('id')
                ->on('employees')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('deduction_id')
                ->references('id')
                ->on('deductions')
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
        Schema::drop('deduction_payments');
    }
}
