<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('payroll_number'); // instead of using employee_id we
            // use payroll number to prevent duplicate keys on relationships
            $table->string('avatar');
            $table->string('identification_number');
            $table->enum('identification_type', ['National ID', 'Passport']);
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('mobile_phone', 13);
            $table->integer('payment_structure_id')->unsigned();
            $table->timestamps();
            $table->softDeletes(); // works also as termination date
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('employees');
    }
}
