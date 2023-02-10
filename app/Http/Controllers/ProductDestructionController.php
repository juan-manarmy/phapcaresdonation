<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Beneficiary;
use App\Member;
use App\Inventory;
use App\Document;
use Illuminate\Support\Facades\DB;
use Auth;
use Codedge\Fpdf\Fpdf\Fpdf;
use Illuminate\Support\Carbon;
use App\DestructedProduct;
use App\Destruction;
use App\Summary;
use App\TransactionReport;
use Illuminate\Support\Str;


class ProductDestructionController extends Controller
{

    public function cancelDestruction(Request $request) {
        $destruction = Destruction::findOrFail($request->id);
        $destruction->delete();
        return redirect()->route('product-destruction-list')->with('destruction-cancelled','Destruction successfully cancelled.');
    }

    public function updateDestructionStatus($destruction_id, Request $request)
    {
        $destruction = Destruction::findOrFail($destruction_id);

        $user_id = Auth::id();

        if($request->status == 2) {
            $this->verifyPDRF($destruction_id,$request->notice_to,$request->pickup_address,$request->pickup_contact_person,$request->pickup_contact_no,
            $request->pickup_date,$request->other_pickup_instructions,$request->delivery_contact_person,$request->delivery_address,$request->delivery_authorized_recipient,
            $request->delivery_contact_no,$request->delivery_date,$request->other_delivery_instructions,$request->reasons_rejected_destruction,$request->status);
        }

        if($request->status == 3) {
            $this->verifyPDRF($destruction_id,$request->notice_to,$request->pickup_address,$request->pickup_contact_person,$request->pickup_contact_no,
            $request->pickup_date,$request->other_pickup_instructions,$request->delivery_contact_person,$request->delivery_address,$request->delivery_authorized_recipient,
            $request->delivery_contact_no,$request->delivery_date,$request->other_delivery_instructions,$request->reasons_rejected_destruction,$request->status);

            $this->saveTransactionReport($destruction_id);
        }

        $destruction->save();
        return back();
    }

    public function verifyPDRF($destruction_id,$notice_to,$pickup_address,$pickup_contact_person,$pickup_contact_no,$pickup_date,$other_pickup_instructions,$delivery_contact_person,
    $delivery_address,$delivery_authorized_recipient,$delivery_contact_no,$delivery_date,$other_delivery_instructions,$reasons_rejected_destruction,$status) {

        $destruction = Destruction::findOrFail($destruction_id);
        $user_id = Auth::id();

        $destruction->notice_to = $notice_to;
        $destruction->pickup_address = $pickup_address;
        $destruction->pickup_contact_person = $pickup_contact_person;
        $destruction->pickup_contact_no = $pickup_contact_no;
        $destruction->pickup_date = new Carbon($pickup_date);
        $destruction->other_pickup_instructions = $other_pickup_instructions;
        $destruction->delivery_contact_person = $delivery_contact_person;
        $destruction->delivery_address = $delivery_address;
        $destruction->delivery_authorized_recipient = $delivery_authorized_recipient;
        $destruction->delivery_contact_no = $delivery_contact_no;
        $destruction->delivery_date = new Carbon($delivery_date);
        $destruction->other_delivery_instructions = $other_delivery_instructions;
        $destruction->reasons_rejected_destruction = $reasons_rejected_destruction;
        $destruction->status = $status;
        $destruction->verified_date = Carbon::now()->format('Y-m-d');
        $destruction->verified_by_user_id = $user_id;
        $destruction->save();

    }


