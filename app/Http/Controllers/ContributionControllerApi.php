<?php
namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;
use Auth;

use App\Form;
use App\Contribution;
use App\CfsDonor;

use App\Donation;
use App\Inventory;
use App\AllocatedProduct;
use App\CfsPost;
use App\User;

use App\Document;

class ContributionControllerApi extends Controller
{
    
    public function getContributions() {
        $member_id = Auth::user()->member_id;
        $contributions = Contribution::where('member_id', $member_id)
        ->where('status','!=', 0)
        ->join('cfs_posts', 'contributions.cfs_id', '=', 'cfs_posts.id')
        ->select('contributions.id','cfs_id','contribution_no','contribution_date','status','contributions.updated_at','title','distributor')
        ->orderBy('contributions.id', 'DESC')
        ->get();
        return $contributions;
    }

    public function getCompanyMember($member_id) {
        $users = User::where('users.status',1)
        ->where('users.member_id',$member_id)
        ->join('members', 'users.member_id', '=', 'members.id')
        ->select('users.id','first_name','last_name','member_name')
        ->orderBy('members.id', 'DESC')
        ->get();
        return $users;
    }

    // public function getContributionById($id) {
    //     $contribution = Contribution::find($id)
    //     ->join('cfs_posts', 'contributions.cfs_id', '=', 'cfs_posts.id')
    //     ->select('contributions.id','cfs_id','contribution_no','contribution_date','status','contributions.updated_at','title')
    //     ->get();
    //     return $contribution;
    // }

    public function getCfsDonors ($cfs_id) {
        $cfsDonors = CfsDonor::where('cfs_id', $cfs_id)
        ->join('members', 'cfs_donors.member_id', '=', 'members.id')
        ->select('cfs_donors.id','cfs_id','cfs_donors.member_id','is_inkind','is_medicine','is_cash','member_name','member_logo_path')
        ->orderBy('cfs_donors.id', 'DESC')
        ->get();
        return $cfsDonors;
    }

    public function getApprovedContribution($contribution_id) {

        $contribution = Contribution::where('contributions.id', $contribution_id)
        ->join('cfs_posts', 'contributions.cfs_id', '=', 'cfs_posts.id')
        ->select(
        'contributions.id',
        'contribution_no',
        'cfs_id',
        'distributor',
        'contribution_date',
        'pickup_address',
        'pickup_contact_person',
        'pickup_contact_no',
        'pickup_date',
        'delivery_address',
        'delivery_contact_person',
        'delivery_contact_no',
        'delivery_date',
        'requester_user_id',
        'tel_no',
        'email',
        'status',
        'title')
        ->first();

        $requester = User::where('users.status',1)
        ->where('users.id',$contribution->requester_user_id)
        ->join('members', 'users.member_id', '=', 'members.id')
        ->select('users.id','first_name','last_name','member_name')
        ->orderBy('members.id', 'DESC')
        ->first();

        $contribution->requester = $requester->member_name.': '.$requester->last_name.', '.$requester->first_name;
        
        return $contribution;
    }

    public function getAllocatedByInventory($inventory_id) {
        
        $allocated_products = AllocatedProduct::where('inventory_id', $inventory_id)
        ->where('is_allocated',1)
        ->join('allocations', 'allocated_products.allocation_id', '=', 'allocations.id')
        ->join('beneficiaries', 'allocations.beneficiary_id', '=', 'beneficiaries.id')
        ->select('allocated_products.id',
        'allocated_products.product_name',
        'allocated_products.quantity',
        'allocated_products.unit_cost',
        'allocated_products.total',
        'allocated_products.is_allocated',
        'allocated_products.created_at',
        'beneficiaries.name')
        ->get();

        return $allocated_products;
    }

    public function getContributionsDrafts() {
        $member_id = Auth::user()->member_id;
        $contributions = Contribution::where('member_id', $member_id)
        ->where('status', 0)
        ->join('cfs_posts', 'contributions.cfs_id', '=', 'cfs_posts.id')
        ->select('contributions.id','cfs_id','contribution_no','contribution_date','status','contributions.updated_at','title','distributor')
        ->orderBy('contributions.id', 'DESC')
        ->get();
        return $contributions;
    }

    public function getContributionsDraftsCount() {
        $member_id = Auth::user()->member_id;
        $contributions = Contribution::where('member_id', $member_id)
        ->where('status', 0)
        ->get();
        return ['draftsCount' => $contributions->count()];
    }

    public function getContributionDonation($contribution_id) {
        $donations = Donation::where('contribution_id', $contribution_id)
        ->orderBy('id', 'DESC')
        ->get();
        return $donations;
    }

    public function getDonations($contribution_id) {
        $donations = Donation::where('contribution_id', $contribution_id)
        ->where('product_type','!=', 0)
        ->orderBy('id', 'DESC')
        ->get();
        return $donations;
    }

    public function getMedicineDonation($contribution_id) {

        $donations = Donation::where('contribution_id', $contribution_id)
        ->where('product_type', 1)
        ->orderBy('id', 'DESC')
        ->get();

        return $donations;
    }

