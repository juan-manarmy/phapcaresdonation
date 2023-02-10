@extends('layouts.app')


@section('custom-css')
<link rel="stylesheet" type="text/css" href="{{ asset('js/DataTables/datatables.min.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('css/custom-table.css') }}"/>

@endsection

@section('content')
<div class="page-heading">
    <div class="d-flex align-items-baseline justify-content-between">
        <h4>Allocations</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('all-users') }}">Users List</a></li>
                <li class="breadcrumb-item active" aria-current="page">Companies</li>
            </ol>
        </nav>
    </div>

    <div class="border-container">
        <div class="heading-line"></div>
        <hr class="rounded">
    </div>
</div>


@if (session('allocation-created'))
<div class="alert alert-success" role="alert">
    {{ session('allocation-created') }}
</div>
@endif

@if (session('allocation-updated'))
<div class="alert alert-success" role="alert">
    {{ session('allocation-updated') }}
</div>
@endif

<section class="section">
    <div class="d-flex">
        <a type="button" class="btn btn-primary ms-auto bd-highlight" href="/data/create-allocation"> <i class="fa-solid fa-plus"></i> Add Allocation</a>
    </div>

    <div class="card shadow mt-2">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table" id="allocations-table" style="width:100%">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Created Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($allocations as $allocation)
                        <tr>
                            <td>{{ $allocation->allocation_name }}</td>
                            <td>{{date('M, d Y', strtotime($allocation->created_at))}}</td>
                            <td>
                                <a href="{{route('edit-allocation-view',['id' => $allocation->id])}}" class="btn tt cfs-edit-btn" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Edit"><i class="fas fa-edit cfs-edit-ic text-secondary"></i></a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@endsection

@section('custom-js')
 
<script type="text/javascript" src="{{ asset('js/DataTables/datatables.min.js') }}"></script>

<script>
    $(document).ready(function () {
        $('#allocations-table').dataTable({
        order: [[ 0, "desc" ]],
        searching:true,
        paging:true,
        info:true,
        scrollX:true,
        scrollCollapse:true,
        sort:true,
        });
    });
</script>

@endsection