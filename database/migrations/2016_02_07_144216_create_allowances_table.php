<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAllowancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('allowances', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->boolean('non_cash');
            $table->integer('currency_id')->unsigned();
            $table->string('type');
            $table->string('rate');
            $table->boolean('in_basic');
            $table->boolean('taxable')->default(true);
            $table->double('tax_rate')->nullable();
            $table->boolean('has_relief')->default(false);
            $table->boolean('system_install')->default(false);
            $table->timestamps();

            $table->foreign('currency_id')
                ->references('id')
                ->on('currencies')
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
        Schema::drop('allowances');
    }
}
