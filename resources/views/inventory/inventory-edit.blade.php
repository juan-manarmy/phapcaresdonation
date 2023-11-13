@extends('layouts.app')

@section('custom-css')
<link rel="stylesheet" type="text/css" href="{{ asset('js/modules/datepicker/datepicker.css') }} "/>
<link rel="stylesheet" type="text/css" href="{{ asset('css/product-donation.css') }}"/>
@endsection

@section('content')

<div class="bg-heading">

    <div class="d-flex justify-content-between">
        <h4 class="px-4 py-3 mb-0">Update Inventory Product</h4>
    </div>
</div>

<div class="bg-initial-details mt-2 px-4 py-4">
    <div class="row">
            <h5 class="donation-titles">Allocation Details</h5>
            <!-- Contribution Details Forms -->
            <form method="POST" action="{{ route('inventory-update', ['inventory_id' => $inventory->id ]) }}">                       
                @csrf
                <div class="row">
                    <div class="col-md-6 mt-2">
                        <div class="row">
                            <label for="" class="col-lg-4 col-form-label fw-bold">Product Type :</label>
                            <div class="col-lg-8">
                                <input type="text" readonly class="form-control-plaintext" placeholder="product_type" name="product_type" value="{{$inventory->product_type}}" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mt-2">
                        <div class="row">
                            <label for="" class="col-lg-4 col-form-label fw-bold">Product Code :</label>
                            <div class="col-lg-8">
                                <input type="text" readonly class="form-control-plaintext" placeholder="product_code" name="product_code" value="{{ $inventory->product_code }}" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mt-2">
                        <div class="row">
                            <label for="" class="col-lg-4 col-form-label fw-bold">Product Name :</label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control" placeholder="Product Name" name="product_name" value="{{ $inventory->product_name }}" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mt-2">
                        <div class="row">
                            <label for="" class="col-lg-4 col-form-label fw-bold">Lot No. :</label>
                            <div class="col-lg-8">
                                <input type="text" readonly class="form-control-plaintext" placeholder="lot_no" name="lot_no" value="{{ $inventory->lot_no }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mt-2">
                        <div class="row">
                            <label for="" class="col-lg-4 col-form-label fw-bold">Unit Cost :</label>
                            <div class="col-lg-8">
                                <input type="text" readonly class="form-control-plaintext" placeholder="unit_cost" name="unit_cost" value="{{ $inventory->unit_cost }}" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mt-2">
                        <div class="row">
                            <label for="" class="col-lg-4 col-form-label fw-bold">Quantity :</label>
                            <div class="col-lg-8">
                                <input                                 
                                type="number" 
                                onkeypress="return event.charCode != 45" 
                                class="form-control" name="quantity" 
                                value="{{ $inventory->quantity }}" 
                                required>
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
                                    <input type="text" class="form-control" placeholder="MFG Date" id="mfg_date" name="mfg_date" value="{{date('m/d/Y', strtotime($inventory->mfg_date))}}" onkeydown="return false" required>
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
                                    <input type="text" class="form-control" placeholder="Expiry Date" id="expiry_date" name="expiry_date" value="{{date('m/d/Y', strtotime($inventory->expiry_date))}}" onkeydown="return false" required>
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
                                <input type="text" class="form-control" placeholder="" name="drug_reg_no" value="{{ $inventory->drug_reg_no }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mt-2">
                        <div class="row">
                            <label for="" class="col-lg-4 col-form-label fw-bold">Job No.:</label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control" placeholder="Job No." id="" name="job_no" value="{{ $inventory->job_no }}" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex flex-row-reverse mt-3">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <button type="button" class="btn btn-outline-secondary me-2">Go Back</button>
                </div>
            </form>
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
