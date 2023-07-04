<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class BookingHistory
 * @package App
 */
class BookingHistory extends Model
{
    /**
     * @var string
     */
    protected $table = 'booking_history';

    /**
     * @var array
     */
    protected $dates = ['deleted_at'];


    /**
     * @var array
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = ['transaction_id', 'amount', 'data', 'payment_status'];
}