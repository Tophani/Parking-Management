<?php
namespace App\Http\Controllers\Operator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Client;
use Auth, DB, Lang;

class DashboardController extends Controller
{ 
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    # Show the application dashboard. 
    public function index()
    {
    	$title = Lang::label('Home');
        $reportCounter = $this->bookingCount();
        $clientCounter = $this->clientCount();
        $booking = $this->reportThisYearBooking();
        $client  = $this->reportThisYearClient();
        $amount  = $this->reportThisYearAmount();
        $messages  = $this->recentMessage();
        return view('operator.dashboard.home', compact(
            'title', 
            'reportCounter', 
            'clientCounter', 
            'booking', 
            'client', 
            'amount', 
            'messages' 
        ));
    }

    # Report Counter
    public function bookingCount()
    {
        # Count Total 
        $date = date('Y-m-d');
        return Booking::select(
            DB::raw(" 
                COUNT(CASE WHEN payment_type = '0' THEN id END) AS not_paid,
                COUNT(CASE WHEN payment_type = '1' THEN id END) AS paid,
                COUNT(CASE WHEN booking_status = '0' THEN id END) AS current,
                COUNT(CASE WHEN booking_status = '1' THEN id END) AS releases,
                COUNT(CASE WHEN DATE(arrival_time) = '$date' THEN id END) AS todays_booking,
                SUM(net_price + fine + vat - discount) AS amount,
                COUNT(*) AS total
            ") 
        )
        ->where('place_id', Auth::user()->place_id)
        ->first();     
    }

    public function clientCount()
    { 
        return Client::select(
            DB::raw(" 
                COUNT(DISTINCT(id_no)) AS total_client
            ") 
        )
        ->where('created_by', Auth::user()->id)
        ->first();     
    }
   
    # This Year Booking
    public function reportThisYearBooking()
    {
        # Count Total 
        return Booking::select(
            DB::raw(" 
                COUNT(CASE WHEN MONTH(arrival_time) = '01' THEN id END) AS jan,
                COUNT(CASE WHEN MONTH(arrival_time) = '02' THEN id END) AS feb,
                COUNT(CASE WHEN MONTH(arrival_time) = '03' THEN id END) AS mar,
                COUNT(CASE WHEN MONTH(arrival_time) = '04' THEN id END) AS apr,
                COUNT(CASE WHEN MONTH(arrival_time) = '05' THEN id END) AS may,
                COUNT(CASE WHEN MONTH(arrival_time) = '06' THEN id END) AS jun,
                COUNT(CASE WHEN MONTH(arrival_time) = '07' THEN id END) AS jul,
                COUNT(CASE WHEN MONTH(arrival_time) = '08' THEN id END) AS aug,
                COUNT(CASE WHEN MONTH(arrival_time) = '09' THEN id END) AS sep,
                COUNT(CASE WHEN MONTH(arrival_time) = '10' THEN id END) AS oct,
                COUNT(CASE WHEN MONTH(arrival_time) = '11' THEN id END) AS nov,
                COUNT(CASE WHEN MONTH(arrival_time) = '12' THEN id END) AS decx 
            ")
        )
        ->where(DB::raw('YEAR(arrival_time)'), date('Y')) 
        // ->whereIn('place_id', Auth::user()->place_id)
        ->whereIn('place_id', Auth::user()->place_id())
        ->first();   
    }

    # This Year Client
    public function reportThisYearClient()
    {
        return Client::select(
            DB::raw(" 
                COUNT(DISTINCT(CASE WHEN MONTH(created_at) = '01' THEN id_no END)) AS jan,
                COUNT(DISTINCT(CASE WHEN MONTH(created_at) = '02' THEN id_no END)) AS feb,
                COUNT(DISTINCT(CASE WHEN MONTH(created_at) = '03' THEN id_no END)) AS mar,
                COUNT(DISTINCT(CASE WHEN MONTH(created_at) = '04' THEN id_no END)) AS apr,
                COUNT(DISTINCT(CASE WHEN MONTH(created_at) = '05' THEN id_no END)) AS may,
                COUNT(DISTINCT(CASE WHEN MONTH(created_at) = '06' THEN id_no END)) AS jun,
                COUNT(DISTINCT(CASE WHEN MONTH(created_at) = '07' THEN id_no END)) AS jul,
                COUNT(DISTINCT(CASE WHEN MONTH(created_at) = '08' THEN id_no END)) AS aug,
                COUNT(DISTINCT(CASE WHEN MONTH(created_at) = '09' THEN id_no END)) AS sep,
                COUNT(DISTINCT(CASE WHEN MONTH(created_at) = '10' THEN id_no END)) AS oct,
                COUNT(DISTINCT(CASE WHEN MONTH(created_at) = '11' THEN id_no END)) AS nov,
                COUNT(DISTINCT(CASE WHEN MONTH(created_at) = '12' THEN id_no END)) AS decx 
            ")
        )
        ->where(DB::raw('YEAR(created_at)'), date('Y'))
        ->where('created_by', Auth::user()->id)
        ->first();   
    }

    # This Year Client
    public function reportThisYearAmount()
    {
        return Booking::select(
            DB::raw(" 
                SUM(CASE WHEN MONTH(arrival_time) = '01' THEN net_price + fine + vat - discount END) AS jan,
                SUM(CASE WHEN MONTH(arrival_time) = '02' THEN net_price + fine + vat - discount END) AS feb,
                SUM(CASE WHEN MONTH(arrival_time) = '03' THEN net_price + fine + vat - discount END) AS mar,
                SUM(CASE WHEN MONTH(arrival_time) = '04' THEN net_price + fine + vat - discount END) AS apr,
                SUM(CASE WHEN MONTH(arrival_time) = '05' THEN net_price + fine + vat - discount END) AS may,
                SUM(CASE WHEN MONTH(arrival_time) = '06' THEN net_price + fine + vat - discount END) AS jun,
                SUM(CASE WHEN MONTH(arrival_time) = '07' THEN net_price + fine + vat - discount END) AS jul,
                SUM(CASE WHEN MONTH(arrival_time) = '08' THEN net_price + fine + vat - discount END) AS aug,
                SUM(CASE WHEN MONTH(arrival_time) = '09' THEN net_price + fine + vat - discount END) AS sep,
                SUM(CASE WHEN MONTH(arrival_time) = '10' THEN net_price + fine + vat - discount END) AS oct,
                SUM(CASE WHEN MONTH(arrival_time) = '11' THEN net_price + fine + vat - discount END) AS nov,
                SUM(CASE WHEN MONTH(arrival_time) = '12' THEN net_price + fine + vat - discount END) AS decx 
            ")
        )
        ->where(DB::raw('YEAR(arrival_time)'), date('Y'))
        ->whereIn('place_id', Auth::user()->place_id())
        ->first();   
    }
 
    # Recent Message
    public function recentMessage()
    {
        return DB::table('message')->select([ 
                'users.user_role',
                'users.photo', 
                'users.name AS sender',
                'message.id AS id',
                'message.subject AS subject',
                'message.message AS message',
                'message.datetime AS date',
                'message.receiver_status AS receiver_status',
            ])
            ->where('message.receiver_id', Auth::id())
            ->where('message.receiver_status', 0)
            ->whereNotIn('message.receiver_status', [2])
            ->leftJoin('users', 'users.id', '=', 'message.sender_id')
            ->orderBy('message.id', 'desc')
            ->limit(25) 
            ->get(); 
    }
 
}
