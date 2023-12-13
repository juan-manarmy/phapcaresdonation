<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Member;
use App\TransactionReport;
use App\Summary;
use App\Beneficiary;
use App\Destruction;
use App\Contribution;
use App\Allocation;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ReportsController extends Controller
{
    // inbound
    public function SavereportsList() {
        $members = Member::all();
        
        $current_date = Carbon::now();
        $current_month = $current_date->month;
        $current_year = $current_date->year;
        $current_month_name = $current_date->format('F');

        $transaction_reports = TransactionReport::where('month',$current_month)
        ->where('year',$current_year)->get();

        $summaries = Summary::where('month',$current_month)
        ->where('year',$current_year)->get();

        // Get sum total receipt quantity 
        $total_receipt_quantity = TransactionReport::where('month',$current_month)
        ->where('year',$current_year)
        ->where('transaction_type','IMP')
        ->where('status',1)
        ->sum('receipt_quantity');

        // Get sum total receipt quantity 
        $total_receipt_amount = TransactionReport::where('month',$current_month)
        ->where('year',$current_year)
        ->where('transaction_type','IMP')
        ->where('status',1)
        ->sum('receipt_amount');

        return view ('reports.reports-list')
        ->with(compact('summaries','members','total_receipt_quantity','total_receipt_amount'))
        ->with('current_month', $current_month_name)
        ->with('current_year', $current_year);
    }

    public function reportsList() {
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

        $current_date = Carbon::now();
        $current_month = $current_date->month;
        $current_year = $current_date->year;

        $member_id = 0;
        $year = $current_year;
        $month = $current_month;
        $month_name = date("F", mktime(0, 0, 0, $month, 10));
        $inventory_location = '';
        $transaction_type = 'EXP';
        $tab = 'TR';
        $transaction_table_title = "Outbound";
        $transaction_total_titles = "Total Issuance";

        $members_list = DB::table('members')->select('id','member_name')->get();
        
        if($member_id != 0) {
            $members = Member::where('id',$member_id)->select('id','member_name')->get();
        } else {
            $members = DB::table('members')->select('id','member_name')->get();
        }

        $total_receipt_quantity = 0;
        $total_receipt_amount = 0;
        $total_issuance_quantity = 0;
        $total_issuance_amount = 0;
        
        $total_issuance_quantity = TransactionReport::where('month',$month)
        ->where('year',$year)
        ->where('transaction_type',$transaction_type)
        ->where('status',1)
        ->sum('issuance_quantity');

        // Get sum total issuance quantity 
        $total_issuance_amount = TransactionReport::where('month',$month)
        ->where('year',$year)
        ->where('transaction_type',$transaction_type)
        ->where('status',1)
        ->sum('issuance_amount');

        $total_destruction_quantity = TransactionReport::where('month',$month)
        ->where('year',$year)
        ->where('transaction_type','DES')
        ->where('status',1)
        ->sum('destruction_quantity');

        $total_destruction_amount = TransactionReport::where('month',$month)
        ->where('year',$year)
        ->where('transaction_type','DES')
        ->where('status',1)
        ->sum('destruction_amount');

        $total_qty_movements = Summary::where('month',$month)
        ->where('year',$year)
        ->sum('movements_quantity');

        $total_ending_balance_qty = Summary::where('month',$month)
        ->where('year',$year)
        ->sum('ending_balance_quantity');

        $total_ending_balance_value = Summary::where('month',$month)
        ->where('year',$year)
        ->sum('ending_balance_value');

        return view ('reports.reports-list-filtered')
        ->with(compact('members','members_list','member_id','total_receipt_quantity',
        'total_receipt_amount','total_destruction_quantity','total_destruction_amount',
        'total_qty_movements','total_ending_balance_qty','total_ending_balance_value'))
        ->with('tab',$tab)
        ->with('inventory_location',$inventory_location)
        ->with('transaction_type',$transaction_type)
        ->with('transaction_table_title',$transaction_table_title)
        ->with('transaction_total_titles',$transaction_total_titles)
        ->with('total_issuance_amount',$total_issuance_amount)
        ->with('total_issuance_quantity',$total_issuance_quantity)
        ->with('month', $month)
        ->with('month_name', $month_name)
        ->with('year', $year)
        ->with('allocations_notif', $allocations_notif)
        ->with('contributions_notif', $contributions_notif);
    }

    public function filteredReportsList(Request $request) {

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
        
        $members_list = DB::table('members')->select('id','member_name')->get();
        $inventory_location = $request->inventory_location;

        $member_id = $request->member_id;
        $year = $request->year;
        $month = $request->month;

        $month_name = date("F", mktime(0, 0, 0, $month, 10));

        $transaction_type = $request->transaction_type;
        $transaction_table_title = "";
        $transaction_total_titles = "";

        $tab = $request->tab;
        
        if($member_id != 0) {
            $members = Member::where('id',$member_id)->select('id','member_name')->get();
        } else {
            $members = DB::table('members')->select('id','member_name')->get();
        }

        if($transaction_type == "EXP") {
            $transaction_table_title = "Outbound";
            $transaction_total_titles = "Total Issuance";

        } else if($transaction_type == "IMP") {
            $transaction_table_title = "Inbound";
            $transaction_total_titles = "Total Receipt";

        } else if($transaction_type == "ADJI") {
            $transaction_table_title = "Adjustment Issuance";
            $transaction_total_titles = "Total Adjustment Issuance";

        } else if($transaction_type == "ADJR") {
            $transaction_table_title = "Adjustment Receipt";
            $transaction_total_titles = "Total Adjustment Receipt";
        }

        $total_receipt_quantity = 0;
        $total_receipt_amount = 0;
        $total_issuance_quantity = 0;
        $total_issuance_amount = 0;

        if($transaction_type == 'IMP' || $transaction_type == 'ADJR') {
            // Get sum total receipt quantity 
            $total_receipt_quantity = TransactionReport::where('month',$month)
            ->where('inventory_location','LIKE','%'.$inventory_location.'%')
            ->where('year',$year)
            ->where('transaction_type',$transaction_type)
            ->where('status',1)
            ->sum('receipt_quantity');

            // Get sum total receipt quantity 
            $total_receipt_amount = TransactionReport::where('month',$month)
            ->where('inventory_location','LIKE','%'.$inventory_location.'%')
            ->where('year',$year)
            ->where('transaction_type',$transaction_type)
            ->where('status',1)
            ->sum('receipt_amount');
        }

        if($transaction_type == 'EXP' || $transaction_type == 'ADJI') {
            // Get sum total issuance quantity 
            $total_issuance_quantity = TransactionReport::where('month',$month)
            ->where('inventory_location','LIKE','%'.$inventory_location.'%')
            ->where('year',$year)
            ->where('transaction_type',$transaction_type)
            ->where('status',1)
            ->sum('issuance_quantity');

            // Get sum total issuance quantity 
            $total_issuance_amount = TransactionReport::where('month',$month)
            ->where('inventory_location','LIKE','%'.$inventory_location.'%')
            ->where('year',$year)
            ->where('transaction_type',$transaction_type)
            ->where('status',1)
            ->sum('issuance_amount');
        }

        $total_destruction_quantity = TransactionReport::where('month',$month)
        ->where('inventory_location','LIKE','%'.$inventory_location.'%')

        ->where('year',$year)
        ->where('transaction_type','DES')
        ->where('status',1)
        ->sum('destruction_quantity');

        $total_destruction_amount = TransactionReport::where('month',$month)
        ->where('inventory_location','LIKE','%'.$inventory_location.'%')

        ->where('year',$year)
        ->where('transaction_type','DES')
        ->where('status',1)
        ->sum('destruction_amount');

        $total_qty_movements = Summary::where('month',$month)
        ->where('year',$year)
        ->sum('movements_quantity');

        $total_ending_balance_qty = Summary::where('month',$month)
        ->where('year',$year)
        ->sum('ending_balance_quantity');

        $total_ending_balance_value = Summary::where('month',$month)
        ->where('year',$year)
        ->sum('ending_balance_value');

        return view ('reports.reports-list-filtered')
        ->with(compact('members','members_list','member_id','total_receipt_quantity',
        'total_receipt_amount','total_destruction_quantity','total_destruction_amount',
        'total_qty_movements','total_ending_balance_qty','total_ending_balance_value'))
        ->with('tab',$tab)
        ->with('transaction_type',$transaction_type)
        ->with('inventory_location',$inventory_location)
        ->with('transaction_table_title',$transaction_table_title)
        ->with('transaction_total_titles',$transaction_total_titles)
        ->with('total_issuance_amount',$total_issuance_amount)
        ->with('total_issuance_quantity',$total_issuance_quantity)
        ->with('month_name', $month_name)
        ->with('month', $month)
        ->with('year', $year)
        ->with('allocations_notif', $allocations_notif)
        ->with('contributions_notif', $contributions_notif);
    }

    public function generateExcelReport(Request $request) {
        $spreadsheet = new Spreadsheet();

        $spreadsheet->getProperties()->setCreator("PHAPCARES")
        ->setLastModifiedBy("PHAP Cares Admin")
        ->setTitle("PHAP Cares Product Donation System")
        ->setSubject("PHAP Cares Inventory Report")
        ->setDescription("PHAP Cares Generate Inventory Report")
        ->setKeywords("PHAP Cares Report")
        ->setCategory("Report Generation");

        $spreadsheet->setActiveSheetIndex(0);
        $spreadsheet->getActiveSheet()->freezePane('H9');

        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(12);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(10);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(10);
        $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(12);
        $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(10);
        $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(10);
        $spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(10);
        $spreadsheet->getActiveSheet()->getColumnDimension('L')->setWidth(10);
        $spreadsheet->getActiveSheet()->getColumnDimension('M')->setWidth(10);
        $spreadsheet->getActiveSheet()->getColumnDimension('N')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('O')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('P')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('Q')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('R')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('S')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('T')->setWidth(15);

        //Transaction Report Header
        //Header Title
        $spreadsheet->getActiveSheet()->mergeCells('A1:B1');
                
        $spreadsheet->getActiveSheet()->getCell('A1')
        ->setValue('PHAP Cares Inventory Report : ');
                
        $spreadsheet->getActiveSheet()->mergeCells('C1:D1');

        //Get Report Month And Year
        $year = $request->year;
        $month = $request->month;
        $selected_member_id = $request->member_id;
        $inventory_location = $request->inventory_location;

        // dd($inventory_location);

        $reportMonthYear = $year."-".$month;
        
        $reportMonthYearHeader = date_format(date_create($reportMonthYear),"F Y");
        
        $spreadsheet->getActiveSheet()->getCell('C1')
        ->setValue("{$reportMonthYearHeader}");

        //Header Title Style Properties
        $style = array(
            'alignment' => array('horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER,),
            'font'  => array('bold'  => true, 'color' => array('rgb' => 'FFFFFF'), 'size'  => 10, 'name'  => 'Calibri'),
            'borders' => array('allborders' => array('style' => Border::BORDER_THIN,  'color' => array('rgb' => '179B78')))
        );
        
        $style2 = array(
            'alignment' => array('horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER,),
            'font'  => array('bold'  => true, 'color' => array('rgb' => '99F9B1'), 'size'  => 10, 'name'  => 'Calibri'),
            'borders' => array('allborders' => array('style' => Border::BORDER_THIN,  'color' => array('rgb' => '179B78')))
        );

        $spreadsheet->getActiveSheet()->getStyle("A1:T1")->applyFromArray($style);
        $spreadsheet->getActiveSheet()->getStyle("C1")->applyFromArray($style2);
        
        $spreadsheet->getActiveSheet()->getStyle('A1:T1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('179B78');

        //Header Details
        $spreadsheet->getActiveSheet()->getCell('A2')
        ->setValue("Transaction Report");

        $spreadsheet->getActiveSheet()->mergeCells('B2:C2');

        //Set Report Date
        $currentDate = Carbon::now();

        if (strtotime($reportMonthYear) > strtotime($currentDate)) {
            //No Generated Transaction As Of This Time
            $reportDateFrom = "NA";
            $reportDateTo = "NA";
        } else if (strtotime($currentDate) > strtotime($reportMonthYear)) {
            //Reports From Past Months
            //Set Report Date From : First Day Of The Month
            $reportDateFrom = date_create($year."-".$month."-01");
            $reportDateFrom = date_format($reportDateFrom,"m-d-Y");
            
            //Set Report Date To : Last Day Of The Month
            $reportDateTo = date_create($year."-".$month."-01");
            $reportDateTo = date_format($reportDateTo,"m-t-Y");
        } else {
            //Current Report Month And Year
            //Set Report Date From : First Day Of The Month
            $reportDateFrom = date_create($year."-".$month."-01");
            $reportDateFrom = date_format($reportDateFrom,"m-d-Y");
            
            //Set Report Date To : Current Day Of The Month
            $reportDateTo = date("m-d-Y");
        }

        $spreadsheet->getActiveSheet()->getCell('B2')
        ->setValue("From : {$reportDateFrom}");

        $spreadsheet->getActiveSheet()->mergeCells('D2:E2');

        $spreadsheet->getActiveSheet()->getCell('D2')
                ->setValue("To : {$reportDateTo}");

        //Set Generated Date        
        $generatedDate = date("m-d-Y h:i:s A");

        $spreadsheet->getActiveSheet()->mergeCells('A3:C3');

        $spreadsheet->getActiveSheet()->getCell('A3')
        ->setValue("Transaction Report Generated Date/Time : ");

        $spreadsheet->getActiveSheet()->mergeCells('D3:E3');
                
        $spreadsheet->getActiveSheet()->getCell('D3')
        ->setValue("{$generatedDate}");

        //Header Details Style Properties
        $style = array(
            'alignment' => array('horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER,),
            'font'  => array('bold'  => true, 'color' => array('rgb' => 'FFFFFF'), 'size'  => 10, 'name'  => 'Calibri'),
            'borders' => array('allborders' => array('style' => Border::BORDER_THIN,  'color' => array('rgb' => '56B69C')))
        );
        
        $style2 = array(
            'alignment' => array('horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER,),
            'font'  => array('bold'  => true, 'color' => array('rgb' => '99F9B1'), 'size'  => 10, 'name'  => 'Calibri'),
            'borders' => array('allborders' => array('style' => Border::BORDER_THIN,  'color' => array('rgb' => '56B69C')))
        );

        $spreadsheet->getActiveSheet()->getStyle("A2:T2")->applyFromArray($style);
        $spreadsheet->getActiveSheet()->getStyle("B2")->applyFromArray($style2);
        $spreadsheet->getActiveSheet()->getStyle("D2")->applyFromArray($style2);
        
        $spreadsheet->getActiveSheet()->getStyle("A3:T3")->applyFromArray($style);
        $spreadsheet->getActiveSheet()->getStyle("D3")->applyFromArray($style2);
        
        $spreadsheet->getActiveSheet()->getStyle('A2:T2')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('56B69C');
        $spreadsheet->getActiveSheet()->getStyle('A3:T3')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('56B69C');
        
        //Transaction Summary
        //Summary Title
        $spreadsheet->getActiveSheet()->getCell('A5')
        ->setValue('Transaction Summary');
                    
        //Summary Title Style Properties
        $style = array(
            'alignment' => array('horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER,),
            'font'  => array('bold'  => true, 'color' => array('rgb' => 'FFFFFF'), 'size'  => 10, 'name'  => 'Calibri'),
            'borders' => array('allborders' => array('style' => Border::BORDER_THIN,  'color' => array('rgb' => '5096A0')))
        );

        $spreadsheet->getActiveSheet()->getStyle("A5:T5")->applyFromArray($style);
        $spreadsheet->getActiveSheet()->getStyle('A5:T5')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('5096A0');

        if($selected_member_id == 0) {

            $total_issuance_quantity = TransactionReport::where('month',$month)
            ->where('inventory_location','LIKE','%'.$inventory_location.'%')
            ->where('year',$year)
            ->where('status',1)
            ->sum('issuance_quantity');

            $total_issuance_amount = TransactionReport::where('month',$month)
            ->where('inventory_location','LIKE','%'.$inventory_location.'%')
            ->where('year',$year)
            ->where('status',1)
            ->sum('issuance_amount');

            $total_receipt_quantity = TransactionReport::where('month',$month)
            ->where('inventory_location','LIKE','%'.$inventory_location.'%')
            ->where('year',$year)
            ->where('status',1)
            ->sum('receipt_quantity');

            $total_receipt_amount = TransactionReport::where('month',$month)
            ->where('inventory_location','LIKE','%'.$inventory_location.'%')
            ->where('year',$year)
            ->where('status',1)
            ->sum('receipt_amount');

        } else {

            $total_issuance_quantity = TransactionReport::where('month',$month)
            ->where('inventory_location','LIKE','%'.$inventory_location.'%')
            ->where('member_id',$selected_member_id)
            ->where('year',$year)
            ->where('status',1)
            ->sum('issuance_quantity');

            $total_issuance_amount = TransactionReport::where('month',$month)
            ->where('inventory_location','LIKE','%'.$inventory_location.'%')
            ->where('member_id',$selected_member_id)
            ->where('year',$year)
            ->where('status',1)
            ->sum('issuance_amount');

            $total_receipt_quantity = TransactionReport::where('month',$month)
            ->where('inventory_location','LIKE','%'.$inventory_location.'%')
            ->where('member_id',$selected_member_id)
            ->where('year',$year)
            ->where('status',1)
            ->sum('receipt_quantity');

            $total_receipt_amount = TransactionReport::where('month',$month)
            ->where('inventory_location','LIKE','%'.$inventory_location.'%')
            ->where('member_id',$selected_member_id)
            ->where('year',$year)
            ->where('status',1)
            ->sum('receipt_amount');
        }

        //Receipt Quantity
        $spreadsheet->getActiveSheet()->getCell('A6')
        ->setValue('Receipt Qty : ');

        //Set Number Format
        $spreadsheet->getActiveSheet()->getStyle('B6')
        ->getNumberFormat()
        ->setFormatCode('#,##0');
                
        $spreadsheet->getActiveSheet()->getCell('B6')
        ->setValue("{$total_receipt_quantity}");
                
        //Receipt Amount
        $spreadsheet->getActiveSheet()->getCell('D6')
        ->setValue('Receipt Amt : ');

        //Set Number Format
        $spreadsheet->getActiveSheet()->getStyle('E6')
        ->getNumberFormat()
        ->setFormatCode('#,##0.00');
                            
        $spreadsheet->getActiveSheet()->getCell('E6')
        ->setValue("{$total_receipt_amount}");

        //Issuance Quantity
        $spreadsheet->getActiveSheet()->getCell('A7')
        ->setValue('Issuance Qty : ');

        //Set Number Format
        $spreadsheet->getActiveSheet()->getStyle('B7')
        ->getNumberFormat()
        ->setFormatCode('#,##0');
                
        $spreadsheet->getActiveSheet()->getCell('B7')
        ->setValue("{$total_issuance_quantity}");

        //Issuance Amount
        $spreadsheet->getActiveSheet()->getCell('D7')
        ->setValue('Issuance Amt : ');

        //Set Number Format
        $spreadsheet->getActiveSheet()->getStyle('E7')
        ->getNumberFormat()
        ->setFormatCode('#,##0.00');
                
        $spreadsheet->getActiveSheet()->getCell('E7')
        ->setValue("{$total_issuance_amount}");

        //Summary Details Style Properties
        $style = array(
            'alignment' => array('horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER,),
            'font'  => array('bold'  => true, 'color' => array('rgb' => 'FFFFFF'), 'size'  => 10, 'name'  => 'Calibri'),
            'borders' => array('allborders' => array('style' => Border::BORDER_THIN,  'color' => array('rgb' => '85BCC5')))
        );
        
        $style2 = array(
            'alignment' => array('horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER,),
            'font'  => array('bold'  => true, 'color' => array('rgb' => '253D5B'), 'size'  => 10, 'name'  => 'Calibri'),
            'borders' => array('allborders' => array('style' => Border::BORDER_THIN,  'color' => array('rgb' => '85BCC5')))
        );

        $spreadsheet->getActiveSheet()->getStyle("A6:T6")->applyFromArray($style);
        $spreadsheet->getActiveSheet()->getStyle("B6")->applyFromArray($style2);
        $spreadsheet->getActiveSheet()->getStyle("E6")->applyFromArray($style2);
        
        $spreadsheet->getActiveSheet()->getStyle("A7:T7")->applyFromArray($style);
        $spreadsheet->getActiveSheet()->getStyle("B7")->applyFromArray($style2);
        $spreadsheet->getActiveSheet()->getStyle("E7")->applyFromArray($style2);
        
        $spreadsheet->getActiveSheet()->getStyle('A6:T6')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('85BCC5');
        $spreadsheet->getActiveSheet()->getStyle('A7:T7')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('85BCC5');
        
        //Product Details Header
        $spreadsheet->getActiveSheet()->getCell('A8')
        ->setValue('Product Code');
        
        $spreadsheet->getActiveSheet()->mergeCells('B8:C8');
    
        $spreadsheet->getActiveSheet()->getCell('B8')
        ->setValue('Product Name');
        
        $spreadsheet->getActiveSheet()->getCell('D8')
        ->setValue('Principal');
        
        $spreadsheet->getActiveSheet()->mergeCells('E8:F8');
    
        $spreadsheet->getActiveSheet()->getCell('E8')
        ->setValue('Beneficiary');
        
        $spreadsheet->getActiveSheet()->getCell('G8')
        ->setValue('Lot No');
        
        $spreadsheet->getActiveSheet()->getCell('H8')
        ->setValue('Opening Balance');
        
        $spreadsheet->getActiveSheet()->getCell('I8')
        ->setValue('Txn Type');
        
        $spreadsheet->getActiveSheet()->getCell('J8')
        ->setValue('Qty');
        
        $spreadsheet->getActiveSheet()->getCell('K8')
        ->setValue('Trade Price');
        
        $spreadsheet->getActiveSheet()->getCell('L8')
        ->setValue('Receipt');
        
        $spreadsheet->getActiveSheet()->getCell('M8')
        ->setValue('Issuance');
        
        $spreadsheet->getActiveSheet()->getCell('N8')
        ->setValue('Receipt Amt');
        
        $spreadsheet->getActiveSheet()->getCell('O8')
        ->setValue('Issuance Amt');
        
        $spreadsheet->getActiveSheet()->getCell('P8')
        ->setValue('Reference');
        
        $spreadsheet->getActiveSheet()->getCell('Q8')
        ->setValue('Txn Date');
        
        $spreadsheet->getActiveSheet()->getCell('R8')
        ->setValue('Mfg Date');
        
        $spreadsheet->getActiveSheet()->getCell('S8')
        ->setValue('Exp Date');

        $spreadsheet->getActiveSheet()->getCell('T8')
        ->setValue('Inventory Location');
        
        //Product Details Style Properties
        $style = array(
            'alignment' => array('horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER,),
            'font'  => array('bold'  => true, 'color' => array('rgb' => 'FFFFFF'), 'size'  => 10, 'name'  => 'Calibri'),
            'borders' => array('allborders' => array('style' => Border::BORDER_THIN,  'color' => array('rgb' => 'FFFFFF')))
        );

        $spreadsheet->getActiveSheet()->getStyle("A8:T8")->applyFromArray($style);
        $spreadsheet->getActiveSheet()->getStyle('A8:T8')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('24323D');
        
        $startingRow = 9;
        // Start of Transaction Report

        if($selected_member_id == 0) {
            $members = DB::table('members')->select('id','member_name')->get();
        } else {
            $members = Member::where('id',$selected_member_id)->select('id','member_name')->get();
        }

        $memberCount = $members->count();

        if ($memberCount > 0) {
            foreach($members as $member) {
                $member_id =  $member->id;
                $memberName =  $member->member_name;
    
                //Show Member Name
                $spreadsheet->getActiveSheet()->mergeCells('A'.$startingRow.':T'.$startingRow);
    
                $spreadsheet->getActiveSheet()->getCell('A'.$startingRow)
                ->setValue("{$memberName}");
                
                $style = array(
                    'alignment' => array('horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER,),
                    'font'  => array('bold'  => true, 'color' => array('rgb' => 'FFFFFF'), 'size'  => 11, 'name'  => 'Calibri'),
                    'borders' => array('allborders' => array('style' => Border::BORDER_THIN,  'color' => array('rgb' => '000000')))
                );
    
                $spreadsheet->getActiveSheet()->getStyle('A'.$startingRow.':T'.$startingRow)->applyFromArray($style);
                $spreadsheet->getActiveSheet()->getStyle('A'.$startingRow.':T'.$startingRow)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('63768D');
    
                //Transaction List Query
                $transaction_reports = TransactionReport::where('member_id',$member_id)
                ->where('inventory_location','LIKE','%'.$inventory_location.'%')
                ->where('year',$year)
                ->where('month',$month)
                ->where('status',1)->get();
    
                $totalTransactionRow = $transaction_reports->count();
                
                $productDetailsRow = $startingRow + 1;
    
                if($totalTransactionRow > 0) {
                    foreach($transaction_reports as $transactionReportDetails) {
                        //Product Details
                        $transactionReportId = $transactionReportDetails->transaction_report_id;
                        $memberId = $transactionReportDetails->member_id;
                        $beneficiaryId = $transactionReportDetails->beneficiary_id;
                        $contributionId = $transactionReportDetails->contribution_id;
                        $allocationId = $transactionReportDetails->allocation_id;
                        $month = $transactionReportDetails->month;
                        $year = $transactionReportDetails->year;
                        $contributionNo = $transactionReportDetails->contribution_no;
                        $allocationNo = $transactionReportDetails->allocation_no;
                        $productCode = $transactionReportDetails->product_code;
                        $productName = $transactionReportDetails->product_name;
                        $lotNo = $transactionReportDetails->lot_no;
                        $openingBalanceQuantity = $transactionReportDetails->opening_balance_quantity;
                        $transactionType = $transactionReportDetails->transaction_type;
                        $quantity = $transactionReportDetails->quantity;
                        $unitCost = $transactionReportDetails->unit_cost;
                        $receiptQuantity = $transactionReportDetails->receipt_quantity;
                        $issuanceQuantity = $transactionReportDetails->issuance_quantity;
                        $receiptAmount = $transactionReportDetails->receipt_amount;
                        $issuanceAmount = $transactionReportDetails->issuance_amount;
                        $mfgDate = $transactionReportDetails->mfg_date;
                        $expiryDate = $transactionReportDetails->expiry_date;
                        $createDate = $transactionReportDetails->created_at;
                        $inventoryLocation = $transactionReportDetails->inventory_location;
                                                            
                        $beneficiaryName = "";

                        //Beneficiary Details
                        $beneficiary = Beneficiary::where('id',$beneficiaryId)->get();
                        foreach($beneficiary as $item) {
                            $beneficiaryName = $item->name;
                        }
        
                        //Set Beneficiary Name To Empty If Benficiary Id Is Zero
                        if ($beneficiaryId == 0) {
                            $beneficiaryName = "-";
                        }
        
                        $dndNo = "";
                        $didrfNo = "";

                        //Get Contribution Details
                        $contribution = Contribution::where('id', $contributionId)->get();
                        foreach($contribution as $item){
                            $dndNo = $item->dnd_no;
                            $didrfNo = $item->didrf_no;
                        }

                        //Get Allocation Details
                        $allocation = Allocation::where('id', $allocationId)->get();

                        $dodrfNo = "";
                        $dnaNo = "";
                        
                        foreach($allocation as $item){
                            $dodrfNo = $item->dodrf_no;
                            $dnaNo = $item->dna_no;
                        }
                                   
                        //Identify Reference By Transaction Type
                        if ($transactionType == 'EXP') {
                            $reference = $allocationNo."/DODRF: ".$dodrfNo."/DNA: ".$dnaNo;
                        } else if ($transactionType == 'IMP') {
                            $reference = $contributionNo."/DIDRF: ".$didrfNo."/DNA: ".$dndNo;
                        } else if ($transactionType == 'ADJI') {
                            $reference = 'INVENTORY ADJ-';
                        } else {
                            $reference = 'INVENTORY ADJ+';
                        }
                        
                        $spreadsheet->getActiveSheet()
                        ->getCell('A'.$productDetailsRow)
                        ->setValue("{$productCode}");
                
                        $spreadsheet->getActiveSheet()
                        ->mergeCells('B'.$productDetailsRow.':C'.$productDetailsRow);
                    
                        $spreadsheet->getActiveSheet()
                        ->getCell('B'.$productDetailsRow)
                        ->setValue("{$productName}");
                        
                        $spreadsheet->getActiveSheet()
                        ->getCell('D'.$productDetailsRow)
                        ->setValue("{$memberName}");
                        
                        $spreadsheet->getActiveSheet()
                        ->mergeCells('E'.$productDetailsRow.':F'.$productDetailsRow);
                    
                        $spreadsheet->getActiveSheet()
                        ->getCell('E'.$productDetailsRow)
                        ->setValue("{$beneficiaryName}");
                        
                        $spreadsheet->getActiveSheet()
                        ->getCell('G'.$productDetailsRow)
                        ->setValue("{$lotNo}");
                        
                        //Set Number Format
                        $spreadsheet->getActiveSheet()
                        ->getStyle('H'.$productDetailsRow)
                        ->getNumberFormat()
                        ->setFormatCode('#,##0');
                        
                        $spreadsheet->getActiveSheet()
                        ->getCell('H'.$productDetailsRow)
                        ->setValue("{$openingBalanceQuantity}");
                        
                        $spreadsheet->getActiveSheet()
                        ->getCell('I'.$productDetailsRow)
                        ->setValue("{$transactionType}");
                        
                        //Set Number Format
                        $spreadsheet->getActiveSheet()
                        ->getStyle('J'.$productDetailsRow)
                        ->getNumberFormat()
                        ->setFormatCode('#,##0');
                        
                        $spreadsheet->getActiveSheet()
                        ->getCell('J'.$productDetailsRow)
                        ->setValue("{$quantity}");
                        
                        //Set Number Format
                        $spreadsheet->getActiveSheet()
                        ->getStyle('K'.$productDetailsRow)
                        ->getNumberFormat()
                        ->setFormatCode('#,##0.00');
                        
                        $spreadsheet->getActiveSheet()
                        ->getCell('K'.$productDetailsRow)
                        ->setValue("{$unitCost}");
                        
                        //Set Number Format
                        $spreadsheet->getActiveSheet()
                        ->getStyle('L'.$productDetailsRow)
                        ->getNumberFormat()
                        ->setFormatCode('#,##0');
                        
                        $spreadsheet->getActiveSheet()
                        ->getCell('L'.$productDetailsRow)
                        ->setValue("{$receiptQuantity}");
                        
                        //Set Number Format
                        $spreadsheet->getActiveSheet()
                        ->getStyle('M'.$productDetailsRow)
                        ->getNumberFormat()
                        ->setFormatCode('#,##0');
                        
                        $spreadsheet->getActiveSheet()
                        ->getCell('M'.$productDetailsRow)
                        ->setValue("{$issuanceQuantity}");
                        
                        //Set Number Format
                        $spreadsheet->getActiveSheet()
                        ->getStyle('N'.$productDetailsRow)
                        ->getNumberFormat()
                        ->setFormatCode('#,##0.00');
                        
                        $spreadsheet->getActiveSheet()
                        ->getCell('N'.$productDetailsRow)
                        ->setValue("{$receiptAmount}");
                        
                        //Set Number Format
                        $spreadsheet->getActiveSheet()
                        ->getStyle('O'.$productDetailsRow)
                        ->getNumberFormat()
                        ->setFormatCode('#,##0.00');
                        
                        $spreadsheet->getActiveSheet()
                        ->getCell('O'.$productDetailsRow)
                        ->setValue("{$issuanceAmount}");
                        
                        $spreadsheet->getActiveSheet()
                        ->getCell('P'.$productDetailsRow)
                        ->setValue("{$reference}");
                        
                        $spreadsheet->getActiveSheet()
                        ->getCell('Q'.$productDetailsRow)
                        ->setValue("{$createDate}");
                        
                        $spreadsheet->getActiveSheet()
                        ->getCell('R'.$productDetailsRow)
                        ->setValue("{$mfgDate}");
                        
                        $spreadsheet->getActiveSheet()
                        ->getCell('S'.$productDetailsRow)
                        ->setValue("{$expiryDate}");

                        $spreadsheet->getActiveSheet()
                        ->getCell('T'.$productDetailsRow)
                        ->setValue("{$inventoryLocation}");
                        
                        $style = array(
                            'alignment' => array('horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER,),
                            'font'  => array('color' => array('rgb' => '000000'), 'size'  => 8, 'name'  => 'Calibri'),
                            'borders' => array('allborders' => array('style' => Border::BORDER_THIN,  'color' => array('rgb' => '000000')))
                        );
                        
                        $style2 = array(
                            'alignment' => array('horizontal' => Alignment::HORIZONTAL_RIGHT, 'vertical' => Alignment::VERTICAL_CENTER,),
                            'font'  => array('color' => array('rgb' => '000000'), 'size'  => 8, 'name'  => 'Calibri'),
                            'borders' => array('allborders' => array('style' => Border::BORDER_THIN,  'color' => array('rgb' => '000000')))
                        );
        
                        $spreadsheet->getActiveSheet()->getStyle('A'.$productDetailsRow.':G'.$productDetailsRow)->applyFromArray($style);
                        $spreadsheet->getActiveSheet()->getStyle('H'.$productDetailsRow.':O'.$productDetailsRow)->applyFromArray($style2);
                        $spreadsheet->getActiveSheet()->getStyle('P'.$productDetailsRow.':T'.$productDetailsRow)->applyFromArray($style);
                        $spreadsheet->getActiveSheet()->getStyle('A'.$productDetailsRow.':T'.$productDetailsRow)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('E2E6EA');
                    
                        $productDetailsRow++;
                    }
    
                    //Compute The Location Of Total Row By Adding Starting Row, Member Title Row (1) And Total Transaction Row
                    $totalStatsRow = $startingRow + $totalTransactionRow + 1;
    
                    $total_issuance_quantity = TransactionReport::where('member_id', $member_id)
                    ->where('year',$year)
                    ->where('month',$month)
                    ->where('status',1)
                    ->sum('issuance_quantity');
    
                    $total_issuance_amount = TransactionReport::where('member_id', $member_id)
                    ->where('year',$year)
                    ->where('month',$month)
                    ->where('status',1)
                    ->sum('issuance_amount');
    
                    $total_receipt_quantity = TransactionReport::where('member_id', $member_id)
                    ->where('year',$year)
                    ->where('month',$month)
                    ->where('status',1)
                    ->sum('receipt_quantity');
    
                    $total_receipt_amount = TransactionReport::where('member_id', $member_id)
                    ->where('year',$year)
                    ->where('month',$month)
                    ->where('status',1)
                    ->sum('receipt_amount');
    
                    //Show Total Record
                    $spreadsheet->getActiveSheet()->mergeCells('A'.$totalStatsRow.':K'.$totalStatsRow);
            
                    $spreadsheet->getActiveSheet()->getCell('A'.$totalStatsRow)
                    ->setValue('TOTAL');
                    
                    //Set Number Format
                    $spreadsheet->getActiveSheet()->getStyle('L'.$totalStatsRow)
                    ->getNumberFormat()
                    ->setFormatCode('#,##0');
                        
                    $spreadsheet->getActiveSheet()->getCell('L'.$totalStatsRow)
                    ->setValue("{$total_receipt_quantity}");
                    
                    //Set Number Format
                    $spreadsheet->getActiveSheet()->getStyle('M'.$totalStatsRow)
                    ->getNumberFormat()
                    ->setFormatCode('#,##0');
                    
                    $spreadsheet->getActiveSheet()->getCell('M'.$totalStatsRow)
                    ->setValue("{$total_issuance_quantity}");
                    
                    //Set Number Format
                    $spreadsheet->getActiveSheet()->getStyle('N'.$totalStatsRow)
                    ->getNumberFormat()
                    ->setFormatCode('#,##0.00');
                        
                    $spreadsheet->getActiveSheet()->getCell('N'.$totalStatsRow)
                    ->setValue("{$total_receipt_amount}");
                    
                    //Set Number Format
                    $spreadsheet->getActiveSheet()->getStyle('O'.$totalStatsRow)
                    ->getNumberFormat()
                    ->setFormatCode('#,##0.00');
                    
                    $spreadsheet->getActiveSheet()->getCell('O'.$totalStatsRow)
                    ->setValue("{$total_issuance_amount}");
                    
                    $spreadsheet->getActiveSheet()->mergeCells('P'.$totalStatsRow.':S'.$totalStatsRow);
                        
                    $style = array(
                        'alignment' => array('horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER,),
                        'font'  => array('bold'  => true, 'color' => array('rgb' => '000000'), 'size'  => 8, 'name'  => 'Calibri'),
                        'borders' => array('allborders' => array('style' => Border::BORDER_THIN,  'color' => array('rgb' => '000000')))
                    );
                    
                    $style2 = array(
                        'alignment' => array('horizontal' => Alignment::HORIZONTAL_RIGHT, 'vertical' => Alignment::VERTICAL_CENTER,),
                        'font'  => array('bold'  => true, 'color' => array('rgb' => '000000'), 'size'  => 8, 'name'  => 'Calibri'),
                        'borders' => array('allborders' => array('style' => Border::BORDER_THIN,  'color' => array('rgb' => '000000')))
                    );
    
                    $spreadsheet->getActiveSheet()->getStyle('A'.$totalStatsRow.':G'.$totalStatsRow)->applyFromArray($style);
                    $spreadsheet->getActiveSheet()->getStyle('H'.$totalStatsRow.':T'.$totalStatsRow)->applyFromArray($style2);
                    $spreadsheet->getActiveSheet()->getStyle('A'.$totalStatsRow.':T'.$totalStatsRow)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('B8C0CB');
                } else {
                    //Show No Record
                    $spreadsheet->getActiveSheet()->mergeCells('A'.$productDetailsRow.':T'.$productDetailsRow);
                
                    $spreadsheet->getActiveSheet()->getCell('A'.$productDetailsRow)
                    ->setValue('NO TRANSACTION FOUND');
                                    
                    $style = array(
                        'alignment' => array('horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER,),
                        'font'  => array('bold'  => true, 'color' => array('rgb' => '000000'), 'size'  => 8, 'name'  => 'Calibri'),
                        'borders' => array('allborders' => array('style' => Border::BORDER_THIN,  'color' => array('rgb' => '000000')))
                    );
                
                    $spreadsheet->getActiveSheet()->getStyle('A'.$productDetailsRow.':T'.$productDetailsRow)->applyFromArray($style);
                    $spreadsheet->getActiveSheet()->getStyle('A'.$productDetailsRow.':T'.$productDetailsRow)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('E2E6EA');
                }
                //Compute The Next Starting Row By Adding Starting Row, Total Transaction Row, Member Title Row (1), Total Stats Row (1) and Space Row(1)
                $startingRow = $startingRow + $totalTransactionRow + 3;
            }
            //Show Cancelled Tramsactions
            $spreadsheet->getActiveSheet()
                        ->mergeCells('A'.$startingRow.':T'.$startingRow);
	
            $spreadsheet->getActiveSheet()
			 			->getCell('A'.$startingRow)
						->setValue("Cancelled Items");
				
			$style = array(
				'alignment' => array('horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER,),
                'font'  => array('bold'  => true, 'color' => array('rgb' => 'FFFFFF'), 'size'  => 11, 'name'  => 'Calibri'),
				'borders' => array('allborders' => array('style' => Border::BORDER_THIN,  'color' => array('rgb' => '000000')))
            );

			$spreadsheet->getActiveSheet()->getStyle('A'.$startingRow.':T'.$startingRow)->applyFromArray($style);
			$spreadsheet->getActiveSheet()->getStyle('A'.$startingRow.':T'.$startingRow)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('6D466B');
            
            //Cancelled Transaction List Query
            $transaction_reports = TransactionReport::where('year',$year)
            ->where('month',$month)
            ->where('status',0)->get();

			$totalTransactionRow = $transaction_reports->count();
				
			$productDetailsRow = $startingRow + 1;
            
			if ($totalTransactionRow > 0) {
				foreach($transaction_reports as $transactionReportDetails) {
					//Product Details
                    $transactionReportId = $transactionReportDetails->id;
					$memberId = $transactionReportDetails->member_id;
					$beneficiaryId = $transactionReportDetails->beneficiary_id;
					$contributionId = $transactionReportDetails->contribution_id;
					$allocationId = $transactionReportDetails->allocation_id;
					$month = $transactionReportDetails->month;
					$year = $transactionReportDetails->year;
					$contributionNo = $transactionReportDetails->contribution_no;
					$allocationNo = $transactionReportDetails->allocation_no;
					$productCode = $transactionReportDetails->product_code;
					$productName = $transactionReportDetails->product_name;
					$lotNo = $transactionReportDetails->lot_no;
					$openingBalanceQuantity = $transactionReportDetails->opening_balance_quantity;
					$transactionType = $transactionReportDetails->transaction_type;
					$quantity = $transactionReportDetails->quantity;
					$unitCost = $transactionReportDetails->unit_cost;
					$receiptQuantity = $transactionReportDetails->receipt_quantity;
					$issuanceQuantity = $transactionReportDetails->issuance_quantity;
					$receiptAmount = $transactionReportDetails->receipt_amount;
					$issuanceAmount = $transactionReportDetails->issuance_amount;
					$mfgDate = $transactionReportDetails->mfg_date;
					$expiryDate = $transactionReportDetails->expiry_date;
					$createDate = $transactionReportDetails->create_at;

                    //Member Details
                    $member = Member::where('id',$memberId)->first();
                    $memberName = $member->member_name;
                    
                    $beneficiaryName = "";

                    //Beneficiary Details
                    $beneficiary = Beneficiary::where('id',$beneficiaryId)->get();
                    foreach($beneficiary as $item) {
                        $beneficiaryName = $item->name;
                    }
    
                    //Set Beneficiary Name To Empty If Benficiary Id Is Zero
                    if ($beneficiaryId == 0) {
                        $beneficiaryName = "-";
                    }
		
                    $dndNo = "";
                    $didrfNo = "";

                    //Get Contribution Details
                    $contribution = Contribution::where('id', $contributionId)->get();
                    foreach($contribution as $item){
                        $dndNo = $item->dnd_no;
                        $didrfNo = $item->didrf_no;
                    }
					
                    $dnaNo = "";
                    $dodrfNo = "";

                    //Get Allocation Details
                    $allocation = Allocation::where('id', $allocationId)->get();

                    foreach($allocation as $item){
                        $dnaNo = $item->dna_no;
                        $dodrfNo = $item->dodrf_no;
                    }
															
					//Identify Reference By Transaction Type
					if ($transactionType == 'EXP') {
						$reference = $allocationNo."/DODRF: ".$dodrfNo."/DNA: ".$dnaNo;
					} else if ($transactionType == 'IMP') {
						$reference = $contributionNo."/DIDRF: ".$didrfNo."/DNA: ".$dndNo;
					} else if ($transactionType == 'ADJI') {
						$reference = 'INVENTORY ADJ-';
					} else {
						$reference = 'INVENTORY ADJ+';
					}
						
					$spreadsheet->getActiveSheet()
						->getCell('A'.$productDetailsRow)
						->setValue("{$productCode}");
				
					$spreadsheet->getActiveSheet()
						->mergeCells('B'.$productDetailsRow.':C'.$productDetailsRow);
					
					$spreadsheet->getActiveSheet()
						->getCell('B'.$productDetailsRow)
						->setValue("{$productName}");
						
					$spreadsheet->getActiveSheet()
						->getCell('D'.$productDetailsRow)
						->setValue("{$memberName}");
									
					$spreadsheet->getActiveSheet()
						->mergeCells('E'.$productDetailsRow.':F'.$productDetailsRow);
					
					$spreadsheet->getActiveSheet()
						->getCell('E'.$productDetailsRow)
						->setValue("{$beneficiaryName}");
						
					$spreadsheet->getActiveSheet()
						->getCell('G'.$productDetailsRow)
						->setValue("{$lotNo}");
						
					//Set Number Format
					$spreadsheet->getActiveSheet()
						->getStyle('H'.$productDetailsRow)
						->getNumberFormat()
						->setFormatCode('#,##0');
									
					$spreadsheet->getActiveSheet()
						->getCell('H'.$productDetailsRow)
						->setValue("{$openingBalanceQuantity}");
						
					$spreadsheet->getActiveSheet()
						->getCell('I'.$productDetailsRow)
						->setValue("{$transactionType}");
					
					//Set Number Format
					$spreadsheet->getActiveSheet()
						->getStyle('J'.$productDetailsRow)
						->getNumberFormat()
						->setFormatCode('#,##0');
									
					$spreadsheet->getActiveSheet()
						->getCell('J'.$productDetailsRow)
						->setValue("{$quantity}");
					
					//Set Number Format
					$spreadsheet->getActiveSheet()
						->getStyle('K'.$productDetailsRow)
						->getNumberFormat()
						->setFormatCode('#,##0.00');
						
					$spreadsheet->getActiveSheet()
						->getCell('K'.$productDetailsRow)
						->setValue("{$unitCost}");
						
					//Set Number Format
					$spreadsheet->getActiveSheet()
						->getStyle('L'.$productDetailsRow)
						->getNumberFormat()
						->setFormatCode('#,##0');
									
					$spreadsheet->getActiveSheet()
						->getCell('L'.$productDetailsRow)
						->setValue("{$receiptQuantity}");
						
					//Set Number Format
					$spreadsheet->getActiveSheet()
						->getStyle('M'.$productDetailsRow)
						->getNumberFormat()
                        ->setFormatCode('#,##0');
									
					$spreadsheet->getActiveSheet()
						->getCell('M'.$productDetailsRow)
						->setValue("{$issuanceQuantity}");
						
					//Set Number Format
					$spreadsheet->getActiveSheet()
						->getStyle('N'.$productDetailsRow)
						->getNumberFormat()
						->setFormatCode('#,##0.00');
									
					$spreadsheet->getActiveSheet()
						->getCell('N'.$productDetailsRow)
						->setValue("{$receiptAmount}");
						
					//Set Number Format
					$spreadsheet->getActiveSheet()
						->getStyle('O'.$productDetailsRow)
						->getNumberFormat()
						->setFormatCode('#,##0.00');
									
					$spreadsheet->getActiveSheet()
						->getCell('O'.$productDetailsRow)
						->setValue("{$issuanceAmount}");
						
					$spreadsheet->getActiveSheet()
						->getCell('P'.$productDetailsRow)
						->setValue("{$reference}");
						
					$spreadsheet->getActiveSheet()
						->getCell('Q'.$productDetailsRow)
						->setValue("{$createDate}");
						
					$spreadsheet->getActiveSheet()
						->getCell('R'.$productDetailsRow)
						->setValue("{$mfgDate}");
						
					$spreadsheet->getActiveSheet()
						->getCell('S'.$productDetailsRow)
						->setValue("{$expiryDate}");
						
					$style = array(
						'alignment' => array('horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER,),
						'font'  => array('color' => array('rgb' => '000000'), 'size'  => 8, 'name'  => 'Calibri'),
						'borders' => array('allborders' => array('style' => Border::BORDER_THIN,  'color' => array('rgb' => '000000')))
					);
						
					$style2 = array(
						'alignment' => array('horizontal' => Alignment::HORIZONTAL_RIGHT, 'vertical' => Alignment::VERTICAL_CENTER,),
						'font'  => array('color' => array('rgb' => '000000'), 'size'  => 8, 'name'  => 'Calibri'),
						'borders' => array('allborders' => array('style' => Border::BORDER_THIN,  'color' => array('rgb' => '000000')))
					);

					$spreadsheet->getActiveSheet()->getStyle('A'.$productDetailsRow.':G'.$productDetailsRow)->applyFromArray($style);
					$spreadsheet->getActiveSheet()->getStyle('H'.$productDetailsRow.':O'.$productDetailsRow)->applyFromArray($style2);
					$spreadsheet->getActiveSheet()->getStyle('P'.$productDetailsRow.':S'.$productDetailsRow)->applyFromArray($style);
					$spreadsheet->getActiveSheet()->getStyle('A'.$productDetailsRow.':S'.$productDetailsRow)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('E2E6EA');
					
					$productDetailsRow++;
				}
					
				//Compute The Location Of Total Row By Adding Starting Row, Member Title Row (1) And Total Transaction Row
				$totalStatsRow = $startingRow + $totalTransactionRow + 1;
                
                $total_issuance_quantity = TransactionReport::where('member_id', $member_id)
                
                ->where('year',$year)
                ->where('month',$month)
                ->where('status',0)
                ->sum('issuance_quantity');

                $total_issuance_amount = TransactionReport::where('member_id', $member_id)
                ->where('year',$year)
                ->where('month',$month)
                ->where('status',0)
                ->sum('issuance_amount');

                $total_receipt_quantity = TransactionReport::where('member_id', $member_id)
                ->where('year',$year)
                ->where('month',$month)
                ->where('status',0)
                ->sum('receipt_quantity');

                $total_receipt_amount = TransactionReport::where('member_id', $member_id)
                ->where('year',$year)
                ->where('month',$month)
                ->where('status',0)
                ->sum('receipt_amount');
		
				//Show Total Record
				$spreadsheet->getActiveSheet()
				    ->mergeCells('A'.$totalStatsRow.':K'.$totalStatsRow);
				
				$spreadsheet->getActiveSheet()
				    ->getCell('A'.$totalStatsRow)
				    ->setValue('TOTAL');
					
				//Set Number Format
				$spreadsheet->getActiveSheet()
				    ->getStyle('L'.$totalStatsRow)
					->getNumberFormat()
					->setFormatCode('#,##0');
									
				$spreadsheet->getActiveSheet()
				    ->getCell('L'.$totalStatsRow)
					->setValue("{$total_receipt_quantity}");
					
				//Set Number Format
				$spreadsheet->getActiveSheet()
				    ->getStyle('M'.$totalStatsRow)
					->getNumberFormat()
					->setFormatCode('#,##0');
								
				$spreadsheet->getActiveSheet()
				    ->getCell('M'.$totalStatsRow)
					->setValue("{$total_issuance_quantity}");
					
				//Set Number Format
				$spreadsheet->getActiveSheet()
				    ->getStyle('N'.$totalStatsRow)
					->getNumberFormat()
					->setFormatCode('#,##0.00');
									
				$spreadsheet->getActiveSheet()
				    ->getCell('N'.$totalStatsRow)
					->setValue("{$total_receipt_amount}");
					
				//Set Number Format
				$spreadsheet->getActiveSheet()
				    ->getStyle('O'.$totalStatsRow)
					->getNumberFormat()
					->setFormatCode('#,##0.00');
								
				$spreadsheet->getActiveSheet()
				    ->getCell('O'.$totalStatsRow)
					->setValue("{$total_issuance_amount}");
					
				$spreadsheet->getActiveSheet()
				    ->mergeCells('P'.$totalStatsRow.':S'.$totalStatsRow);
									
				$style = array(
					'alignment' => array('horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER,),
					'font'  => array('bold'  => true, 'color' => array('rgb' => '000000'), 'size'  => 8, 'name'  => 'Calibri'),
					'borders' => array('allborders' => array('style' => Border::BORDER_THIN,  'color' => array('rgb' => '000000')))
				);
					
				$style2 = array(
					'alignment' => array('horizontal' => Alignment::HORIZONTAL_RIGHT, 'vertical' => Alignment::VERTICAL_CENTER,),
					'font'  => array('bold'  => true, 'color' => array('rgb' => '000000'), 'size'  => 8, 'name'  => 'Calibri'),
					'borders' => array('allborders' => array('style' => Border::BORDER_THIN,  'color' => array('rgb' => '000000')))
				);

				$spreadsheet->getActiveSheet()->getStyle('A'.$totalStatsRow.':G'.$totalStatsRow)->applyFromArray($style);
				$spreadsheet->getActiveSheet()->getStyle('H'.$totalStatsRow.':T'.$totalStatsRow)->applyFromArray($style2);
				$spreadsheet->getActiveSheet()->getStyle('A'.$totalStatsRow.':T'.$totalStatsRow)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('B8C0CB');	
            } else {
				//Show No Record of cancelled transaction report
				$spreadsheet->getActiveSheet()
                            ->mergeCells('A'.$productDetailsRow.':T'.$productDetailsRow);
				
				$spreadsheet->getActiveSheet()
				            ->getCell('A'.$productDetailsRow)
							->setValue('NO ITEM FOUND');
									
				$style = array(
					'alignment' => array('horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER,),
					'font'  => array('bold'  => true, 'color' => array('rgb' => '000000'), 'size'  => 8, 'name'  => 'Calibri'),
					'borders' => array('allborders' => array('style' => Border::BORDER_THIN,  'color' => array('rgb' => '000000')))
				);

				$spreadsheet->getActiveSheet()->getStyle('A'.$productDetailsRow.':S'.$productDetailsRow)->applyFromArray($style);
				$spreadsheet->getActiveSheet()->getStyle('A'.$productDetailsRow.':S'.$productDetailsRow)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('E2E6EA');
            }      
        } else {
            //Show No Record if there are no members
            $spreadsheet->getActiveSheet()->mergeCells('A9:S9');
    
            $spreadsheet->getActiveSheet()->getCell('A9')
                        ->setValue('NO MEMBER FOUND');
                        
            $style = array(
                'alignment' => array('horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER,),
                'font'  => array('bold'  => true, 'color' => array('rgb' => '000000'), 'size'  => 8, 'name'  => 'Calibri'),
                'borders' => array('allborders' => array('style' => Border::BORDER_THIN,  'color' => array('rgb' => '000000')))
            );

            $spreadsheet->getActiveSheet()->getStyle("A9:S9")->applyFromArray($style);
            $spreadsheet->getActiveSheet()->getStyle('A9:S9')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('B8C0CB');
        }
        
        //Rename Worksheet
        $reportMonthYearWorksheet = date_format(date_create($reportMonthYear),"M Y");
        $spreadsheet->getActiveSheet()->setTitle("Txn Report - {$reportMonthYearWorksheet}");
        // End of Transaction Report

        //START DESTRUCTION REPORT
        //Product Destruction Report Sheet
        $spreadsheet->createSheet();
        $spreadsheet->setActiveSheetIndex(1);
        $spreadsheet->getActiveSheet()->freezePane('H9');
		
		//Product Destruction Report Column Properties)
		$spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(18);
		$spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(12);
		$spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(10);
		$spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(15);
		$spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(12);
		$spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(10);
		$spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(12);
		$spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(10);
		$spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(10);
		$spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(10);
		$spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(15);
		$spreadsheet->getActiveSheet()->getColumnDimension('L')->setWidth(15);
		$spreadsheet->getActiveSheet()->getColumnDimension('M')->setWidth(25);
		$spreadsheet->getActiveSheet()->getColumnDimension('N')->setWidth(15);
		$spreadsheet->getActiveSheet()->getColumnDimension('O')->setWidth(15);
		
		//Product Destruction Report Header
		//Header Title
		$spreadsheet->getActiveSheet()
					->mergeCells('A1:B1');
					
		$spreadsheet->getActiveSheet()
					->getCell('A1')
					->setValue('PHAP Cares Inventory Report : ');
					
		$spreadsheet->getActiveSheet()
					->mergeCells('C1:D1');
		
		//Get Report Month And Year
		$reportMonthYear = $year."-".$month;
		$reportMonthYearHeader = date_format(date_create($reportMonthYear),"F Y");
		
		$spreadsheet->getActiveSheet()
					->getCell('C1')
					->setValue("{$reportMonthYearHeader}");
					
		//Header Title Style Properties
		$style = array(
			'alignment' => array('horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER,),
			'font'  => array('bold'  => true, 'color' => array('rgb' => 'FFFFFF'), 'size'  => 10, 'name'  => 'Calibri'),
			'borders' => array('allborders' => array('style' => Border::BORDER_THIN,  'color' => array('rgb' => 'A33B20')))
		);
		
		$style2 = array(
			'alignment' => array('horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER,),
			'font'  => array('bold'  => true, 'color' => array('rgb' => 'E5C9C2'), 'size'  => 10, 'name'  => 'Calibri'),
			'borders' => array('allborders' => array('style' => Border::BORDER_THIN,  'color' => array('rgb' => 'A33B20')))
		);

		$spreadsheet->getActiveSheet()->getStyle("A1:O1")->applyFromArray($style);
		$spreadsheet->getActiveSheet()->getStyle("C1")->applyFromArray($style2);
		
		$spreadsheet->getActiveSheet()->getStyle('A1:O1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('A33B20');
		
		//Header Details
		$spreadsheet->getActiveSheet()
					->getCell('A2')
					->setValue("Product Destruction");
		
		$spreadsheet->getActiveSheet()
		->mergeCells('B2:C2');
        
        if (strtotime($reportMonthYear) > strtotime($currentDate)) {
            //No Generated Transaction As Of This Time
            $reportDateFrom = "NA";
            $reportDateTo = "NA";
        } else if (strtotime($currentDate) > strtotime($reportMonthYear)) {
            //Reports From Past Months
            //Set Report Date From : First Day Of The Month
            $reportDateFrom = date_create($year."-".$month."-01");
            $reportDateFrom = date_format($reportDateFrom,"m-d-Y");
            
            //Set Report Date To : Last Day Of The Month
            $reportDateTo = date_create($year."-".$month."-01");
            $reportDateTo = date_format($reportDateTo,"m-t-Y");
        } else {
            //Current Report Month And Year
            //Set Report Date From : First Day Of The Month
            $reportDateFrom = date_create($year."-".$month."-01");
            $reportDateFrom = date_format($reportDateFrom,"m-d-Y");
            
            //Set Report Date To : Current Day Of The Month
            $reportDateTo = date("m-d-Y");
        }		
        
        $spreadsheet->getActiveSheet()
                    ->getCell('B2')
                    ->setValue("From : {$reportDateFrom}");
        
        $spreadsheet->getActiveSheet()
                    ->mergeCells('D2:E2');
        
        $spreadsheet->getActiveSheet()
                    ->getCell('D2')
                    ->setValue("To : {$reportDateTo}");
        
        //Set Generated Date		
        $generatedDate = date("m-d-Y h:i:s A");
        
        $spreadsheet->getActiveSheet()
                    ->mergeCells('A3:C3');
        
        $spreadsheet->getActiveSheet()
                    ->getCell('A3')
                    ->setValue("Product Destruction Generated Date/Time : ");
        
        $spreadsheet->getActiveSheet()
                    ->mergeCells('D3:E3');
                    
        $spreadsheet->getActiveSheet()
                    ->getCell('D3')
                    ->setValue("{$generatedDate}");
                    
        //Header Details Style Properties
        $style = array(
            'alignment' => array('horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER,),
            'font'  => array('bold'  => true, 'color' => array('rgb' => 'FFFFFF'), 'size'  => 10, 'name'  => 'Calibri'),
            'borders' => array('allborders' => array('style' => Border::BORDER_THIN,  'color' => array('rgb' => 'B35E48')))
        );
        
        $style2 = array(
            'alignment' => array('horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER,),
            'font'  => array('bold'  => true, 'color' => array('rgb' => 'E5C9C2'), 'size'  => 10, 'name'  => 'Calibri'),
            'borders' => array('allborders' => array('style' => Border::BORDER_THIN,  'color' => array('rgb' => 'B35E48')))
        );

        $spreadsheet->getActiveSheet()->getStyle("A2:O2")->applyFromArray($style);
        $spreadsheet->getActiveSheet()->getStyle("B2")->applyFromArray($style2);
        $spreadsheet->getActiveSheet()->getStyle("D2")->applyFromArray($style2);
        
        $spreadsheet->getActiveSheet()->getStyle("A3:O3")->applyFromArray($style);
        $spreadsheet->getActiveSheet()->getStyle("D3")->applyFromArray($style2);
        
        $spreadsheet->getActiveSheet()->getStyle('A2:O2')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('B35E48');
        $spreadsheet->getActiveSheet()->getStyle('A3:O3')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('B35E48');
        
        //Destruction Summary
        //Summary Title
        $spreadsheet->getActiveSheet()
                    ->getCell('A5')
                    ->setValue('Destruction Summary');
                    
        //Summary Title Style Properties
        $style = array(
            'alignment' => array('horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER,),
            'font'  => array('bold'  => true, 'color' => array('rgb' => 'FFFFFF'), 'size'  => 10, 'name'  => 'Calibri'),
            'borders' => array('allborders' => array('style' => Border::BORDER_THIN,  'color' => array('rgb' => '5096A0')))
        );

        $spreadsheet->getActiveSheet()->getStyle("A5:O5")->applyFromArray($style);
        $spreadsheet->getActiveSheet()->getStyle('A5:O5')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('5096A0');

        //Summary Details
		//Compute Destruction Quantity For The Month
        $total_destruction_quantity = TransactionReport::where('month',$month)
        ->where('year',$year)
        ->where('status',1)
        ->sum('destruction_quantity');

        $total_destruction_amount = TransactionReport::where('month',$month)
        ->where('year',$year)
        ->where('status',1)
        ->sum('destruction_amount');

        //Destruction Quantity
		$spreadsheet->getActiveSheet()
        ->getCell('A6')
        ->setValue('Destruction Qty : ');

        //Set Number Format
        $spreadsheet->getActiveSheet()
                ->getStyle('B6')
                ->getNumberFormat()
                ->setFormatCode('#,##0');
                
        $spreadsheet->getActiveSheet()
                ->getCell('B6')
                ->setValue("{$total_destruction_quantity}");

        //Destruction Amount
        $spreadsheet->getActiveSheet()
                ->getCell('A7')
                ->setValue('Destruction Amt : ');

        //Set Number Format
        $spreadsheet->getActiveSheet()
                ->getStyle('B7')
                ->getNumberFormat()
                ->setFormatCode('#,##0');
                
        $spreadsheet->getActiveSheet()
                ->getCell('B7')
                ->setValue("{$total_destruction_amount}");

        //Summary Details Style Properties
        $style = array(
        'alignment' => array('horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER,),
        'font'  => array('bold'  => true, 'color' => array('rgb' => 'FFFFFF'), 'size'  => 10, 'name'  => 'Calibri'),
        'borders' => array('allborders' => array('style' => Border::BORDER_THIN,  'color' => array('rgb' => '85BCC5')))
        );

        $style2 = array(
        'alignment' => array('horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER,),
        'font'  => array('bold'  => true, 'color' => array('rgb' => '253D5B'), 'size'  => 10, 'name'  => 'Calibri'),
        'borders' => array('allborders' => array('style' => Border::BORDER_THIN,  'color' => array('rgb' => '85BCC5')))
        );

        $spreadsheet->getActiveSheet()->getStyle("A6:O6")->applyFromArray($style);
        $spreadsheet->getActiveSheet()->getStyle("B6")->applyFromArray($style2);
        $spreadsheet->getActiveSheet()->getStyle("E6")->applyFromArray($style2);

        $spreadsheet->getActiveSheet()->getStyle("A7:O7")->applyFromArray($style);
        $spreadsheet->getActiveSheet()->getStyle("B7")->applyFromArray($style2);
        $spreadsheet->getActiveSheet()->getStyle("E7")->applyFromArray($style2);

        $spreadsheet->getActiveSheet()->getStyle('A6:O6')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('85BCC5');
        $spreadsheet->getActiveSheet()->getStyle('A7:O7')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('85BCC5');

        //Product Details Header
		$spreadsheet->getActiveSheet()
					->getCell('A8')
					->setValue('Product Code');
		
		$spreadsheet->getActiveSheet()
					->mergeCells('B8:C8');
	
		$spreadsheet->getActiveSheet()
					->getCell('B8')
					->setValue('Product Name');
		
		$spreadsheet->getActiveSheet()
					->getCell('D8')
					->setValue('Principal');
					
		$spreadsheet->getActiveSheet()
					->mergeCells('E8:F8');
	
		$spreadsheet->getActiveSheet()
					->getCell('E8')
					->setValue('Beneficiary');
		
		$spreadsheet->getActiveSheet()
					->getCell('G8')
					->setValue('Lot No');
		
		$spreadsheet->getActiveSheet()
					->getCell('H8')
					->setValue('Txn Type');
		
		$spreadsheet->getActiveSheet()
					->getCell('I8')
					->setValue('Qty');
		
		$spreadsheet->getActiveSheet()
					->getCell('J8')
					->setValue('Trade Price');
		
		$spreadsheet->getActiveSheet()
					->getCell('K8')
					->setValue('Destruction');
					
		$spreadsheet->getActiveSheet()
					->getCell('L8')
					->setValue('Destruction Amt');
		
		$spreadsheet->getActiveSheet()
					->getCell('M8')
					->setValue('Reference');
		
		$spreadsheet->getActiveSheet()
					->getCell('N8')
					->setValue('Txn Date');
		
		$spreadsheet->getActiveSheet()
					->getCell('O8')
					->setValue('Exp Date');
					
		//Product Details Style Properties
		$style = array(
			'alignment' => array('horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER,),
			'font'  => array('bold'  => true, 'color' => array('rgb' => 'FFFFFF'), 'size'  => 10, 'name'  => 'Calibri'),
			'borders' => array('allborders' => array('style' => Border::BORDER_THIN,  'color' => array('rgb' => 'FFFFFF')))
		);

		$spreadsheet->getActiveSheet()->getStyle("A8:O8")->applyFromArray($style);
		$spreadsheet->getActiveSheet()->getStyle('A8:O8')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('24323D');

        //Product Details Header
		$spreadsheet->getActiveSheet()
        ->getCell('A8')
        ->setValue('Product Code');

        $spreadsheet->getActiveSheet()
                ->mergeCells('B8:C8');

        $spreadsheet->getActiveSheet()
                ->getCell('B8')
                ->setValue('Product Name');

        $spreadsheet->getActiveSheet()
                ->getCell('D8')
                ->setValue('Principal');
                
        $spreadsheet->getActiveSheet()
                ->mergeCells('E8:F8');

        $spreadsheet->getActiveSheet()
                ->getCell('E8')
                ->setValue('Beneficiary');

        $spreadsheet->getActiveSheet()
                ->getCell('G8')
                ->setValue('Lot No');

        $spreadsheet->getActiveSheet()
                ->getCell('H8')
                ->setValue('Txn Type');

        $spreadsheet->getActiveSheet()
                ->getCell('I8')
                ->setValue('Qty');

        $spreadsheet->getActiveSheet()
                ->getCell('J8')
                ->setValue('Trade Price');

        $spreadsheet->getActiveSheet()
                ->getCell('K8')
                ->setValue('Destruction');
                
        $spreadsheet->getActiveSheet()
                ->getCell('L8')
                ->setValue('Destruction Amt');

        $spreadsheet->getActiveSheet()
                ->getCell('M8')
                ->setValue('Reference');

        $spreadsheet->getActiveSheet()
                ->getCell('N8')
                ->setValue('Txn Date');

        $spreadsheet->getActiveSheet()
                ->getCell('O8')
                ->setValue('Exp Date');
                
        //Product Details Style Properties
        $style = array(
        'alignment' => array('horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER,),
        'font'  => array('bold'  => true, 'color' => array('rgb' => 'FFFFFF'), 'size'  => 10, 'name'  => 'Calibri'),
        'borders' => array('allborders' => array('style' => Border::BORDER_THIN,  'color' => array('rgb' => 'FFFFFF')))
        );

        $spreadsheet->getActiveSheet()->getStyle("A8:O8")->applyFromArray($style);
        $spreadsheet->getActiveSheet()->getStyle('A8:O8')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('24323D');

        //Product List Starting Row : A9
		$startingRow = 9;
        
        if($selected_member_id != 0) {
            $members = Member::where('id',$selected_member_id)->select('id','member_name')->get();
        } else {
            $members = DB::table('members')->select('id','member_name')->get();
        }

        $memberCount = $members->count();

        if ($memberCount > 0) {
			foreach($members as $memberDetails) {
				$member_id =  $memberDetails->id;
				$memberName =  $memberDetails->member_name;
				
				//Show Member Name
				$spreadsheet->getActiveSheet()
							->mergeCells('A'.$startingRow.':O'.$startingRow);
	
				$spreadsheet->getActiveSheet()
							->getCell('A'.$startingRow)
							->setValue("{$memberName}");
				
				$style = array(
					'alignment' => array('horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER,),
					'font'  => array('bold'  => true, 'color' => array('rgb' => 'FFFFFF'), 'size'  => 11, 'name'  => 'Calibri'),
					'borders' => array('allborders' => array('style' => Border::BORDER_THIN,  'color' => array('rgb' => '000000')))
				);

				$spreadsheet->getActiveSheet()->getStyle('A'.$startingRow.':O'.$startingRow)->applyFromArray($style);
				$spreadsheet->getActiveSheet()->getStyle('A'.$startingRow.':O'.$startingRow)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('63768D');
				
				//Transaction List Query
                $transaction_reports = TransactionReport::where('member_id',$member_id)
                ->where('year',$year)
                ->where('month',$month)
                ->where('status',1)
                ->where('transaction_type','DES')
                ->get();
    
                $totalTransactionRow = $transaction_reports->count();
				
				$productDetailsRow = $startingRow + 1;
				if ($totalTransactionRow > 0) {
					foreach($transaction_reports as $transactionReportDetails) {
						//Product Details
						$transactionReportId = $transactionReportDetails->id;
						$memberId = $transactionReportDetails->member_id;
						$beneficiaryId = $transactionReportDetails->beneficiary_id;
						$destructionId = $transactionReportDetails->destruction_id;
						$month = $transactionReportDetails->month;
						$year = $transactionReportDetails->year;
						$destructionNo = $transactionReportDetails->destruction_no;
						$productCode = $transactionReportDetails->product_code;
						$productName = $transactionReportDetails->product_name;
						$lotNo = $transactionReportDetails->lot_no;
						$transactionType = $transactionReportDetails->transaction_type;
						$quantity = $transactionReportDetails->quantity;
						$unitCost = $transactionReportDetails->unit_cost;
						$destructionQuantity = $transactionReportDetails->destruction_quantity;
						$destructionAmount = $transactionReportDetails->destruction_amount;
						$expiryDate = $transactionReportDetails->expiry_date;
						$createDate = $transactionReportDetails->created_at;
                        
                        $beneficiaryName = "";

                        //Beneficiary Details
                        $beneficiary = Beneficiary::where('id',$beneficiaryId)->get();
                        foreach($beneficiary as $item) {
                            $beneficiaryName = $item->name;
                        }

                        //Set Beneficiary Name To Empty If Benficiary Id Is Zero
                        if ($beneficiaryId == 0) {
                            $beneficiaryName = "-";
                        }
						
                        //Get Destruction Details
                        $destruction = Destruction::where('id', $destructionId)->first();
                        $pdrfNo = $destruction->pdrf_no;
					
						//Identify Reference By Transaction Type
						$reference = $destructionNo."/PDRF: ".$pdrfNo;
						
						$spreadsheet->getActiveSheet()
									->getCell('A'.$productDetailsRow)
									->setValue("{$productCode}");
				
						$spreadsheet->getActiveSheet()
									->mergeCells('B'.$productDetailsRow.':C'.$productDetailsRow);
					
						$spreadsheet->getActiveSheet()
									->getCell('B'.$productDetailsRow)
									->setValue("{$productName}");
						
						$spreadsheet->getActiveSheet()
									->getCell('D'.$productDetailsRow)
									->setValue("{$memberName}");
									
						$spreadsheet->getActiveSheet()
									->mergeCells('E'.$productDetailsRow.':F'.$productDetailsRow);
					
						$spreadsheet->getActiveSheet()
									->getCell('E'.$productDetailsRow)
									->setValue("{$beneficiaryName}");
						
						$spreadsheet->getActiveSheet()
									->getCell('G'.$productDetailsRow)
									->setValue("{$lotNo}");

						$spreadsheet->getActiveSheet()
									->getCell('H'.$productDetailsRow)
									->setValue("{$transactionType}");
						
						//Set Number Format
						$spreadsheet->getActiveSheet()
									->getStyle('I'.$productDetailsRow)
									->getNumberFormat()
									->setFormatCode('#,##0');
									
						$spreadsheet->getActiveSheet()
									->getCell('I'.$productDetailsRow)
									->setValue("{$quantity}");
						
						//Set Number Format
						$spreadsheet->getActiveSheet()
									->getStyle('J'.$productDetailsRow)
									->getNumberFormat()
									->setFormatCode('#,##0.00');
						
						$spreadsheet->getActiveSheet()
									->getCell('J'.$productDetailsRow)
									->setValue("{$unitCost}");
						
						//Set Number Format
						$spreadsheet->getActiveSheet()
									->getStyle('K'.$productDetailsRow)
									->getNumberFormat()
									->setFormatCode('#,##0');
									
						$spreadsheet->getActiveSheet()
									->getCell('K'.$productDetailsRow)
									->setValue("{$destructionQuantity}");
						
						//Set Number Format
						$spreadsheet->getActiveSheet()
									->getStyle('L'.$productDetailsRow)
									->getNumberFormat()
									->setFormatCode('#,##0.00');
									
						$spreadsheet->getActiveSheet()
									->getCell('L'.$productDetailsRow)
									->setValue("{$destructionAmount}");
						
						$spreadsheet->getActiveSheet()
									->getCell('M'.$productDetailsRow)
									->setValue("{$reference}");
						
						$spreadsheet->getActiveSheet()
									->getCell('N'.$productDetailsRow)
									->setValue("{$createDate}");
						
						$spreadsheet->getActiveSheet()
									->getCell('O'.$productDetailsRow)
									->setValue("{$expiryDate}");
						
						$style = array(
							'alignment' => array('horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER,),
							'font'  => array('color' => array('rgb' => '000000'), 'size'  => 8, 'name'  => 'Calibri'),
							'borders' => array('allborders' => array('style' => Border::BORDER_THIN,  'color' => array('rgb' => '000000')))
						);
						
						$style2 = array(
							'alignment' => array('horizontal' => Alignment::HORIZONTAL_RIGHT, 'vertical' => Alignment::VERTICAL_CENTER,),
							'font'  => array('color' => array('rgb' => '000000'), 'size'  => 8, 'name'  => 'Calibri'),
							'borders' => array('allborders' => array('style' => Border::BORDER_THIN,  'color' => array('rgb' => '000000')))
						);

						$spreadsheet->getActiveSheet()->getStyle('A'.$productDetailsRow.':G'.$productDetailsRow)->applyFromArray($style);
						$spreadsheet->getActiveSheet()->getStyle('H'.$productDetailsRow.':L'.$productDetailsRow)->applyFromArray($style2);
						$spreadsheet->getActiveSheet()->getStyle('M'.$productDetailsRow.':O'.$productDetailsRow)->applyFromArray($style);
						$spreadsheet->getActiveSheet()->getStyle('A'.$productDetailsRow.':O'.$productDetailsRow)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('E2E6EA');
					
						$productDetailsRow++;
					}
					
					//Compute The Location Of Total Row By Adding Starting Row, Member Title Row (1) And Total Transaction Row
					$totalStatsRow = $startingRow + $totalTransactionRow + 1;

					//Compute Total Destruction Quantity
                    $total_destruction_quantity = TransactionReport::where('member_id',$memberId)
                    ->where('year',$year)
                    ->where('month',$month)
                    ->where('status',1)
                    ->sum('destruction_quantity');

                    $total_destruction_amount = TransactionReport::where('member_id',$memberId)
                    ->where('year',$year)
                    ->where('month',$month)
                    ->where('status',1)
                    ->sum('destruction_amount');

					//Show Total Record
					$spreadsheet->getActiveSheet()
								->mergeCells('A'.$totalStatsRow.':J'.$totalStatsRow);
				
					$spreadsheet->getActiveSheet()
								->getCell('A'.$totalStatsRow)
								->setValue('TOTAL');
					
					//Set Number Format
					$spreadsheet->getActiveSheet()
								->getStyle('K'.$totalStatsRow)
								->getNumberFormat()
								->setFormatCode('#,##0');
									
					$spreadsheet->getActiveSheet()
								->getCell('K'.$totalStatsRow)
								->setValue("{$total_destruction_quantity}");
					
					//Set Number Format
					$spreadsheet->getActiveSheet()
								->getStyle('L'.$totalStatsRow)
								->getNumberFormat()
								->setFormatCode('#,##0.00');
								
					$spreadsheet->getActiveSheet()
								->getCell('L'.$totalStatsRow)
								->setValue("{$total_destruction_amount}");
					
					$spreadsheet->getActiveSheet()
								->mergeCells('M'.$totalStatsRow.':O'.$totalStatsRow);
									
					$style = array(
						'alignment' => array('horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER,),
						'font'  => array('bold'  => true, 'color' => array('rgb' => '000000'), 'size'  => 8, 'name'  => 'Calibri'),
						'borders' => array('allborders' => array('style' => Border::BORDER_THIN,  'color' => array('rgb' => '000000')))
					);
					
					$style2 = array(
						'alignment' => array('horizontal' => Alignment::HORIZONTAL_RIGHT, 'vertical' => Alignment::VERTICAL_CENTER,),
						'font'  => array('bold'  => true, 'color' => array('rgb' => '000000'), 'size'  => 8, 'name'  => 'Calibri'),
						'borders' => array('allborders' => array('style' => Border::BORDER_THIN,  'color' => array('rgb' => '000000')))
					);

					$spreadsheet->getActiveSheet()->getStyle('A'.$totalStatsRow.':G'.$totalStatsRow)->applyFromArray($style);
					$spreadsheet->getActiveSheet()->getStyle('H'.$totalStatsRow.':O'.$totalStatsRow)->applyFromArray($style2);
					$spreadsheet->getActiveSheet()->getStyle('A'.$totalStatsRow.':O'.$totalStatsRow)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('B8C0CB');	
				} else {
					//Show No Record
					$spreadsheet->getActiveSheet()
								->mergeCells('A'.$productDetailsRow.':O'.$productDetailsRow);
				
					$spreadsheet->getActiveSheet()
								->getCell('A'.$productDetailsRow)
								->setValue('NO TRANSACTION FOUND');
									
					$style = array(
						'alignment' => array('horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER,),
						'font'  => array('bold'  => true, 'color' => array('rgb' => '000000'), 'size'  => 8, 'name'  => 'Calibri'),
						'borders' => array('allborders' => array('style' => Border::BORDER_THIN,  'color' => array('rgb' => '000000')))
					);

					$spreadsheet->getActiveSheet()->getStyle('A'.$productDetailsRow.':O'.$productDetailsRow)->applyFromArray($style);
					$spreadsheet->getActiveSheet()->getStyle('A'.$productDetailsRow.':O'.$productDetailsRow)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('E2E6EA');
				}
		
				//Compute The Next Starting Row By Adding Starting Row, Total Transaction Row, Member Title Row (1), Total Stats Row (1) and Space Row(1)
				$startingRow = $startingRow + $totalTransactionRow + 3;
			}
            
            // Show Cancelled 
            //Show Cancelled Destructions
            $spreadsheet->getActiveSheet()
                        ->mergeCells('A'.$startingRow.':O'.$startingRow);
	
            $spreadsheet->getActiveSheet()
			 			->getCell('A'.$startingRow)
						->setValue("Cancelled Items");
				
			$style = array(
				'alignment' => array('horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER,),
                'font'  => array('bold'  => true, 'color' => array('rgb' => 'FFFFFF'), 'size'  => 11, 'name'  => 'Calibri'),
				'borders' => array('allborders' => array('style' => Border::BORDER_THIN,  'color' => array('rgb' => '000000')))
            );

			$spreadsheet->getActiveSheet()->getStyle('A'.$startingRow.':O'.$startingRow)->applyFromArray($style);
			$spreadsheet->getActiveSheet()->getStyle('A'.$startingRow.':O'.$startingRow)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('6D466B');
            
            //Transaction List Query
            $transaction_reports = TransactionReport::where('year',$year)
            ->where('month',$month)
            ->where('status',0)
            ->where('transaction_type','DES')
            ->get();

            $totalTransactionRow = $transaction_reports->count();
				
			$productDetailsRow = $startingRow + 1;
			if ($totalTransactionRow > 0) {
				foreach($transaction_reports as $transactionReportDetails) {
					//Product Details
                    $transactionReportId = $transactionReportDetails->id;
					$memberId = $transactionReportDetails->member_id;
					$beneficiaryId = $transactionReportDetails->beneficiary_id;
					$destructionId = $transactionReportDetails->destruction_id;
					$month = $transactionReportDetails->month;
					$year = $transactionReportDetails->year;
					$destructionNo = $transactionReportDetails->destruction_no;
					$productCode = $transactionReportDetails->product_code;
					$productName = $transactionReportDetails->product_name;
					$lotNo = $transactionReportDetails->lot_no;
					$transactionType = $transactionReportDetails->transaction_type;
					$quantity = $transactionReportDetails->quantity;
					$unitCost = $transactionReportDetails->unit_cost;
					$destructionQuantity = $transactionReportDetails->destruction_quantity;
					$destructionAmount = $transactionReportDetails->destruction_amount;
					$expiryDate = $transactionReportDetails->expiry_date;
					$createDate = $transactionReportDetails->create_at;

                    //Member Details
                    $member = Member::where('id'. $memberId)->first();
                    $memberName = $member->member_name;

                    $beneficiaryName = "";

                    //Beneficiary Details
                    $beneficiary = Beneficiary::where('id',$beneficiaryId)->get();
                    foreach($beneficiary as $item) {
                        $beneficiaryName = $item->name;
                    }

					//Set Beneficiary Name To Empty If Benficiary Id Is Zero
					if ($beneficiaryId == 0) {
						$beneficiaryName = "-";
					}

					//Get Destruction Details
                    $destruction = Destruction::where('id',$destruction_id)->first();
                    $pdrfNo = $destruction->pdrf_no;

					//Identify Reference By Transaction Type
					$reference = $destructionNo."/PDRF: ".$pdrfNo;
						
					$spreadsheet->getActiveSheet()
						->getCell('A'.$productDetailsRow)
						->setValue("{$productCode}");
				
					$spreadsheet->getActiveSheet()
						->mergeCells('B'.$productDetailsRow.':C'.$productDetailsRow);
					
					$spreadsheet->getActiveSheet()
						->getCell('B'.$productDetailsRow)
						->setValue("{$productName}");
						
					$spreadsheet->getActiveSheet()
						->getCell('D'.$productDetailsRow)
						->setValue("{$memberName}");
									
					$spreadsheet->getActiveSheet()
						->mergeCells('E'.$productDetailsRow.':F'.$productDetailsRow);
					
					$spreadsheet->getActiveSheet()
						->getCell('E'.$productDetailsRow)
						->setValue("{$beneficiaryName}");
						
					$spreadsheet->getActiveSheet()
						->getCell('G'.$productDetailsRow)
						->setValue("{$lotNo}");
						
					$spreadsheet->getActiveSheet()
						->getCell('H'.$productDetailsRow)
						->setValue("{$transactionType}");
						
					//Set Number Format
					$spreadsheet->getActiveSheet()
						->getStyle('I'.$productDetailsRow)
						->getNumberFormat()
						->setFormatCode('#,##0');
									
					$spreadsheet->getActiveSheet()
						->getCell('I'.$productDetailsRow)
						->setValue("{$quantity}");
						
					//Set Number Format
					$spreadsheet->getActiveSheet()
						->getStyle('J'.$productDetailsRow)
						->getNumberFormat()
						->setFormatCode('#,##0.00');
						
					$spreadsheet->getActiveSheet()
						->getCell('J'.$productDetailsRow)
						->setValue("{$unitCost}");
						
					//Set Number Format
					$spreadsheet->getActiveSheet()
						->getStyle('K'.$productDetailsRow)
						->getNumberFormat()
						->setFormatCode('#,##0');
									
					$spreadsheet->getActiveSheet()
						->getCell('K'.$productDetailsRow)
						->setValue("{$destructionQuantity}");
						
					//Set Number Format
					$spreadsheet->getActiveSheet()
						->getStyle('L'.$productDetailsRow)
						->getNumberFormat()
						->setFormatCode('#,##0.00');
									
					$spreadsheet->getActiveSheet()
						->getCell('L'.$productDetailsRow)
						->setValue("{$destructionAmount}");
						
					$spreadsheet->getActiveSheet()
						->getCell('M'.$productDetailsRow)
						->setValue("{$reference}");
						
					$spreadsheet->getActiveSheet()
						->getCell('N'.$productDetailsRow)
						->setValue("{$createDate}");
					
					$spreadsheet->getActiveSheet()
						->getCell('O'.$productDetailsRow)
						->setValue("{$expiryDate}");
						
					$style = array(
						'alignment' => array('horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER,),
						'font'  => array('color' => array('rgb' => '000000'), 'size'  => 8, 'name'  => 'Calibri'),
						'borders' => array('allborders' => array('style' => Border::BORDER_THIN,  'color' => array('rgb' => '000000')))
					);
						
					$style2 = array(
						'alignment' => array('horizontal' => Alignment::HORIZONTAL_RIGHT, 'vertical' => Alignment::VERTICAL_CENTER,),
						'font'  => array('color' => array('rgb' => '000000'), 'size'  => 8, 'name'  => 'Calibri'),
						'borders' => array('allborders' => array('style' => Border::BORDER_THIN,  'color' => array('rgb' => '000000')))
					);

					$spreadsheet->getActiveSheet()->getStyle('A'.$productDetailsRow.':G'.$productDetailsRow)->applyFromArray($style);
					$spreadsheet->getActiveSheet()->getStyle('H'.$productDetailsRow.':L'.$productDetailsRow)->applyFromArray($style2);
					$spreadsheet->getActiveSheet()->getStyle('M'.$productDetailsRow.':O'.$productDetailsRow)->applyFromArray($style);
					$spreadsheet->getActiveSheet()->getStyle('A'.$productDetailsRow.':O'.$productDetailsRow)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('E2E6EA');
					
					$productDetailsRow++;
				}
					
				//Compute The Location Of Total Row By Adding Starting Row, Member Title Row (1) And Total Transaction Row
				$totalStatsRow = $startingRow + $totalTransactionRow + 1;

                //Compute Total Destruction Quantity
                $total_destruction_quantity = TransactionReport::where('member_id',$memberId)
                ->where('year',$year)
                ->where('month',$month)
                ->where('status',0)
                ->sum('destruction_quantity');

                $total_destruction_amount = TransactionReport::where('member_id',$memberId)
                ->where('year',$year)
                ->where('month',$month)
                ->where('status',0)
                ->sum('destruction_amount');

				//Show Total Record
				$spreadsheet->getActiveSheet()
				    ->mergeCells('A'.$totalStatsRow.':J'.$totalStatsRow);
				
				$spreadsheet->getActiveSheet()
				    ->getCell('A'.$totalStatsRow)
				    ->setValue('TOTAL');
					
				//Set Number Format
				$spreadsheet->getActiveSheet()
				    ->getStyle('K'.$totalStatsRow)
					->getNumberFormat()
					->setFormatCode('#,##0');
									
				$spreadsheet->getActiveSheet()
				    ->getCell('K'.$totalStatsRow)
					->setValue("{$total_destruction_quantity}");
					
				//Set Number Format
				$spreadsheet->getActiveSheet()
				    ->getStyle('L'.$totalStatsRow)
					->getNumberFormat()
					->setFormatCode('#,##0.00');
								
				$spreadsheet->getActiveSheet()
				    ->getCell('L'.$totalStatsRow)
					->setValue("{$total_destruction_amount}");
					
				$spreadsheet->getActiveSheet()
				    ->mergeCells('M'.$totalStatsRow.':O'.$totalStatsRow);
									
				$style = array(
					'alignment' => array('horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER,),
					'font'  => array('bold'  => true, 'color' => array('rgb' => '000000'), 'size'  => 8, 'name'  => 'Calibri'),
					'borders' => array('allborders' => array('style' => Border::BORDER_THIN,  'color' => array('rgb' => '000000')))
				);
					
				$style2 = array(
					'alignment' => array('horizontal' => Alignment::HORIZONTAL_RIGHT, 'vertical' => Alignment::VERTICAL_CENTER,),
					'font'  => array('bold'  => true, 'color' => array('rgb' => '000000'), 'size'  => 8, 'name'  => 'Calibri'),
					'borders' => array('allborders' => array('style' => Border::BORDER_THIN,  'color' => array('rgb' => '000000')))
				);

				$spreadsheet->getActiveSheet()->getStyle('A'.$totalStatsRow.':G'.$totalStatsRow)->applyFromArray($style);
				$spreadsheet->getActiveSheet()->getStyle('H'.$totalStatsRow.':O'.$totalStatsRow)->applyFromArray($style2);
				$spreadsheet->getActiveSheet()->getStyle('A'.$totalStatsRow.':O'.$totalStatsRow)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('B8C0CB');

            } else {
				//Show No Record
				$spreadsheet->getActiveSheet()
                            ->mergeCells('A'.$productDetailsRow.':O'.$productDetailsRow);
				
				$spreadsheet->getActiveSheet()
				            ->getCell('A'.$productDetailsRow)
							->setValue('NO ITEM FOUND');
									
				$style = array(
					'alignment' => array('horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER,),
					'font'  => array('bold'  => true, 'color' => array('rgb' => '000000'), 'size'  => 8, 'name'  => 'Calibri'),
					'borders' => array('allborders' => array('style' => Border::BORDER_THIN,  'color' => array('rgb' => '000000')))
				);

				$spreadsheet->getActiveSheet()->getStyle('A'.$productDetailsRow.':O'.$productDetailsRow)->applyFromArray($style);
				$spreadsheet->getActiveSheet()->getStyle('A'.$productDetailsRow.':O'.$productDetailsRow)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('E2E6EA');
            }
		} else {
            //if there are no members 
			//Show No Record
			$spreadsheet->getActiveSheet()
					->mergeCells('A9:O9');
	
			$spreadsheet->getActiveSheet()
						->getCell('A9')
						->setValue('NO MEMBER FOUND');
						
			$style = array(
				'alignment' => array('horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER,),
				'font'  => array('bold'  => true, 'color' => array('rgb' => '000000'), 'size'  => 8, 'name'  => 'Calibri'),
				'borders' => array('allborders' => array('style' => Border::BORDER_THIN,  'color' => array('rgb' => '000000')))
			);

			$spreadsheet->getActiveSheet()->getStyle("A9:O9")->applyFromArray($style);
			$spreadsheet->getActiveSheet()->getStyle('A9:O9')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('B8C0CB');
		}

        //Rename Worksheet
		$reportMonthYearWorksheet = date_format(date_create($reportMonthYear),"M Y");
		$spreadsheet->getActiveSheet()->setTitle("Product Destruction - {$reportMonthYearWorksheet}");
        //END DESTRUCTION REPORT

        //SUMMARY REPORT START HERE
        //Summary Report Sheet
		$spreadsheet->createSheet();
		$spreadsheet->setActiveSheetIndex(2);
		$spreadsheet->getActiveSheet()->freezePane('J9');
		
		//Summary Report Column Properties)
		$spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(15);
		$spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(14);
		$spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(14);
		$spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(15);
		$spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(12);
		$spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(23);
		$spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(20);
		$spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(20);
		$spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(20);

        //Summary Report Header
		//Header Title
		$spreadsheet->getActiveSheet()
        ->mergeCells('A1:B1');
        
        $spreadsheet->getActiveSheet()
                ->getCell('A1')
                ->setValue('PHAP Cares Inventory Report : ');
                
        $spreadsheet->getActiveSheet()
                ->mergeCells('C1:D1');

        $spreadsheet->getActiveSheet()
                ->getCell('C1')
                ->setValue("{$reportMonthYearHeader}");
                
        //Summary Report Title Style Properties
        $style = array(
        'alignment' => array('horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER,),
        'font'  => array('bold'  => true, 'color' => array('rgb' => 'FFFFFF'), 'size'  => 10, 'name'  => 'Calibri'),
        'borders' => array('allborders' => array('style' => Border::BORDER_THIN,  'color' => array('rgb' => '6D466B')))
        );

        $style2 = array(
        'alignment' => array('horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER,),
        'font'  => array('bold'  => true, 'color' => array('rgb' => 'E9CCC9'), 'size'  => 10, 'name'  => 'Calibri'),
        'borders' => array('allborders' => array('style' => Border::BORDER_THIN,  'color' => array('rgb' => '6D466B')))
        );

        $spreadsheet->getActiveSheet()->getStyle("A1:I1")->applyFromArray($style);
        $spreadsheet->getActiveSheet()->getStyle("C1")->applyFromArray($style2);

        $spreadsheet->getActiveSheet()->getStyle('A1:I1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('6D466B');

        //Header Details
        $spreadsheet->getActiveSheet()
                ->getCell('A2')
                ->setValue("Summary");

        $spreadsheet->getActiveSheet()
                ->mergeCells('B2:C2');

        $spreadsheet->getActiveSheet()
                ->getCell('B2')
                ->setValue("From : {$reportDateFrom}");

        $spreadsheet->getActiveSheet()
                ->mergeCells('D2:E2');

        $spreadsheet->getActiveSheet()
                ->getCell('D2')
                ->setValue("To : {$reportDateTo}");

        $spreadsheet->getActiveSheet()
                ->mergeCells('A3:C3');

        $spreadsheet->getActiveSheet()
                ->getCell('A3')
                ->setValue("Summary Generated Date/Time : ");

        $spreadsheet->getActiveSheet()
                ->mergeCells('D3:E3');
                
        $spreadsheet->getActiveSheet()
                ->getCell('D3')
                ->setValue("{$generatedDate}");
                
        //Header Details Style Properties
        $style = array(
        'alignment' => array('horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER,),
        'font'  => array('bold'  => true, 'color' => array('rgb' => 'FFFFFF'), 'size'  => 10, 'name'  => 'Calibri'),
        'borders' => array('allborders' => array('style' => Border::BORDER_THIN,  'color' => array('rgb' => '947893')))
        );

        $style2 = array(
        'alignment' => array('horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER,),
        'font'  => array('bold'  => true, 'color' => array('rgb' => 'E9CCC9'), 'size'  => 10, 'name'  => 'Calibri'),
        'borders' => array('allborders' => array('style' => Border::BORDER_THIN,  'color' => array('rgb' => '947893')))
        );

        $spreadsheet->getActiveSheet()->getStyle("A2:I2")->applyFromArray($style);
        $spreadsheet->getActiveSheet()->getStyle("B2")->applyFromArray($style2);
        $spreadsheet->getActiveSheet()->getStyle("D2")->applyFromArray($style2);

        $spreadsheet->getActiveSheet()->getStyle("A3:I3")->applyFromArray($style);
        $spreadsheet->getActiveSheet()->getStyle("D3")->applyFromArray($style2);

        $spreadsheet->getActiveSheet()->getStyle('A2:I2')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('947893');
        $spreadsheet->getActiveSheet()->getStyle('A3:I3')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('947893');

        //Transaction Summary
        //Summary Title
        $spreadsheet->getActiveSheet()
                ->getCell('A5')
                ->setValue('Summary');
                
        //Summary Title Style Properties
        $style = array(
        'alignment' => array('horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER,),
        'font'  => array('bold'  => true, 'color' => array('rgb' => 'FFFFFF'), 'size'  => 10, 'name'  => 'Calibri'),
        'borders' => array('allborders' => array('style' => Border::BORDER_THIN,  'color' => array('rgb' => '5096A0')))
        );

        $spreadsheet->getActiveSheet()->getStyle("A5:I5")->applyFromArray($style);
        $spreadsheet->getActiveSheet()->getStyle('A5:I5')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('5096A0');

		//Summary Details
		//Compute Total Qty Movements
        $total_quantity_movements = Summary::where('year',$year)
        ->where('month',$month)
        ->sum('movements_quantity');

        $total_quantity_ending_balance = Summary::where('year',$year)
        ->where('month',$month)
        ->sum('ending_balance_quantity');

        $total_ending_balance = Summary::where('year',$year)
        ->where('month',$month)
        ->sum('ending_balance_value');	
	
		//Total Qty Movements
		$spreadsheet->getActiveSheet()
					->mergeCells('A6:B6');		
					
		$spreadsheet->getActiveSheet()
					->getCell('A6')
					->setValue('Total Qty Movements: ');
		
		//Set Number Format
		$spreadsheet->getActiveSheet()
					->getStyle('C6')
					->getNumberFormat()
					->setFormatCode('#,##0');
					
		$spreadsheet->getActiveSheet()
					->getCell('C6')
					->setValue("{$total_quantity_movements}");
					
		//Total Qty Ending Balance
		$spreadsheet->getActiveSheet()
					->mergeCells('A7:B7');		
					
		$spreadsheet->getActiveSheet()
					->getCell('A7')
					->setValue('Total Qty Ending Balance: ');
		
		//Set Number Format
		$spreadsheet->getActiveSheet()
					->getStyle('C7')
					->getNumberFormat()
					->setFormatCode('#,##0');
					
		$spreadsheet->getActiveSheet()
					->getCell('C7')
					->setValue("{$total_quantity_ending_balance}");
					
		//Total Ending Balance
		$spreadsheet->getActiveSheet()
					->mergeCells('D7:E7');		
					
		$spreadsheet->getActiveSheet()
					->getCell('D7')
					->setValue('Total Ending Balance: ');
		
		//Set Number Format
		$spreadsheet->getActiveSheet()
					->getStyle('F7')
					->getNumberFormat()
					->setFormatCode('#,##0.00');
					
		$spreadsheet->getActiveSheet()
					->getCell('F7')
					->setValue("{$total_ending_balance}");

		//Summary Details Style Properties
		$style = array(
			'alignment' => array('horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER,),
			'font'  => array('bold'  => true, 'color' => array('rgb' => 'FFFFFF'), 'size'  => 10, 'name'  => 'Calibri'),
			'borders' => array('allborders' => array('style' => Border::BORDER_THIN,  'color' => array('rgb' => '85BCC5')))
		);
		
		$style2 = array(
			'alignment' => array('horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER,),
			'font'  => array('bold'  => true, 'color' => array('rgb' => '253D5B'), 'size'  => 10, 'name'  => 'Calibri'),
			'borders' => array('allborders' => array('style' => Border::BORDER_THIN,  'color' => array('rgb' => '85BCC5')))
		);

		$spreadsheet->getActiveSheet()->getStyle("A6:I6")->applyFromArray($style);
		$spreadsheet->getActiveSheet()->getStyle("A7:I7")->applyFromArray($style);
		$spreadsheet->getActiveSheet()->getStyle("C6")->applyFromArray($style2);
		$spreadsheet->getActiveSheet()->getStyle("C7")->applyFromArray($style2);
		$spreadsheet->getActiveSheet()->getStyle("F7")->applyFromArray($style2);
		
		$spreadsheet->getActiveSheet()->getStyle('A6:I6')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('85BCC5');
		$spreadsheet->getActiveSheet()->getStyle('A7:I7')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('85BCC5');
		
		//Get Last Summary Month And Year
		$reportDate = date_create($year."-".$month);
		$reportDate = date_format($reportDate,"Y-m");
		
		$lastSummaryDate = date("Y-m", strtotime("-1 month", strtotime($reportDate)));
		$lastSummaryDateFormat = date_create($lastSummaryDate);
		$lastSummaryMonth = date_format($lastSummaryDateFormat, 'm');
		$lastSummaryYear = date_format($lastSummaryDateFormat, 'Y');

		//Set Report Date To : Last Day Of The Month
		$beginningBalanceDate = date_create($lastSummaryYear."-".$lastSummaryMonth."-01");
		$beginningBalanceDate = date_format($beginningBalanceDate,"m-t-Y");
				
		//Product Details Header
		$spreadsheet->getActiveSheet()
					->getCell('A8')
					->setValue('Product Code');
		
		$spreadsheet->getActiveSheet()
					->mergeCells('B8:C8');
					
		$spreadsheet->getActiveSheet()
					->getCell('B8')
					->setValue('Product Name');
		
		$spreadsheet->getActiveSheet()
					->getCell('D8')
					->setValue('Lot No');
	
		$spreadsheet->getActiveSheet()
					->getCell('E8')
					->setValue('Trade Price');
		
		$spreadsheet->getActiveSheet()
					->getCell('F8')
					->setValue("Qty Balance {$beginningBalanceDate}");
					
		$spreadsheet->getActiveSheet()
					->getCell('G8')
					->setValue('Qty Movements');
		
		$spreadsheet->getActiveSheet()
					->getCell('H8')
					->setValue('Qty Ending Balance');
		
		$spreadsheet->getActiveSheet()
					->getCell('I8')
					->setValue('Value Of Ending Balance');
		
		//Product Details Style Properties
		$style = array(
			'alignment' => array('horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER,),
			'font'  => array('bold'  => true, 'color' => array('rgb' => 'FFFFFF'), 'size'  => 10, 'name'  => 'Calibri'),
			'borders' => array('allborders' => array('style' => Border::BORDER_THIN,  'color' => array('rgb' => 'FFFFFF')))
		);

		$spreadsheet->getActiveSheet()->getStyle("A8:I8")->applyFromArray($style);
		$spreadsheet->getActiveSheet()->getStyle('A8:I8')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('24323D');
		
		//Product List Starting Row : A9
		$startingRow = 9;
		
		//Member List Query

        if($selected_member_id != 0) {
            $members = Member::where('id',$selected_member_id)->select('id','member_name')->get();
        } else {
            $members = DB::table('members')->select('id','member_name')->get();
        }

        $memberCount = $members->count();

		if ($memberCount > 0) {
			foreach($members as $memberDetails) {
				$memberId =  $memberDetails->id;
				$memberName =  $memberDetails->member_name;
				
				//Show Member Name
				$spreadsheet->getActiveSheet()
							->mergeCells('A'.$startingRow.':I'.$startingRow);
	
				$spreadsheet->getActiveSheet()
							->getCell('A'.$startingRow)
							->setValue("{$memberName}");
				
				$style = array(
					'alignment' => array('horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER,),
					'font'  => array('bold'  => true, 'color' => array('rgb' => 'FFFFFF'), 'size'  => 11, 'name'  => 'Calibri'),
					'borders' => array('allborders' => array('style' => Border::BORDER_THIN,  'color' => array('rgb' => '000000')))
				);

				$spreadsheet->getActiveSheet()->getStyle('A'.$startingRow.':I'.$startingRow)->applyFromArray($style);
				$spreadsheet->getActiveSheet()->getStyle('A'.$startingRow.':I'.$startingRow)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('63768D');
				
                $summary = Summary::where('member_id',$memberId)
                ->where('year', $year)
                ->where('month', $month)
                ->get();

				$totalSummaryRow = $summary->count();
				
				$productDetailsRow = $startingRow + 1;
				if ($totalSummaryRow > 0) {
					foreach($summary as $summaryDetails) {
						//Product Details
						$summaryId = $summaryDetails->id;
						$memberId = $summaryDetails->member_id;
						$month = $summaryDetails->month;
						$year = $summaryDetails->year;
						$productCode = $summaryDetails->product_code;
						$productName = $summaryDetails->product_name;
						$lotNo = $summaryDetails->lot_no;
						$unitCost = $summaryDetails->unit_cost;
						$beginningBalanceQuantity = $summaryDetails->beginning_balance_quantity;
						$movementsQuantity = $summaryDetails->movements_quantity;
						$endingBalanceQuantity = $summaryDetails->ending_balance_quantity;
						$endingBalanceValue = $summaryDetails->ending_balance_value;
						$createDate = $summaryDetails->create_date;
						
						$spreadsheet->getActiveSheet()
									->getCell('A'.$productDetailsRow)
									->setValue("{$productCode}");
				
						$spreadsheet->getActiveSheet()
									->mergeCells('B'.$productDetailsRow.':C'.$productDetailsRow);
					
						$spreadsheet->getActiveSheet()
									->getCell('B'.$productDetailsRow)
									->setValue("{$productName}");
						
						$spreadsheet->getActiveSheet()
									->getCell('D'.$productDetailsRow)
									->setValue("{$lotNo}");
						
						//Set Number Format
						$spreadsheet->getActiveSheet()
									->getStyle('E'.$productDetailsRow)
									->getNumberFormat()
									->setFormatCode('#,##0.00');
									
						$spreadsheet->getActiveSheet()
									->getCell('E'.$productDetailsRow)
									->setValue("{$unitCost}");
						
						//Set Number Format
						$spreadsheet->getActiveSheet()
									->getStyle('F'.$productDetailsRow)
									->getNumberFormat()
									->setFormatCode('#,##0');
									
						$spreadsheet->getActiveSheet()
									->getCell('F'.$productDetailsRow)
									->setValue("{$beginningBalanceQuantity}");
									
						//Set Number Format
						$spreadsheet->getActiveSheet()
									->getStyle('G'.$productDetailsRow)
									->getNumberFormat()
									->setFormatCode('#,##0');
									
						$spreadsheet->getActiveSheet()
									->getCell('G'.$productDetailsRow)
									->setValue("{$movementsQuantity}");
						
						//Set Number Format
						$spreadsheet->getActiveSheet()
									->getStyle('H'.$productDetailsRow)
									->getNumberFormat()
									->setFormatCode('#,##0');
									
						$spreadsheet->getActiveSheet()
									->getCell('H'.$productDetailsRow)
									->setValue("{$endingBalanceQuantity}");
						
						//Set Number Format
						$spreadsheet->getActiveSheet()
									->getStyle('I'.$productDetailsRow)
									->getNumberFormat()
									->setFormatCode('#,##0.00');
						
						$spreadsheet->getActiveSheet()
									->getCell('I'.$productDetailsRow)
									->setValue("{$endingBalanceValue}");
						
						$style = array(
							'alignment' => array('horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER,),
							'font'  => array('color' => array('rgb' => '000000'), 'size'  => 8, 'name'  => 'Calibri'),
							'borders' => array('allborders' => array('style' => Border::BORDER_THIN,  'color' => array('rgb' => '000000')))
						);
						
						$style2 = array(
							'alignment' => array('horizontal' => Alignment::HORIZONTAL_RIGHT, 'vertical' => Alignment::VERTICAL_CENTER,),
							'font'  => array('color' => array('rgb' => '000000'), 'size'  => 8, 'name'  => 'Calibri'),
							'borders' => array('allborders' => array('style' => Border::BORDER_THIN,  'color' => array('rgb' => '000000')))
						);

						$spreadsheet->getActiveSheet()->getStyle('A'.$productDetailsRow.':D'.$productDetailsRow)->applyFromArray($style);
						$spreadsheet->getActiveSheet()->getStyle('E'.$productDetailsRow.':I'.$productDetailsRow)->applyFromArray($style2);
						$spreadsheet->getActiveSheet()->getStyle('A'.$productDetailsRow.':I'.$productDetailsRow)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('E2E6EA');
					
						$productDetailsRow++;
					}
					//Compute The Location Of Total Row By Adding Starting Row, Member Title Row (1) And Total Summary Row
					$totalStatsRow = $startingRow + $totalSummaryRow + 1;

                    $total_quantity_movements = Summary::where('member_id',$memberId)
                    ->where('year',$year)
                    ->where('month',$month)
                    ->sum('movements_quantity');
            
                    $total_quantity_ending_balance = Summary::where('member_id',$memberId)
                    ->where('year',$year)
                    ->where('month',$month)
                    ->sum('ending_balance_quantity');
            
                    $total_ending_balance = Summary::where('member_id',$memberId)
                    ->where('year',$year)
                    ->where('month',$month)
                    ->sum('ending_balance_value');	

					//Show Total Record
					$spreadsheet->getActiveSheet()
								->mergeCells('A'.$totalStatsRow.':F'.$totalStatsRow);
				
					$spreadsheet->getActiveSheet()
								->getCell('A'.$totalStatsRow)
								->setValue('TOTAL');
					
					$spreadsheet->getActiveSheet()
								->getStyle('G'.$totalStatsRow)
								->getNumberFormat()
								->setFormatCode('#,##0');
									
					$spreadsheet->getActiveSheet()
								->getCell('G'.$totalStatsRow)
								->setValue("{$total_quantity_movements}");
								
					$spreadsheet->getActiveSheet()
								->getStyle('H'.$totalStatsRow)
								->getNumberFormat()
								->setFormatCode('#,##0');
									
					$spreadsheet->getActiveSheet()
								->getCell('H'.$totalStatsRow)
								->setValue("{$total_quantity_ending_balance}");
								
					$spreadsheet->getActiveSheet()
								->getStyle('I'.$totalStatsRow)
								->getNumberFormat()
								->setFormatCode('#,##0.00');
									
					$spreadsheet->getActiveSheet()
								->getCell('I'.$totalStatsRow)
								->setValue("{$total_ending_balance}");
									
					$style = array(
						'alignment' => array('horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER,),
						'font'  => array('bold'  => true, 'color' => array('rgb' => '000000'), 'size'  => 8, 'name'  => 'Calibri'),
						'borders' => array('allborders' => array('style' => Border::BORDER_THIN,  'color' => array('rgb' => '000000')))
					);
					
					$style2 = array(
						'alignment' => array('horizontal' => Alignment::HORIZONTAL_RIGHT, 'vertical' => Alignment::VERTICAL_CENTER,),
						'font'  => array('bold'  => true, 'color' => array('rgb' => '000000'), 'size'  => 8, 'name'  => 'Calibri'),
						'borders' => array('allborders' => array('style' => Border::BORDER_THIN,  'color' => array('rgb' => '000000')))
					);

					$spreadsheet->getActiveSheet()->getStyle('A'.$totalStatsRow.':E'.$totalStatsRow)->applyFromArray($style);
					$spreadsheet->getActiveSheet()->getStyle('F'.$totalStatsRow.':I'.$totalStatsRow)->applyFromArray($style2);
					$spreadsheet->getActiveSheet()->getStyle('A'.$totalStatsRow.':I'.$totalStatsRow)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('B8C0CB');	
				} else {
					//Show No Record
					$spreadsheet->getActiveSheet()
								->mergeCells('A'.$productDetailsRow.':I'.$productDetailsRow);
				
					$spreadsheet->getActiveSheet()
								->getCell('A'.$productDetailsRow)
								->setValue('NO RECORD FOUND');
									
					$style = array(
						'alignment' => array('horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER,),
						'font'  => array('bold'  => true, 'color' => array('rgb' => '000000'), 'size'  => 8, 'name'  => 'Calibri'),
						'borders' => array('allborders' => array('style' => Border::BORDER_THIN,  'color' => array('rgb' => '000000')))
					);

					$spreadsheet->getActiveSheet()->getStyle('A'.$productDetailsRow.':I'.$productDetailsRow)->applyFromArray($style);
					$spreadsheet->getActiveSheet()->getStyle('A'.$productDetailsRow.':I'.$productDetailsRow)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('E2E6EA');
				}
		
				//Compute The Next Starting Row By Adding Starting Row, Total Transaction Row, Member Title Row (1), Total Stats Row (1) and Space Row(1)
				$startingRow = $startingRow + $totalSummaryRow + 3;
			}
		} else {
			//Show No Record
			$spreadsheet->getActiveSheet()
					->mergeCells('A9:I9');
	
			$spreadsheet->getActiveSheet()
						->getCell('A9')
						->setValue('NO MEMBER FOUND');
						
			$style = array(
				'alignment' => array('horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER,),
				'font'  => array('bold'  => true, 'color' => array('rgb' => '000000'), 'size'  => 8, 'name'  => 'Calibri'),
				'borders' => array('allborders' => array('style' => Border::BORDER_THIN,  'color' => array('rgb' => '000000')))
			);

			$spreadsheet->getActiveSheet()->getStyle("A9:I9")->applyFromArray($style);
			$spreadsheet->getActiveSheet()->getStyle('A9:I9')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('B8C0CB');
		}

		//Rename Worksheet
		$reportMonthYearWorksheet = date_format(date_create($reportMonthYear),"M Y");
		$spreadsheet->getActiveSheet()->setTitle("Summary - {$reportMonthYearWorksheet}");
		
		//Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$spreadsheet->setActiveSheetIndex(0);
		
		//Set Filename
		$fileNameMonthYear = date_format(date_create($reportMonthYear),"M_Y");
		$fileNameGeneratedDate = date("mdY_hisA");
		
        // add this when filtering as member
		// if ($reportMemberId != '') {
		// 	$fileName = $functionListClass->stringToUc($memberName."_".$fileNameMonthYear."_".$fileNameGeneratedDate);
		// } else {
		// 	$fileName = $functionListClass->stringToUc($fileNameMonthYear."_".$fileNameGeneratedDate);
		// }

		$fileName = $fileNameMonthYear."_".$fileNameGeneratedDate;
        
		//Redirect output to a client’s web browser (Excel2007)
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header("Content-Disposition: attachment;filename=PHAP Cares Inventory Report ".$fileName.".xlsx");
		header('Cache-Control: max-age=0');
		
		//If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');

		//If you're serving to IE over SSL, then the following may be needed
		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); //Date in the past
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); //Always modified
		header ('Cache-Control: cache, must-revalidate'); //HTTP/1.1
		header ('Pragma: public'); //HTTP/1.0

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');

    }

    
}
