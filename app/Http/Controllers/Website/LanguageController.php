<?php

namespace App\Http\Controllers\Website;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Setting;   
use Session, Lang;

class LanguageController extends Controller
{    
    # Show the booking form. 
    public function switchLanguage(Request $request)
    {
        Session::put("language", $request->name);

        alert()->success(Lang::label("Language changed successful!"));
        return back();
    } 

}
