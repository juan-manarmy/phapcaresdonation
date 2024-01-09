<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\User;

class UserControllerApi extends Controller
{
    public function show()
    {
        $userId = Auth::id();
        $user = User::join('members','users.member_id', '=', 'members.id')
        ->where('users.id', $userId)
        ->get(['users.*','members.member_name','members.member_logo_path'])
        ->first();
        return $user;
    }

    public function updateDeviceToken(Request $request)
    {
        $userId = Auth::id();
        $user = User::findOrFail($userId);
        $user->device_token = $request->device_token;
        $user->save();
        return ['deviceToken' => $user->device_token];
    }

}
