<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('message', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sender_id');
            $table->integer('receiver_id');
            $table->string('subject', 255);
            $table->longText('message')->nullable(); 
            $table->dateTime('datetime');
            $table->tinyInteger('sender_status')->default(0)->comment('0->Unseen, 1->Seen, 2->Delete');
            $table->tinyInteger('receiver_status')->default(0)->comment('0->Unseen, 1->Seen, 2->Delete');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('message');
    }
}
