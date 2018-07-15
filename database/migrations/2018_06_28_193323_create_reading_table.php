<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReadingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('readings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('customer_id');
            $table->string('serial_number');
            $table->enum('power_type', ['MPAN', 'MPRN']);
            $table->string('mpxn');
            $table->json('read');
            $table->dateTime('read_date');
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
        Schema::drop('readings');
    }
}
