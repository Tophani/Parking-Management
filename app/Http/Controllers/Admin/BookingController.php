<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Common\SmsController AS SmsController;
use App\Models\Setting;
use App\Models\EmailHistory;
use App\Models\EmailSetting;
use App\Models\Booking;
use App\Models\Place;
use App\Models\Price;
use App\Models\Promocode;
use App\Models\Client;
use App\Models\ClientVehicle;
use App\Models\VehicleType;
use App\Models\SmsSetting;
use App\Models\SmsHistory;
use App\Mail\Promocode as Template;
use Auth, DB, Validator, Mail, Image, Lang, DataTables, Hash;

class BookingController extends Controller
{
    # Create a new controller instance
    public function __construct()
    { 
        $this->middleware('auth');
    }

    # Show the booking list
    public function show(Request $request)
    {
        $title = Lang::label("Bookings");
        $setting = Setting::first();
        $bookings = array();
        if ($request->type == 'current')
        {
            $title .= " - ".Lang::label("Active Booking");  
        } 
        else if ($request->type == 'today')
        {
            $title .= " - ".Lang::label("Today's Booking"); 
        } 
        else if ($request->type == 'paid')
        {
            $title .= " - ".Lang::label("Paid Booking"); 
        } 
        else if ($request->type == 'not_paid')
        {
            $title .= " - ".Lang::label("Unpaid Booking"); 
        }  
        $type  = $request->type;

        return view('admin.booking.list', compact('title','type', 'setting'));
    }

