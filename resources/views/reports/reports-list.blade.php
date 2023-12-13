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
                    <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Transaction Reports</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Product Destructions</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab" aria-controls="contact" aria-selected="false">Summary</button>
                </li>
            </ul>

            <div class="tab-content" id="myTabContent">
                <!-- Transaction Tab  -->
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <!-- Top Forms Filter Selection -->
                    <form method="GET" action="{{ route('filter-reports-list') }}">
                    <div class="row mt-3">
                        <div class="col-auto my-auto">
                            Year :
                        </div>
                        <div class="col-auto my-auto">
                            <select class="form-control form-select me-2" aria-label="Default select example" name="year">
                                <option value="2022">2022</option>
                                <option value="2022">2023</option>
                            </select>
                        </div>

                        <div class="col-auto my-auto">
                            Month :
                        </div>

                        <div class="col-auto my-auto">
                            <select class="form-control form-select me-2" aria-label="Default select example" name="month">
                                <option value="1">January</option>
                                <option value="2">February</option>
                                <option value="3">March</option>
                                <option value="4">April</option>
                                <option value="5">May</option>
                                <option value="6">June</option>
                                <option value="7">July</option>
                                <option value="8">August</option>
                                <option value="9">September</option>
                                <option value="10">October</option>
                                <option value="11">November</option>
                                <option value="12">December</option>
                            </select>
                        </div>

                        <div class="col-auto my-auto">
                            Members :
                        </div>

                        <div class="col-auto my-auto">
                            <select class="form-control form-select me-2" aria-label="Default select example" name="member_id">
                                <option value="0">All</option>
                                @foreach($members as $member) 
                                    <option value="{{ $member->id }}">{{ $member->member_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-auto my-auto">
                            TXN Type :
                        </div>

                        <div class="col-auto my-auto">
                            <select class="form-control form-select me-2" aria-label="Default select example" name="transaction_type">
                                <option value="EXP">Outbound</option>
                                <option value="IMP">Inbound</option>
                                <option value="ADJI">Adjustment-</option>
                                <option value="ADJR">Adjustment+</option>
                            </select>
                        </div>

                        <div class="col my-auto">
                            <div class="d-flex justify-content-end">
                                <button class="btn btn-primary mr-auto fw-bold">Generate Report</button>
                            </div>
                        </div>
                    </div>
                    </form>
                    <!-- EndTop Forms Filter Selection -->

                    <!-- Issuance row -->
                    <div class="d-flex flex-row-reverse">
                        <div class="mt-3 mb-1 rounded border fw-bold" style="padding-top:10px; padding-bottom:10px;  padding-left:15px; padding-right:15px;">
                            Total Receipt Quantity: {{ number_format($total_receipt_quantity) }}
                        </div>
                        <div class="mt-3 mb-1 rounded border me-2 fw-bold" style="padding-top:10px; padding-bottom:10px;  padding-left:15px; padding-right:15px;">
                            Total Receipt Amount: PHP {{ number_format($total_receipt_amount,2) }}
                        </div>
                    </div>
                    <!-- End Issuance row -->
                    @foreach($members as $member)
                    <div class="members-title mt-3 mb-1 rounded">
                        {{ $current_month }} {{ $current_year }} : {{ $member->member_name }} : Export
                    </div>

                    <div class="table-responsive">
                        <table class="table" style="width:100%">
                            <thead class="theader">
                                <tr>
                                    <th scope="col">No.</th>
                                    <th scope="col">Product Code</th>
                                    <th scope="col">Product Name</th>
                                    <th scope="col">Lot No.</th>
                                    <th scope="col">Opening Balance</th>
                                    <th scope="col">Quantity</th>
                                    <th scope="col">Trade Price</th>
                                    <th scope="col">Receipt</th>
                                    <th scope="col">Receipt Amount</th>
                                    <th scope="col">Reference</th>
                                    <th scope="col">Transaction Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $transaction_reports = \App\TransactionReport::where('member_id', $member->id)
                                ->where('transaction_type', 'IMP')
                                ->select('id','product_code','product_name','lot_no','opening_balance_quantity','quantity','unit_cost','receipt_quantity','receipt_amount','job_no','created_at')
                                ->get();

                                $transaction_report_total_quantity = 0;
                                $transaction_report_total_amount = 0;

                                if($transaction_reports->count() != 0) {
                                    foreach($transaction_reports as $transaction_report) {
                                        $transaction_report_total_quantity += $transaction_report->receipt_quantity;
                                        $transaction_report_total_amount += $transaction_report->receipt_amount;

                                        $quantity = number_format($transaction_report->quantity);
                                        $receipt_quantity = number_format($transaction_report->receipt_quantity);
                                        $receipt_amount = number_format($transaction_report->receipt_amount,2);
                                        echo 
                                        "<tr>
                                            <td>$transaction_report->id</td>
                                            <td>$transaction_report->product_code</td>
                                            <td>$transaction_report->product_name</td>
                                            <td>$transaction_report->lot_no</td>
                                            <td>$transaction_report->opening_balance_quantity</td>
                                            <td>$quantity</td>
                                            <td>$transaction_report->unit_cost</td>
                                            <td>$receipt_quantity</td>
                                            <td>$receipt_amount</td>
                                            <td>$transaction_report->job_no</td>
                                            <td>$transaction_report->created_at</td>
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
                                    <td></td>
                                    <td></td>
                                    <td>Total Receipt</td>
                                    <td class="tableRecordStatAmount">{{ number_format($transaction_report_total_quantity) }}</td>
                                    <td class="tableRecordStatAmount">Php {{ number_format($transaction_report_total_amount,2) }}</td>
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
                <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                    <!-- Top Forms Filter Selection -->
                    <div class="row mt-3">
                        <div class="col-auto my-auto">
                            Year :
                        </div>
                        <div class="col-auto my-auto">
                            <select class="form-control form-select me-2" aria-label="Default select example">
                                <option value="1">2022</option>
                            </select>
                        </div>

                        <div class="col-auto my-auto">
                            Month :
                        </div>

                        <div class="col-auto my-auto">
                            <select class="form-control form-select me-2" aria-label="Default select example">
                                <option value="1">January</option>
                            </select>
                        </div>

                        <div class="col-auto my-auto">
                            Members :
                        </div>

                        <div class="col-auto my-auto">
                            <select class="form-control form-select me-2" aria-label="Default select example">
                                <option value="1">Aspen Philippines</option>
                            </select>
                        </div>

                        <div class="col">
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-outline-success mr-auto fw-bold">Generate Report</button>
                            </div>
                        </div>
                    </div>
                    <!-- EndTop Forms Filter Selection -->

                    <!-- Issuance row -->
                    <div class="d-flex flex-row-reverse">
                        <div class="mt-3 mb-1 rounded border fw-bold" style="padding-top:10px; padding-bottom:10px;  padding-left:15px; padding-right:15px;">
                            Total Destruction Amount: PHP 0.00
                        </div>

                        <div class="mt-3 mb-1 rounded border me-2 fw-bold" style="padding-top:10px; padding-bottom:10px;  padding-left:15px; padding-right:15px;">
                            Total Destruction Quantity: 0
                        </div>
                    </div>
                    <!-- End Issuance row -->

                    <!-- Items -->
                    @foreach($members as $member)
                        <div class="members-title mt-3 mb-1 rounded">
                            {{ $current_month }} {{ $current_year }} : {{ $member->member_name }} : Export
                        </div>
                        <div class="table-responsive">
                            <table class="table" style="width:100%">
                                <thead class="theader">
                                    <tr>
                                        <th scope="col">No.</th>
                                        <th scope="col">Product Code</th>
                                        <th scope="col">Product Name</th>
                                        <th scope="col">Lot No.</th>
                                        <th scope="col">Opening Balance</th>
                                        <th scope="col">Quantity</th>
                                        <th scope="col">Trade Price</th>
                                        <th scope="col">Receipt</th>
                                        <th scope="col">Receipt Amount</th>
                                        <th scope="col">Reference</th>
                                        <th scope="col">Transaction Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($transaction_reports as $transaction_report)
                                        @if($transaction_report->member_id == $member->id && $transaction_report->transaction_type == 'DES')
                                            <tr>
                                                <td>{{ $transaction_report->id }}</td>
                                                <td>{{ $transaction_report->product_code }}</td>
                                                <td>{{ $transaction_report->product_name }}</td>
                                                <td>{{ $transaction_report->lot_no }}</td>
                                                <td>{{ $transaction_report->opening_balance_quantity }}</td>
                                                <td>{{ $transaction_report->quantity }}</td>
                                                <td>{{ $transaction_report->unit_cost }}</td>
                                                <td>{{ $transaction_report->receipt }}</td>
                                                <td>{{ $transaction_report->receipt_amount }}</td>
                                                <td>{{ $transaction_report->job_no }}</td>
                                                <td>{{ $transaction_report->created_at }}</td>
                                            </tr>
                                        @else

                                        @endif
                                    @endforeach
                                    <tr class="tableNoRecord">
                                        <td colspan="11" align="center">No Record Found</td>
                                    </tr>
                                    <tr class="tableRecordStat">
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td>Total Receipt</td>
                                        <td class="tableRecordStatAmount">22</td>
                                        <td>Total Donation</td>
                                        <td class="tableRecordStatAmount">Php 4,884.00</td>
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
                <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                    <!-- Top Forms Filter Selection -->
                    <div class="row mt-3">
                        <div class="col-auto my-auto">
                            Year :
                        </div>
                        <div class="col-auto my-auto">
                            <select class="form-control form-select me-2" aria-label="Default select example">
                                <option value="1">2022</option>
                            </select>
                        </div>

                        <div class="col-auto my-auto">
                            Month :
                        </div>

                        <div class="col-auto my-auto">
                            <select class="form-control form-select me-2" aria-label="Default select example">
                                <option value="1">January</option>
                            </select>
                        </div>

                        <div class="col-auto my-auto">
                            Members :
                        </div>

                        <div class="col-auto my-auto">
                            <select class="form-control form-select me-2" aria-label="Default select example">
                                <option value="1">Aspen Philippines</option>
                            </select>
                        </div>
                        <input type="text" name="tab" value="SR" hidden>
                        <div class="col">
                            <div class="d-flex justify-content-end">
                                <button class="btn btn-outline-success mr-auto fw-bold">Generate Report</button>
                            </div>
                        </div>
                    </div>
                    <!-- EndTop Forms Filter Selection -->

                    <!-- Issuance row -->
                    <div class="d-flex flex-row-reverse">
                        <div class="mt-3 mb-1 rounded border fw-bold" style="padding-top:10px; padding-bottom:10px;  padding-left:15px; padding-right:15px;">
                            Total Ending Balance :PHP 0.00
                        </div>
                        <div class="mt-3 mb-1 rounded border me-2 fw-bold" style="padding-top:10px; padding-bottom:10px;  padding-left:15px; padding-right:15px;">
                            Total Quantity Ending Balance : 0
                        </div>
                        <div class="mt-3 mb-1 rounded border me-2 fw-bold" style="padding-top:10px; padding-bottom:10px;  padding-left:15px; padding-right:15px;">
                            Total Quantity Movement: 0
                        </div>
                    </div>
                    <!-- End Issuance row -->

                    <!-- Items -->
                    @foreach($members as $member)
                        <div class="members-title mt-3 mb-1 rounded">
                            {{ $current_month }} {{ $current_year }} : {{ $member->member_name }} : Export
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
                                @php
                                $summaries = \App\TransactionReport::where('member_id', $member->id)
                                ->where('transaction_type', 'IMP')
                                ->select('id','product_code','product_name','lot_no','opening_balance_quantity','quantity','unit_cost','receipt_quantity','receipt_amount','job_no','created_at')
                                ->get();

                                $transaction_report_total_quantity = 0;
                                $transaction_report_total_amount = 0;

                                if($transaction_reports->count() != 0) {
                                    foreach($transaction_reports as $transaction_report) {
                                        $transaction_report_total_quantity += $transaction_report->receipt_quantity;
                                        $transaction_report_total_amount += $transaction_report->receipt_amount;

                                        $quantity = number_format($transaction_report->quantity);
                                        $receipt_quantity = number_format($transaction_report->receipt_quantity);
                                        $receipt_amount = number_format($transaction_report->receipt_amount,2);
                                        echo 
                                        "<tr>
                                            <td>$transaction_report->id</td>
                                            <td>$transaction_report->product_code</td>
                                            <td>$transaction_report->product_name</td>
                                            <td>$transaction_report->lot_no</td>
                                            <td>$transaction_report->opening_balance_quantity</td>
                                            <td>$quantity</td>
                                            <td>$transaction_report->unit_cost</td>
                                            <td>$receipt_quantity</td>
                                            <td>$receipt_amount</td>
                                            <td>$transaction_report->job_no</td>
                                            <td>$transaction_report->created_at</td>
                                        </tr>";
                                    }
                                } else {
                                    echo
                                    '<tr class="tableNoRecord">
                                        <td colspan="11" align="center">No Record Found</td>
                                    </tr>';
                                }

                                @endphp
                                    @foreach($summaries as $summary)
                                        @if($summary->member_id == $member->id)
                                            <tr>
                                                <td>{{ $summary->id }}</td>
                                                <td>{{ $summary->product_code }}</td>
                                                <td>{{ $summary->product_name }}</td>
                                                <td>{{ $summary->lot_no }}</td>
                                                <td>{{ $summary->unit_cost }}</td>
                                                <td>{{ $summary->movements_quantity }}</td>
                                                <td>{{ $summary->ending_balance_quantity }}</td>
                                                <td>{{ $summary->ending_balance_value }}</td>
                                            </tr>
                                        @else

                                        @endif
                                    @endforeach

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
    <script type="text/javascript" src="{{ asset('js/DataTables/datatables.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('#cfs-table').dataTable({
            order: [[ 0, "desc" ]],
            searching:true,
            paging:true,
            info:true,
            scrollX:true,
            scrollCollapse:true,
            sort:true,
            });
        });
    </script>
@endsection