    public function getPromatsDonation($contribution_id) {
        $donations = Donation::where('contribution_id', $contribution_id)
        ->where('product_type', 2)
        ->orderBy('id', 'DESC')
        ->get();
        return $donations;
    }

    public function getMonetaryDonation($contribution_id) {

        $donations = DB::table('donations')
        ->where('donations.product_type',3)
        ->join('documents', 'donations.document_id', '=', 'documents.id')
        ->where('donations.contribution_id',$contribution_id)
        ->select('donations.id','donations.unit_cost','documents.directory',)
        ->orderBy('donations.id', 'DESC')
        ->get();

        return $donations;
    }

    public function getTotalDonations($contribution_id) {
        $promatsDonations = Donation::where('contribution_id', $contribution_id)
        ->where('product_type', 2)
        ->get();

        $medicineDonations = Donation::where('contribution_id', $contribution_id)
        ->where('product_type', 1)
        ->get();

        $monetaryDonations = Donation::where('contribution_id', $contribution_id)
        ->where('product_type', 3)
        ->get();

        return ['promatsCount' => $promatsDonations->count(), 
        'medicineCount' => $medicineDonations->count(),
        'medicineTotalAmount' => $medicineDonations->sum('total'),
        'promatsTotalAmount' => $promatsDonations->sum('total'),
        'monetaryTotalAmount' => $monetaryDonations->sum('total'),
        ];
    }

    public function saveInitialDetails(Request $request)
    {
        $responseCode = 10;
        $converted_contribution_date = new Carbon($request->contribution_date);

        $contribution = new Contribution;

        // create contribution number
        $currentDate = Carbon::now();
        $randomStr = strtoupper(Str::random(8));
        $cn_no = $currentDate->format('ymd');
        $cn_no = $cn_no.$randomStr;

        $contribution->contribution_no = $cn_no;
        $contribution->cfs_id =  $request->cfs_id;
        $contribution->member_id = $request->member_id;
        $contribution->distributor = $request->distributor;
        $contribution->inventory_location = 'ZPC';
        $contribution->contribution_date = $converted_contribution_date;

        $contribution->save();

        $contribution_id = $contribution->id;

        if($contribution->save() == 1) {
            $responseCode = 20;
        }

        return ['responseId' => $contribution_id];
    }

    public function updateInitialDetails($id, Request $request)
    {
        $responseCode = 10;
        $converted_contribution_date = new Carbon($request->contribution_date);

        $contribution = Contribution::find($id);
        $contribution->cfs_id =  $request->cfs_id;
        $contribution->member_id = $request->member_id;
        $contribution->distributor = $request->distributor;
        $contribution->contribution_date = $converted_contribution_date;
        $contribution->save();

        $contribution_id = $contribution->id;

        return ['responseId' => $contribution_id];
    }

    public function saveSecondaryDetails($contribution_id, Request $request) {
        $responseCode = 10;
        $contribution = Contribution::findOrFail($contribution_id);
        $contribution->requester_user_id = $request->requester_user_id;
        $contribution->pickup_address = $request->pickup_address;
        $contribution->pickup_contact_person = $request->pickup_contact_person;
        $contribution->pickup_contact_no = $request->pickup_contact_no;
        $contribution->pickup_date = new Carbon($request->pickup_date);
        $contribution->delivery_address = $request->delivery_address;
        $contribution->delivery_contact_person = $request->delivery_contact_person;
        $contribution->delivery_contact_no = $request->delivery_contact_no;
        $contribution->delivery_date = new Carbon($request->delivery_date);
        $contribution->requester_user_id = Auth::user()->member_id;
        $contribution->tel_no = $request->tel_no;
        $contribution->fax_no = $request->tel_no;
        $contribution->email = $request->email;
        $contribution->status = 1;
        if($contribution->save() == 1) {
            $responseCode = 20;
        }
        return ['responseId' => $contribution_id];
    }

    public function saveMedicineDonation($contribution_id, Request $request)
    {
        $responseCode = 10;

        $donation = new Donation;
        $donation->contribution_id = $contribution_id;
        $donation->product_type = $request->product_type;
        $donation->product_name = $request->product_name;
        $donation->generic_name = $request->generic_name;
        $donation->strength = $request->strength;
        $donation->dosage_form = $request->dosage_form;
        $donation->package_size = $request->package_size;
        $donation->quantity = $request->quantity;
        $donation->lot_no = $request->lot_no;
        $donation->mfg_date = new Carbon($request->mfg_date);
        $donation->expiry_date = new Carbon($request->expiry_date);
        $donation->drug_reg_no = $request->drug_reg_no;
        $donation->unit_cost = $request->unit_cost;
        $donation->medicine_status = $request->medicine_status;
        $donation->total = $request->unit_cost * $request->quantity;
        $donation->status = 1;

        if($donation->save() == 1) {
            $responseCode = 20;
        }

        return ['responseCode' => $responseCode];
    }

