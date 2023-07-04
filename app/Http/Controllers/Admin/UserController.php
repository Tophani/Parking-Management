<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Place;
use Datatables, DB, Validator, Hash, Image, Lang;

class UserController extends Controller
{
    # Create a new controller instance
    public function __construct()
    {
        $this->middleware(['auth', 'roles:superadmin']);
    }

    # Show the admin list
    public function show()
    {
        $title = Lang::label("Users");
        $users = User::all();
        return view('admin.admin.list', compact('title', 'users'));
    }

    public function getAdminData(Request $request)
    {
        DB::statement(DB::raw('set @rownum=0'));
        $users = DB::select(DB::raw("
            SELECT 
                @rownum  := @rownum  + 1 AS rownum,
                u.id,
                u.name,
                u.email,
                u.photo,
                u.status,
                u.user_role,
                u.place_id,
                u.created_at,
                u.updated_at,
                CASE
                    WHEN u.place_id THEN GROUP_CONCAT(p.name, ', ')
                END AS zone 
            FROM users AS u
            LEFT JOIN place AS p 
                ON  p.id IN (SELECT place_id FROM users WHERE users.id = u.id)
            GROUP BY u.id
        "));

 
        $datatables = datatables()
            ->of($users)
            ->editColumn('user_role', '{{ ($user_role=="superadmin")?"Super Admin":(($user_role=="admin")?"Admin":"Operator") }}'
            )
            ->addColumn('zone', function ($user) {

                $data = DB::table("place")
                    ->select(DB::raw("GROUP_CONCAT(name) AS zone"))
                    ->whereIn("id", (!empty($user->place_id)?explode(",", $user->place_id):[]))
                    ->first();

                return $data->zone;
            })
            ->addColumn('status', function ($user) {
                if ($user->status==1)
                    return "<span class='label label-success label-xs'>Active</span>";
                else
                    return "<span class='label label-danger label-xs'>Inactive</span>"; 
            })
            ->addColumn('action', function ($user) {
                if ($user->user_role!='superadmin'):
                    return '<a href="'. url("admin/user/edit/$user->id") .'" class="btn btn-xs btn-primary waves-effect"><i class="material-icons">edit</i></a>
                    <a  onclick="return confirm(\'Are you sure?\')" href="'. url("admin/user/delete/$user->id") .'" class="btn btn-xs btn-danger waves-effect"><i class="material-icons">delete</i></a></a>';
                else:
                    return '<div class="btn btn-info">Admin</div>';
                endif;
            })
            ->addColumn('profile', function ($user) {
                return '<img src=" '.asset($user->photo?$user->photo:"public/assets/images/icons/user.png").' " width="60" height="40" />';
            })
            ->rawColumns(['action', 'zone', 'profile', 'status'])
            ->removeColumn('password')
            ->setTotalRecords(count($users)); 

        return $datatables->make(true); 
    }


    # Show the admin form. 
    public function form()
    {
        $title = Lang::label("New User");
        $placeList = Place::where('status', 1)->pluck('name', 'id');
        return view('admin.admin.form', compact('title', 'placeList'));
    }

    # Save admin data
    public function create(Request $request)
    {   
        $validator = Validator::make($request->all(), [ 
            'name'        => 'required|max:50',
            'email'       => 'required|unique:users,email|max:50',
            'password'    => 'required|max:50',
            'user_role'   => 'required|max:20',
            'place_id'    => 'required|max:128',
            'conf_password' => 'required|max:50|same:password',
            'photo'       => 'mimes:jpeg,jpg,png,gif|max:10000', 
        ]);  

        if (!empty($request->photo)) {
            $filePath = 'public/assets/images/client/'.md5(time()) .'.jpg';
            Image::make($request->photo)->resize(300, 200)->save($filePath);
        } else {
            $filePath = $request->old_photo;
        }  

        if ($validator->fails()) 
        {
            return redirect('admin/user/new/')
                        ->withErrors($validator)
                        ->withInput()
                        ->with('photo', $filePath);
        } 
        else 
        { 
            $user = new User;
            $user->name        = $request->name;
            $user->email       = $request->email;
            $user->password    = Hash::make($request->conf_password); 
            $user->photo       = $filePath;
            $user->created_at  = date('Y-m-d H:i:s');
            $user->updated_at  = null;
            $user->user_role   = $request->user_role;
            $user->place_id    = ((count($request->place_id)>0)?(implode(",", $request->place_id)):null);
            $user->status      = ($request->status?1:0); 

            if ($user->save()) { 
                alert()->success(Lang::label("Save Successful!"));
                return back()
                    ->withInput()  
                    ->with('photo', $filePath);
            } else {
                alert()->error(Lang::label('Please Try Again.'));
                return back()
                    ->withInput()
                    ->withErrors($validator)
                    ->with('photo', $filePath);
            }
        }
    }


    # Show the admin edit form. 
    public function editForm(Request $request)
    {
        $title = Lang::label("Edit User"); 
        $user = User::whereNotIn('user_role', [1])
            ->findOrFail($request->id);
        $placeList = Place::where('status', 1)
            ->pluck('name', 'id');
        return view('admin.admin.edit', compact('title', 'user', 'placeList'));
    }

    
    # Update the admin data 
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [ 
            'name'        => 'required|max:50',
            'email'       => 'required|max:100|unique:users,id,'.$request->id,
            'password'    => 'required|max:50',
            'conf_password' => 'required|max:50|same:password',
            'user_role'   => 'required|max:20',
            'place_id'    => 'required|max:128',
            'photo'       => 'mimes:jpeg,jpg,png,gif|max:10000', 
        ]); 

        if (!empty($request->photo)) 
        {
            $filePath = 'public/assets/images/admin/'. date('ymdhis') .'.jpg';
            Image::make($request->photo)->resize(300, 200)->save($filePath);
        } 
        else 
        {
            $filePath = $request->old_photo;
        } 


        if ($validator->fails()) 
        {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('photo', $filePath);
        } 
        else 
        { 
            $user = User::whereNotIn('user_role', [1])
                    ->findOrFail($request->id); 

            $user->name        = $request->name;
            $user->email       = $request->email;
            $user->password    = Hash::make($request->password);
            $user->photo       = $filePath;
            $user->updated_at  = date('Y-m-d H:i:s');
            $user->user_role   = $request->user_role;
            $user->place_id    = ((count($request->place_id)>0)?(implode(",", $request->place_id)):null);
            $user->status      = ($request->status?1:0); 

            if ($user->save()) { 
                alert()->success(Lang::label('Update Successful!'));
                return back()
                    ->withInput()  
                    ->with('photo', $filePath);
            } else { 
                alert()->error(Lang::label('Please Try Again.'));
                return back()
                    ->withInput()
                    ->withErrors($validator)
                    ->with('photo', $filePath);
            }
        }
    }
 

    # Delete admin data by id
    public function delete(Request $request)
    {
        $user = User::whereNotIn('user_role', [1])
                ->findOrFail($request->id);

        if ($user->delete())
        {
            alert()->success(Lang::label("Delete Successful!"));
            return back();
        } else {
            alert()->error(Lang::label('Please Try Again.'));
            return back();
        }
    } 
}
