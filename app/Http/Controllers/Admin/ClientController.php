<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\ClientVehicle;
use Auth, Validator, Image, DB, Response, Lang, Hash;


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
        return view('admin.client.list', compact('title', 'clients'));
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
			'status',
        ])->orderBy('id', 'desc')
          ->get();
 
 
        $datatables = datatables()
            ->of($clients)
            ->addColumn('status', function ($client) {
                if ($client->status=='1') 
                {
                    return "<span class=\"label label-success\">".Lang::label("Active")."</span>";
                }
                elseif ($client->status=='0') 
                {
                    return "<span class=\"label label-danger\">".Lang::label("Deactive")."</span>";
                }
                else 
                {
                    return "<span class=\"label label-warning\">".Lang::label("Pending")."</span>";
                }
            })
            ->addColumn('action', function ($client) {
                return '<a href="'. url("admin/client/profile/$client->id_no") .'" class="btn btn-xs btn-success waves-effect"><i class="material-icons">remove_red_eye</i></a>
                    <a href="'. url("admin/client/edit/$client->id_no") .'" class="btn btn-xs btn-primary waves-effect"><i class="material-icons">edit</i></a>
                    <a  onclick="return confirm(\'Are you sure?\')" href="'. url("admin/client/delete/$client->id_no") .'" class="btn btn-xs btn-danger waves-effect"><i class="material-icons">delete</i></a>';
            })
            ->rawColumns(['action', 'status'])
            ->setTotalRecords(count($clients)); 

        return $datatables->make(true); 
    }

    # Show the client profile by id_no
    public function profile(Request $request)
    {
        $title  = Lang::label('Profile');
        $client = Client::where('id_no', $request->id)->first();
        $vehicles = ClientVehicle::where('client_id_no', $request->id)->get();
        return view('admin.client.profile', compact('title', 'client', 'vehicles'));
    }

    # Show the client form. 
    public function form()
    {
        $title = Lang::label('New Client');
        return view('admin.client.form', compact('title'));
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
                return redirect('admin/client/profile/'.$id_no);
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

    # Show the client edit form. 
    public function edit(Request $request)
    {
        $title = Lang::label('Edit Client'); 
        $title2 = Lang::label('Vehicles'); 
        $client = Client::where('id_no', $request->id)->first();
        $vehicles = ClientVehicle::where('client_id_no', $request->id)->get();
        return view('admin.client.edit', compact('title', 'title2', 'client', 'vehicles'));
    }

    # Update client data
    public function update(Request $request)
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
            $client->status      = ($request->status?1:0);

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

    # Delete admin data by id_no
    public function delete(Request $request)
    {
    	// delete client info
        $client = Client::where('id_no', $request->id)->delete();

        if ($client)
        {
            // delete vehicle info
            ClientVehicle::where('client_id_no', $request->id)->delete();

            alert()->success(Lang::label("Delete Successful!"));
            return back();
        } 
        else 
        {
            alert()->error(Lang::label("Please Try Again."));
            return back();
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

 
    public function getClientContactData(Request $request)
    {
        DB::statement(DB::raw('set @rownum=0'));
        $clients = DB::table('client')->select([
            'name',
            'mobile',
            'email',
        ])
        ->whereNotNull('mobile')
        ->whereNotNull('email')
        ->orderBy('name', 'asc')
        ->get();
 
 
        $datatables = datatables()
            ->of($clients)    
            ->addColumn('contact', function ($client) {
                return "<contact><mobile>$client->mobile</mobile> <br> <email>$client->email</email></contact>";
            })
            ->rawColumns(['contact']); 

        return $datatables->make(true); 
    }

    public function getEmail(Request $request)
    {
        return Client::distinct("email")
        ->whereNotNull('email')
        ->where('email', 'like', "%$request->email%")
        ->orderBy('email', 'asc')
        ->pluck('email');  
    }

    public function getMobile(Request $request)
    {
        return Client::distinct("mobile")
        ->whereNotNull('mobile')
        ->where('mobile', 'like', "%$request->mobile%")
        ->orderBy('mobile', 'asc')
        ->pluck('mobile');  
    }

}