    public function updateMedicineDonation($id, Request $request)
    {
        $responseCode = 10;

        $donation = Donation::find($id);
        $donation->product_name = $request->product_name;
        $donation->generic_name = $request->generic_name;
        $donation->strength = $request->strength;
        $donation->dosage_form = $request->dosage_form;
        $donation->package_size = $request->package_size;
        $donation->quantity = $request->quantity;
        $donation->lot_no = $request->lot_no;
        $donation->mfg_date = new Carbon($request->mfg_date);
        $donation->expiry_date = new Carbon($request->expiry_date);
        $donation->drug_reg_no = $request->drug_reg_no;
        $donation->unit_cost = $request->unit_cost;
        $donation->medicine_status = $request->medicine_status;
        $donation->total = $request->unit_cost * $request->quantity;

        if($donation->save() == 1) {
            $responseCode = 20;
        }

        return ['responseCode' => $responseCode];
    }

    public function savePromatsDonation($contribution_id, Request $request)
    {
        $responseCode = 10;

        $donation = new Donation;
        $donation->contribution_id = $contribution_id;
        $donation->product_type = $request->product_type;
        $donation->product_name = $request->product_name;
        $donation->quantity = $request->quantity;
        $donation->lot_no = $request->lot_no;
        $donation->mfg_date = new Carbon($request->mfg_date);
        $donation->expiry_date = new Carbon($request->expiry_date);
        $donation->unit_cost = $request->unit_cost;
        $donation->total = $request->unit_cost * $request->quantity;
        $donation->status = 1;

        if($donation->save() == 1) {
            $responseCode = 20;
        }

        return ['responseCode' => $responseCode];
    }

    public function updatePromatsDonation($id, Request $request)
    {
        $responseCode = 10;

        $donation = Donation::find($id);
        $donation->product_name = $request->product_name;
        $donation->quantity = $request->quantity;
        $donation->lot_no = $request->lot_no;
        $donation->mfg_date = new Carbon($request->mfg_date);
        $donation->expiry_date = new Carbon($request->expiry_date);
        $donation->unit_cost = $request->unit_cost;
        $donation->total = $request->unit_cost * $request->quantity;

        if($donation->save() == 1) {
            $responseCode = 20;
        }

        return ['responseCode' => $responseCode];
    }



    public function saveMonetaryDonation($contribution_id, Request $request)
    {
        $responseCode = 10;

        $new_donation = new Donation;
        $new_donation->contribution_id = $request->contribution_id;
        $new_donation->product_type = $request->product_type;
        $new_donation->total = $request->unit_cost;
        $new_donation->product_name = 'Monetary';
        $new_donation->product_code = 'Monetary';
        $new_donation->quantity = 1;
        $new_donation->unit_cost = $request->unit_cost;

        $proof_deposit = $request->file('proof_deposit');
        $proof_deposit_name = Str::uuid().'.'.$request->file('proof_deposit')->extension();
        $destination_path = public_path('/images/monetary');

        // upload banner
        $proof_deposit->move($destination_path,$proof_deposit_name);
        $new_donation->status = 1;

        $document_id = $this->saveProofToDocuments($request->contribution_id,$proof_deposit_name);
        $new_donation->document_id  = $document_id;

        if($new_donation->save() == 1) {
            $responseCode = 20;
        }

        return ['responseCode' => $responseCode];
    }

    public function updateMonetaryDonation($id, Request $request)
    {
        $responseCode = 10;

        $donation = Donation::find($id);
        $donation->unit_cost = $request->unit_cost;
        $donation->total = $request->unit_cost;

        if($request->proof_deposit) {
            $proof_deposit = $request->file('proof_deposit');
            $existing_document_name = $request->current_proof_deposit;
            $proof_deposit_name = $existing_document_name;
            $destination_path = public_path('/images/monetary');
            // upload banner overwrite to public images
            $proof_deposit->move($destination_path,$proof_deposit_name);
        }
        
        if($donation->save() == 1) {
            $responseCode = 20;
        }

        return ['responseCode' => $responseCode];
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

    public function saveTotalDonation($contribution_id, Request $request) {

        $responseCode = 10;
        $contribution = Contribution::findOrFail($contribution_id);

        $contribution->total_medicine = $request->total_medicine;
        $contribution->total_promats = $request->total_promats;
        $contribution->total_monetary = $request->total_monetary;
        $contribution->total_donation = $request->total_donation;

        if($contribution->save() == 1) {
            $responseCode = 20;
        }

        return ['responseCode' => $responseCode];
    }

    public function deleteDonation($id)
    {
        $existing_donation = Donation::findOrFail($id);
        $existing_donation->delete();
        return ['responseCode' => "success"];
    }

    public function deleteContribution($id)
    {
        // 100 not found
        // 300 is deleted
        $contributionStatus = '100';
        $donationStatus = '100';

        $contribution = Contribution::find($id);
        $donations = Donation::where('contribution_id', $id)->get();
        
        if($contribution !== null) {
            $contribution->delete();
            $contributionStatus = '300';
        }

        if($donations->isNotEmpty()) {
            $donations->each->delete();
            $donationStatus = '300';
        }

        // $existing_donation = Contribution::where('id', $id)->findOrFail();

        return [
            'contributionStatus' => $contributionStatus,
            'donationStatus' => $donationStatus
        ];
    }


}
