<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeductionSlabsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deduction_slabs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('deduction_id')->unsigned();
            $table->integer('slab_number');
            $table->double('min_amount');
            $table->double('max_amount');
            $table->string('rate');
            $table->timestamps();

            $table->foreign('deduction_id')
                ->references('id')
                ->on('deductions')
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
        Schema::drop('deduction_slabs');
    }
}
