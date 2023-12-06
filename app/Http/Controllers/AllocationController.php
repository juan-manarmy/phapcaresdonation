<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Allocation;
use Illuminate\Support\Carbon;
use App\Beneficiary;
use App\Member;
use App\Inventory;
use App\AllocatedProduct;
use App\Document;
use App\Summary;
use App\TransactionReport;

use Illuminate\Support\Facades\DB;
use Auth;
use Codedge\Fpdf\Fpdf\Fpdf;
use Illuminate\Support\Str;

class AllocationController extends Controller
{
    public function cancelAllocation(Request $request) {
        $allocation = Allocation::findOrFail($request->id);
        $allocation->delete();
        return redirect()->route('allocation-list')->with('allocation-cancelled','Allocation successfully cancelled.');
    }

    public function editAllocatedProduct ($allocated_product_id) {
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

        $allocated_product = AllocatedProduct::findOrFail($allocated_product_id);
        $inventory_available_stock = Inventory::where('id',$allocated_product->inventory_id)
        ->select('quantity')->first();
        
        $available_stock = $inventory_available_stock->quantity;

        return view('allocations.allocation-edit-product')
        ->with('allocated_product', $allocated_product)
        ->with('available_stock', $available_stock)
        ->with('allocations_notif', $allocations_notif)
        ->with('contributions_notif', $contributions_notif);
    } 

    public function processAllocatedProductsRevert($allocationId) {
		$currentDateTime = Carbon::now();
		
		//Get Allocated Products And Revert Back The Stock To Inventory
        $allocated_products = AllocatedProduct::where('allocation_id',$allocationId)
        ->where('status',1)->get();

        foreach($allocated_products as $allocatedProductsDetails) {
            $inventoryId =  $allocatedProductsDetails['inventory_id'];
			$allocatedProductQuantity =  $allocatedProductsDetails['quantity'];

            $inventory = Inventory::find($inventoryId);

            //Get Inventory Details
            $inventoryQuantity = $inventory->quantity;
            $unitCost = $inventory->unit_cost;

            //Get The Total Stocks
			$quantity = $inventoryQuantity + $allocatedProductQuantity;
			
			//Compute The Total Value
			$total = $quantity * $unitCost;

            //Update The Inventory With The Difference
            $inventory->quantity = $quantity;
            $inventory->total = $total;
            $inventory->updated_at = $currentDateTime;
            $inventory->save();
        }
    }

    //Remove Product
	public function processAllocatedProductsRemove($allocatedProductId){
		$currentDateTime = Carbon::now();

        //Get Product Details
        $allocated_product = AllocatedProduct::findOrFail($allocatedProductId);

        $inventoryId = $allocated_product->inventory_id;
        $allocationId = $allocated_product->allocation_id;
        $allocatedProductQuantity = $allocated_product->quantity;

        //Get Inventory Details
        $inventory = Inventory::find($inventoryId);
        $inventoryQuantity = $inventory->quantity;
        $unitCost = $inventory->unit_cost;

		//Add Again The Quantity
		$quantity = $inventoryQuantity + $allocatedProductQuantity;
		
		//Compute The Total Value
		$total = $quantity * $unitCost;

		//Delete Product
        $allocated_product->delete();

        $inventory->quantity = $quantity;
        $inventory->total = $total;
        $inventory->updated_at = $currentDateTime;
        $inventory->save();

        //Update Allocated Products Total Amount
        $this->processAllocationAmountUpdate($allocationId);
	}

    //Cancel Product
	public function processAllocatedProductsCancel(Request $request){
        $allocatedProductId  = $request->allocation_product_id;
		$currentDateTime = Carbon::now();
		
		//Get Product Details
        $allocated_product = AllocatedProduct::findOrFail($allocatedProductId);

        $inventoryId = $allocated_product->inventory_id;
        $allocationId = $allocated_product->allocation_id;
        $allocatedProductQuantity = $allocated_product->quantity;

        //Get Inventory Details
        $inventory = Inventory::find($inventoryId);
        $inventoryQuantity = $inventory->quantity;
        $unitCost = $inventory->unit_cost;
		
		//Add Again The Quantity
		$quantity = $inventoryQuantity + $allocatedProductQuantity;
		
		//Compute The Total Value
		$total = $quantity * $unitCost;

		//Update Product
        $allocated_product->status = 0;
        $allocated_product->updated_at = $currentDateTime;
        $allocated_product->save();

        $inventory->quantity = $quantity;
        $inventory->total = $total;
        $inventory->updated_at = $currentDateTime;
        $inventory->save();

        //Update Allocated Products Total Amount
        $this->processAllocationAmountUpdate($allocationId);
        return back();
	}

