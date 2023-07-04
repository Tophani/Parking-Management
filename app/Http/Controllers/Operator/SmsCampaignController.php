<?php

namespace App\Http\Controllers\Operator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Common\SmsController AS SmsController;
use App\Models\Setting;
use App\Models\SmsSetting;
use App\Models\SmsHistory;
use Auth, DB, Lang, Validator, Str;

class SmsCampaignController extends Controller
{
    # Create a new controller instance
    public function __construct()
    {
        $this->middleware(['auth']);
    }


    # Show the email list
    public function show()
    {
        $title = Lang::label('SMS History');
        return view('operator.campaign_sms.list', compact('title'));
    } 

    public function getData(Request $request)
    { 
        DB::statement(DB::raw('set @serial_no=0'));
        $sms = DB::table('sms_history')->select([
            DB::raw('@serial_no  := @serial_no  + 1 AS serial_no'),
            'id',  
            'to',  
            'message',  
            'created_at',  
            'updated_at',  
            'status'   
        ]) 
        ->orderBy('id', 'asc')
        ->where('created_by', Auth::user()->id)
        ->get();
 
 
        $datatables = datatables()
            ->of($sms)  
            ->addColumn('status', function ($sms) {
                if ($sms->status == 1)
                {
                    return "<span class='label label-success'>Success</span>";
                }
                else
                {
                    return "<span class='label label-danger'>Pending</span>";
                }
            })  
            ->rawColumns(['message', 'status'])
            ->setTotalRecords(count($sms)); 

        return $datatables->make(true); 
    }
 

    # SMS form
    public function form()
    {
    	$title = Lang::label("New SMS");
    	$sms_setting = SmsSetting::first();
        return view('operator.campaign_sms.form', compact('title', 'sms_setting'));
    }

    # Send a mail
    public function send(Request $request)
    {
        $validator = Validator::make($request->all(), [ 
            'to'        => 'required|max:100',
            'message'   => 'required|max:255',
        ]);  

        if ($validator->fails()) 
        {
            return back()
                ->withErrors($validator)
                ->withInput(); 
        } 
        else 
        {  
        	$setting  = SmsSetting::where('status',1)->first();
            $response = (new SmsController)
	            ->provider("$setting->provider")
	            ->api_key("$setting->api_key")
	            ->username("$setting->username")
	            ->password("$setting->password")
	            ->from("$setting->from")
	            ->to("$request->to")
	            ->message("$request->message")
	            ->response();
 
            //store sms information 
            $sms = new SmsHistory;
            $sms->sms_setting_id = $setting->id;
            $sms->client_id_no   = null;
            $sms->from        = $setting->from;
            $sms->to          = $request->to;
            $sms->message     = $request->message;
            $sms->response    = $response;
            $sms->schedule_at = null;
            $sms->created_at  = date('Y-m-d H:i:s');
            $sms->updated_at  = null;
            $sms->created_by  = Auth::user()->id;
            $sms->status      = 1; 

            if ($sms->save()) 
            {
                alert()->success(Lang::label("SMS Sent!"));
                return back()
                    ->withInput();
            } else {
                alert()->error(Lang::label('Please Try Again.'));
                return back()
                    ->withInput()
                    ->withErrors($validator);
            }
        } 
    } 

}
