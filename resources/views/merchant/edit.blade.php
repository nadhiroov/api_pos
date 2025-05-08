<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<div class="form-floating mb-3">
    <input type="text" class="form-control" id="tb-fname" placeholder="Enter Name here" name="name" value="{{ $data->name }}">
    <label for="tb-fname">Name</label>
</div>
<div class="form-floating mb-3">
    <textarea class="form-control" id="tb-address" rows="3" name="address" placeholder="Type your address here"> {{ $data->address }} </textarea>
    <label for="tb-address">Address</label>
</div>
<div class="form-floating mb-3">
    <input type="tel" id="tb-phone" class="form-control" value="{{ $data->phone }}" name="phone">
    <label for="tb-phone">Phone</label>
</div>
<input type="hidden" name="id" value="{{ $data->id }}">