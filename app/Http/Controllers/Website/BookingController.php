<?php

namespace App\Http\Controllers\Website;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Common\SmsController AS SmsController;
use App\Models\Setting;
use App\Models\EmailHistory;
use App\Models\EmailSetting;
use App\Models\Booking;
use App\Models\BookingHistory;
use App\Models\Place;
use App\Models\Price;
use App\Models\Promocode;
use App\Models\Client;
use App\Models\ClientVehicle;
use App\Models\VehicleType;
use App\Models\SmsSetting;
use App\Models\SmsHistory; 
use Auth, DB, Validator, Mail, Image, Lang, DataTables, Hash;

/** All Paypal Details class **/
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\ExecutePayment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\Transaction;

class BookingController extends Controller
{

    private $_api_context;

    # Create a new controller instance
    public function __construct()
    {  
        if (session()->get("isLogin")){
            return redirect('/');
        }

        $setting = Setting::first();
        $client_id = $setting->paypal_client_id;
        $secret    = $setting->paypal_secret_key;
        // parent::__construct();
        $settings  = array(
            /**
            * Available option 'sandbox' or 'live'
            */
            'mode' => app()->environment() == "demo" ? "sandbox" : "live",
            /**
            * Specify the max request time in seconds
            */
            'http.ConnectionTimeOut' => 10000,
            /**
            * Whether want to log to a file
            */
            'log.LogEnabled' => true,
            /**
            * Specify the file that want to write on
            */
            'log.FileName' => storage_path() . '/logs/paypal.log',
            /**
            * Available option 'FINE', 'INFO', 'WARN' or 'ERROR'
            *
            * Logging is most verbose in the 'FINE' level and decreases as you
            * proceed towards ERROR
            */
            'log.LogLevel' => 'FINE'
        );
        /** setup PayPal api context **/ 

        $this->_api_context = new ApiContext(new OAuthTokenCredential($client_id, $secret));
        $this->_api_context->setConfig($settings);
    } 

   /*
    |____________________________________________________________
    |
    | Show the booking history list
    |____________________________________________________________
    |
    */ 
    public function history(Request $request)
    { 
        $title = Lang::label("Booking History");
        $setting = Setting::first();
        return view('website.pages.booking_history', compact('title', 'setting'));
    }

    public function historyData(Request $request)
    { 
        DB::statement(DB::raw('set @serial_no=0'));
        $bookings = Booking::select(
            DB::raw('@serial_no := @serial_no + 1 AS serial_no'),
            'booking.*',
            'place.name AS place_name'
        )
        ->leftJoin('place','place.id','=','booking.place_id')
        ->where('booking.client_id_no', session()->get("client.id_no"))
        ->orderBy("booking.id_no", "DESC")
        ->get();
 
        return DataTables::of($bookings) 
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
            ->addColumn('print', function($bookings){
                return "<button data-booking-id=\"".$bookings->id_no."\"  type=\"button\" title=\"Print Token\" class=\"printAction btn btn-sm btn-warning waves-effect\">
                    <span class=\"glyphicon glyphicon-print\"></span> 
                    ".Lang::label("Print")." 
                </button>";
            })  
            ->rawColumns(['serial_no', 'departure_time','status','print'])
            ->toJson();
    }

 