    public function createPDRFForm ($destruction_id) {
        
        $destructionDetails = Destruction::findOrFail($destruction_id);
        $beneficiary = Beneficiary::findOrFail($destructionDetails->beneficiary_id);
        $beneficiaryName = $beneficiary->name;

        $destructionNo = $destructionDetails['destruction_no'];
        $pdrfNo = $destructionDetails['pdrf_no'];
        $noticeTo = $destructionDetails['notice_to'];
        $beneficiaryId = $destructionDetails['beneficiary_id'];
        $pickupAddress = $destructionDetails['pickup_address'];
        $pickupContactPerson = $destructionDetails['pickup_contact_person'];
        $pickupContactNo = $destructionDetails['pickup_contact_no'];
        $pickupDate = $destructionDetails['pickup_date'];
        $pickupInstructions = $destructionDetails['other_pickup_instructions'];

        $deliveryContactPerson = $destructionDetails['delivery_contact_person'];
        $deliveryAddress = $destructionDetails['delivery_address'];
        $deliveryAuthorizedRecipient = $destructionDetails['delivery_authorized_recipient'];
        $deliveryContactNo = $destructionDetails['delivery_contact_no'];
        $deliveryDate = $destructionDetails['delivery_date'];
        $deliveryInstructions = $destructionDetails['other_delivery_instructions'];
        $createDate = $destructionDetails['created_at'];

        $totalDestructedProducts = $destructionDetails['total_destructed_products'];

        $pdrf_template = public_path("/images/templates/pdrfTemplate.jpg");
        $signature01 = public_path("/images/templates/signature01.png");
        $signature02 = public_path("/images/templates/signature02.png");

        //Insert Contribution Details In PDF Page
        //Set Page Layout and Add PDF Page
        $pdf = new FPDF('P','mm','A4');
        $pdf->AddPage();
        $pdf->Image($pdrf_template,0,0,210,297);
        $pdf->Image($signature01,19,233.5,50,10);
        $pdf->Image($signature02,84,233.5,40,8);
        
        //PDF Contents
        $pdf->SetXY(165,20);
        $pdf->SetTextColor(43,43,43);	
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(0,0,"{$pdrfNo}");
        
        $pdf->SetXY(14,35);
        $pdf->SetTextColor(43,43,43);	
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(0,0,"{$noticeTo}");
        
        $pdf->SetXY(123,35);
        $pdf->SetTextColor(43,43,43);	
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(0,0,"{$createDate}");
        
        $pdf->SetXY(14,51);
        $pdf->SetTextColor(43,43,43);	
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(0,0,"{$beneficiaryName}");
        
        $pdf->SetXY(14,59);
        $pdf->SetTextColor(43,43,43);	
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(0,0,"{$pickupAddress}");
        
        $pdf->SetXY(14,68);
        $pdf->SetTextColor(43,43,43);	
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(0,0,"{$pickupContactPerson}");
        
        $pdf->SetXY(96,68);
        $pdf->SetTextColor(43,43,43);	
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(0,0,"{$pickupContactNo}");

        $pdf->SetXY(144,68);
        $pdf->SetTextColor(43,43,43);	
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(0,0,"{$pickupDate}");
        
        $pdf->SetXY(14,76);
        $pdf->SetTextColor(43,43,43);	
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(0,0,"{$pickupInstructions}");

        $pdf->SetXY(14,95);
        $pdf->SetTextColor(43,43,43);	
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(0,0,"{$deliveryContactPerson}");

        $pdf->SetXY(14,103);
        $pdf->SetTextColor(43,43,43);	
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(0,0,"{$deliveryAddress}");

        $pdf->SetXY(14,112);
        $pdf->SetTextColor(43,43,43);	
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(0,0,"{$deliveryAuthorizedRecipient}");

        $pdf->SetXY(96,112);
        $pdf->SetTextColor(43,43,43);	
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(0,0,"{$deliveryContactNo}");

        $pdf->SetXY(144,112);
        $pdf->SetTextColor(43,43,43);	
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(0,0,"{$deliveryDate}");
        
        $pdf->SetXY(14,120);
        $pdf->SetTextColor(43,43,43);	
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(0,0,"{$deliveryInstructions}");

        $destructed_products = DB::table('destructed_products')
        ->join('inventories', 'destructed_products.inventory_id', '=', 'inventories.id')
        ->join('members', 'inventories.member_id', '=', 'members.id')
        ->select('destructed_products.*', 'members.member_name')
        ->where('destruction_id',$destruction_id)
        ->get();

        //Set Y Positions
        $formRowArray = array(136.5,141,145,149.5,153.5,158,162,166,170.5,174.5,179,183.5,187.5,191.5,196,200,204.5,208.5,213,217);
        $formRowCount = 0;

        foreach ($destructed_products as $destructedProductsDetails) {

            $inventoryId =  $destructedProductsDetails->inventory_id;
            $productName =  $destructedProductsDetails->product_name;
            $expiryDate =  $destructedProductsDetails->expiry_date;
            $lotNo =  $destructedProductsDetails->lot_no;
            $quantity =  $destructedProductsDetails->quantity;
            $memberName =  $destructedProductsDetails->member_name;

            $pdf->SetXY(14,"{$formRowArray[$formRowCount]}");
            $pdf->SetTextColor(43,43,43);	
            $pdf->SetFont('Arial','',8);
            $pdf->Cell(0,0,"{$memberName}");
            
            $pdf->SetXY(60,"{$formRowArray[$formRowCount]}");
            $pdf->SetTextColor(43,43,43);	
            $pdf->SetFont('Arial','',7);
            $pdf->Cell(0,0,"{$productName}");
            
            $pdf->SetXY(122,"{$formRowArray[$formRowCount]}");
            $pdf->SetTextColor(43,43,43);	
            $pdf->SetFont('Arial','',8);
            $pdf->Cell(0,0,"{$expiryDate}");
            
            $pdf->SetXY(157,"{$formRowArray[$formRowCount]}");
            $pdf->SetTextColor(43,43,43);	
            $pdf->SetFont('Arial','',8);
            $pdf->Cell(0,0,"{$lotNo}");
            
            $pdf->SetXY(186,"{$formRowArray[$formRowCount]}");
            $pdf->SetTextColor(43,43,43);	
            $pdf->SetFont('Arial','',8);
            $pdf->Cell(0,0,"{$quantity}");
    
            $formRowCount++;
        }

        $pdf->SetXY(14,232.5);
        $pdf->SetTextColor(43,43,43);	
        $pdf->SetFont('Arial','',8);
        $pdf->Cell(0,0,"PHAPCARES PROJECT MANAGER");
        
        $pdf->SetXY(74,232.5);
        $pdf->SetTextColor(43,43,43);	
        $pdf->SetFont('Arial','',8);
        $pdf->Cell(0,0,"PHAPCARES EXECUTIVE DIRECTOR");
        
        $pdf->SetXY(24,243);
        $pdf->SetTextColor(43,43,43);	
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(0,0,"Dennis Romerick G. Tuazon");
        
        $pdf->SetXY(84,243);
        $pdf->SetTextColor(43,43,43);	
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(0,0,"Dr. Maria Rosarita Q. Siasoco");

        $random = rand(1,10000);
        $destination_path = public_path("/pdf/pdrf/{$destructionNo}_PDRF.pdf");
        $pdf->Output($destination_path,'F');

    }

