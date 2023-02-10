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
@endsection

@section('content')
<div class="page-heading">
    <div class="d-flex align-items-baseline justify-content-between">
        <h4>Update Existing Allocation</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url()->previous() }}">Allocations List</a></li>
                <li class="breadcrumb-item active" aria-current="page">Update</li>
            </ol>
        </nav>
    </div>

    <div class="border-container">
        <div class="heading-line"></div>
        <hr class="rounded">
    </div>
</div>
<div class="container">
    <div class="card shadow mt-2">
        <div class="card-body">
            <form method="POST" action="{{ route('edit-allocation-submit', ['id'=> $allocation->id]) }}">
                @method('PUT')
                @csrf
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Allocation Name</label>
                    <input type="text" class="form-control" id="allocation_name" name="allocation_name" aria-describedby="emailHelp" value="{{ $allocation->allocation_name }}">
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('custom-js')

@endsection