    public function getData(Request $request)
    { 
        $setting = Setting::first();
        $bookings = array();
        if ($request->type == 'current')
        {
            DB::statement(DB::raw('set @serial_no=0'));
            $bookings = Booking::select(
                DB::raw('@serial_no := @serial_no + 1 AS serial_no'),
                'booking.*',
                'place.name AS place_name'
            )
            ->leftJoin('place','place.id','=','booking.place_id')
            ->where('booking_status','=','0')
            ->get();
        } 
        else if ($request->type == 'today')
        {
            DB::statement(DB::raw('set @serial_no=0'));
            $bookings =  Booking::select(
                DB::raw('@serial_no := @serial_no + 1 AS serial_no'),
                'booking.*',
                'place.name AS place_name'
            )
            ->leftJoin('place','place.id','=','booking.place_id')
            ->whereDate('arrival_time', date('Y-m-d'))
            ->get();
        } 
        else if ($request->type == 'paid')
        {
            DB::statement(DB::raw('set @serial_no=0'));
            $bookings =  Booking::select(
                DB::raw('@serial_no := @serial_no + 1 AS serial_no'),
                'booking.*',
                'place.name AS place_name'
            )
            ->leftJoin('place','place.id','=','booking.place_id')
            ->where('booking.payment_type', '=', '1')
            ->get();
        } 
        else if ($request->type == 'not_paid')
        {
            DB::statement(DB::raw('set @serial_no=0'));
            $bookings =  Booking::select(
                DB::raw('@serial_no := @serial_no + 1 AS serial_no'),
                'booking.*',
                'place.name AS place_name'
            )
            ->leftJoin('place','place.id','=','booking.place_id')
            ->where('booking.payment_type', '=', '0')
            ->get();
        } 
        else 
        { 
            DB::statement(DB::raw('set @serial_no=0'));
            $bookings = Booking::select(
                DB::raw('@serial_no := @serial_no + 1 AS serial_no'),
                'booking.*',
                'place.name AS place_name'
            )
            ->leftJoin('place','place.id','=','booking.place_id')
            ->get();
        }
 
        return DataTables::of($bookings)
            ->addColumn('place_name', function($bookings){
                return "<a href=".url('admin/place/show/'.$bookings->place_id)." target=\"_blank\">".$bookings->place_name."</a>";

            }) 
            ->addColumn('client_id_no', function($bookings){
                return "<a href=".url('admin/client/profile/'.$bookings->client_id_no)." target=\"_blank\">".$bookings->client_id_no."</a>";
            }) 
            ->addColumn('net_price', function($bookings){
                return number_format($bookings->net_price, 1);
            })
            ->addColumn('vat', function($bookings){
                return number_format($bookings->vat, 1);
            })
            ->addColumn('discount', function($bookings){
                return number_format($bookings->discount, 1);
            })
            ->addColumn('fine', function($bookings){
                return number_format($bookings->fine, 1);
            })
            ->addColumn('total_price', function($bookings) {
                return number_format($bookings->total_price, 1);
            })
            ->addColumn('departure_time', function($bookings){
                
                $data = $bookings->departure_time."<br/>";
                if (empty($bookings->fine) && $bookings->booking_status == '0')
                { 
                    $diffMin = round((strtotime(date("Y-m-d H:i:s")) - strtotime($bookings->departure_time)) / 60,2);
                    $hours = floor($diffMin / 60);
                    $minutes = $diffMin % 60;

                    $time = (!empty($hours))?"$hours hours $minutes minutes":"$minutes minutes";
                    $data .= $diffMin>30?"<span class='label label-danger'>$time late</span>":"";
                }

                return $data;
            })
            ->addColumn('status', function($bookings){
                $status = ""; 
                if($bookings->booking_status=='1'):
                    $status .= "<label class=\"label label-success\">". Lang::label('Released') ."</label>";
                else:
                    $status .= "<label class=\"label label-warning\">". Lang::label('Active') ."</label>";
                endif;
                if($bookings->payment_type=='1'):
                    $status .= "<label class=\"label label-success\">". Lang::label('Paid') ."</label>";
                else:
                    $status .= "<label class=\"label label-danger\">". Lang::label('Not Paid') ."</label>";
                endif; 
                return $status;
            }) 
            ->addColumn('action', function ($bookings) {

                $data = "<button  data-booking-id=\"".$bookings->id_no."\"  type=\"button\" title=\"Print Token\" class=\"tokenAction btn btn-sm btn-default waves-effect\">
                    <span class=\"glyphicon glyphicon-print\"></span> 
                    ".Lang::label("Token")." 
                </button>";


                if ($bookings->payment_type=="0")
                {
                    $data .= "<button type=\"button\" data-booking-id=\"".$bookings->id_no."\" title=\"Paid Now\" class=\"paidAction btn btn-sm btn-info waves-effect\">
                        <span class=\"glyphicon glyphicon-usd\"></span> 
                        ".Lang::label("Paid Now")."
                    </button>";
                }

                if ($bookings->booking_status=="0")
                { 
                    // fine button
                    if (empty($bookings->fine) && round((strtotime(date("Y-m-d H:i:s")) - strtotime($bookings->departure_time)) / 60,2) > 30)
                    {
                        $data .= "<button onclick=\"return confirm('Are you sure?')\" data-booking-id=\"".$bookings->id_no."\"  type=\"button\" title=\"Take Extra Time Payment & Fine\" class=\"fineAction btn btn-sm btn-warning waves-effect\">
                            <span class=\"glyphicon glyphicon-usd\"></span> 
                            ".Lang::label("Extra Time Payment & Fine")." 
                        </button>";
                    }

                    $data .= "<a data-booking-id=\"".$bookings->id_no."\" href=\"#\" title=\"Release Vehicle\" class=\"releaseAction btn btn-sm btn-success waves-effect\">
                        <span class=\"glyphicon glyphicon-ok-sign\"></span> 
                        ".Lang::label("Release")."
                    </a>";
                }

                $data .= "<a onclick=\"return confirm('Are you sure?')\" href='".url("admin/booking/delete/$bookings->id_no")."' class=\"btn btn-sm btn-danger waves-effect\">
                    <span class=\"glyphicon glyphicon-trash\"></span> 
                    ".Lang::label("Delete")." 
                </a>"; 

                return $data;
 
            })  
            ->rawColumns(['serial_no','place_name','client_id_no','departure_time','status','action'])
            ->toJson();
    }

   /*
    |____________________________________________________________
    |
    | Show the booking form.
    |____________________________________________________________
    |
    */ 
    public function form()
    {
        $title = Lang::label("New Booking");
        $setting   = Setting::first(); 
        $placeList = Place::where('status', 1)->pluck('name', 'id');
        $vehicleTypeList = VehicleType::where('status', 1)->pluck('name', 'id');
        $priceList = ["" => "Please Select Parking Zone & Vehicle Type"]; 
 
        return view('admin.booking.form', compact('title', 'setting', 'priceList', 'vehicleTypeList', 'placeList'));
    }