    public function saveDocuments($destruction_id,$destruction_no,$pdrf_no,$type) 
    {
        $document = new Document;
        $document->destruction_id = $destruction_id;
        $document->type = $type;
        $document->name = "{$destruction_no}_{$type}";
        $document->directory = "/pdf/pdrf/{$destruction_no}_{$type}";
        $document->save();
    }

    public function editDestructedProduct ($destructed_product_id) {
        $destructed_product = DestructedProduct::findOrFail($destructed_product_id);
        $inventory_available_stock = Inventory::where('id',$destructed_product->inventory_id)
        ->select('quantity')->first();
        
        $available_stock = $inventory_available_stock->quantity;

        return view('product-destruction.product-destruction-edit-product')
        ->with('destructed_product', $destructed_product)
        ->with('available_stock', $available_stock);
    } 

    public function cancelDestructedProduct (Request $request) {

        $destructed_product = DestructedProduct::findOrFail($request->donation_id);
        $inventory_id = $destructed_product->inventory_id; 
        $destruction_id = $destructed_product->destruction_id; 
        // saving the status as cancelled
        $destructed_product->status = 2;
        $destructed_product->save();
        
        // Update Allocation Total med, promats, donation
        $destruction = Destruction::findOrFail($destruction_id);
        
        if($destructed_product->product_type == 1) {
            $destruction->total_medicine -= $destructed_product->total;
            $destruction->total_destructed_products -= $destructed_product->total;
        }

        if($destructed_product->product_type == 2) {
            $destruction->total_promats -= $destructed_product->total;
            $destruction->total_destructed_products -= $destructed_product->total;
        }

        $destruction->save();
        // revert back the quantity and total of allocated product back to inventory
        $inventory = Inventory::findOrFail($inventory_id);
        // adding back the quantity from allocated to inventory
        $inventory->quantity += $destructed_product->quantity;
        $inventory->total += $destructed_product->total;  
        $inventory->save();
        return back();

        // $allocated_product->delete();
        // return $quantity;
    }

