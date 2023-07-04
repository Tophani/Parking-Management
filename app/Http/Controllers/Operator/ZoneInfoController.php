<?php

namespace App\Http\Controllers\Operator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Place;
use App\Models\Setting;
use Auth, Lang, DB;

class ZoneInfoController extends Controller
{    

	public function __construct()
    {
        $this->middleware(['auth']);
    }

    # Parking Zone Info
    public function parking_zone()
    {
        $title = Lang::label('Parking Zone');
        # place info
        $places = Place::whereIn("id", auth()->user()->place_id())->get(); 

        return view('operator.parking_zone.list', compact('title', 'places'));
    }  

    # Parking Zone Details
    public function parkingZoneDetails(Request $request)
    {
        $title = Lang::label('Parking Zone');
        # place info
        $place = Place::whereIn("id", auth()->user()->place_id())
            ->where("id", $request->id)
            ->first();
        # application setting
        $setting = Setting::first();
        # price history
        $prices = DB::table('price')->select([
            'price.id', 
            'place.name AS place_name',
            'vehicle_type.name AS vehicle_type',
            'price.time',
            'price.unit',
            'price.price',
            'price.status', 
        ])->orderBy('price.id', 'asc')
          ->leftJoin('place', 'place.id', '=', 'price.place_id')
          ->leftJoin('vehicle_type', 'vehicle_type.id', '=', 'price.vehicle_type_id')
          ->whereIn('price.place_id', Auth::user()->place_id()) 
          ->where("price.place_id", $request->id)
          ->get();

        return view('operator.parking_zone.show', compact('title', 'place', 'prices', 'setting'));
    }  
}
