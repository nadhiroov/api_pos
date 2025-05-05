@extends('layouts.app')

@section('title', 'Home Page')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/libs/sweetalert2/dist/sweetalert2.min.css') }}" />
@endsection

@section('content')
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
                                <li class="breadcrumb-item" aria-current="page">{{ $title }}</li>
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
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-4 pb-8">
                    <h4 class="card-title">Categories</h4>
                    <div class="d-flex">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_new">Add new</button>
                    </div>
                </div>
                <br>
                <div class="table-responsive">
                    <table id="datatable" class="table table-striped table-bordered text-nowrap align-middle">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- @foreach ($datas as $data)
                                <tr>
                                    <td>{{ $data->name }}</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <a href="button" class="btn bg-warning-subtle text-warning">
                                                <i class="ti ti-edit fs-4 me-2"></i>
                                            </a>
                                            <a href="button" class="btn bg-danger-subtle text-danger">
                                                <i class="ti ti-trash fs-4 me-2"></i>
                                            </a>
                                        </div>

                                    </td>
                                </tr>
                            @endforeach --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    {{-- modal add new --}}
    <div class="modal fade" id="add_new" tabindex="-1" aria-labelledby="mySmallModalLabel" aria-hidden="true"
        style="display: none;">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <form action="/category" method="POST" class="form-process-add">
                    <div class="modal-header d-flex align-items-center">
                        <h4 class="modal-title" id="myModalLabel">
                            Add new data
                        </h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body category-body-add">
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
    {{-- modal edit --}}
    <div class="modal fade" id="edit" tabindex="-1" aria-labelledby="mySmallModalLabel" aria-hidden="true"
        style="display: none;">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <form action="/category" method="PATCH" class="form-process-edit">
                    <div class="modal-header d-flex align-items-center">
                        <h4 class="modal-title" id="myModalLabel">
                            Edit data
                        </h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body category-body-edit">
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
    <script>
        $(document).ready(function() {
            $('#datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '/category/data',
                    type: 'GET'
                },
                columns: [{
                        data: 'name',
                        name: 'name'
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
            });
        });

        $('#add_new').on('show.bs.modal', function(e) {
            $.ajax({
                type: 'get',
                url: '/category/add',
                success: function(data) {
                    $('.category-body-add').html(data);
                }
            })
        })

        $('#edit').on('show.bs.modal', function(e) {
            let id = $(e.relatedTarget).data('id')
            $.ajax({
                type: 'get',
                url: `/category/${id}/edit`,
                success: function(data) {
                    $('.category-body-edit').html(data);
                }
            })
        })

        $(".form-process-add").on('submit', function(e) {
            e.preventDefault()
            let formData = new FormData(this);
            $.ajax({
                url: '/category',
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
                    $('#datatable').DataTable().ajax.reload(null, false);
                },
                error: function(response) {
                    toastr.error(response.message, response.status);
                },
            })
        })

        $(".form-process-edit").on('submit', function(e) {
            e.preventDefault()
            let formData = new FormData(this);
            $.ajax({
                url: `/category/${formData.get('id')}`,
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
                        toastr.success(response.message, response.status);
                    } else {
                        toastr.error(response.message, response.status);
                    }
                    $('#datatable').DataTable().ajax.reload(null, false);
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
                        url: `/category/${id}`,
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
