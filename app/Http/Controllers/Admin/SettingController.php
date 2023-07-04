<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User; 
use App\Models\Setting; 
use Auth, Validator, Hash, Image, Lang;
 

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'roles:superadmin']);
    }


    # Show the app setting
    public function app(Request $request)
    {
        $title = Lang::label("Application Setting"); 
        $currency = $this->currencySign();
        $setting = Setting::first();
        if (!$setting) 
        {
            Setting::create([
                'title'  => "Bit-Parking Lot",
                'email'  => "application@example.com",
                'footer' => "&copy; 2018 - 2019 Bit-Parking Lot.",
                'map_api_key' => "AIzaSyBnNwWEbC5yWgXtxNvF_WnfKTFHf4FZs4E"
            ]);
        } 
        return view('admin.setting.application', compact('title', 'setting', 'currency'));
    }


    # Update the app setting
    public function updateApp(Request $request)
    {  
        $validator = Validator::make($request->all(), [ 
            'title'      => 'required|max:50',
            'about'      => 'max:1024',
            'meta_description' => 'max:255',
            'meta_keyword'     => 'max:255',
            'email'      => 'required|max:100|unique:users,id,'.$request->id,
            'phone'      => 'max:20',
            'address'    => 'max:255',
            'logo'       => 'mimes:jpeg,jpg,png,gif|max:10000', 
            'favicon'    => 'mimes:jpeg,jpg,png,gif|max:10000', 
            'slider_1_text' => 'max:512', 
            'slider_2_text' => 'max:512',  
            'slider_3_text' => 'max:512', 
            'facebook'   => 'max:255', 
            'twitter'    => 'max:255',  
            'youtube'    => 'max:255',  
            'footer'     => 'max:255', 
        ]); 
        
        if (!empty($request->logo)) {
            $logo = 'public/assets/images/icons/logo.png';
            Image::make($request->logo)->resize(190, 50)->save($logo);
        } else {
            $logo = $request->old_logo;
        } 

        if (!empty($request->favicon)) {
            $favicon = 'public/assets/images/icons/favicon.png';
            Image::make($request->favicon)->resize(32, 32)->save($favicon);
        } else {
            $favicon = $request->old_favicon;
        } 

        if (!empty($request->slider_1)) {
            $slider_1 = 'public/assets/images/icons/slider_1.png';
            Image::make($request->slider_1)->resize(1280, 300)->save($slider_1);
        } else {
            $slider_1 = $request->old_slider_1;
        } 

        if (!empty($request->slider_2)) {
            $slider_2 = 'public/assets/images/icons/slider_2.png';
            Image::make($request->slider_2)->resize(1280, 300)->save($slider_2);
        } else {
            $slider_2 = $request->old_slider_2;
        } 

        if (!empty($request->slider_3)) {
            $slider_3 = 'public/assets/images/icons/slider_3.png';
            Image::make($request->slider_3)->resize(1280, 300)->save($slider_3);
        } else {
            $slider_3 = $request->old_slider_3;
        } 


        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('logo', $logo)
                ->with('favicon', $favicon)
                ->with('slider_1', $slider_1)
                ->with('slider_2', $slider_2)
                ->with('slider_3', $slider_3);
        } else {  
            
            $setting = Setting::find($request->id);
            $setting->id      = $request->id;
            $setting->title   = $request->title;
            $setting->about   = $request->about;
            $setting->meta_description = $request->meta_description;
            $setting->meta_keyword     = $request->meta_keyword;
            $setting->email   = $request->email;
            $setting->phone   = $request->phone;
            $setting->address = $request->address;
            $setting->logo    = $logo;
            $setting->favicon = $favicon;
            $setting->slider_1 = $slider_1;
            $setting->slider_1_text = $request->slider_1_text;
            $setting->slider_2 = $slider_2;
            $setting->slider_2_text = $request->slider_2_text;
            $setting->slider_3 = $slider_3;
            $setting->slider_3_text = $request->slider_3_text;
            $setting->facebook = $request->facebook;
            $setting->twitter  = $request->twitter;
            $setting->youtube = $request->youtube;
            $setting->footer  = $request->footer;

            if ($setting->save()) {
                alert()->success(Lang::label('Update Successful!'));
                return back();
            } else {
                alert()->error(Lang::label('Please Try Again.'));
                return back()
                    ->withInput()
                    ->withErrors($validator)
                    ->with('logo', $logo)
                    ->with('favicon', $favicon)
                    ->with('slider_1', $slider_1)
                    ->with('slider_2', $slider_2)
                    ->with('slider_3', $slider_3);
            }
        }
    }


    # Update the app setting
    public function updateMap(Request $request)
    {  
        $validator = Validator::make($request->all(), [ 
            'map_api_key' => 'required|max:50',
            'latitude'    => 'required|max:50',
            'longitude'   => 'required|max:50',  
            'map_zoom'    => 'required|min:1|max:20',  
        ]); 
  
        if ($validator->fails()) 
        {
            return back()
                ->withErrors($validator)
                ->withInput();
        } 
        else 
        {  
            $setting = Setting::find($request->id); 
            $setting->id          = $request->id;
            $setting->map_api_key = $request->map_api_key;
            $setting->latitude    = $request->latitude;
            $setting->longitude   = $request->longitude; 
            $setting->map_zoom    = $request->map_zoom; 

            if ($setting->save()) {
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


    # Update the app setting
    public function updatePrice(Request $request)
    {  

        $validator = Validator::make($request->all(), [ 
            'currency'  => 'required|max:50',
            'vat'       => 'max:11', 
            'vat_type'  => 'max:1', 
            'fine'      => 'max:11', 
            'fine_type' => 'max:1', 
        ]);   

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        } else {  
            
            $setting = Setting::find($request->id);
            $setting->id       = $request->id;
            $setting->currency = $request->currency;
            $setting->vat      = $request->vat;
            $setting->vat_type = $request->vat_type;
            $setting->fine      = $request->fine;
            $setting->fine_type = $request->fine_type;

            if ($setting->save()) {
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

    # admin/setting/paypal
    # PayPal Setting
    public function updatePayPal(Request $request)
    {  
        $validator = Validator::make($request->all(), [ 
            'id'                 => 'required|max:11',
            'paypal_client_id'   => 'required|max:128', 
            'paypal_secret_key'  => 'required|max:128', 
        ]);   

        if ($validator->fails()) 
        {
            return back()
                ->withErrors($validator)
                ->withInput();
        } 
        else 
        {  
            $setting = Setting::find($request->id);
            $setting->id                 = $request->id;
            $setting->paypal_client_id   = $request->paypal_client_id;
            $setting->paypal_secret_key  = $request->paypal_secret_key;

            if ($setting->save()) {
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

    # Update the notification setting
    public function updateNotification(Request $request)
    {  
        $validator = Validator::make($request->all(), [ 
            'id'                 => 'required|max:11',
            'sms_notification'   => 'max:1', 
            'email_notification' => 'max:1', 
            'sms_alert'          => 'max:11', 
        ]);   

        if ($validator->fails()) 
        {
            return back()
                ->withErrors($validator)
                ->withInput();
        } 
        else 
        {  
            $setting = Setting::find($request->id);
            $setting->id                 = $request->id;
            $setting->sms_notification   = (!empty($request->sms_notification)?1:0);
            $setting->email_notification = (!empty($request->email_notification)?1:0);
            $setting->sms_alert          = $request->sms_alert;

            if ($setting->save()) {
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

    #currency List
    protected function currencySign()
    {
        return [
            "$" => "$",
            "¢" => "¢",
            "£" => "£",
            "¤" => "¤",
            "¥" => "¥",
            "֏" => "֏",
            "؋" => "؋",
            "֏" => "֏",
            "৲" => "৲",
            "৳" => "৳",
            "৻" => "৻",
            "૱" => "૱",
            "௹" => "௹",
            "฿" => "฿",
            "៛" => "៛",
            "₠" => "₠",
            "₡" => "₡",
            "₢" => "₢",
            "₣" => "₣",
            "₤" => "₤",
            "₥" => "₥",
            "₦" => "₦",
            "₧" => "₧",
            "₨" => "₨",
            "₩" => "₩",
            "₪" => "₪",
            "₫" => "₫",
            "€" => "€",
            "₭" => "₭",
            "₮" => "₮",
            "₯" => "₯",
            "₰" => "₰",
            "₱" => "₱",
            "₲" => "₲",
            "₳" => "₳",
            "₴" => "₴",
            "₵" => "₵",
            "₶" => "₶",
            "₷" => "₷",
            "₸" => "₸",
            "₹" => "₹",
            "₺" => "₺",
            "₻" => "₻",
            "₼" => "₼",
            "₽" => "₽",
            "₾" => "₾",
            "꠸" => "꠸",
            "﷼" => "﷼",
            "﹩" => "﹩",
            "＄" => "＄",
            "￠" => "￠",
            "￡" => "￡",
            "￥" => "￥",
            "￦" => "￦", 
        ];
    }



    # Update the website setting
    public function website(Request $request)
    {
        $validator = Validator::make($request->all(), [ 
            'id' => 'required|max:11',
            'website' => 'max:1', 
        ]);   

        if ($validator->fails()) 
        { 
            $data['status'] = false; 
            $data['success'] = Lang::label('Please Try Again.');
        } 
        else 
        {  
            $setting = Setting::find($request->id);
            $setting->id = $request->id;
            $setting->website_enable = (empty($request->website)?1:0);

            if ($setting->save()) 
            { 
                $data['status'] = true; 
                $data['success'] = Lang::label('Update Successful!');
            } 
            else 
            { 
                $data['status'] = false; 
                $data['exception'] = Lang::label('Please Try Again.');
            }
        }

        return response()->json($data); 
    }


}
