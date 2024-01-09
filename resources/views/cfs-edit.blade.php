@extends('layouts.app')
@section('custom-css')
<style>
    label {
        font-family: neosans_std_medium;
        font-size:.80rem;
        color:rgb(0,59,115);;
    }

    .form-control {
        font-size:.80rem;
    }

    .form-text {
        font-size:.80rem;
    }
</style>
<link rel="stylesheet" type="text/css" href="{{ asset('css/product-donation.css') }}"/>
@endsection

@section('content')
<div class="bg-heading">
    <h4 class="px-4 py-3">Update Call for Support</h4>
</div>
        
<div class="card shadow mt-2">
        <div class="card-body">
            <form method="POST"  enctype="multipart/form-data" action="{{ route('call-for-support-submit-edit', ['id' => $cfs->id]) }}" >
                @method('PUT')
                @csrf
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Title</label>
                    <input type="text" class="form-control" name="title" id="title" aria-describedby="emailHelp" value="{{ $cfs->title }}">
                </div>
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Details</label>
                    <input type="text" class="form-control" name="details" id="details" aria-describedby="emailHelp" value="{{ $cfs->details }}">
                </div>
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Request Items</label>
                    <input type="text" class="form-control" name="request_items" id="" aria-describedby="emailHelp" value="{{ $cfs->request_items }}">
                </div>

                <div class="card mb-3">
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="text-center">
                                <img src="{{asset('/images/cfs_banners/'.$cfs->banner_path)}}" width="500" alt="" class="img-fluid" id="banner">
                            </div>
                        </div>

                        <label for="formFile" class="form-label">Banner</label>
                        <input class="form-control" type="file" name="banner_path" id="banner_path" accept="" onchange="showPreview(event);">
                        <div class="form-text text-primary" for="exampleCheck1">Upload new image to update the banner.</div>
                    </div>
                </div>
                
                <div class="mb-3 form-check">
                    <input type='hidden' value='0' name='is_yearend'>
                    <input type="checkbox" class="form-check-input" name="is_yearend" id="is_yearend" value="1" {{ $cfs->is_yearend ? 'checked' : ''}} >
                    <label class="form-check-label" for="is_yearend">Is this a Year-End request?</label>
                </div>

                <div class="mb-3 form-check">
                    <input type='hidden' value='0' name='is_active'>
                    <input type="checkbox" class="form-check-input" name="is_active" id="is_active" value="1" {{ $cfs->is_active ? 'checked' : ''}}>
                    <label class="form-check-label" for="is_active">Active</label>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
</div>
@endsection

@section('custom-js')
<script>
    function showPreview(event) {
        if(event.target.files.length > 0 ) {
            var src = URL.createObjectURL(event.target.files[0]);
            var preview = document.getElementById("banner");
            preview.src = src;
        }
    }
</script>
@endsection