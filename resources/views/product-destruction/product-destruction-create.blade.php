@extends('layouts.app')

@section('custom-css')
<link rel="stylesheet" type="text/css" href="{{ asset('js/modules/datepicker/datepicker.css') }} "/>
<link rel="stylesheet" type="text/css" href="{{ asset('css/product-donation.css') }}"/>
@endsection

@section('content')
<div class="bg-heading">
    <div class="d-flex justify-content-between p-0">
        <h4 class="px-4 py-3 mb-0">Create New Product Destruction</h4>
    </div>
</div>

<div class="bg-initial-details mt-2 px-4 py-4">
    <div class="row">
        <div class="col-md-10">
            <div class="tab-content" id="myTabContent">
                <!-- Contribution Info Tab  -->
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <h5 class="donation-titles mt-4">Product Destruction Details</h5>
                    <!-- Contribution Details Forms -->
                    @if(isset($destruction))
                        <form method="POST" action="{{ route('destruction-create-save') }}" class="mt-3">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mt-2">
                                    <div class="row">
                                        <label for="" class="col-lg-4 col-form-label fw-bold">Destruction No. :</label>
                                        <div class="col-lg-8">
                                            <input type="text" class="form-control" placeholder="" id="destruction_no" name="destruction_no" value="{{ $destruction->destruction_no }}" readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="row">
                                        <label for="" class="col-lg-4 col-form-label fw-bold">PDRF No. :</label>
                                        <div class="col-lg-8">
                                            <input type="text" class="form-control" placeholder="" id="" name="pdrf_no" value="{{ $destruction->destruction_no }}" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- <div class="row">
                                <div class="col-md-6 mt-2">
                                    <div class="row">
                                        <label for="" class="col-lg-4 col-form-label fw-bold">Beneficiary :</label>
                                        <div class="col-lg-8">
                                            <select class="form-control form-select" name="beneficiary_id" aria-label="Default select example">
                                                @foreach($beneficiaries as $item)
                                                    <option {{ $destruction->beneficiary_id == $item->id ? "selected" : "" }} value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div> -->

                            <div class="row">
                                <div class="col-md-6 mt-2">
                                    <div class="row">
                                        <label for="" class="col-lg-4 col-form-label fw-bold">Notice To :</label>
                                        <div class="col-lg-8">
                                            <select class="form-control form-select " aria-label="Default select example" name="notice_to">
                                                <option value="Zuellig Pharma Corp." {{ $destruction->notice_to == 'ZPC MDI' ? 'selected' : ''}}>ZPC MDI</option>
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
                                            <input type="text" class="form-control" placeholder="Pick-Up Address" id="" name="pickup_address" value="{{ $destruction->pickup_address }}" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--End Pick-up Details -->

                            <div class="row">
                                <div class="col-md-6 mt-2">
                                    <div class="row">
                                        <label for="" class="col-lg-4 col-form-label fw-bold">Contact Person :</label>
                                        <div class="col-lg-8">
                                            <input type="text" class="form-control" placeholder="Contact Person" name="pickup_contact_person" value="{{ $destruction->pickup_contact_person }}" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 mt-2">
                                    <div class="row">
                                        <label for="" class="col-lg-4 col-form-label fw-bold">Contact No. :</label>
                                        <div class="col-lg-8">
                                            <input type="text" class="form-control" placeholder="Contact No." id="" name="pickup_contact_no" value="{{ $destruction->pickup_contact_no }}" required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mt-2">
                                    <div class="row">
                                        <label for="" class="col-lg-4 col-form-label fw-bold">Pick-up Date :</label>
                                        <div class="col-lg-8">
                                            <div class="input-group mb-3">
                                                <input type="text" class="form-control" placeholder="Pick-up Date" name="pickup_date" id="pickup_date" onkeydown="return false" value="{{ date('m/d/Y', strtotime($destruction->pickup_date)) }}" required>
                                                <span class="input-group-text"><i class="fa-solid fa-calendar text-main-color"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 mt-2">
                                    <div class="row">
                                        <label for="" class="col-lg-4 col-form-label fw-bold">Pick-up Instructions :</label>
                                        <div class="col-lg-8">
                                            <input type="text" class="form-control" placeholder="Other Pickup Instructions" name="other_pickup_instructions" value="{{ $destruction->other_pickup_instructions }}" required>
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
                                            <input type="text" class="form-control" placeholder="Contact Person" id="" name="delivery_contact_person" value="{{ $destruction->delivery_contact_person }}" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 mt-2">
                                    <div class="row">
                                        <label for="" class="col-lg-4 col-form-label fw-bold">Delivery Address :</label>
                                        <div class="col-lg-8">
                                            <input type="text" class="form-control" placeholder="Delivery Address" id="" name="delivery_address" value="{{ $destruction->delivery_address }}" required>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-md-6 mt-2">
                                    <div class="row">
                                        <label for="" class="col-lg-4 col-form-label fw-bold">Authorized Recipient :</label>
                                        <div class="col-lg-8">
                                            <input type="text" class="form-control" placeholder="Authorized Recipient" id="" name="delivery_authorized_recipient" value="{{ $destruction->pickup_contact_person }}" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mt-2">
                                    <div class="row">
                                        <label for="" class="col-lg-4 col-form-label fw-bold">Contact No. :</label>
                                        <div class="col-lg-8">
                                            <input type="text" class="form-control" placeholder="Contact No." id="" name="delivery_contact_no" value="{{ $destruction->pickup_contact_person }}" required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mt-2">
                                    <div class="row">
                                        <label for="" class="col-lg-4 col-form-label fw-bold">Delivery Date :</label>
                                        <div class="col-lg-8">
                                            <div class="input-group mb-3">
                                                <input type="text" class="form-control" placeholder="Delivery Date" id="delivery_date" name="delivery_date" onkeydown="return false" value="{{ date('m/d/Y', strtotime($destruction->delivery_date)) }}" required>
                                                <span class="input-group-text"><i class="fa-solid fa-calendar text-main-color"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mt-2">
                                    <div class="row">
                                        <label for="" class="col-lg-4 col-form-label fw-bold">Delivery Instructions :</label>
                                        <div class="col-lg-8">
                                            <input type="text" class="form-control" placeholder="Delivery Instructions" id="" name="other_delivery_instructions" value="{{ $destruction->pickup_contact_person }}" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End Delivery Details -->
                            <div class="d-flex flex-row-reverse mt-3">
                                <button type="submit" class="btn btn-primary">Save and Proceed</button>
                                <a href="{{ route('product-destruction-list') }}" type="button" class="btn btn-outline-secondary me-2">Go Back</a>
                            </div>
                        </form>
                    @else
                        <form method="POST" action="{{ route('destruction-create-save') }}" class="mt-3">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mt-2">
                                    <div class="row">
                                        <label for="" class="col-lg-4 col-form-label fw-bold">Destruction No. :</label>
                                        <div class="col-lg-8">
                                            <input type="text" class="form-control" placeholder="" id="destruction_no" name="destruction_no" value="{{ $destruction_no }}" readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="row">
                                        <label for="" class="col-lg-4 col-form-label fw-bold">PDRF No. :</label>
                                        <div class="col-lg-8">
                                            <input type="text" class="form-control" placeholder="" id="" name="pdrf_no" value="{{ $destruction_no }}" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mt-2">
                                    <div class="row">
                                        <label for="beneficiary_id" class="col-lg-4 col-form-label fw-bold">Beneficiary :</label>
                                        <div class="col-lg-8">
                                            <select class="form-control form-select" name="beneficiary_id" aria-label="Default select example">
                                                <option value="0">None</option>
                                                @foreach($beneficiaries as $item)
                                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mt-2">
                                    <div class="row">
                                        <label for="notice_to" class="col-lg-4 col-form-label fw-bold">Notice To :</label>
                                        <div class="col-lg-8">
                                            <select class="form-control form-select " aria-label="Default select example" name="notice_to">
                                                <option value="Zuellig Pharma Corp.">ZPC MDI</option>
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
                                        <label for="pickup_address" class="col-lg-4 col-form-label fw-bold">Pick-Up Address:</label>
                                        <div class="col-lg-8">
                                            <input type="text" class="form-control" placeholder="Pick-Up Address" id="" name="pickup_address" required>
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
                                            <input type="text" class="form-control" placeholder="Contact Person" name="pickup_contact_person" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 mt-2">
                                    <div class="row">
                                        <label for="pickup_contact_no" class="col-lg-4 col-form-label fw-bold">Contact No. :</label>
                                        <div class="col-lg-8">
                                            <input type="text" class="form-control" placeholder="Contact No." id="" name="pickup_contact_no" required>
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
                                                <input type="text" class="form-control" placeholder="Pick-up Date" name="pickup_date" id="pickup_date" onkeydown="return false" required>
                                                <span class="input-group-text"><i class="fa-solid fa-calendar text-main-color"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 mt-2">
                                    <div class="row">
                                        <label for="other_pickup_instructions" class="col-lg-4 col-form-label fw-bold">Pick-up Instructions :</label>
                                        <div class="col-lg-8">
                                            <input type="text" class="form-control" placeholder="Other Pickup Instructions" name="other_pickup_instructions" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Delivery Details -->
                            <h5 class="donation-titles mt-4">Delivery Details</h5>

                            <div class="row">
                                <div class="col-md-6 mt-2">
                                    <div class="row">
                                        <label for="delivery_contact_person" class="col-lg-4 col-form-label fw-bold">Contact Person :</label>
                                        <div class="col-lg-8">
                                            <input type="text" class="form-control" placeholder="Contact Person" id="" name="delivery_contact_person" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 mt-2">
                                    <div class="row">
                                        <label for="delivery_address" class="col-lg-4 col-form-label fw-bold">Delivery Address :</label>
                                        <div class="col-lg-8">
                                            <input type="text" class="form-control" placeholder="Delivery Address" id="delivery_address" name="delivery_address" required>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-md-6 mt-2">
                                    <div class="row">
                                        <label for="delivery_authorized_recipient" class="col-lg-4 col-form-label fw-bold">Authorized Recipient :</label>
                                        <div class="col-lg-8">
                                            <input type="text" class="form-control" placeholder="Authorized Recipient" id="delivery_authorized_recipient" name="delivery_authorized_recipient" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mt-2">
                                    <div class="row">
                                        <label for="delivery_contact_no" class="col-lg-4 col-form-label fw-bold">Contact No. :</label>
                                        <div class="col-lg-8">
                                            <input type="text" class="form-control" placeholder="Contact No." id="delivery_contact_no" name="delivery_contact_no" required>
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
                                                <input type="text" class="form-control" placeholder="Delivery Date" id="delivery_date" name="delivery_date" onkeydown="return false" required>
                                                <span class="input-group-text"><i class="fa-solid fa-calendar text-main-color"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mt-2">
                                    <div class="row">
                                        <label for="other_delivery_instructions" class="col-lg-4 col-form-label fw-bold">Delivery Instructions :</label>
                                        <div class="col-lg-8">
                                            <input type="text" class="form-control" placeholder="Delivery Instructions" id="other_delivery_instructions" name="other_delivery_instructions" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End Delivery Details -->
                            <div class="d-flex flex-row-reverse mt-3">
                                <button type="submit" class="btn btn-primary">Save and Proceed</button>
                                <a href="{{ route('product-destruction-list') }}" type="button" class="btn btn-outline-secondary me-2">Go Back</a>
                            </div>
                        </form>
                    @endif

                </div>
                <!-- End Contribution Info Tab  -->
            </div>

        </div>

        <div class="col-md-2">
            <div class="card shadow stats-card">
                <div class="card-header stats-card-header">
                    Statistics
                </div>
                <div class="card-body stats-card-body">
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
                                0
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
                                0
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
                                0
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
                                0
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
                                0
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
                                0
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('custom-js')
<script src="{{asset('js/modules/datepicker/datepicker.js')}} "></script>

<script>
    var $delivery_date = $('#delivery_date');
    var $pickup_date = $('#pickup_date');

    $(document).ready(function () {
        $delivery_date.datepicker({
        autoHide: true,
        });
        $pickup_date.datepicker({
        autoHide: true,
        });
    });
    
</script>
@endsection
