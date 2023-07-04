<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Place;
use App\Models\VehicleType;
use App\Models\Price;

use DB, Validator, Hash, Lang;

class PriceController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }


    # Show the price list
    public function list()
    {
        $title = Lang::label('Prices');
        return view('admin.price.list', compact('title'));
    }

    public function getPriceData(Request $request)
    {
        DB::statement(DB::raw('set @rownum=0'));
        $prices = DB::table('price')->select([
             DB::raw('@rownum  := @rownum  + 1 AS rownum'),
            'price.id', 
            'price.place_id',
            'place.name AS place_name',
            'price.note',
            'price.status', 
        ])->orderBy('place.name', 'asc')
          ->groupBy('price.place_id')
          ->leftJoin('place', 'place.id', '=', 'price.place_id')
		  ->get();
 
 
        return datatables()
            ->of($prices)
            ->addColumn('status', function ($price) {
                return '<div class="switch" >
                        <label>
                        <input disabled name="status" type="checkbox" '.(($price->status==1)?'checked':null).'>
                            <span class="lever"></span>
                        </label>
                    </div>';
            })->addColumn('action', function ($price) {
                return '<a href="'. url("admin/price/show/$price->place_id") .'" class="btn btn-xs btn-success waves-effect"><i class="material-icons">remove_red_eye</i></a>
                <a href="'. url("admin/price/edit/$price->place_id") .'" class="btn btn-xs btn-primary waves-effect"><i class="material-icons">edit</i></a>
                <a  onclick="return confirm(\'Are you sure?\')" href="'. url("admin/price/delete/$price->place_id") .'" class="btn btn-xs btn-danger waves-effect"><i class="material-icons">delete</i></a></a>';
            })->rawColumns(['action', 'status'])
            	->setTotalRecords(count($prices))
            	->make(true); 
    }


    # Show the price form. 
    public function form()
    {
    	$title = Lang::label('New Price');
    	$placeList = Place::where('status', 1)->pluck('name', 'id');
        $vehicleTypeList = VehicleType::where('status', 1)->pluck('name', 'id');
    	$unitList = [
            'minutes' => 'Minute(s)',
            'hours'   => 'Hour(s)',
            'days'    => 'Day(s)',
            'months'  => 'Month(s)',
            'years'   => 'Year(s)',
    	];
        return view('admin.price.form', compact('title', 'placeList', 'vehicleTypeList', 'unitList'));
    }

    # Save the price data. 
    public function create(Request $request)
    {  
        $validator = Validator::make($request->all(), [ 
            'place_id'      => 'required|max:11',
            'vehicle_type_id' => 'required|max:11',
            'time'          => 'required|max:11',
            'unit'          => 'required|max:20',
            'price'         => 'required|max:11',
            'note'	        => 'max:512',
        ]);  

        if ($validator->fails()) {
            return back()->withErrors($validator)
                ->withInput();
        } 
        else 
        {  
            $data = [];
            for ($i=0; $i<sizeof($request->time); $i++) {
                $data[$i] = [
                    'place_id' => $request->place_id,
                    'vehicle_type_id' => $request->vehicle_type_id[$i],
                    'time'  => $request->time[$i],
                    'unit'  => $request->unit[$i],
                    'price' =>  $request->price[$i], 
                    'note'  => $request->note,
                    'status'  => ($request->status?1:0),
                ];
            }  


            if (Price::insert($data)) {
                alert()->success(Lang::label("Save Successful!"));
                return back();
            } else {
                alert()->error(Lang::label('Please Try Again.'));
                return back()
                    ->withInput()
                    ->withErrors($validator);
            }
        }
    }

    # Show the price by id. 
    public function show(Request $request)
    {
    	$title = Lang::label("Price");
        $prices = DB::table('price')->select([
            'price.id', 
            'place.name AS place_name',
            'vehicle_type.name AS vehicle_type',
            'price.time',
            'price.unit',
            'price.price', 
            'price.status', 
        ])->orderBy('price.id', 'asc')
          ->leftJoin('place', 'place.id', '=', 'price.place_id')
          ->leftJoin('vehicle_type', 'vehicle_type.id', '=', 'price.vehicle_type_id')
          ->where('price.place_id', $request->p_id)
          ->where('vehicle_type.status', 1)
          ->where('place.status', 1)
          ->get();

        return view('admin.price.show', compact('title', 'prices'));
    }

    # Show the price form. 
    public function edit(Request $request)
    {
    	$title = Lang::label("Edit Price");
        $price = Price::where('place_id', $request->p_id)->first();
        $prices = Price::where('place_id', $request->p_id)->get();
        $vehicleTypeList = VehicleType::where('status', 1)->pluck('name', 'id');
        $placeList = Place::where('status', 1)->pluck('name', 'id');
        $unitList = [
            'minutes' => 'Minute(s)',
            'hours'   => 'Hour(s)',
            'days'    => 'Day(s)',
            'months'  => 'Month(s)',
            'years'   => 'Year(s)',
        ];
        return view('admin.price.edit', compact('title', 'price', 'prices',  'placeList', 'unitList', 'vehicleTypeList'));
    }

    # Update price data by id
    public function update(Request $request)
    {  
        $validator = Validator::make($request->all(), [ 
            'place_id'      => 'required|max:11',
            'vehicle_type_id' => 'required|max:11',
            'time'          => 'required|max:11',
            'unit'          => 'required|max:20',
            'price'         => 'required|max:11',
            'note'          => 'max:512',
        ]);  

        if ($validator->fails()) {
            return back()->withErrors($validator)
                        ->withInput();
        } else { 

            Price::where('place_id', $request->p_id)->delete();

            $data = [];
            for ($i=0; $i<sizeof($request->time); $i++) {
                $data[$i] = [
                    'place_id' => $request->place_id,
                    'vehicle_type_id' => $request->vehicle_type_id[$i],
                    'time'  => $request->time[$i],
                    'unit'  => $request->unit[$i], 
                    'price' => $request->price[$i],
                    'note'  => $request->note,
                    'status'  => ($request->status?1:0),
                ];
            }  

            if (Price::insert($data)) {
                alert()->success(Lang::label('Update Successful!'));
                return redirect('admin/price/list');
            } else {
                alert()->error(Lang::label('Please Try Again.'));
                return back()
                    ->withInput()
                    ->withErrors($validator);
            }
        }
    }


    # Delete price data by id
    public function delete(Request $request)
    {
        $price = Price::where('place_id', $request->p_id)->delete();
        if ($price)
        {
            alert()->success(Lang::label("Delete Successful!"));
            return back();
        } else {
            alert()->error(Lang::label('Please Try Again.'));
            return back();
        }
    } 

}
