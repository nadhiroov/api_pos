<?php

use Livewire\Component;
use App\Models\Branch;
use App\Models\Category;
use App\Models\Product;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

new 
#[Layout('layouts::app')] #[Title('Ecommerce Shop')] 
class extends Component {
    public $branches;
    public ?int $selectedBranchId = null;
    public ?int $selectedCategoryId = null;
    public string $search = '';

    public function selectBranch(int $branchId): void
    {
        $this->selectedBranchId = $branchId;
        $this->selectedCategoryId = null;
    }

    public function selectCategory(int $categoryId): void
    {
        $this->selectedCategoryId = $categoryId;
    }

    public function resetFilters(): void
    {
        $this->reset(['selectedBranchId', 'selectedCategoryId', 'search']);
    }

    public function addToCart(int $productId): void
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$productId])) {
            $cart[$productId]++;
        } else {
            $cart[$productId] = 1;
        }
        session()->put('cart', $cart);
        $this->dispatch('cart-updated'); // Optional: for toast notifications
    }

    public function getCartCountProperty(): int
    {
        return count(session()->get('cart', []));
    }

    public function render()
    {
        $branches = Branch::orderBy('name')->get();
        $categories = [];
        $products = [];

        if ($this->selectedBranchId) {
            $categories = Category::whereHas('products', function ($query) {
                $query->where('branch_id', $this->selectedBranchId);
            })->orderBy('name')->get();

            $productsQuery = Product::where('branch_id', $this->selectedBranchId);

            if ($this->selectedCategoryId) {
                $productsQuery->where('category_id', $this->selectedCategoryId);
            }

            if ($this->search) {
                $productsQuery->where('name', 'like', '%' . $this->search . '%');
            }

            $products = $productsQuery->orderBy('name')->get();
        }

        $this->branches = $branches;

        return $this->view()->with([
            'categories' => $categories,
            'products' => $products,
        ]);
    }
};
?>

