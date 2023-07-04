<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mail\Promocode;
use App\Models\EmailSetting;
use App\Models\EmailHistory;
use App\Models\Setting; 
use Auth, Validator, Mail, Lang, DB, Str;
use Illuminate\Support\Facades\Log;

class EmailCampaignController extends Controller
{
    # Create a new controller instance
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    # Show the email list
    public function show()
    {
        $title = Lang::label('Email History'); 
        $emails = EmailHistory::all();
        return view('admin.campaign_email.list', compact('title', 'emails'));
    }

    public function getCampaignData(Request $request)
    { 
        DB::statement(DB::raw('set @rownum=0'));
        $campaigns = DB::table('email_history')->select([
            DB::raw('@rownum  := @rownum  + 1 AS rownum'),
            'id',
            'email',
            'subject',
            'message',
            'created_at',
            'status',
        ]) 
        ->orderBy('id', 'asc')
          ->get();
 
 
        $datatables = datatables()
            ->of($campaigns)
            ->addColumn('message', function ($campaign) {
                return Str::words($campaign->message, 8,'....'); 
            })
            ->addColumn('status', function ($campaign) {
                if ($campaign->status == 1)
                {
                    return "<span class='label label-success'>Success</span>";
                }
                else
                {
                    return "<span class='label label-danger'>Pending</span>";
                }
            })
            ->addColumn('action', function ($campaign) {
                return '<a  onclick="return confirm(\'Are you sure?\')" href="'. url("admin/email/delete/$campaign->id") .'" class="btn btn-xs btn-danger waves-effect"><i class="material-icons">delete</i></a></a>';
            }) 
            ->rawColumns(['action', 'status'])
            ->setTotalRecords(count($campaigns)); 

        return $datatables->make(true); 
    }

    # Email form
    public function form()
    {
    	$title = Lang::label("New Email");
    	$email_setting = EmailSetting::first();
        return view('admin.campaign_email.form', compact('title', 'email_setting'));
    }