   /*
    |____________________________________________________________
    |
    | Get price list with place and vehicle_type
    |____________________________________________________________
    |
    */  
    public function getZoneAndVehicleWisePriceList(Request $request)
    {
        $place_id        = $request->place_id;
        $vehicle_type_id = $request->vehicle_type_id;

        if (empty($place_id))
        {
            return ["Please select a parking zone" => "Please select a parking zone"];
        }
        if (empty($vehicle_type_id))
        {
            return ["Please select a vehicle type" => "Please select a vehicle type"];
        }

        $prices = Price::select("id", DB::raw("CONCAT(time, ' ', CONCAT(UCASE(MID(unit,1,1)),LCASE(MID(unit,2)))) AS name"))
            ->where(function($condition) use($place_id, $vehicle_type_id) {
                $condition->where('status', 1);
                $condition->where('place_id', $place_id);
                $condition->where('vehicle_type_id', $vehicle_type_id);
            })
            ->pluck('name', 'id'); 

        return $prices;
    }


   /*
    |____________________________________________________________
    |
    | Create a new Booking
    |____________________________________________________________
    |
    */ 
    public function placeOrder(Request $request)
    {    
        $validator = Validator::make($request->all(), [ 
            'place_id'       => 'required|max:11',
            'client_id_no'   => 'required|max:30',
            'vehicle_licence' => 'required|max:30',
            'price_id'       => 'required|max:11',
            'promocode_id'   => 'max:11',
            'arrival_time'   => 'required|date',
            'departure_time' => 'required|date',
            'space'          => 'required|max:30',
            'promocode'      => 'max:100',
            'net_price'      => 'required|max:11',
            'discount'       => 'required|max:11',
            'vat'            => 'required|max:11',
            'total_price'    => 'required|max:11',
            'note'           => 'max:512',
        ])->setAttributeNames(array(
            'place_id'       => Lang::label("Parking Zone"),
            'client_id_no'   => Lang::label("Client ID"),
            'vehicle_licence' => Lang::label("Vehicle Licence"),
            'price_id'       => Lang::label("Net Price"),
            'promocode_id'   => Lang::label("Promo Code"),
            'arrival_time'   => Lang::label("Arrival Time"),
            'departure_time' => Lang::label("Departure Time"),
            'space'          => Lang::label("Space "),
            'promocode'      => Lang::label("Promo Code"),
            'net_price'      => Lang::label("Net Price"),
            'discount'       => Lang::label("Discount"),
            'vat'            => Lang::label("Vat"),
            'total_price'    => Lang::label("Total Price"),
            'note'           => Lang::label("Note"),
        ));    


        if ($validator->fails()) 
        {
            $data['status'] = false;
            $data['exception'] = "<ul class='list-unstyled'>"; 
            $messages = $validator->messages();
            foreach ($messages->all('<li>:message</li>') as $message)
            {
                $data['exception'] .= $message; 
            }
            $data['exception'] .= "</ul>"; 
        } 
        else 
        {  
            $setting   = Setting::first(); 
            $bookingID = $this->newTokenNo(); 

            $booking = new Booking; 
            $booking->id_no        = $bookingID;
            $booking->client_id_no = $request->client_id_no;
            $booking->vehicle_licence = $request->vehicle_licence;
            $booking->place_id     = $request->place_id;
            $booking->price_id     = $request->price_id;
            $booking->promocode_id = $request->promocode_id;
            $booking->promocode    = $request->promocode;
            $booking->space        = $request->space;
            $booking->arrival_time = (!empty($request->arrival_time)?date('Y-m-d H:i:s', strtotime($request->arrival_time)):null);
            $booking->departure_time = (!empty($request->departure_time)?date('Y-m-d H:i:s', strtotime($request->departure_time)):null);  
            $booking->release_time = null;  
            $booking->booking_period = $request->booking_period." = ".(!empty($request->net_price)?$request->net_price:"0.0")."".$setting->currency;  
            $booking->net_price    = (!empty($request->net_price)?$request->net_price:"0.0");
            $booking->discount     = (!empty($request->discount)?$request->discount:"0.0");
            $booking->vat          = (!empty($request->vat)?$request->vat:"0.0");
            $booking->fine         = (!empty($request->fine)?$request->fine:"0.0");
            $booking->total_price  = (!empty($request->total_price)?$request->total_price:"0.0");
            $booking->note         = $request->note;
            $booking->release_note = $request->release_note;
            $booking->created_at   = date('Y-m-d H:i:s');
            $booking->created_by   = Auth::User()->id;
            $booking->payment_type   = $request->payment_type?$request->payment_type:0; // '0->not paid, 1->paid',
            $booking->booking_status = 0; // '0->current, 1->release',

            if ($booking->save()) 
            { 
                // update promocode used
                if (!empty($request->promocode_id))
                {
                    Promocode::where("id", $request->promocode_id)
                        ->increment('used');
                }

                $data['status'] = true;
                $data['result'] = $this->invoice($bookingID);

                #-------------------------------------
                /* SMS Notification */
                if ($setting->sms_notification == 1)
                {
                    $smsSetting  = SmsSetting::where('status',1)->first();
                    $client = Client::where('id_no', $request->client_id_no)->first();

                    $sms = new SmsHistory;
                    $sms->sms_setting_id = $smsSetting->id;
                    $sms->client_id_no   = $request->client_id_no;
                    $sms->from        = $smsSetting->from;
                    $sms->to          = $client->mobile;
                    $sms->message     = "$setting->title. \nYour Booking ID: $bookingID, Client ID: $request->client_id_no and Booking Time: $request->booking_time";
                    $sms->response    = null;
                    $sms->schedule_at = null;
                    $sms->created_at  = date('Y-m-d H:i:s');
                    $sms->updated_at  = null;
                    $sms->created_by  = Auth::user()->id;
                    $sms->status      = 0; 
                    $sms->save();
                }
                #-------------------------------------
                
                /* EMAIL Notification */
                if ($setting->email_notification == 1)
                {
                    $client = Client::where('id_no', $request->client_id_no);
                    $emailSetting = EmailSetting::first();

                    if ($client->exists())
                    {
                        $cinfo = $client->first();
                        if ($cinfo->email)
                        { 
                            //store email information 
                            $email = new EmailHistory;
                            $email->email_setting_id = $emailSetting->id;
                            $email->client_id_no = $cinfo->id_no;
                            $email->email       = $cinfo->email;
                            $email->subject     = Lang::label('New Booking');
                            $email->message     = $data['result'];
                            $email->created_at  = date('Y-m-d H:i:s');
                            $email->schedule_at = null;
                            $email->updated_at  = null;
                            $email->created_by  = Auth::user()->id;
                            $email->status      = 0; //top priority
                            $email->save();
                        } 
                    }
                }
                #-------------------------------------
            } 
            else 
            {
                $data['status'] = false; 
                $data['exception'] = Lang::label('Please Try Again...');
            }
        }

        return response()->json($data); 
    } 


