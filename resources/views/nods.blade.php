@extends('layouts.app')


@section('custom-css')
<link rel="stylesheet" type="text/css" href="{{ asset('js/DataTables/datatables.min.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('css/custom-table.css') }}"/>

<style>
    .cfs-id {
        max-width: 20px;
    }
</style>
@endsection

@section('content')
<div class="page-heading">
    <div class="d-flex align-items-baseline justify-content-between">
        <!-- <h4> Notice of Donations Forms</h4> -->
        <h4>PRODUCT DONATION</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"></li>
                <li class="breadcrumb-item active" aria-current="page">Notice of Donations Forms</li>
            </ol>
        </nav>
    </div>

    <div class="border-container">
        <div class="heading-line"></div>
        <hr class="rounded">
    </div>
</div>

<section class="section">

    <div class="card shadow mt-2">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table" id="nods-table" style="width:100%">
                    <thead>
                        <tr>
                            <th>Form No.</th>
                            <th>Call for Support</th>
                            <th>Submitted By</th>
                            <th>Company</th>
                            <th>Date Submitted</th>
                            <th>Approval Status</th>
                            <th>Approval Date</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach($forms as $item)
                            <tr>
                                <td class="cfs-id">#{{ $item->id }}</td>
                                <td class="cfs-title">{{ $item->title }}</td>
                                <td>{{ $item->last_name }}, {{ $item->first_name }}</td>
                                <td>{{ $item->member_name }}</td>
                                <td>{{date('M, d Y', strtotime($item->created_at))}}</td>
                                @if ($item->approval_status === 1)
                                    <td><span class="badge bg-success"> <i class="fa-solid fa-circle-check"></i> Approved </span></td>
                                @elseif ($item->approval_status === 2)
                                    <td><span class="badge bg-danger"> <i class="fas fa-exclamation-triangle"></i> Rejected </span></td>
                                @else
                                    <td><span class="badge bg-warning" style="color:#665f1e;"> <i class="fa-solid fa-circle-exclamation"></i> Awaiting for Approval</span></td>
                                @endif
                                <!-- <span class="badge bg-warning"> <i class="fa-solid fa-circle-exclamation"></i> Awaiting for Approval</span>
                                <span class="badge bg-danger"> <i class="fas fa-exclamation-triangle"></i> Rejected </span>
                                <span class="badge bg-success"> <i class="fa-solid fa-circle-check"></i> Approved </span> -->
                                <td>{{date('M, d Y', strtotime($item->created_at))}}</td>
                                <td>
                                    <a href="{{ route('nod-form-view', ['id' => $item->id]) }}" class="btn tt cfs-edit-btn" data-bs-toggle="tooltip" data-bs-placement="bottom" title="View">
                                        <i class="fa-solid fa-eye cfs-edit-ic text-secondary"></i>
                                    </a>
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
        $('#nods-table').dataTable({
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