@extends('layouts.app')

@section('custom-css')
<link rel="stylesheet" type="text/css" href="{{ asset('css/product-donation.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('js/DataTables/datatables.min.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('css/custom-table.css') }}"/>
@endsection

@section('content')
<div class="bg-heading">
    <h4 class="px-4 py-3">Create Beneficiary</h4>
</div>
<div class="container" style="max-width:700px;">
    <div class="bg-initial-details mt-2 p-5">
        <form method="POST" action="{{ route('beneficiary-create-submit') }}">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Beneficiary Name</label>
                <input type="text" class="form-control" name="name" id="name" aria-describedby="emailHelp" required autofocus>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('custom-js')
@endsection
