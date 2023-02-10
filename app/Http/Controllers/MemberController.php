<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Member;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class MemberController extends Controller
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

        $companies = Member::all();
        return view('users-companies')->with('companies', $companies)
        ->with('allocations_notif', $allocations_notif)
        ->with('contributions_notif', $contributions_notif);
    }

    public function create()
    {
        
    }

    public function store(Request $request)
    {
        //
        $company_logo = $request->file('member_logo_path');
        $company_logo_name = Str::uuid().'.'.$request->file('member_logo_path')->extension();
        $destination_path = public_path('/images/company_logos');

        $company_logo->move($destination_path,$company_logo_name);

        $member = new Member;
        $member->member_name = $request->member_name;
        $member->member_logo_path = $company_logo_name;
        $member->status = 1;
        
        if( $member->save() == 1) {
            return redirect('users/companies')->with('company-created','Company successfully added!');
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

        $company = Member::findorFail($id);
        return view('users-edit-company', compact('company'))
        ->with('allocations_notif', $allocations_notif)
        ->with('contributions_notif', $contributions_notif);
    }
     
    public function edit($id, Request $request)
    {


        //
        $company = Member::findorFail($id);

        $company_logo = $request->file('member_logo_path');
        $company_logo_name = Str::uuid().'.'.$request->file('member_logo_path')->extension();
        $destination_path = public_path('/images/company_logos/');

        $company_logo->move($destination_path,$company_logo_name);

        $company->member_name = $request->member_name;
        $company->member_logo_path = $company_logo_name;
        $company->updated_at = Carbon::parse($request->date);

        if($company->save()){
            return redirect('users/companies')->with('company-created','Company Successfully Updated!');
        }
    }

    public function createCompanyView()
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

        return view('users-create-company')
        ->with('allocations_notif', $allocations_notif)
        ->with('contributions_notif', $contributions_notif);
    }

    public function destroy($id)
    {
        //
    }
}
