@extends('layouts.app-forms')
@section('custom-css')
<style>
    label {
        font-weight:700;
        font-size:.80rem;
        color:black;
    }

    .form-control {
        font-size:.80rem;
    }

    .form-text {
        font-size:.80rem;
    }

</style>
@endsection

@section('content')

    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <div class="card shadow mt-2">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-1"></div>
                        <div class="col-lg-10">
                            <img src="/images/phapcares-logo.jpg" class="img-fluid" alt="">
                            
                            <div class="forms-note-bg rounded p-3 mt-3 mb-3">
                                <div class="text-center">
                                    <div class="forms-title-bg">
                                    PHAPCares Foundation Request Forms
                                    </div>
                                    <div>
                                    Please fill up to request donation of medicine for individual* or for your organization
                                    </div>
                                </div>
                            </div>

                            <form method="POST" enctype="multipart/form-data" action="{{ route('call-for-support-submit') }}" >
                                @csrf
                                <div class="mb-2">
                                    <label for="exampleInputEmail1" class="form-label">Requesting For</label>
                                    <input type="text" class="form-control" name="request_for" id="title" aria-describedby="emailHelp" placeholder="Ex. Medicine, Cash, Etc." required>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="request_type" id="inlineRadio1" value="option1" required>
                                    <label class="form-check-label" for="inlineRadio1">Individual</label>
                                </div>

                                <div class="form-check form-check-inline mb-2">
                                    <input class="form-check-input" type="radio" name="request_type" id="inlineRadio2" value="option2" required>
                                    <label class="form-check-label" for="inlineRadio2">Organization</label>
                                </div>

                                <div class="mb-2">
                                    <label for="exampleInputEmail1" class="form-label">Name</label>
                                    <input type="text" class="form-control" name="name" id="name" aria-describedby="emailHelp" placeholder="Name" required>
                                </div>
                                <div class="mb-2" id="request_area">
                                    <label for="exampleInputEmail1" class="form-label">Email</label>
                                    <input type="text text-white" class="form-control me-1" name="email" placeholder="Email"> 
                                </div>

                                <div class="mb-2" id="request_area">
                                    <label for="exampleInputEmail1" class="form-label">Mobile Number</label>
                                    <input type="text text-white" class="form-control me-1" name="mobile_number" placeholder="Mobile Number"> 
                                </div>

                                <div class="mb-3" id="request_area">
                                    <label for="exampleInputEmail1" class="form-label">Message</label>
                                    <textarea class="form-control" aria-label="With textarea" placeholder="Message" name="message"></textarea>
                                </div>

                                <div class="d-flex justify-content-center">
                                    <button  class="btn btn-primary btn-forms">Submit</button>
                                </div>
                            </form>
                        </div>
                        <div class="col-lg-1"></div>
                    </div>

                </div>
            </div>
        </div>
        <div class="col-md-3"></div>
    </div>

@endsection

@section('custom-js')
<script>
 
</script>
@endsection
