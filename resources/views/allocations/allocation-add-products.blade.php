@extends('layouts.app')

@section('custom-css')
<link rel="stylesheet" type="text/css" href="{{ asset('css/product-donation.css') }}"/>
@endsection

@section('content')
<div class="bg-heading">
    <h4 class="px-4 py-3">Create Allocation - Add Products</h4>
</div>

<allocation-add-product allocation_id='{{ $allocation_id }}'></allocation-add-product>
@endsection

@section('custom-js')

@endsection
