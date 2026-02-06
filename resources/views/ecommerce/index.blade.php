<!DOCTYPE html>
<html lang="en" dir="ltr" data-bs-theme="light" data-color-theme="Blue_Theme" data-layout="vertical">

<head>
    <!-- Required meta tags -->
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- Favicon icon-->
    <link rel="shortcut icon" type="image/png" href="{{ asset('assets/images/logos/favicon.png') }}" />

    <!-- Core Css -->
    <link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}" />

    <title>Modernize Bootstrap Admin</title>
</head>

<body class="link-sidebar">
    <!-- Preloader -->
    <div class="preloader">
        <img src="{{ asset('assets/images/logos/favicon.png') }}" alt="loader" class="lds-ripple img-fluid" />
    </div>
    <div id="main-wrapper">
        <aside class="left-sidebar with-vertical">
            <div>
                <!-- ---------------------------------- -->
                <!-- Start Vertical Layout Sidebar -->
                <!-- ---------------------------------- -->
                <div class="brand-logo d-flex align-items-center justify-content-between">
                    <a href="../main/index.html" class="text-nowrap logo-img">
                        <img src="../assets/images/logos/dark-logo.svg" class="dark-logo" alt="Logo-Dark" />
                        <img src="../assets/images/logos/light-logo.svg" class="light-logo" alt="Logo-light" />
                    </a>
                    <a href="javascript:void(0)"
                        class="sidebartoggler ms-auto text-decoration-none fs-5 d-block d-xl-none">
                        <i class="ti ti-x"></i>
                    </a>
                </div>

                <nav class="sidebar-nav scroll-sidebar" data-simplebar>
                    <div class="shop-filters flex-shrink-0 border-end d-none d-lg-block">
                        <ul class="list-group pt-2 border-bottom rounded-0">
                            <h6 class="my-3 mx-4">Select Merchant</h6>
                            @foreach ($branches as $branch)
                                <li class="list-group-item border-0 p-0 mx-4 mb-2">
                                    <a class="branch-item d-flex align-items-center gap-6 list-group-item-action text-dark px-3 py-6 rounded-1"
                                        href="javascript:void(0)" data-id="{{ $branch->id }}">
                                        <i class="ti ti-circles fs-5"></i>{{ $branch->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                        <ul class="list-group pt-2 border-bottom rounded-0 list-categories">
                            <h6 class="my-3 mx-4">Category</h6>
                            <span class="fs-2">Select merchant first</span>
                        </ul>
                        <div class="p-4">
                            <a href="javascript:void(0)" class="btn btn-primary w-100">Reset Filters</a>
                        </div>
                    </div>
                </nav>

                <!-- ---------------------------------- -->
                <!-- Start Vertical Layout Sidebar -->
                <!-- ---------------------------------- -->
            </div>
        </aside>
        <div class="page-wrapper">
            <!--  Header Start -->
            <header class="topbar">
                <div class="with-vertical"><!-- ---------------------------------- -->
                    <!-- Start Vertical Layout Header -->
                    <!-- ---------------------------------- -->
                    <nav class="navbar navbar-expand-lg p-0">
                        <div class="d-block d-lg-none py-4">
                            <a href="../main/index.html" class="text-nowrap logo-img">
                                <img src="../assets/images/logos/dark-logo.svg" class="dark-logo" alt="Logo-Dark" />
                                <img src="../assets/images/logos/light-logo.svg" class="light-logo" alt="Logo-light" />
                            </a>
                        </div>
                        <a class="navbar-toggler nav-icon-hover-bg rounded-circle p-0 mx-0 border-0"
                            href="javascript:void(0)" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                            <i class="ti ti-dots fs-7"></i>
                        </a>
                        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                            <div class="d-flex align-items-center justify-content-between">

                                <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-center">
                                    <!-- ------------------------------- -->
                                    <!-- start shopping cart Dropdown -->
                                    <!-- ------------------------------- -->
                                    <li class="nav-item nav-icon-hover-bg rounded-circle">
                                        <a class="nav-link position-relative" href="javascript:void(0)"
                                            data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight"
                                            aria-controls="offcanvasRight">
                                            <i class="ti ti-basket"></i>
                                            <span class="popup-badge rounded-pill bg-danger text-white fs-2">2</span>
                                        </a>
                                    </li>
                                    <!-- ------------------------------- -->
                                    <!-- end shopping cart Dropdown -->
                                    <!-- ------------------------------- -->

                                    <!-- ------------------------------- -->
                                    <!-- start notification Dropdown -->
                                    <!-- ------------------------------- -->
                                    <li class="nav-item nav-icon-hover-bg rounded-circle dropdown">
                                        <a class="nav-link position-relative" href="javascript:void(0)" id="drop2"
                                            aria-expanded="false">
                                            <i class="ti ti-bell-ringing"></i>
                                            <div class="notification bg-primary rounded-circle"></div>
                                        </a>
                                        <div class="dropdown-menu content-dd dropdown-menu-end dropdown-menu-animate-up"
                                            aria-labelledby="drop2">
                                            <div class="d-flex align-items-center justify-content-between py-3 px-7">
                                                <h5 class="mb-0 fs-5 fw-semibold">Notifications</h5>
                                                <span class="badge text-bg-primary rounded-4 px-3 py-1 lh-sm">5
                                                    new</span>
                                            </div>
                                            <div class="message-body" data-simplebar>
                                                <a href="javascript:void(0)"
                                                    class="py-6 px-7 d-flex align-items-center dropdown-item">
                                                    <span class="me-3">
                                                        <img src="../assets/images/profile/user-2.jpg" alt="user"
                                                            class="rounded-circle" width="48" height="48" />
                                                    </span>
                                                    <div class="w-100">
                                                        <h6 class="mb-1 fw-semibold lh-base">Roman Joined the Team!
                                                        </h6>
                                                        <span class="fs-2 d-block text-body-secondary">Congratulate
                                                            him</span>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="py-6 px-7 mb-1">
                                                <button class="btn btn-outline-primary w-100">See All
                                                    Notifications</button>
                                            </div>
                                        </div>
                                    </li>
                                    <!-- ------------------------------- -->
                                    <!-- end notification Dropdown -->
                                    <!-- ------------------------------- -->

                                    <!-- ------------------------------- -->
                                    <!-- start profile Dropdown -->
                                    <!-- ------------------------------- -->
                                    <li class="nav-item dropdown">
                                        <a class="nav-link pe-0" href="javascript:void(0)" id="drop1"
                                            aria-expanded="false">
                                            <div class="d-flex align-items-center">
                                                <div class="user-profile-img">
                                                    <img src="../assets/images/profile/user-1.jpg"
                                                        class="rounded-circle" width="35" height="35"
                                                        alt="modernize-img" />
                                                </div>
                                            </div>
                                        </a>
                                        <div class="dropdown-menu content-dd dropdown-menu-end dropdown-menu-animate-up"
                                            aria-labelledby="drop1">
                                            <div class="profile-dropdown position-relative" data-simplebar>
                                                <div class="py-3 px-7 pb-0">
                                                    <h5 class="mb-0 fs-5 fw-semibold">User Profile</h5>
                                                </div>
                                                <div class="d-flex align-items-center py-9 mx-7 border-bottom">
                                                    <img src="../assets/images/profile/user-1.jpg"
                                                        class="rounded-circle" width="80" height="80"
                                                        alt="modernize-img" />
                                                    <div class="ms-3">
                                                        <h5 class="mb-1 fs-3">Mathew Anderson</h5>
                                                        <span class="mb-1 d-block">Designer</span>
                                                        <p class="mb-0 d-flex align-items-center gap-2">
                                                            <i class="ti ti-mail fs-4"></i> info@modernize.com
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="message-body">
                                                    <a href="../main/page-user-profile.html"
                                                        class="py-8 px-7 mt-8 d-flex align-items-center">
                                                        <span
                                                            class="d-flex align-items-center justify-content-center text-bg-light rounded-1 p-6">
                                                            <img src="../assets/images/svgs/icon-account.svg"
                                                                alt="modernize-img" width="24" height="24" />
                                                        </span>
                                                        <div class="w-100 ps-3">
                                                            <h6 class="mb-1 fs-3 fw-semibold lh-base">My Profile</h6>
                                                            <span class="fs-2 d-block text-body-secondary">Account
                                                                Settings</span>
                                                        </div>
                                                    </a>
                                                </div>
                                                <div class="d-grid py-4 px-7 pt-8">
                                                    <a href="../main/authentication-login.html"
                                                        class="btn btn-outline-primary">Log Out</a>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <!-- ------------------------------- -->
                                    <!-- end profile Dropdown -->
                                    <!-- ------------------------------- -->
                                </ul>
                            </div>
                        </div>
                    </nav>
                    <!-- ---------------------------------- -->
                    <!-- End Vertical Layout Header -->
                    <!-- ---------------------------------- -->
                </div>

            </header>
            <!--  Header End -->
            <div class="body-wrapper">
                <div class="container-fluid">
                    <div class="card bg-info-subtle shadow-none position-relative overflow-hidden mb-4">
                        <div class="card-body px-4 py-3">
                            <div class="row align-items-center">
                                <div class="col-9">
                                    <h4 class="fw-semibold mb-8">Shop</h4>
                                    <nav aria-label="breadcrumb">
                                        <ol class="breadcrumb">
                                            <li class="breadcrumb-item">
                                                <a class="text-muted text-decoration-none"
                                                    href="../main/index.html">Home</a>
                                            </li>
                                            <li class="breadcrumb-item" aria-current="page">Shop</li>
                                        </ol>
                                    </nav>
                                </div>
                                <div class="col-3">
                                    <div class="text-center mb-n5">
                                        <img src="../assets/images/breadcrumb/ChatBc.png" alt="modernize-img"
                                            class="img-fluid mb-n4" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card position-relative overflow-hidden">
                        <div class="shop-part d-flex w-100">
                            <div class="card-body p-4 pb-0">
                                <div class="d-flex justify-content-between align-items-center gap-6 mb-4">
                                    <a class="btn btn-primary d-lg-none d-flex" data-bs-toggle="offcanvas"
                                        href="#filtercategory" role="button" aria-controls="filtercategory">
                                        <i class="ti ti-menu-2 fs-6"></i>
                                    </a>
                                    <h5 class="fs-5 mb-0 d-none d-lg-block">Products</h5>
                                    <form class="position-relative">
                                        <input type="text" class="form-control search-chat py-2 ps-5"
                                            id="text-srh" placeholder="Search Product">
                                        <i
                                            class="ti ti-search position-absolute top-50 start-0 translate-middle-y fs-6 text-dark ms-3"></i>
                                    </form>
                                </div>
                                {{-- product --}}
                                <div class="row products-lists">
                                </div>
                            </div>
                            <div class="offcanvas offcanvas-start" tabindex="-1" id="filtercategory"
                                aria-labelledby="filtercategoryLabel">
                                <div class="offcanvas-body shop-filters w-100 p-0">
                                    <ul class="list-group pt-2 border-bottom rounded-0">
                                        <h6 class="my-3 mx-4">Select Merchant</h6>
                                        @foreach ($branches as $branch)
                                            <li class="list-group-item border-0 p-0 mx-4 mb-2">
                                                <a class="branch-item-mobile d-flex align-items-center gap-6 list-group-item-action text-dark px-3 py-6 rounded-1"
                                                    href="javascript:void(0)" data-id="{{ $branch->id }}">
                                                    <i class="ti ti-circles fs-5"></i>{{ $branch->name }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                    <ul class="list-group pt-2 border-bottom rounded-0 list-categories-mobile">
                                        <h6 class="my-3 mx-4">Category</h6>
                                    </ul>
                                    <div class="p-4">
                                        <a href="javascript:void(0)" class="btn btn-primary w-100">Reset Filters</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>


        <!--  Shopping Cart -->
        <div class="offcanvas offcanvas-end shopping-cart" tabindex="-1" id="offcanvasRight"
            aria-labelledby="offcanvasRightLabel">
            <div class="offcanvas-header justify-content-between py-4">
                <h5 class="offcanvas-title fs-5 fw-semibold" id="offcanvasRightLabel">
                    Shopping Cart
                </h5>
                <span class="badge bg-primary rounded-4 px-3 py-1 lh-sm">5 new</span>
            </div>
            <div class="offcanvas-body h-100 px-4 pt-0" data-simplebar>
                <ul class="mb-0">
                    <li class="pb-7">
                        <div class="d-flex align-items-center">
                            <img src="../assets/images/products/product-1.jpg" width="95" height="75"
                                class="rounded-1 me-9 flex-shrink-0" alt="modernize-img" />
                            <div>
                                <h6 class="mb-1">Supreme toys cooker</h6>
                                <p class="mb-0 text-muted fs-2">Kitchenware Item</p>
                                <div class="d-flex align-items-center justify-content-between mt-2">
                                    <h6 class="fs-2 fw-semibold mb-0 text-muted">$250</h6>
                                    <div class="input-group input-group-sm w-50">
                                        <button class="btn border-0 round-20 minus p-0 bg-success-subtle text-success"
                                            type="button" id="add1">
                                            -
                                        </button>
                                        <input type="text"
                                            class="form-control round-20 bg-transparent text-muted fs-2 border-0 text-center qty"
                                            placeholder="" aria-label="Example text with button addon"
                                            aria-describedby="add1" value="1" />
                                        <button class="btn text-success bg-success-subtle p-0 round-20 border-0 add"
                                            type="button" id="addo2">
                                            +
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="pb-7">
                        <div class="d-flex align-items-center">
                            <img src="../assets/images/products/product-2.jpg" width="95" height="75"
                                class="rounded-1 me-9 flex-shrink-0" alt="modernize-img" />
                            <div>
                                <h6 class="mb-1">Supreme toys cooker</h6>
                                <p class="mb-0 text-muted fs-2">Kitchenware Item</p>
                                <div class="d-flex align-items-center justify-content-between mt-2">
                                    <h6 class="fs-2 fw-semibold mb-0 text-muted">$250</h6>
                                    <div class="input-group input-group-sm w-50">
                                        <button class="btn border-0 round-20 minus p-0 bg-success-subtle text-success"
                                            type="button" id="add2">
                                            -
                                        </button>
                                        <input type="text"
                                            class="form-control round-20 bg-transparent text-muted fs-2 border-0 text-center qty"
                                            placeholder="" aria-label="Example text with button addon"
                                            aria-describedby="add2" value="1" />
                                        <button class="btn text-success bg-success-subtle p-0 round-20 border-0 add"
                                            type="button" id="addon34">
                                            +
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="pb-7">
                        <div class="d-flex align-items-center">
                            <img src="../assets/images/products/product-3.jpg" width="95" height="75"
                                class="rounded-1 me-9 flex-shrink-0" alt="modernize-img" />
                            <div>
                                <h6 class="mb-1">Supreme toys cooker</h6>
                                <p class="mb-0 text-muted fs-2">Kitchenware Item</p>
                                <div class="d-flex align-items-center justify-content-between mt-2">
                                    <h6 class="fs-2 fw-semibold mb-0 text-muted">$250</h6>
                                    <div class="input-group input-group-sm w-50">
                                        <button class="btn border-0 round-20 minus p-0 bg-success-subtle text-success"
                                            type="button" id="add3">
                                            -
                                        </button>
                                        <input type="text"
                                            class="form-control round-20 bg-transparent text-muted fs-2 border-0 text-center qty"
                                            placeholder="" aria-label="Example text with button addon"
                                            aria-describedby="add3" value="1" />
                                        <button class="btn text-success bg-success-subtle p-0 round-20 border-0 add"
                                            type="button" id="addon3">
                                            +
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
                <div class="align-bottom">
                    <div class="d-flex align-items-center pb-7">
                        <span class="text-dark fs-3">Sub Total</span>
                        <div class="ms-auto">
                            <span class="text-dark fw-semibold fs-3">$2530</span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center pb-7">
                        <span class="text-dark fs-3">Total</span>
                        <div class="ms-auto">
                            <span class="text-dark fw-semibold fs-3">$6830</span>
                        </div>
                    </div>
                    <a href="../main/eco-checkout.html" class="btn btn-outline-primary w-100">Go to shopping
                        cart</a>
                </div>
            </div>
        </div>
    </div>
    <div class="dark-transparent sidebartoggler"></div>
    <!-- Import Js Files -->
    <script src="{{ asset('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/libs/simplebar/dist/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/js/theme/app.init.js') }}"></script>
    <script src="{{ asset('assets/js/theme/theme.js') }}"></script>
    <script src="{{ asset('assets/js/theme/app.min.js') }}"></script>
    <script src="{{ asset('assets/js/theme/sidebarmenu.js') }}"></script>

    <!-- solar icons -->
    <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>

    <!-- highlight.js (code view) -->
    <script src="{{ asset('assets/js/highlights/highlight.min.js') }}"></script>
    <script>
        hljs.initHighlightingOnLoad();
        document.querySelectorAll("pre.code-view > code").forEach((codeBlock) => {
            codeBlock.textContent = codeBlock.innerHTML;
        });
    </script>
    <script src="https://code.jquery.com/jquery-4.0.0.min.js"
        integrity="sha256-OaVG6prZf4v69dPg6PhVattBXkcOWQB62pdZ3ORyrao=" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {
            // web
            $('.branch-item').on('click', function() {
                let branchId = $(this).data('id');

                $.ajax({
                    url: '{{ route('getCategoryByMerchant') }}',
                    type: 'get',
                    data: {
                        branchId: branchId,
                    },
                    success: function(res) {
                        let categories = res.categories
                        let categoryList = $('.list-categories');
                        categoryList.empty();
                        categoryList.append('<h6 class="my-3 mx-4">Category</h6>');
                        for (let i = 0; i < categories.length; i++) {
                            let category = categories[i];
                            let listItem = `<li class="list-group-item border-0 p-0 mx-4 mb-2">
                                                <a class="d-flex align-items-center gap-6 list-group-item-action text-dark px-3 py-6 rounded-1" href="javascript:void(0)">
                                                    <i class="ti ti-circles fs-5"></i>${category.name}
                                                </a>
                                            </li>`;
                            categoryList.append(listItem);
                        }
                        renderProducts(res.products);
                    },
                    error: function(err) {
                        console.error(err);
                    }
                });
            });

            // mobile
            $('.branch-item-mobile').on('click', function() {
                let branchId = $(this).data('id');

                $.ajax({
                    url: '{{ route('getCategoryByMerchant') }}',
                    type: 'get',
                    data: {
                        branchId: branchId,
                    },
                    success: function(res) {
                        let categories = res.categories
                        let categoryListMobile = $('.list-categories-mobile');
                        categoryListMobile.empty();
                        categoryListMobile.append('<h6 class="my-3 mx-4">Category</h6>');
                        for (let i = 0; i < categories.length; i++) {
                            let category = categories[i];
                            let listItem = `<li class="list-group-item border-0 p-0 mx-4 mb-2">
                                                <a class="d-flex align-items-center gap-6 list-group-item-action text-dark px-3 py-6 rounded-1" href="javascript:void(0)">
                                                    <i class="ti ti-circles fs-5"></i>${category.name}
                                                </a>
                                            </li>`;
                            categoryListMobile.append(listItem);
                        }
                        renderProducts(res.products);
                    },
                    error: function(err) {
                        console.error(err);
                    }
                });
            });

            function renderProducts(products) {
                let productList = $('.products-lists');
                productList.empty();
                for (let i = 0; i < products.length; i++) {
                    let product = products[i];
                    let listProduct = `
                                 <div class="col-sm-4 col-xxl-3">
                                        <div class="card hover-img overflow-hidden">
                                            <div class="position-relative">
                                                <a href="../main/eco-shop-detail.html">
                                                    <img src="${product.image_url}" class="card-img-top"
                                                        alt="modernize-img">
                                                </a>
                                                <a href="javascript:void(0)"
                                                    class="text-bg-primary rounded-circle p-2 text-white d-inline-flex position-absolute bottom-0 end-0 mb-n3 me-3"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-bs-title="Add To Cart">
                                                    <i class="ti ti-basket fs-4"></i>
                                                </a>
                                            </div>
                                            <div class="card-body pt-3 p-4">
                                                <h6 class="fs-4">${product.name}</h6>
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <h6 class="fs-4 mb-0"> ${product.price}
                                                    </h6>
                                                    <ul class="list-unstyled d-flex align-items-center mb-0">
                                                        <li>
                                                            <a class="me-1" href="javascript:void(0)">
                                                                <i class="ti ti-star text-warning"></i>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="me-1" href="javascript:void(0)">
                                                                <i class="ti ti-star text-warning"></i>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="me-1" href="javascript:void(0)">
                                                                <i class="ti ti-star text-warning"></i>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="me-1" href="javascript:void(0)">
                                                                <i class="ti ti-star text-warning"></i>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="javascript:void(0)">
                                                                <i class="ti ti-star text-warning"></i>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            `;
                    productList.append(listProduct);
                }
            }

        });
    </script>

</body>

</html>
