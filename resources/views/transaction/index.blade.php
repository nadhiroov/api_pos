@extends('layouts.app')

@section('title', 'Home Page')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/libs/sweetalert2/dist/sweetalert2.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/libs/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/daterangepicker/daterangepicker.css') }}">
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
                <h4 class="card-title">{{ $title }}</h4>
                <div class="row g-3 align-items-end mb-4">
                    <!-- Branch filter -->
                    <div class="col-md-4">
                        <label for="branch_id" class="form-label">Branch</label>
                        <select id="branch_id" class="select2-branch form-select" name="branch_id">
                            <option></option>
                            @foreach ($datas->branches as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Date range picker -->
                    <div class="col-md-4">
                        <label for="date_range" class="form-label">Date range</label>
                        <input id="date_range" type="text" class="form-control daterange"
                            placeholder="Select date range" />
                    </div>

                    <!-- Reset button -->
                    <div class="col-md-4 text-end">
                        <button type="button" id="btn-reset" class="btn btn-outline-secondary mt-1">
                            <i class="bi bi-arrow-counterclockwise"></i> Reset
                        </button>
                    </div>
                </div>
                <br>
                <div class="table-responsive">
                    <table id="datatable" class="table table-striped table-bordered text-nowrap align-middle">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Transaction id</th>
                                <th>Total Product</th>
                                <th>Total Item</th>
                                <th>Total Price</th>
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
    {{-- modal detail --}}
    <div class="modal fade" id="detail" tabindex="-1" aria-labelledby="mySmallModalLabel" aria-hidden="true"
        style="display: none;">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <form action="/merchant" method="POST" class="form-process-add">
                    <div class="modal-header d-flex align-items-center">
                        <h4 class="modal-title" id="myModalLabel">
                            Detail data {{ strtolower($title) }}
                        </h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body modal-body-detail">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn bg-info-subtle text-info  waves-effect" data-bs-dismiss="modal">
                            Close
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
    <script src="{{ asset('assets/libs/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('assets/js/extra-libs/moment/moment.min.js') }}"></script>
    <script src="{{ asset('assets/libs/daterangepicker/daterangepicker.js') }}"></script>
    <script>
        $(document).ready(function() {
            $(".select2-branch").select2({
                placeholder: "Select a branch",
            })

            $(".daterange").daterangepicker({
                autoUpdateInput: false,
                locale: {
                    format: 'YYYY-MM-DD'
                }
            })
        })

        const table = $('#datatable').DataTable({
            processing: true,
            serverSide: true,
            deferLoading: 0,
            order: [
                [0, 'desc']
            ],
            ajax: {
                type: 'GET',
                url: '/transaction/data',
                data: function(d) {
                    d.branch_id = $('.select2-branch').val()
                    d.date_range = $('.daterange').val()
                },
            },
            columns: [{
                    data: 'date',
                    name: 'date',
                    render: function(data, type, row) {
                        return moment(data).format('DD MMMM YYYY');
                    }
                },
                {
                    data: 'transaction_id',
                    name: 'transaction_id'
                },
                {
                    data: 'total_product',
                    name: 'total_product'
                },
                {
                    data: 'total_item',
                    name: 'total_item'
                },
                {
                    data: 'total_price',
                    name: 'total_price',
                    render: function(data, type, row) {
                        return 'Rp. ' + new Intl.NumberFormat('id-ID').format(data);
                    }
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
                targets: 5
            }]
        })

        function reloadIfValid() {
            if ($('.select2-branch').val()) {
                table.ajax.reload()
            }
        }

        $('.select2-branch').on('change', () => {
            reloadIfValid()
        });

        $('.daterange').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('DD-MM-YYYY') +
                ' - ' +
                picker.endDate.format('DD-MM-YYYY'))
            reloadIfValid()
        })

        $('#btn-reset').on('click', function() {
            $('.daterange').val('')
            table.clear().draw()
        })

        $('#detail').on('show.bs.modal', function(e) {
            let data = $(e.relatedTarget).data('id')
            // console.log(data.trx_index)
            $.ajax({
                type: 'get',
                url: `/transaction/detail`,
                data: {
                    transaction_id: data.trx_index,
                    id: data.id
                },
                beforeSend: function() {
                    $('.modal-body-detail').html(
                        `<div class="text-center">
                            <div class="spinner-grow" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>`
                    )
                },
                success: function(data) {
                    $('.modal-body-detail').removeClass('text-center')
                    $('.modal-body-detail').html(data)
                }
            })
        })
    </script>
@endsection
