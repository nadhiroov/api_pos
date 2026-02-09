@extends('templates.app')

@section('title', 'Home Page')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/libs/daterangepicker/daterangepicker.css') }}">
@endsection

@section('content')
    <div class="container-fluid">
        <div class="col-xl-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title fw-semibold">Detail report</h4>
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="card overflow-hidden shadow-none border card-hover mb-4 mb-md-0">
                                <img src="{{ route('product.image', $product->image) }}" alt="img" />
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <h6 class="mb-0 fs-5 fw-semibold"> {{ $product->name }} </h6>
                                            <span> {{ $product->category->name }} </span>
                                        </div>
                                        <img src="{{ route('product.image', $product->image) }}" alt="user1"
                                            width="35" class="rounded-circle" />
                                    </div>
                                    <div class="d-flex align-items-start justify-content-between mt-3">
                                        <span>Price</span>
                                        <div class="text-end">
                                            <h5 class="mb-0 fs-5 fw-semibold">{{ formatRupiah($product->price) }}</h5>
                                            <span class="fs-3"> {{ $product->sku }} </span>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-start justify-content-between mt-3">
                                        <span>
                                            <i class="ti ti-clock-hour-4 me-1 fs-4"></i>{{-- {{ $product->unit }} --}}
                                            {{ $product->created_at->format('d M Y') }}
                                        </span>
                                        <span>
                                            <i class="ti ti-stack-3 fs-4 me-1"></i>{{ $product->stock }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Line Chart</h4>
                    <div class="col-md-4">
                        <label for="date_range" class="form-label">Date range</label>
                        <input id="date_range" type="text" class="form-control daterange"
                            placeholder="Select date range" />
                    </div>
                    <div id="chart-line-zoomable" class="mx-n3"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('assets/libs/daterangepicker/moment.min.js') }}"></script>
    <script src="{{ asset('assets/libs/daterangepicker/daterangepicker.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        $(document).ready(function() {
            /* 
            $('.daterange').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format(
                    'YYYY-MM-DD'));
            });

            $('.daterange').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
            }); */

            loadChartData()
            let dateRangePick = $('.daterange').daterangepicker({
                autoUpdateInput: false,
                startDate: moment().startOf('hour'),
                endDate: moment().startOf('hour').add(32, 'hour'),
                locale: {
                    cancelLabel: 'Clear'
                }
            })
        });
        let options_zoomable = {
            series: [{
                name: 'Total Sales',
                data: []
            }],
            chart: {
                fontFamily: 'inherit',
                type: 'area',
                stacked: false,
                height: 350,
                zoom: {
                    type: 'x',
                    enabled: true,
                    autoScaleYaxis: true
                },
                toolbar: {
                    autoSelected: 'zoom'
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth'
            },
            markers: {
                size: 0,
            },
            grid: {
                borderColor: 'transparent'
            },
            colors: ['var(--bs-primary)'],
            markers: {
                size: 0
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    inverseColors: false,
                    opacityFrom: 0.12,
                    opacityTo: 0,
                    stops: [0, 90, 100]
                }
            },
            xaxis: {
                type: 'datetime',
            },
            tooltip: {
                shared: false,
                /* x: {
                    formatter: val => (val / 1e6).toFixed(0)
                }, */
                theme: 'dark'
            }
        }
        var chart = new ApexCharts(document.querySelector('#chart-line-zoomable'), options_zoomable);
        chart.render();

        function loadChartData() {
            let dateRange = $('.daterange').val() == '' ? '{{ $date }}' : $('.daterange').val();
            let id = '{{ $product->id }}'
            alert($('.daterange').val())

            $.ajax({
                url: `/report/${id}/${dateRange}/chart`,
                method: 'GET',
                dataType: 'json',
                async: false,
                cache: false,
                success: function(res) {
                    if (res.series && res.series[0]) {
                        chart.updateOptions({
                            series: res.series,
                        })
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Failed to load chart data:', error);
                }
            });
        }

        $('.select2-branch').on('change', loadChartData);
        $('.daterange').on('apply.daterangepicker', loadChartData)
    </script>
@endsection
