<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Inventory;
use App\Contribution;
use App\Allocation;
use App\Summary;
use App\TransactionReport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class InventoryController extends Controller
{
    public function inventoryList() {
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

        $inventory = Inventory::all();

        return view ('inventory.inventory-list')
        ->with('inventory', $inventory)
        ->with('allocations_notif', $allocations_notif)
        ->with('contributions_notif', $contributions_notif);
    }

    public function inventoryDetails($id) {
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

        $inventory = Inventory::findOrFail($id);
        if($inventory->product_type == 1) {
            $inventory->product_type = "Medicine/Vaccine";
        } else if($inventory->product_type == 2) {
            $inventory->product_type = "Promotional Materials";
        } else if($inventory->product_type == 3){
            $inventory->product_type = "Cash Donation";
        }
        return view ('inventory.inventory-edit')
        ->with('inventory',$inventory)
        ->with('allocations_notif', $allocations_notif)
        ->with('contributions_notif', $contributions_notif);
    }

    public function inventoryUpdate($inventory_id, Request $request) {
        $inventory = Inventory::findOrFail($inventory_id);

        //Get Existing Inventory Unit Cost And Quantity
        $inventory_quantity = $inventory->quantity;
        $unit_cost = $inventory->unit_cost;

        //Get User inputs
		$product_name = $request->product_name;
		$quantity = $request->quantity;
		$mfg_date = new Carbon($request->mfg_date);
		$expiry_date = new Carbon($request->expiry_date);
		$drug_reg_no = $request->drug_reg_no;
		$job_no = $request->job_no;

		//Compute Updated Inventory Product Value
		$inventoryTotal = $quantity * $unit_cost;
        
        // Save Update
        $inventory->product_name = $product_name;
        $inventory->quantity = $quantity;
        $inventory->mfg_date = $mfg_date;
        $inventory->expiry_date = $expiry_date;
        $inventory->drug_reg_no = $drug_reg_no;
        $inventory->updated_at = Carbon::now();

        if($inventory->save() == 0){
        } else {
            $inventory = Inventory::find($inventory_id);

            $member_id = $inventory->member_id;
            $product_code = $inventory->product_code;
            $product_name = $inventory->product_name;
            $lot_no = $inventory->lot_no;
            $expiry_date = new Carbon($inventory->expiry_date);
            $job_no = $inventory->job_no;

            $transactionType ="";

			//Identify Adjustment Type And Compute For The Adjustments
			if ($inventory_quantity > $quantity) {
				//Issuance Adjustment
				$transactionType = "ADJI";
			} else if ($inventory_quantity < $quantity) {
				//Receipt Adjustment
				$transactionType = "ADJR";
			} else {
				//No Adjustments Made, Existing And New Quantity Are Equal
			}
            
			if (($transactionType == 'ADJI') || ($transactionType == 'ADJR')) {
                //Generate Transaction Report
				//Get The Opening Balance By Getting The Ending Balance From Last Month In Summary Table

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

                // 12.23.2022 5.02pm

                $quantityColumn = '';
                $amountColumn = '';

                //Insert Transaction Report
				if ($transactionType == 'ADJI') {
					$quantityColumn = 'issuance_quantity';
					$amountColumn = 'issuance_amount';
				} else {
					$quantityColumn = 'receipt_quantity';
					$amountColumn = 'receipt_amount';
				}

                //Compute For The Adjustment Quantity
				$adjustmentQuantity = $inventory_quantity - $quantity;
				$adjustmentQuantity = ltrim($adjustmentQuantity,"-");

                //Compute For The Adjustment Total Value
				$adjustmentTotal = $adjustmentQuantity * $unit_cost;

                $transaction_report = new TransactionReport;
                $transaction_report->member_id = $member_id;
                $transaction_report->inventory_id = $inventory_id;
                $transaction_report->month = $currentDateMonth;
                $transaction_report->year = $currentDateYear;
                $transaction_report->product_code = $product_code;
                $transaction_report->product_name = $product_name;
                $transaction_report->lot_no = $lot_no;
                $transaction_report->opening_balance_quantity = $openingBalanceQuantity;
                $transaction_report->transaction_type = $transactionType;
                $transaction_report->quantity = $quantity;
                $transaction_report->unit_cost = $unit_cost;

                $transaction_report->$quantityColumn = $adjustmentQuantity;
                $transaction_report->$amountColumn = $adjustmentTotal;

                $transaction_report->expiry_date = $expiry_date;
                $transaction_report->remarks = '';
                $transaction_report->status = 1;
                $transaction_report->job_no = $job_no;
                $transaction_report->save();

                // Last Progress 02/09/2023 9.16pm

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
                    $summaryId = $summaryDetails['summary_id'];
					$movementsQuantity = $summaryDetails['movements_quantity'];
                }

                //Compute For The Updated Quantity Movements
				if ($transactionType == 'ADJI') {
					$movementsQuantity = $movementsQuantity - $adjustmentQuantity; //Operation Is Subtraction Because The Tranasction Is Issuance Adjustment
				} else {
					$movementsQuantity = $movementsQuantity + $adjustmentQuantity; //Operation Is Addition Because The Tranasction Is Receipt Adjustment
				}
							
				//Compute For The Updated Ending Balance Quantity
				$endingBalanceQuantity = $lastSummaryEndingBalanceQuantity + $movementsQuantity;
							
				//Compute For The Updated Ending Balance Value
				$endingBalanceValue = $endingBalanceQuantity * $unit_cost;

                $summary = Summary::find($summaryId);
                $summary->movements_quantity = $movementsQuantity;
                $summary->ending_balance_quantity = $movementsQuantity;
                $summary->ending_balance_value = $movementsQuantity;
                $summary->updated_at = $movementsQuantity;
                $summary->save();

				// $this->query("UPDATE summary SET movements_quantity = '{$movementsQuantity}', ending_balance_quantity = '{$endingBalanceQuantity}', ending_balance_value = '{$endingBalanceValue}', update_date = '{$currentDateTime}' WHERE summary_id = {$summaryId}");


            }
            
            // return $inventory;
        }

        // return redirect()->route('inventory-list')->with('inventory-updated','Inventory successfully updated!');
    }


}
