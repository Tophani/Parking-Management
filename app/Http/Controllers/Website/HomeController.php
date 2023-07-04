<?php

namespace App\Http\Controllers\Website;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Setting; 
use App\Models\Booking;
use App\Models\Place;
use App\Models\Price;  
use App\Models\VehicleType;  
use App\Models\Client;
use App\Models\ClientVehicle;
use App\Models\EmailHistory;  
use App\Mail\Promocode;
use DB, Lang, Mail, Validator, Hash;

class HomeController extends Controller
{    
    # Show the booking form. 
    public function index()
    {
        $setting   = Setting::first();
        
        if (!$setting->website_enable)
        {
            redirect("login")->send();
        }

        $title = Lang::label("Website"); 

        $lots = $this->parkingLotListWithSpaces();

        $placeList = Place::where('status', 1)
            ->pluck('name', DB::raw('CONCAT(id ,",", latitude, "," ,longitude) AS id'));

        $placeLocation = Place::where('status', 1)
	        ->select('name', 'latitude', 'longitude')
	        ->get();

        return view('website.pages.home', compact(
        	'title', 
        	'setting', 
            'lots', 
        	'placeList', 
            'placeLocation'
        ));
    } 
 

   /*
    |____________________________________________________________
    |
    | Get schedule/space and price
    |____________________________________________________________
    |
    */
    public function parkingLotListWithSpaces()
    {
        $lots = DB::table("place AS p")
            ->select(
                'p.id',
                'p.name',
                DB::raw('CONCAT(p.id ,",", p.latitude, "," ,p.longitude) AS geolocation'),
                'p.limit',
                DB::raw('CASE WHEN COUNT(pr.place_id)>0 THEN "Yes" ELSE "No" END AS is_price')
            )
            ->leftJoin("price AS pr", "pr.place_id", "=", "p.id")
            ->where('p.status', 1)
            ->groupBy("p.id")
            ->get();

        $data = array();
        $occupied = 0;
        $total_space  = 0;
        $available    = 0;

        foreach($lots as $lot)
        {  
            $occupied = Booking::where('booking_status', '=', '0')
                ->where('place_id', $lot->id)
                ->groupBy('booking_status')
                ->count();
 
            $data[] = (object)array(
                'place_id'    => $lot->id,
                'name'        => $lot->name,
                'geolocation' => $lot->geolocation,
                'total_space' => $lot->limit,
                'is_price'    => $lot->is_price,
                'occupied'    => $occupied,
                'available'   => ($lot->limit-$occupied),
            ); 
        }
 
        return $data;
    }

   /*
    |____________________________________________________________
    |
    | Get price list by id
    |____________________________________________________________
    |
    */
    public function getPrices(Request $request)
    { 
        $data = DB::table("price")
            ->select("price.*", DB::raw("vehicle_type.name AS vehicle_type"))
            ->leftJoin("vehicle_type", "vehicle_type.id", "=", "price.vehicle_type_id")
            ->where('price.place_id', $request->place_id)
            ->get();

        return response()->json($data);
    } 
}
