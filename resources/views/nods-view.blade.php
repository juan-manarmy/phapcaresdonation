@extends('layouts.app')


@section('custom-css')
<link rel="stylesheet" type="text/css" href="{{ asset('js/DataTables/datatables.min.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('css/custom-table.css') }}"/>
<style>

    label {
        font-family: neosans_std_medium;
        font-size:13.5px;
    }

    .items-title {
        font-family: neosans_std_regular;
        font-size:13.5px;
        color: gray;
    }

    .form-title {
        font-family: neosans_std_medium;
        font-size:13.5px;
        color:var(--main-green);
    }

    .items-value {
        font-family: neosans_std_medium;
        font-size:13.5px;
    }

    .form-control {
        font-size:.80rem;
    }

    .form-text {
        font-size:.80rem;
    }
    .update-link {
        font-family: neosans_std_medium;
        color: var(--main-green);
        font-size:13.5px;

    }
</style>
@endsection



@section('content')
<div class="page-heading">
    <div class="d-flex align-items-baseline justify-content-between">
        <h4>Notice of Donations Form</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"></li>
                <li class="breadcrumb-item active" aria-current="page">Notice of Donations Forms</li>
            </ol>
        </nav>
    </div>
    

    <div class="border-container">
        <div class="heading-line"></div>
        <hr class="rounded">
    </div>
</div>

<div class="container">

    @if (session('nods-item-updated'))
    <div class="alert alert-success" role="alert">
        {{ session('nods-item-updated') }}
    </div>
    @endif

    @if ($form[0]->approval_status != 1 && $form[0]->approval_status != 2)
    <div class="alert alert-warning" role="alert">
        <div class="fw-bold">
            This Form is Awaiting for Approval.
        </div>    
    </div>

    <div class="row">
        <div class="col-6">
            <div class="d-grid">
                <a type="button" href="{{ route('nods-change-approval-status', ['id' => $form[0]->id, 'approvalCode' => 3 ]) }}" class="btn btn-approval btn-warning text-white" ><i class="fas fa-exclamation-triangle"></i> Awaiting for Approval</a>
            </div>
        </div>
        <div class="col-6">
            <div class="d-grid">
                <a type="button" href="{{ route('nods-change-approval-status', ['id' => $form[0]->id, 'approvalCode' => 2 ]) }}" class="btn btn-approval btn-danger text-white" ><i class="fas fa-exclamation-triangle"></i> Reject</a>
            </div>
        </div>
        <div class="col-6 ">
            <div class="d-grid">
                <a type="button" href="{{ route('nods-change-approval-status', ['id' => $form[0]->id , 'approvalCode' => 1 ]) }}" class="btn btn-approval btn-approve"><i class="fa-solid fa-circle-check"></i> Approve</a>
            </div>
        </div>
    </div>
    @endif


    <div class="card shadow mt-2">
        <div class="card-body py-4 px-5">
            <div class="d-flex flex-row-reverse">
                <div class="form-title">Form ID: #{{$form[0]->id }} </div>
            </div>
            <div class="row mt-2">
                <div class="col-lg-6 mt-2">
                    <div class="d-flex justify-content-between">
                        <label for="" class="form-label">Event : </label>
                        <div class=""> {{$form[0]->title }}</div>
                    </div>
                </div>
                <div class="col-lg-6 mt-2">
                    <div class="d-flex justify-content-between">
                    <label for="" class="form-label">Date Submitted : </label>
                    <div class=""> {{date('M, d Y', strtotime($form[0]->created_at))}} </div>
                    </div>

                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 mt-2">
                    <div class="d-flex justify-content-between">
                        <label for="" class="form-label">Submitted By : </label>
                        <div class=""> {{$form[0]->last_name }}, {{$form[0]->first_name }} </div>
                    </div>
                </div>
                <div class="col-lg-6 mt-2">
                    <div class="d-flex justify-content-between">
                        <label for="" class="form-label">Company : </label>
                        <div class=""> {{$form[0]->member_name }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
                        
    @foreach($nods as $item)
    <div class="card shadow mt-2 ">
        <div class="card-header py-3 px-5 bg-white">
            <div class="d-flex flex-row-reverse">
                <a href="{{ route('nod-form-view-item', ['id' => $form[0]->id, 'itemId' => $item->id]) }}"class="update-link">Update</a>
            </div>
        </div>
        <div class="card-body pt-4 pb-4 px-5">
            <div class="row mt-1">
                <div class="col-lg-4">
                    <div class="mb-1">
                        <div class="items-title">Brand Name</div>
                        <div class="items-value mt-2">{{$item->brand_name}}</div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="mb-1">
                        <div class="items-title">Generic Brand Name</div>
                        <div class="items-value mt-2">{{$item->generic_name}}</div>
                    </div>    
                </div>

                <div class="col-lg-4">
                    <div class="mb-1">
                        <div class="items-title">Strength</div>
                        <div class="items-value mt-2">{{$item->strength}}</div>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-lg-4">
                    <div class="mb-1">
                        <div class="items-title">Dosage Form</div>
                        <div class="items-value mt-2">{{$item->dosage_form}}</div>
                    </div>    
                </div>
                <div class="col-lg-4">
                    <div class="mb-1">
                        <div class="items-title">Package Size</div>
                        <div class="items-value mt-2">{{$item->package_size}}</div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="mb-1">
                        <div class="items-title">Quantity</div>
                        <div class="items-value mt-2">{{$item->quantity}}</div>
                    </div>    
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-lg-4">
                    <div class="mb-1">
                        <div class="items-title">Lot/Batch No.</div>
                        <div class="items-value mt-2">{{$item->lotbatch_no}}</div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="mb-1">
                        <div class="items-title">Expiry Date</div>
                        <div class="items-value mt-2">{{$item->expiry_date}}</div>
                    </div>    
                </div>
                <div class="col-lg-4">
                    <div class="mb-1">
                        <div class="items-title">Trade Price</div>
                        <div class="items-value mt-2">{{$item->trade_price}}</div>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-lg-4">
                </div>
                <div class="col-lg-4">
                </div>
                <div class="col-lg-4">
                    <div class="mb-1">
                        <div class="items-title">Total</div>
                        <div class="items-value mt-2">{{$item->total}}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    @endforeach

</div>

@endsection

@section('custom-js')
<script type="text/javascript" src="{{ asset('js/DataTables/datatables.min.js') }}"></script>
@endsection