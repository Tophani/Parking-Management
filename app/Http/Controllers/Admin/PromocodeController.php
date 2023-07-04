<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Promocode;
use Validator, DB, Lang;

class PromocodeController extends Controller
{

	public function __construct()
    {
        $this->middleware(['auth']);
    }

    # Show the promocode list
    public function show()
    {
        $title = Lang::label('Promo Codes');
        $promocodes = Promocode::all();
        return view('admin.promocode.list', compact('title', 'promocodes'));
    }

    public function getPromocodeData(Request $request)
    {
        DB::statement(DB::raw('set @rownum=0'));
        $promocodes = DB::table('promocode')->select([
             DB::raw('@rownum  := @rownum  + 1 AS rownum'),
            'id',
            'name',
            'description',
            'promocode',
            'discount',
            'limit',
            'used',
            'start_date',
            'end_date',
            'status',
        ])->orderBy('id', 'asc')
          ->get();
 
        return datatables()
            ->of($promocodes)
            ->addColumn('status', function ($promocode) {
                return '<div class="switch" >
                        <label>
                        <input disabled name="status" type="checkbox" '.(($promocode->status==1)?'checked':null).'>
                            <span class="lever"></span>
                        </label>
                    </div>';
            })
            ->addColumn('action', function ($promocode) {
                return '<a href="'. url("admin/promocode/edit/$promocode->id") .'" class="btn btn-xs btn-primary waves-effect"><i class="material-icons">edit</i></a>
                    <a  onclick="return confirm(\'Are you sure?\')" href="'. url("admin/promocode/delete/$promocode->id") .'" class="btn btn-xs btn-danger waves-effect"><i class="material-icons">delete</i></a></a>';
            })->rawColumns(['action', 'profile', 'status'])
            ->setTotalRecords(count($promocodes))
            ->make(true); 
    }

    # Show the promocode form. 
    public function form()
    {
    	$title = Lang::label('New Promo Code');
        return view('admin.promocode.form', compact('title'));
    }

    # Save the promocode data. 
    public function create(Request $request)
    {  
        $validator = Validator::make($request->all(), [ 
            'name'        => 'required|max:50',
            'description' => 'max:255',
            'promocode'   => 'required|unique:promocode|max:30',
            'discount'    => 'required|max:11',
            'limit'       => 'required|max:11',
            'start_date'  => 'required|max:255',
            'end_date'    => 'required|max:255',
        ]);  

        if ($validator->fails()) {
            return back()->withErrors($validator)
                        ->withInput();
        } else { 

            $promocode = new promocode;
            $promocode->name        = $request->name;
            $promocode->description = $request->description;
            $promocode->promocode = $request->promocode;
            $promocode->discount    = $request->discount;
            $promocode->limit       = $request->limit;
            $promocode->start_date  = date('Y-m-d H:i:s', strtotime($request->start_date));
            $promocode->end_date    = date('Y-m-d H:i:s', strtotime($request->end_date));
            $promocode->status      = ($request->status?1:0);

            if ($promocode->save()) {
                alert()->success(Lang::label("Save Successful!"));
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

    # Show the promocode edit form. 
    public function edit(Request $request)
    {
        $promocode = Promocode::findOrFail($request->id);
        $title = Lang::label('Edit Promo Code'); 
        return view('admin.promocode.edit', compact('title', 'promocode'));
    }

    # Update the promocode data. 
    public function update(Request $request)
    {  
        $validator = Validator::make($request->all(), [ 
            'name'        => 'required|max:50',
            'description' => 'max:255',
            'promocode'   => 'required|max:30',
            'discount'    => 'required|max:11',
            'limit'       => 'required|max:11',
            'start_date'  => 'required|max:255',
            'end_date'    => 'required|max:255',
        ]);  

        if ($validator->fails()) {
            return back()->withErrors($validator)
                        ->withInput();
        } else { 

            $promocode = Promocode::findOrFail($request->id); 
            $promocode->name        = $request->name;
            $promocode->description = $request->description;
            $promocode->promocode   = $request->promocode;
            $promocode->discount    = $request->discount;
            $promocode->limit       = $request->limit;
            $promocode->start_date  = date('Y-m-d H:i:s', strtotime($request->start_date));
            $promocode->end_date    = date('Y-m-d H:i:s', strtotime($request->end_date));
            $promocode->status      = ($request->status?1:0);

            if ($promocode->save()) {
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


    # Delete admin data by id
    public function delete(Request $request)
    {
        $promocode = Promocode::findOrFail($request->id);

        if ($promocode->delete())
        {
            alert()->success(Lang::label("Delete Successful!"));
            return back();
        } else {
            alert()->error(Lang::label('Please Try Again.'));
            return back();
        }
    } 

}
