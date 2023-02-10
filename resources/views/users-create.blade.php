@extends('layouts.app')


@section('custom-css')
<link rel="stylesheet" type="text/css" href="{{ asset('css/product-donation.css') }}"/>
@endsection

@section('content')
<div class="bg-heading">
    <h4 class="px-4 py-3">Create Brand New User</h4>
</div>
<div class="container" style="max-width:700px;">
    <div class="bg-initial-details mt-2 p-5">
        <form method="POST" action="{{ route('create-user-submit') }}">
            @csrf
            <div class="mb-3">
                <label for="first_name" class="form-label">First Name</label>
                <input type="text" class="form-control @error('first_name') is-invalid @enderror" name="first_name" id="first_name" aria-describedby="emailHelp" value="{{ old('first_name') }}" required autocomplete="first_name" autofocus>

                @error('first_name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div class="mb-3">
                <label for="last_name" class="form-label">Last Name</label>
                <input type="last_name" class="form-control @error('last_name') is-invalid @enderror" name="last_name" id="last_name" aria-describedby="emailHelp" value="{{ old('last_name') }}" required autocomplete="last_name">

                @error('last_name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <div class="mb-3">
                <label for="mobile_number" class="form-label">Mobile Number</label>
                <input type="text" class="form-control @error('mobile_number') is-invalid @enderror" name="mobile_number" id="mobile_number" aria-describedby="emailHelp" value="{{ old('mobile_number') }}" required autocomplete="mobile_number">

                @error('mobile_number')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <div class="mb-3">
                <label for="email_info" class="form-label">Contact Email address</label>
                <input type="email_info" class="form-control @error('email_info') is-invalid @enderror" id="email_info" name="email_info" aria-describedby="emailHelp" value="{{ old('email_info') }}" required autocomplete="email_info">
                <div id="" class="form-text">We'll never share your email with anyone else.</div>

                @error('email_info')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Company</label>
                <select class="form-select" aria-label="Default select example" id="member_id" name="member_id">
                    @foreach ($companies as $item)
                        <option value="{{$item->id}}"> {{ $item->member_name }} </option>
                    @endforeach
                </select>
                <div id="" class="form-text">If your company doesn't exist. Create your needed company first in company creation page.</div>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Username</label>
                <input type="email" name="email"  class="form-control @error('email') is-invalid @enderror" id="email" aria-describedby="emailHelp" value="{{ old('email') }}" required autocomplete="email">

                @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" aria-describedby="passwordHelpBlock" value="{{ old('password') }}" required autocomplete="password">
                <div id="passwordHelpBlock" class="form-text">
                Your password must be 8-20 characters long, contain letters and numbers, and must not contain spaces, special characters, or emoji.
                </div>

                @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password-confirm" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="password-confirm" name="password_confirmation" aria-describedby="emailHelp" required>
                <div id="" class="form-text">Re-type your password.</div>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('custom-js')

@endsection