   /*
    |____________________________________________________________
    |
    | Show invoice of a booking
    |____________________________________________________________
    |
    */
    public function invoice($bookingID = "")
    {
        $bookingID = (!empty($bookingID)?$bookingID:(request()->get("id_no")));

        $setting = Setting::first();
        $booking = DB::table("booking AS b")
            ->select("b.*", "pl.name AS place_name", "c.name AS client_name")
            ->leftJoin("place AS pl", "pl.id", "=", "b.place_id")
            ->leftJoin("price AS p", "p.id", "=", "b.price_id")
            ->leftJoin("client AS c", "c.id_no", "=", "b.client_id_no")
            ->where("b.id_no", $bookingID)
            ->first();


        $diffMin = round((strtotime($booking->departure_time) - strtotime($booking->arrival_time)) / 60,2);
        $hours = floor($diffMin / 60);
        $minutes = $diffMin % 60;
        $estimatedTime = (($hours>0)?("$hours Hours "):null).(($minutes>0)?("$minutes Minutes"):null);

        $data = "<table style=\"width:100%;font-size:15px\">
            <tbody>
                <tr>
                    <td style=\"width:65%;text-align:left;border-bottom:1px dashed gray;padding-bottom:5px;text-transform:uppercase\">
                        $setting->title :: $booking->place_name<br/>
                        $setting->address<br/><br/>
                    </td>
                    <td style=\"width:35%;text-align:right;border-bottom:1px dashed gray;padding-bottom:5px;\">
                        <font style=\"text-transform:uppercase\">".date('d M, Y h:i A')."</font><br>
                        ". Lang::label('Phone').": $setting->phone<br/>
                        ". Lang::label('Email').": $setting->email
                    </td>
                </tr>
                <tr>
                    <td style=\"text-align:left;padding:5px 0;border-bottom:1px dashed gray;text-transform:uppercase\">
                        ". Lang::label('Booking ID') ." - $booking->id_no
                    </td>
                    <td style=\"text-align:right;padding:5px 0;border-bottom:1px dashed gray;text-transform:uppercase\">
                        $booking->client_name - $booking->client_id_no ($booking->vehicle_licence)
                    </td>
                </tr>
            </tbody>
        </table>

        <table style=\"width:100%;font-size:15px\">
            <tbody>
                <tr>
                    <td style=\"width:15%\">". Lang::label('Space') .": $booking->space </td>
                    <td style=\"width:2%\">,</td>
                    <td style=\"width:25%\">". Lang::label('Promo code') .": ". (!empty($booking->promocode)?$booking->promocode:null) ."</td>
                    <td colspan=\"3\" style=\"text-align:right;border-bottom:1px dashed gray\">". Lang::label('Booking Period') ."/". Lang::label('Price') ." $booking->booking_period 
                    </td>
                </tr>
                <tr>
                    <td>". Lang::label('Booking Time')." </td>
                    <td>:</td> 
                    <td>".date('d M, Y h:i A', strtotime($booking->created_at))."</td>
                    <td style=\"text-align:right\">". Lang::label('Net Price') ." $estimatedTime</td>
                    <td>&nbsp;=&nbsp;</td>
                    <td style=\"width:50px;text-align:right\">".number_format($booking->net_price, 1)."$setting->currency</td>
                </tr>
                <tr>
                    <td>". Lang::label('Arrival Time')."</td>
                    <td>:</td>
                    <td>".date('d M, Y h:i A', strtotime($booking->arrival_time))."</td>
                    <td style=\"text-align:right\">". Lang::label('Discount') ."</td>
                    <td>&nbsp;=&nbsp;</td>
                    <td style=\"text-align:right\">".number_format($booking->discount, 1)."$setting->currency</td>
                </tr>
                <tr>
                    <td>". Lang::label('Departure Time')."</td> 
                    <td>:</td>
                    <td>".date('d M, Y h:i A', strtotime($booking->departure_time))."</td>
                    <td style=\"text-align:right\">". Lang::label('Vat') ." (". (($setting->vat_type==1)?($setting->vat . '%  of ' . Lang::label('Net Price')) : ($setting->vat . ' ' . $setting->currency)) .")</td>
                    <td>&nbsp;=&nbsp;</td>
                    <td style=\"text-align:right\">".number_format($booking->vat, 1)."$setting->currency</td>
                </tr>
                <tr>
                    <td>". Lang::label('Release Time')."</td>
                    <td>:</td>
                    <td>".(!empty($booking->release_time)?(date('d M, Y h:i A', strtotime($booking->release_time))):null)."</td>
                    <td style=\"text-align:right;border-bottom:1px dashed gray;padding-bottom:5px;\">
                        ". Lang::label('Fine') ." (". $setting->fine . (($setting->fine_type==1)?'% of '. Lang::label('Net Price'):$setting->currency) .")</td>
                    <td style=\"border-bottom:1px dashed gray;padding-bottom:5px;\">&nbsp;=&nbsp;</td>
                    <td style=\"border-bottom:1px dashed gray;padding-bottom:5px;text-align:right\">".number_format($booking->fine, 1)."$setting->currency</td>
                </tr>
                <tr>
                    <td>". Lang::label('Status')."</td>
                    <td>:</td>
                    <td>".(($booking->payment_type==1)?(Lang::label('Paid')):(Lang::label('Not Paid'))) ." & ". (($booking->booking_status==1)?(Lang::label('Release')):(Lang::label('Active')))."</td>
                    <td style=\"text-align:right\">". Lang::label('Grand Total') ."</td>
                    <td>&nbsp;=&nbsp;</td>
                    <td style=\"text-align:right\">".number_format($booking->total_price, 1)."$setting->currency</td>
                </tr>
            </tbody>
        </table>";

        return $data;
    }


