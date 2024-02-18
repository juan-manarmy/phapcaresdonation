<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

use App\CfsPost;
use App\CfsRequest;
use App\CfsDonor;
use Auth;
use App\User;

class CfsPostControllerApi extends Controller
{
    public function index()
    {
        $cfsPosts = DB::table('cfs_posts')
        ->orderBy('id', 'DESC')
        ->where('is_active',1)
        ->get();

        return $cfsPosts;
    }

    public function getPostById($id) {
        $cfsPost = CfsPost::find($id);
        return $cfsPost;
    }

    public function getEventSelection() {
        $cfsPost = CfsPost::select('id','title','is_yearend','created_at')
        ->orderBy('id', 'DESC')
        ->where('is_active',1)
        ->get();
        return $cfsPost;
    }
    
    public function getPostRequests($id) {
        $cfsPost = CfsPost::find($id)->cfsrequests;
        // $donationName = $cfsPost->select('id','donation_name');
        return $cfsPost;
    }

    public function checkDonation() {

        $cfsPosts = CfsPost::all();
        $donatedCfs = array();
        // Checking if member id is match from post id
        // Checking  if this member have donated in this particular cfs

        foreach ($cfsPosts as $cfsPost) {
            
            $userMemberId = Auth::user()->member_id;
            $cfsDonors = CfsDonor::where('member_id', $userMemberId)
            ->where('cfs_post_id', $cfsPost->id)
            ->first();
           
            if($cfsDonors) {
                 // if theres an existing data add cfs 
                array_push($donatedCfs,$cfsPost);
            }
        }
        
        //return cfs that this member donated in that particular cfs
        return $donatedCfs;
    }

    public function getPostDonors($id) {
        // $cfsPost = CfsPost::find($id)->cfsdonors;
        $donors = CfsPost::join('cfs_donors','cfs_posts.id', '=', 'cfs_donors.cfs_post_id')
        ->join('members','cfs_donors.member_id', '=', 'members.id')
        ->where('cfs_posts.id', $id)
        ->get(['cfs_donors.id','cfs_donors.donation_type','members.member_name','members.member_logo_path']);
        // ->where('cfs_donors.donation_type', $donation_type)

        return $donors;
    }

    public function search($query) {
        // $search_text = $_GET['query'];
        $cfsPost = CfsPost::where('title','LIKE','%'.$query.'%')
        ->where('is_active',1)
        ->get();
        return $cfsPost;
    }
}
