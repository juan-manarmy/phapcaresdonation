@extends('layouts.app')

@section('custom-css')
<link rel="stylesheet" type="text/css" href="{{ asset('css/product-donation.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('js/modules/datepicker/datepicker.css') }} "/>
@endsection

@section('content')
<!-- <div class="page-heading">
    <h4>Product Donation</h4>
    <div class="border-container">
        <div class="heading-line"></div>
        <hr class="rounded">
    </div>
</div> -->
<div class="bg-heading">
    <h4 class="px-4 py-3">Product Donation</h4>
</div>
<!-- green -->
<!-- dot-status -->


<!-- circle with check -->
<!-- <div class="ic-bg-circle">
    <i class="fa-solid fa-check check-size"></i>
</div> -->
<div class="bg-progress-wrapper">
    <div class="bg-heading-progress">
        <div class="prog-line"></div>
        <div class="row dot-progress">
            <div class="col">
                <div class="text-center">
                    <i class="fa-solid fa-circle dot-size status-green"></i>
                    <p class="status-text mt-2">Initial Details</p>
                </div>
            </div>
            <div class="col">
                <div class="text-center">
                <i class="fa-solid fa-circle dot-size status-green"></i>
                    <p class="status-text mt-2">Donations</p>
                </div>
            </div>
            <div class="col">
                <div class="text-center">
                    <i class="fa-solid fa-circle dot-size status-green"></i>
                    <p class="status-text mt-2">Secondary Details</p>
                </div>
            </div>
            <div class="col">
                <div class="text-center">
                    <i class="fa-solid fa-circle dot-size status-gray"></i>
                    <p class="status-text mt-2">Finish</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="bg-initial-details mt-2 px-4 py-4">
<div class="row">
    <div class="col-md-12">
            <!-- Medicine Donation Forms -->
            <form method="POST" action="{{ route('pd-secondary-details-save', ['contribution_id' => $contribution_id]) }}" class="mt-3">
                @csrf
                <h5 class="donation-titles">Pick-Up Details</h5>
                <div class="row">
                    <div class="col-md-5 mt-2">
                        <div class="row">
                            <label for="" class="col-lg-3 col-form-label sd-label">Address :</label>
                            <div class="col-lg-6">
                                <input type="text" class="form-control" name="pickup_address" id="pickup_address" placeholder="Address" placeholder="Brand Name" required>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mt-2">
                        <div class="row">
                            <label for="" class="col-lg-3 col-form-label sd-label">Contact Person :</label>
                            <div class="col-lg-6">
                                <input type="text" class="form-control" name="pickup_contact_person" id="pickup_contact_person" placeholder="Contact Person" required>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="row">
                    <div class="col-md-5 mt-2">
                        <div class="row">
                            <label for="" class="col-lg-3 col-form-label sd-label">Contact Number :</label>
                            <div class="col-lg-6">
                            <input type="text" class="form-control" name="pickup_contact_no" id="pickup_contact_no" placeholder="Contact Number" required>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mt-2">
                        <div class="row">
                            <label for="" class="col-lg-3 col-form-label sd-label">Date :</label>
                            <div class="col-lg-6">
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" name="pickup_date" id="pickup_date" aria-describedby="basic-addon2" placeholder="Pick-Up Date" onkeydown="return false" required>
                                    <span class="input-group-text" id="basic-addon2"><i class="fa-solid fa-calendar text-main-color"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <h5 class="donation-titles mt-3">Delivery Details</h5>
                <div class="row">
                    <div class="col-md-5 mt-2">
                        <div class="row">
                            <label for="" class="col-lg-3 col-form-label sd-label">Address :</label>
                            <div class="col-lg-6">
                                <input type="text" class="form-control" name="delivery_address" id="delivery_address" placeholder="Address" required>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mt-2">
                        <div class="row">
                            <label for="" class="col-lg-3 col-form-label sd-label">Contact Person :</label>
                            <div class="col-lg-6">
                                <input type="text" class="form-control" name="delivery_contact_person" id="delivery_contact_person" placeholder="Contact Person" required>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="row">
                    <div class="col-md-5 mt-2">
                        <div class="row">
                            <label for="" class="col-lg-3 col-form-label sd-label">Contact Number :</label>
                            <div class="col-lg-6">
                            <input type="text" class="form-control" name="delivery_contact_no" id="delivery_contact_no" placeholder="Contact Number" required>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mt-2">
                        <div class="row">
                            <label for="" class="col-lg-3 col-form-label sd-label">Date :</label>
                            <div class="col-lg-6">
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" name="delivery_date" id="delivery_date" aria-describedby="basic-addon2" placeholder="Delivery Date" onkeydown="return false" required>
                                    <span class="input-group-text" id="basic-addon2"><i class="fa-solid fa-calendar text-main-color"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <h5 class="donation-titles mt-3">Request By</h5>
                <div class="row">
                    <div class="col-md-5 mt-2">
                        <div class="row">
                            <label for="" class="col-lg-3 col-form-label sd-label">Requester :</label>
                            <div class="col-lg-6">
                                <select class="form-control form-select " aria-label="Default select example">
                                    <option value="1">Dela Cruz, Juan</option>
                                    <option value="2">Super, Admin</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mt-2">
                        <div class="row">
                            <label for="" class="col-lg-3 col-form-label sd-label">Telephone No. :</label>
                            <div class="col-lg-6">
                                <input type="text" class="form-control" name="tel_no" id="tel_no" placeholder="Telephone No." required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">

                    <div class="col-md-5 mt-2">
                        <div class="row">
                            <label for="" class="col-lg-3 col-form-label sd-label">Fax No. :</label>
                            <div class="col-lg-6">
                            <input type="text" class="form-control" name="fax_no" id="fax_no" placeholder="Fax No." required>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mt-2">
                        <div class="row">
                            <label for="" class="col-lg-3 col-form-label sd-label">Email :</label>
                            <div class="col-lg-6">
                            <input type="text" class="form-control" name="email" id="email" placeholder="Email" required>
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



