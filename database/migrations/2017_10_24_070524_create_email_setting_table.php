<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmailSettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_setting', function (Blueprint $table) {
            $table->increments('id');
            $table->string('driver')->default('smtp')->comment('smtp, mailgun or mailtrap');
            $table->string('host')->default('mailtrap.io');
            $table->string('port')->default('2525');
            $table->string('username')->nullable();
            $table->string('password')->nullable();
            $table->string('encryption')->default('tls');
            $table->string('mailpath')->nullable()->default('usr/sbin/sendmail -bs')->comment("The server path to Sendmail."); 
            $table->string('pretend')->default('false');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('email_setting');
    }
}
