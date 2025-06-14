<div class="card w-100">
    <div class="card-body p-4">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <h4 class="card-title fw-semibold">Cashier : {{ $cashier }}</h4>
                <p class="card-subtitle">{{ $date }}</p>
            </div>
        </div>
        <div class="card shadow-none mt-3 mb-0">
            @foreach ($detailTrx['items'] as $item)
                <div class="d-flex align-items-center gap-3 py-3 border-bottom">
                    <img src="{{ route('product.image', $item['image']) }}"
                        onerror="this.onerror=null; this.src='{{ asset('assets/images/products/empty-shopping-bag.gif') }}';"
                        class="rounded-circle" alt="product" width="40" />
                    <div>
                        <h6 class="mb-0 fw-semibold">{{ $item['name'] }}</h6>
                        <span class="fs-2">{{ $item['qty'] }} items @ Rp.
                            {{ number_format($item['price'], 0, '.', '.') }}</span>
                    </div>
                    <div class="ms-auto text-end">
                        <h6 class="mb-0 fw-semibold">Rp.
                            {{ number_format($item['qty'] * $item['price'], 0, '.', '.') }}</h6>
                        <span class="fs-2">Total</span>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="d-flex align-items-center justify-content-between mt-3">
            <h5 class="mb-0 fw-semibold">Payment infromation</h5>
            <h5 class="mb-0 fw-semibold"></h5>
        </div>
        <div class="d-flex align-items-center justify-content-between mt-3">
            <h5 class="mb-0 fw-">Payment Method</h5>
            <h5 class="mb-0 fw-">{{ $detailTrx['payment_method'] }}</h5>
        </div>
        <div class="d-flex align-items-center justify-content-between mt-3">
            <h5 class="mb-0 fw-">Total</h5>
            <h5 class="mb-0 fw-">Rp. {{ number_format($detailTrx['total'], 0, '.', '.') }}</h5>
        </div>
    </div>