   /*
    |____________________________________________________________
    |
    | Paid a booking by id_no
    |____________________________________________________________
    |
    */
    public function paid(Request $request)
    {
        // request by id_no
        $client = Booking::where('id_no', $request->id_no)->update([
            'payment_type' => '1'
        ]);

        if ($client)
        {
            // return invoice
            return $this->invoice($request->id_no);
        } 
        else 
        {
            return Lang::label('Please Try Again...');
        }
    } 


   /*
    |____________________________________________________________
    |
    | Release a booking by id_no
    |____________________________________________________________
    |
    */
    public function release(Request $request)
    {
        // request by id_no
        $booking = Booking::where('id_no', $request->id_no)->update([
            'release_time' => date("Y-m-d H:i:s"),
            'booking_status' => '1'
        ]);

        if ($booking)
        {
            // return invoice
            return $this->invoice($request->id_no);
        } 
        else 
        {
            return Lang::label('Please Try Again...');
        }
    } 


   /*
    |____________________________________________________________
    |
    | generate fine for late departure time by id_no
    FIX FINE ISSUE
    |____________________________________________________________
    |
    */
    public function fine(Request $request)
    {
        $setting = Setting::first();
        $booking = Booking::where("id_no", $request->id_no)->first();

        if ($booking->booking_status == '0' && empty($bookings->fine))
        { 

            // calculate booked minutes and price
            $bookedMin = round((strtotime($booking->departure_time) - strtotime($booking->arrival_time)) / 60,2); 
            $perMinPrice = ($booking->net_price/$bookedMin); 

            // calculate total minutes and price
            $DEPARTURE_TIME = date("Y-m-d H:i:s");
            $totalMin = round((strtotime($DEPARTURE_TIME) - strtotime($booking->arrival_time)) / 60,2);
            $NET_PRICE   = floatval($totalMin*$perMinPrice); 
 
            // calculate vat
            $VAT  = floatval(($setting->vat_type==1)?(($setting->vat / 100) * $NET_PRICE):$setting->vat);
            
            // calculate fine
            $FINE  = floatval(($setting->fine_type==1)?(($setting->fine / 100) * $NET_PRICE):$setting->fine);
            
            // calculate discount
            $DISCOUNT = floatval($booking->discount);

            // calculate grand total
            $TOTAL_PRICE = floatval($NET_PRICE+$VAT-$DISCOUNT+$FINE);
 
            Booking::where("id_no", $request->id_no)->update(array(
                'departure_time' => $DEPARTURE_TIME,
                'net_price'      => number_format($NET_PRICE, 1),
                'vat'            => number_format($VAT, 1),
                'fine'           => number_format($FINE, 1),
                'total_price'    => number_format($TOTAL_PRICE, 1),
                'payment_type'   => 1
            ));
        } 

        return $this->invoice($request->id_no);
    } 


