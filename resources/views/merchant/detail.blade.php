@extends('layouts.app')
@section('title', 'Home Page')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/libs/dropzone/dist/min/dropzone.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/select2/dist/css/select2.min.css') }}">
@endsection

@section('content')

    <head>
        <meta name="csrf-token" content="{{ csrf_token() }}">
    </head>
    <div class="container-fluid">
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
                                <li class="breadcrumb-item">
                                    <a class="text-muted text-decoration-none" href="/merchant">Merchant</a>
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
        <div class="row mb-4">
            <div class="col-md-6 col-lg-4 d-flex align-items-stretch">
                <div class="card w-100">
                    <div class="card-body mb-4">
                        <div class="mb-4">
                            <h4 class="card-title fw-semibold">{{ $title }}</h4>
                        </div>
                        <ul class="timeline-widget mb-0 position-relative mb-n5">
                            <div class="mb-3 row">
                                <label for="merchantName" class="col-md-4 col-form-label">Merchant name</label>
                                <div class="col-md-8">
                                    <input class="form-control" type="text" value="{{ $branch->name }}" id="merchantName"
                                        disabled>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="example-text-input" class="col-md-4 col-form-label">Address</label>
                                <div class="col-md-8">
                                    <textarea name="" class="form-control" rows="4" disabled>{{ $branch->address }}</textarea>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="merchantPhone" class="col-md-4 col-form-label">Phone</label>
                                <div class="col-md-8">
                                    <input class="form-control" type="text" value="{{ $branch->phone }}"
                                        id="merchantPhone" disabled>
                                </div>
                            </div>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-md-8 col-lg-8 d-flex align-items-stretch">
                <div class="card w-100">
                    <div class="card-body">
                        <div class="d-sm-flex d-block align-items-center justify-content-between mb-3">
                            <div class="mb-3 mb-sm-0">
                                <h4 class="card-title fw-semibold">Staff</h4>
                            </div>
                            <div>
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add-new-staff">Manage</button>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id="datatableStaff" class="table table-striped table-bordered text-nowrap align-middle">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Role</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>


        </div>
        <div class="row">
            <div class="col-md-12 col-lg-12 d-flex align-items-stretch">
                <div class="card w-100">
                    <div class="card-body">
                        <div class="d-sm-flex d-block align-items-center justify-content-between mb-3">
                            <div class="mb-3 mb-sm-0">
                                <h4 class="card-title fw-semibold">Product</h4>
                                {{-- <p class="card-subtitle">What Impacts Product Performance?</p> --}}
                            </div>
                            <div>
                                {{-- <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_new">Add
                                    new</button> --}}
                                {{-- <a href="/product/add" class="btn btn-primary">Add new</a> --}}
                                <a href="{{ route('product.add', $data->id) }}" class="btn btn-primary">Add new</a>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id="datatable" class="table table-striped table-bordered text-nowrap align-middle">
                                <thead>
                                    <tr>
                                        <th>Image</th>
                                        <th>Name</th>
                                        <th>Stock</th>
                                        <th>Price</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- modal add new staff --}}
    <div class="modal fade" id="add-new-staff" tabindex="-1" aria-labelledby="mySmallModalLabel" aria-hidden="true"
        style="display: none;">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <form action="/merchant" method="POST" class="form-process-add-staff">
                    <div class="modal-header d-flex align-items-center">
                        <h4 class="modal-title" id="myModalLabel">
                            Add staff to merchant
                        </h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body modal-body-add-staff">
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
    <script src="{{ asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/toastr-init.js') }}"></script>
    <script src="{{ asset('assets/libs/sweetalert2/dist/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('assets/libs/dropzone/dist/min/dropzone.min.js') }}"></script>
    <script src="{{ asset('assets/libs/select2/dist/js/select2.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '/product/data?branch_id={{ $branch->id }}',
                    type: 'GET'
                },
                columns: [{
                        data: 'image',
                        name: 'image'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'stock',
                        name: 'stock'
                    },
                    {
                        data: 'price_formatted',
                        name: 'price_formatted'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
                columnDefs: [{
                    width: '20%',
                    targets: 1
                }]
            })

            $('#datatableStaff').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '/merchant/{{ $branch->id }}/staffs',
                    type: 'GET'
                },
                columns: [{
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'role_name',
                        name: 'role_name'
                    }
                ],
                columnDefs: [{
                    width: '20%',
                    targets: 1
                }]
            });
        });

        $('#add-new-staff').on('show.bs.modal', function(e) {
            $.ajax({
                type: 'get',
                url: '/staff/{{ $branch->id }}/add',
                success: function(data) {
                    $('.modal-body-add-staff').html(data)
                    $('.select2').select2({
                        placeholder: "Select staffs",
                        dropdownParent: $('#add-new-staff')
                    })
                }
            })
        })

        $('#edit-staff').on('show.bs.modal', function(e) {
            let id = $(e.relatedTarget).data('id')
            $.ajax({
                type: 'get',
                url: `/merchant/${id}/edit`,
                success: function(data) {
                    $('.modal-body-edit-staff').html(data);
                }
            })
        })

        $('#edit').on('show.bs.modal', function(e) {
            let id = $(e.relatedTarget).data('id')
            $.ajax({
                type: 'get',
                url: `/merchant/${id}/edit`,
                success: function(data) {
                    $('.modal-body-edit').html(data);
                }
            })
        })

        $(".form-process-add-staff").on('submit', function(e) {
            e.preventDefault()
            let formData = new FormData(this);
            $.ajax({
                url: '/staff/{{ $branch->id }}/add',
                type: "post",
                data: formData,
                processData: false,
                contentType: false,
                dataType: "json",
                cache: false,
                async: false,
                success: function(response = "") {
                    if (response.status == 'Success') {
                        toastr.success(response.message, response.status);
                    } else {
                        toastr.error(response.message, response.status);
                    }
                    $('#datatableStaff').DataTable().ajax.reload(null, false);
                },
                error: function(response) {
                    toastr.error(response.message, response.status);
                },
            })
        })

        function confirmDelete(selection) {
            let id = $(selection).attr("data-id");
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!",
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: `/merchant/${id}`,
                        type: "DELETE",
                        data: {
                            _token: "{{ csrf_token() }}",
                        },
                        dataType: "json",
                        async: false,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response = "") {
                            if (response.status == 'Success') {
                                toastr.success(response.message, response.status);
                                $('#datatable').DataTable().ajax.reload(null, false);
                            } else {
                                if (response.code == 23000) {
                                    Swal.fire({
                                        type: "error",
                                        title: response.status,
                                        text: "The data is constrained with product",
                                    });
                                } else {
                                    Swal.fire({
                                        type: "error",
                                        title: response.status,
                                        text: response.message,
                                    });

                                }
                            }
                            $('#zero_config').DataTable().ajax.reload(null, false);
                        },
                        error: function(response) {
                            Swal.fire({
                                type: "error",
                                title: response.status,
                                text: response.message,
                            });
                        },
                    })
                }
            });
        }
    </script>
@endsection
