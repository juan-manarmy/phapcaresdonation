@extends('layouts.app')

@section('custom-css')
<link rel="stylesheet" type="text/css" href="{{ asset('css/product-donation.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('js/modules/datepicker/datepicker.css') }} "/>
@endsection

@section('content')

<div class="bg-heading">
    <h4 class="px-4 py-3">Contribution Details</h4>
</div>

<div class="bg-initial-details mt-2 px-4 py-4">
    <div class="row">
        <div class="col-md-10">
            <!-- Tab Buttons -->
            <ul class="nav nav-tabs mt-3" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Contribution Info</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">
                        Donations 
                        @if($product_code_missing != 0)
                        <i class="fa-solid fa-circle-exclamation tt" style="color:#ffc107;" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Update Product Code"></i>
                        @endif
                        <input type="text" value="{{ $product_code_missing }}" id="product_code_missing" hidden>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab" aria-controls="contact" aria-selected="false">Documents</button>
                </li>
            </ul>

            <div class="tab-content" id="myTabContent">
                <!-- Contribution Info Tab  -->
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <h5 class="donation-titles mt-4">Contribution Details</h5>
                        <!-- Contribution Details Forms -->
                        <div class="row">
                            <div class="col-md-6 mt-2">
                                <div class="row align-items-center">
                                    <label for="" class="col-lg-4 col-form-label fw-bold">Status :</label>
                                    <!-- Status Text -->
                                    <div class="col-lg-8">
                                            @if ($contribution->status === 1)
                                                <div class="fw-bold text-success">
                                                    Contribution Registered Successfully!
                                                </div>
                                            @elseif ($contribution->status === 2)
                                                <div class="fw-bold text-danger">
                                                    Contribution Rejected!
                                                </div>
                                            @elseif ($contribution->status === 3)
                                                <div class="fw-bold text-success">
                                                    Contribution Approved!
                                                </div>
                                            @elseif ($contribution->status === 4)
                                                <div class="fw-bold text-danger">
                                                    Donation Rejected!
                                                </div>
                                            @elseif ($contribution->status === 5)
                                                <div class="fw-bold text-success">
                                                    Donation Accepted!
                                                </div>
                                            @elseif ($contribution->status === 6)
                                                <div class="fw-bold text-danger">
                                                    Contribution Rejected By Staff!
                                                </div>
                                            @elseif ($contribution->status === 7)
                                                <div class="fw-bold text-success">
                                                    Contribution Approved By Staff!
                                                </div>
                                            @elseif ($contribution->status === 8)
                                                <div class="fw-bold text-danger">
                                                    Donation Inbound Failed!
                                                </div>
                                            @elseif ($contribution->status === 9)
                                                <div class="fw-bold text-success">
                                                    Successful Contribution!
                                                </div>
                                            @else
                                                No Status
                                            @endif
                                        
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mt-2">
                                <div class="row align-items-center">
                                    <label for="" class="col-lg-4 col-form-label fw-bold">Contribution No. :</label>
                                    <div class="col-lg-8">
                                        {{ $contribution->contribution_no }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <label for="" class="col-lg-4 col-form-label fw-bold">Member :</label>
                                    <div class="col-lg-8">
                                        <input type="text" readonly class="form-control-plaintext" value="{{ $member_name }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    <!-- if status is finished or cancelled -->
                    @if($contribution->status == 9 || $contribution->status == 2 || $contribution->status == 4 || $contribution->status == 6 || $contribution->status == 8)
                        <div class="row">
                            <div class="col-md-6 mt-2">
                                <div class="row">
                                    <label for="" class="col-lg-4 col-form-label fw-bold">Contribution Date :</label>
                                    <div class="col-lg-8">
                                        <input type="text" readonly class="form-control-plaintext" value="{{ date('m/d/Y', strtotime($contribution->contribution_date)) }}">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mt-2">
                                <div class="row">
                                    <label for="" class="col-lg-4 col-form-label fw-bold">Distributor :</label>
                                    <div class="col-lg-8">
                                        <input type="text" readonly class="form-control-plaintext" value="{{ $contribution->distributor }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Pick-up Details -->
                        <h5 class="donation-titles mt-4">Pick-up Details</h5>

                        <div class="row">
                            <div class="col-md-6 mt-2">
                                <div class="row">
                                    <label for="" class="col-lg-4 col-form-label fw-bold">Address :</label>
                                    <div class="col-lg-8">
                                        <input type="text" readonly class="form-control-plaintext" value="{{ $contribution->pickup_address }}">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mt-2">
                                <div class="row">
                                    <label for="" class="col-lg-4 col-form-label fw-bold">Contact Person :</label>
                                    <div class="col-lg-8">
                                        <input type="text" readonly class="form-control-plaintext" value="{{ $contribution->pickup_contact_person }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mt-2">
                                <div class="row">
                                    <label for="" class="col-lg-4 col-form-label fw-bold">Contact No. :</label>
                                    <div class="col-lg-8">
                                        <input type="text" readonly class="form-control-plaintext" value="{{ $contribution->pickup_contact_no }}">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mt-2">
                                <div class="row">
                                    <label for="" class="col-lg-4 col-form-label fw-bold">Date :</label>
                                    <div class="col-lg-8">
                                        <input type="text" readonly class="form-control-plaintext" value="{{ date('m/d/Y', strtotime($contribution->pickup_date)) }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--End Pick-up Details -->

                        <!-- Delivery Details -->
                        <h5 class="donation-titles mt-4">Delivery Details</h5>

                        <div class="row">
                            <div class="col-md-6 mt-2">
                                <div class="row">
                                    <label for="" class="col-lg-4 col-form-label fw-bold">Address :</label>
                                    <div class="col-lg-8">
                                        <input type="text" readonly class="form-control-plaintext" value="{{ $contribution->delivery_address }}">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mt-2">
                                <div class="row">
                                    <label for="" class="col-lg-4 col-form-label fw-bold">Contact Person :</label>
                                    <div class="col-lg-8">
                                        <input type="text" readonly class="form-control-plaintext" value="{{ $contribution->delivery_contact_person }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mt-2">
                                <div class="row">
                                    <label for="" class="col-lg-4 col-form-label fw-bold">Contact No. :</label>
                                    <div class="col-lg-8">
                                        <input type="text" readonly class="form-control-plaintext" value="{{ $contribution->delivery_contact_no }}">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mt-2">
                                <div class="row">
                                    <label for="" class="col-lg-4 col-form-label fw-bold">Date :</label>
                                    <div class="col-lg-8">
                                        <input type="text" readonly class="form-control-plaintext" value="{{ date('m/d/Y', strtotime($contribution->delivery_date)) }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Delivery Details -->

                        <!-- Request By -->
                        <h5 class="donation-titles mt-4">Request By</h5>
                        <div class="row">
                            <div class="col-md-6 mt-2">
                                <div class="row">
                                    <label for="" class="col-lg-4 col-form-label fw-bold">Name :</label>
                                    <div class="col-lg-8">
                                        Super Admin
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mt-2">
                                <div class="row">
                                    <label for="" class="col-lg-4 col-form-label fw-bold">Position :</label>
                                    <div class="col-lg-8">
                                        Superadmin
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mt-2">
                                <div class="row">
                                    <label for="" class="col-lg-4 col-form-label fw-bold">Contact No. :</label>
                                    <div class="col-lg-8">
                                    8655602
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mt-2">
                                <div class="row">
                                    <label for="" class="col-lg-4 col-form-label fw-bold">Email :</label>
                                    <div class="col-lg-8">
                                    jdelcastillo@phapcares.org
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Request By -->
                    @endif

                    <!-- if status is not finished yet -->
                    @if($contribution->status != 9)
                        <form class="needs-validation" novalidate method="POST" enctype="multipart/form-data" action="{{ route('contribution-status-update', ['contribution_id' => $contribution->id]) }}" id="contribution_form">
                        @csrf
                            @if($contribution->status == 1 || $contribution->status == 3 || $contribution->status == 5 || $contribution->status == 7)
                                <div class="row">
                                    <div class="col-md-6 mt-2">
                                        <div class="row">
                                            <label for="" class="col-lg-4 col-form-label fw-bold">Contribution Date :</label>
                                            <div class="col-lg-8">
                                                <div class="input-group mb-3">
                                                    <input type="text" class="form-control" name="contribution_date" id="contribution_date" value="{{ date('m/d/Y', strtotime($contribution->contribution_date)) }}" onkeydown="return false" required>
                                                    <span class="input-group-text"><i class="fa-solid fa-calendar text-main-color"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mt-2">
                                        <div class="row">
                                            <label for="" class="col-lg-4 col-form-label fw-bold">Distributor :</label>
                                            <div class="col-lg-8">
                                                <input type="text" class="form-control" name="distributor" id="distributor" value="{{ $contribution->distributor }}" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            
                            @if($contribution->status == 1 || $contribution->status == 3)
                                <!-- Pick-up Details -->
                                <h5 class="donation-titles mt-4">Pick-up Details</h5>

                                <div class="row">
                                    <div class="col-md-6 mt-2">
                                        <div class="row">
                                            <label for="" class="col-lg-4 col-form-label fw-bold">Address :</label>
                                            <div class="col-lg-8">
                                                <input type="text" class="form-control" name="pickup_address" id="pickup_address" value="{{ $contribution->pickup_address }}" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mt-2">
                                        <div class="row">
                                            <label for="" class="col-lg-4 col-form-label fw-bold">Contact Person :</label>
                                            <div class="col-lg-8">
                                                <input type="text" class="form-control" name="pickup_contact_person" id="pickup_contact_person" value="{{ $contribution->pickup_contact_person }}" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mt-2">
                                        <div class="row">
                                            <label for="" class="col-lg-4 col-form-label fw-bold">Contact No. :</label>
                                            <div class="col-lg-8">
                                                <input type="text" class="form-control" name="pickup_contact_no" id="pickup_contact_no" value="{{ $contribution->pickup_contact_no }}" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mt-2">
                                        <div class="row">
                                            <label for="" class="col-lg-4 col-form-label fw-bold">Date :</label>
                                            <div class="col-lg-8">
                                                <div class="input-group mb-3">
                                                    <input type="text" class="form-control" name="pickup_date" id="pickup_date" value="{{ date('m/d/Y', strtotime($contribution->pickup_date)) }}" onkeydown="return false" required>
                                                    <span class="input-group-text"><i class="fa-solid fa-calendar text-main-color"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--End Pick-up Details -->

                                <!-- Delivery Details -->
                                <h5 class="donation-titles mt-4">Delivery Details</h5>

                                <div class="row">
                                    <div class="col-md-6 mt-2">
                                        <div class="row">
                                            <label for="" class="col-lg-4 col-form-label fw-bold">Address :</label>
                                            <div class="col-lg-8">
                                                <input type="text" class="form-control" name="delivery_address" id="delivery_address" value="{{ $contribution->delivery_address }}" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mt-2">
                                        <div class="row">
                                            <label for="" class="col-lg-4 col-form-label fw-bold">Contact Person :</label>
                                            <div class="col-lg-8">
                                                <input type="text" class="form-control" name="delivery_contact_person" id="delivery_contact_person" value="{{ $contribution->delivery_contact_person }}" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mt-2">
                                        <div class="row">
                                            <label for="" class="col-lg-4 col-form-label fw-bold">Contact No. :</label>
                                            <div class="col-lg-8">
                                                <input type="text" class="form-control" name="delivery_contact_no" id="delivery_contact_no" value="{{ $contribution->delivery_contact_no }}" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mt-2">
                                        <div class="row">
                                            <label for="" class="col-lg-4 col-form-label fw-bold">Date :</label>
                                            <div class="col-lg-8">
                                                <div class="input-group mb-3">
                                                    <input type="text" class="form-control" name="delivery_date" id="delivery_date" value="{{ date('m/d/Y', strtotime($contribution->delivery_date)) }}" onkeydown="return false" required>
                                                    <span class="input-group-text"><i class="fa-solid fa-calendar text-main-color"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Delivery Details -->

                                <!-- Request By -->
                                <h5 class="donation-titles mt-4">Request By</h5>
                                <div class="row">
                                    <div class="col-md-6 mt-2">
                                        <div class="row">
                                            <label for="" class="col-lg-4 col-form-label fw-bold">Name :</label>
                                            <div class="col-lg-8">
                                                Super Admin
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mt-2">
                                        <div class="row">
                                            <label for="" class="col-lg-4 col-form-label fw-bold">Position :</label>
                                            <div class="col-lg-8">
                                                Superadmin
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mt-2">
                                        <div class="row">
                                            <label for="" class="col-lg-4 col-form-label fw-bold">Contact No. :</label>
                                            <div class="col-lg-8">
                                            8655602
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mt-2">
                                        <div class="row">
                                            <label for="" class="col-lg-4 col-form-label fw-bold">Email :</label>
                                            <div class="col-lg-8">
                                            jdelcastillo@phapcares.org
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Request By -->
                            @endif
                            <!-- DELIVERY NOTICE DETAILS  -->
                            @if($contribution->status == 5)
                                <!-- DELIVERY NOTICE DETAILS -->
                                <h5 class="donation-titles mt-4">Delivery Notice Details</h5>
                                <div class="row">
                                    <div class="col-md-6 mt-2">
                                        <div class="row">
                                            <label for="" class="col-lg-4 col-form-label fw-bold">DND No. :</label>
                                            <div class="col-lg-8">
                                                <input type="text" class="form-control" name="dnd_no" id="dnd_no" value="DND_{{ $contribution->contribution_no }}" readonly required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mt-2">
                                        <div class="row">
                                            <label for="" class="col-lg-4 col-form-label fw-bold">Date :</label>
                                            <div class="col-lg-8">
                                                <input type="text" class="form-control" name="dnd_date" id="" value="{{ date('m/d/Y') }}" readonly required>
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
                                                    <option value="Zuellig Pharma Corp." {{ $contribution->notice_to == 'Zuellig Pharma Corp.' ? 'selected' : ''}}>Zuellig Pharma Corp.</option>
                                                    <option value="Metro Drug" {{ $contribution->notice_to == 'Metro Drug' ? 'selected' : ''}}>Metro Drug</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--End DELIVERY NOTICE DETAILS -->

                                <!-- CONTACT PERSON -->
                                <h5 class="donation-titles mt-4">Contact Person</h5>
                                <div class="row">
                                    <div class="col-md-6 mt-2">
                                        <div class="row">
                                            <label for="" class="col-lg-4 col-form-label fw-bold">Name :</label>
                                            <div class="col-lg-8">
                                                <input type="text" class="form-control" name="dnd_contact_person" id="dnd_contact_person" placeholder="Contact Person Name" value="{{ $contribution->dnd_contact_person }}" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mt-2">
                                        <div class="row">
                                            <label for="" class="col-lg-4 col-form-label fw-bold">Contact No. :</label>
                                            <div class="col-lg-8">
                                                <input type="text" class="form-control" name="pickup_contact_no" id="pickup_contact_no" value="{{ $contribution->pickup_contact_no }}" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--End CONTACT PERSON -->
                            @endif
                            <!-- Delivery Inbound Details -->
                            @if($contribution->status == 7)
                                <!-- Delivery Inbound Details -->
                                <h5 class="donation-titles mt-4">Delivery Inbound Details</h5>
                                <div class="row">
                                    <div class="col-md-6 mt-2">
                                        <div class="row">
                                            <label for="" class="col-lg-4 col-form-label fw-bold">Notice To :</label>
                                            <div class="col-lg-8">
                                                <select class="form-control form-select " aria-label="Default select example" name="notice_to">
                                                    <option value="Zuellig Pharma Corp." {{ $contribution->notice_to == 'Zuellig Pharma Corp.' ? 'selected' : ''}}>Zuellig Pharma Corp.</option>
                                                    <option value="Metro Drug" {{ $contribution->notice_to == 'Metro Drug' ? 'selected' : ''}}>Metro Drug</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--End Delivery Inbound Details -->
                                
                                <!-- CONTACT PERSON -->
                                <h5 class="donation-titles mt-4">Contact Person</h5>
                                <div class="row">
                                    <div class="col-md-6 mt-2">
                                        <div class="row">
                                            <label for="" class="col-lg-4 col-form-label fw-bold">Name :</label>
                                            <div class="col-lg-8">
                                                <input type="text" class="form-control" name="pickup_contact_person" id="pickup_contact_person" placeholder="Contact Person Name" value="{{ $contribution->pickup_contact_person }}" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mt-2">
                                        <div class="row">
                                            <label for="" class="col-lg-4 col-form-label fw-bold">Contact No. :</label>
                                            <div class="col-lg-8">
                                                <input type="text" class="form-control" name="pickup_contact_no" id="pickup_contact_no" value="{{ $contribution->pickup_contact_no }}" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--End CONTACT PERSON -->
                            @endif

                            @if($contribution->status == 5 || $contribution->status == 7)
                                <!-- Pick-up Details -->
                                <h5 class="donation-titles mt-4">Pick-up Details</h5>

                                <div class="row">
                                    <div class="col-md-6 mt-2">
                                        <div class="row">
                                            <label for="" class="col-lg-4 col-form-label fw-bold">Date :</label>
                                            <div class="col-lg-8">
                                                <div class="input-group mb-3">
                                                    <input type="text" class="form-control" name="pickup_date" id="pickup_date" value="{{ date('m/d/Y', strtotime($contribution->pickup_date)) }}" onkeydown="return false" required>
                                                    <span class="input-group-text"><i class="fa-solid fa-calendar text-main-color"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mt-2">

                                        <div class="row">
                                            <label for="" class="col-lg-4 col-form-label fw-bold">Address :</label>
                                            <div class="col-lg-8">
                                                <input type="text" class="form-control" name="pickup_address" id="pickup_address" value="{{ $contribution->pickup_address }}" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mt-2">
                                        <div class="row">
                                            <label for="" class="col-lg-4 col-form-label fw-bold">Other Pick-Up Instructions :</label>
                                            <div class="col-lg-8">
                                                <input type="text" class="form-control" name="pickup_instructions" id="pickup_instructions" placeholder="Other pick-up instructions" value="{{ $contribution->pickup_instructions }}" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--End Pick-up Details -->
                            @endif

                        <!-- Approval Status Input Radio Buttons Approve - Reject -->
                        @if($contribution->status == 1)
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
                                        <input type="text" id="reasons_rejected_contribution" name="reasons_rejected_contribution" class="form-control" aria-describedby="passwordHelpInline">
                                    </div>
                                </div>

                                <div class="d-flex flex-row-reverse mt-3">
                                    <button type="submit" class="btn btn-primary">Verify</button>
                                    <a type="button" class="btn btn-outline-secondary me-2">Go Back</a>
                                </div>
                        </form>
                        @elseif($contribution->status == 3)
                            <h5 class="donation-titles mt-4">Approval</h5>
                            <hr>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status" id="status_5" value="5" onclick="hideReasonInput(this.value)">
                                <label class="form-check-label" for="status_5">YES, we accept the foregoing donations. We will call you for further intructions</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" id="status_6" value="4" onclick="showReasonInput(this.value)">
                                <label class="form-check-label" for="status_6">NO, we regret we are unable to accept the foregoing donations for the following reasons:</label>
                            </div>
                            
                            <div class="row align-items-center mt-3" id="reasons_rejected_donation_div" style="display:none;">
                                <div class="col-md-1">
                                    <label for="" class="col-form-label fw-bold">Reasons :</label>
                                </div>
                                <div class="col-lg-4">
                                    <input type="text" id="reasons_rejected_donation" name="reasons_rejected_donation" class="form-control" aria-describedby="passwordHelpInline">
                                </div>
                            </div>

                            <div class="row align-items-center mt-3">
                                <div class="col-md-1">
                                    <label for="" class="col-form-label fw-bold">Verified By :</label>
                                </div>
                                <div class="col-lg-4">
                                    <select class="form-select" id="asd" name="asd">
                                        <option value="1">Test </option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="d-flex flex-row-reverse mt-3">
                                <button type="submit" class="btn btn-primary">Verify</button>
                                <a type="button" class="btn btn-outline-secondary me-2">Go Back</a>
                            </div>
                        </form>
                        @elseif($contribution->status == 5)
                            <h5 class="donation-titles mt-4">Approval</h5>
                            <hr>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status" id="inlineRadio1" value="7" onclick="hideReasonInput(this.value)">
                                <label class="form-check-label" for="inlineRadio1">Approve</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status" id="inlineRadio2" value="6" onclick="showReasonInput(this.value)">
                                <label class="form-check-label" for="inlineRadio2">Reject</label>
                            </div>

                            <div class="row align-items-center mt-3" id="reasons_rejected_dnd_div" style="display:none;">
                                <div class="col-auto">
                                    <label for="" class="col-form-label fw-bold">Reasons :</label>
                                </div>
                                <div class="col-md-5">
                                    <input type="text" name="reasons_rejected_dnd" id="reasons_rejected_dnd" class="form-control" aria-describedby="passwordHelpInline">
                                </div>
                            </div>

                            <div class="d-flex flex-row-reverse mt-3">
                                <button type="submit" class="btn btn-primary">Verify</button>
                                <a type="button" class="btn btn-outline-secondary me-2">Go Back</a>
                            </div>
                        </form>
                        @elseif($contribution->status == 7)
                            <h5 class="donation-titles mt-4">Approval</h5>
                            <hr>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status" id="inlineRadio1" value="9" onclick="hideReasonInput(this.value)" required>
                                <label class="form-check-label" for="inlineRadio1">Success</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status" id="inlineRadio2" value="8" onclick="showReasonInput(this.value)" required>
                                <label class="form-check-label" for="inlineRadio2">Failed</label>
                            </div>

                            <div id="didrf_div" style="display:block;">
                                <div class="row align-items-center mt-3">
                                    <div class="col-md-6 mt-2">
                                        <div class="row">
                                            <label for="" class="col-lg-3 col-form-label fw-bold">DND No. :</label>
                                            <div class="col-lg-9">
                                                <input type="text" name="dnd_no" id="dnd_no" class="form-control" value="{{ $contribution->dnd_no }}" aria-describedby="dnd_no"  readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mt-2">
                                        <div class="row">
                                            <label for="" class="col-lg-3 col-form-label fw-bold">DIDRF No. :</label>
                                            <div class="col-lg-9">
                                                <input type="text" name="didrf_no" id="didrf_no" class="form-control" aria-describedby="" required>
                                                <div class="invalid-feedback">
                                                    DIDRF No. is required
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mt-2">
                                        <div class="row">
                                            <label for="" class="col-lg-3 col-form-label fw-bold">DAFF No. :</label>
                                            <div class="col-lg-9">
                                                <input type="text" name="daff_no" id="daff_no" class="form-control" aria-describedby="" required>
                                                <div class="invalid-feedback">
                                                    DIDRF file is required
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row align-items-center">
                                    <div class="col-md-5 mt-2">
                                        <label for="didrf_file" class="col-form-label fw-bold">Upload DIDRF: </label>
                                        <input class="form-control" type="file" id="didrf_file" name="didrf_file" required>
                                        <div class="invalid-feedback">
                                            DAFF No. is required
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row align-items-center mt-3" id="reasons_rejected_inbound_div" style="display:none;">
                                <div class="col-auto">
                                    <label for="" class="col-form-label fw-bold">Reasons :</label>
                                </div>
                                <div class="col-md-5">
                                    <input type="text" name="reasons_rejected_inbound" id="reasons_rejected_inbound" class="form-control" aria-describedby="" >
                                </div>
                            </div>

                            <div class="d-flex flex-row-reverse mt-3">
                                <button type="submit" class="btn btn-primary" id="didrf_button">Verify</button>
                            </div>
                        </form>
                        @endif
                        <!-- Approval Status Input-->
                    @endif

                </div>
                <!-- End Contribution Info Tab  -->

                <!-- DONATION TABS -->
                <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                    <h5 class="donation-titles mt-4">Donation Details</h5>
                    <table class="table mt-3">
                        <thead class="theader">
                            <tr>
                                <th scope="col">Product Code</th>
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
                            @if(!$donations->isEmpty())
                                @foreach ($donations as $donation)
                                    @if($donation->status == 1)
                                    <tr>
                                        @if($donation->product_code == '')
                                        <td>
                                            <i class="fa-solid fa-circle-exclamation tt" style="color:#ffc107;" data-bs-toggle="tooltip" data-bs-placement="right" title="Update Product Code"></i>
                                        </td>
                                        @else
                                        <td>{{ $donation->product_code }}</td>
                                        @endif
                                        <!-- {{ $donation->product_code }} -->
                                        <td>{{ $donation->product_name }}</td>
                                        <td>{{ $donation->lot_no }}</td>
                                        <td>{{ $donation->quantity }}</td>
                                        <td>{{ $donation->unit_cost }}</td>
                                        <td>{{ $donation->total }}</td>
                                        <td>{{ date('F, d Y', strtotime($donation->expiry_date)) }}</td>
                                        <td>
                                            @if($contribution->status == 7)
                                            <a href="{{ route('contribution-donation-edit-view', ['donation_id' => $donation->id]) }}" class="btn tt cfs-edit-btn" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Edit">
                                                <i class="fas fa-edit cfs-edit-ic text-secondary"></i>
                                            </a>
                                            @endif
                                            @if($contribution->status == 1 || $contribution->status == 3 || $contribution->status == 5 || $contribution->status == 7)
                                            <button data-bs-toggle="modal" data-id="{{ $donation->id }}" data-bs-target="#deleteModal" class="open-delete-modal btn tt cfs-edit-btn" title="Delete">
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
                    <!-- Cancelled Table -->
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
                            @if(!$donations->isEmpty())
                                @foreach ($donations as $donation)
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
            <!--End Medicine Donation Forms -->
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
                                NOD
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
                                APPROVED NOD
                            </div>
                        </div>
                        <div class="col">
                            <!-- <i class="fa-solid fa-circle-check status-green"></i> -->
                            @if($contribution->status != 2 && $contribution->status >= 3)
                                <!-- Green Check -->
                                <i class="fa-solid fa-circle-check status-green"></i>
                            @elseif($contribution->status == 2)
                                <!-- Red X -->
                                <i class="fas fa-times-circle text-danger"></i>
                            @elseif($contribution->status >= 1)
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
                            DONATION ACCEPTED
                            </div>
                        </div>
                        <div class="col">
                            <!-- <i class="fa-solid fa-circle-check status-green"></i> -->
                            <!-- <i class="fas fa-circle status-gray"></i> -->
                            @if($contribution->status != 4 && $contribution->status >= 5)
                                <!-- Green Check -->
                                <i class="fa-solid fa-circle-check status-green"></i>
                            @elseif($contribution->status == 4)
                                <!-- Red X -->
                                <i class="fas fa-times-circle text-danger"></i>
                            @elseif($contribution->status >= 1)
                                <!-- Gray circle -->
                                <i class="fas fa-circle status-gray"></i>
                            @endif
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-8">
                            <div class="stats-title">
                            DND
                            </div>
                        </div>
                        <div class="col">
                            <!-- <i class="fas fa-circle status-gray"></i> -->
                            @if($contribution->status != 6 && $contribution->status >= 7)
                                <!-- Green Check -->
                                <i class="fa-solid fa-circle-check status-green"></i>
                            @elseif($contribution->status == 6)
                                <!-- Red X -->
                                <i class="fas fa-times-circle text-danger"></i>
                            @elseif($contribution->status >= 1)
                                <!-- Gray circle -->
                                <i class="fas fa-circle status-gray"></i>
                            @endif
                        </div>
                    </div>

                    <hr>
                    <div class="row">
                        <div class="col-8">
                            <div class="stats-title">
                                DIDRF
                            </div>
                        </div>
                        <div class="col">
                            <!-- <i class="fas fa-circle status-gray"></i> -->
                            @if($contribution->status != 8 && $contribution->status >= 9)
                                <!-- Green Check -->
                                <i class="fa-solid fa-circle-check status-green"></i>
                            @elseif($contribution->status == 8)
                                <!-- Red X -->
                                <i class="fas fa-times-circle text-danger"></i>
                            @elseif($contribution->status >= 1)
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
                                P{{ number_format($contribution->total_medicine,2) }}
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
                            P{{ number_format($contribution->total_promats,2) }}
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
                                P{{ number_format($contribution->total_donation,2) }}
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!-- End Statistics -->

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
                    <form method="POST" action="{{ route('cancel-donation-status', ['contribution_id' => $contribution->id ]) }}">
                        @csrf
                        <input name="donation_id" id="donation_id" value="" hidden/>
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

<!-- Toast -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 10">
    <div id="liveToast" class="toast" style="background:#FFF3CD;" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body" style="color:#664d03;">
                Please update product code
            </div>
            <button type="button" class="btn-close me-2 m-auto" style="color:#664d03;" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>
@endsection

@section('custom-js')
<script src="{{asset('js/modules/datepicker/datepicker.js')}} "></script>
<script>
var $contribution_date = $('#contribution_date');
var $pickup_date = $('#pickup_date');
var $delivery_date = $('#delivery_date');

// contribution_form
var forms = document.getElementById("contribution_form");
var product_code_missing = document.getElementById("product_code_missing");
var didrf_button = document.getElementById("didrf_button");

var toastLiveExample = document.getElementById('liveToast')
var toast = new bootstrap.Toast(toastLiveExample)
var didrf_file = document.getElementById("didrf_file");

var status = document.querySelector("input[type='radio'][name=status]:checked");

if(didrf_button) {
    didrf_button.addEventListener("click", function () {
        if(status.value == 8) {
            return;
        }
        
        if(product_code_missing.value != 0) {
            event.preventDefault()
            event.stopPropagation()
            toast.show()
        }
        
        if(!forms.checkValidity()) {
            event.preventDefault()
            event.stopPropagation()
        }

        forms.classList.add('was-validated')
        
    });
}

$(document).ready(function () {
    $contribution_date.datepicker({
        autoHide: true,
    });
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

var contribution_div = document.getElementById("reasons_rejected_contribution_div");
var donation_div = document.getElementById("reasons_rejected_donation_div");
var dnd_div = document.getElementById("reasons_rejected_dnd_div");
var inbound_div = document.getElementById("reasons_rejected_inbound_div");
var didrf_div = document.getElementById("didrf_div");

function showReasonInput(value) {
    if(value == 2) {
        contribution_div.style.display = "flex";
    } else if(value == 4) {
        donation_div.style.display = "flex";
    } else if(value == 6) {
        dnd_div.style.display = "flex";
    } else if(value == 8) {
        inbound_div.style.display = "flex";
        didrf_div.style.display = "none";
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
    } else if(value == 9) {
        inbound_div.style.display = "none";
        didrf_div.style.display = "block";
    }
}
</script>
@endsection