   /*
    |____________________________________________________________
    |
    | Delete booking data by id_no
    |____________________________________________________________
    |
    */
    public function delete(Request $request)
    {
        // request by id_no
        $delete = Booking::where('id_no', $request->id_no)->delete();

        if ($delete)
        {
            alert()->success(Lang::label("Delete Successful!"));
            return back();
        } 
        else 
        {
            alert()->error(Lang::label("Please Try Again."));
            return back();
        } 
    } 

   /*
    |____________________________________________________________
    |
    | Get schedule/space and price
    |____________________________________________________________
    |
    */
    public function findScheduleAndPrice(Request $request)
    {
        $place_id        = $request->place_id; 
        $arrival_time    = $request->arrival_time;
        $price_id        = $request->price_id;
        #------------------------------------------------
        $price = Price::select("price", DB::raw("CONCAT(time, ' ', unit) AS period"))
            ->where('id', $price_id)
            ->first(); 
        #------------------------------------------------
        $arrival_time    = $request->arrival_time;
        $departure_time = date("Y-m-d H:i:s", strtotime("+$price->period", strtotime($arrival_time)));
        #------------------------------------------------
        $setting  = Setting::first();
        #------------------------------------------------
        $total  = Place::where('id', $place_id)->first();
        $totalSpace = array();
        if (!empty($total->space)) 
        $totalSpace = explode(', ', $total->space);
        krsort($totalSpace); 
        #------------Find Booking-------------
        $booked = Booking::select(
                DB::raw("
                    GROUP_CONCAT(space SEPARATOR ', ') AS space,
                    booking_status, 
                    COUNT(*) as total
                ")
            )->where('booking_status', '=', '0')
            ->where('place_id', $place_id)
            ->groupBy('booking_status')
            ->first();

        $bookedSpace = array();
        if (!empty($booked->space)) 
        $bookedSpace = explode(', ', $booked->space);
        krsort($bookedSpace);
        #------------------end find shcedule-------------------
        $showSpace = "";
        if (count($totalSpace) > 0)
        {
            foreach ($totalSpace as $available) 
            {
                $available = str_replace(',', '', $available);

                if (in_array($available, $bookedSpace))
                {
                    $showSpace .= "<div class=\"res-field occupied\"><span class=\"title\">$available</span><i class=\"icon material-icons\">directions_car</i></div>";
                } 
                else
                {
                    $showSpace .= "<div class=\"res-field res-slot\"><span class=\"title\">$available</span><i class=\"icon material-icons\">directions_car</i></div>";
                }
            }

            $data['booking_status'] = true;
            $data['departure_time'] = $departure_time;
            $data['price']  = number_format($price->price, 1);
            $data['vat']    = number_format((($setting->vat_type==1)?(($setting->vat / 100) * $price->price):$setting->vat), 1);
            $data['spaces'] = trim($showSpace);
        } 
        else 
        {
            $data['booking_status'] = false;
            $data['departure_time'] = "";
            $data['price']  = "0.0";
            $data['vat']    = "0.0";
            $data['spaces'] = "No space found! please set space in parking zone!";
        }
        #---------------------------------------------------------#
        return response()->json($data);
    }

   /*
    |____________________________________________________________
    |
    | Get price list by id
    |____________________________________________________________
    |
    */
    public function getPriceList(Request $request)
    { 
        $price_list = DB::table("price")
            ->select("price.*", DB::raw("vehicle_type.name AS vehicle_type"))
            ->leftJoin("vehicle_type", "vehicle_type.id", "=", "price.vehicle_type_id")
            ->where('price.place_id', $request->place_id)
            ->get();

        return response()->json($price_list);
    }

   /*
    |____________________________________________________________
    |
    | Get discount by promocode
    |____________________________________________________________
    |
    */
    public function getDiscount(Request $request)
    {
        $setting = Setting::first();
        $promocode = Promocode::where('promocode', $request->promocode)
            ->where('status', 1)
            ->whereColumn('used', '<=', 'limit') 
            ->whereDate('start_date', '<=', date('Y-m-d', strtotime($request->arrival_time)))
            ->whereDate('end_date', '>=', date('Y-m-d', strtotime($request->arrival_time)))
            ->first();

        if (!empty($promocode))
        {
            $data['status']   = true;
            $data['promocode_id'] = $promocode->id;
            $data['discount'] = number_format($promocode->discount, 1);
            $data['success'] = "Your discount amount $promocode->discount$setting->currency";
        } 
        else 
        {
            $data['status']    = false;
            $data['promocode_id'] = "";
            $data['discount']  = "0.0";
            $data['exception'] = "No discount available!";
        }

        return response()->json($data);
    }


   /*
    |____________________________________________________________
    |
    | Check the client is valid or not
    |____________________________________________________________
    |
    */
    public function checkClientID(Request $request)
    {
        $client = Client::where('id_no', $request->client_id_no)->first();
        if ($client)
        {
            $vehicles = ClientVehicle::select('licence')
                ->where('status', 1)
                ->where('client_id_no', $request->client_id_no)
                ->get();
                        
            $data['status'] = true;
            $data['name']   = $client->name;
            $data['vehicles'] = "";
            foreach ($vehicles as $v) {
                $data['vehicles'] .= "<option value=\"$v->licence\">$v->licence</option>";
            }
        }
        else 
        {
            $data['status']    = false;
            $data['exception'] = "No client found!";
        }
        return response()->json($data);
    }


    /*
    |____________________________________________________________
    |
    | Create new client
    |____________________________________________________________
    |
    */
    public function createClient(Request $request)
    {  
        $validator = Validator::make($request->all(), [ 
            'name'    => 'required|max:50',
            'mobile'  => 'required|max:20',
            'email'   => 'required|max:100|unique:client,email',
            'password'  => 'required|min:5',
            'address' => 'max:255',
            'licence' => 'required|max:100',
            'photo'   => 'mimes:jpeg,jpg,png,gif|max:10000', 
            'note'    => 'max:1000',
        ]);  


        if (!empty($request->photo)) {
            $filePath = 'public/assets/images/client/'.md5(time()) .'.jpg';
            Image::make($request->photo)->resize(300, 200)->save($filePath);
        } else {
            $filePath = $request->old_photo;
        }  

        if ($validator->fails()) {
            $data['status'] = false;
            $data['exception'] = "<ul class='list-unstyled'>"; 
            $messages = $validator->messages();
            foreach ($messages->all('<li>:message</li>') as $message)
            {
                $data['exception'] .= $message; 
            }
            $data['exception'] .= "</ul>"; 
        } 
        else 
        { 
            $id_no  = $this->randStr();

            //Save Client Info
            $client = new Client;
            $client->id_no       = $id_no;
            $client->name        = $request->name;
            $client->mobile      = $request->mobile;
            $client->password    = Hash::make($request->password);
            $client->email       = $request->email;
            $client->address     = $request->address;  
            $client->created_by   = Auth::User()->id;
            $client->created_at  = date('Y-m-d H:i:s');
            $client->status      = ($request->status?1:0);

            if ($client->save()) 
            { 
                // Save Vehicle Info
                $vehicle = new ClientVehicle;
                $vehicle->client_id_no = $id_no; 
                $vehicle->licence = $request->licence;  
                $vehicle->photo   = $filePath;
                $vehicle->color   = $request->color; 
                $vehicle->note    = $request->note; 
                $vehicle->created_at = date('Y-m-d H:i:s');
                $vehicle->save();

                $data['status']      = true;
                $data['client_name']  = $request->name; 
                $data['client_id_no'] = $id_no; 
                $data['vehicles'] = "<option value=\"$request->licence\">$request->licence</option>"; 
            } 
            else 
            {
                $data['status']    = false;
                $data['exception'] = Lang::label('Please try again!');
            } 
        }
        return response()->json($data);
    }

    /*
    |____________________________________________________________
    |
    | Extras
    |____________________________________________________________
    |
    */

    # Generates random string 
    private function randStr()
    {  
        $lastTokenNo = Client::orderBy('id_no','desc') 
                        ->value('id_no');

        $lastTokenNo = (!empty($lastTokenNo)?$lastTokenNo:'A0000');

        $letter = $lastTokenNo[0];
        $number = $lastTokenNo[1].$lastTokenNo[2].$lastTokenNo[3].$lastTokenNo[4];

        if ($number < 9999) {
            $newTokenNo = $letter.sprintf("%04d", (int)$number+1);
        } else {
            $ascii = ord($letter);
            $newLetter = chr($ascii+1);
            $newTokenNo = $newLetter.'0001';
        }
        return $newTokenNo;     
    } 


    public function newTokenNo()
    {  
        $lastTokenNo = Booking::orderBy('id_no','desc') 
                        ->value('id_no');

        $lastTokenNo = (!empty($lastTokenNo)?$lastTokenNo:'A000000');

        $letter = $lastTokenNo[0];
        $number = $lastTokenNo[1].$lastTokenNo[2].$lastTokenNo[3].$lastTokenNo[4].$lastTokenNo[5].$lastTokenNo[6];

        if ($number < 999999) {
            $newTokenNo = $letter.sprintf("%06d", $number+1);
        } else {
            $ascii = ord($letter);
            $newLetter = chr($ascii+1);
            $newTokenNo = $newLetter.'000001';
        }
        return $newTokenNo;     
    } 

}
