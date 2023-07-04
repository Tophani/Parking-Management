<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
	public $timestamps = false;

	protected $table = "setting";

    protected $fillable = [
        'title',
        'about',
        'description',
        'email',
        'phone',
        'address',
        'favicon',
        'logo',
        'map_api_key',
        'latitude',
        'longitude',
        'map_zoom',
        'currency',
        'vat',
        'vat_type',
        'fine',
        'fine_type',
        'sms_notification',
        'email_notification',
        'sms_alert',
        'slider_1',
        'slider_2',
        'slider_3',
        'footer',
        'website',
        'paypal_client_id',
        'paypal_secret_key'
    ];

}
