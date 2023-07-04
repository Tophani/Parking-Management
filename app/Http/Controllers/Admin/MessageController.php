<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Message; 
use Validator;
use Session;
use DB, Auth, Lang;

class MessageController extends Controller
{ 
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    # Show the message form
    public function form()
    { 
        $title = Lang::label('New Message');
        $userList = DB::table('users')
            ->whereNotIn('id', [Auth::id()])
        	->whereNotIn('status', [0])
        	->orderBy('name', 'asc')
            ->pluck('name','id');

    	return view('admin.message.form', compact('title', 'userList'));
    }


    public function new(Request $request)
    { 
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|max:11',
            'subject' => 'required|max:255',
            'message' => 'required|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()
    			->back()
                ->withErrors($validator)
                ->withInput();
        } else {
            $save = Message::insert([
                'sender_id'      => Auth::id(),
                'receiver_id'    => $request->user_id,
                'subject'        => $request->subject,
                'message'        => $request->message,
                'datetime'  	 => date('Y-m-d h:i:s'),
                'sender_status'  => 0,
                'receiver_status' => 0,
            ]);

        	if ($save) {
                alert()->success(Lang::label("Message Sent!"));
	            return back()->withInput();
        	} else {
                alert()->error(Lang::label('Please Try Again.'));
	            return back()->withInput();
        	}

        }
    }

	public function inbox()
    {  
        $title = Lang::label('Inbox Message');
    	return view('admin.message.inbox', compact('title'));
    }

    public function getInboxData(Request $request)
    {
        DB::statement(DB::raw('set @rownum=0'));
        $messages = DB::table('message')->select([
             DB::raw('@rownum  := @rownum  + 1 AS rownum'), 
            'users.name AS sender',
            'users.user_role',
            'users.photo',
            'message.id AS id',
            'message.subject AS subject',
            'message.message AS message',
            'message.datetime AS date',
            'message.receiver_status AS receiver_status',
        ])->where('message.receiver_id', Auth::id())
            ->whereNotIn('receiver_status', [2])
            ->leftJoin('users', 'users.id', '=', 'message.sender_id')
            ->orderBy('message.id', 'desc')
            ->get();
 
        $datatables = datatables()
            ->of($messages)
            ->addColumn('photo', function ($message) {
                return '<img src=" '.asset($message->photo?$message->photo:"public/assets/images/icons/user.png").' " width="60" height="40" />';
            })
            ->addColumn('sender', function ($message) {
                if ($message->user_role=="superadmin")
                    return  $message->sender.'<br><i class="label label-success">Super Admin</i>';
                else if ($message->user_role=="admin")
                    return  $message->sender.'<br><i class="label label-primary">Admin</i>';
                else
                    return  $message->sender.'<br><i class="label label-warning">Operator</i>';
            })
            ->addColumn('status', function ($message) {
                return (($message->receiver_status==0)?'<i class="label label-warning">Not Seen</i>':'<i class="label label-success">Seen</i>');
            }) 
            ->addColumn('action', function ($message) {
                return '<a href="'. url("admin/message/details/$message->id/inbox") .'" class="btn btn-xs btn-primary waves-effect"><i class="material-icons">remove_red_eye</i></a>
                <a  onclick="return confirm(\'Are you sure?\')" href="'. url("admin/message/delete/$message->id/inbox") .'" class="btn btn-xs btn-danger waves-effect"><i class="material-icons">delete</i></a></a>';
            })
            ->rawColumns(['photo','sender','action', 'status'])
            ->setTotalRecords(count($messages)); 

        return $datatables->make(true); 
    }
  
 
 
    # Show the sent message
    public function sent()
    {  
        $title = Lang::label('Sent Message');
        return view('admin.message.sent', compact('title'));
    }

    public function getSentData(Request $request)
    {
        DB::statement(DB::raw('set @rownum=0'));
        $messages = DB::table('message')->select([
             DB::raw('@rownum  := @rownum  + 1 AS rownum'),
            'users.name AS send_to', 
            'users.user_role',
            'users.photo',
            'message.id AS id',
            'message.subject AS subject',
            'message.message AS message',
            'message.datetime AS date',
            'message.receiver_status AS receiver_status',
        ])->where('sender_id', Auth::id())
            ->whereNotIn('sender_status', [2])
            ->leftJoin('users', 'users.id', '=', 'message.receiver_id')
            ->orderBy('message.id', 'desc')
            ->get();
 
        $datatables = datatables()
            ->of($messages)
            ->addColumn('photo', function ($message) {
                return '<img src=" '.asset($message->photo?$message->photo:"public/assets/images/icons/user.png").' " width="60" height="40" />';
            })
            ->addColumn('send_to', function ($message) { 
                if ($message->user_role=="superadmin")
                    return $message->send_to.'<br><i class="label label-success">Super Admin</i>';
                else if ($message->user_role=="admin")
                    return $message->send_to.'<br><i class="label label-primary">Admin</i>';
                else
                    return $message->send_to.'<br><i class="label label-warning">Operator</i>';
            })
            ->addColumn('status', function ($message) {
                return (($message->receiver_status==0)?'<i class="label label-warning">Not Seen</i>':'<i class="label label-success">Seen</i>');
            })
            ->addColumn('action', function ($message) {
                return '<a href="'. url("admin/message/details/$message->id/sent") .'" class="btn btn-xs btn-primary waves-effect"><i class="material-icons">remove_red_eye</i></a>
                <a  onclick="return confirm(\'Are you sure?\')" href="'. url("admin/message/delete/$message->id/sent") .'" class="btn btn-xs btn-danger waves-effect"><i class="material-icons">delete</i></a></a>';
            })
            ->rawColumns(['photo', 'send_to', 'action', 'status'])
            ->setTotalRecords(count($messages)); 

        return $datatables->make(true); 
    }
  

    # Details of a message
    public function details(Request $request)
    {  
        $title = Lang::label('Message Details');
        if ($request->type == "inbox") {
            DB::table('message')
                ->where('id', $request->id)
                ->update(['receiver_status' => 1]);
        } 
        #-------------------------------# 
        $message = collect(\DB::select("
            SELECT 
                message.*,
                u1.name AS sender,
                u2.name AS receiver
            FROM message
            LEFT JOIN 
                users u1 ON message.sender_id = u1.id
            LEFT JOIN 
                users u2 ON message.receiver_id = u2.id
            WHERE message.id = $request->id
        "))->first();

        return view('admin.message.details', compact('title', 'message'));
    }

    # Delete a message
    public function delete(Request $request)
    {
        if ($request->type == 'inbox') {
            DB::table('message')
                ->where('id', $request->id)
                ->update(['receiver_status' => 2]); 
                alert()->success(Lang::label("Delete Successful!"));   
                return redirect()->back();
        } else if ($request->type == "sent") {
            DB::table('message')
                ->where('id', $request->id)
                ->update(['sender_status' => 2]);   
                alert()->success(Lang::label("Delete Successful!")); 
                return redirect()->back();
        } else {
            alert()->error(Lang::label('Please Try Again.'));
            return redirect()->back();
        } 
    }


    public function notify()
    { 
        return Message::where('receiver_id', Auth::id())
            ->where('receiver_status', 0)
            ->count('id'); 
    }
   

}
