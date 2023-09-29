<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CfsPost;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Helpers\Helper;

class CfsPostController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function saveToken(Request $request)
    {
        auth()->user()->update(['device_token'=>$request->token]);
        return response()->json(['token saved successfully.']);
    }

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

        $cfs = DB::table('cfs_posts')->get();
        
        return view('cfs')->with('cfs', $cfs)
        ->with('allocations_notif', $allocations_notif)
        ->with('contributions_notif', $contributions_notif);
    }

    public function create()
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

        return view('cfs-create')
        ->with('allocations_notif', $allocations_notif)
        ->with('contributions_notif', $contributions_notif);
    }

    public function store(Request $request)
    {
        $cfs_banner = $request->file('banner_path');
        $cfs_banner_name = Str::uuid().'.'.$request->file('banner_path')->extension();
        $destination_path = public_path('/images/cfs_banners');

        // $destination_path_api = 'http://192.168.0.121:8000/images/cfs_banners';
        // upload banner
        $cfs_banner->move($destination_path,$cfs_banner_name);

        $cfs = new CfsPost;
        $cfs->title = $request->title;
        $cfs->details = $request->details;
        $cfs->request_items = $request->request_items;
        $cfs->banner_path = $cfs_banner_name;
        $cfs->is_yearend = $request->is_yearend;
        $cfs->is_active = 1;

        if($cfs->save() == 1) {
            Helper::sendNotification($request->title, $request->details);
            return redirect('call-for-support')->with('cfs-created','New Call for Support Successfully Added!');
        }
    }

    public function show($id)
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
        
        $cfs = CfsPost::findOrFail($id);
        return view('cfs-edit', compact('cfs'))
        ->with('allocations_notif', $allocations_notif)
        ->with('contributions_notif', $contributions_notif);
    }

    public function edit($id,Request $request)
    {
        
        $cfs = CfsPost::findOrFail($id);

        $cfs_banner = $request->file('banner_path');
        $cfs_banner_name = Str::uuid().'.'.$request->file('banner_path')->extension();
        $destination_path = public_path('/images/cfs_banners');

        $cfs_banner->move($destination_path, $cfs_banner_name);

        $cfs->title = $request->title;
        $cfs->details = $request->details;
        $cfs->request_items = $request->request_items;
        $cfs->banner_path = $cfs_banner_name;

        $cfs->is_yearend = $request->is_yearend;
        $cfs->is_active = $request->is_active;
        $cfs->updated_at = Carbon::parse($request->date);

        if($cfs->save()){
            return redirect('call-for-support')->with('cfs-updated','Call for Support Successfully Updated!');
        }

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
