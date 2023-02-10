<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Contribution;
use App\Donation;
use App\Member;
use App\Beneficiary;
use Illuminate\Support\Carbon;

class BeneficiaryController extends Controller
{
    //
    public function beneficiaryList() {
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

        $beneficiaries = DB::table('beneficiaries')->select('id','name','created_at')->get();

        return view('beneficiary.beneficiary-list')
        ->with('beneficiaries', $beneficiaries)
        ->with('allocations_notif', $allocations_notif)
        ->with('contributions_notif', $contributions_notif);
    }

    public function editBeneficiary($id) {
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

        $beneficiary = Beneficiary::findOrFail($id);

        return view('beneficiary.beneficiary-edit')
        ->with('beneficiary', $beneficiary)
        ->with('allocations_notif', $allocations_notif)
        ->with('contributions_notif', $contributions_notif);
    }

    public function createBeneficiary() {
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

        return view('beneficiary.beneficiary-create')
        ->with('allocations_notif', $allocations_notif)
        ->with('contributions_notif', $contributions_notif);
    }

    public function createBeneficiarySubmit(Request $request) {
        $beneficiary = new Beneficiary;
        $beneficiary->name = $request->name;
        $beneficiary->save();
        return redirect('/users/beneficiaries')->with('beneficiary-created','New Beneficiary Successfully Added!');
    }

    public function editBeneficiarySubmit($id, Request $request) {
        $beneficiary = Beneficiary::findOrFail($id);
        $beneficiary->name = $request->name;
        $beneficiary->updated_at = Carbon::now();
        $beneficiary->save();
        return redirect('/users/beneficiaries')->with('beneficiary-edited','Beneficiary Successfully Updated!');
    }




}