    //Update Allocated Products Quantity
	public function processAllocatedProductsUpdate($allocatedProductId, Request $request) {
		$currentDateTime = Carbon::now();

        $allocated_product = AllocatedProduct::findOrFail($allocatedProductId);

        //Get Allocated Product Details
        $oldQuantity =  $allocated_product->quantity;
        $allocatedProductUnitCost =  $allocated_product->unit_cost;

        $allocationId = $allocated_product->allocation_id;
		$inventoryId = $allocated_product->inventory_id;

        $input_quantity = $request->quantity;

        //Get Available Stock From Inventory
        $inventory = Inventory::find($inventoryId);
        $inventoryQuantity =  $inventory->quantity;
        $inventoryUnitCost =  $inventory->unit_cost;

        //Add The Inventory Quantity And Old Quantity Of Allocated Product To Compute The New Inventory Stock
		$inventoryStock = $inventoryQuantity + $oldQuantity;

		//Deduct The New Quantity Of Allocated Product From The New Inventory Stock
		$inventoryQuantity = $inventoryStock - $input_quantity;

        //Compute The Inventory Total Amount Based On Inventory Unit Cost
		$inventoryTotal = $inventoryQuantity * $inventoryUnitCost;

        //Update Inventory Quantity
        $inventory->quantity = $inventoryQuantity;
        $inventory->total = $inventoryTotal;
        $inventory->updated_at = $currentDateTime;
        $inventory->save();

        //Compute The Allocated Product Total Amount Based On Allocated Product Unit Cost
		$allocatedProductTotal = $input_quantity * $allocatedProductUnitCost;

        $allocated_product->quantity = $input_quantity;
        $allocated_product->total = $allocatedProductTotal;
        $allocated_product->updated_at = $currentDateTime;
        $allocated_product->save();

        $this->processAllocationAmountUpdate($allocationId);
        $this->createDNAForm($allocationId);
        $this->createDTACForm($allocationId);

        // return back();
        return redirect()->route('allocation-details', ['allocation_id' => $allocationId]);

	}

    //Update Allocation Amount
    public function processAllocationAmountUpdate($allocationId) {
        $currentDateTime = Carbon::now();
        //Compute Total Medicine Value And Save It To Allocations
        $total_medicine_allocation = AllocatedProduct::where('allocation_id', $allocationId)
        ->where('product_type', 1)
        ->where('status',1)
        ->sum('total');

        //Compute Total Promotional Materials Value And Save It To Allocations
        $total_promats_allocation = AllocatedProduct::where('allocation_id', $allocationId)
        ->where('product_type',2)
        ->where('status',1)
        ->sum('total');

        //Compute Total Allocated Products Amount And Save It To Allocations
        $total_allocated_products = AllocatedProduct::where('allocation_id', $allocationId)
        ->where('status',1)
        ->sum('total');

        $allocation = Allocation::find($allocationId);
        $allocation->total_medicine = $total_medicine_allocation;
        $allocation->total_promats = $total_promats_allocation;
        $allocation->total_allocated_products = $total_allocated_products;
        $allocation->updated_at = $currentDateTime;
        $allocation->save();
    }


    //Cancel Allocation
	public function processAllocationCancel($allocationId) {
		//Get Allocated Products And Revert Back The Stock To Inventory
		$this->processAllocatedProductsRevert($allocationId);
        $allocated_products = AllocatedProduct::where('allocation_id',$allocationId)->delete();
        $allocation = Allocation::findOrFail($allocationId);
        $allocation->delete();

        return redirect()->route('allocation-list')->with('allocation-cancelled','Allocation successfully cancelled.');
	}

    //Cancel Allocation
	public function processAllocationCancelRequest(Request $request) {
		//Get Allocated Products And Revert Back The Stock To Inventory
        $allocationId = $request->id;
		$this->processAllocatedProductsRevert($allocationId);
        $allocated_products = AllocatedProduct::where('allocation_id',$allocationId)->delete();
        $allocation = Allocation::findOrFail($allocationId);
        $allocation->delete();

        return redirect()->route('allocation-list')->with('allocation-cancelled','Allocation successfully cancelled.');
	}

    public function revertAllocatedProduct ($allocated_product_id, Request $request) {

        $allocated_product = AllocatedProduct::findOrFail($allocated_product_id);
        $inventory_id = $allocated_product->inventory_id; 
        $allocation_id = $allocated_product->allocation_id; 
        
        $old_quantity = $allocated_product->quantity;
        $unit_cost  = $allocated_product->unit_cost;

        $allocated_product->quantity -= $old_quantity;

        $existing_total = $allocated_product->total;
        // Update Allocation Total med, promats, donation
        $allocation = Allocation::findOrFail($allocation_id);

        if($allocated_product->product_type == 1) {
            $allocation->total_medicine -= $existing_total;
            $allocation->total_allocated_products -= $existing_total;
        }

        if($allocated_product->product_type == 2) {
            $allocation->total_promats -= $existing_total;
            $allocation->total_allocated_products -= $existing_total;
        }
        // revert back the quantity and total of allocated product back to inventory
        $inventory = Inventory::findOrFail($inventory_id);
        // adding back the quantity from allocated to inventory
        $inventory->quantity += $old_quantity;
        $inventory->total += $existing_total;  

        $inventory->save();
        $allocation->save();
        $allocated_product->save();

        $this->updateProductQuantity($allocated_product_id, $request->quantity);

        return back();
    }