   /*
    |____________________________________________________________
    |
    | Show booking form
    |____________________________________________________________
    |
    */ 
    public function showForm()
    {    
        $title = Lang::label("New Booking");
        $setting = Setting::first();
        $vehicleTypeList = VehicleType::where('status', 1)->pluck('name', 'id');
        $licenceList = ClientVehicle::where('client_id_no', session()->get('client.id_no'))
            ->pluck('licence', 'licence');        
        $placeLocation = Place::where('status', 1)
            ->where('id', request()->get("place_id"))
            ->select('name', 'latitude', 'longitude')
            ->get();

        return view('website.pages.booking_form', compact(
            'title', 
            'setting',
            'vehicleTypeList',
            'licenceList',
            'placeLocation'
        ));
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
            'vehicle_type_id' => 'required|max:30',
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
            'vehicle_type_id' => Lang::label("Vehicle Type"),
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

        $setting     = Setting::first(); 
        if (empty($setting->paypal_client_id) || empty($setting->paypal_secret_key))
        {
            alert()->error(Lang::label("Please configure your paypal! PayPal credentials are missing in the application setting (the client id and secret key are required).")); 
            return back()
                ->withInput();  
        }


        if ($validator->fails()) 
        {
            return back()
                ->withErrors($validator)
                ->withInput(); 
        } 
        else 
        {  
            $bookingID   = $this->newTokenNo(); 
            $clientIdNo  = session()->get('client.id_no'); 
            // $title       = "$setting->title :: space $request->place_id#$request->space";
            $title       = "DIGITAL_GOODS";
            // $description = "$setting->title :: space $request->place_id#$request->space";
            $description = "DIGITAL_GOODS";
            $place_id    = $request->place_id;
            $total_price = (!empty($request->total_price)?$request->total_price:0);
            $return_url  = url("/website/booking/status");

            $postData = array(
                'id_no'           => $bookingID,
                'client_id_no'    => $clientIdNo,
                'vehicle_licence' => $request->vehicle_licence,
                'place_id'        => $request->place_id,
                'price_id'        => $request->price_id,
                'promocode_id'    => $request->promocode_id,
                'promocode'       => $request->promocode,
                'space'           => $request->space,
                'arrival_time'    => (!empty($request->arrival_time)?date('Y-m-d H:i:s', strtotime($request->arrival_time)):null),
                'departure_time'  => (!empty($request->departure_time)?date('Y-m-d H:i:s', strtotime($request->departure_time)):null),
                'release_time'    => null,
                'booking_period'    => $request->booking_period." = ".(!empty($request->net_price)?$request->net_price:"0.0")."".$setting->currency,
                'net_price'       => (!empty($request->net_price)?$request->net_price:"0.0"),
                'discount'        => (!empty($request->discount)?$request->discount:"0.0"),
                'vat'             => (!empty($request->vat)?$request->vat:"0.0"),
                'fine'            => (!empty($request->fine)?$request->fine:"0.0"),
                'total_price'     => (!empty($request->total_price)?$request->total_price:"0.0"),
                'note'            => $request->note,
                'release_note'    => $request->release_note,
                'created_at'      => date('Y-m-d H:i:s'),
                'created_by'      => 0,
                'payment_type'    => 1, // '0->not paid, 1->paid' 
                'booking_status'  => 0, // '0->current, 1->release'  
            );

            /*----------------------------------------------
            * starts payment transaction 
            *-----------------------------------------------*/
            $payer = new Payer();
            $payer->setPaymentMethod('paypal');

            $item_1 = new Item();

            $item_1->setName($title) /** item name **/
                ->setCurrency('USD')
                ->setQuantity(1)
                ->setPrice($total_price); /** unit price **/

            $item_list = new ItemList();
            $item_list->setItems(array($item_1));

            $amount = new Amount();
            $amount->setCurrency('USD')
                ->setTotal($total_price);

            $transaction = new Transaction();
            $transaction->setAmount($amount)
                ->setItemList($item_list)
                ->setDescription($description);

            $redirect_urls = new RedirectUrls();
            $redirect_urls->setReturnUrl($return_url) /** Specify return URL **/
                ->setCancelUrl($return_url);

            $payment = new Payment();
            $payment->setIntent('Sale')
                ->setPayer($payer)
                ->setRedirectUrls($redirect_urls)
                ->setTransactions(array($transaction));
                /** dd($payment->create($this->_api_context));exit; **/ 

            try {
                // dd($payment->create($this->_api_context));
                $payment->create($this->_api_context); 
            } catch (\PayPal\Exception\PPConnectionException $ex) { 
                if (\Config::get('app.debug')) {
                    alert()->error(Lang::label("Connection timeout!")); 
                    return back()
                        ->withInput();  
                } else {
                    alert()->error(Lang::label("Some error occur, sorry for inconvenient!")); 
                    return back()
                        ->withInput();  
                }
            }

            foreach($payment->getLinks() as $link) {
                if($link->getRel() == 'approval_url') {
                    $redirect_url = $link->getHref();
                    break;
                }
            }

            /** add payment ID to session **/
            session()->put('paypalPaymentID', $payment->getId());

            if(isset($redirect_url)) 
            {
                /** redirect to paypal **/
                BookingHistory::insert(array(
                    'transaction_id' => $payment->getId(),
                    'booking_id_no'  => $bookingID,
                    'client_id_no'   => $clientIdNo,
                    'amount'         => $total_price,
                    'data'           => json_encode($postData),
                    'created_at'     => date("Y-m-d H:i:s"),
                    'payment_status' => 0,
                ));

                return redirect()->away($redirect_url); 
            }
            else 
            {
                alert()->error(Lang::label("Unknown error occurred!"));
                return back()
                    ->withInput();
            }
            /*----------------------------------------------
            * ends of payment transaction 
            *-----------------------------------------------*/ 
 
        }
 
    } 


