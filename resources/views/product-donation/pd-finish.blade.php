@extends('layouts.app')

@section('custom-css')
<link rel="stylesheet" type="text/css" href="{{ asset('css/product-donation.css') }}"/>
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
                    <i class="fa-solid fa-circle dot-size status-green"></i>
                    <p class="status-text mt-2">Finish</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="bg-initial-details mt-2 px-4 py-4">
    <div class="row mt-3">
        <div class="col">
            <div class="text-center">
                <h4 class="donation-titles mt-2"><i class="fa-solid fa-circle-check finish-ic text-main-color"></i> Product Donation Successful!</h4>
                <p>Your donation is for approval. You can track the donation in Contribution page.</p>
                <p>Thank you very much!</p>
            </div>
        </div>
    </div>
    

    <div class="d-flex justify-content-center mb-3">
        <a href="{{ route('pd-initial-details') }}" type="button" class="btn btn-outline-success">Create Donation</a>
    </div>

</div>



@endsection
