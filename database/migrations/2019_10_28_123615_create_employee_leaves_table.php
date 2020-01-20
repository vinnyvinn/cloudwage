<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeeLeavesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        if (DB::getDefaultConnection() == 'mysql') {
            return;
        }
        Schema::create('employee_leaves', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('employee_id')->unsigned()->index();
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('days');
            $table->string('status');
            $table->timestamps();
            $table->foreign('employee_id')->references('id')->on('employees');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        if (DB::getDefaultConnection() == 'mysql') {
            return;
        }
        Schema::dropIfExists('employee_leaves');
    }

}
