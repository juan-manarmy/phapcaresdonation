<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use App\Member;
use App\Role;
use Illuminate\Support\Facades\DB;


class UserController extends Controller
{

    public function index()
    {
        $contributions_notif = DB::table('contributions')
        ->join('members', 'contributions.member_id', '=', 'members.id')
        ->where('contributions.status',1)
        ->select('contributions.id','members.member_name','contributions.contribution_no','contributions.contribution_date','contributions.total_donation')
        ->orderBy('id', 'DESC')
        ->get();

        $allocations_notif = DB::table('allocations')
        ->join('beneficiaries', 'allocations.beneficiary_id', '=', 'beneficiaries.id')
        ->where('allocations.status',1)
        ->select('allocations.id','beneficiaries.name','allocations.total_allocated_products','allocations.allocation_no')
        ->orderBy('id', 'DESC')
        ->get();

        $users = DB::table('users')
        ->select('users.*','members.member_name')
        ->join('members', 'users.member_id', '=', 'members.id')
        ->get();
        // return $users;
        return view('users-allusers')->with('users', $users)
        ->with('allocations_notif', $allocations_notif)
        ->with('contributions_notif', $contributions_notif);
    }

    public function create()
    {
        //
    }

    public function saveUser(Request $request)
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
            $user->role_id = $request->role_id;
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
        $contributions_notif = DB::table('contributions')
        ->join('members', 'contributions.member_id', '=', 'members.id')
        ->where('contributions.status',1)
        ->select('contributions.id','members.member_name','contributions.contribution_no','contributions.contribution_date','contributions.total_donation')
        ->orderBy('id', 'DESC')
        ->get();

        $allocations_notif = DB::table('allocations')
        ->join('beneficiaries', 'allocations.beneficiary_id', '=', 'beneficiaries.id')
        ->where('allocations.status',1)
        ->select('allocations.id','beneficiaries.name','allocations.total_allocated_products','allocations.allocation_no')
        ->orderBy('id', 'DESC')
        ->get();

        $user = User::findOrFail($id);
        $companies = Member::all();
        $roles = Role::all();

        return view('users-edit', compact('user','companies','roles'))
        ->with('allocations_notif', $allocations_notif)
        ->with('contributions_notif', $contributions_notif);
        
    }

    public function edit($id, Request $request)
    {
        //
        $user = User::findOrFail($id);

        if($request->password != '') {

            $validated = $request->validate([
                'password' => ['required', 'string', 'min:8'],
            ]);

            if($validated) {
                $user->role_id = $request->role_id;
                $user->first_name = $request->first_name;
                $user->last_name = $request->last_name;
                $user->member_id = $request->member_id;
                $user->mobile_number = $request->mobile_number;
                $user->email_info = $request->email_info;
                $user->password = Hash::make($request->password);
                $user->updated_at = Carbon::parse($request->date);
                $user->save();
                return redirect('users')->with('user-updated','Profile Updated!');
            }

        } else {
            $user->role_id = $request->role_id;
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->member_id = $request->member_id;
            $user->mobile_number = $request->mobile_number;
            $user->email_info = $request->email_info;
            $user->updated_at = Carbon::parse($request->date);
            $user->save();
            return redirect('users')->with('user-updated','Profile Updated!');
        }
    }

    public function createUserView()
    {
        $contributions_notif = DB::table('contributions')
        ->join('members', 'contributions.member_id', '=', 'members.id')
        ->where('contributions.status',1)
        ->select('contributions.id','members.member_name','contributions.contribution_no','contributions.contribution_date','contributions.total_donation')
        ->orderBy('id', 'DESC')
        ->get();

        $allocations_notif = DB::table('allocations')
        ->join('beneficiaries', 'allocations.beneficiary_id', '=', 'beneficiaries.id')
        ->where('allocations.status',1)
        ->select('allocations.id','beneficiaries.name','allocations.total_allocated_products','allocations.allocation_no')
        ->orderBy('id', 'DESC')
        ->get();

        $companies = Member::all();
        $roles = Role::all();

        return view('users-create',compact('companies'))
        ->with('roles',$roles)
        ->with('allocations_notif', $allocations_notif)
        ->with('contributions_notif', $contributions_notif);
    }
    
}
