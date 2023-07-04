<?php

namespace App\Http\Controllers\Website;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mail\Promocode as Template;
use App\Models\Client; 
use App\Models\ClientVehicle;
use App\Models\Setting; 
use App\Models\EmailSetting;
use App\Models\EmailHistory; 
use DB, Lang, Validator, Mail, Auth, Hash, Image, Session;

class AuthController extends Controller
{    
    # Register
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [ 
            'name'        => 'required|max:50',
            'mobile'      => 'required|max:20',
            'email'       => 'required|email|unique:client,email',
            'licence'     => 'required|max:50',
            'password'    => 'min:5|required_with:confirm_password|same:confirm_password',
            'confirm_password' => 'min:5'
        ]);  

         
        if ($validator->fails()) 
        {
            $data = array(
                'status' => false,
                'message'   => "<p>".implode("</p><p>",$validator->errors()->all())."</p>",
                'data'   => null
            ); 
        } 
        else 
        { 
            $client = new Client;
            $client->id_no       = $this->randStr();
            $client->name        = $request->name;
            $client->mobile      = $request->mobile;
            $client->email       = $request->email;
            $client->password    = Hash::make($request->password);
            $client->created_by  = null;
            $client->created_at  = date('Y-m-d H:i:s');
            $client->updated_at  = null;
            $client->status      = 2; //pending

            if ($client->save()) 
            { 

                // Save Vehicle Info
                $vehicle = new ClientVehicle;
                $vehicle->client_id_no = $client->id_no; 
                $vehicle->licence = $request->licence;  
                $vehicle->created_at  = date('Y-m-d H:i:s');
                $vehicle->save();


                // Send Mail
                $app = Setting::first();
                $subject = $app->title ."::". Lang::label("Account confirmation mail");
                $message = "Hi, $request->name, \r\n Please click on the following link to confirm your account <a href='".url('website/auth/account_confirmation?token='.strrev(md5($request->email)))."' target=\"_blank\">Click Here</a>";

                try {
                    @Mail::to($request->email)
                    ->send(new Template(array(
                        'url'     => url(''), 
                        'title'   => $app->title,
                        'footer'  => $app->footer,
                        'from'    => [
                            'address' => $app->email, 
                            'name'    => $app->title 
                        ],
                        'subject' => $subject,
                        'message' => $message
                    ))); 


                    $data = array(
                        'status'  => true,
                        'message' => Lang::label("Registration successful! Please check your email inbox."),
                        'data'    => null
                    ); 

                } catch(\Exception $e) {
                    $data = array(
                        'status'  => false,
                        'message' => Lang::label("Registration successful! Please contact with author to confirm your accout."),
                        'data'    => $e
                    ); 
                }
            } 
            else 
            {
                $data = array(
                    'status'  => false,
                    'message' => Lang::label('Internal Server Error!'),
                    'data'    => null
                ); 
            }
        }

        return response()->json($data);
    }

    # Login 
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [ 
            'email'       => 'required|email',
            'password'    => 'required|min:5',
        ]);  

         
        if ($validator->fails()) 
        { 
            return response()->json([
                'status' => false,
                'message'   => "<p>".implode("</p><p>",$validator->errors()->all())."</p>",
                'data'   => null
            ]);
        } 
        else 
        { 
            $email = $request->input('email');
            $password = $request->input('password');

            $client = Client::where('email', '=', $email)->first();
            if (!$client) 
            {
                return response()->json([
                    'status'  => false, 
                    'message' => 'Login Fail, please check email!'
                ]);
            }
            else
            {
                if ($client->status=='2')
                {
                    return response()->json([
                        'status'  => false, 
                        'message' => 'Login Fail, Your account is not confirm yet. please contact with author!'
                    ]);
                }
                elseif ($client->status=='0')
                {
                    return response()->json([
                        'status'  => false, 
                        'message' => 'Login Fail, Your account is blocked for a few days. please contact with author!'
                    ]);
                }
                elseif (!Hash::check($password, $client->password)) 
                {
                    return response()->json([
                        'status'  => false, 
                        'message' => 'Login Fail, please enter valid credential!'
                    ]);
                }
                else
                { 
                    // remove unnecessary fields
                    unset($client->id);
                    unset($client->password);
                    unset($client->created_by);
                    unset($client->created_at);
                    unset($client->updated_at);
                    unset($client->status);

                    // Store in session
                    Session::put([
                        'isLogin' => true,
                        'client'  => $client
                    ]);

                    return response()->json([
                        'status'  => true,
                        'message' => Lang::label("Login successful!"), 
                        'data'    => $client 
                    ]);
                }
            } 
        } 
    }

    # Account confirmation
    public function accountConfirmation(Request $request)
    { 
        $token = $request->get('token');
        $data = Client::where(DB::raw('MD5(email)'), strrev($token))
            ->whereNull('updated_at')
            ->take(1)
            ->update(["status" => "1"]);

        if ($data)
        {
            alert()->success("Your account confirmation successful! please login with credential!");
        }
        else
        {
            alert()->error("Please contact with author!");
        } 
        return redirect("/"); 
    }

    # Logout
    public function logout(Request $request)
    {
        Session::flush();
        alert()->success(Lang::label("Logout successful!"));
        return back();
    }

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


   /*
    |____________________________________________________________
    |
    | Update Profile 
    |____________________________________________________________
    |
    */

    # Show the client profile by id_no
    public function profile()
    {
        $title  = Lang::label('Profile');
        $client = Client::where('id_no', session()->get('client.id_no'))->first();
        $vehicles = ClientVehicle::where('client_id_no', session()->get('client.id_no'))->get();
        return view('website.pages.profile', compact('title', 'client', 'vehicles'));
    }

    # Show the client edit form. 
    public function profileEdit()
    {
        $title = Lang::label('Edit Profile'); 
        $title2 = Lang::label('Vehicles'); 
        $client = Client::where('id_no', session()->get('client.id_no'))->first();
        $vehicles = ClientVehicle::where('client_id_no', session()->get('client.id_no'))->get();
        return view('website.pages.profile_edit', compact('title', 'title2', 'client', 'vehicles'));
    }

    # Update client data
    public function profileUpdate(Request $request)
    {  
        $validator = Validator::make($request->all(), [ 
            'id'          => 'required|max:11',
            'name'        => 'required|max:50',
            'mobile'      => 'required|max:20',
            'email'       => 'required|max:100',
            'password'    => 'required|min:5',
            'address'     => 'max:255',
        ]);   

        if ($validator->fails()) 
        {
            return back()->withErrors($validator)
                        ->withInput();
        } 
        else 
        { 
            $client = Client::findOrFail($request->id); 
            $client->name        = $request->name;
            $client->mobile      = $request->mobile;
            $client->email       = $request->email;
            $client->password    = Hash::make($request->password);
            $client->address     = $request->address; 
            $client->updated_at  = date('Y-m-d H:i:s'); 

            if ($client->save()) 
            { 
                alert()->success(Lang::label("Update Successful!"));
                return back()
                    ->withInput();
            } 
            else 
            {
                alert()->error(Lang::label("Please Try Again."));
                return back()
                    ->withInput()
                    ->withErrors($validator);
            }
        }
    }

    # New vehicle data
    public function newVehicle(Request $request) 
    {
        $validator = Validator::make($request->all(), [ 
            'client_id_no' => 'required|max:20',
            'licence' => 'required|max:255|unique:client_vehicle',
            'color'   => 'max:255',
            'photo'   => 'mimes:jpeg,jpg,png,gif|max:10000', 
            'note'    => 'max:1000',
        ]);   

        if (!empty($request->photo)) 
        {
            $filePath = 'public/assets/images/client/'.md5(time()) .'.jpg';
            Image::make($request->photo)->resize(300, 200)->save($filePath);
        } 
        else 
        {
            $filePath = $request->old_photo;
        } 

        if ($validator->fails()) 
        {
            $data = array(
                'status'  => false,
                'message' => "<p>".implode("</p><p>",$validator->errors()->all())."</p>",
                'data'    => array(
                    'photo' => $filePath
                )
            ); 
        } 
        else 
        { 
            $vehicle = new ClientVehicle; 
            $vehicle->client_id_no = $request->client_id_no; 
            $vehicle->licence = $request->licence; 
            $vehicle->photo   = $filePath;
            $vehicle->color   = $request->color; 
            $vehicle->note    = $request->note; 
            $vehicle->created_at  = date('Y-m-d H:i:s');
            $vehicle->status      = ($request->status?1:0);

            if ($vehicle->save()) 
            { 
                $data = array(
                    'status'  => true,
                    'message' => Lang::label("Save successful!"),
                    'data'    => $vehicle
                ); 
            } 
            else 
            {
                $data = array(
                    'status'  => false,
                    'message' => Lang::label('Internal Server Error!'),
                    'data'    => array(
                        'photo' => $filePath
                    )
                ); 
            }
        }

        return response()->json($data);
    }


    # vehicle status update  
    public function statusVehicle(Request $request)
    {
        $vehicle = ClientVehicle::findOrFail($request->id);
        $vehicle->status =  $request->status==1?'0':'1';

        if ($vehicle->save())
        {
            alert()->success(Lang::label("Update Successful!"));
            return back();
        } 
        else 
        {
            alert()->error(Lang::label("Please Try Again."));
            return back();
        }
    }

}
