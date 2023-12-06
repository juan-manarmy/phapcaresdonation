@extends('layouts.app')

@section('custom-css')
<link rel="stylesheet" type="text/css" href="{{ asset('js/DataTables/datatables.min.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('css/custom-table.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('css/product-donation.css') }}"/>

<style>


</style>

@endsection

@section('content')

@if (session('cfs-created'))
<div class="alert alert-success" role="alert">
    {{ session('cfs-created') }}
</div>
@endif

@if (session('cfs-updated'))
<div class="alert alert-success" role="alert">
    {{ session('cfs-updated') }}
</div>
@endif

<div class="bg-heading">
    <h4 class="px-4 py-3">Call for Support</h4>
</div>
<div class="d-flex justify-content-end mt-2">
    <a type="button" class="btn btn-primary" href="{{ route('call-for-support-create') }}"> <i class="bi bi-person-plus-fill"></i>Request Call for Support</a>
</div>
<div class="bg-initial-details mt-2 px-4 py-4">
    <div class="row">
        <div class="col">
            <!-- <h5 class="donation-titles mt-2">Medicine Donations</h5> -->
            <table class="table table-striped table-hover" id="cfs-table" style="width:100%">
                <thead class="theader">
                    <tr>
                        <th class="ps-4">Title</th>
                        <th>Request Donations</th>
                        <th>Date Created</th>
                        <th>Request Type</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cfs as $item)
                        <tr>
                            <td class="cfs-title ps-4">{{ $item->title }}</td>
                            <td class="cfs-title">{{ $item->request_items }}</td>
                            
                            <td>{{date('M, d Y', strtotime($item->created_at))}}</td>
                            <!-- Year-End Request -->
                            <td>
                                <span class="badge {{ $item->is_yearend ? 'bg-success' : 'bg-info' }} "> {{ $item->is_yearend ? 'All-Year-Round' : 'Month Request' }}  </span>
                            </td>
                            <td>
                                <span class="badge {{ $item->is_active ? 'bg-success' : 'bg-danger' }}">{{ $item->is_active ? 'Active' : 'Inactive' }}</span>
                            </td>
                            <td class="cfs-title pe-4">
                                <a href="{{ route('edit-call-for-support-view', ['id' => $item->id]) }}" class="btn tt cfs-edit-btn" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Edit">
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
