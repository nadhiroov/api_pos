<?php

use Livewire\Component;
use App\Models\Product;
use Illuminate\Support\Str;

new 
#[Layout('layouts::app')] 
#[Title('Checkout')] 
class extends Component {
    public $order_id;
    public function mount()
    { 
        $this->order_id = Str::uuid()->toString();
    }

    public function render()
    {
        return view('components.⚡checkout');
    }
};
?>

<div style="display: contents">
    <style>
        @media (max-width: 991px) {

            html,
            body {
                overflow-y: auto !important;
            }

            #main-wrapper {
                overflow: hidden !important;
                /* Keep wrapper hidden to prevent double scrollbar if necessary, but body usually handles it */
                height: 100vh !important;
            }

            .page-wrapper {
                height: 100vh !important;
                overflow-y: auto !important;
            }
        }
    </style>

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
                                        <livewire:cart-counter />
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
                <div class="checkout">
                    <div class="card">
                        <div class="card-body p-4">
                            <div class="wizard-content">
                                <form action="#" class="tab-wizard wizard-circle" wire:ignore>
                                    <!-- Step 1 -->
                                    <h6>Cart</h6>
                                    <section>
                                        <livewire:checkout-cart />
                                    </section>
                                    <!-- Step 2 -->
                                    <h6>Billing & address</h6>
                                    <section>
                                        <div class="billing-address-content">
                                            <div class="row" x-data="{ deliveryMethod: 'pickup' }">
                                                <div class="delivery-option btn-group-active  card shadow-none border">
                                                    <div class="card-body p-4">
                                                        <h6 class="mb-3 fw-semibold fs-4">Delivery Option</h6>
                                                        <div class="btn-group flex-column flex-md-row gap-3 w-100" role="group"
                                                            aria-label="Basic radio toggle button group">
                                                            <div class="position-relative form-check btn-custom-fill flex-fill ps-0">
                                                                <input type="radio" class="form-check-input ms-4 round-16" name="deliveryOpt1" id="btnradio1" autocomplete="off" value="pickup" x-model="deliveryMethod">
                                                                <label class="btn btn-outline-primary mb-0 p-3 rounded ps-5 w-100" for="btnradio1">
                                                                    <div class="text-start ps-2">
                                                                        <h6 class="fs-4 fw-semibold mb-0">Pick up</h6>
                                                                        <p class="mb-0 text-muted">Free</p>
                                                                    </div>
                                                                </label>
                                                            </div>
                                                            <div class="position-relative form-check btn-custom-fill flex-fill ps-0">
                                                                <input type="radio" class="form-check-input ms-4 round-16" name="deliveryOpt1" id="btnradio2" autocomplete="off" value="delivery" x-model="deliveryMethod">
                                                                <label class="btn btn-outline-primary mb-0 p-3 rounded ps-5 w-100" for="btnradio2">
                                                                    <div class="text-start ps-2">
                                                                        <h6 class="fs-4 fw-semibold mb-0">Delivery</h6>
                                                                        <p class="mb-0 text-muted">Free</p>
                                                                    </div>
                                                                </label>
                                                            </div>
                                                            <div class="position-relative form-check btn-custom-fill flex-fill ps-0">
                                                                <input type="radio" class="form-check-input ms-4 round-16" name="deliveryOpt1" id="btnradio3" autocomplete="off" value="shipping" x-model="deliveryMethod">
                                                                <label class="btn btn-outline-primary mb-0 p-3 rounded ps-5 w-100" for="btnradio3">
                                                                    <div class="text-start ps-2">
                                                                        <h6 class="fs-4 fw-semibold mb-0">Shipping</h6>
                                                                        <p class="mb-0 text-muted">Shipping fee according to location</p>
                                                                    </div>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Delivery Form (Name, Phone, Room) -->
                                                <div class="col-12 mt-3" x-show="deliveryMethod === 'delivery'" style="display: none;">
                                                    <div class="card shadow-none border">
                                                        <div class="card-body p-4">
                                                            <h6 class="mb-3 fs-4 fw-semibold">Delivery Details</h6>
                                                            <div class="row">
                                                                <div class="col-12 mb-3">
                                                                    <label class="form-label">Name</label>
                                                                    <input type="text" class="form-control" name="delivery_name" placeholder="Enter your name">
                                                                </div>
                                                                <div class="col-12 mb-3">
                                                                    <label class="form-label">Phone Number</label>
                                                                    <input type="text" class="form-control" name="delivery_phone" placeholder="Enter phone number">
                                                                </div>
                                                                <div class="col-12 mb-3">
                                                                    <label class="form-label">Room Location</label>
                                                                    <input type="text" class="form-control" name="delivery_room" placeholder="e.g. Leiden 107, Sorbone principal 4th floor">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Shipping Form (Name, Address, Phone) -->
                                                <div class="col-12 mt-3" x-show="deliveryMethod === 'shipping'" style="display: none;">
                                                    <div class="card shadow-none border">
                                                        <div class="card-body p-4">
                                                            <h6 class="mb-3 fs-4 fw-semibold">Shipping Details</h6>
                                                            <div class="row">
                                                                <div class="col-12 mb-3">
                                                                    <label class="form-label">Name</label>
                                                                    <input type="text" class="form-control" name="shipping_name" placeholder="Enter your name">
                                                                </div>
                                                                <div class="col-12 mb-3">
                                                                    <label class="form-label">Address</label>
                                                                    <textarea class="form-control" name="shipping_address" rows="3" placeholder="Enter full address"></textarea>
                                                                </div>
                                                                <div class="col-12 mb-3">
                                                                    <label class="form-label">Phone Number</label>
                                                                    <input type="text" class="form-control" name="shipping_phone" placeholder="Enter phone number">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <livewire:checkout-summary />
                                    </section>
                                    <!-- Step 3 -->
                                    <h6>Payment</h6>
                                    <section class="payment-method text-center">
                                        <h5 class="fw-semibold fs-5">Thank you for your purchase!</h5>
                                        <h6 class="fw-semibold text-primary mb-7">Your order id:
                                            {{ $order_id }}</h6>
                                        <img src="../assets/images/products/payment-complete.svg" alt="matdash-img"
                                            class="img-fluid mb-4" width="350">
                                        <p class="mb-0 fs-2">We will send you a notification
                                            <br>within 5 minutes after your order is confirmed.
                                        </p>
                                        <div class="d-sm-flex align-items-center justify-content-between my-4">
                                            <a href="../main/eco-shop.html"
                                                class="btn btn-success d-block mb-2 mb-sm-0">Continue Shopping</a>
                                            <a href="javascript:void(0)" class="btn btn-primary d-block">Download
                                                Receipt</a>
                                        </div>
                                    </section>
                                </form>
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
