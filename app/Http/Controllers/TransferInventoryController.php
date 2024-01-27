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
use App\User;
use App\TransferInventory;
use Codedge\Fpdf\Fpdf\Fpdf;
class TransferInventoryController extends Controller
{
    public function createView($contribution_id) {   

        $transfer_inventory_without_ttif = TransferInventory::where('ttif_no','!=', '')
        ->get();

        $ttif_count = $transfer_inventory_without_ttif->count() + 1;
        $current_date = Carbon::now();
        $todays_year = $current_date->year;
        $new_ttif_no = $todays_year."-".$ttif_count;

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

        $donations = Donation::where('contribution_id', $contribution_id)->get();


        return view('transfer-inventory.transfer-inventory-create')
        ->with('donations',$donations)
        ->with('new_ttif_no',$new_ttif_no)
        ->with('current_date',$current_date)
        ->with('contribution_id', $contribution_id)
        ->with('allocations_notif', $allocations_notif)
        ->with('contributions_notif', $contributions_notif);
    }

    public function saveTransferInventory($contribution_id,Request $request) {

        $contribution = Contribution::findOrFail($contribution_id);
        $contribution_no = $contribution->contribution_no;
        $contribution_id = $contribution->id;

        $member_id = $contribution->member_id;
        $member = Member::findOrFail($member_id);
        $member_name = $member->member_name;

        $transfer_inventory = TransferInventory::updateOrCreate(
            ['ttif_no' => $request->ttif_no],
            ['contribution_id' => $request->contribution_id,
            'notice_to' => $request->notice_to,
            'transfer_date' => new Carbon($request->transfer_date),
            'daff_no' => $request->daff_no,
            'pickup_organization_name' => $request->pickup_organization_name,
            'pickup_address'=> $request->pickup_address,
            'pickup_contact_person'=> $request->pickup_contact_person,
            'pickup_contact_no'=> $request->pickup_contact_no,
            'pickup_date'=> new Carbon($request->pickup_date),
            'pickup_other_instruction'=> $request->pickup_other_instruction]
        );

        $contribution->inventory_location = $request->inventory_location;
        $contribution->save();
        $transfer_inventory->save();

        $template_path = public_path("/images/templates/transferToInventoryForm.jpg");

        // Header 
        $pdf = new FPDF('P','mm','A4');
        $pdf->AddPage();
        $pdf->Image($template_path,0,0,210,297);

        
        $pdf->SetXY(27,31);
        $pdf->SetTextColor(43,43,43);	
        $pdf->SetFont('Arial','',10);
        $pdf->Cell(0,0,"{$request->notice_to}");

        
        $pdf->SetXY(150,17);
        $pdf->SetTextColor(43,43,43);	
        $pdf->SetFont('Arial','',10);
        $pdf->Cell(0,0,"{$request->ttif_no}");

        $pdf->SetXY(44,46);
        $pdf->SetTextColor(43,43,43);	
        $pdf->SetFont('Arial','',10);
        $pdf->Cell(0,0,"{$request->pickup_organization_name}");

        $pdf->SetXY(37.5,54);
        $pdf->SetTextColor(43,43,43);	
        $pdf->SetFont('Arial','',10);
        $pdf->Cell(0,0,"{$request->pickup_address}");
        
        $pdf->SetXY(138.5,31);
        $pdf->SetTextColor(43,43,43);	
        $pdf->SetFont('Arial','',10);
        $pdf->Cell(0,0,"{$request->daff_no}");

        $pdf->SetXY(111,30);
        $pdf->SetTextColor(43,43,43);	
        $pdf->SetFont('Arial','',10);
        $pdf->Cell(0,0,"{$request->transfer_date}");

        $pdf->SetXY(24,63.3);
        $pdf->SetTextColor(43,43,43);	
        $pdf->SetFont('Arial','',10);
        $pdf->Cell(0,0,"{$request->pickup_contact_person}");

        $pdf->SetXY(86.6,64);
        $pdf->SetTextColor(43,43,43);	
        $pdf->SetFont('Arial','',10);
        $pdf->Cell(0,0,"{$request->pickup_contact_no}");

        $pdf->SetXY(125,64);
        $pdf->SetTextColor(43,43,43);	
        $pdf->SetFont('Arial','',10);
        $pdf->Cell(0,0,"{$request->pickup_date}");

        $pdf->SetXY(25,74.5);
        $pdf->SetTextColor(43,43,43);	
        $pdf->SetFont('Arial','',10);
        $pdf->Cell(0,0,"{$request->pickup_other_instruction}");

        $positionY = 89.5;

        foreach ($request->inputs as $key => $value) {

            if(isset($value['is_selected'])) {
                $product_name = $value['product_name'];
                $lot_no = $value['lot_no'];
                $transfer_quantity = $value['transfer_quantity'];
                $unit_cost = $value['unit_cost'];
                $expiry_date = date('m/d/Y', strtotime($value['expiry_date']));

                $pdf->SetXY(22,"{$positionY}");
                $pdf->SetTextColor(43,43,43);	
                $pdf->SetFont('Arial','',7);
                $pdf->Cell(0,0,$member_name);

                $pdf->SetXY(60,"{$positionY}");
                $pdf->SetTextColor(43,43,43);	
                $pdf->SetFont('Arial','',7);
                $pdf->Cell(0,0,$product_name);

                $pdf->SetXY(100,"{$positionY}");
                $pdf->SetTextColor(43,43,43);	
                $pdf->SetFont('Arial','',7);
                $pdf->Cell(0,0,$expiry_date);
                
                $pdf->SetXY(124,"{$positionY}");
                $pdf->SetTextColor(43,43,43);	
                $pdf->SetFont('Arial','',7);
                $pdf->Cell(0,0,$lot_no);

                $pdf->SetXY(146,"{$positionY}");
                $pdf->SetTextColor(43,43,43);	
                $pdf->SetFont('Arial','',7);
                $pdf->Cell(0,0,$transfer_quantity);

                $positionY = $positionY + 4;
            }
        }
        $file_name = $contribution_no.'_TTIF';
        $destination_path = public_path("/pdf/transfer-inventory/$file_name.pdf");

        $pdf->Output($destination_path,'F');
        $this->saveDocuments($contribution_id,$file_name,'TTIF');

        return redirect()->route('contribution-details', ['contribution_id' => $contribution_id])->with('inventory-updated', 'Inventory Transfer Updated!');

        // save to docs the inventory transfer
    }

    public function saveDocuments($contribution_id,$file_name,$type) 
    {
        $document = new Document;
        $document->contribution_id = $contribution_id;
        $document->type = $type;
        $document->name = $file_name;
        $document->directory = "/pdf/transfer-inventory/$file_name.pdf";
        $document->save();
    }
}
