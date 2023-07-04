<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking', function (Blueprint $table) {
            $table->increments('id');
            $table->string('id_no', 30);
            $table->integer('place_id');
            $table->string('space', 100)->nullable();
            $table->string('client_id_no', 30);
            $table->integer('price_id')->nullable();
            $table->float('price', 8, 2);
            $table->integer('promocode_id')->nullable();
            $table->string('promocode', 30)->nullable();
            $table->float('discount', 8, 2)->nullable();
            $table->dateTime('booking_time');
            $table->string('duration', 50)->nullable();
            $table->dateTime('departure_time')->nullable()->comment('Set with a release token');
            $table->string('extra_time', 50)->nullable();
            $table->float('extra_fee', 8, 2);
            $table->longText('note')->nullable(); 
            $table->dateTime('created_at');
            $table->integer('created_by_id');
            $table->tinyInteger('booking_status')->default(0)->comment('0->current, 1->release');
            $table->tinyInteger('payment_status')->default(0)->comment('0->pending, 1->cash, 2->paypal');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('booking');
    }
}
