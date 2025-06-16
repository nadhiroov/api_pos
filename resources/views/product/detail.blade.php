@extends('layouts.app')

@section('title', 'Product Detail')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/libs/owl.carousel/dist/assets/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/sweetalert2/dist/sweetalert2.min.css') }}" />
@endsection

@section('content')
    <div class="cotainer-fluid">
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
                                    <a class="text-muted text-decoration-none" href="/product">Product</a>
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

        <div class="shop-detail">
            <div class="card shadow-none border">
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-lg-6">
                            <div id="sync1" class="owl-carousel owl-theme">
                                <div class="item rounded-4 overflow-hidden">
                                    <img src="{{ route('product.image', $data->image) }}"
                                        onerror="this.onerror=null;this.src='{{ asset('assets/images/products/empty-shopping-bag.gif') }}'"
                                        alt="modernize-img" class="img-fluid">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="shop-content">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <span
                                        class="badge text-bg-{{ $stockColor }} fs-2 fw-semibold">{{ $data->stock . ' ' . $data->unit }}</span>
                                    <span class="fs-2">In Stock</span>
                                </div>
                                <h4> {{ $data->name }} </h4>
                                <p class="mb-3">{{ $data->description }}</p>
                                <h4 class="mb-3">
                                    Rp. {{ number_format($data->price, 0, '.', '.') }}
                                </h4>
                                <div class="d-flex align-items-center gap-8 pb-4 border-bottom">
                                </div>
                                <div class="d-flex align-items-center gap-8 py-1">
                                    <h6 class="mb-0 fs-4">Category :</h6>
                                    {{ $data->category->name }}
                                </div>
                                <div class="d-flex align-items-center gap-8 py-1">
                                    <h6 class="mb-0 fs-4">SKU :</h6>
                                    {{ $data->sku }}
                                </div>
                                <div class="d-flex align-items-center gap-8 py-1">
                                    <h6 class="mb-0 fs-4">Barcode :</h6>
                                    {{ $data->barcode }}
                                </div>
                                <div class="d-flex align-items-center gap-8 py-1">
                                    <h6 class="mb-0 fs-4">Created At :</h6>
                                    {{ $data->created_at->format('d M Y H:i') }}
                                </div>
                                <div class="d-flex align-items-center gap-8 py-1 border-bottom">
                                    <h6 class="mb-0 fs-4">Last Updated :</h6>
                                    {{ $data->updated_at->format('d M Y H:i') }}
                                </div>
                                <div class="d-sm-flex align-items-center gap-6 pt-8 mb-7">
                                    <button class="btn d-block btn-info px-5 py-8"  onclick="history.back();" >Back</button>
                                    <a href="{{ route('product.edit', $data->id) }}" class="btn d-block btn-warning px-5 py-8">Edit</i></a>
                                    <a onclick="confirmDeletes(this)" class="btn d-block btn-danger px-7 py-8" target="product" data-id="{{ $data->id }}">Delete</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        function handleColorTheme(e) {
            document.documentElement.setAttribute("data-color-theme", e);
        }
    </script>
    <script src="{{ asset('assets/js/highlights/highlight.min.js') }}"></script>
    <script>
        hljs.initHighlightingOnLoad();


        document.querySelectorAll("pre.code-view > code").forEach((codeBlock) => {
            codeBlock.textContent = codeBlock.innerHTML;
        });
    </script>
    <script src="{{ asset('assets/libs/owl.carousel/dist/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('assets/js/apps/productDetail.js') }}"></script>
    <script src="{{ asset('assets/libs/sweetalert2/dist/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/toastr-init.js') }}"></script>
    <script>
        function confirmDeletes(selection) {
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
                        url: `/product/${id}`,
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
                            toastr.success(response.message, response.status)
                            setTimeout(() => {
                                window.location.href = "/product";
                            }, 1000)
                        },
                        error: function(response) {
                            Swal.fire({
                                type: "error",
                                title: response.status,
                                text: response.message,
                            })
                        },
                    })
                }
            })
        }
    </script>
@endsection
