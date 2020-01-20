<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSpecialAllowancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('special_allowances', function (Blueprint $table) {
            $table->increments('id');
            $table->float('employee_id')->unsigned();
            $table->date('for_month');
            $table->integer('type');
            $table->string('name');
            $table->float('rate');
            $table->float('units');
            $table->float('total');
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
        Schema::drop('special_allowances');
    }
}
