@extends('layouts.app')


@section('custom-css')
<link rel="stylesheet" type="text/css" href="{{ asset('css/product-donation.css') }}"/>
@endsection

@section('content')
<div class="bg-heading px-4 py-3">
    <div class="d-flex align-items-baseline justify-content-between">
        <h4 class="mb-0">Update Member</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Users List</a></li>
                <li class="breadcrumb-item active" aria-current="page">Members</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-lg-3"></div>
    <div class="col">
        <div class="bg-initial-details mt-4 px-5 py-5 shadow">
            <form method="POST" enctype="multipart/form-data" action="{{ route('edit-company-submit', ['id' => $company->id]) }}">
                @method('PUT')
                @csrf
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label col-form-label fw-bold">Company Name</label>
                    <input type="text" class="form-control" id="member_name" name="member_name" aria-describedby="emailHelp" value="{{ $company->member_name }}">
                </div>

                <div class="mb-3">
                    <div class="text-center">
                        <img src="{{asset('/images/company_logos/'.$company->member_logo_path)}}" width="100" alt="" class="img-fluid" id="logo" >
                    </div>
                </div>

                <div class="mb-3">
                    <label for="formFile" class="form-label col-form-label fw-bold">Company Logo</label>
                    <input class="form-control" type="file" id="member_logo_path" name="member_logo_path"  onchange="showPreview(event);" required>
                </div>

                <div class="d-flex flex-row-reverse">
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
    <div class="col-lg-3"></div>
</div>
@endsection

@section('custom-js')
<script>
    function showPreview(event) {
        if(event.target.files.length > 0 ) {
            var src = URL.createObjectURL(event.target.files[0]);
            var preview = document.getElementById("logo");

            preview.src = src;
        }
    }
</script>
@endsection