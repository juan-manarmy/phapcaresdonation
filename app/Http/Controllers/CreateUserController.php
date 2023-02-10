<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

use Illuminate\Support\Facades\Validator;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Hash;
use App\Member;

use Auth;

class CreateUserController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    
    public function index()
    {
        //
        $companies = Member::all();
        return view('users-create',compact('companies'));
    }

    protected function create(Request $request)
    {
        


        // $user = new User;
        // $user->f_name = $request->f_name;
        // $user->l_name = $request->l_name;
        // $user->email = $request->email;
        // $user->password = Hash::make($request->password);
        // $user->save();
    }

    public function store(Request $request)
    {
        //
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string','confirmed', 'min:8'],
        ]);

        if($validated) {
            $user = new User;
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->member_id = $request->member_id;
            $user->mobile_number = $request->mobile_number;
            $user->email_info = $request->email_info;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->save();
    
            if($user->save() == 1) {
                return redirect('users')->with('user-created','User successfully added!');
            } else {

            }

        }
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

 
    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
