@extends('layouts.app')

@section('custom-css')
<link rel="stylesheet" type="text/css" href="{{ asset('js/modules/datepicker/datepicker.css') }} "/>
<link rel="stylesheet" type="text/css" href="{{ asset('css/product-donation.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('css/custom-table.css') }}"/>
@endsection

@section('content')

<div class="bg-heading">
    <h4 class="px-4 py-3">Product Donation</h4>
</div>

<div class="bg-initial-details mt-2 px-4 py-4">
    <h5 class="donation-titles mt-4">Update Product</h5>
            <hr>
            <!-- Medicine Donation Forms -->
            <form method="POST" action="{{ route('contribution-donation-update', ['donation_id' => $donation->id]) }}" class="mt-3">
                @csrf
                <!-- <input type="text" name="status" id="status" value="{{ $donation->status }}" hidden> -->
                <div class="row mt-2">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="row">
                            <label class="col-lg-4 col-form-label fw-bold" for="">Product Type :</label>
                            <div class="col-lg-8">
                                <input type="text" name="product_type" id="product_type" value="{{ $donation->product_type }}" hidden>
                                @if ($donation->product_type == 1)
                                    Medicine / Vaccine
                                @else
                                    Promotional Materials
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mt-2">
                        <div class="row">
                            <label for="" class="col-lg-4 col-form-label fw-bold">Product Code :</label>
                            <div class="col-lg-8">
                            <input type="text" class="form-control" name="product_code" id="product_code" placeholder="Product Code" value="{{ $donation->product_code }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mt-2">
                        <div class="row">
                            <label for="" class="col-lg-4 col-form-label fw-bold">Brand Name / Product Name :</label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control" name="product_name" id="product_name" placeholder="Brand Name / Product Name" value="{{ $donation->product_name }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mt-2">
                        <div class="row">
                            <label for="" class="col-lg-4 col-form-label fw-bold">Generic Name :</label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control" name="generic_name" id="generic_name" placeholder="Generic Name" value="{{ $donation->generic_name }}">
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6 mt-2">
                        <div class="row">
                            <label for="" class="col-lg-4 col-form-label fw-bold">Strength :</label>
                            <div class="col-lg-8">
                            <input type="text" class="form-control" name="strength" id="strength" placeholder="Strength" value="{{ $donation->strength }}">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mt-2">
                        <div class="row">
                            <label for="" class="col-lg-4 col-form-label fw-bold">Dosage Form :</label>
                            <div class="col-lg-8">
                            <input type="text" class="form-control" name="dosage_form" id="dosage_form" placeholder="Dosage Form" value="{{ $donation->dosage_form }}">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mt-2">
                        <div class="row">
                            <label for="" class="col-lg-4 col-form-label fw-bold">Package Size :</label>
                            <div class="col-lg-8">
                            <input type="text" class="form-control" name="package_size" id="package_size" placeholder="Package Size" value="{{ $donation->package_size }}">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mt-2">
                        <div class="row">
                            <label for="" class="col-lg-4 col-form-label fw-bold">Quantity :</label>
                            <div class="col-lg-8">
                            <input type="text" class="form-control" name="quantity" id="quantity" placeholder="Quantity" value="{{ $donation->quantity }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mt-2">
                        <div class="row">
                            <label for="" class="col-lg-4 col-form-label fw-bold">Lot/Batch No. :</label>
                            <div class="col-lg-8">
                            <input type="text" class="form-control" name="lot_no" id="lot_no" placeholder="Lot/Batch No." value="{{ $donation->lot_no }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mt-2">
                        <div class="row">
                            <label for="" class="col-lg-4 col-form-label fw-bold">MFG Date :</label>
                            <div class="col-lg-8">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="mfg_date" id="mfg_date" placeholder="MFG Date" value="{{ date('m/d/Y', strtotime($donation->mfg_date)) }}">
                                    <span class="input-group-text"><i class="fa-solid fa-calendar text-main-color"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mt-2">
                        <div class="row">
                            <label for="" class="col-lg-4 col-form-label fw-bold">Expiry Date :</label>
                            <div class="col-lg-8">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="expiry_date" id="expiry_date" placeholder="Expiry Date" value="{{ date('m/d/Y', strtotime($donation->expiry_date)) }}">
                                    <span class="input-group-text"><i class="fa-solid fa-calendar text-main-color"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mt-2">
                        <div class="row">
                            <label for="" class="col-lg-4 col-form-label fw-bold">Drug Reg. No. :</label>
                            <div class="col-lg-8">
                            <input type="text" class="form-control" name="drug_reg_no" id="drug_reg_no" placeholder="Drug Reg. No." value="{{ $donation->drug_reg_no }}">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mt-2">
                        <div class="row">
                            <label for="" class="col-lg-4 col-form-label fw-bold">Unit Cost/ Trade Price :</label>
                            <div class="col-lg-8">
                            <input type="text" class="form-control" name="unit_cost" id="unit_cost" placeholder="Unit Cost/ Trade Price" value="{{ $donation->unit_cost }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mt-2">
                        <div class="row">
                            <label for="" class="col-lg-4 col-form-label fw-bold">Medicine Status :</label>
                            <div class="col-lg-8">
                            <input type="text" class="form-control" name="medicine_status" id="medicine_status" placeholder="Medicine Status" value="{{ $donation->medicine_status }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mt-2">
                        <div class="row">
                            <label for="" class="col-lg-4 col-form-label fw-bold">Job No. :</label>
                            <div class="col-lg-8">
                            <input type="text" class="form-control" name="job_no" id="job_no" placeholder="Job No." value="{{ $donation->job_no }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mt-2">
                        <div class="row">
                            <label for="" class="col-lg-4 col-form-label fw-bold">UOM :</label>
                            <div class="col-lg-8">
                            <input type="text" class="form-control" name="uom" id="uom" placeholder="UOM" value="{{ $donation->uom }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mt-2">
                        <div class="row">
                            <label for="" class="col-lg-4 col-form-label fw-bold">Remarks :</label>
                            <div class="col-lg-8">
                            <input type="text" class="form-control" name="remarks" id="remarks" placeholder="Remarks" value="{{ $donation->remarks }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex flex-row-reverse mt-4">
                    <button type="submit" class="btn btn-primary">Update and Proceed</button>
                    <button href="#" type="button" class="btn btn-outline-secondary me-2">Go Back</button>
                </div>
            </form>
            <!--End Medicine Donation Forms -->


        </div>
</div>
@endsection

@section('custom-js')
<script src="{{asset('js/modules/datepicker/datepicker.js')}} "></script>

<script>
    var $mfg_date = $('#mfg_date');
    var $expiry_date = $('#expiry_date');

    $(document).ready(function () {
        $mfg_date.datepicker({
            autoHide: true,
        });

        $expiry_date.datepicker({
            autoHide: true,
        });
    });
</script>
@endsection
