<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthControllerApi extends Controller
{
    public function register(Request $request) 
    {
        $request->validate([
            'first_name'=>'required',
            'last_name'=>'required',
            'email'=>'required|unique:users',
            'password'=>'required'
        ]);

        $user = User::create(['first_name'=>$request->first_name,
                              'last_name'=>$request->last_name,
                              'member_id'=>$request->member_id,
                              'mobile_number'=>$request->mobile_number,
                              'email'=>$request->email,
                              'password'=> Hash::make($request->password)]);
        return $user;
    }

    public function login(Request $request) 
    {
        $request->validate([
            'email'=>'required',
            'password'=>'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            // returns json
            return ['errors'=> 'The provided credentials are incorrect.'];
        }
        // returns json
        return ['token' => $user->createToken('my-token')->plainTextToken];
    }
}
