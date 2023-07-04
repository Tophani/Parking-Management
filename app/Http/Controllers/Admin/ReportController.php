<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\Booking;
use App\Models\Place;
use DB, Lang;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    # Reports
    public function index(Request $request)
    {
        $title = Lang::label("Reports");
        $setting = Setting::first();
        $placeList = Place::where('status', 1)->pluck('name', 'id');

        $input = (object)array(
            'start_date'   => (!empty($request->start_date)?date('Y-m-d', strtotime($request->start_date)):null),
            'end_date'     => (!empty($request->end_date)?date('Y-m-d', strtotime($request->end_date)):null),
            'booking_type' => $request->booking_type,
            'place_id'     => $request->place_id,
            'filter_type'  => $request->filter_type,
            'search'       => $request->search,
        );

        $bookings = array(); 
        $bookings = Booking::select('booking.*','place.name AS place_name')
            ->where(function($query) use ($input)
            {
                # filter by date to date
                if (!empty($input->start_date) && !empty($input->end_date))
                {
                    $query->whereBetween( DB::raw('DATE(`arrival_time`)'), array($input->start_date, $input->end_date));
                }

                # fileter by bookin type
                switch ($input->booking_type) 
                {
                    case 'active':
                        $query->where('booking.booking_status','=','0');
                        break;
                    case 'today':
                        $query->whereDate('booking.arrival_time', date('Y-m-d'));
                        break;
                    case 'paid':
                        $query->where('booking.payment_type', '=', '1');
                        break;
                    case 'not_paid':
                        $query->where('booking.payment_type', '=', '0');
                        break; 
                }

                # filter by place id
                if (!empty($input->place_id))
                {
                    $query->where('place.id', '=', $input->place_id);
                }

                # filter by filter_type 
                if (!empty($input->filter_type) && !empty($input->search))
                { 
                    switch ($input->filter_type) 
                    {
                        case 'booking_id':
                            $query->where('booking.id_no','=',$input->search);
                            break;
                        case 'client_id':
                            $query->where('booking.client_id_no','=',$input->search);
                            break;
                        case 'space':
                            $query->where('booking.space','=',$input->search);
                            break; 
                    }
                }


            })
            ->leftJoin('client','client.id_no','=','booking.client_id_no')
            ->leftJoin('place','place.id','=','booking.place_id')
            ->paginate(25);

        return view('admin.booking.report', compact('title', 'bookings', 'setting', 'placeList', 'input'));
    }

}
