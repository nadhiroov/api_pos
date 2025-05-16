@extends('layouts.app')

@section('title', 'Home Page')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/libs/owl.carousel/dist/assets/owl.carousel.min.css') }}" />
@endsection

@section('content')
    <div class="container-fluid">
        <div class="owl-carousel counter-carousel owl-theme">
            <div class="item">
                <div class="card border-0 zoom-in bg-primary-subtle shadow-none">
                    <div class="card-body">
                        <div class="text-center">
                            <img src="../assets/images/svgs/icon-user-male.svg" width="50" height="50" class="mb-3"
                                alt="modernize-img" />
                            <p class="fw-semibold fs-3 text-primary mb-1">
                                Employees
                            </p>
                            <h5 class="fw-semibold text-primary mb-0">96</h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="item">
                <div class="card border-0 zoom-in bg-warning-subtle shadow-none">
                    <div class="card-body">
                        <div class="text-center">
                            <img src="../assets/images/svgs/icon-briefcase.svg" width="50" height="50" class="mb-3"
                                alt="modernize-img" />
                            <p class="fw-semibold fs-3 text-warning mb-1">Clients</p>
                            <h5 class="fw-semibold text-warning mb-0">3,650</h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="item">
                <div class="card border-0 zoom-in bg-info-subtle shadow-none">
                    <div class="card-body">
                        <div class="text-center">
                            <img src="../assets/images/svgs/icon-mailbox.svg" width="50" height="50" class="mb-3"
                                alt="modernize-img" />
                            <p class="fw-semibold fs-3 text-info mb-1">Projects</p>
                            <h5 class="fw-semibold text-info mb-0">356</h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="item">
                <div class="card border-0 zoom-in bg-danger-subtle shadow-none">
                    <div class="card-body">
                        <div class="text-center">
                            <img src="../assets/images/svgs/icon-favorites.svg" width="50" height="50" class="mb-3"
                                alt="modernize-img" />
                            <p class="fw-semibold fs-3 text-danger mb-1">Events</p>
                            <h5 class="fw-semibold text-danger mb-0">696</h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="item">
                <div class="card border-0 zoom-in bg-success-subtle shadow-none">
                    <div class="card-body">
                        <div class="text-center">
                            <img src="../assets/images/svgs/icon-speech-bubble.svg" width="50" height="50"
                                class="mb-3" alt="modernize-img" />
                            <p class="fw-semibold fs-3 text-success mb-1">Payroll</p>
                            <h5 class="fw-semibold text-success mb-0">$96k</h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="item">
                <div class="card border-0 zoom-in bg-info-subtle shadow-none">
                    <div class="card-body">
                        <div class="text-center">
                            <img src="../assets/images/svgs/icon-connect.svg" width="50" height="50" class="mb-3"
                                alt="modernize-img" />
                            <p class="fw-semibold fs-3 text-info mb-1">Reports</p>
                            <h5 class="fw-semibold text-info mb-0">59</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('script')
    <script src="{{ asset('assets/libs/owl.carousel/dist/owl.carousel.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $(".counter-carousel").owlCarousel({
                loop: true,
                rtl: true,
                margin: 30,
                mouseDrag: true,

                nav: false,

                responsive: {
                    0: {
                        items: 2,
                        loop: true,
                    },
                    576: {
                        items: 2,
                        loop: true,
                    },
                    768: {
                        items: 3,
                        loop: true,
                    },
                    1200: {
                        items: 5,
                        loop: true,
                    },
                    1400: {
                        items: 6,
                        loop: true,
                    },
                },
            });

        })
    </script>
@endsection
