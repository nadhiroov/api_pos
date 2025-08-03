@extends('layouts.app')

@section('title', 'Home Page')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/libs/daterangepicker/daterangepicker.css') }}">
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
                                <th>Branch</th>
                                <th>Product</th>
                                <th>Total Items Sold</th>
                                <th>Total Sales</th>
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
@endsection

@section('script')
    <script src="{{ asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/extra-libs/moment/moment.min.js') }}"></script>
    <script src="{{ asset('assets/libs/daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('assets/libs/select2/dist/js/select2.full.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $(".daterange").daterangepicker({
                autoUpdateInput: false,
                locale: {
                    format: 'YYYY-MM-DD'
                }
            })

            $(".select2-branch").select2({
                placeholder: "Select a branch",
            })
        })

        const table = $('#datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '/report/sales/data',
                type: 'GET',
                data: function(d) {
                    d.branch_id = $('.select2-branch').val()
                    d.date_range = $('.daterange').val()
                },
            },
            columns: [
                {
                    data: 'image',
                    name: 'image'
                },
                {
                    data: 'branch_name',
                    name: 'branch_name'
                },
                {
                    data: 'product_name',
                    name: 'product_name'
                },
                {
                    data: 'sold',
                    name: 'sold'
                },
                {
                    data: 'total_price',
                    name: 'total_price'
                },
                {
                    data: 'detail',
                    name: 'detail'
                },
            ],
            columnDefs: [{
                width: '15%',
                orderable: false,
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
    </script>
@endsection