    public function paymentStatus()
    {
        /** Get the payment ID before session clear **/
        $setting    = Setting::first(); 
        $payment_id = session()->get('paypalPaymentID');
        $history = BookingHistory::where("transaction_id", $payment_id)
            ->first();
        $data = @json_decode($history->data);

        $content = []; 
        /** clear the session payment ID **/
        session()->forget('paypalPaymentID');
        if (!empty(request()->get('PayerID')) && !empty(request()->get('token')) && !empty($payment_id)) 
        {
            $payment = Payment::get($payment_id, $this->_api_context);
            /** PaymentExecution object includes information necessary **/
            /** to execute a PayPal account payment. **/
            /** The payer_id is added to the request query parameters **/
            /** when the user is redirected from paypal back to your site **/
            $execution = new PaymentExecution();
            $execution->setPayerId(request()->get('PayerID'));
            /**Execute the payment **/
            $result = $payment->execute($execution, $this->_api_context);
            /** dd($result);exit; /** DEBUG RESULT, remove it later **/
            if ($result->getState() == 'approved') { 

                // update transaction status
                BookingHistory::where("transaction_id", $payment_id)
                    ->update(["payment_status" => "1"]); 

                /** it's all right **/
                /** Here Write your database logic like that insert record or value in database if you want **/

                // store into booking table
                $booking = Booking::insert(json_decode($history->data, true)); 
                if (!$booking)
                {
                    alert()->warning(Lang::label("Payment success! but data not store into database!"));
                    return redirect('website/booking?place_id='.$data->place_id);
                }
                $content['status'] = true;
                $content['data'] = $this->invoice($data->id_no);

                // update promocode used
                if (!empty($data->promocode_id))
                {
                    Promocode::where("id", $data->promocode_id)
                        ->increment('used');
                }

                #-------------------------------------
                /* SMS Notification */
                if ($setting->sms_notification == 1)
                {
                    $smsSetting  = SmsSetting::where('status',1)->first();
                    $client = Client::where('id_no', $data->client_id_no)->first();

                    $sms = new SmsHistory;
                    $sms->sms_setting_id = $smsSetting->id;
                    $sms->client_id_no   = $data->client_id_no;
                    $sms->from        = $smsSetting->from;
                    $sms->to          = $client->mobile;
                    $sms->message     = "$setting->title. \nYour Booking ID: $data->id_no, Client ID: $data->client_id_no and Booking Time: $data->arrival_time";
                    $sms->response    = null;
                    $sms->schedule_at = null;
                    $sms->created_at  = date('Y-m-d H:i:s');
                    $sms->updated_at  = null;
                    $sms->created_by  = 0;
                    $sms->status      = 0; 
                    $sms->save();
                }
                #-------------------------------------
                
                /* EMAIL Notification */
                if ($setting->email_notification == 1)
                {
                    $client = Client::where('id_no', $data->client_id_no);
                    $emailSetting = EmailSetting::first();

                    if ($client->exists())
                    {
                        $cinfo = $client->first();
                        if ($cinfo->email)
                        { 
                            //store email information 
                            $email = new EmailHistory;
                            $email->email_setting_id = $emailSetting->id;
                            $email->client_id_no = $data->client_id_no;
                            $email->email       = $cinfo->email;
                            $email->subject     = Lang::label('New Booking');
                            $email->message     = $content["data"];
                            $email->created_at  = date('Y-m-d H:i:s');
                            $email->schedule_at = null;
                            $email->updated_at  = null;
                            $email->created_by  = 0;
                            $email->status      = 0; //top priority
                            $email->save();
                        } 
                    }
                }
                #-------------------------------------
                alert()->success(Lang::label("Payment successful"));
            }
            else
            {
                alert()->error(Lang::label("Payment failed"));
                $content['status'] = false;
                $content['data']   = Lang::label("Payment failed");
            }
        }
        else
        {
            $content['status'] = false;
            $content['data'] = Lang::label("<h4>Invalid request!</h4> payment id not found. If payment has been completed then please check the booking history");
        } 

        $title = Lang::label("Booking Status");
        return view('website.pages.booking_status', compact(
            'title', 
            'content'
        ));
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
            ->where("b.client_id_no", session()->get('client.id_no'))
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
                        ". Lang::label('Booking ID') .": $booking->id_no
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
        $arrival_time   = date("Y-m-d H:i:s", strtotime($request->arrival_time));
        $departure_time = date("Y-m-d H:i:s", strtotime("+$price->period", strtotime($arrival_time)));
        $arrival_time_tolerance   = date("Y-m-d H:i:s", strtotime("+$price->period", strtotime($arrival_time)));
        $departure_time_tolerance = date("Y-m-d H:i:s", strtotime("-30 minutes", strtotime($arrival_time)));
        #------------------------------------------------
        $setting  = Setting::first();
        #------------------------------------------------
        $total  = Place::where('id', $place_id)->first();
        $totalSpace = array();
        if (!empty($total->space)) 
        $totalSpace = explode(', ', $total->space);
        krsort($totalSpace); 
        #------------Find Booking Spaces -------------
        $booked = Booking::select(
                DB::raw("
                    GROUP_CONCAT(space SEPARATOR ', ') AS space,
                    booking_status, 
                    COUNT(*) as total
                ")
            ) 
            ->where(function($q) use($place_id, $arrival_time_tolerance, $departure_time_tolerance) {
                return $q->where('booking_status', '0')
                    ->where('departure_time', ">", $departure_time_tolerance)
                    ->where('arrival_time', "<", $arrival_time_tolerance)
                    ->where('place_id', $place_id);
            }) 
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
            $data['arrival_time'] = $arrival_time;
            $data['arrival_time_tolerance'] = $arrival_time_tolerance;
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
