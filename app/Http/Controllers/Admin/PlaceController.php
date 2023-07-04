<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Place;
use App\Models\Setting;
use Validator, DB, Str, Lang;

class PlaceController extends Controller
{    

	public function __construct()
    {
        $this->middleware(['auth']);
    }

    # Show the Parking Zone
    public function list()
    {
        $title = Lang::label('Parking Zones');
        $places = Place::all();
        return view('admin.place.list', compact('title', 'places'));
    }

    public function getListData(Request $request)
    {
        DB::statement(DB::raw('set @rownum=0'));
        $places = DB::table('place')->select([
             DB::raw('@rownum  := @rownum  + 1 AS rownum'),
            'id',
            'name',
            'address',
            'latitude',
            'longitude',
            'limit',
            'space',
            'note',
            'status'
        ])->orderBy('id', 'asc')
          ->get();
 
 
        $datatables = datatables()
            ->of($places)
            ->addColumn('space', function ($place) {
                return Str::words($place->space,2);
            })
            ->addColumn('status', function ($place) {
                return '<div class="switch" >
                        <label>
                        <input disabled name="status" type="checkbox" '.(($place->status==1)?'checked':null).'>
                            <span class="lever"></span>
                        </label>
                    </div>';
            })
            ->addColumn('action', function ($place) {
                return '<a href="'. url("admin/place/show/$place->id") .'" class="btn btn-xs btn-success waves-effect"><i class="material-icons">remove_red_eye</i></a>
                    <a href="'. url("admin/place/edit/$place->id") .'" class="btn btn-xs btn-primary waves-effect"><i class="material-icons">edit</i></a>
                    <a  onclick="return confirm(\'Are you sure?\')" href="'. url("admin/place/delete/$place->id") .'" class="btn btn-xs btn-danger waves-effect"><i class="material-icons">delete</i></a></a>';
            })
            ->rawColumns(['action',  'status'])
            ->removeColumn('password')
            ->setTotalRecords(count($places)); 

        return $datatables->make(true); 
    }



    # Show the parking Zone form. 
    public function form()
    {
    	$title = Lang::label('New Parking Zone');
    	$setting = Setting::first();
        return view('admin.place.form', compact('title', 'setting'));
    }


    # Save the parking Zone data
    public function create(Request $request)
    {  
        $validator = Validator::make($request->all(), [ 
            'name'        => 'required|max:255',
            'address'     => 'required|max:255',
            'latitude'    => 'required|max:50',
            'longitude'   => 'required|max:50',  
            'limit'       => 'required|min:1',
            'space'     => 'required',  
            'note'        => 'max:2048', 
        ]); 
  
        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        } else {  
            
            $place = new Place;  
            $place->name      = $request->name;
            $place->address   = $request->address;
            $place->latitude  = $request->latitude;
            $place->longitude = $request->longitude; 
            $place->limit     = $request->limit; 
            $place->note      = $request->note;
            $place->space     = $request->space; 
            $place->status    = ($request->status==1?1:0); 

            if ($place->save()) {
                alert()->success(Lang::label("Save Successful!"));
                return redirect()->back();
            } else {
                alert()->error(Lang::label('Please Try Again.'));
                return redirect()
                    ->back()
                    ->withInput()
                    ->withErrors($validator);
            }
        }
    }


    # Show place data by id
    public function show(Request $request)
    {
        $title = Lang::label('Parking Zone');
        $place = Place::findOrFail($request->id);
        $setting = Setting::first();
        return view('admin.place.show', compact('title', 'place', 'setting'));
    } 


    # Show the parking place by id. 
    public function edit(Request $request)
    {
        $title = Lang::label('Edit Parking Zone');
        $place = Place::findOrFail($request->id);
        $setting = Setting::first();
        return view('admin.place.edit', compact('title', 'place', 'setting'));
    }

    # Update the parking place data
    public function update(Request $request)
    {  
        $validator = Validator::make($request->all(), [ 
            'name'        => 'required|max:255',
            'address'     => 'required|max:255',
            'latitude'    => 'required|max:50',
            'longitude'   => 'required|max:50',  
            'limit'       => 'required|min:1',
            'space'     => 'required',  
            'note'        => 'max:2048', 
        ]); 
  
        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        } else {  
            
            $place = Place::findOrFail($request->id);  
            $place->name      = $request->name;
            $place->address   = $request->address;
            $place->latitude  = $request->latitude;
            $place->longitude = $request->longitude; 
            $place->limit     = $request->limit; 
            $place->note      = $request->note;
            $place->space   = $request->space; 
            $place->status    = ($request->status?1:0); 

            if ($place->save()) {
                alert()->success(Lang::label('Update Successful!'));
                return back();
            } else {
                alert()->error(Lang::label('Please Try Again.'));
                return back()
                    ->withInput()
                    ->withErrors($validator);
            }
        }
    }

    # Delete place data by id
    public function delete(Request $request)
    {
        $place = Place::findOrFail($request->id);
        if ($place->delete())
        {
            alert()->success(Lang::label("Delete Successful!"));
            return back();
        } else {
            alert()->error(Lang::label('Please Try Again.'));
            return back();
        }
    } 


}