    public function revertDestructedProduct ($destructed_product_id, Request $request) {
        $destructed_product = DestructedProduct::findOrFail($destructed_product_id);
        $inventory_id = $destructed_product->inventory_id; 
        $destruction_id = $destructed_product->destruction_id; 
        
        $old_quantity = $destructed_product->quantity;
        $unit_cost  = $destructed_product->unit_cost;

        $destructed_product->quantity -= $old_quantity;

        $existing_total = $destructed_product->total;
        // Update destruction Total med, promats, donation
        $destruction = Destruction::findOrFail($destruction_id);

        if($destructed_product->product_type == 1) {
            $destruction->total_medicine -= $existing_total;
            $destruction->total_destructed_products -= $existing_total;
        }

        if($destructed_product->product_type == 2) {
            $destruction->total_promats -= $existing_total;
            $destruction->total_destructed_products -= $existing_total;
        }

        // revert back the quantity and total of allocated product back to inventory
        $inventory = Inventory::findOrFail($inventory_id);
        // adding back the quantity from allocated to inventory
        $inventory->quantity += $old_quantity;
        $inventory->total += $existing_total;  

        $inventory->save();
        $destruction->save();
        $destructed_product->save();

        $this->updateProductQuantity($destructed_product_id, $request->quantity);

        return back();
    }

    public function updateProductQuantity($destructed_product_id, $quantity) {

        $destructed_product = DestructedProduct::findOrFail($destructed_product_id);
        $inventory_id = $destructed_product->inventory_id; 
        $destruction_id = $destructed_product->destruction_id; 

        //update destruction product new quantity and total
        $new_quantity = $quantity;
        $new_total = $new_quantity * $destructed_product->unit_cost;

        $destructed_product->quantity = $new_quantity;
        $destructed_product->total = $new_total;

        $destruction = Destruction::findOrFail($destruction_id);
        // update destruction
        // add the new total donation, total medicine, toatl promats
        if($destructed_product->product_type == 1) {
            $destruction->total_medicine += $new_total;
            $destruction->total_destructed_products += $new_total;
        }

        if($destructed_product->product_type == 2) {
            $destruction->total_promats += $new_total;
            $destruction->total_destructed_products += $new_total;
        }

        //update inventory
        $inventory = Inventory::findOrFail($inventory_id);
        // reducing the quantity from allocated to inventory
        $inventory->quantity -= $new_quantity;
        $inventory->total -= $new_total;  

        $destruction->save();
        $inventory->save();
        $destructed_product->save();
    }

    public function saveDestructedProduct($destruction_id, Request $request)
    {
        $selected_product = new DestructedProduct;
        $selected_product->inventory_id = $request->selected_product["inventory_id"];
        $selected_product->destruction_id = $destruction_id;
        $selected_product->product_type = $request->selected_product["product_type"];
        $selected_product->product_code = $request->selected_product["product_code"];
        $selected_product->product_name = $request->selected_product["product_name"];
        $selected_product->quantity = $request->selected_product["quantity"];
        $selected_product->lot_no = $request->selected_product["lot_no"];
        $selected_product->expiry_date = new Carbon($request->selected_product["expiry_date"]);
        $selected_product->unit_cost = $request->selected_product["unit_cost"];
        $selected_product->total = $request->selected_product["unit_cost"] * $request->selected_product["quantity"];
        $selected_product->status = 1;
        $selected_product->save();
        // deduct the issuance quantity to the inventory quantity
        $inventory = Inventory::findOrFail($request->selected_product["inventory_id"]);
        $inventory->quantity -= $request->selected_product["quantity"];
        $inventory->save();
        // 35000
        return $selected_product;
    }

    public function getDestructedProduct($destruction_id) {
        $destruction_product = DestructedProduct::where('destruction_id',$destruction_id)->get();
        return $destruction_product;
    }

    public function saveTotalDonations($destruction_id, Request $request) {
        $destruction = Destruction::findOrFail($destruction_id);
        $destruction->total_medicine = $request->total_donations["medicine_total_donation"];
        $destruction->total_promats = $request->total_donations["promats_total_donation"];
        $destruction->total_destructed_products = $request->total_donations["total_products_amount"];
        $destruction->status = 1;
        $destruction->save();

        $this->createPDRFForm($destruction_id);
        $this->saveDocuments($destruction_id,$destruction->destruction_no,$destruction->pdrf_no,"PDRF");
    }

