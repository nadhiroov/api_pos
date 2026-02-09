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