    public function updateProductQuantity($allocated_product_id, $quantity) {

        $allocated_product = AllocatedProduct::findOrFail($allocated_product_id);
        $inventory_id = $allocated_product->inventory_id; 
        $allocation_id = $allocated_product->allocation_id; 

        //update allocation product new quantity and total
        $new_quantity = $quantity;
        $new_total = $new_quantity * $allocated_product->unit_cost;

        $allocated_product->quantity = $new_quantity;
        $allocated_product->total = $new_total;

        $allocation = Allocation::findOrFail($allocation_id);
        // update allocation
        // add the new total donation, total medicine, toatl promats
        if($allocated_product->product_type == 1) {
            $allocation->total_medicine += $new_total;
            $allocation->total_allocated_products += $new_total;
        }

        if($allocated_product->product_type == 2) {
            $allocation->total_promats += $new_total;
            $allocation->total_allocated_products += $new_total;
        }

        //update inventory
        $inventory = Inventory::findOrFail($inventory_id);
        // reducing the quantity from allocated to inventory
        $inventory->quantity -= $new_quantity;
        $inventory->total -= $new_total;  

        $allocation->save();
        $inventory->save();
        $allocated_product->save();
    }

    public function verifyDNA($allocation_id,$notice_to,$authorized_representative,$position,$contact_number,
    $delivery_address,$delivery_date,$other_delivery_instructions,$reasons_rejected_allocation,$status) {

        $user_id = Auth::id();
        $allocation = Allocation::find($allocation_id);

        $allocation->notice_to = $notice_to;
        $allocation->authorized_representative = $authorized_representative;
        $allocation->position = $position;
        $allocation->contact_number = $contact_number;
        $allocation->delivery_address = $delivery_address;
        $allocation->delivery_date = new Carbon($delivery_date);
        $allocation->other_delivery_instructions = $other_delivery_instructions;

        $allocation->reasons_rejected_allocation = $reasons_rejected_allocation;
        $allocation->allocation_date = Carbon::now()->format('Y-m-d');
        $allocation->verified_date = Carbon::now()->format('Y-m-d');
        $allocation->verified_by_user_id = $user_id;
        $allocation->status = $status;
        $allocation->save();
    }

    public function verifyDTAC($allocation_id,$notice_to,$authorized_representative,$position,$contact_number,
    $delivery_address,$delivery_date,$other_delivery_instructions,$reasons_rejected_terms,$status) {

        $user_id = Auth::id();
        $allocation = Allocation::find($allocation_id);

        $allocation->notice_to = $notice_to;
        $allocation->authorized_representative = $authorized_representative;
        $allocation->position = $position;
        $allocation->contact_number = $contact_number;
        $allocation->delivery_address = $delivery_address;
        $allocation->delivery_date = new Carbon($delivery_date);
        $allocation->other_delivery_instructions = $other_delivery_instructions;

        $allocation->reasons_rejected_terms = $reasons_rejected_terms;
        $allocation->dtac_approval_date = Carbon::now()->format('Y-m-d');
        $allocation->dtac_approval_user_id = $user_id;
        $allocation->status = $status;
        $allocation->save();
    }

    public function verifyDODRF($allocation_id,$notice_to,$authorized_representative,$position,$contact_number,
    $delivery_address,$delivery_date,$other_delivery_instructions,$reasons_failed_outbound,$dodrf_no,$status) {

        $user_id = Auth::id();
        $allocation = Allocation::find($allocation_id);

        $allocation->notice_to = $notice_to;
        $allocation->authorized_representative = $authorized_representative;
        $allocation->position = $position;
        $allocation->contact_number = $contact_number;
        $allocation->delivery_address = $delivery_address;
        $allocation->delivery_date = new Carbon($delivery_date);
        $allocation->other_delivery_instructions = $other_delivery_instructions;

        $allocation->dodrf_no = $dodrf_no;
        $allocation->reasons_failed_outbound = $reasons_failed_outbound;
        $allocation->dodrf_approval_user_id = $user_id;
        $allocation->dodrf_approval_date = Carbon::now()->format('Y-m-d');
        $allocation->status = $status;
        $allocation->save();
    }

