@extends('layouts.app-login')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
            <div class="col-lg-5">
                <div class="card p-5 shadow">
                    <img src="/images/phapcares_logo.png" class="rounded mx-auto d-block" height="80" alt="...">
                    <div class="mt-5 ">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            @if (session('admin-message'))
                                <div class="alert alert-danger" role="alert">
                                    {{ session('admin-message') }}
                                </div>
                            @endif

                            <div class="mb-3">
                                <label for="exampleInputEmail1" class="form-label">Email address</label>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"  name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                <div id="emailHelp" class="form-text"></div>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="exampleInputPassword1" class="form-label">Password</label>
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                
                            </div>
                            <div class="mb-3 form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember">Remember Me</label>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
