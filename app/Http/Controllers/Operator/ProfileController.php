<?php

namespace App\Http\Controllers\Operator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Setting; 
use Auth, Validator, Hash, Image, Lang;

class ProfileController extends Controller
{

    # Show the user profile form
    public function profile(Request $request)
    {
        $user  = User::findOrFail(Auth::id());
        $title = Lang::label('Edit Profile'); 
        return view('operator.setting.profile', compact('title', 'user'));
    }

    # Show the user profile form
    public function profileUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [ 
            'name'        => 'required|max:50',
            'email'       => 'required|max:100|unique:users,id,'.$request->id,
            'password'    => 'required|max:50',
            'conf_password' => 'required|max:50|same:password',
            'photo'       => 'mimes:jpeg,jpg,png,gif|max:10000', //kb 
        ]); 
 
        if (!empty($request->photo)) {
            $filePath = 'public/assets/images/admin/'.md5(time()) .'.jpg';
            Image::make($request->photo)->resize(300, 200)->save($filePath);
        } else {
            $filePath = $request->old_photo;
        }  

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('photo', $filePath);
        } else { 
            $user = User::findOrFail(Auth::id());; 

            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->photo = $filePath;
            $user->updated_at  = date('Y-m-d H:i:s');

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

}
