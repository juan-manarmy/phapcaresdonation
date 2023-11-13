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
    <h5 class="donation-titles mt-4">Update Allocated Product</h5>
            <hr>
            <form method="POST" action="{{ route('allocation-update-product', ['allocated_product_id' => $allocated_product->id ]) }}" class="mt-3">
                @csrf
                <div class="row">
                    <div class="col-md-6 mt-2">
                        <div class="row">
                            <label for="" class="col-lg-4 col-form-label fw-bold">Principal :</label>
                            <div class="col-lg-8">
                                Member who donated
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mt-2">
                        <div class="row">
                            <label for="" class="col-lg-4 col-form-label fw-bold">Product Code :</label>
                            <div class="col-lg-8">
                                {{ $allocated_product->product_code }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mt-2">
                        <div class="row">
                            <label for="" class="col-lg-4 col-form-label fw-bold">Product Name :</label>
                            <div class="col-lg-8">
                                {{ $allocated_product->product_name }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mt-2">
                        <div class="row">
                            <label for="" class="col-lg-4 col-form-label fw-bold">Expiry Date :</label>
                            <div class="col-lg-8">
                                {{ date('m/d/Y', strtotime($allocated_product->expiry_date)) }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mt-2">
                        <div class="row">
                            <label for="" class="col-lg-4 col-form-label fw-bold">Unit Cost :</label>
                            <div class="col-lg-8">
                                {{ $allocated_product->unit_cost }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mt-2">
                        <div class="row">
                            <label for="" class="col-lg-4 col-form-label fw-bold">Available Stock :</label>
                            <div class="col-lg-8">
                                {{ $available_stock }}
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
                                class="form-control" name="quantity" id="quantity" 
                                placeholder="Quantity"
                                autocomplete="off"
                                value="{{ $allocated_product->quantity }}" 
                                type="number" 
                                onkeypress="return event.charCode != 45" 
                                min="0" max="{{ $available_stock + $allocated_product->quantity }}"
                                onKeyUp = "if(this.value> {{$available_stock + $allocated_product->quantity}} ){this.value='{{$available_stock + $allocated_product->quantity}}';}else if(this.value<0){this.value='0';}" 
                                required >
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex flex-row-reverse mt-4">
                    <button type="submit" class="btn btn-primary">Update and Proceed</button>
                    <!-- <button href="#" type="button" class="btn btn-outline-secondary me-2">Go Back</button> -->
                </div>
            </form>
            <!--End Medicine Donation Forms -->
</div>
@endsection

@section('custom-js')

@endsection
