<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUDFsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('udfs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('udfable_id')->unsigned();
            $table->string('udfable_type');
            $table->string('field_title');
            $table->string('field_name');
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
        Schema::drop('udfs');
    }
}
