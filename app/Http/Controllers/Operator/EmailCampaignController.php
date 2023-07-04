<?php

namespace App\Http\Controllers\Operator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\EmailSetting;
use App\Models\EmailHistory;
use App\Models\Setting;
use App\Mail\Promocode;
use Auth, Validator, Mail, Lang, DB, Str;

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
        return view('operator.campaign_email.list', compact('title', 'emails'));
    }

    public function getData(Request $request)
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
        ->where('created_by', Auth::user()->id)
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
            ->rawColumns([ 'status'])
            ->setTotalRecords(count($campaigns)); 

        return $datatables->make(true); 
    }

    # Email form
    public function form()
    {
    	$title = Lang::label("New Email");
    	$email_setting = EmailSetting::first();
        return view('operator.campaign_email.form', compact('title', 'email_setting'));
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
            ->queue(new Promocode(array(
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
            $email->status      = 1;

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

}
