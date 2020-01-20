<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEmployeeContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'employee_contracts',
            function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->bigInteger('employee_id')->unsigned()->index();
                $table->integer('employee_type_id')->unsigned()->index();
                $table->integer('pay_grade_id')->unsigned()->index();
                $table->dateTime('start_date');
                $table->dateTime('end_date');
                $table->integer('currency_id')->unsigned()->index();
                $table->double('current_basic_salary');
                $table->integer('times_renewed'); // increment once renewed
                $table->timestamps();
                $table->softDeletes();

                $table->foreign('employee_id')
                    ->references('id')
                    ->on('employees')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');

                $table->foreign('employee_type_id')
                    ->references('id')
                    ->on('employee_types')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');

                $table->foreign('pay_grade_id')
                    ->references('id')
                    ->on('pay_grades')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');

                $table->foreign('currency_id')
                    ->references('id')
                    ->on('currencies')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('employee_contracts');
    }
}
