@extends('layouts.app')


@section('css')
    <link rel="stylesheet" href="{{ asset('assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/libs/sweetalert2/dist/sweetalert2.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/libs/dropzone/dist/min/dropzone.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/quill/dist/quill.snow.css') }}">
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
        <div class="row">
            <div class="col-lg-8 ">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-7">
                            <h4 class="card-title">General</h4>

                            <button class="navbar-toggler border-0 shadow-none d-md-none" type="button"
                                data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">
                                <i class="ti ti-menu fs-5 d-flex"></i>
                            </button>
                        </div>
                            <div class="mb-4">
                                <label class="form-label">Product Name <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" name="name"
                                    placeholder="A product name is required and recommended to be unique.">
                            </div>
                            <div>
                                <label class="form-label">Description</label>
                                <div id="editor">
                                </div>
                                <p class="fs-2 mb-0">Set a description to the product for better visibility.</p>
                            </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-7">Pricing</h4>
                        <div class="mb-7">
                            <label class="form-label">Base Price <span class="text-danger">*</span>
                            </label>
                            <input type="number" class="form-control" placeholder="Product Price" name="price">
                        </div>
                        <div class="mb-7">
                            <label class="form-label">Discount Type</label>
                            <nav>
                                <div class="nav nav-tabs justify-content-between align-items-center gap-9" id="nav-tab"
                                    role="tablist">
                                    <label for="radio1"
                                        class="form-check-label form-check p-3  border gap-2 rounded-2 d-flex flex-fill justify-content-center cursor-pointer"
                                        id="customControlValidation2" id="nav-home-tab" data-bs-toggle="tab"
                                        data-bs-target="#nav-home" aria-controls="nav-home">
                                        <input type="radio" class="form-check-input" name="new-products" id="radio1"
                                            checked>
                                        <span class="fs-4 text-dark">No Discount</span>
                                    </label>
                                    <label for="radio2"
                                        class="form-check-label p-3 form-check border gap-2 rounded-2 d-flex flex-fill justify-content-center cursor-pointer"
                                        id="customControlValidation2" id="nav-profile-tab" data-bs-toggle="tab"
                                        data-bs-target="#nav-profile" aria-controls="nav-profile">
                                        <input type="radio" class="form-check-input" name="new-products" id="radio2" disabled readonly>
                                        <span class="fs-4 text-dark">Percentage %</span>
                                    </label>
                                    <label for="radio3"
                                        class="form-check-label form-check p-3 border gap-2 rounded-2 d-flex flex-fill justify-content-center cursor-pointer"
                                        id="customControlValidation2" id="nav-contact-tab" data-bs-toggle="tab"
                                        data-bs-target="#nav-contact" aria-controls="nav-contact">
                                        <input type="radio" class="form-check-input" name="new-products"
                                            id="radio3" disabled readonly>
                                        <span class="fs-4 text-dark">Fixed Price</span>
                                    </label>
                                </div>
                            </nav>
                            {{-- <div class="tab-content" id="nav-tabContent">
                                <div class="tab-pane fade mt-7" id="nav-profile" role="tabpanel"
                                    aria-labelledby="nav-profile-tab" tabindex="0">
                                    <form class="mt-3">
                                        <div class="form-group">
                                            <label class="form-label">Set Discount Percentage <span
                                                    class="text-danger">*</span>
                                            </label>
                                            <input type="range" class="form-range" id="customRange1" min="0"
                                                max="100" step="10">
                                            <p class="fs-2">Set a percentage discount to be applied on this product.
                                            </p>
                                        </div>
                                    </form>
                                </div>
                                <div class="tab-pane fade mt-7" id="nav-contact" role="tabpanel"
                                    aria-labelledby="nav-contact-tab" tabindex="0">
                                    <div class="mb-7">
                                        <label class="form-label">Fixed Discounted Price <span
                                                class="text-danger">*</span>
                                        </label>
                                        <input type="text" class="form-control" placeholder="Discounted Price">
                                        <p class="fs-2">Set the discounted product price. The product will be reduced
                                            at the
                                            determined fixed price.</p>
                                    </div>
                                </div>
                            </div> --}}
                        </div>
                    </div>
                </div>
                <div class="form-actions mb-5">
                    <button type="submit" class="btn btn-primary">
                        Save changes
                    </button>
                    <button type="button" class="btn bg-danger-subtle text-danger ms-6">
                        Cancel
                    </button>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="offcanvas-md offcanvas-end overflow-auto" tabindex="-1" id="offcanvasRight"
                    aria-labelledby="offcanvasRightLabel">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-7">Product Details</h4>
                            <div class="mt-7 mb-3">
                                <label class="form-label">Branch</label>
                                <select class="select2-branch form-control">
                                    <option></option>
                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Unit</label>
                                <select class="select2-unit form-control" name="unit">
                                    <option></option>
                                    <option value="pcs">pcs</option>
                                    <option value="kg">kg</option>
                                    <option value="gr">gr</option>
                                    <option value="box">box</option>
                                    <option value="portion">portion</option>
                                    <option value="set">set</option>
                                </select>
                            </div>
                            <div class="mt-7 mb-3">
                                <label class="form-label">Category</label>
                                <select class="select2-category form-control">
                                    <option></option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="button" class="btn bg-primary-subtle text-primary ">
                                <span class="fs-4 me-1">+</span>
                                Create New Category
                            </button>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-7">Image</h4>
                            {{-- <form action="#" class="dropzone dz-clickable mb-2"> --}}
                                <div class="dz-default dz-message">
                                    <button class="dz-button" type="button">Drop Thumbnail here
                                        to upload</button>
                                </div>
                            {{-- </form> --}}
                            <p class="fs-2 text-center mb-0">
                                Set the product thumbnail image. Only *.png, *.jpg and *.jpeg image files are accepted.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection


    @section('script')
        <script src="{{ asset('assets/libs/dropzone/dist/min/dropzone.min.js') }}"></script>
        <script src="{{ asset('assets/libs/select2/dist/js/select2.full.min.js') }}"></script>
        <script src="{{ asset('assets/libs/select2/dist/js/select2.min.js') }}"></script>
        <script src="{{ asset('assets/js/forms/select2.init.js') }}"></script>
        <script src="{{ asset('assets/libs/quill/dist/quill.min.js') }}"></script>
        <script>
            $(document).ready(function() {
                console.log("Hello World!");
                var quill = new Quill("#editor", {
                    theme: "snow",
                })

                $(".select2-branch").select2({
                    placeholder: "Select a merchant",
                })

                $(".select2-category").select2({
                    placeholder: "Select a category",
                })

                $(".select2-unit").select2({
                    placeholder: "Select an unit",
                })
            })
        </script>
    @endsection
