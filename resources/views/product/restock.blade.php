<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<div class="form-floating mb-3">
    <input type="text" class="form-control" id="tb-fname" placeholder="Enter Name here" name="quantity" required>
    <label for="tb-fname">Quantity</label>
</div>
<div class="form-floating mb-3">
    <input type="text" class="form-control datepicker" name="date" placeholder="mm/dd/yyyy" />
    <label for="tb-address">Date in</label>
</div>
<div class="form-floating mb-3">
    <input type="text" class="form-control datepicker2" name="expired" placeholder="mm/dd/yyyy" />
    <label for="tb-address">Expiry date</label>
</div>
<input type="hidden" name="product_id" value="{{ $data->id }}">