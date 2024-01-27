@extends('layouts.app')

@section('custom-css')
<link rel="stylesheet" type="text/css" href="{{ asset('js/DataTables/datatables.min.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('css/product-donation.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('css/reports.css') }}"/>

@endsection

@section('content')
<div class="bg-heading">
    <h4 class="px-4 py-3">Reports</h4>
</div>

<div class="bg-initial-details mt-2 px-4 py-4">
    <div class="row">
        <div class="col-md-12">
            <!-- Tab Buttons -->
            <ul class="nav nav-tabs mt-3" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $tab == 'TR' ? 'active' : '' }}" id="transaction-tab" data-bs-toggle="tab" data-bs-target="#transaction" type="button" role="tab" aria-controls="home" aria-selected="true">Transaction Reports</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $tab == 'PD' ? 'active' : '' }}" id="destruction-tab" data-bs-toggle="tab" data-bs-target="#destruction" type="button" role="tab" aria-controls="profile" aria-selected="false">Product Destructions</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $tab == 'SR' ? 'active' : '' }}" id="summary-tab" data-bs-toggle="tab" data-bs-target="#summary" type="button" role="tab" aria-controls="contact" aria-selected="false">Summary</button>
                </li>
            </ul>

            <div class="tab-content" id="myTabContent">
                <!-- Transaction Tab  -->
                <div class="tab-pane fade {{ $tab == 'TR' ? 'show active' : '' }}" id="transaction" role="tabpanel" aria-labelledby="home-tab">
                    <!-- Top Forms Filter Selection -->
                    <form method="GET" action="{{ route('filter-reports-list') }}">
                    <div class="row mt-3">
                        <div class="col-auto my-auto">
                            Year :
                        </div>
                        <div class="col-auto my-auto">
                            <select class="form-control form-select me-2" aria-label="Default select example" name="year">
                                <option {{ $year == 2022 ? "selected" : "" }} value="2022">2022</option>
                                <option {{ $year == 2023 ? "selected" : "" }} value="2023">2023</option>
                                <option {{ $year == 2024 ? "selected" : "" }} value="2024">2024</option>
                                <option {{ $year == 2025 ? "selected" : "" }} value="2025">2025</option>
                                <option {{ $year == 2026 ? "selected" : "" }} value="2026">2026</option>
                            </select>
                        </div>

                        <div class="col-auto my-auto">
                            Month :
                        </div>

                        <div class="col-auto my-auto">
                            <select class="form-control form-select me-2" aria-label="Default select example" name="month">
                                <option {{ $month == 1 ? "selected" : "" }} value="1">January</option>
                                <option {{ $month == 2 ? "selected" : "" }} value="2">February</option>
                                <option {{ $month == 3 ? "selected" : "" }} value="3">March</option>
                                <option {{ $month == 4 ? "selected" : "" }} value="4">April</option>
                                <option {{ $month == 5 ? "selected" : "" }} value="5">May</option>
                                <option {{ $month == 6 ? "selected" : "" }} value="6">June</option>
                                <option {{ $month == 7 ? "selected" : "" }} value="7">July</option>
                                <option {{ $month == 8 ? "selected" : "" }} value="8">August</option>
                                <option {{ $month == 9 ? "selected" : "" }} value="9">September</option>
                                <option {{ $month == 10 ? "selected" : "" }} value="10">October</option>
                                <option {{ $month == 11 ? "selected" : "" }} value="11">November</option>
                                <option {{ $month == 12 ? "selected" : "" }} value="12">December</option>
                            </select>
                        </div>

                        <div class="col-auto my-auto">
                            Members :
                        </div>

                        <div class="col-auto my-auto">
                            <select class="form-control form-select me-2" aria-label="Default select example" name="member_id">
                                <option value="0">All</option>
                                @foreach($members_list as $member) 
                                    <option {{ $member_id == $member->id ? "selected" : "" }} value="{{ $member->id }}">{{ $member->member_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-auto my-auto">
                            TXN Type :
                        </div>

                        <div class="col-auto my-auto">
                            <select class="form-control form-select me-2" aria-label="Default select example" name="transaction_type">
                                <option {{ $transaction_type == 'EXP' ? "selected" : "" }} value="EXP">Outbound</option>
                                <option {{ $transaction_type == 'IMP' ? "selected" : "" }} value="IMP">Inbound</option>
                                <option {{ $transaction_type == 'ADJI' ? "selected" : "" }} value="ADJI">Adjustment-</option>
                                <option {{ $transaction_type == 'ADJR' ? "selected" : "" }} value="ADJR">Adjustment+</option>
                            </select>
                        </div>

                        <input type="text" name="tab" value="TR" hidden>

                        <div class="col-auto my-auto">
                            Inventory Loc :
                        </div>

                        <div class="col-auto my-auto">
                            <select class="form-control form-select me-2" aria-label="Default select example" name="inventory_location">
                                <option {{ $inventory_location == '' ? "selected" : "" }} value="">ALL</option>
                                <option {{ $inventory_location == 'ZPC' ? "selected" : "" }} value="ZPC">ZPC</option>
                                <option {{ $inventory_location == 'OCP' ? "selected" : "" }} value="OCP">OCP</option>
                            </select>
                        </div>
                        <input type="text" name="tab" value="TR" hidden>

                        <div class="col-auto my-auto">
                            <button type="submit" class="btn btn-outline-success mr-auto fw-bold">Search Filter</button>
                        </div>

                    </form>
                        <div class="col my-auto">
                            <div class="d-flex justify-content-end">
                                <form method="GET" action="{{ route('reports-generate') }}">
                                    <input type="text" name="year" value="{{ $year }}" hidden>
                                    <input type="text" name="month" value="{{ $month }}" hidden>
                                    <input type="text" name="member_id" value="{{ $member_id }}" hidden>
                                    <input type="text" name="inventory_location" value="{{ $inventory_location }}" hidden>
                                    <button href="{{ route('reports-generate') }}" class="btn btn-primary mr-auto fw-bold">Generate Report</a>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <!-- EndTop Forms Filter Selection -->

                    <!-- Issuance row -->
                    <!-- Not Showing if filetered by a member -->
                    @if($member_id == 0)
                    <div class="d-flex flex-row-reverse">
                        <div class="mt-3 mb-1 rounded border fw-bold" style="padding-top:10px; padding-bottom:10px;  padding-left:15px; padding-right:15px;">
                            {{ $transaction_total_titles }} Quantity: 
                            @if($transaction_type == 'IMP' || $transaction_type == 'ADJR')
                            {{ number_format($total_receipt_quantity) }}
                            @elseif($transaction_type == 'EXP' || $transaction_type == 'ADJI')
                            {{ number_format($total_issuance_quantity) }}
                            @endif
                        </div>
                        <div class="mt-3 mb-1 rounded border me-2 fw-bold" style="padding-top:10px; padding-bottom:10px;  padding-left:15px; padding-right:15px;">
                            {{ $transaction_total_titles }} Amount: PHP 
                            @if($transaction_type == 'IMP' || $transaction_type == 'ADJR')
                            {{ number_format($total_receipt_amount,2) }}
                            @elseif($transaction_type == 'EXP' || $transaction_type == 'ADJI')
                            {{ number_format($total_issuance_amount,2) }}
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- End Issuance row -->
                    @foreach($members as $member)
                    <div class="members-title mt-3 mb-1 rounded">
                        {{ $month_name }} {{ $year }} : {{ $member->member_name }} : {{ $transaction_table_title }}
                    </div>

                    <div class="table-responsive">
                        <table class="table" style="width:100%">
                            <thead class="theader">
                                <tr>
                                    <th scope="col">No.</th>
                                    <th scope="col">Product Code</th>
                                    <th scope="col">Product Name</th>
                                    @if($transaction_type == 'EXP')<th scope="col">Beneficiary</th>@endif
                                    <th scope="col">Lot No.</th>
                                    <th scope="col">Opening Balance</th>
                                    <th scope="col">Quantity</th>
                                    <th scope="col">Trade Price</th>
                                    @if($transaction_type == 'IMP' || $transaction_type == 'ADJR')<th scope="col">Receipt</th>@endif
                                    @if($transaction_type == 'EXP' || $transaction_type == 'ADJI')<th scope="col">Issuance</th>@endif
                                    @if($transaction_type == 'IMP' || $transaction_type == 'ADJR')<th scope="col">Receipt Amount</th>@endif
                                    @if($transaction_type == 'EXP' || $transaction_type == 'ADJI')<th scope="col">Issuance Amount</th>@endif
                                    <th scope="col">Reference</th>
                                    <th scope="col">Transaction Date</th>
                                    <th scope="col">Inv. Loc</th>
                                </tr>
                            </thead>
                            <!-- TRANSACTION REPORT ITEMS -->
                            <tbody>
                                @php
                                $transaction_reports = \App\TransactionReport::where('member_id', $member->id)
                                ->where('year', $year)
                                ->where('month', $month)
                                ->where('transaction_type', $transaction_type)
                                ->where('inventory_location','LIKE','%'.$inventory_location.'%')
                                ->select('id','product_code','contribution_id',
                                'contribution_no','allocation_no','allocation_id',
                                'beneficiary_id','product_name','transaction_type',
                                'lot_no','opening_balance_quantity','quantity','unit_cost',
                                'receipt_quantity','receipt_amount','job_no','issuance_quantity','issuance_amount','created_at','inventory_location')
                                ->get();

                                $transaction_report_total_quantity = 0;
                                $transaction_report_total_amount = 0;

                                if($transaction_reports->count() != 0) {
                                    foreach($transaction_reports as $transaction_report) {

                                        if (($transaction_type == 'EXP') || ($transaction_type == 'ADJI')) {
                                            $transaction_report_total_quantity += $transaction_report->issuance_quantity;
                                            $transaction_report_total_amount += $transaction_report->issuance_amount;
                                        } else {
                                            $transaction_report_total_quantity += $transaction_report->receipt_quantity;
                                            $transaction_report_total_amount += $transaction_report->receipt_amount;
                                        }

                                        $quantity = number_format($transaction_report->quantity);
                                        $receipt_quantity = number_format($transaction_report->receipt_quantity);
                                        $receipt_amount = number_format($transaction_report->receipt_amount,2);
                                        $issuance_quantity = number_format($transaction_report->issuance_quantity);
                                        $issuance_amount = number_format($transaction_report->issuance_amount,2);

                                        $beneficiary = \App\Beneficiary::where('id', $transaction_report->beneficiary_id)
                                        ->select('name')
                                        ->get();

                                        $beneficiary_name = "";

                                        foreach($beneficiary as $item){
                                            $beneficiary_name = $item->name;
                                        }

                                        $contribution = \App\Contribution::where('id', $transaction_report->contribution_id)
                                        ->select('dnd_no','didrf_no')
                                        ->get();

                                        $contributionNo = $transaction_report->contribution_no;
                                        $dndNo = "";
                                        $didrfNo = "";

                                        //Get Contribution Details
                                        foreach($contribution as $item){
                                            $dndNo = $item->dnd_no;
                                            $didrfNo = $item->didrf_no;
                                        }

                                        $allocation = \App\Allocation::where('id', $transaction_report->allocation_id)
                                        ->select('dna_no','dodrf_no')
                                        ->get();

                                        $allocationNo = $transaction_report->allocation_no;
                                        $dodrfNo = "";
                                        $dnaNo = "";
                                
                                        foreach($allocation as $item){
                                            $dodrfNo = $item->dodrf_no;
                                            $dnaNo = $item->dna_no;
                                        }

                                        if ($transaction_type == 'EXP') {
                                            $reference = $allocationNo."/DODRF: ".$dodrfNo."/DNA: ".$dnaNo;
                                        } else if ($transaction_type == 'IMP') {
                                            $reference = $contributionNo."/DIDRF: ".$didrfNo."/DND: ".$dndNo;
                                        } else if ($transaction_type == 'ADJI') {
                                            $reference = 'INVENTORY ADJ-';
                                        } else {
                                            $reference = 'INVENTORY ADJ+';
                                        }

                                        $created_at = date('F, d Y', strtotime($transaction_report->created_at));

                                        echo"<tr>";
                                            echo"<td>$transaction_report->id</td>";
                                            echo"<td>$transaction_report->product_code</td>";
                                            echo"<td>$transaction_report->product_name</td>";
                                            if ($transaction_type == 'EXP') { echo"<td>$beneficiary_name</td>"; }
                                            echo"<td>$transaction_report->lot_no</td>";
                                            echo"<td>$transaction_report->opening_balance_quantity</td>";
                                            echo"<td>$quantity</td>";
                                            echo"<td>$transaction_report->unit_cost</td>";
                                            if ($transaction_type == 'IMP' || $transaction_type == 'ADJR') { echo"<td>$receipt_quantity</td>"; }
                                            if ($transaction_type == 'EXP' || $transaction_type == 'ADJI') { echo"<td>$issuance_quantity</td>"; }
                                            if ($transaction_type == 'IMP' || $transaction_type == 'ADJR') { echo"<td>$receipt_amount</td>"; }
                                            if ($transaction_type == 'EXP' || $transaction_type == 'ADJI') { echo"<td>$issuance_amount</td>"; }
                                            echo"<td>$reference</td>";
                                            echo"<td>$created_at</td>";
                                            echo"<td>$transaction_report->inventory_location</td>";
                                        echo"</tr>";
                                        
                                    }
                                } else {
                                    $colspan = 12;
                                    if($transaction_type == 'EXP') {
                                        $colspan = 13;
                                    }
                                    echo
                                    '<tr class="tableNoRecord">
                                        <td colspan="'.$colspan.'" align="center">No Record Found</td>
                                    </tr>';
                                }
                                @endphp
                                <tr class="tableRecordStat">
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    @if($transaction_type == 'EXP')<td></td>@endif
                                    <td>{{ $transaction_total_titles }}</td>
                                    <td class="tableRecordStatAmount">{{ number_format($transaction_report_total_quantity) }}</td>
                                    <td class="tableRecordStatAmount">Php {{ number_format($transaction_report_total_amount,2) }}</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    @endforeach
                    <!-- Table -->
                </div>
                <!-- End Transaction Tab  -->

                <!-- Product Destruction Tab -->
                <div class="tab-pane fade {{ $tab == 'PD' ? 'show active' : '' }}" id="destruction" role="tabpanel" aria-labelledby="profile-tab">
                    <!-- Top Forms Filter Selection -->
                    <form method="GET" action="{{ route('filter-reports-list') }}">
                    <div class="row mt-3">
                        <div class="col-auto my-auto">
                            Year :
                        </div>
                        <div class="col-auto my-auto">
                            <select class="form-control form-select me-2" aria-label="Default select example" name="year">
                                <option {{ $year == 2022 ? "selected" : "" }} value="2022">2022</option>
                                <option {{ $year == 2023 ? "selected" : "" }} value="2023">2023</option>
                                <option {{ $year == 2024 ? "selected" : "" }} value="2024">2024</option>
                                <option {{ $year == 2025 ? "selected" : "" }} value="2025">2025</option>
                                <option {{ $year == 2026 ? "selected" : "" }} value="2026">2026</option>
                            </select>
                        </div>

                        <div class="col-auto my-auto">
                            Month :
                        </div>

                        <div class="col-auto my-auto">
                            <select class="form-control form-select me-2" aria-label="Default select example" name="month">
                                <option {{ $month == 1 ? "selected" : "" }} value="1">January</option>
                                <option {{ $month == 2 ? "selected" : "" }} value="2">February</option>
                                <option {{ $month == 3 ? "selected" : "" }} value="3">March</option>
                                <option {{ $month == 4 ? "selected" : "" }} value="4">April</option>
                                <option {{ $month == 5 ? "selected" : "" }} value="5">May</option>
                                <option {{ $month == 6 ? "selected" : "" }} value="6">June</option>
                                <option {{ $month == 7 ? "selected" : "" }} value="7">July</option>
                                <option {{ $month == 8 ? "selected" : "" }} value="8">August</option>
                                <option {{ $month == 9 ? "selected" : "" }} value="9">September</option>
                                <option {{ $month == 10 ? "selected" : "" }} value="10">October</option>
                                <option {{ $month == 11 ? "selected" : "" }} value="11">November</option>
                                <option {{ $month == 12 ? "selected" : "" }} value="12">December</option>
                            </select>
                        </div>

                        <div class="col-auto my-auto">
                            Members :
                        </div>

                        <div class="col-auto my-auto">
                            <select class="form-control form-select me-2" aria-label="Default select example" name="member_id">
                                <option value="0">All</option>
                                @foreach($members_list as $member) 
                                    <option {{ $member_id == $member->id ? "selected" : "" }} value="{{ $member->id }}">{{ $member->member_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <input type="text" name="transaction_type" value="{{ $transaction_type}}" hidden>
                        <input type="text" name="tab" value="PD" hidden>

                        <div class="col-auto my-auto">
                            Inventory Loc :
                        </div>

                        <div class="col-auto my-auto">
                            <select class="form-control form-select me-2" aria-label="Default select example" name="inventory_location">
                                <option {{ $inventory_location == '' ? "selected" : "" }} value="">ALL</option>
                                <option {{ $inventory_location == 'ZPC' ? "selected" : "" }} value="ZPC">ZPC</option>
                                <option {{ $inventory_location == 'OCP' ? "selected" : "" }} value="OCP">OCP</option>
                            </select>
                        </div>
                    
                        <div class="col-auto my-auto">
                            <button type="submit" class="btn btn-outline-success mr-auto fw-bold">Search Filter</button>
                        </div>
                    </form>
                        <div class="col">
                            <div class="d-flex justify-content-end">
                                <form method="GET" action="{{ route('reports-generate') }}">
                                    <input type="text" name="year" value="{{ $year }}" hidden>
                                    <input type="text" name="month" value="{{ $month }}" hidden>
                                    <input type="text" name="member_id" value="{{ $member_id }}" hidden>
                                    <input type="text" name="inventory_location" value="{{ $inventory_location }}" hidden>
                                    <button href="{{ route('reports-generate') }}" class="btn btn-primary mr-auto fw-bold">Generate Report</a>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <!-- EndTop Forms Filter Selection -->

                    <!-- Issuance row -->
                    @if($member_id == 0)
                    <div class="d-flex flex-row-reverse">
                        <div class="mt-3 mb-1 rounded border me-2 fw-bold" style="padding-top:10px; padding-bottom:10px;  padding-left:15px; padding-right:15px;">
                            Total Destruction Amount: {{ $total_destruction_amount }}
                        </div>
                        <div class="mt-3 mb-1 rounded border me-2 fw-bold" style="padding-top:10px; padding-bottom:10px;  padding-left:15px; padding-right:15px;">
                            Total Destruction Quantity: {{ $total_destruction_quantity }}
                        </div>
                    </div>
                    @endif
                    <!-- End Issuance row -->

                    <!-- Items -->
                    @foreach($members as $member)
                        <div class="members-title mt-3 mb-1 rounded">
                            {{ $month_name }} {{ $year }} : {{ $member->member_name }} : Product Destruction
                        </div>
                        <div class="table-responsive">
                            <table class="table" style="width:100%">
                                <thead class="theader">
                                    <tr>
                                        <th>No</th>
                                        <th>Product Code</th>
                                        <th>Product Name</th>
                                        <th>Beneficiary</th>
                                        <th>Lot No</th>
                                        <th>Quantity</th>
                                        <th>Trade Price</th>
                                        <th>Destruction</th>
                                        <th>Destruction Amount</th>
                                        <th>Reference</th>
                                        <th>Transaction Date</th>
                                        <th>Inventory Loc</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @php
                                $transaction_reports = \App\TransactionReport::where('member_id', $member->id)
                                ->where('year', $year)
                                ->where('month', $month)
                                ->where('inventory_location','LIKE','%'.$inventory_location.'%')
                                ->where('transaction_type', 'DES')
                                ->select('id','member_id','beneficiary_id','destruction_id','month','year',
                                'destruction_no','product_code','product_name','lot_no','transaction_type',
                                'quantity','unit_cost','destruction_quantity','destruction_amount','expiry_date','created_at','inventory_location')->get();

                                $transaction_report_total_quantity = 0;
                                $transaction_report_total_amount = 0;

                                if($transaction_reports->count() != 0) {
                                    foreach($transaction_reports as $transaction_report) {

                                        $transaction_report_total_quantity += $transaction_report->destruction_quantity;
                                        $transaction_report_total_amount += $transaction_report->destruction_amount;

                                        $quantity = number_format($transaction_report->quantity);

                                        $beneficiary = \App\Beneficiary::where('id', $transaction_report->beneficiary_id)
                                        ->select('name')
                                        ->first();

                                        $destruction = \App\Destruction::where('id', $transaction_report->destruction_id)
                                        ->select('pdrf_no')
                                        ->first();

                                        $destructionNo = $transaction_report->destruction_no;
                                        $pdrfNo = $destruction->pdrf_no;

                                        $reference = $destructionNo."/PDRF: ".$pdrfNo;
            
                                        echo"<tr>";
                                            echo"<td>$transaction_report->id</td>";
                                            echo"<td>$transaction_report->product_code</td>";
                                            echo"<td>$transaction_report->product_name</td>";
                                            echo"<td>$beneficiary->name</td>";
                                            echo"<td>$transaction_report->lot_no</td>";
                                            echo"<td>$quantity</td>";
                                            echo"<td>$transaction_report->unit_cost</td>";
                                            echo"<td>$transaction_report->destruction_quantity</td>";
                                            echo"<td>$transaction_report->destruction_amount</td>";
                                            echo"<td>$reference</td>";
                                            echo"<td>$transaction_report->created_at</td>";
                                            echo"<td>$transaction_report->inventory_location</td>";
                                        echo"</tr>";
                                    }
                                } else {
                                    echo
                                    '<tr class="tableNoRecord">
                                        <td colspan="12" align="center">No Record Found</td>
                                    </tr>';
                                }
                                @endphp
                                <tr class="tableRecordStat">
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td>Total Destruction</td>
                                    <td class="tableRecordStatAmount">{{ number_format($transaction_report_total_quantity) }}</td>
                                    <td class="tableRecordStatAmount">Php {{ number_format($transaction_report_total_amount,2) }}</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tbody>
                            </table>
                        </div>
                    @endforeach

                    <!-- End Items -->
                </div>
                <!-- End Product Destruction Tab -->

                <!-- Summary Tab -->
                <div class="tab-pane fade {{ $tab == 'SR' ? 'show active' : '' }}" id="summary" role="tabpanel" aria-labelledby="contact-tab">
                    <!-- Top Forms Filter Selection -->
                    <form method="GET" action="{{ route('filter-reports-list') }}">
                    <div class="row mt-3">
                        <div class="col-auto my-auto">
                            Year :
                        </div>
                        <div class="col-auto my-auto">
                            <select class="form-control form-select me-2" aria-label="Default select example" name="year">
                                <option {{ $year == 2022 ? "selected" : "" }} value="2022">2022</option>
                                <option {{ $year == 2023 ? "selected" : "" }} value="2023">2023</option>
                                <option {{ $year == 2024 ? "selected" : "" }} value="2024">2024</option>
                                <option {{ $year == 2025 ? "selected" : "" }} value="2025">2025</option>
                                <option {{ $year == 2026 ? "selected" : "" }} value="2026">2026</option>
                            </select>
                        </div>
                        <div class="col-auto my-auto">
                            Month :
                        </div>
                        <div class="col-auto my-auto">
                            <select class="form-control form-select me-2" aria-label="Default select example" name="month">
                                <option {{ $month == 1 ? "selected" : "" }} value="1">January</option>
                                <option {{ $month == 2 ? "selected" : "" }} value="2">February</option>
                                <option {{ $month == 3 ? "selected" : "" }} value="3">March</option>
                                <option {{ $month == 4 ? "selected" : "" }} value="4">April</option>
                                <option {{ $month == 5 ? "selected" : "" }} value="5">May</option>
                                <option {{ $month == 6 ? "selected" : "" }} value="6">June</option>
                                <option {{ $month == 7 ? "selected" : "" }} value="7">July</option>
                                <option {{ $month == 8 ? "selected" : "" }} value="8">August</option>
                                <option {{ $month == 9 ? "selected" : "" }} value="9">September</option>
                                <option {{ $month == 10 ? "selected" : "" }} value="10">October</option>
                                <option {{ $month == 11 ? "selected" : "" }} value="11">November</option>
                                <option {{ $month == 12 ? "selected" : "" }} value="12">December</option>
                            </select>
                        </div>

                        <div class="col-auto my-auto">
                            Members :
                        </div>

                        <div class="col-auto my-auto">
                            <select class="form-control form-select me-2" aria-label="Default select example" name="member_id">
                                <option value="0">All</option>
                                @foreach($members_list as $member) 
                                    <option {{ $member_id == $member->id ? "selected" : "" }} value="{{ $member->id }}">{{ $member->member_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <input type="text" name="transaction_type" value="{{ $transaction_type}}" hidden>
                        <input type="text" name="tab" value="SR" hidden>

                        <div class="col-auto my-auto">
                            <button type="submit" class="btn btn-outline-success mr-auto fw-bold">Search Filter</button>
                        </div>
                    </form>
                        <div class="col">
                            <div class="d-flex justify-content-end">
                                <form method="GET" action="{{ route('reports-generate') }}">
                                    <input type="text" name="year" value="{{ $year }}" hidden>
                                    <input type="text" name="month" value="{{ $month }}" hidden>
                                    <input type="text" name="member_id" value="{{ $member_id }}" hidden>
                                    <input type="text" name="inventory_location" value="{{ $inventory_location }}" hidden>
                                    <button href="{{ route('reports-generate') }}" class="btn btn-primary mr-auto fw-bold">Generate Report</a>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <!-- EndTop Forms Filter Selection -->

                    <!-- Issuance row -->
                    @if($member_id == 0)
                    <div class="d-flex flex-row-reverse">
                        <div class="mt-3 mb-1 rounded border fw-bold" style="padding-top:10px; padding-bottom:10px;  padding-left:15px; padding-right:15px;">
                            Total Ending Balance : PHP {{ number_format($total_qty_movements,2) }}
                        </div>
                        <div class="mt-3 mb-1 rounded border me-2 fw-bold" style="padding-top:10px; padding-bottom:10px;  padding-left:15px; padding-right:15px;">
                            Total Quantity Ending Balance : {{ number_format($total_ending_balance_qty) }}
                        </div>
                        <div class="mt-3 mb-1 rounded border me-2 fw-bold" style="padding-top:10px; padding-bottom:10px;  padding-left:15px; padding-right:15px;">
                            Total Quantity Movement: {{ number_format($total_ending_balance_value) }}
                        </div>
                    </div>
                    @endif
                    <!-- End Issuance row -->

                    <!-- Items -->
                    @foreach($members as $member)
                        <div class="members-title mt-3 mb-1 rounded">
                            {{ $month_name }} {{ $year }} : {{ $member->member_name }} : Summary
                        </div>

                        <div class="table-responsive">
                            <table class="table" style="width:100%">
                                <thead class="theader">
                                    <tr>
                                        <th scope="col">No.</th>
                                        <th scope="col">Product Code</th>
                                        <th scope="col">Product Name</th>
                                        <th scope="col">Lot No.</th>
                                        <th scope="col">Trade Price</th>
                                        <th scope="col">Quantity Movements</th>
                                        <th scope="col">Quantity Ending Balance</th>
                                        <th scope="col">Value of Ending Balance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <tbody>
                                    @php
                                    $summaries = \App\Summary::where('member_id', $member->id)
                                    ->where('year', $year)
                                    ->where('month', $month)
                                    ->select('id','product_code','product_name','lot_no','unit_cost','movements_quantity','ending_balance_quantity','ending_balance_value')
                                    ->get();

                                    $summary_total_qty_movements = 0;
                                    $summary_total_ending_balance_qty = 0;
                                    $summary_total_ending_balance_value = 0;

                                    if($summaries->count() != 0) {
                                        foreach($summaries as $summary) {

                                            $summary_total_qty_movements += $summary->movements_quantity;
                                            $summary_total_ending_balance_qty += $summary->ending_balance_quantity;
                                            $summary_total_ending_balance_value += $summary->ending_balance_value;

                                            echo 
                                            "<tr>
                                                <td>$summary->id</td>
                                                <td>$summary->product_code</td>
                                                <td>$summary->product_name</td>
                                                <td>$summary->lot_no</td>
                                                <td>$summary->unit_cost</td>
                                                <td>$summary->movements_quantity</td>
                                                <td>$summary->ending_balance_quantity</td>
                                                <td>$summary->ending_balance_value</td>
                                            </tr>";
                                        }
                                    } else {
                                        echo
                                        '<tr class="tableNoRecord">
                                            <td colspan="11" align="center">No Record Found</td>
                                        </tr>';
                                    }
                                    @endphp
                                    <tr class="tableRecordStat">
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td>Total</td>
                                        <td class="tableRecordStatAmount">{{ number_format($summary_total_qty_movements) }}</td>
                                        <td class="tableRecordStatAmount">{{ number_format($summary_total_ending_balance_qty) }}</td>
                                        <td class="tableRecordStatAmount">Php {{ number_format($summary_total_ending_balance_value,2) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    @endforeach
                    <!-- End Items -->
                </div>
                <!-- End Summary Tab -->

            </div>

        </div>
    </div>
</div>
@endsection

@section('custom-js')

@endsection
