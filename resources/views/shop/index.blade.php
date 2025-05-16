@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/libs/dropzone/dist/min/dropzone.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/sweetalert2/dist/sweetalert2.min.css') }}" />
@endsection

@section('content')
    <div class="container-fluid">
        @if ($data == null)
            <div class="card bg-info-subtle overflow-hidden shadow-none">
                <div class="card-body py-3">
                    <div class="row justify-content-between align-items-center">
                        <div class="col-sm-6">
                            <h5 class="fw-semibold mb-9 fs-5">You don't have any shop.</h5>
                            <p class="mb-9">
                                Set up your store in seconds and take control of your business from day one.
                            </p>
                            <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#add_new">Create
                                now!</button>
                        </div>
                        <div class="col-sm-5">
                            <div class="position-relative mb-n5 text-center">
                                <img src="{{ asset('assets/images/backgrounds/shop.png') }}" alt="modernize-img"
                                    class="img-fluid" width="180" height="230">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card bg-info-subtle overflow-hidden shadow-none">
                <div class="card-body py-3">
                    <div class="row justify-content-between align-items-center">
                        <div class="col-sm-6">
                            <h5 class="fw-semibold mb-9 fs-5">Join an existing store.</h5>
                            <p class="mb-9">
                                Enter the store code to become part of the team and manage sales together.
                            </p>
                            <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#join">Join
                                now!</button>
                        </div>
                        <div class="col-sm-5">
                            <div class="position-relative mb-n5 text-center">
                                <img src="{{ asset('assets/images/backgrounds/cashier.png') }}" alt="modernize-img"
                                    class="img-fluid" width="180" height="230">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="card bg-info-subtle shadow-none position-relative overflow-hidden mb-4">
                <div class="card-body px-4 py-3">
                    <div class="row align-items-center">
                        <div class="col-9">
                            <h4 class="fw-semibold mb-8">{{ $title }}</h4>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a class="text-muted text-decoration-none" href="/">Dashboard</a>
                                    </li>
                                    <li class="breadcrumb-item" aria-current="page">{{ $title }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="col-3">
                            <div class="text-center mb-n5">
                                <img src="{{ asset('assets/images/breadcrumb/ChatBc.png') }}" alt="modernize-img"
                                    class="img-fluid mb-n4" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="fw-semibold fs-5 mb-4">{{ $data->code }}</h5>
                    <div class="position-relative overflow-hidden d-inline-block">
                        <img src="{{ route('shop.image', $data->logo) }}" alt="modernize-img"
                            class="img-fluid mb-4 rounded-circle position-relative" width="140">
                    </div>
                    <h5 class="fw-semibold fs-5 mb-2">{{ $data->name }}</h5>
                    <p class="px-xl-5">{{ $data->phone }}</p>
                    <p class="mb-3 px-xl-5">{{ $data->address }}</p>
                    <div class="d-flex align-items-center justify-content-center gap-3">
                        @if (auth()->user()->id == $data->user_id)
                            {{-- <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_new">Add new</button> --}}
                            <button class="btn btn-warning">Edit</button>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- modal add new --}}
    <div class="modal fade" id="add_new" tabindex="-1" aria-labelledby="mySmallModalLabel" aria-hidden="true"
        style="display: none;">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header d-flex align-items-center">
                    <h4 class="modal-title" id="myModalLabel">
                        Add new data
                    </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-7">Image</h4>
                            <form action="/shop/uploadImage" class="dropzone dz-clickable mb-2" id="my-dropzone">
                                @csrf
                                <div class="dz-default dz-message">
                                    <button class="dz-button" type="button">Drop Thumbnail here
                                        to upload</button>
                                </div>
                            </form>
                            <p class="fs-2 text-center mb-0">
                                Set the product thumbnail image. Only *.png, *.jpg and *.jpeg image files are accepted.
                            </p>
                        </div>
                    </div>
                    <form action="/shop" method="POST" class="form-process-add">
                        @csrf
                        <input type="hidden" name="image" id="imageInput" value="">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name"
                                placeholder="Enter your shop name" required>
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <input type="text" class="form-control" id="address" name="address"
                                placeholder="Enter your shop address" required>
                        </div>
                        <div class="mb-3">
                            <label for="phone_number" class="form-label">Phone Number</label>
                            <input type="text" class="form-control" id="phone_number" name="phone"
                                placeholder="Enter your phone number" required>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn bg-danger-subtle text-danger  waves-effect"
                        data-bs-dismiss="modal">
                        Close
                    </button>
                    <button type="submit" class="btn bg-secondary-subtle text-secondary  waves-effect">
                        Save
                    </button>
                </div>
                </form>
            </div>
        </div>
    </div>
    {{-- modal add new --}}
    <div class="modal fade" id="join" tabindex="-1" aria-labelledby="mySmallModalLabel" aria-hidden="true"
        style="display: none;">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header d-flex align-items-center">
                    <h4 class="modal-title" id="myModalLabel">
                        Join to existing store
                    </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="/shop/join" method="POST" class="form-process-join">
                        @csrf
                        <input type="hidden" name="image" id="imageInput" value="">
                        <div class="mb-3">
                            <label for="name" class="form-label">Code</label>
                            <input type="text" class="form-control" id="name" name="code"
                                placeholder="Enter your shop code" required>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn bg-danger-subtle text-danger  waves-effect"
                        data-bs-dismiss="modal">
                        Close
                    </button>
                    <button type="submit" class="btn bg-secondary-subtle text-secondary  waves-effect">
                        Save
                    </button>
                </div>
                </form>
            </div>
        </div>
    </div>
@endsection


@section('script')
    <script src="{{ asset('assets/libs/dropzone/dist/min/dropzone.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/toastr-init.js') }}"></script>
    <script>
        Dropzone.autoDiscover = false;

        const myDropzone = new Dropzone("#my-dropzone", {
            url: "/product/uploadImage",
            paramName: "file",
            autoProcessQueue: true,
            maxFiles: 1,
            addRemoveLinks: true,
            init: function() {
                this.on("success", function(file, response) {
                    document.getElementById("imageInput").value = response.filename;
                });
                this.on("removedfile", function(file) {
                    document.getElementById("imageInput").value = "";
                });
            }
        });
    </script>

    <script>
        $(".form-process-add").on('submit', function(e) {
            e.preventDefault()
            let formData = new FormData(this)
            $.ajax({
                url: '/shop',
                type: "post",
                data: formData,
                processData: false,
                contentType: false,
                dataType: "json",
                cache: false,
                async: false,
                success: function(response = "") {
                    if (response.status == 'Success') {
                        toastr.success(response.message, response.status)
                        setTimeout(() => {
                            location.reload()
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

        $(".form-process-join").on('submit', function(e) {
            e.preventDefault()
            let formData = new FormData(this)
            $.ajax({
                url: '/shop/join',
                type: "post",
                data: formData,
                processData: false,
                contentType: false,
                dataType: "json",
                cache: false,
                async: false,
                success: function(response) {
                    if (response.status == 'Success') {
                        toastr.success(response.message, response.status)
                        setTimeout(() => {
                            location.reload()
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
    </script>
@endsection
