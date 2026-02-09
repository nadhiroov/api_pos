<div style="overflow-y: auto; height: 100%;">
    <div class="offcanvas-header justify-content-between py-4">
        <h5 class="offcanvas-title fs-5 fw-semibold" id="offcanvasRightLabel">
            Shopping Cart
        </h5>
        <span class="badge bg-primary rounded-4 px-3 py-1 lh-sm">{{ $this->cartCount }} items</span>
    </div>
    <div class="offcanvas-body h-100 px-4 pt-0">
        <ul class="mb-0">
            @forelse($this->cartItems as $item)
                <li class="pb-7" wire:key="cart-item-{{ $item->id }}">
                    <div class="d-flex align-items-center">
                        <img src="{{ $item->image_url }}" width="95" height="75"
                            class="rounded-1 me-9 flex-shrink-0" alt="{{ $item->name }}" style="object-fit: cover" />
                        <div class="w-100">
                            <h6 class="mb-1">{{ $item->name }}</h6>
                            <p class="mb-0 text-muted fs-2">{{ $item->category->name ?? 'Product' }}</p>
                            <div class="d-flex align-items-center justify-content-between mt-2">
                                <h6 class="fs-2 fw-semibold mb-0 text-muted">{{ 'Rp ' . number_format($item->price, 0, ',', '.') }}</h6>
                                <div class="input-group input-group-sm w-50">
                                    <button wire:click.prevent="updateQuantity({{ $item->id }}, {{ $item->quantity - 1 }})" class="btn border-0 round-20 minus p-0 bg-success-subtle text-success"
                                        type="button">
                                        -
                                    </button>
                                    <input type="text"
                                        class="form-control round-20 bg-transparent text-muted fs-2 border-0 text-center qty"
                                        value="{{ $item->quantity }}" readonly />
                                    <button wire:click.prevent="addToCart({{ $item->id }})" class="btn text-success bg-success-subtle p-0 round-20 border-0 add"
                                        type="button">
                                        +
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
            @empty
                <li class="pb-7 text-center">
                    <p class="text-muted">Your cart is empty.</p>
                </li>
            @endforelse
        </ul>
        <div class="align-bottom pb-4">
            <div class="d-flex align-items-center pb-7">
                <span class="text-dark fs-3">Sub Total</span>
                <div class="ms-auto">
                    <span class="text-dark fw-semibold fs-3">{{ 'Rp ' . number_format($this->cartTotal, 0, ',', '.') }}</span>
                </div>
            </div>
            <div class="d-flex align-items-center pb-7">
                <span class="text-dark fs-3">Total</span>
                <div class="ms-auto">
                    <span class="text-dark fw-semibold fs-3">{{ 'Rp ' . number_format($this->cartTotal, 0, ',', '.') }}</span>
                </div>
            </div>
            <a href="/checkout" class="btn btn-outline-primary w-100">Checkout</a>
        </div>
    </div>
</div>
