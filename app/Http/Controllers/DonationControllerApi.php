<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Donation;
use Auth;


class DonationControllerApi extends Controller
{
    public function show($cfsPostId,$donation_type)
    {
        //get user id
        $member_id = Auth::user()->member_id;
        // get donation by post id, donation type, member id
        $donations = Donation::where('cfs_post_id',$cfsPostId)
        ->where('donation_type',$donation_type)
        ->where('member_id',$member_id)
        ->get();

        return $donations;
    }

    public function getBeneficiaries($id){
        $beneficiaries = Donation::find($id)->beneficiaries;
        return $beneficiaries;
    }
}
