@extends('layouts.app')

@section('custom-css')
<link rel="stylesheet" type="text/css" href="{{ asset('css/product-donation.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('js/modules/datepicker/datepicker.css') }} "/>
@endsection

@section('content')

<div class="bg-heading">

    <div class="d-flex justify-content-between p-0">
        <h4 class="px-4 py-3">Product Destruction Details</h4>
    </div>
</div>

<div class="bg-initial-details mt-2 px-4 py-4">
    <div class="row">
        <div class="col-md-10">
            <!-- Tab Buttons -->
            <ul class="nav nav-tabs mt-3" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Product Destruction Info</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Destructed Products</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab" aria-controls="contact" aria-selected="false">Documents</button>
                </li>
            </ul>

            <div class="tab-content" id="myTabContent">
                <!-- Contribution Info Tab  -->
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <h5 class="donation-titles mt-4">Product Destruction Details</h5>
                    <!-- Contribution Details Forms -->
                    <div class="mt-3">                       
                        <div class="row">
                            <div class="col-md-6 mt-2">
                                <div class="row">
                                    <label for="" class="col-lg-4 col-form-label fw-bold">Status :</label>
                                    <div class="col-lg-8">
                                        <div class="fw-bold text-success">
                                            @if ($destruction->status == 1)
                                                <div class="fw-bold text-success">
                                                    Destruction Registered Successfully!
                                                </div>
                                            @elseif ($destruction->status == 2)
                                                <div class="fw-bold text-danger">
                                                    Destruction Rejected!
                                                </div>
                                            @elseif ($destruction->status == 3)
                                                <div class="fw-bold text-success">
                                                    Successful Destruction!
                                                </div>
                                            @else
                                                No Status
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mt-2">
                                <div class="row">
                                    <label for="" class="col-lg-4 col-form-label fw-bold">Destruction No. :</label>
                                    <div class="col-lg-8">
                                        {{ $destruction->destruction_no }}
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="row">
                                    <label for="" class="col-lg-4 col-form-label fw-bold">PDRF No. :</label>
                                    <div class="col-lg-8">
                                        {{ $destruction->destruction_no }}
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-md-6 mt-2">
                                <div class="row">
                                    <label for="" class="col-lg-4 col-form-label fw-bold">Beneficiary :</label>
                                    <div class="col-lg-8">
                                        {{ $beneficiary_name }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <form method="POST" class="" action="{{ route('destruction-status-update', ['destruction_id' => $destruction->id]) }}">
                        @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <label for="" class="col-lg-4 col-form-label fw-bold">Notice To :</label>
                                        <div class="col-lg-8">
                                            <select class="form-control form-select " aria-label="Default select example" name="notice_to">
                                                <option value="Zuellig Pharma Corp." {{ $destruction->notice_to == 'Zuellig Pharma Corp.' ? 'selected' : ''}}>Zuellig Pharma Corp.</option>
                                                <option value="Metro Drug" {{ $destruction->notice_to == 'Metro Drug' ? 'selected' : ''}}>Metro Drug</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Pick-up Details -->
                            <h5 class="donation-titles mt-4">Pick-Up Details</h5>

                            <div class="row">
                                <div class="col-md-6 mt-2">
                                    <div class="row">
                                        <label for="" class="col-lg-4 col-form-label fw-bold">Pick-Up Address:</label>
                                        <div class="col-lg-8">
                                            <input type="text" class="form-control" name="pickup_address" id="pickup_address" placeholder="Contact Person Name" value="{{ $destruction->pickup_address }}" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--End Pick-up Details -->

                            <div class="row">
                                <div class="col-md-6 mt-2">
                                    <div class="row">
                                        <label for="pickup_contact_person" class="col-lg-4 col-form-label fw-bold">Contact Person :</label>
                                        <div class="col-lg-8">
                                            <input type="text" class="form-control" name="pickup_contact_person" id="pickup_contact_person" placeholder="Contact Person Name" value="{{ $destruction->pickup_contact_person }}" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 mt-2">
                                    <div class="row">
                                        <label for="" class="col-lg-4 col-form-label fw-bold">Contact No. :</label>
                                        <div class="col-lg-8">
                                            <input type="text" class="form-control" name="pickup_contact_no" id="pickup_contact_no" placeholder="Pickup Contact No." value="{{ $destruction->pickup_contact_no }}" required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mt-2">
                                    <div class="row">
                                        <label for="pickup_date" class="col-lg-4 col-form-label fw-bold">Pick-up Date :</label>
                                        <div class="col-lg-8">
                                            <div class="input-group mb-3">
                                                <input type="text" class="form-control" name="pickup_date" id="pickup_date" value="{{ date('m/d/Y', strtotime($destruction->pickup_date)) }}" onkeydown="return false" required>
                                                <span class="input-group-text"><i class="fa-solid fa-calendar text-main-color"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 mt-2">
                                    <div class="row">
                                        <label for="other_pickup_instructions" class="col-lg-4 col-form-label fw-bold">Pick-up Instructions :</label>
                                        <div class="col-lg-8">
                                            <input type="text" class="form-control" name="other_pickup_instructions" id="other_pickup_instructions" placeholder="other_pickup_instructions" value="{{ $destruction->other_pickup_instructions }}" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Delivery Details -->
                            <h5 class="donation-titles mt-4">Delivery Details</h5>

                            <div class="row">
                                <div class="col-md-6 mt-2">
                                    <div class="row">
                                        <label for="" class="col-lg-4 col-form-label fw-bold">Contact Person :</label>
                                        <div class="col-lg-8">
                                            <input type="text" class="form-control" name="delivery_contact_person" id="delivery_contact_person" placeholder="delivery_contact_person" value="{{ $destruction->delivery_contact_person }}" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 mt-2">
                                    <div class="row">
                                        <label for="delivery_address" class="col-lg-4 col-form-label fw-bold">Delivery Address :</label>
                                        <div class="col-lg-8">
                                            <input type="text" class="form-control" name="delivery_address" id="delivery_address" placeholder="delivery_address" value="{{ $destruction->delivery_address }}" required>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-md-6 mt-2">
                                    <div class="row">
                                        <label for="" class="col-lg-4 col-form-label fw-bold">Authorized Recipient :</label>
                                        <div class="col-lg-8">
                                        <input type="text" class="form-control" name="delivery_authorized_recipient" id="delivery_authorized_recipient" placeholder="delivery_authorized_recipient" value="{{ $destruction->delivery_authorized_recipient }}" required>

                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mt-2">
                                    <div class="row">
                                        <label for="delivery_contact_no" class="col-lg-4 col-form-label fw-bold">Contact No. :</label>
                                        <div class="col-lg-8">
                                            <input type="text" class="form-control" name="delivery_contact_no" id="delivery_contact_no" placeholder="delivery_contact_no" value="{{ $destruction->delivery_contact_no }}" required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mt-2">
                                    <div class="row">
                                        <label for="delivery_date" class="col-lg-4 col-form-label fw-bold">Delivery Date :</label>
                                        <div class="col-lg-8">
                                            <div class="input-group mb-3">
                                                <input type="text" class="form-control" name="delivery_date" id="delivery_date" value="{{ date('m/d/Y', strtotime($destruction->delivery_date)) }}" onkeydown="return false" required>
                                                <span class="input-group-text"><i class="fa-solid fa-calendar text-main-color"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mt-2">
                                    <div class="row">
                                        <label for="" class="col-lg-4 col-form-label fw-bold">Delivery Instructions :</label>
                                        <div class="col-lg-8">
                                            <input type="text" class="form-control" name="other_delivery_instructions" id="other_delivery_instructions" placeholder="other_delivery_instructions" value="{{ $destruction->other_delivery_instructions }}" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <!-- Approval Status Input Radio Buttons Approve - Reject -->
                        @if($destruction->status == 1)
                        <h5 class="donation-titles mt-4">Approval</h5>
                        <hr>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status" id="status_3" value="3" onclick="hideReasonInput(this.value)">
                                <label class="form-check-label" for="status_3">Approve</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status" id="status_2" value="2" onclick="showReasonInput(this.value)">
                                <label class="form-check-label" for="status_2">Reject</label>
                            </div>

                            <div class="row align-items-center mt-3" id="reasons_rejected_destruction_div" style="display:none;">
                                <div class="col-auto">
                                    <label for="" class="col-form-label fw-bold">Reasons :</label>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" id="reasons_rejected_destruction" name="reasons_rejected_destruction" class="form-control" aria-describedby="passwordHelpInline">
                                </div>
                            </div>

                            <div class="d-flex flex-row-reverse mt-3">
                                <button type="submit" class="btn btn-primary">Verify</button>
                                <a type="button" class="btn btn-outline-secondary me-2">Go Back</a>
                            </div>
                        </form>
                        @endif
                        <!-- Approval Status Input-->
                    </div>
                </div>
                <!-- End Contribution Info Tab  -->

                <!-- Products Tab -->
                <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">

                    <h5 class="donation-titles mt-4">Donation Details</h5>
                    <table class="table mt-3">
                        <thead class="theader">
                            <tr>
                                <th scope="col">No.</th>
                                <th scope="col">Product Name</th>
                                <th scope="col">Lot No.</th>
                                <th scope="col">Quantity</th>
                                <th scope="col">Unit Cost</th>
                                <th scope="col">Total</th>
                                <th scope="col">Expiry Date</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!$destructed_products->isEmpty())
                                @foreach ($destructed_products as $donation)
                                    @if($donation->status == 1)
                                    <tr>
                                        <td>{{ $donation->id }}</td>
                                        <td>{{ $donation->product_name }}</td>
                                        <td>{{ $donation->lot_no }}</td>
                                        <td>{{ $donation->quantity }}</td>
                                        <td>{{ $donation->unit_cost }}</td>
                                        <td>{{ $donation->total }}</td>
                                        <td>{{ date('F, d Y', strtotime($donation->expiry_date)) }}</td>
                                        <td>
                                            <a href="{{ route('destruction-edit-view', ['destructed_product_id' => $donation->id]) }}" class="btn tt cfs-edit-btn" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Edit">
                                                <i class="fas fa-edit cfs-edit-ic text-secondary"></i>
                                            </a>
                                            <button data-bs-toggle="modal" data-id="{{ $donation->id }}" data-bs-target="#deleteModal" class="open-delete-modal btn tt cfs-edit-btn" title="Cancel">
                                                <i class="fas fa-trash-alt cfs-edit-ic text-secondary"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endif
                                @endforeach
                            @else
                                <tr class="tableNoRecord">
                                    <td colspan="8" align="center">No Record Found</td>
                                </tr>
                            @endif
                            <tr class="tableRecordStat">
                                <td></td>
                                <td></td>
                                <td>Total Quantity</td>
                                <td class="tableRecordStatAmount">{{ $total_quantity }}</td>
                                <td>Total Donation</td>
                                <td class="tableRecordStatAmount">P{{ number_format($total_amount, 2) }}</td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>

                    <h5 class="donation-titles mt-2">Cancelled Items</h5>
                    <!-- Promats Table -->
                    <table class="table mt-3">
                        <thead class="theader">
                            <tr>
                                <th scope="col">No.</th>
                                <th scope="col">Product Name</th>
                                <th scope="col">Lot No.</th>
                                <th scope="col">Quantity</th>
                                <th scope="col">Unit Cost</th>
                                <th scope="col">Total</th>
                                <th scope="col">Expiry Date</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!$destructed_products->isEmpty())
                                @foreach ($destructed_products as $donation)
                                    @if($donation->status != 1)
                                    <tr>
                                        <td>{{ $donation->id }}</td>
                                        <td>{{ $donation->product_name }}</td>
                                        <td>{{ $donation->lot_no }}</td>
                                        <td>{{ $donation->quantity }}</td>
                                        <td>{{ $donation->unit_cost }}</td>
                                        <td>{{ $donation->total }}</td>
                                        <td>{{ date('F, d Y', strtotime($donation->expiry_date)) }}</td>
                                    </tr>
                                    @endif
                                @endforeach
                            @else
                                <tr class="tableNoRecord">
                                    <td colspan="8" align="center">No Record Found</td>
                                </tr>
                            @endif
                            <tr class="tableRecordStat">
                                <td></td>
                                <td></td>
                                <td>Total Quantity</td>
                                <td class="tableRecordStatAmount">{{ $cancelled_total_quantity }}</td>
                                <td>Total Donation</td>
                                <td class="tableRecordStatAmount">P{{ number_format($cancelled_total_donation,2) }}</td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Documents Tab -->
                <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                    <h5 class="donation-titles mt-4">Documents</h5>
                    <table class="table mt-3">
                        <thead class="theader">
                            <tr>
                                <th scope="col">No.</th>
                                <th scope="col">Document Type</th>
                                <th scope="col">Name</th>
                                <th scope="col">Date Created</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($documents as $item) 
                                <tr class="tableRecordStat">
                                    <td>1</td>
                                    <td>{{$item->type}}</td>
                                    <td>{{$item->name}}</td>
                                    <td>{{date('F, d Y', strtotime($item->created_at))}}</td>
                                    <td>
                                        <a href="{{$item->directory}}.pdf" target="_blank" class="btn  cfs-edit-btn" title="Open/View">
                                            <i class="fas fa-folder-open cfs-edit-ic text-secondary"></i>
                                        </a>
                                        <a href="{{ route('download-pdf', ['id' => $item->id]) }}" class="btn  cfs-edit-btn" title="Download">
                                            <i class="fas fa-download cfs-edit-ic text-secondary"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
        <!-- Statistics -->
        <div class="col-md-2">
            <div class="card shadow stats-card">
                <div class="card-header stats-card-header">
                    Statistics
                </div>
                <div class="card-body stats-card-body">
                    <div class="stats-head-title">
                        <i class="fas fa-check-square"></i>
                        Progress Track
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-8">
                            <div class="stats-title">
                                PDRF
                            </div>
                        </div>
                        <div class="col">
                            <i class="fa-solid fa-circle-check status-green"></i>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-8">
                            <div class="stats-title">
                                Verify PDRF
                            </div>
                        </div>
                        <div class="col">
                            <!-- <i class="fa-solid fa-circle-check status-green"></i> -->
                            @if($destruction->status != 2 && $destruction->status >= 3)
                                <!-- Green Check -->
                                <i class="fa-solid fa-circle-check status-green"></i>
                            @elseif($destruction->status == 2)
                                <!-- Red X -->
                                <i class="fas fa-times-circle text-danger"></i>
                            @elseif($destruction->status >= 1)
                                <!-- Gray circle -->
                                <i class="fas fa-circle status-gray"></i>
                            @endif
                            <!-- <i class="fas fa-circle status-gray"></i> -->
                        </div>
                    </div>

                    <hr>

                    <div class="stats-head-title">
                        <i class="fa-solid fa-box"></i>
                        Products
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-8">
                            <div class="stats-title">
                                Medicine / Vaccine
                            </div>
                        </div>
                        <div class="col">
                            <div class="stats-values">
                                {{ $medicine_count }}
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-8">
                            <div class="stats-title">
                                Promats
                            </div>
                        </div>
                        <div class="col">
                            <div class="stats-values">
                                {{ $promats_count }}
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-8">
                            <div class="stats-title">
                                Total Products
                            </div>
                        </div>
                        <div class="col">
                            <div class="stats-values">
                                {{ $total_count }}
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="stats-head-title">
                    <i class="fa-solid fa-wallet"></i>
                    Amount
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col">
                            <div class="stats-title">
                                Medicine Amount
                            </div>
                        </div>
                        <div class="col">
                            <div class="stats-values">
                                P{{ number_format($destruction->total_medicine,2) }}
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col">
                            <div class="stats-title">
                                Promats Amount
                            </div>
                        </div>
                        <div class="col">
                            <div class="stats-values">
                                P{{ number_format($destruction->total_promats,2) }}
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col">
                            <div class="stats-title">
                                Total Donation
                            </div>
                        </div>
                        <div class="col">
                            <div class="stats-values">
                                P{{ number_format($destruction->total_allocated_products,2) }}
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- Cancel Modal -->
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                   Are you sure you want to delete this record?
                </div>
                <div class="modal-footer">
                    <form method="POST" action="{{ route('destruction-cancel-product') }}">
                        @csrf
                        <input name="destruction_product_id" id="donation_id" value="" hidden/>
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Confirm</button>
                    </form>
                </div>
                </div>
            </div>
        </div>
        <!-- End Cancel Modal -->
    </div>
</div>
@endsection

@section('custom-js')
<script src="{{asset('js/modules/datepicker/datepicker.js')}} "></script>
<script>
var $pickup_date = $('#pickup_date');
var $delivery_date = $('#delivery_date');

$(document).ready(function () {
    $pickup_date.datepicker({
        autoHide: true,
    });
    $delivery_date.datepicker({
        autoHide: true,
    });
});

$(document).on("click", ".open-delete-modal", function () {
     var donationId = $(this).data('id');
     $("form #donation_id").val( donationId );
});

var reasons_rejected_destruction_div = document.getElementById("reasons_rejected_destruction_div");

function showReasonInput(value) {
    if(value == 2) {
        reasons_rejected_destruction_div.style.display = "flex";
    }
}

function hideReasonInput(value) {
    if(value == 3) {
        reasons_rejected_destruction_div.style.display = "none";
    }
}
</script>
@endsection
