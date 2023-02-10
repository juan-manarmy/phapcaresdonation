@extends('layouts.app')

@section('custom-css')
<link rel="stylesheet" type="text/css" href="{{ asset('css/product-donation.css') }}"/>
<style>
    .square {
        background: rgb(2,131,111);
        background: -moz-linear-gradient(180deg, rgba(2,131,111,1) 0%, rgba(2,132,70,1) 100%);
        background: -webkit-linear-gradient(180deg, rgba(2,131,111,1) 0%, rgba(2,132,70,1) 100%);
        background: linear-gradient(180deg, rgba(2,131,111,1) 0%, rgba(2,132,70,1) 100%);
        filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="#02836f",endColorstr="#028446",GradientType=1);

        width: 120px;
        height: 120px;
        border-radius:20px;
    }

    .square:hover {
        background: rgb(2,80,42);
        background: -moz-linear-gradient(180deg, rgba(2,80,42,1) 0%, rgba(2,91,64,1) 100%);
        background: -webkit-linear-gradient(180deg, rgba(2,80,42,1) 0%, rgba(2,91,64,1) 100%);
        background: linear-gradient(180deg, rgba(2,80,42,1) 0%, rgba(2,91,64,1) 100%);
        filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="#02502a",endColorstr="#025b40",GradientType=1);
    }

    .home-ic {
        font-size:50px;
        color:white;
    }

    .home-text {
        color:#028446;
    }

    a {
        text-decoration: none;
    }
</style>
@endsection

@section('content')

<div class="bg-initial-details mt-2 px-4 py-4">
    <div class="container">
        <div class="row">
            <h5>Welcome to quick access panel.</h5>
        </div>
        <div class="row mt-4">
            <div class="col-md-3">
                <a href="{{ route('pd-initial-details') }}" class=""> 
                    <div class="square mx-auto">
                        <div class="d-flex justify-content-center align-items-center h-100">
                            <i class="fa-solid fa-hand-holding-heart home-ic"></i>
                        </div>
                    </div>
                    
                    <div class="text-center mt-3 home-text">
                        Product Donation
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('call-for-support') }}" class=""> 
                    <div class="square mx-auto">
                        <div class="d-flex justify-content-center align-items-center h-100">
                            <i class="fa-solid fa-headset home-ic"></i>
                        </div>
                    </div>
                    
                    <div class="text-center mt-3 home-text">
                        Call for Support
                    </div>
                </a>
            </div>

            <div class="col-md-3">
                <a href="{{ route('all-users') }}" class=""> 
                    <div class="square mx-auto">
                        <div class="d-flex justify-content-center align-items-center h-100">
                            <i class="fa-solid fa-users home-ic"></i>
                        </div>
                    </div>
                    
                    <div class="text-center mt-3 home-text">
                        Users
                    </div>
                </a>
            </div>

            <div class="col-md-3">
                <a href="{{ route('contribution-list') }}" class=""> 
                    <div class="square mx-auto">
                        <div class="d-flex justify-content-center align-items-center h-100">
                            <i class="fa-solid fa-hand-holding-medical home-ic"></i>
                        </div>
                    </div>
                    
                    <div class="text-center mt-3 home-text">
                        Contributions
                    </div>
                </a>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-3">
                <a href="{{ route('allocation-list') }}" class="">
                    <div class="square mx-auto">
                        <div class="d-flex justify-content-center align-items-center h-100">
                            <i class="fa-solid fa-box-open home-ic"></i>
                        </div>
                    </div>
                    
                    <div class="text-center mt-3 home-text">
                        Allocations
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{route('product-destruction-list')}}" class=""> 
                    <div class="square mx-auto">
                        <div class="d-flex justify-content-center align-items-center h-100">
                            <i class="fa-solid fa-rectangle-xmark home-ic"></i>
                        </div>
                    </div>
                    
                    <div class="text-center mt-3 home-text">
                        Product Destruction
                    </div>
                </a>
            </div>

            <div class="col-md-3">
                <a href="{{route('inventory-list')}}" class=""> 
                    <div class="square mx-auto">
                        <div class="d-flex justify-content-center align-items-center h-100">
                            <i class="fa-solid fa-boxes-stacked home-ic"></i>
                        </div>
                    </div>
                    
                    <div class="text-center mt-3 home-text">
                        Inventory
                    </div>
                </a>
            </div>

            <div class="col-md-3">
                <a href="{{route('reports-list')}}" class=""> 
                    <div class="square mx-auto">
                        <div class="d-flex justify-content-center align-items-center h-100">
                            <i class="fa-solid fa-folder-open home-ic"></i>
                        </div>
                    </div>
                    
                    <div class="text-center mt-3 home-text">
                        Reports
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

@endsection

@section('custom-js')
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
