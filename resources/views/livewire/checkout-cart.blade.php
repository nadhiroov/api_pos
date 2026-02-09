<div>
    <div class="table-responsive">
    <table class="table align-middle text-nowrap mb-0">
        <thead class="fs-2">
        <tr>
            <th>Product</th>
            <th>Quantity</th>
            <th class="text-end">Price</th>
        </tr>
        </thead>
        <tbody>
        @forelse($this->cartItems as $item)
        <tr>
            <td class="border-bottom-0">
            <div class="d-flex align-items-center gap-3 overflow-hidden">
                <img src="{{ $item->image_url }}" alt="{{ $item->name }}" class="img-fluid rounded" width="80" style="object-fit: cover">
                <div>
                <h6 class="fw-semibold fs-4 mb-0">{{ $item->name }}</h6>
                <p class="mb-0">{{ $item->category->name ?? 'Product' }}</p>
                <a wire:click.prevent="removeFromCart({{ $item->id }})" href="javascript:void(0)" class="text-danger fs-4">
                    <i class="ti ti-trash"></i>
                </a>
                </div>
            </div>
            </td>
            <td class="border-bottom-0">
            <div class="input-group input-group-sm flex-nowrap rounded">
                <button wire:click.prevent="updateQuantity({{ $item->id }}, {{ $item->quantity - 1 }})" class="btn minus min-width-40 py-0 border-end border-muted border-end-0 text-muted" type="button">
                <i class="ti ti-minus"></i>
                </button>
                <input type="text" class="min-width-40 flex-grow-0 border border-muted text-muted fs-3 fw-semibold form-control text-center qty" value="{{ $item->quantity }}" readonly>
                <button wire:click.prevent="updateQuantity({{ $item->id }}, {{ $item->quantity + 1 }})" class="btn min-width-40 py-0 border border-muted border-start-0 text-muted add" type="button">
                <i class="ti ti-plus"></i>
                </button>
            </div>
            </td>
            <td class="text-end border-bottom-0">
            <h6 class="fs-4 fw-semibold mb-0">{{ 'Rp ' . number_format($item->total_price, 0, ',', '.') }}</h6>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="3" class="text-center py-5">
                <p class="fs-4 text-muted">Your cart is empty.</p>
                <a href="/ecommerce" class="btn btn-primary">Go Shopping</a>
            </td>
        </tr>
        @endforelse
        </tbody>
    </table>
    </div>
    <div class="order-summary border rounded p-4 my-4">
    <div class="p-3">
        <h5 class="fs-5 fw-semibold mb-4">Order Summary</h5>
        <div class="d-flex justify-content-between mb-4">
        <p class="mb-0 fs-4">Sub Total</p>
        <h6 class="mb-0 fs-4 fw-semibold">{{ 'Rp ' . number_format($this->cartTotal, 0, ',', '.') }}</h6>
        </div>
        <!--
        <div class="d-flex justify-content-between mb-4">
        <p class="mb-0 fs-4">Discount 5%</p>
        <h6 class="mb-0 fs-4 fw-semibold text-danger">-$14</h6>
        </div>
        -->
        <div class="d-flex justify-content-between mb-4">
        <p class="mb-0 fs-4">Shipping</p>
        <h6 class="mb-0 fs-4 fw-semibold">Free</h6>
        </div>
        <div class="d-flex justify-content-between">
        <h6 class="mb-0 fs-4 fw-semibold">Total</h6>
        <h6 class="mb-0 fs-5 fw-semibold">{{ 'Rp ' . number_format($this->cartTotal, 0, ',', '.') }}</h6>
        </div>
    </div>
    </div>
</div>
