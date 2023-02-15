@extends('layouts.app')

@section('custom-css')
<link rel="stylesheet" type="text/css" href="{{ asset('css/product-donation.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('js/DataTables/datatables.min.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('css/custom-table.css') }}"/>

@endsection

@section('content')

@if (session('inventory-updated'))
<div class="alert alert-success" role="alert">
    {{ session('inventory-updated') }}
</div>
@endif
<div class="bg-heading">
    <h4 class="px-4 py-3">Inventory</h4>
</div>

<div class="bg-initial-details mt-2 px-4 py-4">
    
    <div class="row ">
        <div class="col">
            <!-- <h5 class="donation-titles mt-2">Medicine Donations</h5> -->
            <table class="table table-striped table-hover" id="cfs-table" style="width:100%">
                <thead class="theader">
                    <tr>
                        <th scope="col">No.</th>
                        <th scope="col">Company</th>
                        <th scope="col">Product Code</th>
                        <th scope="col">Product Name</th>
                        <th scope="col">Lot No.</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Unit Cost</th>
                        <th scope="col">Total</th>
                        <th scope="col">Expiry Date</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($inventory as $item)
                        <tr class="contribution-list-row clickable-row">
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->member_name }}</td>
                            <td>{{ $item->product_code }} </td>
                            <td>{{ $item->product_name }}</td>
                            <td>{{ $item->lot_no }}</td>
                            <td>{{ number_format($item->quantity) }}</td>
                            <td>{{ $item->unit_cost }}</td>
                            <td>P{{ number_format($item->total,2) }}</td>
                            <td>{{ date('F, d Y', strtotime($item->expiry_date)) }}</td> 
                            <td>
                                <a href="{{ route('inventory-edit', ['id' => $item->id]) }}" class="btn tt cfs-edit-btn" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Edit">
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
        });

    });
    
</script>

@endsection
