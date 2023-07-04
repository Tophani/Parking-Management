<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmailHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_history', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('email_setting_id');
            $table->string('user_id');
            $table->string('email', 100);
            $table->string('subject', 255)->nullable();
            $table->text('message')->nullable();
            $table->datetime('date');
            $table->tinyInteger('status')->default('0');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('email_history');
    }
}
