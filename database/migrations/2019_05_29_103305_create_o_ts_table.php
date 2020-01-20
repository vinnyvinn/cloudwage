<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOTsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('o_ts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('employee_id')->unsigned();
            $table->date('payroll_date');
            $table->double('ot_1_rate');
            $table->double('ot_1_hrs');
            $table->double('ot_2_rate');
            $table->double('ot_2_hrs');
            $table->double('ot_1_amount');
            $table->double('ot_2_amount');
            $table->double('amount');
            $table->integer('finalized')->default(0);
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
        Schema::drop('o_ts');
    }
}
