@extends('templates.app')
@section('title', 'Profile')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/libs/sweetalert2/dist/sweetalert2.min.css') }}" />
    <style>
        .avatar-option {
            cursor: pointer;
            position: relative;
            display: inline-block;
        }

        .avatar-img {
            transition: all 0.3s ease;
            border: 3px solid transparent;
        }

        .avatar-option input:checked+.avatar-img {
            border: 3px solid #0d6efd;
            box-shadow: 0 0 0 4px rgb(26, 109, 233);
        }
    </style>
@endsection

@section('content')
    <div class="card bg-info-subtle shadow-none position-relative overflow-hidden mb-4">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-8">Account Setting</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a class="text-muted text-decoration-none" href="../main/index.html">Home</a>
                            </li>
                            <li class="breadcrumb-item" aria-current="page">Account Setting</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-3">
                    <div class="text-center mb-n5">
                        <img src="../assets/images/breadcrumb/ChatBc.png" alt="modernize-img" class="img-fluid mb-n4" />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <ul class="nav nav-pills user-profile-tab" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <button
                    class="nav-link position-relative rounded-0 active d-flex align-items-center justify-content-center bg-transparent fs-3 py-3"
                    id="pills-account-tab" data-bs-toggle="pill" data-bs-target="#pills-account" type="button"
                    role="tab" aria-controls="pills-account" aria-selected="true">
                    <i class="ti ti-user-circle me-2 fs-6"></i>
                    <span class="d-none d-md-block">Account</span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button
                    class="nav-link position-relative rounded-0 d-flex align-items-center justify-content-center bg-transparent fs-3 py-3"
                    id="pills-security-tab" data-bs-toggle="pill" data-bs-target="#pills-security" type="button"
                    role="tab" aria-controls="pills-security" aria-selected="false">
                    <i class="ti ti-lock me-2 fs-6"></i>
                    <span class="d-none d-md-block">Security</span>
                </button>
            </li>
        </ul>
        <div class="card-body">
            <form class="form-process-profile">
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-account" role="tabpanel"
                        aria-labelledby="pills-account-tab" tabindex="0">
                        <div class="row">
                            <div class="col-lg-6 d-flex align-items-stretch">
                                <div class="card w-100 border position-relative overflow-hidden">
                                    <div class="card-body p-4">
                                        <h4 class="card-title">Change Profile</h4>
                                        <p class="card-subtitle mb-4">Change your profile picture from here</p>
                                        <div class="text-center">
                                            <img src="{{ asset('assets/images/profile') . '/' . auth()->user()->image }}"
                                                alt="modernize-img" class="img-fluid rounded-circle" width="120"
                                                height="120">
                                            <div class="d-flex align-items-center justify-content-center my-4 gap-6">
                                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                    data-bs-target="#changePic">Change</button>
                                            </div>
                                            <p class="mb-0">Select an image</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 d-flex align-items-stretch">
                                <div class="card w-100 border position-relative overflow-hidden">
                                    <div class="card-body p-4">
                                        <h4 class="card-title">Change Password</h4>
                                        <p class="card-subtitle mb-4">To change your password please confirm here</p>
                                        <div class="mb-3">
                                            <label for="exampleInputPassword1" class="form-label">Current
                                                Password</label>
                                            <input name="current_password" type="password" class="form-control">
                                        </div>
                                        <div class="mb-3">
                                            <label for="exampleInputPassword2" class="form-label">New Password</label>
                                            <input name="new_password" type="password"
                                                class="form-control @error('new_password') is-invalid @enderror">
                                        </div>
                                        <div>
                                            <label for="exampleInputPassword3" class="form-label">Confirm
                                                Password</label>
                                            <input name="confirm_password" type="password" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="card w-100 border position-relative overflow-hidden mb-0">
                                    <div class="card-body p-4">
                                        <h4 class="card-title">Personal Details</h4>
                                        <p class="card-subtitle mb-4">To change your personal detail , edit and save from
                                            here
                                        </p>

                                        <head>
                                            <meta name="csrf-token" content="{{ csrf_token() }}">
                                        </head>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="exampleInputtext" class="form-label">Username</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ auth()->user()->username }}" disabled>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="exampleInputtext" class="form-label">Your Name</label>
                                                    <input type="text" class="form-control" name="name"
                                                        placeholder="{{ auth()->user()->name }}">
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="exampleInputtext1" class="form-label">Email</label>
                                                    <input type="email" class="form-control" name="email"
                                                        placeholder="{{ auth()->user()->email }}">
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="d-flex align-items-center justify-content-end mt-4 gap-6">
                                                    <button class="btn btn-primary">Save</button>
                                                    <button class="btn bg-danger-subtle text-danger">Cancel</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="pills-security" role="tabpanel" aria-labelledby="pills-security-tab"
                        tabindex="0">
                        <div class="row">
                            <div class="col-lg-8">
                                <div class="card border shadow-none">
                                    <div class="card-body p-4">
                                        <h4 class="card-title mb-3">Two-factor Authentication</h4>
                                        <div class="d-flex align-items-center justify-content-between pb-7">
                                            <p class="card-subtitle mb-0">Lorem ipsum, dolor sit amet consectetur
                                                adipisicing
                                                elit. Corporis sapiente
                                                sunt earum officiis laboriosam ut.</p>
                                            <button class="btn btn-primary">Enable</button>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between py-3 border-top">
                                            <div>
                                                <h5 class="fs-4 fw-semibold mb-0">Authentication App</h5>
                                                <p class="mb-0">Google auth app</p>
                                            </div>
                                            <button class="btn bg-primary-subtle text-primary">Setup</button>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between py-3 border-top">
                                            <div>
                                                <h5 class="fs-4 fw-semibold mb-0">Another e-mail</h5>
                                                <p class="mb-0">E-mail to send verification link</p>
                                            </div>
                                            <button class="btn bg-primary-subtle text-primary">Setup</button>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between py-3 border-top">
                                            <div>
                                                <h5 class="fs-4 fw-semibold mb-0">SMS Recovery</h5>
                                                <p class="mb-0">Your phone number or something</p>
                                            </div>
                                            <button class="btn bg-primary-subtle text-primary">Setup</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="card">
                                    <div class="card-body p-4">
                                        <div
                                            class="text-bg-light rounded-1 p-6 d-inline-flex align-items-center justify-content-center mb-3">
                                            <i class="ti ti-device-laptop text-primary d-block fs-7" width="22"
                                                height="22"></i>
                                        </div>
                                        <h4 class="card-title mb-0">Devices</h4>
                                        <p class="mb-3">Lorem ipsum dolor sit amet consectetur adipisicing elit Rem.</p>
                                        <button class="btn btn-primary mb-4">Sign out from all devices</button>
                                        @foreach ($sessions as $session)
                                            <div
                                                class="d-flex align-items-center justify-content-between py-3 border-bottom">
                                                <div class="d-flex align-items-center gap-3">
                                                    <i class="ti ti-device-mobile text-dark d-block fs-7" width="26"
                                                        height="26"></i>
                                                    <div>
                                                        <h5 class="fs-4 fw-semibold mb-0">{{ $session->ip_address }}</h5>
                                                        <p class="mb-0">{{ $session->time_ago }}</p>
                                                    </div>
                                                </div>
                                                <a class="text-dark fs-6 d-flex align-items-center justify-content-center bg-transparent p-2 fs-4 rounded-circle"
                                                    href="javascript:void(0)">
                                                    <i class="ti ti-dots-vertical"></i>
                                                </a>
                                            </div>
                                        @endforeach
                                        {{-- <div class="d-flex align-items-center justify-content-between py-3">
                                            <div class="d-flex align-items-center gap-3">
                                                <i class="ti ti-device-laptop text-dark d-block fs-7" width="26"
                                                    height="26"></i>
                                                <div>
                                                    <h5 class="fs-4 fw-semibold mb-0">Macbook Air</h5>
                                                    <p class="mb-0">Gujarat India, Oct 24 at 3:15 AM</p>
                                                </div>
                                            </div>
                                            <a class="text-dark fs-6 d-flex align-items-center justify-content-center bg-transparent p-2 fs-4 rounded-circle"
                                                href="javascript:void(0)">
                                                <i class="ti ti-dots-vertical"></i>
                                            </a>
                                        </div> --}}
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="d-flex align-items-center justify-content-end gap-6">
                                    <button class="btn btn-primary">Save</button>
                                    <button class="btn bg-danger-subtle text-danger">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- modal add change --}}
    <div class="modal fade" id="changePic" tabindex="-1" aria-labelledby="mySmallModalLabel" aria-hidden="true"
        style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form class="form-process-changeImg">
                    <div class="modal-header d-flex align-items-center">
                        <h4 class="modal-title" id="myModalLabel">
                            Change picture
                        </h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body change-pic-body">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn bg-danger-subtle text-danger  waves-effect"
                            data-bs-dismiss="modal">
                            Close
                        </button>
                        <button type="submit" class="btn bg-secondary-subtle text-secondary  waves-effect"
                            data-bs-dismiss="modal">
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('assets/js/plugins/toastr-init.js') }}"></script>
    <script>
        // $(document).ready(function() {})
        $('#changePic').on('show.bs.modal', function(e) {
            $.ajax({
                type: 'get',
                url: '/profile/pageChangePic',
                success: function(data) {
                    $('.change-pic-body').html(data)
                    $('.avatar-option input[type="radio"]').on('change', function() {
                        $('.avatar-img').removeClass('selected')
                        $(this).siblings('img').addClass('selected')
                    })

                    $('.avatar-option input[type="radio"]:checked').each(function() {
                        $(this).siblings('img').addClass('selected')
                    })
                }
            })
        })

        $(".form-process-changeImg").on('submit', function(e) {
            e.preventDefault()
            let formData = new FormData(this);
            $.ajax({
                url: `/profile/changeImg`,
                type: "POST",
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                processData: false,
                contentType: false,
                dataType: "json",
                cache: false,
                async: false,
                success: function(response = "") {
                    if (response.status == 'Success') {
                        toastr.success(response.message, response.status)
                        setTimeout(() => {
                            window.location.reload()
                        }, 1000)
                    } else {
                        toastr.error(response.message, response.status)
                    }
                },
                error: function(response) {
                    toastr.error(response.message, response.status)
                },
            })
        })

        $(".form-process-profile").on('submit', function(e) {
            e.preventDefault()
            let formData = new FormData(this)
            $.ajax({
                url: `/profile`,
                type: "POST",
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                processData: false,
                contentType: false,
                dataType: "json",
                cache: false,
                async: false,
                success: function(response = "") {
                    if (response.status == 'Success') {
                        toastr.success(response.message, response.status)
                        setTimeout(() => {
                            window.location.reload()
                        }, 1000)
                    } else {
                        toastr.error(response.message, response.status)
                    }
                },
                error: function(response) {
                    toastr.error(response.responseJSON.message, "Error")
                },
            })
        })
    </script>
@endsection
