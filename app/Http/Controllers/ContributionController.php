<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Contribution;
use App\Donation;
use App\Member;
use Illuminate\Support\Carbon;
use Codedge\Fpdf\Fpdf\Fpdf;
use App\Document;
use App\Inventory;
use App\TransactionReport;
use App\Summary;
use App\Helpers\Helper;

use Auth;

class ContributionController extends Controller
{
    public function contributionList()
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

        $promats_count = 0;
        $medicine_count = 0;
        $total_count = 0;

        $contributions = DB::table('contributions')
        ->join('members', 'contributions.member_id', '=', 'members.id')
        ->select('contributions.id','contribution_no','distributor','contribution_date','total_donation','contributions.status','members.member_name')
        ->where('contributions.status','!=',0)
        ->get();

        $contributions_drafts = DB::table('contributions')
        ->join('members', 'contributions.member_id', '=', 'members.id')
        ->where('contributions.status', 0)
        ->select('contributions.id','contribution_no','distributor','contribution_date','total_donation','contributions.status','members.member_name')
        ->get();

        return view('contributions.contributions-list')->with('contributions', $contributions)
        ->with('contributions_drafts', $contributions_drafts)
        ->with('allocations_notif', $allocations_notif)
        ->with('contributions_notif', $contributions_notif);
    }

    public function contributionDetails($contribution_id)
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

        $contribution = Contribution::findOrFail($contribution_id);
        $documents = Document::where('contribution_id', $contribution_id)->get();

        $promats_count = 0;
        $medicine_count = 0;
        $total_count = 0;
        
        $total_quantity = 0;
        $total_amount = 0;

        $cancelled_total_quantity = 0;
        $cancelled_total_donation = 0;

        $donations = Donation::where('contribution_id', $contribution_id)->get();
        $member = Member::findOrFail($contribution->member_id);

        $member_name = $member->member_name;
        $product_code_missing = 0;

        foreach ($donations as $donation) {

            if($donation->product_code == null && $donation->status != 2) {
                $product_code_missing++;
            }

            if($donation->product_type == 1) {
                $medicine_count += 1;
            } else {
                $promats_count += 1;
            }

            if($donation->status == 1) {
                $total_quantity += $donation->quantity;
                $total_amount += $donation->total;
            }

            if($donation->status != 1) {
                $cancelled_total_quantity += $donation->quantity;
                $cancelled_total_donation += $donation->total;
            }
        }

        $total_count = $medicine_count + $promats_count;

        return view('contributions.contributions-details')
        ->with('product_code_missing',$product_code_missing)
        ->with('donations',$donations)
        ->with('contribution', $contribution)
        ->with('promats_count', $promats_count)
        ->with('medicine_count', $medicine_count)
        ->with('total_count', $total_count)
        ->with('total_quantity', $total_quantity)
        ->with('total_amount', $total_amount)
        ->with('cancelled_total_quantity', $cancelled_total_quantity)
        ->with('cancelled_total_donation', $cancelled_total_donation)
        ->with('member_name', $member_name)
        ->with('documents', $documents)
        ->with('allocations_notif', $allocations_notif)
        ->with('contributions_notif', $contributions_notif);
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
        
        return view('contributions.contributions-product-edit')
        ->with('donation', $donation)
        ->with('allocations_notif', $allocations_notif)
        ->with('contributions_notif', $contributions_notif);
    }

    public function updateDonation($donation_id, Request $request)
    {
        $existing_donation = Donation::findOrFail($donation_id);

        $contribution_id = $existing_donation->contribution_id;

        $existing_donation->product_type = $request["product_type"];
        $existing_donation->product_code = $request["product_code"];
        $existing_donation->product_name = $request["product_name"];
        $existing_donation->generic_name = $request["generic_name"];
        $existing_donation->strength = $request["strength"];
        $existing_donation->dosage_form = $request["dosage_form"];
        $existing_donation->package_size = $request["package_size"];
        $existing_donation->quantity = $request["quantity"];
        $existing_donation->lot_no = $request["lot_no"];
        $existing_donation->mfg_date = new Carbon($request["mfg_date"]);
        $existing_donation->expiry_date = new Carbon($request["expiry_date"]);
        $existing_donation->drug_reg_no = $request["drug_reg_no"];
        $existing_donation->unit_cost = $request["unit_cost"];
        $existing_donation->medicine_status = $request["medicine_status"];
        $existing_donation->total = $request["unit_cost"] * $request["quantity"];
        $existing_donation->job_no = $request["job_no"];
        $existing_donation->uom = $request["uom"];
        $existing_donation->remarks = $request["remarks"];
        $existing_donation->save();

        $newTotalDonation = DB::table('donations')
        ->where('contribution_id', '=', $contribution_id)
        ->sum('donations.total');
        
        $contribution = Contribution::findOrFail($contribution_id);
        $contribution->total_donation = $newTotalDonation;
        $contribution->save();

        $this->createNODForm($contribution_id);
        $this->createDNDForm($contribution_id);

        return redirect()->route('contribution-details', ['contribution_id' => $existing_donation->contribution_id]);
    }
    
    public function updateContributionStatus($contribution_id, Request $request)
    {
        $contribution = Contribution::findOrFail($contribution_id);
        $contribution_no = $contribution->contribution_no;

        $member_id = $contribution->member_id;

        $user_id = Auth::id();
        // $contribution->status = $request->status;

        // 2 Rejecting NOD 3 Approve NOD
        if($request->status == 2 || $request->status == 3) {
            $this->verifyNOD($request->contribution_id,
            $request->contribution_date,
            $request->distributor,
            $request->pickup_address,
            $request->pickup_contact_person,
            $request->pickup_contact_no,
            $request->pickup_date,
            $request->delivery_address,
            $request->delivery_contact_person,
            $request->delivery_contact_no,
            $request->delivery_date,
            $request->reasons_rejected_contribution,
            $request->status);
        }

        // Rejecting DONATION ACCEPTED
        if($request->status == 4) {
            $this->verifyDonation($request->contribution_id,
            $request->contribution_date,
            $request->distributor,
            $request->pickup_address,
            $request->pickup_contact_person,
            $request->pickup_contact_no,
            $request->pickup_date,
            $request->delivery_address,
            $request->delivery_contact_person,
            $request->delivery_contact_no,
            $request->delivery_date,
            $request->reasons_rejected_donation,
            $request->status);
        }
        // Approve DONATION ACCEPTED
        if($request->status == 5) {
            $this->verifyDonation($request->contribution_id,
            $request->contribution_date,
            $request->distributor,
            $request->pickup_address,
            $request->pickup_contact_person,
            $request->pickup_contact_no,
            $request->pickup_date,
            $request->delivery_address,
            $request->delivery_contact_person,
            $request->delivery_contact_no,
            $request->delivery_date,
            $request->reasons_rejected_donation,
            $request->status);
            
            $this->createNODForm($contribution_id);
            $this->saveDocuments($contribution_id,$contribution_no,"NOD");
        }

        // Rejecting DND
        if($request->status == 6) {
            $this->verifyDND($request->contribution_id,$request->contribution_date,$request->distributor,$request->dnd_no,$request->dnd_date,$request->notice_to,$request->dnd_contact_person,
            $request->pickup_contact_no,$request->pickup_date,$request->pickup_address,$request->pickup_instructions,$request->reasons_rejected_dnd,$request->status);
        }
        // Approve DND
        if($request->status == 7) {
            $this->verifyDND($request->contribution_id,$request->contribution_date,$request->distributor,$request->dnd_no,$request->dnd_date,$request->notice_to,$request->dnd_contact_person,
            $request->pickup_contact_no,$request->pickup_date,$request->pickup_address,$request->pickup_instructions,$request->reasons_rejected_dnd,$request->status);
            
            $this->createDNDForm($contribution_id);

            $contributionDND = Contribution::findOrFail($contribution_id);
            $dnd_no = $contributionDND->dnd_no;

            $this->saveDNDDocument($contribution_id,$contribution_no,"DND",$dnd_no);
        }

        // Rejecting DIDRF
        if($request->status == 8) {
            $this->verifyDIDRF($request->contribution_id,$request->contribution_date,$request->distributor,$request->notice_to,$request->pickup_contact_person,
            $request->pickup_contact_no,$request->pickup_date,$request->pickup_address,$request->pickup_instructions,$request->didrf_no,$request->daff_no,$request->reasons_rejected_inbound,$request->status);
        }
        // Approve DIDRF
        if($request->status == 9) {
            $didrf_file = $request->file('didrf_file');
            $didrf_file_name = $contribution_no."_DIDRF".'.'.$request->file('didrf_file')->extension();
            // $destination_path = $_SERVER["DOCUMENT_ROOT"].'/pdf/didrf';
            $destination_path = public_path('/pdf/didrf');
            
            // upload file
            $didrf_file->move($destination_path,$didrf_file_name);
            // save data to documents db
            $this->saveDocuments($contribution_id,$contribution_no,"DIDRF");
            // save donations to inventory for allocation usage
            $this->saveDonationsToInventory($contribution_id,$member_id);

            $this->verifyDIDRF($request->contribution_id,$request->contribution_date,$request->distributor,$request->notice_to,$request->pickup_contact_person,
            $request->pickup_contact_no,$request->pickup_date,$request->pickup_address,$request->pickup_instructions,$request->didrf_no,$request->daff_no,$request->reasons_rejected_inbound,$request->status);
        }

        return back();
    }

    public function verifyNOD($contribution_id,$contribution_date,$distributor,$pickup_address,$pickup_contact_person,
    $pickup_contact_no,$pickup_date,$delivery_address,$delivery_contact_person,$delivery_contact_no,$delivery_date,
    $reasons_rejected_contribution,$status) {
        $contribution = Contribution::find($contribution_id);
        $contribution->contribution_date = new Carbon($contribution_date);
        $contribution->distributor = $distributor;
        $contribution->pickup_address = $pickup_address;
        $contribution->pickup_contact_person = $pickup_contact_person;
        $contribution->pickup_contact_no = $pickup_contact_no;
        $contribution->pickup_date = new Carbon($pickup_date);
        $contribution->delivery_address = $delivery_address;
        $contribution->delivery_contact_person = $delivery_contact_person;
        $contribution->delivery_contact_no = $delivery_contact_no;
        $contribution->delivery_date = new Carbon($delivery_date);
        $contribution->reasons_rejected_contribution = $reasons_rejected_contribution;
        $contribution->status = $status;

        $contribution->approval_date = Carbon::now()->format('Y-m-d');

        $member_id = $contribution->member_id;
        $contribution_no = $contribution->contribution_no;

        if($status == 2) {
            Helper::sendNotificationContribution($contribution_no, 'Contribution Rejected',$member_id);
        } 
        if($status == 3) {
            Helper::sendNotificationContribution($contribution_no, 'Contribution Approved!',$member_id);
        } 
        $contribution->save();
    }

    public function verifyDonation($contribution_id,$contribution_date,$distributor,$pickup_address,$pickup_contact_person,
    $pickup_contact_no,$pickup_date,$delivery_address,$delivery_contact_person,$delivery_contact_no,$delivery_date,
    $reasons_rejected_donation,$status) {
        $contribution = Contribution::find($contribution_id);
        $contribution->contribution_date = new Carbon($contribution_date);
        $contribution->distributor = $distributor;
        $contribution->pickup_address = $pickup_address;
        $contribution->pickup_contact_person = $pickup_contact_person;
        $contribution->pickup_contact_no = $pickup_contact_no;
        $contribution->pickup_date = new Carbon($pickup_date);
        $contribution->delivery_address = $delivery_address;
        $contribution->delivery_contact_person = $delivery_contact_person;
        $contribution->delivery_contact_no = $delivery_contact_no;
        $contribution->delivery_date = new Carbon($delivery_date);
        $contribution->reasons_rejected_donation = $reasons_rejected_donation;
        $contribution->status = $status;
        $contribution->verified_date = Carbon::now()->format('Y-m-d');

        $member_id = $contribution->member_id;
        $contribution_no = $contribution->contribution_no;

        if($status == 4) {
            Helper::sendNotificationContribution($contribution_no, 'Donation Rejected',$member_id);
        } 
        if($status == 5) {
            Helper::sendNotificationContribution($contribution_no, 'Donation Accepted! Your Notice of Donation form is now available.',$member_id);
        } 

        $contribution->save();
    }

    public function verifyDND($contribution_id,$contribution_date,$distributor,$dnd_no,$dnd_date,$notice_to,$dnd_contact_person,
    $pickup_contact_no,$pickup_date,$pickup_address,$pickup_instructions,$reasons_rejected_dnd,$status) {

        $user_id = Auth::id();
        $contribution = Contribution::find($contribution_id);
        $contribution->contribution_date = new Carbon($contribution_date);
        $contribution->distributor = $distributor;
        $contribution->dnd_no = $dnd_no;
        $contribution->dnd_date = new Carbon($dnd_date);
        $contribution->notice_to = $notice_to;
        $contribution->dnd_contact_person = $dnd_contact_person;
        $contribution->pickup_contact_no = $pickup_contact_no;
        $contribution->pickup_date = new Carbon($pickup_date);
        $contribution->pickup_address = $pickup_address;
        $contribution->pickup_instructions = $pickup_instructions;
        $contribution->reasons_rejected_dnd = $reasons_rejected_dnd;
        $contribution->status = $status;

        $contribution->reasons_rejected_dnd = $reasons_rejected_dnd;
        $contribution->dnd_approval_date = Carbon::now()->format('Y-m-d');
        $contribution->dnd_approval_user_id = $user_id;
        $contribution->save();
    }

    public function verifyDIDRF($contribution_id,$contribution_date,$distributor,$notice_to,$pickup_contact_person,
    $pickup_contact_no,$pickup_date,$pickup_address,$pickup_instructions,$didrf_no,$daff_no,$reasons_rejected_inbound,$status) {

        $user_id = Auth::id();
        $contribution = Contribution::find($contribution_id);
        $contribution->contribution_date = new Carbon($contribution_date);
        $contribution->distributor = $distributor;
        $contribution->notice_to = $notice_to;
        $contribution->pickup_contact_person = $pickup_contact_person;
        $contribution->pickup_contact_no = $pickup_contact_no;
        $contribution->pickup_date = new Carbon($pickup_date);
        $contribution->pickup_address = $pickup_address;
        $contribution->pickup_instructions = $pickup_instructions;
        $contribution->reasons_rejected_inbound = $reasons_rejected_inbound;
        $contribution->inbound_date = Carbon::now()->format('Y-m-d');
        $contribution->didrf_no = $didrf_no;
        $contribution->daff_no = $daff_no;
        $contribution->status = $status;
        $contribution->didrf_approval_date = Carbon::now()->format('Y-m-d');
        $contribution->didrf_approval_user_id = $user_id;
        $contribution->save();
    }

    public function cancelDonation($contribution, Request $request)
    {
        $existing_donation = Donation::findOrFail($request->donation_id);
        $existing_donation->status = 2;
        $existing_donation->save();

        $contribution = Contribution::findOrFail($contribution);

        if($existing_donation->product_type == 1) {
            $contribution->total_medicine -= $existing_donation->total;
            $contribution->total_donation -= $existing_donation->total;
        }

        if($existing_donation->product_type == 2) {
            $contribution->total_promats -= $existing_donation->total;
            $contribution->total_donation -= $existing_donation->total;
        }

        $contribution->save();
        
        return back();
    }

    public function createDNDForm($contribution_id)
    {
        $contribution = Contribution::findOrFail($contribution_id);

        $contribution_no = $contribution->contribution_no;
        $number = rand(1,10000);

        $dnd_no = $contribution->dnd_no;

        $member = Member::where('id', $contribution->member_id)
        ->select('member_name')->firstOrFail();

        $donations = Donation::where('contribution_id',$contribution_id)
        ->where('status',1)
        ->get();

        $dnd_date = date('m/d/Y', strtotime($contribution->dnd_date));
        $pickup_date = date('m/d/Y', strtotime($contribution->pickup_date));

        $email = Auth::user()->email;
        $first_name = Auth::user()->first_name;
        $last_name = Auth::user()->last_name;
        $contact_info = Auth::user()->mobile_number;

        $pdf = new FPDF('P','mm','A4');
        $pdf->AddPage();

        $dndTemplate = public_path("/images/templates/dndTemplate.jpg");
        $signature01 = public_path("/images/templates/signature01.png");
        $signature02 = public_path("/images/templates/signature02.png");

        // $dndTemplate = url("/images/templates/dndTemplate.jpg");
        // $signature01 = url("/images/templates/signature01.png");
        // $signature02 = url("/images/templates/signature02.png");

        $pdf->Image($dndTemplate,0,0,0,297);
        $pdf->Image($signature01,21,260,0,12);
        $pdf->Image($signature02,70,260,0,8);

        //PDF Contents
        $pdf->SetXY(174,20);
        $pdf->SetTextColor(43,43,43);	
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(0,0,"2022-{$number}");
        
        $pdf->SetXY(20,36);
        $pdf->SetTextColor(43,43,43);
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(0,0,"{$contribution->distributor}"); //notice_to
        
        $pdf->SetXY(141,36);
        $pdf->SetTextColor(43,43,43);	
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(0,0,"{$dnd_date}"); // $dndDate
        
        $pdf->SetXY(20,54);
        $pdf->SetTextColor(43,43,43);	
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(0,0,"{$member->member_name}");
        
        $pdf->SetXY(20,65);
        $pdf->SetTextColor(43,43,43);	
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(0,0,"{$contribution->pickup_address}");
        
        $pdf->SetXY(20,75);
        $pdf->SetTextColor(43,43,43);	
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(0,0,"{$contribution->dnd_contact_person}");
        
        $pdf->SetXY(100,75);
        $pdf->SetTextColor(43,43,43);	
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(0,0,"{$contribution->pickup_contact_no}");
        
        $pdf->SetXY(158,75);
        $pdf->SetTextColor(43,43,43);	
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(0,0,"{$pickup_date}");
        
        $pdf->SetXY(20,86);
        $pdf->SetTextColor(43,43,43);	
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(0,0,"{$contribution->pickup_instructions}");

        //Set Y Positions
        $formRowArray = array(106,111.5,117,122,127,132,138,143,149,154,159,164,170,175,180,186,191,196,201,207,212,217,223,228,233);
        $formRowCount = 0;

        foreach ($donations as $donation) {

            $product_type = $donation['product_type'];
            $product_name = $donation['product_name'];	
            $generic_name =  $donation['generic_name'];
            $strength =  $donation['strength'];
            $dosage_form =  $donation['dosage_form'];
            $package_size =  $donation['package_size'];
            $quantity =  $donation['quantity'];
            $lot_no =  $donation['lot_no'];
            $expiry_date = date('m/d/Y', strtotime($donation['expiry_date']));

            $generic_name = $product_name." ".$generic_name." ".$strength." ".$dosage_form." ".$package_size;
				
            $pdf->SetXY(16,"{$formRowArray[$formRowCount]}");
            $pdf->SetTextColor(43,43,43);	
            $pdf->SetFont('Arial','',8);
            $pdf->Cell(0,0,"{$member->member_name}");
            
            $pdf->SetXY(63,"{$formRowArray[$formRowCount]}");
            $pdf->SetTextColor(43,43,43);	
            $pdf->SetFont('Arial','',7);
            $pdf->Cell(0,0,"{$product_name}");
            
            $pdf->SetXY(132,"{$formRowArray[$formRowCount]}");
            $pdf->SetTextColor(43,43,43);	
            $pdf->SetFont('Arial','',8);
            $pdf->Cell(0,0,"{$expiry_date}");
            
            $pdf->SetXY(159,"{$formRowArray[$formRowCount]}");
            $pdf->SetTextColor(43,43,43);	
            $pdf->SetFont('Arial','',8);
            $pdf->Cell(0,0,"{$lot_no}");
            
            $pdf->SetXY(184,"{$formRowArray[$formRowCount]}");
            $pdf->SetTextColor(43,43,43);	
            $pdf->SetFont('Arial','',8);
            $pdf->Cell(0,0,"{$quantity}");
            
            $formRowCount++;
        }

        $pdf->SetXY(16,257.5);
        $pdf->SetTextColor(43,43,43);	
        $pdf->SetFont('Arial','',8);
        $pdf->Cell(0,0,"Program Officer");
        
        $pdf->SetXY(59,257.5);
        $pdf->SetTextColor(43,43,43);	
        $pdf->SetFont('Arial','',8);
        $pdf->Cell(0,0,"Managing Director");
        
        $pdf->SetXY(27,270);
        $pdf->SetTextColor(43,43,43);	
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(0,0,"Dennis Tuazon");
        
        $pdf->SetXY(62,270);
        $pdf->SetTextColor(43,43,43);	
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(0,0,"Dr. Maria Rosita Q. Siasoco");

        $dnd_file_name = $contribution_no."_DND_".$dnd_no;
        // $destination_path = "/pdf/nod/{$contribution_no}_NOD.pdf";
        $dnd_directory_path = public_path("/pdf/dnd/{$dnd_file_name}.pdf");

        $pdf->Output($dnd_directory_path,'F');

    }

    public function createNODForm($contribution_id)
    {
        $contribution = Contribution::findOrFail($contribution_id);
        $contribution_no = $contribution->contribution_no;

        $member = Member::where('id', $contribution->member_id)
        ->select('member_name')->firstOrFail();

        $contribution_date = date('F, d Y', strtotime($contribution->contribution_date));
        $pickup_date = date('m/d/Y', strtotime($contribution->pickup_date));
        $delivery_date = date('m/d/Y', strtotime($contribution->delivery_date));
        $total_donation = number_format($contribution->total_donation,2);

        $status = $contribution->status;
        $reasons_rejected_donation = $contribution->reasons_rejected_donation;

        $verified_date = date('F, d Y', strtotime($contribution->verified_date));

        $template_path = public_path("/images/templates/nodTemplate1.jpg");
        // $template_path = urL("/images/templates/nodTemplate1.jpg");

        // Header 
        $pdf = new FPDF('L','mm','Legal');
        $pdf->AddPage();
        $pdf->Image($template_path,0,0,350,220);

        $pdf->SetXY(67,35);
        $pdf->SetTextColor(43,43,43);	
        $pdf->SetFont('Arial','',10);
        $pdf->Cell(0,0,"{$member->member_name}");
        
        $pdf->SetXY(69,39);
        $pdf->SetTextColor(43,43,43);	
        $pdf->SetFont('Arial','',10);
        $pdf->Cell(0,0,"{$contribution->distributor}");
        
        $pdf->SetXY(40,43);
        $pdf->SetTextColor(43,43,43);	
        $pdf->SetFont('Arial','',10);
        $pdf->Cell(0,0,"{$contribution_date}");

        $donations = Donation::where('contribution_id', $contribution_id)
        ->where('status',1)
        ->get();

        // Adding Donation Items
        $positionY = 74;
        foreach ($donations as $donation) {
            $expiry_date = date('m/d/Y', strtotime($donation->expiry_date));
            
            $pdf->SetXY(11,"{$positionY}");
            $pdf->SetTextColor(43,43,43);	
            $pdf->SetFont('Arial','',7);
            $pdf->Cell(0,0,"{$donation->product_name}");
            
            $pdf->SetXY(43,"{$positionY}");
            $pdf->SetTextColor(43,43,43);	
            $pdf->SetFont('Arial','',7);
            $pdf->Cell(0,0,"{$donation->generic_name}");
            
            $pdf->SetXY(76,"{$positionY}");
            $pdf->SetTextColor(43,43,43);	
            $pdf->SetFont('Arial','',8);
            $pdf->Cell(0,0,"{$donation->strength}");
            
            $pdf->SetXY(107,"{$positionY}");
            $pdf->SetTextColor(43,43,43);	
            $pdf->SetFont('Arial','',8);
            $pdf->Cell(0,0,"{$donation->dosage_form}");
            
            $pdf->SetXY(127,"{$positionY}");
            $pdf->SetTextColor(43,43,43);	
            $pdf->SetFont('Arial','',8);
            $pdf->Cell(0,0,"{$donation->package_size}");
            
            $pdf->SetXY(148,"{$positionY}");
            $pdf->SetTextColor(43,43,43);	
            $pdf->SetFont('Arial','',8);
            $pdf->Cell(0,0,"{$donation->quantity}");
            
            $pdf->SetXY(160,"{$positionY}");
            $pdf->SetTextColor(43,43,43);	
            $pdf->SetFont('Arial','',8);
            $pdf->Cell(0,0,"{$donation->lot_no}");
            
            $pdf->SetXY(185,"{$positionY}");
            $pdf->SetTextColor(43,43,43);	
            $pdf->SetFont('Arial','',8);
            $pdf->Cell(0,0,"{$expiry_date}");
            
            $pdf->SetXY(208,"{$positionY}");
            $pdf->SetTextColor(43,43,43);
            $pdf->SetFont('Arial','',8);
            $pdf->Cell(0,0,"{$donation->drug_reg_no}");
            
            $pdf->SetXY(237,"{$positionY}");
            $pdf->SetTextColor(43,43,43);
            $pdf->SetFont('Arial','',8);
            $pdf->Cell(0,0,"{$donation->unit_cost}");
            
            $pdf->SetXY(265,"{$positionY}");
            $pdf->SetTextColor(43,43,43);
            $pdf->SetFont('Arial','',8);
            $pdf->Cell(0,0,"{$donation->total}");
            
            $pdf->SetXY(294,"{$positionY}");
            $pdf->SetTextColor(43,43,43);
            $pdf->SetFont('Arial','',8);
            $pdf->Cell(0,0,"{$donation->medicine_status}");
            
            $positionY = $positionY + 5;
        }

        $pdf->SetXY(265,133);
        $pdf->SetTextColor(43,43,43);	
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(0,0,"{$total_donation}");
        

        $email = Auth::user()->email;
        $first_name = Auth::user()->first_name;
        $last_name = Auth::user()->last_name;
        $contact_info = Auth::user()->mobile_number;

        $pdf->AddPage();

        $template_path_nod_2 = public_path("/images/templates/nodTemplate2.jpg");
        // $template_path_nod_2 = urL("images/templates/nodTemplate2.jpg");

        $pdf->Image($template_path_nod_2,0,0,350,220);
        
        $pdf->SetXY(40,31);
        $pdf->SetTextColor(43,43,43);	
        $pdf->SetFont('Arial','',10);
        $pdf->Cell(0,0,"{$contribution->pickup_address} / {$contribution->pickup_contact_person} / {$contribution->pickup_contact_no} / {$pickup_date}");			
        
        $pdf->SetXY(210,31);
        $pdf->SetTextColor(43,43,43);
        $pdf->SetFont('Arial','',10);
        $pdf->Cell(0,0,"{$contribution->delivery_address} / {$contribution->delivery_contact_person} / {$contribution->delivery_contact_no} / {$delivery_date}");

        $pdf->SetXY(11,65);
        $pdf->SetTextColor(43,43,43);	
        $pdf->SetFont('Arial','',10);
        $pdf->Cell(0,0,"{$first_name} {$last_name}");
        
        $pdf->SetXY(140,69);
        $pdf->SetTextColor(43,43,43);	
        $pdf->SetFont('Arial','',10);
        $pdf->Cell(0,0,"{$contact_info}");
        
        $pdf->SetXY(213,69);
        $pdf->SetTextColor(43,43,43);	
        $pdf->SetFont('Arial','',10);
        $pdf->Cell(0,0,"{$email}");
        
        $pdf->SetXY(11,77);
        $pdf->SetTextColor(43,43,43);	
        $pdf->SetFont('Arial','',10);
        $pdf->Cell(0,0,"Administrator");

        //Phap Cares Portion
        if ($status == 5) {
            $pdf->SetXY(11,96);
            $pdf->SetTextColor(43,43,43);	
            $pdf->SetFont('Arial','B',10);
            $pdf->Cell(0,0,"X");
        } else {
            $pdf->SetXY(11,104);
            $pdf->SetTextColor(43,43,43);	
            $pdf->SetFont('Arial','B',10);
            $pdf->Cell(0,0,"X");
            
            $pdf->SetXY(14,112);
            $pdf->SetTextColor(43,43,43);	
            $pdf->SetFont('Arial','',10);
            $pdf->Cell(0,0,"{$reasons_rejected_donation}");
        }

        $pdf->SetXY(211,109);
        $pdf->SetTextColor(43,43,43);	
        $pdf->SetFont('Arial','',10);
        $pdf->Cell(0,0,"{$verified_date}");

        $pdf->SetXY(270,109);
        $pdf->SetTextColor(43,43,43);	
        $pdf->SetFont('Arial','',10);
        $pdf->Cell(0,0,"{$first_name} {$last_name}");
        $destination_path = public_path("/pdf/nod/{$contribution_no}_NOD.pdf");
        // $destination_path = $_SERVER["DOCUMENT_ROOT"]."/pdf/nod/{$contribution_no}_NOD.pdf";

        $pdf->Output($destination_path,'F');
    }

    public function saveDocuments($contribution_id,$contribution_no,$type) 
    {
        $document = new Document;
        $document->contribution_id = $contribution_id;
        $document->type = $type;
        $document->name = "{$contribution_no}_{$type}";
        
        if($type == "NOD") {
            $document->directory = "/pdf/nod/{$contribution_no}_{$type}";
            // $document->directory = "/pdf/nod/{$contribution_no}_{$type}";
        } else if($type == "DND") {
            $document->directory = "/pdf/dnd/{$contribution_no}_{$type}";
        } else if($type == "DIDRF") {
            $document->directory = "/pdf/didrf/{$contribution_no}_{$type}";
        }
        $document->save();
    }

    public function saveDNDDocument($contribution_id,$contribution_no,$type,$dnd_no) 
    {
        $document = new Document;
        $document->contribution_id = $contribution_id;
        $document->type = $type;
        $document->name = "{$contribution_no}_{$type}_{$dnd_no}";
        $document->directory = "/pdf/dnd/{$contribution_no}_{$type}_{$dnd_no}";
        $document->save();
    }

    public function uploadDidrf($file) 
    {
        $didrf_file = $request->file('didrf_file');
        $didrf_file_name = Str::uuid().'.'.$request->file('didrf_file')->extension();

        $destination_path = $_SERVER["DOCUMENT_ROOT"]."/pdf/nod/{$contribution_no}_NOD.pdf";
        // $destination_path = public_path('/images/cfs_banners');
        // upload file
        $didrf_file->move($destination_path,$didrf_file_name);
    }

    //Download DIDRF Form
    public function downloadDIDRFForm($id) 
    {
        $document = Document::findOrFail($id);
		//File Name
		$name = $document->name.".pdf";
		$directory = $document->directory.".pdf";
        $destination_path = public_path($directory);

        return response()->download($destination_path);
	}

    public function saveDonationsToInventory($contribution_id,$member_id) 
    {
        $contribution = Contribution::findOrFail($contribution_id);
        $contribution_no =  $contribution->contribution_no;
        $inventory_location =  $contribution->inventory_location;


        $donations = Donation::where('contribution_id', $contribution_id)
        ->where('status',1)->get();
        
        foreach($donations as $donation) {
            $product_code = $donation->product_code;
            $product_type = $donation->product_type;
            $product_name =  $donation->product_name;
            $generic_name =  $donation->generic_name;
            $strength =  $donation->strength;
            $dosage_form =  $donation->dosage_form;
            $package_size =  $donation->package_size;
            $quantity =  $donation->quantity;
            $lot_no =  $donation->lot_no;
            $mfg_date =  $donation->mfg_date;
            $expiry_date =  $donation->expiry_date;
            $drug_reg_no =  $donation->drug_reg_no;
            $unit_cost =  $donation->unit_cost;
            $total =  $donation->total;
            $job_no =  $donation->job_no;
            $remarks =  $donation->remarks;
            $status =  $donation->status;

            $product_name = $product_name." ".$generic_name." ".$strength." ".$dosage_form." ".$package_size;

            // Add donations to inventory 
            //Search Inventory If Donation Details Is Existing
            $inventory = Inventory::where('member_id', $member_id)
            ->where('product_code', $product_code)
            ->where('lot_no', $lot_no)
            ->where('unit_cost', $unit_cost)
            ->get();

            if($inventory->count() == 0) {
                //Insert Details If Unique Member Id, Product Code, Lot No and Unit Cost
                $inventory = new Inventory;
                $inventory->member_id = $member_id; 
                $inventory->product_type = $product_type;
                $inventory->product_code = $product_code;
                $inventory->product_name = $product_name;
                $inventory->quantity = $quantity;
                $inventory->lot_no = $lot_no;
                $inventory->mfg_date = $mfg_date;
                $inventory->expiry_date = $expiry_date;
                $inventory->drug_reg_no = $drug_reg_no;
                $inventory->unit_cost = $unit_cost;
                $inventory->total = $total;
                $inventory->job_no = $job_no;
                $inventory->save();
            } else {

                //Update Details If Existing Member Id, Product Code, Lot No and Unit Cost
                //Get The Details
                foreach($inventory as $inventoryDetails) {
                    $inventoryId = $inventoryDetails['id'];
                    $existingQuantity = $inventoryDetails['quantity'];
                }
 
                //Add Quantity From The Existing Quantity To Compute The Inventory Product Stocks
                $inventoryQuantity = $quantity + $existingQuantity;
                
                //Compute Inventory Product Value
                $inventoryTotal = $inventoryQuantity * $unit_cost;
                $existing_inventory = Inventory::find($inventoryId);
                $existing_inventory->quantity = $inventoryQuantity;
                $existing_inventory->total = $inventoryTotal;
                $existing_inventory->updated_at = Carbon::now();

                //Update Inventory
                $existing_inventory->save();
            }

            // Add Transaction Report
            $currentDate = Carbon::now();
            $currentDateMonth = $currentDate->month;
            $currentDateYear = $currentDate->year;

            $lastSummaryDate = date("m/d/Y", strtotime("-1 month", strtotime($currentDate)));
            $lastSummaryMonth = Carbon::parse($lastSummaryDate)->format('m');
            $lastSummaryYear = Carbon::parse($lastSummaryDate)->format('Y');

            //Get The Ending Balance Last Month Summary
            $summaryLastMonth = Summary::where('member_id',$member_id)
            ->where('month',$lastSummaryMonth)
            ->where('year',$lastSummaryYear)
            ->where('product_code',$product_code)
            ->where('lot_no',$lot_no)
            ->where('unit_cost',$unit_cost)->get();

            $lastSummaryEndingBalanceQuantity = 0;

            foreach($summaryLastMonth as $summaryDetails) {
                $lastSummaryEndingBalanceQuantity = $summaryDetails['ending_balance_quantity'];
            }

            //Set The Ending Balance Last Month As Opening Balance For The Current Month
            $openingBalanceQuantity = $lastSummaryEndingBalanceQuantity;

            $transaction_report = new TransactionReport;
            $transaction_report->member_id = $member_id;
            $transaction_report->contribution_id = $contribution_id;
            $transaction_report->beneficiary_id = 0;
            $transaction_report->allocation_id = 0;
            $transaction_report->destruction_id = 0;
            $transaction_report->inventory_id = 0;
            $transaction_report->month = $currentDateMonth;
            $transaction_report->year = $currentDateYear;
            $transaction_report->contribution_no = $contribution_no;
            $transaction_report->product_code = $product_code;
            $transaction_report->product_name = $product_name;
            $transaction_report->lot_no = $lot_no;
            $transaction_report->opening_balance_quantity = $openingBalanceQuantity;
            $transaction_report->transaction_type = 'IMP';
            $transaction_report->quantity = $quantity;
            $transaction_report->unit_cost = $unit_cost;
            $transaction_report->receipt_quantity = $quantity;
            $transaction_report->receipt_amount = $total;
            $transaction_report->mfg_date = $mfg_date;
            $transaction_report->expiry_date = $expiry_date;
            $transaction_report->remarks = $remarks;
            $transaction_report->job_no = $job_no;
            $transaction_report->status = $status;
            $transaction_report->inventory_location = $inventory_location;
            $transaction_report->save(); 

            if($status == 1) {
                $this->processSummary();

                //Update Summary										 
                //Get The Current Summary Month And Year Of Product
                $summaryThisMonth = Summary::where('member_id',$member_id)
                ->where('month',$currentDateMonth)
                ->where('year',$currentDateYear)
                ->where('product_code',$product_code)
                ->where('lot_no',$lot_no)
                ->where('unit_cost',$unit_cost)->get();

                $summaryId = 0;
                $movementsQuantity = 0;

                foreach($summaryThisMonth as $summaryDetails) {
                    $summaryId = $summaryDetails->id;
                    $movementsQuantity = $summaryDetails->movements_quantity;
                }
                
                //Compute For The Updated Quantity Movements
                $movementsQuantity = $movementsQuantity + $quantity; //Operation Is Addition Because The Tranasction Is Import
                
                //Compute For The Updated Ending Balance Quantity
                $endingBalanceQuantity = $lastSummaryEndingBalanceQuantity + $movementsQuantity;
                
                //Compute For The Updated Ending Balance Value
                $endingBalanceValue = $endingBalanceQuantity * $unit_cost;
                
                //Insert Updated Product Summary
                $updateSummary = Summary::findOrFail($summaryId);
                $updateSummary->movements_quantity = $movementsQuantity;
                $updateSummary->ending_balance_quantity = $endingBalanceQuantity;
                $updateSummary->ending_balance_value = $endingBalanceValue;
                $updateSummary->updated_at = $currentDate;
                $updateSummary->save();
            }
        }

    }

    public function saveTransactionReport ($contribution_id) 
    {

        $donations = Donation::where('contribution_id', $contribution_id)
        ->where('status',1)
        ->get();

        $contribution = Contribution::findOrFail($contribution_id);
        $member_id = $contribution->member_id;
        $contribution_no =  $contribution->contribution_no;
        $inventory_location =  $contribution->inventory_location;

        $currentDate = Carbon::now();
        $currentDateMonth = $currentDate->month;
        $currentDateYear = $currentDate->year;

        //Generate Transaction Report
        //Get The Opening Balance By Getting The Ending Balance From Last Month In Summary Table

        foreach($donations as $donationDetails) {
            $productType = $donationDetails['product_type'];
            $productCode = $donationDetails['product_code'];
            $productName =  $donationDetails['product_name'];
            $genericName =  $donationDetails['generic_name'];
            $strength =  $donationDetails['strength'];
            $dosageForm =  $donationDetails['dosage_form'];
            $packageSize =  $donationDetails['package_size'];
            $quantity =  $donationDetails['quantity'];
            $lotNo =  $donationDetails['lot_no'];
            $mfgDate =  $donationDetails['mfg_date'];
            $expiryDate =  $donationDetails['expiry_date'];
            $drugRegNo =  $donationDetails['drug_reg_no'];
            $unitCost =  $donationDetails['unit_cost'];
            $total =  $donationDetails['total'];
            $jobNo =  $donationDetails['job_no'];
            $donationStatus =  $donationDetails['status'];
            
            $productName = $productName." ".$genericName." ".$strength." ".$dosageForm." ".$packageSize;

            $lastSummaryEndingBalanceQuantity = 0;

            $lastSummaryDate = date("m/d/Y", strtotime("-1 month", strtotime($currentDate)));
            $lastSummaryMonth = Carbon::parse($lastSummaryDate)->format('m');
            $lastSummaryYear = Carbon::parse($lastSummaryDate)->format('Y');

            //Get The Ending Balance Last Month Summary
            $summaryLastMonth = Summary::where('member_id',$member_id)
            ->where('month',$lastSummaryMonth)
            ->where('year',$lastSummaryYear)
            ->where('product_code',$productCode)
            ->where('lot_no',$lotNo)
            ->where('unit_cost',$unitCost)->get();

            foreach($summaryLastMonth as $item) {
                $lastSummaryEndingBalanceQuantity = $item->ending_balance_quantity;
            }

            $openingBalanceQuantity = $lastSummaryEndingBalanceQuantity;

            $transaction_report = new TransactionReport;
            $transaction_report->member_id = $member_id;
            $transaction_report->contribution_id = $contribution_id;
            $transaction_report->month = $currentDateMonth;
            $transaction_report->year = $currentDateYear;
            $transaction_report->contribution_no = $contribution_no;
            $transaction_report->product_code = $productCode;
            $transaction_report->product_name = $productName;
            $transaction_report->lot_no = $lotNo;
            $transaction_report->opening_balance_quantity = $openingBalanceQuantity;
            $transaction_report->transaction_type = 'IMP';
            $transaction_report->quantity = $quantity;
            $transaction_report->unit_cost = $unitCost;
            $transaction_report->receipt_quantity = $quantity;
            $transaction_report->receipt_amount = $total;
            $transaction_report->mfg_date = $mfgDate;
            $transaction_report->expiry_date = $expiryDate;
            $transaction_report->remarks = "";
            $transaction_report->job_no = $jobNo;
            $transaction_report->status = $donationStatus;
            $transaction_report->inventory_location = $inventory_location;
            $transaction_report->save();

            if($donationStatus == 1) {
                //Initiate Process Summary Function Before Updating The Product To Register Already New Product If Not Existed
                $this->processSummary();

                //Update Summary
                //Get The Current Summary Month And Year Of Product
                $this->query("SELECT * FROM summary WHERE member_id = '{$memberId}' AND month = '{$currentDateMonth}' AND year = '{$currentDateYear}' AND product_code = '{$productCode}' AND lot_no = '{$lotNo}' AND unit_cost  = '{$unitCost}'");

                $summaryThisMonth = Summary::where('member_id',$member_id)
                ->where('month',$currentDateMonth)
                ->where('year',$currentDateYear)
                ->where('product_code',$productCode)
                ->where('lot_no',$lotNo)
                ->where('unit_cost',$unitCost)->get();

                $movementsQuantity = 0;
                $summaryId = 0;

                foreach($summaryThisMonth as $summaryDetails) {
                    $summaryId = $summaryDetails->id;
                    $movementsQuantity = $summaryDetails->movements_quantity;
                }
                
                //Compute For The Updated Quantity Movements
                $movementsQuantity = $movementsQuantity + $quantity; //Operation Is Addition Because The Tranasction Is Import
                
                //Compute For The Updated Ending Balance Quantity
                $endingBalanceQuantity = $lastSummaryEndingBalanceQuantity + $movementsQuantity;
                
                //Compute For The Updated Ending Balance Value
                $endingBalanceValue = $endingBalanceQuantity * $unitCost;
                
                //Insert Updated Product Summary
                $updateSummary = Summary::findOrFail($summaryId);
                $updateSummary->movements_quantity = $movementsQuantity;
                $updateSummary->ending_balance_quantity = $endingBalanceQuantity;
                $updateSummary->ending_balance_value = $endingBalanceValue;
                $updateSummary->updated_at = $currentDate;
                $updateSummary->save();
            }
        }


    }

    public function processSummary() {

        $inventory = Inventory::all();

        foreach($inventory as $inventoryDetails) {
            $memberId = $inventoryDetails->member_id;
			$productCode = $inventoryDetails->product_code;
			$productName = $inventoryDetails->product_name;
			$quantity =  $inventoryDetails->quantity;
			$lotNo =  $inventoryDetails->lot_no;
			$unitCost =  $inventoryDetails->unit_cost;

            $currentDate = Carbon::now();
            $currentDateMonth = $currentDate->month;
            $currentDateYear = $currentDate->year;

            $summary = Summary::where('member_id',$memberId)
            ->where('month',$currentDateMonth)
            ->where('year',$currentDateYear)
            ->where('product_code',$productCode)
            ->where('lot_no',$lotNo)
            ->where('unit_cost',$unitCost)->get();

            if($summary->isEmpty()) {

                $lastSummaryDate = date("m/d/Y", strtotime("-1 month", strtotime($currentDate)));
                $lastSummaryMonth = Carbon::parse($lastSummaryDate)->format('m');
                $lastSummaryYear = Carbon::parse($lastSummaryDate)->format('Y');

                $summaryLastMonth = Summary::where('member_id',$memberId)
                ->where('month',$lastSummaryMonth)
                ->where('year',$lastSummaryYear)
                ->where('product_code',$productCode)
                ->where('lot_no',$lotNo)
                ->where('unit_cost',$unitCost)->get();

                $lastSummaryEndingBalanceQuantity = 0;

                foreach($summaryLastMonth as $item) {
                    $lastSummaryEndingBalanceQuantity = $item->ending_balance_quantity;
                }

				$movementsQuantity = 0; //Set Initial Movement To None
				$endingBalanceQuantity = $lastSummaryEndingBalanceQuantity + $movementsQuantity;

				//Compute For The Initial Ending Balance Value
				$endingBalanceValue = $endingBalanceQuantity * $unitCost;

                $new_summary = new Summary;
                $new_summary->member_id = $memberId;
                $new_summary->month = $currentDateMonth;
                $new_summary->year = $currentDateYear;
                $new_summary->product_code = $productCode;
                $new_summary->product_name = $productName;
                $new_summary->lot_no = $lotNo;
                $new_summary->unit_cost = $unitCost;
                $new_summary->beginning_balance_quantity = $lastSummaryEndingBalanceQuantity;
                $new_summary->ending_balance_quantity = $endingBalanceQuantity;
                $new_summary->ending_balance_value = $endingBalanceValue;
                $new_summary->save();

            } else {

            }
        }
    }

}
