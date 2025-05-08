<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<div class="form-floating mb-3">
    <input type="text" class="form-control" id="tb-fname" placeholder="Enter Name here" name="name" value="{{ $data->name }}">
    <label for="tb-fname">Category</label>
    <input type="hidden" name="id" value="{{ $data->id }}">
</div>
