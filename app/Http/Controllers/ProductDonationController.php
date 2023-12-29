<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Member;
use App\Contribution;
use App\Donation;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Document;

class ProductDonationController extends Controller
{
 
    public function cancelContribution(Request $request) {
        $contribution = Contribution::findOrFail($request->id);
        $donation = Donation::where('contribution_id',$request->id)->delete();
        $contribution->delete();
        return redirect()->route('contribution-list')->with('contribution-cancelled','Contribution successfully cancelled.');
    }

    public function secondaryDetailsView($contribution_id)
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

        return view('product-donation.pd-secondary-details')
        ->with('contribution_id', $contribution_id)
        ->with('allocations_notif', $allocations_notif)
        ->with('contributions_notif', $contributions_notif);
    }

    public function saveSecondaryDetails($contribution_id, Request $request) {

        $contribution = Contribution::findOrFail($contribution_id);

        $contribution->pickup_address = $request->pickup_address;
        $contribution->pickup_contact_person = $request->pickup_contact_person;
        $contribution->pickup_contact_no = $request->pickup_contact_no;
        $contribution->pickup_date = new Carbon($request->pickup_date);
        $contribution->delivery_address = $request->delivery_address;
        $contribution->delivery_contact_person = $request->delivery_contact_person;
        $contribution->delivery_contact_no = $request->delivery_contact_no;
        $contribution->delivery_date = new Carbon($request->delivery_date);
        $contribution->requester_user_id = 5;
        $contribution->tel_no = $request->tel_no;
        $contribution->fax_no = $request->fax_no;
        $contribution->email = $request->email;
        $contribution->status = 1;

        $contribution->save();

        return redirect()->route('pd-finish');
    }

    public function initialDetailsView()
    {
        $members = Member::all();

        $currentDate = Carbon::now();
        $randomStr = strtoupper(Str::random(8));
        $cn_no = $currentDate->format('ymd');
        $cn_no = $cn_no.$randomStr;
        
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

        $cfs = DB::table('cfs_posts')
        ->where('is_active', 1)
        ->select('id','title')
        ->get();

        return view('product-donation.pd-initial-details')
        ->with('cfs',$cfs)
        ->with('members', $members)
        ->with('cn_no', $cn_no)
        ->with('allocations_notif', $allocations_notif)
        ->with('contributions_notif', $contributions_notif);
    }

    public function initialDetailsViewRead($id)
    {
        $members = Member::all();

        $contribution = Contribution::find($id);

        if($contribution == null) {
            // if user trying to access not existing contribution. redirect back to list
            return redirect()->route('contribution-list');
        }

        $cn_no = $contribution->contribution_no;

        // $contribution = Contribution::where('contribution_no', $contribution_no)
        // ->select('id','contribution_no','member_id','distributor','contribution_date')
        // ->first();

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

        $cfs = DB::table('cfs_posts')
        ->where('is_active', 1)
        ->select('id','title')
        ->get();


        return view('product-donation.pd-initial-details')
        ->with('cfs',$cfs)
        ->with('members', $members)
        ->with('contribution', $contribution)
        ->with('cn_no', $cn_no)
        ->with('allocations_notif', $allocations_notif)
        ->with('contributions_notif', $contributions_notif);
    }

    public function saveInitialDetails(Request $request)
    {
        $converted_contribution_date = new Carbon($request->contribution_date);
        
        $contribution = Contribution::updateOrCreate(
            ['contribution_no' => $request->contribution_no],
            ['cfs_id' => $request->cfs_id,
            'member_id' => $request->member_id,
            'distributor' => $request->distributor,
            'inventory_location' => $request->inventory_location,
            'contribution_date' => $converted_contribution_date]
        );
        
        
        $contribution_id = $contribution->id;
        $contribution_no = $contribution->contribution_no;
        return redirect()->route('pd-donations', ['contribution_id' =>  $contribution_id, 'contribution_no' =>  $contribution_no]);
    }

    public function donationsView($contribution_id, $contribution_no)
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

        return view('product-donation.pd-donations')->with('contribution_id',$contribution_id)
        ->with('allocations_notif', $allocations_notif)
        ->with('contributions_notif', $contributions_notif)
        ->with('contribution_no',$contribution_no);
    }

    public function showDonations($contribution_id)
    {
        $donations = Donation::where('contribution_id', $contribution_id)->get();
        return $donations;
    }

    public function saveProofToDocuments($contribution_id,$proof_deposit) 
    {
        $document = new Document;
        $document->contribution_id = $contribution_id;
        $document->type = 'Proof of Deposit';
        $document->name = 'Monetary';
        $document->directory = "/images/monetary/{$proof_deposit}";
        $document->save();

        return $document->id;
    }

    public function uploadMonetary(Request $request) {
        $new_donation = new Donation;
        $new_donation->contribution_id = $request->contribution_id;
        $new_donation->product_type = $request->product_type;
        $new_donation->total = $request->total;
        $new_donation->product_name = 'Monetary';
        $new_donation->product_code = 'Monetary';
        $new_donation->quantity = 1;
        $new_donation->unit_cost = $request->total;

        $proof_deposit = $request->file('proof_deposit');
        $proof_deposit_name = Str::uuid().'.'.$request->file('proof_deposit')->extension();
        $destination_path = public_path('/images/monetary');
        // upload banner
        $proof_deposit->move($destination_path,$proof_deposit_name);
        // $new_donation->proof_deposit = $proof_deposit_name;
        $new_donation->status = 1;

        $document_id = $this->saveProofToDocuments($request->contribution_id,$proof_deposit_name);
        $new_donation->document_id  = $document_id ;
        
        $new_donation->save();

        return $new_donation;
    }

    public function saveDonation($production_id, Request $request)
    {
        // new Carbon($request->contribution_date)
        $new_donation = new Donation;
        $new_donation->product_type = $request->donation["product_type"];
        $new_donation->contribution_id = $production_id;
        $new_donation->product_name = $request->donation["product_name"];
        $new_donation->generic_name = $request->donation["generic_name"];
        $new_donation->strength = $request->donation["strength"];
        $new_donation->dosage_form = $request->donation["dosage_form"];
        $new_donation->package_size = $request->donation["package_size"];
        $new_donation->quantity = $request->donation["quantity"];
        $new_donation->lot_no = $request->donation["lot_no"];
        $new_donation->mfg_date = new Carbon($request->donation["mfg_date"]);
        $new_donation->expiry_date = new Carbon($request->donation["expiry_date"]);
        $new_donation->unit_cost = $request->donation["unit_cost"];
        $new_donation->medicine_status = $request->donation["medicine_status"];
        $new_donation->total = $request->donation["unit_cost"] * $request->donation["quantity"];
        // $random = rand(1,10000);
        // $new_donation->job_no = "JO-{$random}";
        // $new_donation->product_code = "JO-{$random}";
        // $new_donation->drug_reg_no = "DR-{$random}";
        // $new_donation->uom = $request->donation["uom"];
        // $new_donation->remarks = $request->donation["remarks"];
        $new_donation->status = 1;
        $new_donation->save();

        return $new_donation;
    }

    public function updateDonationToDrafts($donation_id, Request $request)
    {
        if($request->product_type == 1 || $request->product_type == 2) {
            $existing_donation = Donation::findOrFail($donation_id);
            $contribution = Contribution::findOrFail($existing_donation->contribution_id);
            $contribution_no = $contribution->contribution_no;
            $contribution_id = $contribution->id;
    
            $existing_donation->product_name = $request["product_name"];
            $existing_donation->generic_name = $request["generic_name"];
            $existing_donation->strength = $request["strength"];
            $existing_donation->dosage_form = $request["dosage_form"];
            $existing_donation->package_size = $request["package_size"];
            $existing_donation->quantity = $request["quantity"];
            $existing_donation->lot_no = $request["lot_no"];
            $existing_donation->expiry_date = new Carbon($request["expiry_date"]);
            $existing_donation->drug_reg_no = $request["drug_reg_no"];
            $existing_donation->unit_cost = $request["unit_cost"];
            $existing_donation->medicine_status = $request["medicine_status"];
            $existing_donation->total = $request["unit_cost"] * $request["quantity"];
            $existing_donation->save();
        }

        if($request->product_type == 3) {
            $existing_donation = Donation::findOrFail($donation_id);
            $contribution = Contribution::findOrFail($existing_donation->contribution_id);
            $contribution_no = $contribution->contribution_no;
            $contribution_id = $contribution->id;
            $existing_donation->total = $request->unit_cost;
            $existing_donation->unit_cost = $request->unit_cost;
            
            if($request->proof_deposit) {
                $proof_deposit = $request->file('proof_deposit');
                $existing_document_name = $request->current_proof_deposit;
                $proof_deposit_name = $existing_document_name;
                $destination_path = public_path('/images/monetary');
                // upload banner overwrite to public images
                $proof_deposit->move($destination_path,$proof_deposit_name);
            }
            $existing_donation->save();
        }

        return redirect()->route('pd-donations', ['contribution_id' => $contribution_id, 'contribution_no' =>  $contribution_no]);
    }

    public function deleteDonation($id)
    {
        $existing_donation = Donation::findOrFail($id);
        $existing_donation->delete();
        return "Existing donation successfully deleted";
    }

    public function saveTotalDonations($contribution_id, Request $request) {

        // {total_medicine}/{total_promats}/{total_donation}
        $contribution = Contribution::findOrFail($contribution_id);

        $contribution->total_medicine = $request->total_donations["medicine_total_donation"];
        $contribution->total_promats = $request->total_donations["promats_total_donation"];
        $contribution->total_monetary = $request->total_donations["monetary_total_donation"];
        $contribution->total_donation = $request->total_donations["total_products_amount"];
        $contribution->save();
        // return $contribution;
        // redirect()->route('pd-secondary-details', ['contribution_id' =>  $contribution_id]);
    }

    public function updateDonationsView($donation_id)
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

        $donation = Donation::findOrFail($donation_id);

        $monetary_path = '';
        
        if($donation->product_type == 3) {
            $monetary_path = Document::find($donation->document_id);
            $monetary_path = $monetary_path->directory;
        }

        // update monetary img when updated 
        return view('product-donation.pd-update')
        ->with('monetary_path', $monetary_path)
        ->with('donation', $donation)
        ->with('allocations_notif', $allocations_notif)
        ->with('contributions_notif', $contributions_notif);
    }

    public function finishView()
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

        return view('product-donation.pd-finish')
        ->with('allocations_notif', $allocations_notif)
        ->with('contributions_notif', $contributions_notif);
    }
}
