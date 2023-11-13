@extends('layouts.app')
@section('custom-css')
<link rel="stylesheet" type="text/css" href="{{ asset('css/product-donation.css') }}"/>
@endsection

@section('content')

    <div class="bg-heading">
        <h4 class="px-4 py-3">Request Call for Support</h4>
    </div>
    <div class="row">
        <div class="col-2"></div>
        <div class="col">
            <div class="bg-initial-details mt-2 p-5">
                <form method="POST" enctype="multipart/form-data" action="{{ route('call-for-support-submit') }}" >
                    @csrf
                    <div class="mb-3">
                        <label for="formFile" class="form-label">Banner</label>
                        <input class="form-control" type="file" name="banner_path" id="banner_path" required>
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Title</label>
                        <input type="text" class="form-control" name="title" id="title" aria-describedby="emailHelp" placeholder="Ex. Taal Eruption 2022" required>
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Details</label>
                        <input type="text" class="form-control" name="details" id="details" aria-describedby="emailHelp" placeholder="Ex. We are looking for donation" required>
                    </div>
                    <div class="mb-3" id="request_area">
                        <label for="exampleInputEmail1" class="form-label">Request Items</label>
                        <div class="d-flex mt-2" > 
                            <input type="text text-white" class="form-control me-1" name="request_items" placeholder="Ex. Food, Clothes, Medicine, Etc." required> 
                        </div>
                    </div>
                    <div class="mb-3 form-check">
                        <input type='hidden' value='0' name='is_yearend'>
                        <input type="checkbox" class="form-check-input" name="is_yearend" id="is_yearend" value="1">
                        <label class="form-check-label" for="is_yearend">Is this a Year-End request?</label>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button class="btn btn-primary">Submit Call for Support</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-2"></div>
    </div>

@endsection

@section('custom-js')
<script>

</script>
@endsection
