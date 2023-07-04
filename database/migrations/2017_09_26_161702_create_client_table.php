<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client', function (Blueprint $table) {
            $table->increments('id');
            $table->string('id_no', 20);
            $table->string('name', 100);
            $table->string('mobile', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('address',255)->nullable();
            $table->string('vehicle_licence', 100)->nullable();
            $table->string('vehicle_photo',100)->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
            $table->tinyInteger('status')->default('1');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('client');
    }
}
