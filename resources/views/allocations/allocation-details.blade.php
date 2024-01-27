@extends('layouts.app')

@section('custom-css')
<link rel="stylesheet" type="text/css" href="{{ asset('css/product-donation.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('js/modules/datepicker/datepicker.css') }} "/>
@endsection

@section('content')

<div class="bg-heading">
    <h4 class="px-4 py-3">Allocation Details</h4>
</div>

<div class="bg-initial-details mt-2 px-4 py-4">
    <div class="row">
        <div class="col-md-10">
            <!-- Tab Buttons -->
            <ul class="nav nav-tabs mt-3" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Allocation Info</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Allocation Products</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab" aria-controls="contact" aria-selected="false">Documents</button>
                </li>
            </ul>

            <div class="tab-content" id="myTabContent">
                <!-- Allocation Details Tab  -->
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <h5 class="donation-titles mt-4">Allocation Details</h5>
                    <!-- Allocation Details Forms -->
                    <div class="mt-3">                       
                        <div class="row">
                            <div class="col-md-6 mt-2">
                                <div class="row">
                                    <label for="" class="col-lg-4 col-form-label fw-bold">Status :</label>
                                    <div class="col-lg-8">
                                            @if ($allocation->status == 1)
                                                <div class="fw-bold text-success">
                                                    Allocation Registered Successfully!
                                                </div>
                                            @elseif ($allocation->status == 2)
                                                <div class="fw-bold text-danger">
                                                    Allocation Rejected!
                                                </div>
                                            @elseif ($allocation->status == 3)
                                                <div class="fw-bold text-success">
                                                    Allocation Approved By Staff
                                                </div>
                                            @elseif ($allocation->status == 4)
                                                <div class="fw-bold text-danger">
                                                    Terms And Conditions Rejected
                                                </div>
                                            @elseif ($allocation->status == 5)
                                                <div class="fw-bold text-success">
                                                    Terms And Conditions Approved
                                                </div>
                                            @elseif ($allocation->status == 7)
                                                <div class="fw-bold text-success">
                                                    Successful Allocation!
                                                </div>
                                            @else
                                                No Status
                                            @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <form class="needs-validation" novalidate method="POST" enctype="multipart/form-data" class="mt-3" action="{{ route('allocation-status-update', ['allocation_id' => $allocation->id]) }}" id="allocation_form">
                        @csrf
                            <div class="row">
                                <div class="col-md-6 mt-2">
                                    <div class="row">
                                        <label for="" class="col-lg-4 col-form-label fw-bold">Allocation No. :</label>
                                        <div class="col-lg-8">
                                            {{ $allocation->allocation_no }}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="row">
                                        <label for="" class="col-lg-4 col-form-label fw-bold">DNA No. :</label>
                                        <div class="col-lg-8">
                                            {{ $allocation->dna_no }}
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

                            <div class="row">
                                <div class="col-md-6 mt-2">
                                    <div class="row">
                                        <label for="" class="col-lg-4 col-form-label fw-bold">Notice To :</label>
                                        <div class="col-lg-8">
                                            <select class="form-control form-select " aria-label="Default select example" name="notice_to">
                                                <option value="Zuellig Pharma Corp." {{ $allocation->notice_to == 'Zuellig Pharma Corp.' ? 'selected' : ''}}>Zuellig Pharma Corp.</option>
                                                <option value="Metro Drug" {{ $allocation->notice_to == 'Metro Drug' ? 'selected' : ''}}>Metro Drug</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Pick-up Details -->
                            <h5 class="donation-titles mt-4">Authorized Representative</h5>

                            <div class="row">
                                <div class="col-md-6 mt-2">
                                    <div class="row">
                                        <label for="" class="col-lg-4 col-form-label fw-bold">Name :</label>
                                        <div class="col-lg-8">
                                            <input type="text" class="form-control" name="authorized_representative" id="authorized_representative" placeholder="Contact Person Name" value="{{ $allocation->authorized_representative }}" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 mt-2">
                                    <div class="row">
                                        <label for="" class="col-lg-4 col-form-label fw-bold">Position :</label>
                                        <div class="col-lg-8">
                                            <input type="text" class="form-control" name="position" id="position" placeholder="Contact Person Name" value="{{ $allocation->position }}" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--End Pick-up Details -->

                            <div class="row">
                                <div class="col-md-6 mt-2">
                                    <div class="row">
                                        <label for="" class="col-lg-4 col-form-label fw-bold">Contact No. :</label>
                                        <div class="col-lg-8">
                                            <input type="text" class="form-control" name="contact_number" id="contact_number" placeholder="Contact Person Name" value="{{ $allocation->contact_number }}" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 mt-2">
                                    <div class="row">
                                        <label for="" class="col-lg-4 col-form-label fw-bold">Email Address :</label>
                                        <div class="col-lg-8">
                                            <input type="text" class="form-control" name="email_address" id="email_address" placeholder="Email Address" value="{{ $allocation->email_address }}" required>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <!-- Delivery Details -->
                            <h5 class="donation-titles mt-4">Delivery Details</h5>
                            <div class="row">
                                <div class="col-md-6 mt-2">
                                    <div class="row">
                                        <label for="" class="col-lg-4 col-form-label fw-bold">Delivery Address :</label>
                                        <div class="col-lg-8">
                                            <input type="text" class="form-control" name="delivery_address" id="delivery_address" placeholder="Contact Person Name" value="{{ $allocation->delivery_address }}" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 mt-2">
                                    <div class="row">
                                        <label for="" class="col-lg-4 col-form-label fw-bold">Delivery Date :</label>
                                        <div class="col-lg-8">
                                            <div class="input-group mb-3">
                                                <input type="text" class="form-control" name="delivery_date" id="delivery_date" value="{{ date('m/d/Y', strtotime($allocation->delivery_date)) }}" onkeydown="return false" required>
                                                <span class="input-group-text"><i class="fa-solid fa-calendar text-main-color"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mt-2">
                                    <div class="row">
                                        <label for="" class="col-lg-4 col-form-label fw-bold">Delivery Instructions :</label>
                                        <div class="col-lg-8">
                                            <input type="text" class="form-control" name="other_delivery_instructions" id="other_delivery_instructions" placeholder="Contact Person Name" value="{{ $allocation->other_delivery_instructions }}" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Approval Status Input Radio Buttons Approve - Reject -->
                            @if($allocation->status == 1)
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

                                <div class="row align-items-center mt-3" id="reasons_rejected_contribution_div" style="display:none;">
                                    <div class="col-auto">
                                        <label for="" class="col-form-label fw-bold">Reasons :</label>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" id="reasons_rejected_allocation" name="reasons_rejected_allocation" class="form-control" aria-describedby="passwordHelpInline">
                                    </div>
                                </div>

                                <div class="d-flex flex-row-reverse mt-3">
                                    <button type="submit" class="btn btn-primary">Verify</button>
                                    <a type="button" class="btn btn-outline-secondary me-2">Go Back</a>
                                </div>
                            </form>
                            @elseif($allocation->status == 3)
                                <h5 class="donation-titles mt-4">Approval</h5>
                                <hr>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="status" id="status_5" value="5" onclick="hideReasonInput(this.value)">
                                    <label class="form-check-label" for="status_5">Approve</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status" id="status_6" value="4" onclick="showReasonInput(this.value)">
                                    <label class="form-check-label" for="status_6">Reject</label>
                                </div>
                                
                                <div class="row align-items-center mt-3" id="reasons_rejected_donation_div" style="display:none;">
                                    <div class="col-md-1">
                                        <label for="" class="col-form-label fw-bold">Reasons :</label>
                                    </div>
                                    <div class="col-lg-4">
                                        <input type="text" id="reasons_rejected_terms" name="reasons_rejected_terms" class="form-control" aria-describedby="passwordHelpInline">
                                    </div>
                                </div>
                                
                                <div class="d-flex flex-row-reverse mt-3">
                                    <button type="submit" class="btn btn-primary">Verify</button>
                                    <a type="button" class="btn btn-outline-secondary me-2">Go Back</a>
                                </div>
                            </form>
                            @elseif($allocation->status == 5)
                                <h5 class="donation-titles mt-4">Approval</h5>
                                <hr>
                                @csrf
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="status" id="inlineRadio1" value="7" onclick="hideReasonInput(this.value)" required>
                                    <label class="form-check-label" for="inlineRadio1">Approve</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="status" id="inlineRadio2" value="6" onclick="showReasonInput(this.value)" required>
                                    <label class="form-check-label" for="inlineRadio2">Reject</label>
                                </div>

                                <div id="dodrf_div" style="display:block;">
                                    <div class="row align-items-end">
                                        <div class="col-md-6 mt-2">
                                            <label for="dodrf_file" class="col-form-label fw-bold">Upload DODRF: </label>
                                            <input class="form-control" accept="application/pdf"  onchange="validateSize(this)" type="file" id="dodrf_file" name="dodrf_file" required>
                                            <div id="emailHelp" class="form-text">Maximum upload file size: 1 MB.</div>
                                            <div class="invalid-feedback">
                                                DIDRF file is required
                                            </div>
                                        </div>
                                        <div class="col-md-6 mt-2">
                                            <div class="row">
                                                <label for="" class="col-lg-2 col-form-label fw-bold">DODRF No. :</label>
                                                <div class="col-lg-8">
                                                    <input type="text" name="dodrf_no" id="dodrf_no" class="form-control" aria-describedby="didrf_no" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row align-items-center">

                                    </div>
                                </div>

                                <div class="row align-items-center mt-3" id="reasons_rejected_dnd_div" style="display:none;">
                                    <div class="col-auto">
                                        <label for="" class="col-form-label fw-bold">Reasons :</label>
                                    </div>
                                    <div class="col-md-5">
                                        <input type="text" name="reasons_failed_outbound" id="reasons_failed_outbound" class="form-control" aria-describedby="passwordHelpInline">
                                    </div>
                                </div>

                                <div class="d-flex flex-row-reverse mt-3">
                                    <button type="submit" class="btn btn-primary" id="dodrf_button">Close Transaction</button>
                                </div>
                            </form>
                            @elseif($allocation->status == 7)
                            @endif
                        <!-- Approval Status Input-->
                        <!-- End Delivery Details -->
                    </div> 

                </div>
                <!-- End Contribution Info Tab  -->

                <!-- products tab -->
                <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                    <h5 class="donation-titles mt-4">Product Details</h5>
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
                            @if(!$allocated_products->isEmpty())
                                @foreach ($allocated_products as $donation)
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
                                            @if($allocation->status == 1 || $allocation->status == 3 || $allocation->status == 5)
                                                <a href="{{ route('allocation-edit-view', ['allocated_product_id' => $donation->id]) }}" class="btn tt cfs-edit-btn" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Edit">
                                                    <i class="fas fa-edit cfs-edit-ic text-secondary"></i>
                                                </a>
                                                <button data-bs-toggle="modal" data-id="{{ $donation->id }}" data-bs-target="#deleteModal" class="open-delete-modal btn tt cfs-edit-btn" title="Cancel">
                                                    <i class="fas fa-trash-alt cfs-edit-ic text-secondary"></i>
                                                </button>
                                            @endif
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
                            </tr>
                        </thead>
                        <tbody>
                            @if(!$allocated_products->isEmpty())
                                @foreach ($allocated_products as $donation)
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
                <!-- Documents tab -->
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
                                        <a href="{{$item->directory}}" target="_blank" class="btn  cfs-edit-btn" title="Open/View">
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
                                DNA
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
                                Verify DNA
                            </div>
                        </div>
                        <div class="col">
                            <!-- <i class="fa-solid fa-circle-check status-green"></i> -->
                            @if($allocation->status != 2 && $allocation->status >= 3)
                                <!-- Green Check -->
                                <i class="fa-solid fa-circle-check status-green"></i>
                            @elseif($allocation->status == 2)
                                <!-- Red X -->
                                <i class="fas fa-times-circle text-danger"></i>
                            @elseif($allocation->status >= 1)
                                <!-- Gray circle -->
                                <i class="fas fa-circle status-gray"></i>
                            @endif
                            <!-- <i class="fas fa-circle status-gray"></i> -->
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-8">
                            <div class="stats-title">
                            DTAC
                            </div>
                        </div>
                        <div class="col">
                            <!-- <i class="fas fa-circle status-gray"></i> -->
                            @if($allocation->status != 4 && $allocation->status >= 5)
                                <!-- Green Check -->
                                <i class="fa-solid fa-circle-check status-green"></i>
                            @elseif($allocation->status == 4)
                                <!-- Red X -->
                                <i class="fas fa-times-circle text-danger"></i>
                            @elseif($allocation->status >= 1)
                                <!-- Gray circle -->
                                <i class="fas fa-circle status-gray"></i>
                            @endif
                        </div>
                    </div>

                    <hr>
                    <div class="row">
                        <div class="col-8">
                            <div class="stats-title">
                            DODRF
                            </div>
                        </div>
                        <div class="col">
                            <!-- <i class="fas fa-circle status-gray"></i> -->
                            @if($allocation->status != 6 && $allocation->status >= 7)
                                <!-- Green Check -->
                                <i class="fa-solid fa-circle-check status-green"></i>
                            @elseif($allocation->status == 6)
                                <!-- Red X -->
                                <i class="fas fa-times-circle text-danger"></i>
                            @elseif($allocation->status >= 1)
                                <!-- Gray circle -->
                                <i class="fas fa-circle status-gray"></i>
                            @endif
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
                                P{{ number_format($allocation->total_medicine,2) }}
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
                                P{{ number_format($allocation->total_promats,2) }}
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
                                P{{ number_format($allocation->total_allocated_products,2) }}
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
                    <form method="POST" action="{{ route('allocation-cancel-product') }}">
                        @csrf
                        <input name="allocation_product_id" id="donation_id" value="" hidden/>
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
var $delivery_date = $('#delivery_date');
$(document).ready(function () {
    $delivery_date.datepicker({
        autoHide: true,
    });
});