    # Send a mail
    public function send(Request $request)
    {
        $validator = Validator::make($request->all(), [ 
            'email_setting_id' => 'required|max:11',
            'email'       => 'required|max:100',
            'subject'     => 'required|max:255',
        ]);  

        if ($validator->fails()) 
        {
            return back()
                    ->withErrors($validator)
                    ->withInput();
        } 
        else 
        { 
            //send mail
            $app = Setting::first();
 
            Mail::to($request->email)
            ->send(new Promocode(array(
                'url'     => url(''), 
                'title'   => $app->title,
                'footer'  => $app->footer,
                'from'    => [
                    'address' => $app->email, 
                    'name'    => $app->title 
                ],
                'subject' => $request->subject,
                'message' => $request->message
            )));
 
            //store email information 
            $email = new EmailHistory;
            $email->email_setting_id = $request->email_setting_id;
            $email->client_id_no = null;
            $email->email       = $request->email;
            $email->subject     = $request->subject;
            $email->message     = $request->message;
            $email->created_at  = date('Y-m-d H:i:s');
            $email->updated_at  = null;
            $email->created_by  = Auth::user()->id;
            $email->status      = 1; //sent

            if( count(Mail::failures()) > 0 )
            {
                $email->status  = 0;
            }

            if ($email->save()) 
            {
                alert()->success(Lang::label("Mail Sent!"));
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


    # Email form
    public function bulk()
    {
        $title = Lang::label("New Email");
        return view('admin.campaign_email.bulk', compact('title'));
    }

        # Send a mail
    public function sendBulk(Request $request)
    {
        $validator = Validator::make($request->all(), [ 
            'email'       => 'required|max:100',
            'subject'     => 'required|max:255',
        ]);  

        if ($validator->fails()) 
        {
            return back()
                    ->withErrors($validator)
                    ->withInput();
        } 
        else 
        { 
            //send mail
            $app = EmailSetting::first(); 

            $emails = explode(',', $request->email);
            if (is_array($emails) && sizeof($emails)>0)
            {
                foreach ($emails as $email) 
                {
                    //store email information 
                    $email = new EmailHistory;
                    $email->email_setting_id = $app->email_setting_id;
                    $email->client_id_no = null;
                    $email->email       = $email;
                    $email->subject     = $request->subject;
                    $email->message     = $request->message;
                    $email->created_at  = date('Y-m-d H:i:s');
                    $email->updated_at  = null;
                    $email->created_by  = Auth::user()->id;
                    $email->status      = 0;
                    $email->save();
                }
            }

            alert()->success(Lang::label("Mail Sent!"));
            return back()->withInput(); 
        } 
    }


    # Show the email setting form
    public function setting(Request $request)
    {
        $setting = EmailSetting::first(); 
        if (!$setting) 
        {
            $data = new EmailSetting;
            $data->driver     = 'smtp';
            $data->host       = 'mailtrap.io';
            $data->port       = 2525;
            $data->username   = '89955d8d34c48e';
            $data->password   = '097ce508ab4744';
            $data->encryption = 'tls';
            $data->sendmail   = 'usr/sbin/sendmail -bs';
            $data->pretend    = false;
            $data->save();
        }

        $title = Lang::label('Email Setting'); 
        return view('admin.campaign_email.setting', compact('title', 'setting'));
    }
 

    # Update email setting
    public function updateSetting(Request $request)
    {  
        $validator = Validator::make($request->all(), [ 
            'driver'     => 'required|max:255',
            'host'       => 'required|max:255',
            'port'       => 'required|max:50',
            'username'   => 'required|max:100', 
            'password'   => 'required|max:100', 
            'encryption' => 'required|max:50', 
            'sendmail'   => 'required|max:100', 
            'pretend'    => 'required|max:50', 
        ]); 

        if ($validator->fails()) {
            alert()->error(Lang::label('Please Try Again.'));
            return back()
                ->withErrors($validator)
                ->withInput();
        } else { 

            $setting = EmailSetting::find($request->id);

            $setting->driver     = $request->driver;
            $setting->host       = $request->host;
            $setting->port       = $request->port;
            $setting->username   = $request->username;
            $setting->password   = $request->password;
            $setting->encryption = $request->encryption;
            $setting->sendmail   = $request->sendmail;
            $setting->pretend    = $request->pretend ;

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


    # Delete admin data by id_no
    public function delete(Request $request)
    {
        // request by id_no
        $campaign = EmailHistory::where('id', $request->id)->delete();

        if ($campaign)
        {
            alert()->success(Lang::label("Delete Successful!"));
            return back();
        } else {
            alert()->error(Lang::label('Please Try Again.'));
            return back();
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
            $setting      = Setting::first();
            /*
            * First priority status = 2
            * Schedule Time Wise
            *---------------------------------------------------
            */
            $from = date('Y-m-d H:i:s', strtotime("-$setting->sms_alert minute")); //-5 minutes
            $to   = date('Y-m-d H:i:s', strtotime("+$setting->sms_alert minute")); //+5 minutes

            $alert = DB::table("email_history")
                    ->where("status", 0) 
                    ->orWhere(function($query) use($from, $to) { 
                        return $query->where('status', 2)
                            ->whereBetween('schedule_at', [$from, $to]); 
                    })
                    ->orderBy('status', 'DESC')
                    ->limit(10)
                    ->get();


            foreach ($alert as $value) 
            { 
                Mail::to($value->email)
                ->queue(new Promocode(array(
                    'url'     => url(''), 
                    'title'   => $setting->title,
                    'footer'  => $setting->footer,
                    'from'    => [
                        'address' => $setting->email, 
                        'name'    => $setting->title 
                    ],
                    'subject' => $value->subject,
                    'message' => $value->message
                )));

                //store email information 
                $email = EmailHistory::find($value->id);
                $email->updated_at  = date('Y-m-d H:i:s');
                $email->status      = 1;
                $email->save(); 

                Log::info("EmailCampaignController::jobs - ". "Response: ". json_encode($value));
            }  
        }
        catch(\Exception $e)
        {
            Log::info("EmailCampaignController::jobs - Error: " . $e->getMessage());
        }
    }



}
