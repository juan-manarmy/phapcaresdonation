@extends('layouts.app')

@section('custom-css')
<link rel="stylesheet" type="text/css" href="{{ asset('css/product-donation.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('js/DataTables/datatables.min.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('css/custom-table.css') }}"/>
@endsection



@section('content')
@if (session('beneficiary-edited'))
<div class="alert alert-success" role="alert">
    {{ session('beneficiary-edited') }}
</div>
@endif

@if (session('beneficiary-created'))
<div class="alert alert-success" role="alert">
    {{ session('beneficiary-created') }}
</div>
@endif
<div class="bg-heading">
    <h4 class="px-4 py-3">Beneficiaries</h4>
</div>

<div class="d-flex">
    <a type="button" class="btn btn-primary ms-auto bd-highlight" href="{{ route('beneficiary-create') }}"> <i class="fa-solid fa-user-plus"></i> Add Beneficiary</a>
</div>

<div class="bg-initial-details mt-2 px-4 py-4">
    <div class="row ">
        <div class="col">
            <!-- <h5 class="donation-titles mt-2">Medicine Donations</h5> -->
            <table class="table table-striped table-hover" id="cfs-table" style="width:100%">
                <thead class="theader">
                    <tr>
                        <th scope="col">No.</th>
                        <th scope="col">Beneficiary Name</th>
                        <th scope="col">Created Date</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($beneficiaries as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->name }}</td>
                            <td>{{ date('F, d Y', strtotime($item->created_at)) }}</td> 
                            <td>
                                <a href="{{ route('beneficiary-edit', ['id' => $item->id]) }}" class="btn tt cfs-edit-btn" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Edit">
                                    <i class="fas fa-edit cfs-edit-ic text-secondary"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>


@endsection

@section('custom-js')
<script type="text/javascript" src="{{ asset('js/DataTables/datatables.min.js') }}"></script>
<script>
    $(document).ready(function () {
        $('#cfs-table').dataTable({
        order: [[ 0, "desc" ]],
        searching:true,
        paging:true,
        info:true,
        scrollX:true,
        scrollCollapse:true,
        sort:true,
        pageLength: 50
        });

        $(".clickable-row").click(function() {
        window.location = $(this).data("href");
        });
    });
</script>
@endsection
