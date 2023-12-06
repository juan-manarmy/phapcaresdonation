@extends('layouts.app')

@section('custom-css')
<link rel="stylesheet" type="text/css" href="{{ asset('css/product-donation.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('js/modules/datepicker/datepicker.css') }} "/>
@endsection

@section('content')
<div class="bg-heading">
    <h4 class="px-4 py-3">Product Donation</h4>
</div>

<div class="bg-progress-wrapper">
    <div class="bg-heading-progress">
        <div class="prog-line"></div>
        <div class="row dot-progress">
            <div class="col">
                <div class="text-center">
                    <i class="fa-solid fa-circle dot-size status-green"></i>
                    <p class="dot-status mt-2">Initial Details</p>
                </div>
            </div>
            <div class="col">
                <div class="text-center">
                    <i class="fa-solid fa-circle dot-size status-gray"></i>
                    <p class="status-text mt-2">Donations</p>
                </div>
            </div>
            <div class="col">
                <div class="text-center">
                    <i class="fa-solid fa-circle dot-size status-gray"></i>
                    <p class="status-text mt-2">
                    Secondary Details
                    </p>
                </div>
            </div>
            <div class="col">
                <div class="text-center">
                    <i class="fa-solid fa-circle dot-size status-gray"></i>
                    <p class="status-text mt-2">Finish</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="bg-initial-details mt-2 px-4 py-4">
    <h5 class="donation-titles">Initial Details</h5>
    @if(isset($contribution))
        <form method="POST" action="{{ route('pd-initial-details-save') }}" class="mt-4"> 
            @csrf
            <div class="form-group row mt-2">
                <label for="staticEmail" class="col-sm-1 col-form-label sd-label">Contribution No:</label>
                <div class="col-lg-4">
                <input type="text" class="form-control" id="staticEmail" readonly value="{{ $contribution->contribution_no }}" name="contribution_no">
                </div>
            </div>
            <div class="form-group row mt-2">
                <label class="col-lg-1 col-form-label sd-label" for="sel1">Company :</label>
                <div class="col-lg-4">
                    <select class="form-select" name="member_id">
                        @foreach ($members as $item)
                            <option {{ $contribution->member_id == $item->id ? "selected" : "" }} value="{{ $item->id }}">{{ $item->member_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group row mt-2">
                <label class="col-lg-1 col-form-label sd-label" for="sel1">Call for Support :</label>
                <div class="col-lg-4">
                    <select class="form-select" name="cfs_id">
                        <option {{ $contribution->cfs_id == $item->id ? "selected" : "" }} value="{{ $item->id }}">None</option>
                        @foreach ($cfs as $item)
                            <option {{ $contribution->cfs_id == $item->id ? "selected" : "" }} value="{{ $item->id }}">{{ $item->title }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group row mt-2">
                <label for="inputPassword" class="col-sm-1 col-form-label sd-label">Distributor :</label>
                <div class="col-lg-4">
                    <input type="text" class="form-control" id="distributor" name="distributor" value="{{ $contribution->distributor }}" placeholder="Distributor" required>
                </div>
            </div>
            <div class="form-group row mt-2">
                <label for="inputPassword" class="col-sm-1 col-form-label sd-label">Date :</label>
                <div class="col-lg-4">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="contribution_date" id="contribution_date" value="{{date('m/d/Y', strtotime($contribution->contribution_date))}}" placeholder="Date" onkeydown="return false" autocomplete="off" required>
                        <span class="input-group-text"><i class="fa-solid fa-calendar text-main-color"></i></span>
                    </div>
                </div>
            </div>

            <div class="d-flex flex-row-reverse">
                <button type="submit" class="btn btn-primary me-2">Save and Proceed</button>
            </div>
        </form>
    @else
        <form method="POST" action="{{ route('pd-initial-details-save') }}" class="mt-4"> 
            @csrf
            <div class="form-group row mt-2">
                <label for="staticEmail" class="col-sm-1 col-form-label sd-label">Contribution No:</label>
                <div class="col-lg-4">
                <input type="text" class="form-control" id="staticEmail" readonly value="CN-{{ $cn_no }}" name="contribution_no">
                </div>
            </div>

            <div class="form-group row mt-2">
                <label class="col-lg-1 col-form-label sd-label" for="sel1">Call for Support :</label>
                <div class="col-lg-4">
                    <select class="form-select" name="cfs_id">
                        <option value="0">None</option>
                        @foreach ($cfs as $item)
                            <option value="{{ $item->id }}">{{ $item->title }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group row mt-2">
                <label class="col-lg-1 col-form-label sd-label" for="sel1">Company :</label>
                <div class="col-lg-4">
                    <select class="form-select" name="member_id">
                        @foreach ($members as $item)
                            <option value="{{ $item->id }}">{{ $item->member_name }} </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group row mt-2">
                <label for="inputPassword" class="col-sm-1 col-form-label sd-label">Distributor :</label>
                <div class="col-lg-4">
                    <input type="text" class="form-control" id="distributor" name="distributor" placeholder="Distributor" required>
                </div>
            </div>
            <div class="form-group row mt-2">
                <label for="inputPassword" class="col-sm-1 col-form-label sd-label">Date :</label>
                <div class="col-lg-4">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="contribution_date" id="contribution_date" placeholder="Date" onkeydown="return false" autocomplete="off" required>
                        <span class="input-group-text"><i class="fa-solid fa-calendar text-main-color"></i></span>
                    </div>
                </div>
            </div>

            <div class="d-flex flex-row-reverse">
                <button type="submit" class="btn btn-primary me-2">Save and Proceed</button>
            </div>
        </form>
    @endif






</div>
@endsection

@section('custom-js')
<script src="{{asset('js/modules/datepicker/datepicker.js')}} "></script>

<script>
    var $startDate = $('#contribution_date');
    $(document).ready(function () {
        $startDate.datepicker({
        autoHide: true,
        });

    });
</script>

@endsection