@extends('layouts.app')

@section('custom-css')
<link rel="stylesheet" type="text/css" href="{{ asset('css/product-donation.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('js/DataTables/datatables.min.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('css/custom-table.css') }}"/>
@endsection

@section('content')

@if (session('allocation-cancelled'))
<div class="alert alert-success" role="alert">
    {{ session('allocation-cancelled') }}
</div>
@endif

<div class="bg-heading">
    <h4 class="px-4 py-3">Allocations</h4>
</div>

<div class="d-flex justify-content-end">
    <a href="{{route('allocation-create')}}" type="button" class="btn btn-primary">Add Allocation</a>
</div>

<div class="bg-initial-details mt-2 px-4 py-4">
    <ul class="nav nav-tabs mt-3" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Allocations</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Drafts ({{ $allocations_drafts->count() }})</button>
        </li>
    </ul>
    <div class="tab-content mt-3" id="myTabContent">
        <!-- Allocation Details Tab  -->
        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
            <!-- Allocation Details Forms -->
            <div class="row">
                <div class="col">
                    <!-- <h5 class="donation-titles mt-2">Medicine Donations</h5> -->
                    <table class="table table-striped table-hover" id="cfs-table" style="width:100%">
                        <thead class="theader">
                            <tr>
                                <th scope="col">No.</th>
                                <th scope="col">Allocation No.</th>
                                <th scope="col">DNA No.</th>
                                <th scope="col">Date Created</th>
                                <th scope="col">Total Product Amount</th>
                                <th scope="col">Current Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($allocations as $item) 
                                <tr class="contribution-list-row clickable-row" data-href='{{ route("allocation-details", ["allocation_id" => $item->id]) }}'>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->allocation_no }}</td>
                                    <td>{{ $item->dna_no }}</td>
                                    <td>{{ date('F d, Y', strtotime($item->created_at)) }}</td>  <!-- <th>Date</th> -->
                                    <td>P{{ number_format($item->total_allocated_products,2) }}</td>
                                    <td>
                                            <i class="fas fa-check-circle status-green"></i>
                                        @if($item->status != 2 && $item->status >= 3)
                                            <i class="fa-solid fa-circle-check status-green"></i>
                                        @elseif($item->status == 2)
                                            <i class="fas fa-times-circle text-danger"></i>
                                        @elseif($item->status >= 1)
                                            <i class="fas fa-circle status-gray"></i>
                                        @endif

                                        @if($item->status != 4 && $item->status >= 5)
                                            <i class="fa-solid fa-circle-check status-green"></i>
                                        @elseif($item->status == 4)
                                            <i class="fas fa-times-circle text-danger"></i>
                                        @elseif($item->status >= 1)
                                            <i class="fas fa-circle status-gray"></i>
                                        @endif

                                        @if($item->status != 6 && $item->status >= 7)
                                            <i class="fa-solid fa-circle-check status-green"></i>
                                        @elseif($item->status == 6)
                                            <i class="fas fa-times-circle text-danger"></i>
                                        @elseif($item->status >= 1)
                                            <i class="fas fa-circle status-gray"></i>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
        <!-- End Contribution Info Tab  -->

        <!-- products tab -->
        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
            <div class="row">
                <div class="col">
                    <!-- <h5 class="donation-titles mt-2">Medicine Donations</h5> -->
                    <table class="table table-striped table-hover" id="cfs-table" style="width:100%">
                        <thead class="theader">
                            <tr>
                                <th scope="col">No.</th>
                                <th scope="col">Allocation No.</th>
                                <th scope="col">DNA No.</th>
                                <th scope="col">Date Created</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($allocations_drafts as $item) 
                                <tr class="contribution-list-row">
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->allocation_no }}</td>
                                    <td>{{ $item->dna_no }}</td>
                                    <td>{{ date('F d, Y', strtotime($item->created_at)) }}</td>  
                                    <td>
                                        <a href="{{ route('allocation-create-read', ['id' => $item->id]) }}" class="btn tt cfs-edit-btn" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Edit">
                                            <i class="fas fa-edit cfs-edit-ic text-secondary"></i>
                                        </a>
                                        <button type="button" class="btn tt cfs-edit-btn" data-bs-placement="bottom" title="Delete" data-bs-toggle="modal" data-bs-target="#deleteModal" data-id="{{ $item->id }}">
                                            <i class="fas fa-trash-alt cfs-edit-ic text-secondary"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>

        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                   Are you sure you want to cancel this allocation?
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form method="POST" action="{{ route('allocation-cancel-request') }}">
                        @method('DELETE')
                        @csrf
                        <input id="id" name="id" type="hidden" value="0">
                        <button type="submit" class="btn btn-danger">Confirm</button>
                    </form>
                </div>
                
                </div>
            </div>
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

        $(".clickable-row").click(function() {
        window.location = $(this).data("href");
        });
        
        $('#drafts-table').dataTable({
        order: [[ 0, "desc" ]],
        searching:true,
        paging:true,
        info:true,
        sort:true,
        });

        $('#deleteModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget) // Button that triggered the modal
            var moduleId = button.data('id')

            var modal = $(this)
            modal.find('#id').val(moduleId)
        })

    });
    
</script>

@endsection
