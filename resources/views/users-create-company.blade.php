@extends('layouts.app')


@section('custom-css')
<link rel="stylesheet" type="text/css" href="{{ asset('css/product-donation.css') }}"/>
@endsection

@section('content')
<div class="bg-heading px-4 py-3">
    <div class="d-flex align-items-baseline justify-content-between">
        <h4 class="mb-0">Create New Member</h4>
    </div>
</div>

<div class="row">
    <div class="col-lg-4"></div>
    <div class="col">
        <div class="bg-initial-details mt-4 px-5 py-5 shadow">
            <form method="POST" enctype="multipart/form-data" action="{{ route('create-member-submit') }}">
                @csrf
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label col-form-label fw-bold">Company Name</label>
                    <input type="text" class="form-control" id="member_name" name="member_name" aria-describedby="emailHelp" required>
                </div>

                <div class="mb-3">
                    <label for="formFile" class="form-label col-form-label fw-bold">Company Logo</label>
                    <input class="form-control" type="file" id="member_logo_path" name="member_logo_path" required>
                </div>

                <div class="d-flex flex-row-reverse">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
    <div class="col-lg-4"></div>
</div>

@endsection

@section('custom-js')

@endsection