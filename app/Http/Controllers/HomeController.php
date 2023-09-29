<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Inventory;
use App\Contribution;
use App\Allocation;
use App\TransactionReport;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
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

        return view('home')
        ->with('allocations_notif', $allocations_notif)
        ->with('contributions_notif', $contributions_notif);
    }


    public function carbontest() {
        // $currentDateMonth = $carbon->format('d M, Y');
        // $currentDateYear = $carbon->year;

        // $current_date = Carbon::now();
        $input = '1/1/2023';
        $date = strtotime($input);
        $date = date('M d, Y', $date);

        $currentDate = Carbon::now();
        $lastSummaryDate = date("m/d/Y", strtotime("-1 month", strtotime($currentDate)));
        $lastSummaryMonth = Carbon::parse($lastSummaryDate)->format('m');
        $lastSummaryYear = Carbon::parse($lastSummaryDate)->format('Y');

        $allocation = \App\Allocation::where('id', 0)
        ->select('dna_no','dodrf_no')
        ->get();

        $dodrfNo = "";
        $dnaNo = "";
 
        foreach($allocation as $item){
            $dodrfNo = $item->dodrf_no;
            $dnaNo = $item->dna_no;
        }

        return $dodrfNo;
    }

    public function destructiontest() {
        //Transaction List Query
        $transaction_reports = TransactionReport::where('member_id',1)
        ->where('year',2022)
        ->where('month',12)
        ->where('status',1)
        ->where('transaction_type','DES')
        ->get();

        return $transaction_reports;
    }
}
