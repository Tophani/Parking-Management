<?php

namespace App\Http\Controllers\Operator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\ClientVehicle;
use Auth, Validator, Image, DB, Response, Str, Lang, Hash;


class ClientController extends Controller
{
    # Create a new controller instance
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    # Show the clients. 
    public function list()
    {
        $title = Lang::label('Clients');
        $clients = Client::all();
        return view('operator.client.list', compact('title', 'clients'));
    }

    public function getClientData(Request $request)
    {
        DB::statement(DB::raw('set @rownum=0'));
        $clients = DB::table('client')->select([
            DB::raw('@rownum  := @rownum  + 1 AS rownum'),
        	'id_no',
            'name',
			'mobile',
			'email',
            'address',
        ])
        ->where('status', '1')
        ->orderBy('id', 'asc')
        ->get();
 
 
        $datatables = datatables()
            ->of($clients) 
            ->addColumn('action', function ($client) {
                return '<a href="'. url("operator/client/profile/$client->id_no") .'" class="btn btn-xs btn-success waves-effect"><i class="material-icons">remove_red_eye</i></a>';
            })
            ->rawColumns(['action'])
            ->setTotalRecords(count($clients)); 

        return $datatables->make(true); 
    }

    # Show the client profile by id_no
    public function profile(Request $request)
    {
        $title  = Lang::label('Profile');
        $client = Client::where('id_no', $request->id)->first();
        $vehicles = ClientVehicle::where('client_id_no', $request->id)->get();
        return view('operator.client.profile', compact('title', 'client', 'vehicles'));
    }

    # Show the client form. 
    public function form()
    {
        $title = Lang::label('New Client');
        return view('operator.client.form', compact('title'));
    }

    # Save client data
    public function create(Request $request)
    {  
        $validator = Validator::make($request->all(), [ 
            'name'        => 'required|max:50',
            'mobile'      => 'required|max:20',
            'email'       => 'required|max:50|unique:client,email',
            'password'    => 'required|min:5',
            'address'     => 'max:255',
            'vehicle_licence' => 'required|max:255',
            'color'           => 'max:20',
            'vehicle_photo'   => 'mimes:jpeg,jpg,png,gif|max:10000', 
            'note'            => 'max:1000',
        ]);  

        $filePath = null; 

        if (!empty($request->vehicle_photo)) {
            $filePath = 'public/assets/images/client/'.md5(time()) .'.jpg';
            Image::make($request->vehicle_photo)->resize(300, 200)->save($filePath);
        } else {
            $filePath = $request->old_vehicle_photo;
        } 
         
        if ($validator->fails()) {
            return back()->withErrors($validator)
                        ->withInput()
                        ->with('photo', $filePath);
        } 
        else 
        { 
            $id_no  = $this->randStr();

            //Save Client Info
            $client = new Client;
            $client->id_no       = $id_no;
            $client->name        = $request->name;
            $client->mobile      = $request->mobile;
            $client->email       = $request->email;
            $client->password    = Hash::make($request->password);
            $client->address     = $request->address;  
            $client->created_by   = Auth::User()->id;
            $client->created_at  = date('Y-m-d H:i:s');
            $client->status      = ($request->status?1:0);

            if ($client->save()) 
            { 
                // Save Vehicle Info
                $vehicle = new ClientVehicle;
                $vehicle->client_id_no = $id_no; 
                $vehicle->licence = $request->vehicle_licence;  
                $vehicle->photo   = $filePath;
                $vehicle->color   = $request->color; 
                $vehicle->note        = $request->note; 
                $vehicle->created_at  = date('Y-m-d H:i:s');
                $vehicle->save();

                alert()->success(Lang::label("Save Successful!"));
                return redirect('operator/client/profile/'.$id_no);
            } 
            else 
            {
                alert()->error(Lang::label("Please Try Again.")); 
                return back()
                    ->withInput()
                    ->withErrors($validator)
                    ->with('photo', $filePath);
            }
        }
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
 

}
