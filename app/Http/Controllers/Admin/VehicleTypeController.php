<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\VehicleType;
use Datatables, DB, Validator, Lang;

class VehicleTypeController extends Controller
{
    # Create a new controller instance
    public function __construct()
    {
        $this->middleware(['auth', 'roles:superadmin']);
    }

    # Show the admin list
    public function show()
    {
        $title = Lang::label("Vehicle Types");
        return view('admin.vehicle_type.list', compact('title'));
    }

    public function getAdminData(Request $request)
    {
        DB::statement(DB::raw('set @rownum=0'));
        $vehicles = DB::table('vehicle_type AS v')->select([
             DB::raw('@rownum  := @rownum  + 1 AS rownum'),
            'v.*',
        ])
        ->orderBy('v.name', 'asc')
        ->get();
 
        $datatables = datatables()
            ->of($vehicles)  
            ->addColumn('status', function ($vehicles) {
                if ($vehicles->status==1)
                    return "<span class='label label-success label-xs'>Active</span>";
                else
                    return "<span class='label label-danger label-xs'>Inactive</span>"; 
            })
            ->addColumn('action', function ($vehicles) {
                return '<a href="'. url("admin/vehicle_type/edit/$vehicles->id") .'" title="Edit" class="btn btn-xs btn-primary waves-effect"><i class="material-icons">edit</i></a>
                    <a  onclick="return confirm(\'Are you sure?\')" title="Delete" href="'. url("admin/vehicle_type/delete/$vehicles->id") .'" class="btn btn-xs btn-danger waves-effect"><i class="material-icons">delete</i></a></a>';
            }) 
            ->rawColumns(['action', 'status'])
            ->setTotalRecords(count($vehicles)); 

        return $datatables->make(true); 
    }


    # Show the admin form. 
    public function form()
    {
        $title = Lang::label("New Vehicle Type");
        return view('admin.vehicle_type.form', compact('title'));
    }

    # Save admin data
    public function create(Request $request)
    {  
        $validator = Validator::make($request->all(), [ 
            'name'        => 'required|max:128|unique:vehicle_type',
            'description' => 'max:512',
            'status'      => 'max:2' 
        ]);  

        if ($validator->fails()) 
        {
            return back()
                ->withErrors($validator)
                ->withInput();
        } 
        else 
        { 
            $vehicle = new VehicleType;
            $vehicle->name   = $request->name;
            $vehicle->description  = $request->description; 
            $vehicle->status = ($request->status?1:0); 

            if ($vehicle->save()) { 
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


    # Show the admin edit form. 
    public function editForm(Request $request)
    {
        $title = Lang::label("Edit Vehicle Type");  
        $vehicle = VehicleType::findOrFail($request->id);
        return view('admin.vehicle_type.edit', compact('title', 'vehicle'));
    }

    
    # Update the admin data 
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [ 
            'name'        => 'required|max:100|unique:vehicle_type,id,'.$request->id,
            'description' => 'max:512',
            'status'      => 'max:2' 
        ]);   

        if ($validator->fails()) 
        {
            return back()
                ->withErrors($validator)
                ->withInput();
        } 
        else 
        { 
            $vehicle = VehicleType::findOrFail($request->id); 

            $vehicle->name        = $request->name;
            $vehicle->description = $request->description;
            $vehicle->status      = ($request->status?1:0); 

            if ($vehicle->save()) { 
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
        $vehicle = VehicleType::findOrFail($request->id);

        if ($vehicle->delete())
        {
            alert()->success(Lang::label("Delete Successful!"));
            return back();
        } else {
            alert()->error(Lang::label('Please Try Again.'));
            return back();
        }
    } 
}
