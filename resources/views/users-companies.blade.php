@extends('layouts.app')


@section('custom-css')
<link rel="stylesheet" type="text/css" href="{{ asset('js/DataTables/datatables.min.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('css/custom-table.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('css/product-donation.css') }}"/>

@endsection

@section('content')


@if (session('company-created'))
<div class="alert alert-success" role="alert">
    {{ session('company-created') }}
</div>
@endif

@if (session('company-updated'))
<div class="alert alert-success" role="alert">
    {{ session('company-updated') }}
</div>
@endif
<div class="bg-heading px-4 py-3">
    <div class="d-flex align-items-baseline justify-content-between">
        <h4 class="mb-0">All Members</h4>
    </div>
</div>

<div class="bg-initial-details mt-2 px-4 py-4">
    <div class="row ">
        <div class="col">
            <!-- <h5 class="donation-titles mt-2">Medicine Donations</h5> -->
            <table class="table table-striped" id="users-table" style="width:100%">
                <thead class="theader">
                    <tr>
                        <th>Company Name</th>
                        <th>Company Logo</th>
                        <th>Created Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($companies as $company)
                    <tr>
                            <td>{{ $company->member_name }}</td>
                            <td>
                                <img src="{{asset('images/company_logos/'.$company->member_logo_path)}}" class="img-thumbnail" width="70" alt="...">
                            </td>
                            <td>{{date('F, d Y', strtotime($company->created_at))}}</td>
                            <td>
                                <span class="badge bg-success">Active</span>
                            </td>
                            <td>
                                <a href="{{route('edit-company-view',['id' => $company->id])}}" class="btn tt cfs-edit-btn" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Edit">
                                    <i class="fas fa-edit cfs-edit-ic text-secondary"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tr>
                </tbody>
            </table>

            <div class="d-flex justify-content-end">
                <a href="/users/create-company" type="button" class="btn btn-primary mt-3">Add New Member</a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('custom-js')

<script type="text/javascript" src="{{ asset('js/DataTables/datatables.min.js') }}"></script>
<script>
    $(document).ready(function () {
        $('#users-table').dataTable({
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