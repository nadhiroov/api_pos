@extends('layouts.app')

@section('title', 'Home Page')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/libs/daterangepicker/daterangepicker.css') }}">
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
                <div class="d-flex align-items-center justify-content-between mb-4 pb-8">
                    <h4 class="card-title">{{ $title }}</h4>
                    <div class="row g-3 align-items-end mb-4">
                        <div class="col-md-12">
                            <label for="date_range" class="form-label">Date range</label>
                            <input id="date_range" type="text" class="form-control daterange"
                                placeholder="Select date range" />
                        </div>
                    </div>
                </div>
                <br>
                <div class="table-responsive">
                    <table id="datatable" class="table table-striped table-bordered text-nowrap align-middle">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Branch</th>
                                <th># Transactions</th>
                                <th>Total Sales</th>
                                <th>Total Items Sold</th>
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
    <script>
        $(document).ready(function() {
            $('#datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '/report/sales/data',
                    type: 'GET'
                },
                columns: [{
                        data: 'date',
                        name: 'date'
                    },
                    {
                        data: 'branch_name',
                        name: 'branch_name'
                    },
                    {
                        data: 'tx_count',
                        name: 'tx_count'
                    },
                    {
                        data: 'total_sales',
                        name: 'total_sales'
                    },
                    {
                        data: 'total_items_sold',
                        name: 'total_items_sold'
                    },
                ],
                columnDefs: [{
                    width: '20%',
                    targets: 1
                }]
            })

            $(".daterange").daterangepicker({
                autoUpdateInput: false,
                locale: {
                    format: 'YYYY-MM-DD'
                }
            })
        })
        $('.daterange').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('DD-MM-YYYY') +
                ' - ' +
                picker.endDate.format('DD-MM-YYYY'))
            reloadIfValid()
        })
    </script>
@endsection
