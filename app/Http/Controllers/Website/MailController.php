<?php

namespace App\Http\Controllers\Website;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Setting; 
use App\Models\EmailHistory;  
use App\Mail\Promocode;
use Lang, Mail, Validator;

class MailController extends Controller
{    
    
    public function send(Request $request)
    {
        $validator = Validator::make($request->all(), [ 
            'email'       => 'required|max:100',
            'subject'     => 'required|max:255',
            'message'     => 'required',
        ]);  

        if ($validator->fails()) 
        {   
            $data = [
                'status' => false,
                'message' =>  "<p>".implode("<p></p>",$validator->messages()->all())."</p>",
                'data'   => 'validation error!'
            ];  
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
            $email->created_by  = null;
            $email->status      = 1; //sent

            if( count(Mail::failures()) > 0 )
            {
                $email->status  = 0;
            }

            if ($email->save()) 
            {
                $data = [
                    'status' => true,
                    'message' => Lang::label("Mail Sent!"),
                    'data'   => 'success!'
                ]; 
            } else {
                $data = [
                    'status' => false,
                    'message' => Lang::label("Please Try Again."),
                    'data'   => 'mailerror'
                ];  
            }
        } 

        return response()->json($data);
    }

}
