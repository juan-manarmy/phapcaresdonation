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
                <form method="POST" action="{{ route('transfer-inventory-save', ['contribution_id' => $contribution_id]) }}" class="mt-3">
                    @csrf
                    <h5 class="donation-titles">Pick-Up Details</h5>

                    <div class="row">
                        <div class="col-md-6 mt-2">
                            <div class="row">
                                <label for="" class="col-lg-4 col-form-label sd-label">Date :</label>
                                <div class="col-lg-6">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="transfer_date" id="transfer_date" aria-describedby="basic-addon2" value="{{ date('m/d/Y', strtotime($current_date)) }}" readonly required>
                                        <span class="input-group-text" id="basic-addon2"><i class="fa-solid fa-calendar text-main-color"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mt-2">
                            <div class="row">
                                <label for="" class="col-lg-4 col-form-label sd-label">TTIF No. :</label>
                                <div class="col-lg-6">
                                    <input type="text" class="form-control" name="ttif_no" id="ttif_no" value="{{ $new_ttif_no }}" placeholder="ttif_no" readonly required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mt-2">
                            <div class="row">
                                <label for="" class="col-lg-4 col-form-label sd-label">Notice To :</label>
                                <div class="col-lg-6">
                                    <input type="text" class="form-control" name="notice_to" id="notice_to" placeholder="Notice To" required>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mt-2">
                            <div class="row">
                                <label for="" class="col-lg-4 col-form-label sd-label">DAAF No.  :</label>
                                <div class="col-lg-6">
                                    <input type="text" class="form-control" name="daff_no" id="daff_no" placeholder="To be provided by ZPC" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h5 class="donation-titles mt-3">Contact Person Details</h5>

                    <div class="row">
                        <div class="col-md-6 mt-2">
                            <div class="row">
                                <label for="" class="col-lg-4 col-form-label sd-label">Name of Organization :</label>
                                <div class="col-lg-6">
                                    <input type="text" class="form-control" name="pickup_organization_name" id="pickup_organization_name" placeholder="Name of Organization" required>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mt-2">
                            <div class="form-group row mt-2">
                                <label class="col-lg-4 col-form-label sd-label" for="inventory_location">Inventory Location :</label>
                                <div class="col-lg-6">
                                    <select class="form-select" name="inventory_location">
                                        <option value="ZPC">ZPC</option>
                                        <option value="OCP">OCP</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>



                    <div class="row">
                        <div class="col-md-6 mt-2">
                            <div class="row">
                                <label for="" class="col-lg-4 col-form-label sd-label">Contact Person :</label>
                                <div class="col-lg-6">
                                    <input type="text" class="form-control" name="pickup_contact_person" id="pickup_contact_person" placeholder="Contact Person" required>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mt-2">
                            <div class="row">
                                <label for="" class="col-lg-4 col-form-label sd-label">Contact Number :</label>
                                <div class="col-lg-6">
                                <input type="text" class="form-control" name="pickup_contact_no" id="pickup_contact_no" placeholder="Contact Number" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mt-2">
                            <div class="row">
                                <label for="" class="col-lg-4 col-form-label sd-label">Address :</label>
                                <div class="col-lg-6">
                                    <input type="text" class="form-control" name="pickup_address" id="pickup_address" placeholder="Address" required>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mt-2">
                            <div class="row">
                                <label for="" class="col-lg-4 col-form-label sd-label">Pick-Up Date :</label>
                                <div class="col-lg-6">
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" name="pickup_date" id="pickup_date" aria-describedby="basic-addon2" placeholder="Pick-Up Date" onkeydown="return false" autocomplete="off" required>
                                        <span class="input-group-text" id="basic-addon2"><i class="fa-solid fa-calendar text-main-color"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mt-2">
                            <div class="row">
                                <label for="" class="col-lg-4 col-form-label sd-label">Other Pick-Up Instructions :</label>
                                <div class="col-lg-6">
                                    <input type="text" class="form-control" name="pickup_other_instruction" id="pickup_other_instruction" placeholder="Other Pick-Up Instructions" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h5 class="donation-titles mt-4">Donation Details</h5>
                    <table class="table mt-3">
                        <thead class="theader">
                            <tr>
                                <th scope="col">Select</th>
                                <th scope="col">Product Name</th>
                                <th scope="col">Lot No.</th>
                                <th scope="col">Quantity</th>
                                <th scope="col">Quantity to Transfer</th>
                                <th scope="col">Unit Cost</th>
                                <th scope="col">Total</th>
                                <th scope="col">Expiry Date</th>
                                <!-- <th scope="col">Actions</th> -->
                            </tr>
                        </thead>
                        <tbody>
                            @if(!$donations->isEmpty())
                                @foreach ($donations as $donation)
                                    @if($donation->status == 1 && $donation->product_type != 3)
                                    <tr>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="1" id="{{$donation->id}}" name="inputs[{{ $donation->id }}][is_selected]">
                                                <label class="form-check-label" for="flexCheckChecked">
                                                </label>
                                            </div>
                                        </td>
                                        <td>{{ $donation->product_name }}
                                            <input value="{{ $donation->id }}" name="inputs[{{ $donation->id }}][id]" hidden>  
                                            <input value="{{ $donation->product_name }}" name="inputs[{{ $donation->id }}][product_name]" hidden>
                                        </td>
                                        <td>{{ $donation->lot_no }} <input value="{{ $donation->lot_no }}" name="inputs[{{ $donation->id }}][lot_no]" hidden></td>
                                        <td>{{ $donation->quantity }}</td>
                                        <td>
                                            <input type="number" class="form-control" name="inputs[{{ $donation->id }}][transfer_quantity]" id="qt_{{ $donation->id }}" name="inputs[{{ $donation->id }}][transfer_quantity]" max="{{ $donation->quantity }}" placeholder="/{{ $donation->quantity }}" onkeyup="checkMaxInput(this, {{ $donation->quantity }})" >
                                        </td>
                                        <td>{{ $donation->unit_cost }} <input value="{{ $donation->unit_cost }}" name="inputs[{{ $donation->id }}][unit_cost]" hidden></td>
                                        <td>{{ $donation->total }}</td>
                                        <td>
                                            <input value="{{ $donation->expiry_date }}" name="inputs[{{ $donation->id }}][expiry_date]" hidden>
                                            @if($donation->expiry_date != null)
                                                {{ date('F, d Y', strtotime($donation->expiry_date)) }}
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
                        </tbody>
                    </table>

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
    var $pickup_date = $('#pickup_date');
    function checkMaxInput(e, quantity) {
        if(e.value > quantity) {
            var input = document.getElementById(e.id);
            input.value = quantity;
        }
    }
    $(document).ready(function () {
        $pickup_date.datepicker({
        autoHide: true,
        });
    });
</script>
@endsection