    public function saveDestruction(Request $request) {

        $destruction = Destruction::updateOrCreate(
            ['destruction_no' => $request->destruction_no],
            ['pdrf_no' => $request->pdrf_no,
            'beneficiary_id' => $request->beneficiary_id,
            'notice_to' => $request->notice_to,
            'pickup_address' => $request->pickup_address,
            'pickup_contact_person' => $request->pickup_contact_person,
            'pickup_contact_no' => $request->pickup_contact_no,
            'pickup_date' => new Carbon($request->pickup_date),
            'other_pickup_instructions' => $request->other_pickup_instructions,
            'delivery_contact_person' => $request->delivery_contact_person,
            'delivery_address' => $request->delivery_address,
            'delivery_authorized_recipient' => $request->delivery_authorized_recipient,
            'delivery_contact_no' => $request->delivery_contact_no,
            'delivery_date' => new Carbon($request->delivery_date),
            'other_delivery_instructions' => $request->other_delivery_instructions,
            'status' => 0]);

        $destruction_id = $destruction->id;

        return redirect()->route('product-destruction-add-products', ['destruction_id' => $destruction_id]);
    }

    public function deleteDestructedProduct ($destructed_product_id) {

        $destructed_product = DestructedProduct::findOrFail($destructed_product_id);
        $inventory_id = $destructed_product->inventory_id; 
        $destruction_id = $destructed_product->destruction_id; 
        
        $inventory = Inventory::findOrFail($inventory_id);
        // adding back the quantity from allocated to inventory

        // FIX add back also the amount
        $inventory->quantity += $destructed_product->quantity;
        $inventory->save();

        $destructed_product->delete();
        // return $quantity;
    }

    public function productDestructionCreate() {
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

        $beneficiaries = Beneficiary::all();
        $destruction_no = Str::random(10);

        return view('product-destruction.product-destruction-create')
        ->with('destruction_no', $destruction_no)
        ->with('beneficiaries', $beneficiaries)
        ->with('allocations_notif', $allocations_notif)
        ->with('contributions_notif', $contributions_notif);
    }

    public function productDestructionCreateRead($id) {

        $destruction = Destruction::where('id',$id)->first();

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

        $beneficiaries = Beneficiary::all();

        return view('product-destruction.product-destruction-create')
        ->with('destruction', $destruction)
        ->with('beneficiaries', $beneficiaries)
        ->with('allocations_notif', $allocations_notif)
        ->with('contributions_notif', $contributions_notif);
    }

    public function productDestructionAddProducts($destruction_id) {
        $contributions_notif = DB::table('contributions')
        ->join('members', 'contributions.member_id', '=', 'members.id')
        ->where('contributions.status',1)
        ->select('contributions.id','members.member_name','contributions.contribution_no','contributions.contribution_date','contributions.total_donation')
        ->get();

        $allocations_notif = DB::table('allocations')
        ->join('beneficiaries', 'allocations.beneficiary_id', '=', 'beneficiaries.id')
        ->where('allocations.status',1)
        ->select('allocations.id','beneficiaries.name','allocations.total_allocated_products','allocations.allocation_no')
        ->get();

        return view('product-destruction.product-destruction-add-products')
        ->with('destruction_id',$destruction_id)
        ->with('allocations_notif', $allocations_notif)
        ->with('contributions_notif', $contributions_notif);
    }
    //
    public function productDestructionList() {
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

        $destruction = DB::table('destructions')
        ->where('status',1)
        ->select('id','destruction_no','pdrf_no','total_destructed_products','created_at','status')
        ->get();

        $destruction_drafts = DB::table('destructions')
        ->where('status',0)
        ->select('id','destruction_no','pdrf_no','total_destructed_products','created_at','status')
        ->get();

        return view('product-destruction.product-destruction-list')
        ->with('destruction',$destruction)
        ->with('destruction_drafts',$destruction_drafts)
        ->with('allocations_notif', $allocations_notif)
        ->with('contributions_notif', $contributions_notif);
    }

    public function getInventory($member_id, $destruction_id) {
        //get the inventory
        $get_inventory = Inventory::where('member_id', $member_id)
        ->where('quantity','!=', 0)
        ->get();
        // array of inventory item
        $inventory = [];
        // 	Do not add the product If The Details Are Already Added In Allocated Products
  
        // checking, filtering, and inserting back the non added product in the allocation inside the array
        foreach ($get_inventory as $item) {
            $destructed_product = DestructedProduct::where('destruction_id', $destruction_id)
            ->where('inventory_id', $item->id)->get();

            if($destructed_product->count() == 0) {
                array_push($inventory,$item);
            }
        }
        return $inventory;
    }