<div style="display: contents">
    <style>
        @media (max-width: 991px) {
            html, body {
                overflow-y: auto !important;
            }
            #main-wrapper {
                overflow: hidden !important; /* Keep wrapper hidden to prevent double scrollbar if necessary, but body usually handles it */
                height: 100vh !important;
            }
            .page-wrapper {
                height: 100vh !important;
                overflow-y: auto !important;
            }
        }
    </style>

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
                <a href="javascript:void(0)" class="sidebartoggler ms-auto text-decoration-none fs-5 d-block d-xl-none">
                    <i class="ti ti-x"></i>
                </a>
            </div>

            <nav class="sidebar-nav scroll-sidebar" style="overflow-y: auto;">
                <div class="shop-filters flex-shrink-0 border-end d-none d-lg-block">
                    <ul class="list-group pt-2 border-bottom rounded-0">
                        <h6 class="my-3 mx-4">Select Merchant</h6>
                        @foreach ($branches as $branch)
                            <li class="list-group-item border-0 p-0 mx-4 mb-2">
                                <a wire:click.prevent="selectBranch({{ $branch->id }})" class="branch-item d-flex align-items-center gap-6 list-group-item-action text-dark px-3 py-6 rounded-1"
                                    href="javascript:void(0)" data-id="{{ $branch->id }}">
                                    <i class="ti ti-circles fs-5"></i>{{ $branch->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                    <ul class="list-group pt-2 border-bottom rounded-0 list-categories">
                        <h6 class="my-3 mx-4">Category</h6>
                        @if ($selectedBranchId)
                            @foreach ($categories as $category)
                                <li class="list-group-item border-0 p-0 mx-4 mb-2">
                                    <a wire:click.prevent="selectCategory({{ $category->id }})"
                                        class="category-item d-flex align-items-center gap-6 list-group-item-action text-dark px-3 py-6 rounded-1 {{ $selectedCategoryId == $category->id ? 'active bg-primary-subtle text-primary' : '' }}"
                                        href="javascript:void(0)">
                                        <i class="ti ti-category fs-5"></i>{{ $category->name }}
                                    </a>
                                </li>
                            @endforeach
                            <li class="list-group-item border-0 p-0 mx-4 mb-2">
                                <a wire:click.prevent="$set('selectedCategoryId', null)"
                                    class="category-item d-flex align-items-center gap-6 list-group-item-action text-dark px-3 py-6 rounded-1 {{ $selectedCategoryId == null ? 'active bg-primary-subtle text-primary' : '' }}"
                                    href="javascript:void(0)">
                                    <i class="ti ti-category fs-5"></i>All Categories
                                </a>
                            </li>
                        @else
                            <span class="fs-3 mx-4 text-muted">Select merchant first</span>
                        @endif
                    </ul>
                    <div class="p-4">
                        <a wire:click.prevent="resetFilters" href="javascript:void(0)" class="btn btn-primary w-100">Reset Filters</a>
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
                                        @if($this->cartCount > 0)
                                            <span class="popup-badge rounded-pill bg-danger text-white fs-2">{{ $this->cartCount }}</span>
                                        @endif
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
                                                <img src="../assets/images/profile/user-1.jpg" class="rounded-circle"
                                                    width="35" height="35" alt="modernize-img" />
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
                                                <img src="../assets/images/profile/user-1.jpg" class="rounded-circle"
                                                    width="80" height="80" alt="modernize-img" />
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
                                    <input type="text" wire:model.live.debounce.300ms="search" class="form-control search-chat py-2 ps-5" id="text-srh"
                                        placeholder="Search Product">
                                    <i
                                        class="ti ti-search position-absolute top-50 start-0 translate-middle-y fs-6 text-dark ms-3"></i>
                                </form>
                            </div>
                            {{-- product --}}
                            <div class="row mb-10" wire:loading.class.delay="opacity-50">
                                @if (!$selectedBranchId)
                                    <div class="row justify-content-center mt-5 mb-5">
                                        <div class="col-md-6 text-center">
                                            <div class="alert alert-secondary text-secondary" role="alert">
                                                Please select a <strong> merchant </strong> from the sidebar to start shopping.
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    @forelse ($products as $product)
                                        <div class="col-sm-6 col-lg-4 col-xl-3 mb-5">
                                            <div class="card hover-img overflow-hidden rounded-2 h-100">
                                                <div class="position-relative">
                                                    <a href="javascript:void(0)">
                                                        <img src="{{ $product->image_url }}" class="card-img-top rounded-0"
                                                            style="height: 200px; object-fit: cover; width: 100%;"
                                                            alt="{{ $product->name }}">
                                                    </a>
                                                    <a wire:click.prevent="addToCart({{ $product->id }})" href="javascript:void(0)"
                                                        class="bg-primary rounded-circle p-2 text-white d-inline-flex position-absolute bottom-0 end-0 mb-n3 me-3"
                                                        data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Add To Cart"
                                                        wire:loading.attr="disabled">
                                                        <i class="ti ti-basket fs-4"></i>
                                                    </a>
                                                </div>
                                                <div class="card-body pt-3 p-4">
                                                    <h6 class="fw-semibold fs-4 text-truncate">{{ $product->name }}</h6>
                                                    <div class="d-flex align-items-center justify-content-between">
                                                        <h6 class="fw-semibold fs-4 mb-0">
                                                            {{ 'Rp ' . number_format($product->price, 0, ',', '.') }}
                                                        </h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="col-12">
                                            <div class="alert alert-info" role="alert">
                                                No products found in this category.
                                            </div>
                                        </div>
                                    @endforelse
                                @endif
                            </div>
                        </div>
                        <div class="offcanvas offcanvas-start" tabindex="-1" id="filtercategory"
                            aria-labelledby="filtercategoryLabel">
                            <div class="offcanvas-body shop-filters w-100 p-0">
                                <ul class="list-group pt-2 border-bottom rounded-0">
                                    <h6 class="my-3 mx-4">Select Merchant</h6>
                                    @foreach ($branches as $branch)
                                        <li class="list-group-item border-0 p-0 mx-4 mb-2">
                                            <a wire:click.prevent="selectBranch({{ $branch->id }})"
                                                class="branch-item-mobile d-flex align-items-center gap-6 list-group-item-action text-dark px-3 py-6 rounded-1 {{ $selectedBranchId == $branch->id ? 'active bg-primary-subtle text-primary' : '' }}"
                                                href="javascript:void(0)" data-id="{{ $branch->id }}">
                                                <i class="ti ti-building-store fs-5"></i>{{ $branch->name }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                                <ul class="list-group pt-2 border-bottom rounded-0 list-categories-mobile">
                                    <h6 class="my-3 mx-4">Category</h6>
                                    @if ($selectedBranchId)
                                        @foreach ($categories as $category)
                                            <li class="list-group-item border-0 p-0 mx-4 mb-2">
                                                <a wire:click.prevent="selectCategory({{ $category->id }})"
                                                    class="category-item-mobile d-flex align-items-center gap-6 list-group-item-action text-dark px-3 py-6 rounded-1 {{ $selectedCategoryId == $category->id ? 'active bg-primary-subtle text-primary' : '' }}"
                                                    href="javascript:void(0)">
                                                    <i class="ti ti-category fs-5"></i>{{ $category->name }}
                                                </a>
                                            </li>
                                        @endforeach
                                        <li class="list-group-item border-0 p-0 mx-4 mb-2">
                                            <a wire:click.prevent="$set('selectedCategoryId', null)"
                                                class="category-item-mobile d-flex align-items-center gap-6 list-group-item-action text-dark px-3 py-6 rounded-1 {{ $selectedCategoryId == null ? 'active bg-primary-subtle text-primary' : '' }}"
                                                href="javascript:void(0)">
                                                <i class="ti ti-category fs-5"></i>All Categories
                                            </a>
                                        </li>
                                    @else
                                        <span class="fs-3 mx-4 text-muted">Select merchant first</span>
                                    @endif
                                </ul>
                                <div class="p-4">
                                    <a wire:click.prevent="resetFilters" href="javascript:void(0)" class="btn btn-primary w-100">Reset Filters</a>
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
        aria-labelledby="offcanvasRightLabel" wire:ignore.self>
        <div class="h-100">
            <livewire:cart />
        </div>
    </div>
</div>