    public function saveAllocatedProductsToInventory($allocation_id) {

        $allocation = Allocation::find($allocation_id);

        $allocation_no = $allocation->allocation_no;
        $beneficiary_id = $allocation->beneficiary_id;

        $allocated_products = AllocatedProduct::where('allocation_id', $allocation_id)
        ->where('status',1)->get();

        foreach($allocated_products as $allocatedProductsDetails) {
            $inventory_id = $allocatedProductsDetails['inventory_id'];
            $product_code =  $allocatedProductsDetails['product_code'];
            $product_name = $allocatedProductsDetails['product_name'];
            $quantity =  $allocatedProductsDetails['quantity'];
            $lot_no =  $allocatedProductsDetails['lot_no'];
            $mfg_date =  $allocatedProductsDetails['mfg_date'];
            $expiry_date =  $allocatedProductsDetails['expiry_date'];
            $drug_reg_no =  $allocatedProductsDetails['drug_reg_no'];
            $unit_cost =  $allocatedProductsDetails['unit_cost'];
            $total =  $allocatedProductsDetails['total'];
            $medicine_status =  $allocatedProductsDetails['medicine_status'];
            $job_no =  $allocatedProductsDetails['job_no'];
            $status =  $allocatedProductsDetails['status'];

            $inventory = Inventory::find($inventory_id);
            $member_id = $inventory->member_id;

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
            $transaction_report->allocation_id = $allocation_id;
            $transaction_report->contribution_id = 0;
            $transaction_report->destruction_id = 0;
            $transaction_report->inventory_id = 0;
            $transaction_report->month = $currentDateMonth;
            $transaction_report->year = $currentDateYear;
            $transaction_report->allocation_no = $allocation_no;
            $transaction_report->product_code = $product_code;
            $transaction_report->product_name = $product_name;
            $transaction_report->lot_no = $lot_no;
            $transaction_report->opening_balance_quantity = $openingBalanceQuantity;
            $transaction_report->transaction_type = 'EXP';
            $transaction_report->quantity = $quantity;
            $transaction_report->unit_cost = $unit_cost;
            $transaction_report->issuance_quantity = $quantity;
            $transaction_report->issuance_amount = $total;
            $transaction_report->mfg_date = $mfg_date;
            $transaction_report->expiry_date = $expiry_date;
            $transaction_report->remarks = "";
            $transaction_report->job_no = $job_no;
            $transaction_report->status = $status;
            $transaction_report->save(); 

            if($status == 1) {

                $contribution = new ContributionController;
                $contribution->processSummary();

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

    public function updateAllocationStatus($allocation_id, Request $request)
    {
        $allocation = Allocation::findOrFail($allocation_id);

        // Rejecting allocation
        if($request->status == 2) {
            $this->verifyDNA($allocation_id,$request->notice_to,
            $request->authorized_representative,$request->position,$request->contact_number,
            $request->delivery_address,$request->delivery_date,$request->other_delivery_instructions,
            $request->reasons_rejected_allocation,$request->status);
        }

        // approve allocation
        if($request->status == 3) {
            $this->verifyDNA($allocation_id,$request->notice_to,
            $request->authorized_representative,$request->position,$request->contact_number,
            $request->delivery_address,$request->delivery_date,$request->other_delivery_instructions,
            $request->reasons_rejected_allocation,$request->status);

            // create dtac pdf
            //save dtac docs
            $this->createDTACForm($allocation_id);
            $this->saveDocuments($allocation_id,$allocation->allocation_no,$allocation->dna_no,"DTAC");
        }

        // Rejecting dtac 
        if($request->status == 4) {
            $this->verifyDTAC($allocation_id,$request->notice_to,
            $request->authorized_representative,$request->position,$request->contact_number,
            $request->delivery_address,$request->delivery_date,$request->other_delivery_instructions,
            $request->reasons_rejected_terms,$request->status);
        }

        // Approve dtac 
        if($request->status == 5) {
            $this->verifyDTAC($allocation_id,$request->notice_to,
            $request->authorized_representative,$request->position,$request->contact_number,
            $request->delivery_address,$request->delivery_date,$request->other_delivery_instructions,
            $request->reasons_rejected_terms,$request->status);
        }

        // Rejecting DODRF
        if($request->status == 6) {
            $this->verifyDODRF($allocation_id,$request->notice_to,
            $request->authorized_representative,$request->position,$request->contact_number,
            $request->delivery_address,$request->delivery_date,$request->other_delivery_instructions,
            $request->reasons_failed_outbound,$request->dodrf_no,$request->status);
        }

        // Approve DODRF
        if($request->status == 7) {
            $this->verifyDODRF($allocation_id,$request->notice_to,
            $request->authorized_representative,$request->position,$request->contact_number,
            $request->delivery_address,$request->delivery_date,$request->other_delivery_instructions,
            $request->reasons_failed_outbound,$request->dodrf_no,$request->status);

            $dodrf_file = $request->file('dodrf_file');
            $dodrf_file_name = $allocation->allocation_no."_DODRF".'.'.$request->file('dodrf_file')->extension();
            $destination_path = public_path('/pdf/dodrf');
            // upload file
            $dodrf_file->move($destination_path,$dodrf_file_name);
            // save data to documents db
            $this->saveDocuments($allocation_id,$allocation->allocation_no,$allocation->dna_no,"DODRF");
            $this->saveAllocatedProductsToInventory($allocation_id);
        }

        return back();
    }

    public function saveDocuments($allocation_id,$allocation_no,$dna_no,$type) 
    {
        $document = new Document;
        $document->allocation_id = $allocation_id;
        $document->type = $type;
        $document->name = "{$allocation_no}_{$type}";

        if($type == "DNA") {
            $document->directory = "/pdf/dna/{$allocation_no}_{$type}";
        } else if($type == "DTAC") {
            $document->directory = "/pdf/dtac/{$allocation_no}_{$type}";
        } else if($type == "DODRF") {
            $document->directory = "/pdf/dodrf/{$allocation_no}_{$type}";
        }
        $document->save();
    }

    public function createDNAForm($allocation_id)
    {
        $allocation = Allocation::findOrFail($allocation_id);
        $allocation_no = $allocation->allocation_no;

        $pdf = new FPDF('P','mm','A4');

        $dna_template = public_path("/images/templates/dnaTemplate.jpg");
        $signature01 = public_path("/images/templates/signature01.png");
        $signature02 = public_path("/images/templates/signature02.png");
        
        // $dna_template = url("/images/templates/dnaTemplate.jpg");
        // $signature01 = url("/images/templates/signature01.png");
        // $signature02 = url("/images/templates/signature02.png");

        $allocation_created_at = date('m/d/Y', strtotime($allocation->created_at));
        $allocation_delivery_date = date('m/d/Y', strtotime($allocation->delivery_date));

        $pdf->AddPage();
        $pdf->Image($dna_template,0,0,210,297);
        $pdf->Image($signature01,19,250,0,12);
        $pdf->Image($signature02,67,250,0,8);
        
        //PDF Contents
        $pdf->SetXY(169,20);
        $pdf->SetTextColor(43,43,43);	
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(0,0,"{$allocation->dna_no}");
        
        $pdf->SetXY(20,37);
        $pdf->SetTextColor(43,43,43);	
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(0,0,"{$allocation->notice_to}");
        
        $pdf->SetXY(141,37);
        $pdf->SetTextColor(43,43,43);	
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(0,0,"{$allocation_created_at}");
        
        $pdf->SetXY(20,56);
        $pdf->SetTextColor(43,43,43);	
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(0,0,"{$allocation->beneficiary_id}");
        
        $pdf->SetXY(20,65);
        $pdf->SetTextColor(43,43,43);	
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(0,0,"{$allocation->delivery_address}");
        
        $pdf->SetXY(20,74);
        $pdf->SetTextColor(43,43,43);	
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(0,0,"{$allocation->authorized_representative}");
        
        $pdf->SetXY(95,74);
        $pdf->SetTextColor(43,43,43);	
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(0,0,"{$allocation->contact_number}");
        
        $pdf->SetXY(160,74);
        $pdf->SetTextColor(43,43,43);	
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(0,0,"{$allocation_delivery_date}");
        
        $pdf->SetXY(20,83);
        $pdf->SetTextColor(43,43,43);	
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(0,0,"{$allocation->other_delivery_instructions}");
        
        // $allocated_products = AllocatedProduct::where('allocation_id', $allocation_id)->get();
        // Joining Allocated -> Inventory -> Members Table to get the allocated products info and member name from Member Table usign inventory->member_id
        $allocated_products = DB::table('allocated_products')
        ->join('inventories', 'allocated_products.inventory_id', '=', 'inventories.id')
        ->join('members', 'inventories.member_id', '=', 'members.id')
        ->select('allocated_products.*', 'members.member_name')
        ->where('allocation_id',$allocation_id)
        ->get();

        //Set Y Positions
        $formRowArray = array(101,106,110.5,115,119,123.5,128,132,137.5,143,148,153,157,161,165,170,174,179.5,185,190,194.5,198.5,203,207.5,211.5,216,221,226);
        $formRowCount = 0;

        foreach ($allocated_products as $allocatedProductsDetails) {
            $inventoryId =  $allocatedProductsDetails->inventory_id;
            $productCode =  $allocatedProductsDetails->product_code;
            $productName =  $allocatedProductsDetails->product_name;
            $expiryDate = date('m/d/Y', strtotime($allocatedProductsDetails->expiry_date));
            $lotNo =  $allocatedProductsDetails->lot_no;
            $quantity =  $allocatedProductsDetails->quantity;
            $memberName =  $allocatedProductsDetails->member_name;

            $pdf->SetXY(16,"{$formRowArray[$formRowCount]}");
            $pdf->SetTextColor(43,43,43);	
            $pdf->SetFont('Arial','',8);
            $pdf->Cell(0,0,"{$memberName}");
            
            $pdf->SetXY(53,"{$formRowArray[$formRowCount]}");
            $pdf->SetTextColor(43,43,43);	
            $pdf->SetFont('Arial','',8);
            $pdf->Cell(0,0,"{$productCode}");
            
            $pdf->SetXY(74,"{$formRowArray[$formRowCount]}");
            $pdf->SetTextColor(43,43,43);	
            $pdf->SetFont('Arial','',7);
            $pdf->Cell(0,0,"{$productName}");
            
            $pdf->SetXY(136,"{$formRowArray[$formRowCount]}");
            $pdf->SetTextColor(43,43,43);	
            $pdf->SetFont('Arial','',8);
            $pdf->Cell(0,0,"{$expiryDate}");
            
            $pdf->SetXY(161,"{$formRowArray[$formRowCount]}");
            $pdf->SetTextColor(43,43,43);	
            $pdf->SetFont('Arial','',8);
            $pdf->Cell(0,0,"{$lotNo}");
            
            $pdf->SetXY(182,"{$formRowArray[$formRowCount]}");
            $pdf->SetTextColor(43,43,43);	
            $pdf->SetFont('Arial','',8);
            $pdf->Cell(0,0,"{$quantity}");

            $formRowCount++;
        }

        $pdf->SetXY(16,246);
        $pdf->SetTextColor(43,43,43);	
        $pdf->SetFont('Arial','',8);
        $pdf->Cell(0,0,"Program Manager");
        
        $pdf->SetXY(59,246);
        $pdf->SetTextColor(43,43,43);	
        $pdf->SetFont('Arial','',8);
        $pdf->Cell(0,0,"Chief Executive Officer");
        
        $pdf->SetXY(24,260);
        $pdf->SetTextColor(43,43,43);	
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(0,0,"Dennis Tuazon");
        
        $pdf->SetXY(56,260);
        $pdf->SetTextColor(43,43,43);	
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(0,0,"Dr. Maria Rosita Q. Siasoco");

        // $destination_path = $_SERVER["DOCUMENT_ROOT"]."/pdf/dna/{$allocation_no}_DNA.pdf";
        $destination_path = public_path("/pdf/dna/{$allocation_no}_DNA.pdf");
        $pdf->Output($destination_path,'F');
    }

    public function createDTACForm ($allocation_id) 
    {
        $allocationDetails = Allocation::findOrFail($allocation_id);

        $allocationNo = $allocationDetails['allocation_no'];
        $dnaNo = $allocationDetails['dna_no'];
        $noticeTo = $allocationDetails['notice_to'];
        $beneficiaryId = $allocationDetails['beneficiary_id'];
        $authRep = $allocationDetails['authorized_representative'];
        $position = $allocationDetails['position'];
        $contactNumber = $allocationDetails['contact_number'];
        $deliveryAddress = $allocationDetails['delivery_address'];
        $deliveryDate = $allocationDetails['delivery_date'];
        $deliveryInstructions = $allocationDetails['other_delivery_instructions'];
        $createDate = $allocationDetails['created_at'];
        $totalAllocatedProducts = $allocationDetails['total_allocated_products'];


        $dtac_template_1 = public_path("/images/templates/dtacTemplate1.jpg");

        //Insert Contribution Details In PDF Page
        //Set Page Layout and Add PDF Page
        $pdf = new FPDF('P','mm','Letter');
        $pdf->AddPage();
        $pdf->Image($dtac_template_1,-8,0,0,297);
        
        //PDF Contents
        $pdf->SetXY(45,205);
        $pdf->SetTextColor(43,43,43);	
        $pdf->SetFont('Arial','',8);
        $pdf->Cell(0,0,"{$authRep}");
        
        $pdf->SetXY(45,210);
        $pdf->SetTextColor(43,43,43);	
        $pdf->SetFont('Arial','',8);
        $pdf->Cell(0,0,"{$position}");
        
        $pdf->SetXY(45,216);
        $pdf->SetTextColor(43,43,43);	
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(0,0,"{$beneficiaryId}");
        
        $pdf->SetXY(45,220);
        $pdf->SetTextColor(43,43,43);	
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(0,0,"{$deliveryAddress}");
        
        $pdf->SetXY(45,236);
        $pdf->SetTextColor(43,43,43);	
        $pdf->SetFont('Arial','',8);
        $pdf->Cell(0,0,"{$contactNumber}");

        $dtac_template_2 = public_path("/images/templates/dtacTemplate2.jpg");
        
        $pdf->AddPage();
        $pdf->Image($dtac_template_2,-8,0,0,297);


        $allocated_products = DB::table('allocated_products')
        ->join('inventories', 'allocated_products.inventory_id', '=', 'inventories.id')
        ->join('members', 'inventories.member_id', '=', 'members.id')
        ->select('allocated_products.*', 'members.member_name')
        ->where('allocation_id',$allocation_id)
        ->get();

        $positionY = 43;
        foreach ($allocated_products as $allocatedProductsDetails) {
            $inventoryId =  $allocatedProductsDetails->inventory_id;
            $productCode =  $allocatedProductsDetails->product_code;
            $productName =  $allocatedProductsDetails->product_name;
            $quantity =  $allocatedProductsDetails->quantity;
            $lotNo =  $allocatedProductsDetails->lot_no;
            $expiryDate =  date('m/d/Y', strtotime($allocatedProductsDetails->expiry_date));
            $drugRegNo =  $allocatedProductsDetails->drug_reg_no;
            $unitCost =  $allocatedProductsDetails->unit_cost;
            $total =  $allocatedProductsDetails->total;
            $medicineStatus =  $allocatedProductsDetails->medicine_status;
            $memberName =  $allocatedProductsDetails->member_name;

            $pdf->SetXY(19,"{$positionY}");
            $pdf->SetTextColor(43,43,43);	
            $pdf->SetFont('Arial','',8);
            $pdf->Cell(0,0,"{$memberName}");
            
            $pdf->SetXY(58,"{$positionY}");
            $pdf->SetTextColor(43,43,43);	
            $pdf->SetFont('Arial','',8);
            $pdf->Cell(0,0,"{$productName}");
            
            $pdf->SetXY(129,"{$positionY}");
            $pdf->SetTextColor(43,43,43);	
            $pdf->SetFont('Arial','',8);
            $pdf->Cell(0,0,"{$expiryDate}");
            
            $pdf->SetXY(151,"{$positionY}");
            $pdf->SetTextColor(43,43,43);	
            $pdf->SetFont('Arial','',8);
            $pdf->Cell(0,0,"{$quantity}");
            
            $pdf->SetXY(163,"{$positionY}");
            $pdf->SetTextColor(43,43,43);	
            $pdf->SetFont('Arial','B',8);
            $pdf->Cell(0,0,"PHP {$total}");
            
            $positionY = $positionY + 5;
        }

        // $random = rand(1,10000);
        $destination_path = public_path("/pdf/dtac/{$allocationNo}_DTAC.pdf");
        $pdf->Output($destination_path,'F');

    }

    public function saveTotalDonations($allocation_id, Request $request) {
        $allocation = Allocation::findOrFail($allocation_id);
        // $allocation->total_medicine = $request->total_donations["medicine_total_donation"];
        // $allocation->total_promats = $request->total_donations["promats_total_donation"];
        // $allocation->total_allocated_products = $request->total_donations["total_products_amount"];

        $this->createDNAForm($allocation_id);
        $this->saveDocuments($allocation_id,$allocation->allocation_no,$allocation->dna_no,"DNA");
        $allocation->status = 1;
        $allocation->save();
    }

    public function getAllocatedProduct($allocation_id) {
        $allocated_product = AllocatedProduct::where('allocation_id',$allocation_id)->where('status',1)->get();
        return $allocated_product;
    }

    public function saveAllocatedProduct($allocation_id, Request $request)
    {
		$currentDateTime = Carbon::now();

        $inventory_id = $request->selected_product["inventory_id"];
        $input_quantity = $request->selected_product["quantity"];

        $inventory = Inventory::findOrFail($inventory_id);

        $unit_cost = $inventory->unit_cost;
        $product_type = $inventory->product_type;
        $product_code = $inventory->product_code;
        $product_name = $inventory->product_name;
        $quantity = $inventory->quantity;
        $lot_no = $inventory->lot_no;
        $mfg_date = $inventory->mfg_date;
        $expiry_date = $inventory->expiry_date;
        $drug_reg_no = $inventory->drug_reg_no;
        $medicine_status = "Status test";
        $job_no = $inventory->job_no;

        $selected_product = new AllocatedProduct;
        $selected_product->allocation_id = $allocation_id;
        $selected_product->inventory_id = $inventory_id;
        $selected_product->product_type = $product_type;
        $selected_product->product_code = $product_code;
        $selected_product->product_name = $product_name;
        $selected_product->quantity = $input_quantity;
        $selected_product->lot_no = $lot_no;
        $selected_product->mfg_date = $mfg_date;
        $selected_product->expiry_date = $expiry_date;
        $selected_product->drug_reg_no = $drug_reg_no;
        $selected_product->unit_cost = $unit_cost;
        $selected_product->medicine_status = $medicine_status;
        $selected_product->job_no = $job_no;
        $selected_product->total = $unit_cost * $input_quantity;
        $selected_product->status = 1;
        $selected_product->save();

        //Compute And Check Available Stock/Quantity
		$deducted_inventory_quantity = $quantity - $input_quantity;

        //Compute Allocation Total Value
		$deducted_inventory_total = $deducted_inventory_quantity * $unit_cost;

        // deduct the issuance quantity to the inventory quantity
        $inventory->quantity = $deducted_inventory_quantity;
        $inventory->total = $deducted_inventory_total;
        $inventory->updated_at = $currentDateTime;
        $inventory->save();

        $this->processAllocationAmountUpdate($allocation_id);
        // 35000
        return $selected_product;
    }

    public function deleteAllocatedProduct ($allocated_product_id) {

        $allocated_product = AllocatedProduct::findOrFail($allocated_product_id);
        $inventory_id = $allocated_product->inventory_id; 
        $allocation_id = $allocated_product->allocation_id; 

        $inventory = Inventory::findOrFail($inventory_id);
        // adding back the quantity from allocated to inventory
        $inventory->quantity += $allocated_product->quantity;
        $inventory->save();

        $allocated_product->delete();
        // return $quantity;
    }

    public function allocationAddProducts($allocation_id) 
    {
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

        $members = Member::all();
        $allocation = Allocation::findOrFail($allocation_id);
        // $inventory = Inventory::where('member_id',1)->get();
        $inventory = Inventory::where('member_id',1)->get();

        return view('allocations.allocation-add-products')
        ->with('members', $members)
        ->with('inventory', $inventory)
        ->with('allocation_id', $allocation_id)
        ->with('allocations_notif', $allocations_notif)
        ->with('contributions_notif', $contributions_notif);
    }

    public function getMembers() 
    {
        $members = Member::all();
        return $members;
    }
    
    public function getInventory($member_id, $allocation_id) {
        //get the inventory
        $get_inventory = Inventory::where('member_id', $member_id)
        ->where('quantity','!=', 0)
        ->get();
        // array of inventory item
        $inventory = [];
        
        // 	Do not add the product If The Details Are Already Added In Allocated Products
  
        // checking, filtering, and inserting back the non added product in the allocation inside the array
        foreach ($get_inventory as $item) {
            $allocated_product = AllocatedProduct::where('allocation_id', $allocation_id)
            ->where('inventory_id', $item->id)->get();

            if($allocated_product->count() == 0) {
                array_push($inventory,$item);
            }
        }
        return $inventory;
    }

    public function getSelectedProduct($product_id){
        $inventory = Inventory::findOrFail($product_id);
        return $inventory;
    }

    public function allocationCreate() {
        
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
        // $allocation_no = Str::uuid();
        $allocation_no = 'AN-'.Str::random(10);
        
        return view('allocations.allocation-create')
        ->with('allocation_no', $allocation_no)
        ->with('beneficiaries', $beneficiaries)
        ->with('allocations_notif', $allocations_notif)
        ->with('contributions_notif', $contributions_notif);
    }

    public function allocationCreateRead($id) {
        
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

        $allocation = Allocation::where('id', $id)
        ->first();
        // $allocation = Allocation::findOrFail($id);

        // return $allocation;
        // ->select('id','contribution_no','member_id','distributor','contribution_date')
        
        return view('allocations.allocation-create')
        ->with('allocation', $allocation)
        ->with('beneficiaries', $beneficiaries)
        ->with('allocations_notif', $allocations_notif)
        ->with('contributions_notif', $contributions_notif);
    }

    public function saveAllocation(Request $request) {
        $allocation = Allocation::updateOrCreate(
            ['allocation_no' => $request->allocation_no],
            ['dna_no' => $request->dna_no,
            'beneficiary_id' => $request->beneficiary_id,
            'notice_to' => $request->notice_to,
            'authorized_representative' => $request->authorized_representative,
            'position' => $request->position,
            'contact_number' => $request->contact_number,
            'delivery_address'=> $request->delivery_address,
            'delivery_date'=> new Carbon($request->delivery_date),
            'other_delivery_instructions'=> $request->other_delivery_instructions,
            'status'=> 0]
        );

        $allocation_id = $allocation->id;
        return redirect()->route('allocation-add-products', ['allocation_id' => $allocation_id]);

    }

    public function allocationList() {
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

        $allocations_drafts = DB::table('allocations')
        ->where('status',0)
        ->get();

        $allocations = DB::table('allocations')
        ->where('status','!=',0)
        ->select('id','allocation_no','dna_no','created_at','total_allocated_products','status')
        ->get();

        // $allocations = Allocation::all()->sortByDesc('id');

        return view('allocations.allocation-list')->with('allocations', $allocations)
        ->with('allocations_drafts', $allocations_drafts)
        ->with('allocations_notif', $allocations_notif)
        ->with('contributions_notif', $contributions_notif);
    }

    public function allocationDetails($allocation_id) {
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

        $allocation = Allocation::findOrFail($allocation_id);
        $allocated_products = AllocatedProduct::where('allocation_id', $allocation_id)->get();
        $documents = Document::where('allocation_id', $allocation_id)->get();
        $beneficiary = Beneficiary::findOrFail($allocation->beneficiary_id);
        $beneficiary_name = $beneficiary->name;

        $promats_count = 0;
        $medicine_count = 0;
        $total_count = 0;
        
        $total_quantity = 0;
        $total_amount = 0;

        $cancelled_total_quantity = 0;
        $cancelled_total_donation = 0;

        foreach ($allocated_products as $donation) {
            if($donation->product_type == 1 && $donation->status == 1) {
                $medicine_count += 1;
            } else if ($donation->product_type == 2 && $donation->status == 1){
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

        return view('allocations.allocation-details')
        ->with('promats_count', $promats_count)
        ->with('medicine_count', $medicine_count)
        ->with('total_count', $total_count)
        ->with('total_quantity', $total_quantity)
        ->with('total_amount', $total_amount)
        ->with('cancelled_total_quantity', $cancelled_total_quantity)
        ->with('cancelled_total_donation', $cancelled_total_donation)
        ->with('beneficiary_name',$beneficiary_name)
        ->with('allocation',$allocation)
        ->with('allocated_products',$allocated_products)
        ->with('documents',$documents)
        ->with('allocations_notif', $allocations_notif)
        ->with('contributions_notif', $contributions_notif);
    }


    public function cancelAllocatedProduct (Request $request) {
        $allocated_product = AllocatedProduct::findOrFail($request->donation_id);
        $inventory_id = $allocated_product->inventory_id; 
        $allocation_id = $allocated_product->allocation_id;

        // saving the status as cancelled
        $allocated_product->status = 2;
        $allocated_product->save();
        
        // Update Allocation Total med, promats, donation
        $allocation = Allocation::findOrFail($allocation_id);
        
        if($allocated_product->product_type == 1) {
            $allocation->total_medicine -= $allocated_product->total;
            $allocation->total_allocated_products -= $allocated_product->total;
        }

        if($allocated_product->product_type == 2) {
            $allocation->total_promats -= $allocated_product->total;
            $allocation->total_allocated_products -= $allocated_product->total;
        }

        $allocation->save();
        // revert back the quantity and total of allocated product back to inventory
        $inventory = Inventory::findOrFail($inventory_id);
        // adding back the quantity from allocated to inventory
        $inventory->quantity += $allocated_product->quantity;
        $inventory->total += $allocated_product->total;  
        $inventory->save();
        return back();

        // $allocated_product->delete();
        // return $quantity;
    }


}
