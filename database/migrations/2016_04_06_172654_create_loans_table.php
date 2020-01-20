<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('employee_id')->unsigned()->index();
            $table->string('type');
            $table->date('date_processed');
            $table->double('amount');
            $table->double('amount_payable');
            $table->double('balance');
            $table->double('duration');
            $table->double('rate');
            $table->double('installments');
            $table->integer('payment_months_made');
            $table->double('low_benefit')->default(0);
            $table->double('fringe_benefit')->default(0);
            $table->date('deadline');
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
        Schema::drop('loans');
    }
}
