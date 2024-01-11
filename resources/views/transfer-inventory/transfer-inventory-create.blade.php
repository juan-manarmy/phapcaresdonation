@extends('layouts.app')


@section('custom-css')
<link rel="stylesheet" type="text/css" href="{{ asset('css/product-donation.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('js/modules/datepicker/datepicker.css') }} "/>
@endsection

@section('content')
<div class="bg-heading">
    <h4 class="px-4 py-3">Transfer Inventory</h4>
</div>

<div class="bg-initial-details mt-2 px-4 py-4">
    <div class="row">
        <div class="col-md-12">
                <!-- Medicine Donation Forms -->
                <form method="POST" action="{{ route('pd-secondary-details-save', ['contribution_id' => $contribution_id]) }}" class="mt-3">
                    @csrf
                    <h5 class="donation-titles">Pick-Up Details</h5>

                    <div class="row">
                        <div class="col-md-6 mt-2">
                            <div class="row">
                                <label for="" class="col-lg-3 col-form-label sd-label">Date :</label>
                                <div class="col-lg-6">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="pickup_date" id="pickup_date" aria-describedby="basic-addon2" placeholder="Pick-Up Date" readonly required>
                                        <span class="input-group-text" id="basic-addon2"><i class="fa-solid fa-calendar text-main-color"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mt-2">
                            <div class="row">
                                <label for="" class="col-lg-3 col-form-label sd-label">TTIF No. :</label>
                                <div class="col-lg-6">
                                    <input type="text" class="form-control" name="pickup_contact_person" id="pickup_contact_person" placeholder="Contact Person" readonly required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mt-2">
                            <div class="row">
                                <label for="" class="col-lg-3 col-form-label sd-label">Notice To :</label>
                                <div class="col-lg-6">
                                    <input type="text" class="form-control" name="pickup_address" id="pickup_address" placeholder="Notice To" placeholder="Brand Name" required>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mt-2">
                            <div class="row">
                                <label for="" class="col-lg-3 col-form-label sd-label">DAAF No.  :</label>
                                <div class="col-lg-6">
                                    <input type="text" class="form-control" name="pickup_contact_person" id="pickup_contact_person" placeholder="To be provided by ZPC" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h5 class="donation-titles mt-3">Contact Person Details</h5>
                    <div class="row">
                        <div class="col-md-6 mt-2">
                            <div class="row">
                                <label for="" class="col-lg-3 col-form-label sd-label">Contact Person :</label>
                                <div class="col-lg-6">
                                    <input type="text" class="form-control" name="pickup_contact_person" id="pickup_contact_person" placeholder="Contact Person" required>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mt-2">
                            <div class="row">
                                <label for="" class="col-lg-3 col-form-label sd-label">Contact Number :</label>
                                <div class="col-lg-6">
                                <input type="text" class="form-control" name="pickup_contact_no" id="pickup_contact_no" placeholder="Contact Number" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mt-2">
                            <div class="row">
                                <label for="" class="col-lg-3 col-form-label sd-label">Address :</label>
                                <div class="col-lg-6">
                                    <input type="text" class="form-control" name="pickup_address" id="pickup_address" placeholder="Address" placeholder="Brand Name" required>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mt-2">
                            <div class="row">
                                <label for="" class="col-lg-3 col-form-label sd-label">Pick-Up Date :</label>
                                <div class="col-lg-6">
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" name="pickup_date" id="pickup_date" aria-describedby="basic-addon2" placeholder="Pick-Up Date" onkeydown="return false" required>
                                        <span class="input-group-text" id="basic-addon2"><i class="fa-solid fa-calendar text-main-color"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mt-2">
                            <div class="row">
                                <label for="" class="col-lg-3 col-form-label sd-label">Other Pick-Up Instructions :</label>
                                <div class="col-lg-6">
                                    <input type="text" class="form-control" name="pickup_address" id="pickup_address" placeholder="Other Pick-Up Instructions" required>
                                </div>
                            </div>
                        </div>
                    </div>



                    <div class="d-flex flex-row-reverse mt-2">
                        <button type="submit" class="btn btn-primary">Save and Proceed</button>
                        <button onclick="history.back()" type="button" class="btn btn-outline-secondary me-2">Go Back</button>
                    </div>
                </form>
                <!--End Medicine Donation Forms -->
            </div>
        </div>
    </div>
</div>

@endsection

@section('custom-js')

@endsection