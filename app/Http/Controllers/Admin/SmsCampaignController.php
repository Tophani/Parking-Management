<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Common\SmsController AS SmsController;
use App\Models\Setting;
use App\Models\SmsSetting;
use App\Models\SmsHistory;
use Auth, DB, Lang, Validator, Str;
use Exception;
use Illuminate\Support\Facades\Log;

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
        return view('admin.campaign_sms.list', compact('title'));
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
            ->addColumn('action', function ($sms) {
                return '<a  onclick="return confirm(\'Are you sure?\')" href="'. url("admin/sms/delete/$sms->id") .'" class="btn btn-xs btn-danger waves-effect"><i class="material-icons">delete</i></a></a>';
            }) 
            ->rawColumns(['action', 'message', 'status'])
            ->setTotalRecords(count($sms)); 

        return $datatables->make(true); 
    }
 

    # SMS form
    public function form()
    {
    	$title = Lang::label("New SMS");
    	$sms_setting = SmsSetting::first();
        return view('admin.campaign_sms.form', compact('title', 'sms_setting'));
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

    # Delete admin data by id_no
    public function delete(Request $request)
    {
        // request by id_no
        $sms = SmsHistory::where('id', $request->id)->delete();

        if ($sms)
        {
            alert()->success(Lang::label("Delete Successful!"));
            return back();
        } else {
            alert()->error(Lang::label('Please Try Again.'));
            return back();
        }
    } 


    # Show the email setting form
    public function setting(Request $request)
    {
        $setting = SmsSetting::first(); 
        if (!$setting) 
        {
            $data = new SmsSetting;
            $data->provider = 'nexmo';
            $data->api_key  = '';
            $data->username = '';
            $data->password = '';
            $data->save();
        }

        $title = Lang::label('SMS Setting'); 
        return view('admin.campaign_sms.setting', compact('title', 'setting'));
    }


    # Update sms setting
    public function updateSetting(Request $request)
    {  
        $validator = Validator::make($request->all(), [ 
            'provider' => 'required|max:20',
            'api_key'  => 'required|max:255',
            'username' => 'required|max:255',
            'password' => 'required|max:255', 
            'from'     => 'required|max:20', 
        ]); 

        if ($validator->fails()) {
            alert()->error(Lang::label('Please Try Again.'));
            return back()
                ->withErrors($validator)
                ->withInput();
        } else { 
            $setting = SmsSetting::find($request->id);
            $setting->provider = $request->provider;
            $setting->api_key  = $request->api_key;
            $setting->username = $request->username;
            $setting->password = $request->password;
            $setting->from     = $request->from;

            if ($setting->save()) {
                alert()->success(Lang::label('Update Successful!'));
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

    /*
    *---------------------------------------------------------
    * CORN JOB 
    *--------------------------------------------------------- 
    */
    public function jobs()
    {
        try
        {
            $setting     = Setting::first();
            $smsSetting  = SmsSetting::where('status',1)->first();
    
            /*
            * First priority status = 2 & schedule time
            * Second priority status = 0
            *---------------------------------------------------
            */
            $from = date('Y-m-d H:i:s', strtotime("-$setting->sms_alert minute")); //-5 minutes
            $to   = date('Y-m-d H:i:s', strtotime("+$setting->sms_alert minute")); //+5 minutes
    
            $alert = DB::table("sms_history")
                    ->where("status", 0) 
                    ->orWhere(function($query) use($from, $to) { 
                        return $query->where('status', 2)
                            ->whereBetween('schedule_at', [$from, $to]); 
                    })
                    ->orderBy('schedule_at', 'DESC')
                    ->limit(10)
                    ->get();
    
            foreach ($alert as $value) 
            { 
                $response = (new SmsController)
                    ->provider("$smsSetting->provider")
                    ->api_key("$smsSetting->api_key")
                    ->username("$smsSetting->username")
                    ->password("$smsSetting->password")
                    ->from("$smsSetting->from")
                    ->to("$value->to")
                    ->message("$value->message")
                    ->response();
    
                //store sms information 
                $sms = SmsHistory::find($value->id);
                $sms->sms_setting_id = $smsSetting->id;  
                $sms->response    = $response;
                $sms->updated_at  = date('Y-m-d H:i:s');
                $sms->status      = 1; 
                $sms->save();
    
                Log::info("SmsCampaignController::jobs - Request: ". json_encode($value). "Response: ".json_encode($response));
            }  
        }
        catch(\Exception $e)
        {
            Log::info("SmsCampaignController::jobs - Error: " . $e->getMessage());
        }
    }

}
