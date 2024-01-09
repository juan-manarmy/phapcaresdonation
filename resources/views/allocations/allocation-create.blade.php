@extends('layouts.app')

@section('custom-css')
<link rel="stylesheet" type="text/css" href="{{ asset('js/modules/datepicker/datepicker.css') }} "/>
<link rel="stylesheet" type="text/css" href="{{ asset('css/product-donation.css') }}"/>
@endsection

@section('content')

<div class="bg-heading">
    <h4 class="px-4 py-3">Create Allocation</h4>
</div>

<div class="bg-initial-details mt-2 px-4 py-4">
    <div class="row">
        <div class="col-md-10">
            <h5 class="donation-titles mt-2">Allocation Details</h5>

            @if(isset($allocation))
                <form method="POST" action="{{ route('allocation-create-save') }}" class="mt-3">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mt-2">
                            <div class="row">
                                <label for="" class="col-lg-4 col-form-label fw-bold">Allocation No. :</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" name="allocation_no" value="{{ $allocation->allocation_no }}" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="row">
                                <label for="" class="col-lg-4 col-form-label fw-bold">DNA No. :</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" name="dna_no" value="{{ $allocation->dna_no }}" readonly>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mt-2">
                            <div class="row">
                                <label for="" class="col-lg-4 col-form-label fw-bold">Beneficiary :</label>
                                <div class="col-lg-8">
                                    <select class="form-control form-select" name="beneficiary_id" id="beneficiary_id" aria-label="Default select example">
                                        <option value="" disabled selected hidden>Choose a Beneficiary</option>
                                        <option value="0"><p class="font-weight-bold">*Create New Beneficiary</p></option>
                                        @foreach($beneficiaries as $item)
                                            <option {{ $allocation->beneficiary_id == $item->id ? "selected" : "" }} value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mt-2" id="new_beneficiary_div" style="display: none;">
                            <div class="row">
                                <label for="" class="col-lg-4 col-form-label fw-bold">New Beneficiary :</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" name="new_beneficiary" id="new_beneficiary" value="">
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
                                    <input type="text" class="form-control" placeholder="Authorized Representative" name="authorized_representative" value="{{ $allocation->authorized_representative }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mt-2">
                            <div class="row">
                                <label for="" class="col-lg-4 col-form-label fw-bold">Position :</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" placeholder="Position" name="position" value="{{ $allocation->position }}" required>
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
                                    <input type="text" class="form-control" placeholder="Contact Number" name="contact_number" value="{{ $allocation->contact_number }}" required>
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
                                    <input type="text" class="form-control" placeholder="Delivery Address" name="delivery_address" value="{{ $allocation->delivery_address }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mt-2">
                            <div class="form-group row mt-2">
                                <label for="" class="col-lg-4 col-form-label fw-bold">Date :</label>
                                <div class="col-lg-8">
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" placeholder="Delivery Date" name="delivery_date" id="delivery_date" onkeydown="return false" value="{{ date('m/d/Y', strtotime($allocation->delivery_date)) }}" required>
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
                                    <input type="text" class="form-control" placeholder="Delivery Instructions" name="other_delivery_instructions" value="{{ $allocation->other_delivery_instructions }}" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Delivery Details -->
                    <div class="d-flex flex-row-reverse mt-3">
                        <!-- <button type="button" class="btn btn-secondary">Cancel</button> -->
                        <button type="submit" class="btn btn-primary">Save and Proceed</button>
                        <button type="submit" formaction="{{ route('allocation-cancel', ['allocation_id' => $allocation->id] ) }}" type="button" class="btn btn-outline-secondary me-2">Cancel</button>
                        <a href="{{ route('allocation-list') }}" type="button" class="btn btn-outline-secondary me-2">Go Back</a>
                    </div>
                </form>
            @else
                <form method="POST" action="{{ route('allocation-create-save') }}" class="mt-3">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mt-2">
                            <div class="row">
                                <label for="" class="col-lg-4 col-form-label fw-bold">Allocation No. :</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" name="allocation_no" value="{{ $allocation_no }}" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="row">
                                <label for="" class="col-lg-4 col-form-label fw-bold">DNA No. :</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" name="dna_no" value="{{ $new_dna_no }}" readonly>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mt-2">
                            <div class="row">
                                <label for="" class="col-lg-4 col-form-label fw-bold">Beneficiary :</label>
                                <div class="col-lg-8">
                                    <select class="form-control form-select" name="beneficiary_id" id="beneficiary_id" aria-label="Default select example">
                                        <!-- <option>New Beneficiary</option> -->
                                        <option value="" disabled selected hidden>Choose a Beneficiary</option>
                                        <option value="0"><p class="font-weight-bold">*Create New Beneficiary</p></option>
                                        @foreach($beneficiaries as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mt-2" id="new_beneficiary_div" style="display: none;">
                            <div class="row">
                                <label for="" class="col-lg-4 col-form-label fw-bold">New Beneficiary :</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" name="new_beneficiary" id="new_beneficiary" value="">
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
                                        <option value="Zuellig Pharma Corp.">Zuellig Pharma Corp.</option>
                                        <option value="Metro Drug">Metro Drug</option>
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
                                    <input type="text" class="form-control" placeholder="Authorized Representative" name="authorized_representative" required>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mt-2">
                            <div class="row">
                                <label for="" class="col-lg-4 col-form-label fw-bold">Position :</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" placeholder="Position" name="position" required>
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
                                    <input type="text" class="form-control" placeholder="Contact Number" name="contact_number" required>
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
                                    <input type="text" class="form-control" placeholder="Delivery Address" name="delivery_address" required>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mt-2">
                            <div class="form-group row mt-2">
                                <label for="" class="col-lg-4 col-form-label fw-bold">Date :</label>
                                <div class="col-lg-8">
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" placeholder="Delivery Date" name="delivery_date" id="delivery_date" onkeydown="return false" required>
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
                                    <input type="text" class="form-control" placeholder="Delivery Instructions" name="other_delivery_instructions" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Delivery Details -->
                    <div class="d-flex flex-row-reverse mt-3">
                        <!-- <button type="button" class="btn btn-secondary">Cancel</button> -->
                        <button type="submit" class="btn btn-primary">Save and Proceed</button>
                        <a href="{{ route('allocation-list') }}" type="button" class="btn btn-outline-secondary me-2">Go Back</a>

                    </div>
                </form>
            @endif

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
    var $startDate = $('#delivery_date');
    $(document).ready(function () {
        $startDate.datepicker({
        autoHide: true,
        });
    });

    document.getElementById('beneficiary_id').addEventListener('change', function () {
        var style = this.value == 0 ? 'block' : 'none';
        document.getElementById('new_beneficiary_div').style.display = style;
        document.getElementById('new_beneficiary').value = '';
    });

</script>
@endsection