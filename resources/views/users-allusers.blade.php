@extends('layouts.app')


@section('custom-css')
<link rel="stylesheet" type="text/css" href="{{ asset('js/DataTables/datatables.min.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('css/custom-table.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('css/product-donation.css') }}"/>
@endsection

@section('content')



@if (session('user-created'))
<div class="alert alert-success" role="alert">
    {{ session('user-created') }}
</div>
@endif

@if (session('user-updated'))
<div class="alert alert-success" role="alert">
    {{ session('user-updated') }}
</div>
@endif
<div class="bg-heading">
    <h4 class="px-4 py-3">Users</h4>
</div>

<div class="d-flex">
    <a type="button" class="btn btn-primary ms-auto bd-highlight" href="/users/create-user"> <i class="fa-solid fa-user-plus"></i> Add User</a>
</div>

<div class="bg-initial-details mt-2 px-4 py-4">
    <div class="row ">
        <div class="col">
            <!-- <h5 class="donation-titles mt-2">Medicine Donations</h5> -->
            <table class="table table-striped table-hover" id="users-table" style="width:100%">
                <thead class="theader">
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Company</th>
                        <th>Created Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->last_name }}, {{ $user->first_name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->mobile_number }}</td>
                            <td>
                                {{ $user->member_name }}
                            </td>
                            <td>{{date('M, d Y', strtotime($user->created_at))}}</td>
                            <td>
                                <span class="badge bg-success">Active</span>
                            </td>
                            <td>
                                <a href="{{route('edit-user-view',['id' => $user->id])}}" class="btn tt cfs-edit-btn" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Edit"><i class="fas fa-edit cfs-edit-ic text-secondary"></i></a>
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