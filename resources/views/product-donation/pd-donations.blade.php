@extends('layouts.app')

@section('custom-css')
<link rel="stylesheet" type="text/css" href="{{ asset('js/modules/datepicker/datepicker.css') }} "/>
<link rel="stylesheet" type="text/css" href="{{ asset('css/product-donation.css') }}"/>

@endsection

@section('content')

<div class="bg-heading">
    <h4 class="px-4 py-3">Product Donation</h4>
</div>

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
                    <p class="dot-status mt-2">Donations</p>
                </div>
            </div>
            <div class="col">
                <div class="text-center">
                    <i class="fa-solid fa-circle dot-size status-gray"></i>
                    <p class="status-text mt-2">
                    Secondary Details
                    </p>
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
    <pd-donation-form contribution_no='{{ $contribution_no }}' contribution_id='{{ $contribution_id }}'></pd-donation-form>
    <!-- Toast -->
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
        <div id="liveToast" class="toast bg-danger text-white" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    Donations are empty.
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>
</div>


@endsection

@section('custom-js')
<script src="{{asset('js/modules/datepicker/datepicker.js')}} "></script>

<script>
    var toastTrigger = document.getElementById('liveToastBtn')
    var toastLiveExample = document.getElementById('liveToast')
    if (toastTrigger) {
        toastTrigger.addEventListener('click', function () {
            var toast = new bootstrap.Toast(toastLiveExample)
            toast.show()
        })
    }
    
</script>
@endsection