    public function productDestructionDetails($destruction_id) {
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

        $destruction = Destruction::findOrFail($destruction_id);
        $destructed_products = DestructedProduct::where('destruction_id', $destruction_id)->get();
        $documents = Document::where('destruction_id', $destruction_id)->get();
        $beneficiary = Beneficiary::findOrFail($destruction->beneficiary_id);
        $beneficiary_name = $beneficiary->name;

        $promats_count = 0;
        $medicine_count = 0;
        $total_count = 0;
        
        $total_quantity = 0;
        $total_amount = 0;

        $cancelled_total_quantity = 0;
        $cancelled_total_donation = 0;

        foreach ($destructed_products as $donation) {

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

        return view('product-destruction.product-destruction-details')
        ->with('promats_count', $promats_count)
        ->with('medicine_count', $medicine_count)
        ->with('total_count', $total_count)
        ->with('total_quantity', $total_quantity)
        ->with('total_amount', $total_amount)
        ->with('cancelled_total_quantity', $cancelled_total_quantity)
        ->with('cancelled_total_donation', $cancelled_total_donation)
        ->with('beneficiary_name',$beneficiary_name)
        ->with('destruction',$destruction)
        ->with('destructed_products',$destructed_products)
        ->with('documents',$documents)
        ->with('allocations_notif', $allocations_notif)
        ->with('contributions_notif', $contributions_notif);
    }

    public function saveTransactionReport ($destruction_id) {
        $destruction = Destruction::findOrFail($destruction_id);
        // $member_id = $destruction->member_id;
        $destruction_no =  $destruction->destruction_no;
        $beneficiary_id =  $destruction->beneficiary_id;
     
        $destructed_products = DestructedProduct::where('destruction_id', $destruction_id)
        ->where('status',1)
        ->get();

        foreach($destructed_products as $destructedProductsDetails) {

            $inventory_id = $destructedProductsDetails->inventory_id;
            $product_code =  $destructedProductsDetails->product_code;
            $product_name = $destructedProductsDetails->product_name;
            $quantity =  $destructedProductsDetails->quantity;
            $lot_no =  $destructedProductsDetails->lot_no;
            $expiry_date =  $destructedProductsDetails->expiry_date;
            $unit_cost =  $destructedProductsDetails->unit_cost;
            $total =  $destructedProductsDetails->total;
            $status =  $destructedProductsDetails->status;

            $member_id = 0;
            $lastSummaryEndingBalanceQuantity = 0;

            $inventory = Inventory::where('id', $inventory_id)->get();
            foreach($inventory as $item) {
                $member_id = $item->member_id;
            }

            // GET TODAYS MONTH AND YEAR
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
            $transaction_report->beneficiary_id = $beneficiary_id;
            $transaction_report->destruction_id = $destruction_id;
            $transaction_report->allocation_id = 0;
            $transaction_report->contribution_id = 0;
            $transaction_report->inventory_id = 0;
            $transaction_report->month = $currentDateMonth;
            $transaction_report->year = $currentDateYear;
            $transaction_report->destruction_no = $destruction_no;
            $transaction_report->product_code = $product_code;
            $transaction_report->product_name = $product_name;
            $transaction_report->lot_no = $lot_no;
            $transaction_report->opening_balance_quantity = $openingBalanceQuantity;
            $transaction_report->transaction_type = 'DES';
            $transaction_report->quantity = $quantity;
            $transaction_report->unit_cost = $unit_cost;
            $transaction_report->destruction_quantity = $quantity;
            $transaction_report->destruction_amount = $total;
            $transaction_report->expiry_date = $expiry_date;
            $transaction_report->remarks = "";
            $transaction_report->status = $status;

            $transaction_report->save();

            if($status == 1) {
                
                $movementsQuantity = 0;
                $summaryId = 0;
                //Update Summary
                //Get The Current Summary Month And Year Of Product
                $summaryThisMonth = Summary::where('member_id',$member_id)
                ->where('month',$currentDateMonth)
                ->where('year',$currentDateYear)
                ->where('product_code',$product_code)
                ->where('lot_no',$lot_no)
                ->where('unit_cost',$unit_cost)->get();

                foreach($summaryThisMonth as $summaryDetails) {
                    $summaryId = $summaryDetails->id;
                    $movementsQuantity = $summaryDetails->movements_quantity;
                }

                //Insert Updated Product Summary
                //Compute For The Updated Quantity Movements
                $movementsQuantity = $movementsQuantity - $quantity; //Operation Is Addition Because The Tranasction Is Import
                
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

}
