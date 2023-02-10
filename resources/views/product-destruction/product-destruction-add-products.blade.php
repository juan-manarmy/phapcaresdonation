@extends('layouts.app')

@section('custom-css')
<link rel="stylesheet" type="text/css" href="{{ asset('css/product-donation.css') }}"/>
@endsection

@section('content')

<div class="bg-heading">
    <h4 class="px-4 py-3">Create New Product Destruction - Add Products</h4>
</div>

<destruction-add-product destruction_id='{{ $destruction_id }}'></allocation-add-product>

@endsection

@section('custom-js')
 

@endsection
