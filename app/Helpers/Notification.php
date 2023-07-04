<?php
namespace App\Helpers;
use DB, Auth;
 
class Notification
{
    public static function message()
    {
        return DB::table('message')->select([ 
                'users.user_role',
                'users.photo', 
                'users.name AS sender',
                'message.id AS id',
                'message.subject AS subject',
                'message.message AS message',
                'message.datetime AS date',
                'message.receiver_status AS receiver_status',
            ])
            ->where('message.receiver_id', Auth::id())
            ->where('message.receiver_status', 0)
            ->whereNotIn('message.receiver_status', [2])
            ->leftJoin('users', 'users.id', '=', 'message.sender_id')
            ->orderBy('message.id', 'desc')
            ->limit(25) 
            ->get(); 
    }
 


}
