@extends('layouts.app')


@section('custom-css')
<link rel="stylesheet" type="text/css" href="{{ asset('js/DataTables/datatables.min.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('css/custom-table.css') }}"/>
<style>
    label {
        font-family: neosans_std_medium;
        font-size:.80rem;
    }
    .items-title {
        font-family: neosans_std_regular;
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
        <h4>Update Notice of Donations Form Item</h4>
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
<div class="container" style="max-width:700px;">
    <div class="card shadow mt-2">
        <div class="card-body p-5">
            <h3 class="items-title">Update Donation Item</h3>
            <form action="{{ route('nods-view-item-submit-edit', ['id' => $nodItem->id]) }}" method="POST">
                @method('PUT')
                @csrf
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label mt-3">Brand Name</label>
                    <input type="text" class="form-control" name="brand_name" name="brand_name" id="brand_name" aria-describedby="emailHelp" value="{{$nodItem->brand_name}}">
                </div>
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Generic Brand Name</label>
                    <input type="text" class="form-control" name="generic_name" id="generic_name" aria-describedby="emailHelp" value="{{$nodItem->generic_name }}">
                </div>
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Strength</label>
                    <input type="text" class="form-control" name="strength" id="strength" aria-describedby="emailHelp" value="{{$nodItem->strength }}">
                </div>
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Dosage Form</label>
                    <input type="text" class="form-control" name="dosage_form" id="dosage_form" aria-describedby="emailHelp" value="{{$nodItem->dosage_form }}">
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Package Size</label>
                            <input type="text" class="form-control" name="package_size" id="package_size" aria-describedby="emailHelp" value="{{$nodItem->package_size }}">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Quantity</label>
                            <input type="text" class="form-control" name="quantity" id="quantity" aria-describedby="emailHelp" value="{{$nodItem->quantity }}">
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Lot/Batch No.</label>
                    <input type="text" class="form-control" name="lotbatch_no" id="lotbatch_no" aria-describedby="emailHelp" value="{{$nodItem->lotbatch_no }}">
                </div>
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Expiry Date</label>
                    <input type="text" class="form-control" name="expiry_date" id="expiry_date" aria-describedby="emailHelp" value="{{$nodItem->expiry_date }}">
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Trade Price</label>
                            <input type="text" class="form-control" name="trade_price" id="trade_price" aria-describedby="emailHelp" value="{{$nodItem->trade_price }}">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Total</label>
                            <input type="text" class="form-control" name="total" id="total" aria-describedby="emailHelp" value="{{$nodItem->total }}">
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Save Update</button>
            </form>
        </div>
    </div>
</div>

@endsection

@section('custom-js')
<script type="text/javascript" src="{{ asset('js/DataTables/datatables.min.js') }}"></script>
@endsection