$(document).on("click", ".open-delete-modal", function () {
     var donationId = $(this).data('id');
     $("form #donation_id").val( donationId );
});

var contribution_div = document.getElementById("reasons_rejected_contribution_div");
var donation_div = document.getElementById("reasons_rejected_donation_div");
var dnd_div = document.getElementById("reasons_rejected_dnd_div");
var inbound_div = document.getElementById("reasons_rejected_inbound_div");
var dodrf_div = document.getElementById("dodrf_div");

// contribution_form
var forms = document.getElementById("allocation_form");
var dodrf_button = document.getElementById("dodrf_button");

var status = document.querySelector("input[type='radio'][name=status]:checked");

if(dodrf_button) {
    dodrf_button.addEventListener("click", function () {
        if(status.value == 6) {
            return;
        }
        
        if(!forms.checkValidity()) {
            event.preventDefault()
            event.stopPropagation()
        }
        forms.classList.add('was-validated')
    });
}


function showReasonInput(value) {
    if(value == 2) {
        contribution_div.style.display = "flex";
    } else if(value == 4) {
        donation_div.style.display = "flex";
    } else if(value == 6) {
        dnd_div.style.display = "flex";
        dodrf_div.style.display = "none";
    }
}

function hideReasonInput(value) {
    // contribution_div.style.display = "none";
    if(value == 3) {
        contribution_div.style.display = "none";
    } else if(value == 5) {
        donation_div.style.display = "none";
    } else if(value == 7) {
        dnd_div.style.display = "none";
        dodrf_div.style.display = "block";
    }
}

function validateSize(input) {
  const fileSize = input.files[0].size / 1024 / 1024; // in MiB
  const fileType = input.files[0].type;

  if(fileType != 'application/pdf') {
    $(input).val('');
    alert('The file must be in PDF format');
  }

  if (fileSize > 1) {
    $(input).val('');
    alert('File size exceeds 1 MB');
  }

}
</script>
@endsection
