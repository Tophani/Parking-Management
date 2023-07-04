<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Config, DB;

class MailConfigServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        if (\Schema::hasTable('email_setting')) {
            $mail = DB::table('email_setting')->first();
            if (is_object($mail) && count(get_object_vars($mail)) > 0) 
            {  
                $config = array(
                    'driver'     => $mail->driver,
                    'host'       => $mail->host,
                    'port'       => $mail->port,
                    'encryption' => $mail->encryption,
                    'username'   => $mail->username,
                    'password'   => $mail->password,
                    'sendmail'   => $mail->sendmail,
                    'pretend'    => $mail->pretend,
                );
                Config::set('mail', $config);
            }
        }
    }
}
